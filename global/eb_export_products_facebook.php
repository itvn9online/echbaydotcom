<?php



/*
* Cấu trúc dữ liệu sản phẩm theo tiêu chuẩn của google
https://support.google.com/merchants/topic/6324338?hl=vi&ref_topic=7294998
https://developers.facebook.com/docs/marketing-api/dynamic-product-ads/product-catalog?__mref=message_bubble#feed-format
*/



// lấy nhóm cấp 1 của sản phẩm này
function WGR_rss_get_parent_cat ( $id ) {
	$cat = get_term( $id );
//	print_r( $cat );
	
	// xem nhóm có bị khóa bởi plugin EchBay không
	if ( _eb_get_cat_object( $id, '_eb_category_hidden', 0 ) != 1 ) {
		// nếu không có nhóm cha -> lấy luôn id nhóm
		if ( $cat->parent == 0 ) {
			return $id;
		}
		// có nhóm cha -> còn tiếp tục lấy
		else {
			return WGR_rss_get_parent_cat ( $cat->parent );
		}
	}
	
	// mặc định thì trả về 0
	return 0;
}




//
//$rssCacheFilter = 'rss-' . $export_type;
$rss_content = _eb_get_static_html ( $rssCacheFilter, '', '', 300 );
//$rss_content = false;
if ($rss_content == false || isset($_GET['wgr_real_time'])) {
	
	
	// The advertising or marketing medium, for example: cpc, banner, email newsletter
	$utm_medium = 'cpc';
	
	// The individual campaign name, slogan, promo code, etc. for a product
	$utm_campaign = 'rss' . $user_export;
	
	// Used to differentiate similar content, or links within the same ad. For example, if you have two call-to-action links within the same email message, you can use utm_content and set different values for each so you can tell which version is more effective
	$utm_content = date( 'Y-m', date_time );
	
	//
	if ( $export_type == 'google' ) {
		$utm_source = 'google';
	}
	// mặc định là facebook
	else {
		// Identify the advertiser, site, publication, etc. that is sending traffic to your property, for example: google, newsletter4, billboard
		$utm_source = 'facebook';
	}
	
	//
	$url_tracking = '?utm_source=' . $utm_source . '&amp;utm_medium=' . $utm_medium . '&amp;utm_campaign=' . $utm_campaign . '&amp;utm_content=' . $utm_content;
	
	
	
	//
$rss_content = '';


//
$rss_brand = explode( '.', $_SERVER['HTTP_HOST'] );
$rss_brand = $rss_brand[0];


//
$before_price = '';
$after_price = '';
if ( $__cf_row['cf_current_price_before'] == 1 ) {
	$before_price = $__cf_row['cf_current_sd_price'] . ' ';
}
else {
	$after_price = ' ' . $__cf_row['cf_current_sd_price'];
}



//
$cache_google_product = array();
$cache_cat_gender = array();

// xác định giới tính cho sản phẩm
$arr_sex_lang_xml = array(
	0 => 'unisex',
	1 => 'male',
	2 => 'female'
);


//
foreach ( $sql as $v ) {
	$p_link = _eb_p_link( $v->ID );
	
	
	// tìm ID của nhóm -> mặc định lấy theo request
	$ant_id = $by_cat_id;
	
	// nếu không có request -> lấy động theo sản phẩm
	if ( $ant_id == 0 ) {
		$post_categories = wp_get_post_categories( $v->ID );
//		print_r( $post_categories );
		if ( ! empty( $post_categories ) ) {
			
			//
			if ( count( $post_categories ) == 1 ) {
				$ant_id = $post_categories[0];
			}
			else {
				// tìm nhóm chính trước
				foreach($post_categories as $c){
					// có thì trả về luôn
					if ( _eb_get_cat_object( $c, '_eb_category_primary', 0 ) > 0
					|| _eb_get_cat_object( $c, '_eb_category_google_product' ) != '' ) {
						$ant_id = $c;
						break;
					}
				}
				
				// nếu không có -> tìm nhóm cấp 2 trước
				if ( $ant_id == 0 ) {
					foreach($post_categories as $c){
						$a = WGR_rss_get_parent_cat( $c );
						
						// nếu có nhóm cha -> đây là nhóm cấp 2 -> dừng luôn
						if ( $a > 0 ) {
							$ant_id = $c;
							break;
						}
					}
					
					// vẫn không có -> đành lấy nhóm cấp 1 -> lấy nhóm đầu tiên
					if ( $ant_id == 0 ) {
						$ant_id = $post_categories[0];
					}
				}
			}
		}
	}
	
	//
	$google_product_category = '';
	$cat_gender = '';
	if ( $ant_id > 0 ) {
		if ( isset( $cache_google_product[ $ant_id ] ) ) {
			$google_product_category = $cache_google_product[ $ant_id ];
		}
		else {
			$google_product_category = _eb_get_cat_object( $ant_id, '_eb_category_google_product' );
			
			// lấy nhóm mặc định nếu chưa có
			if ( $google_product_category == '' ) {
				$google_product_category = $__cf_row['cf_google_product_category'];
			}
			$cache_google_product[ $ant_id ] = $google_product_category;
		}
		
		// lấy giới tính của nhóm sản phẩm
		if ( isset( $cache_cat_gender[ $ant_id ] ) ) {
			$cat_gender = $cache_cat_gender[ $ant_id ];
		}
		else {
			$cat_gender = _eb_get_cat_object( $ant_id, '_eb_category_gender', 0 );
			
			$cache_cat_gender[ $ant_id ] = $cat_gender;
		}
	}
	
	
	// các dữ liệu khác, có thì mới cho vào
	$add_on_data = '';
	
	
	//
	$price = _eb_float_only( _eb_get_post_object( $v->ID, '_eb_product_oldprice', 0 ) );
	$new_price = _eb_float_only( _eb_get_post_object( $v->ID, '_eb_product_price', 0 ) );
	
	// chỉnh lại giá về 1 thông số
	// có khuyến mại
	if ( $new_price > 0 && $price > $new_price ) {
		$add_on_data .= '<g:sale_price>' . $before_price . $new_price . $after_price . '</g:sale_price>' . "\n";
	}
	else if ( $price < $new_price ) {
		$price = $new_price;
	}
	
	// cho bản của google
	if ( $export_type == 'google' ) {
		$add_on_data .= '<g:item_group_id>' . $ant_id . '</g:item_group_id>' . "\n";
	}
	
	// product category
	if ( $google_product_category != '' ) {
		$add_on_data .= '<g:google_product_category>' . $google_product_category . '</g:google_product_category>' . "\n";
	}
	
	//
	$post_gender = _eb_get_post_object( $v->ID, '_eb_product_gender', 0 );
	if ( $post_gender == 0 ) {
		$post_gender = $cat_gender;
	}
	
	//
	$color = _eb_get_post_object( $v->ID, '_eb_product_color' );
	if ( $color != '' ) {
		$add_on_data .= '<g:color><![CDATA[' . $color . ']]></g:color>' . "\n";
	}
	
	
	
	//
$rss_content .= '<item>
<g:id>' . $v->ID . '</g:id>
<g:availability>' . ( _eb_get_post_object( $v->ID, '_eb_product_buyer', 0 ) < _eb_get_post_object( $v->ID, '_eb_product_quantity', 0 ) ? 'in stock' : 'out of stock' ) . '</g:availability>
<g:condition>new</g:condition>
<g:description><![CDATA[' . $v->post_excerpt . ']]></g:description>
<g:image_link>' . _eb_get_post_img( $v->ID ) . '</g:image_link>
<g:link>' . $p_link . $url_tracking . '</g:link>
<g:title><![CDATA[' . $v->post_title . ']]></g:title>
<g:price>' . $before_price . $price . $after_price . '</g:price>' . $add_on_data . '
<g:gender><![CDATA[' . $arr_sex_lang_xml[$post_gender] . ']]></g:gender>
<g:brand>' . $rss_brand . '</g:brand>
</item>';

}




// tổng hợp lại
$rss_content = '<?xml version="1.0" encoding="UTF-8"?>
<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
<channel>
<title><![CDATA[' . web_name . ']]></title>
<link>' . web_link . '</link>
<description><![CDATA[' . $__cf_row['cf_description'] . ']]></description>
<last_update>' . date( 'r', date_time ) . '</last_update>
<code_copyright>Cache by EchBay.com - WebGiaRe.org</code_copyright>
<facebook_document><![CDATA[https://developers.facebook.com/docs/marketing-api/dynamic-product-ads/product-catalog?__mref=message_bubble#feed-format]]></facebook_document>
<google_document><![CDATA[https://support.google.com/merchants/topic/6324338?hl=vi&ref_topic=7294998]]></google_document>
<google_product_category><![CDATA[https://support.google.com/merchants/answer/6324436?hl=vi]]></google_product_category>
<google_product_taxonomy><![CDATA[https://www.google.com/basepages/producttype/taxonomy-with-ids.en-US.txt]]></google_product_taxonomy>
' . $rss_content . '
</channel>
</rss>';
	
	
	
	
	// ép lưu cache
	_eb_get_static_html ( $rssCacheFilter, $rss_content, '', 60 );
	
}


//
echo $rss_content;



