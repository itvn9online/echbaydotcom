<?php

$__cf_row ['cf_title'] = EBE_get_lang('shopping_cart');
$group_go_to[] = ' <li><a href="./cart" rel="nofollow">' . $__cf_row ['cf_title'] . '</a></li>';


//
$__cf_row ['cf_title'] .= ': ' . web_name . ' - ' . $__cf_row ['cf_abstract'];
$__cf_row ['cf_keywords'] = $__cf_row ['cf_title'];
$__cf_row ['cf_description'] = $__cf_row ['cf_title'];



//
$cart_list_id = _eb_getCucki( 'eb_cookie_cart_list_id' );
echo '<!-- ' . $cart_list_id . ' -->' . "\n";
$new_id = 0;
if ( isset($_GET['id']) && ( $new_id = (int)$_GET['id'] ) > 0 ) {
//	echo $new_id;
	$cart_list_id .= ',' . $new_id;
}
//echo $cart_list_id . '<br>' . "\n";

$cart_list = '';
$cart_total = 0;



//
$cart_node_html = EBE_get_lang('cart_node');
// mặc định là lấy theo file HTML -> act
if ( trim( $cart_node_html ) == 'cart_node' ) {
	$cart_node_html = EBE_get_page_template( 'cart_node' );
}





if ( $cart_list_id != '' && substr( $cart_list_id, 0, 1 ) == ',' ) {
	
	// v1
	/*
	$sql = _eb_load_post_obj( 100, array(
		'post__in' => explode( ',', substr( $cart_list_id, 1 ) ),
	) );
	*/
	
	// v2 -> lấy theo lệnh mysql ví wordpres nó lấy cả sản phẩm sticky vào
	$sql = _eb_q("SELECT *
	FROM
		`" . wp_posts . "`
	WHERE
		ID IN (" . substr( $cart_list_id, 1 ) . ")
		AND post_status = 'publish'
		AND post_type = 'post'");
//	print_r( $sql );
	
	//
	$select_soluong = '';
	for ( $i = 1; $i < 11; $i++ ) {
		$select_soluong .= '<option value="' . $i . '">' . $i . '</option>';
	}
	$select_soluong = '<select name="t_soluong[{cart.post_id}]" data-name="{cart.post_id}" class="change-select-quantity">' . $select_soluong . '</select>';
	
	// v1
	/*
	while ( $sql->have_posts() ) {
		
		$sql->the_post();
		
		$post = $sql->post;
		*/
	foreach ( $sql as $post ) {
		
		//
//		print_r($post);
		
		//
//		$p_link = get_the_permalink( $post->ID );
		$p_link = _eb_p_link( $post->ID );
		
		$trv_masanpham = _eb_get_post_object( $post->ID, '_eb_product_sku', $post->ID );
		
		/*
		$trv_img = wp_get_attachment_image_src ( get_post_thumbnail_id( $post->ID ), $__cf_row['cf_product_thumbnail_size'] );
		$trv_img = ! empty( $trv_img[0] ) ? esc_url( $trv_img[0] ) : _eb_get_post_meta( $post->ID, '_eb_product_avatar', true );
		*/
		$trv_img = _eb_get_post_img( $post->ID, 'medium' );
		
//		$trv_giamoi = (int) _eb_get_post_meta( $post->ID, '_eb_product_price', true );
		$trv_giaban = _eb_float_only( _eb_get_post_object( $post->ID, '_eb_product_oldprice' ) );
		
		$trv_giamoi = _eb_float_only( _eb_get_post_object( $post->ID, '_eb_product_price' ) );
		
		$soLuong = 1;
		$total_line = $trv_giamoi * $soLuong;
		$cart_total += $total_line;
		
		
		
		//
		$post_categories = wp_get_post_categories( $post->ID );
//		print_r($post_categories);
		
		//
	    $cat = get_term( $post_categories[0] );
//		print_r( $cat );
		
		//
		$c_link = _eb_c_link($cat->term_id);
		$c_name = $cat->name;
		
		
		
		//
		$animate_id = 'tr_cart_' . $post->ID;
		
		// product size
		$product_size = _eb_get_post_object( $post->ID, '_eb_product_size' );
		if ( $product_size != '' ) {
			if ( substr( $product_size, 0, 1 ) == ',' ) {
				$product_size = substr( $product_size, 1 );
			}
//			$product_size = str_replace( '"', '\"', $product_size );
		}
		
		//
		$product_list_color = _eb_get_post_object( $post->ID, '_eb_product_list_color' );
		$product_list_color = str_replace( ' src=', ' data-src=', $product_list_color );
		$product_list_color = str_replace( ' data-src=', ' src="' . EB_URL_OF_PLUGIN . 'images-global/_blank.png" data-src=', $product_list_color );
		
		//
		$product_color_name = _eb_get_post_object( $post->ID, '_eb_product_color' );
		if ( $product_color_name != '' ) {
			$product_color_name = ' - (' . $product_color_name . ')';
		}
		
		
		//
		$cart_list .= EBE_html_template( $cart_node_html, array(
			'tmp.trv_masanpham' => $trv_masanpham,
			'tmp.c_link' => $c_link,
			'tmp.trv_img' => $trv_img,
			'tmp.p_link' => $p_link,
			'tmp.post->post_title' => $post->post_title,
			'tmp.product_color_name' => $product_color_name,
			'tmp.cart_mausac' => str_replace( '"', '&quot;', EBE_get_lang('cart_mausac') ),
			'tmp.product_list_color' => $product_list_color,
			'tmp.cart_kichco' => str_replace( '"', '&quot;', EBE_get_lang('cart_kichco') ),
			'tmp.product_size' => $product_size,
			'tmp.trv_giamoi' => EBE_add_ebe_currency_class ( $trv_giamoi ),
			'tmp.c_name' => $c_name,
			'tmp.animate_id' => $animate_id,
			'tmp.num_trv_giaban' => $trv_giaban,
			'tmp.num_trv_giamoi' => $trv_giamoi,
			'tmp.trv_giamoi' => EBE_add_ebe_currency_class ( $trv_giamoi ),
			'tmp.select_soluong' => str_replace( '{cart.post_id}', $post->ID, $select_soluong ),
			'tmp.total_line' => EBE_add_ebe_currency_class ( $total_line ),
			'tmp.post->ID' => $post->ID
		));
		
		/*
		$cart_list .= '
<tr data-id="' . $post->ID . '" data-old-price="' . $trv_giaban . '" data-price="' . $trv_giamoi . '" data-sku="' . $trv_masanpham . '" id="' . $animate_id . '" class="each-for-set-cart-value">
	<td class="cf">
		<div class="lf f30 fullsize-if-mobile">
			<div><a href="' . $p_link . '"><img src="' . $trv_img . '" height="90" /></a></div>
			<br>
		</div>
		<div class="lf f70 cart-div-margin fullsize-if-mobile">
			<div><a href="' . $p_link . '" class="bold upper medium blackcolor"><span class="get-product-name-for-cart">' . $post->post_title . '</span><span class="show-product-color-name' . $post->ID . '">' . $product_color_name . '</span></a></div>
			
			<div data-id="' . $post->ID . '" data-name="' . str_replace( '"', '&quot;', EBE_get_lang('cart_mausac') ) . '" class="show-list-color cf l25 d-none">' . $product_list_color . '</div>
			
			<div data-id="' . $post->ID . '" data-name="' . str_replace( '"', '&quot;', EBE_get_lang('cart_kichco') ) . '" class="show-list-size cf l25 d-none">' . $product_size . '</div>
			
			<div class="bold big show-if-mobile">' . EBE_add_ebe_currency_class ( $trv_giamoi ) . '</div>
			
			<div><a href="' . $c_link . '" class="upper blackcolor">' . $c_name . '</a></div>
			
			<div class="cart-table-remove"><i onClick="_global_js_eb.cart_remove_item(' . $post->ID . ', \'' . $animate_id . '\');" class="fa fa-remove cur"></i></div>
			
			<input type="hidden" name="t_muangay[]" value="' . $post->ID . '" />
		</div>
	</td>
	<td class="bold big hide-if-mobile cart-price-inline">' . EBE_add_ebe_currency_class ( $trv_giamoi ) . '</td>
	<td>' . str_replace( '{cart.post_id}', $post->ID, $select_soluong ) . '</td>
	<td class="bold big hide-if-mobile cart-total-inline">' . EBE_add_ebe_currency_class ( $total_line ) . '</td>
</tr>';
		*/
		
		//
	}
	
	//
	wp_reset_postdata();
}



// kiểm tra nếu có file html riêng -> sử dụng html riêng
/*
$check_html_rieng = EB_THEME_HTML . 'cart.html';
$thu_muc_for_html = EB_THEME_HTML;
if ( ! file_exists($check_html_rieng) ) {
	$thu_muc_for_html = EB_THEME_PLUGIN_INDEX . 'html/';
}

//
$main_content = EBE_str_template ( 'cart.html', array (
	'tmp.js' => 'var new_cart_auto_add_id=' . $new_id . ';',
	
	'tmp.cart_list' => $cart_list,
	'tmp.cart_total' => EBE_add_ebe_currency_class ( $cart_total ),
), $thu_muc_for_html );
*/

//
$chinhsach = '';
if ( EBE_get_lang('url_chinhsach') != '#' ) {
	$chinhsach = str_replace( '{tmp.url_chinhsach}', EBE_get_lang('url_chinhsach'), EBE_get_lang('chinhsach') );
	
	$chinhsach = '
	<li>
		<p class="l19 small">
			<input type="checkbox" name="t_dongy" checked>
			' . $chinhsach . '
		</p>
	</li>';
}




//
$custom_lang_html = EBE_get_lang('cart_html');
// mặc định là lấy theo file HTML -> act
if ( trim( $custom_lang_html ) == $act ) {
	$custom_lang_html = EBE_get_page_template( $act );
}

//
$main_content = EBE_html_template( $custom_lang_html, array(
	'tmp.js' => 'var new_cart_auto_add_id=' . $new_id . ',co_ma_giam_gia=' . WGR_check_discount_code_exist() . ';',
	
	'tmp.cart_list' => $cart_list,
	'tmp.cart_total' => EBE_add_ebe_currency_class ( $cart_total ),
	
	'tmp.cart_hoten' => EBE_get_lang('cart_hoten'),
	'tmp.cart_dienthoai' => EBE_get_lang('cart_dienthoai'),
	'tmp.cart_pla_dienthoai' => EBE_get_lang('cart_pla_dienthoai'),
	'tmp.cart_diachi' => EBE_get_lang('cart_diachi'),
	'tmp.cart_diachi2' => EBE_get_lang('cart_diachi2'),
	'tmp.cart_tinhthanh' => EBE_get_lang('cart_tinhthanh'),
	'tmp.cart_tinhthanh2' => EBE_get_lang('cart_tinhthanh2'),
	'tmp.cart_ghichu' => EBE_get_lang('cart_ghichu'),
	'tmp.cart_vidu' => EBE_get_lang('cart_vidu'),
	'tmp.cart_discount_code' => EBE_get_lang('cart_discount_code'),
	'tmp.cart_gui' => EBE_get_lang('cart_gui'),
	
	'tmp.cart_soluong' => EBE_get_lang('cart_soluong'),
	'tmp.cart_str_list' => EBE_get_lang('cart_str_list'),
	'tmp.cart_price' => EBE_get_lang('cart_price'),
	'tmp.cart_str_total' => EBE_get_lang('cart_str_total'),
	'tmp.cart_str_totals' => EBE_get_lang('cart_str_totals'),
	'tmp.cart_is_null' => EBE_get_lang('cart_is_null'),
	'tmp.cart_continue' => EBE_get_lang('cart_continue'),
	'tmp.cart_customer_info' => EBE_get_lang('cart_customer_info'),
	'tmp.cart_payment_method' => EBE_get_lang('cart_payment_method'),
	
	//
	'tmp.cart_payment_cod' => EBE_get_lang('cart_payment_cod'),
	'tmp.cart_payment_tt' => EBE_get_lang('cart_payment_tt'),
	'tmp.cart_payment_bank' => EBE_get_lang('cart_payment_bank'),
	'tmp.cart_payment_bk' => EBE_get_lang('cart_payment_bk'),
	'tmp.cart_payment_nl' => EBE_get_lang('cart_payment_nl'),
	'tmp.cart_payment_pp' => EBE_get_lang('cart_payment_pp'),
	
	// slogan
	'tmp.lang_ebslogan1' => EBE_get_lang('ebslogan1'),
	'tmp.lang_ebslogan2' => EBE_get_lang('ebslogan2'),
	'tmp.lang_ebslogan3' => EBE_get_lang('ebslogan3'),
	'tmp.lang_ebslogan4' => EBE_get_lang('ebslogan4'),
	
	'tmp.cf_required_phone_cart' => ( $__cf_row['cf_required_phone_cart'] == 1 ) ? ' aria-required="true" required' : '',
	'tmp.cf_required_name_cart' => ( $__cf_row['cf_required_name_cart'] == 1 ) ? ' aria-required="true" required' : '',
	'tmp.cf_required_email_cart' => ( $__cf_row['cf_required_email_cart'] == 1 ) ? ' aria-required="true" required' : '',
	'tmp.cf_required_address_cart' => ( $__cf_row['cf_required_address_cart'] == 1 ) ? ' aria-required="true" required' : '',
	
	'tmp.chinhsach' => $chinhsach
) );

