<?php
/*
* custom-meta-box là các dữ liệu sẽ được bổ sung cho từng hạng mục bài viết, vd: giá cũ, giá mới...
*/




/*
* in ra HTML của các mảng đã nhập vào
* Khai báo callback
* @param $post là đối tượng WP_Post để nhận thông tin của post
*/
function WGR_echo_label_for_edit_form ( $str ) {
	if ( $str != '' ) {
		return '<div><em class="small">' . $str . '</em></div>';
	}
	return '';
}

function EchBayPrintHTMLOutput( $arr_box, $arr_type, $post ) {
	global $eb_arr_placeholder_custom_meta_box;
	
	// với các thông số ẩn thì cho vào 1 biến, rồi in ra sau
	$str_hidden = '';
	
	echo '<table class="eb-public-table eb-editer-table">';
	
	foreach ( $arr_box as $k => $v ) {
//		$val = get_post_meta( $post->ID, $k, true );
		$val = _eb_get_post_object( $post->ID, $k );
		$tai = isset( $arr_type[ $k ] ) ? $tai = $arr_type[ $k ] : 'text';
		
		//
		$other_attr = '';
		
		//
//		$other_attr .= 'placeholder="' . ( isset($eb_arr_placeholder_custom_meta_box[$k]) ? $eb_arr_placeholder_custom_meta_box[$k] : '' ) . '"';
		if ( ! isset( $eb_arr_placeholder_custom_meta_box[$k] ) ) {
			$eb_arr_placeholder_custom_meta_box[$k] = '';
		}
		/*
		else {
			$eb_arr_placeholder_custom_meta_box[$k] = '<em class="small">' . $eb_arr_placeholder_custom_meta_box[$k] . '</em>';
		}
		*/
		
		// chiều dài rộng cho td
//		$td1 = 28;
//		$td2 = 100 - $td1;
		
		//
//		echo gettype( $tai ) . '<br>';
		
		// nếu là array -> hiển thị dưới dạng select option
		if ( gettype( $tai ) == 'array' ) {
			echo '<tr data-row="' . $k . '">';
			echo '<td class="t"><label for="' . $k . '"><strong>' . $v . '</strong></label></td>';
			
			echo '<td class="i">';
			echo '<select id="' . $k . '" name="' . $k . '">';
			
			foreach ( $tai as $k2 => $v2 ) {
				$sl = '';
				if ( $k2 == $val ) {
					$sl = ' selected="selected"';
				}
				
				//
				echo '<option value="' . $k2 . '"' . $sl . '>' . $v2 . '</option>';
			}
			
			echo '</select>';
			echo WGR_echo_label_for_edit_form( $eb_arr_placeholder_custom_meta_box[$k] );
			echo '</td>';
			
			echo '</tr>';
		}
		else if ( $tai == 'textarea_one' ) {
//			echo $val . 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
			
			/*
			echo '
			<div><strong>' . $v . '</strong></div>
			<div><textarea id="' . $k . '" name="' . $k . '">' .esc_attr( $val ). '</textarea></div>
			<br>';
			*/
			
			//
//			echo '<div><strong>' . $v . '</strong></div>';
			
			//
			// cho chỉnh sửa khi SEO echbay đang bật
//			if ( cf_on_off_echbay_seo == 1 ) {
//				wp_editor( html_entity_decode($val, ENT_QUOTES, 'UTF-8'), $k );
				wp_editor( $val, $k );
				
				// từ wp 5.++ thì phần submit textarea đang bị lỗi -> cần xử lý lại chút
				echo '<div class="d-none"><textarea id="' . $k . '_forv5" name="' . $k . '_forv5" class="get-html-for-v5-submit">' .esc_attr( $val ). '</textarea></div>';
//			}
			// còn lại thì hiển thị thông thường
//			else {
//			}
			
//			echo '<br>';
		}
		else if ( $tai == 'hidden' ) {
			$str_hidden .= '<input type="' . $tai . '" id="' . $k . '" name="' . $k . '" value="' .esc_attr( $val ). '" />';
		}
		else if ( $tai == 'checkbox' ) {
			echo '
			<tr data-row="' . $k . '">
				<td class="t"><label for="' . $k . '"><strong>' . $v . '</strong></label></td>
				<td class="i"><label for="' . $k . '"><input type="' . $tai . '" id="' . $k . '" name="' . $k . '" value="' . $val . '" /> ' . $eb_arr_placeholder_custom_meta_box[$k] . '</label></td>
			</tr>';
		}
		// input text
		else {
			echo '
			<tr data-row="' . $k . '">
				<td class="t"><label for="' . $k . '"><strong>' . $v . '</strong></label></td>
				<td class="i"><input type="' . $tai . '" id="' . $k . '" name="' . $k . '" value="' .esc_attr( $val ). '" ' . $other_attr . ' class="m" />' . WGR_echo_label_for_edit_form( $eb_arr_placeholder_custom_meta_box[$k] ) . '</td>
			</tr>';
		}
	}
	
	echo '</table>';
	
	echo $str_hidden;
	
}




/*
* Định dạng dữ liệu
*/
$arr_product_giohethan = array(
	'' => '[ Không chọn ]'
);
for ( $i = 23; $i > 5; $i-- ) {
	$j = $i;
	if ( $j < 10 ) {
		$j = '0' . $j;
	}
	$j .= ':59';
	$arr_product_giohethan[$j] = $j;
}


//
$eb_arr_type_custom_meta_box = array(
	// post
	'_eb_product_status' => $arr_eb_product_status,
	'_eb_product_gender' => $arr_eb_product_gender,
//	'_eb_product_color' => 'number',
//	'_eb_product_sku' => 'number',
//	'_eb_product_oldprice' => 'number',
//	'_eb_product_price' => 'number',
	'_eb_product_buyer' => 'number',
	'_eb_product_quantity' => 'number',
//	'_eb_product_avatar' => 'hidden',
	'_eb_product_giohethan' => $arr_product_giohethan,
	'_eb_product_noindex' => 'checkbox',
	'_eb_product_chinhhang' => 'checkbox',
	
	'_eb_product_size' => 'hidden',
	'_eb_product_searchkey' => 'hidden',
	'_eb_product_rating_value' => 'hidden',
	'_eb_product_rating_count' => 'hidden',
	
	// textarea_one -> một một mình một ô
	// textarea -> đừng chung với các thuộc tính khác -> style để tạo giãn cách
	'_eb_product_gallery' => 'textarea_one',
	'_eb_product_dieukien' => 'textarea_one',
	'_eb_product_noibat' => 'textarea_one',
	'_eb_product_list_color' => 'textarea_one',
	
	// Nội dung phụ cho phần blog, thi thoảng có site sử dụng
	'_eb_blog_2content' => 'textarea_one',
	
	'_eb_product_leech_source' => 'hidden',
	
	// ads
	'_eb_ads_status' => $arr_eb_ads_status,
	'_eb_ads_target' => 'checkbox',
	'_eb_ads_name' => 'checkbox',
//	'_eb_ads_for_post' => 'number',
//	'_eb_ads_for_category' => 'number',
	
	// category
	'_eb_category_status' => $arr_eb_category_status,
	'_eb_category_gender' => $arr_eb_category_gender,
	'_eb_category_order' => 'number',
	'_eb_category_leech_url' => 'hidden',
	'_eb_category_primary' => 'checkbox',
	'_eb_category_content' => 'textarea',
	'_eb_category_noindex' => 'checkbox',
	'_eb_category_hidden' => 'checkbox',
);

// Một số thuộc tính chỉ hiển thị với admin cấp cao
if ( mtv_id == 1 ) {
	$eb_arr_type_custom_meta_box['_eb_product_leech_source'] = 'text';
//	$eb_arr_type_custom_meta_box['_eb_product_avatar'] = 'text';
	
	$eb_arr_type_custom_meta_box['_eb_category_leech_url'] = 'text';
}



// Hiển thị câu ghi chú đối với meta box
$eb_arr_placeholder_custom_meta_box = array(
	'_eb_product_css' => 'Bạn có thể thêm một class class CSS vào đây, class này sẽ xuất hiện trong thẻ BODY, dùng để tùy chỉnh CSS cho các post, page... cụ thể',
	'_eb_product_avatar' => 'Ảnh đại diện dự phòng, dùng để làm <a href="' . admin_link . 'admin.php?page=eb-config_theme&tab=theme-list&support_tab=cf_list_avt_hover" target="_blank">hiệu ứng chuyển ảnh đại diện khi di chuột</a> hoặc  chuyển dữ liệu và làm demo sẽ tiện',
	'_eb_product_old_url' => 'Khi người dùng truy cập vào URL này, hệ thống sẽ redirect 301 về URL mới',
	'_eb_product_supper_shop' => 'Nhập vào URL mà bạn muốn chuyển tới khi người dùng bấm vào nút [Mua ngay] trong trang chi tiết sản phẩm (Mặc định sẽ thêm vào giỏ hoặc mở popup mua ngay).',
	'_eb_product_video_url' => 'Một số giao diện hỗ trợ video youtube (nếu có)',
	
	'_eb_category_avt' => 'Ảnh đại diện của phân nhóm, kích thước khuyến nghị là 500x400 pixel. Ảnh này sẽ xuất hiện khi chia sẻ nhóm lên các mạng xã hội như: Facebook, Twiter...',
	'_eb_category_favicon' => 'Favicon là một ảnh nhỏ hơn, kích thước khuyến nghị là 64x64 pixel. Ảnh này thường dùng trong các menu nếu CSS của menu có hỗ trợ chức năng.',
	'_eb_category_custom_css' => 'Là nơi nhập class CSS riêng để tiện cho việc thay đổi CSS theo từng danh mục cha, con...',
	'_eb_category_title' => 'Với định dạng màu sắc thì nhập mã màu vào (bao gồm cả dấu #)',
	'_eb_category_google_product' => 'Tạo nhóm sản phẩm theo tiêu chuẩn của Google. <a href="https://support.google.com/merchants/answer/6324436?hl=vi" target="_blank" rel="nofollow">Tìm hiểu thêm...</a> Nên sử dụng nhóm sản phẩm dạng ID (số) theo <a href="https://www.google.com/basepages/producttype/taxonomy-with-ids.en-US.txt" target="_blank" rel="nofollow">danh sách này</a>, và chỉ sử dụng <strong>nhóm cấp 1</strong> thay vì các nhóm con chi tiết.',
	'_eb_category_order' => 'Số càng lớn thì độ ưu tiên càng cao, nhóm sẽ được ưu tiên xuất hiện trước',
	'_eb_category_old_url' => 'Khi người dùng truy cập vào URL này, hệ thống sẽ redirect 301 về URL mới',
//	'_eb_category_primary' => 'Sử dụng khi bạn muốn các post_option sử dụng chung với category. Nếu là nhóm chính, sẽ có nhiều quyền ưu tiên hơn, VD: tạo sản phẩm liên quan...',
	'_eb_category_primary' => 'Trong các module lấy sản phẩm, tạo menu... nếu có sự xuất hiện của nhóm chính thì sản phẩm trong nhóm đó sẽ được ưu tiên hơn.',
	'_eb_category_noindex' => 'Ngăn chặn các công cụ tìm kiếm đánh chỉ mục Danh mục này.',
	'_eb_category_hidden' => 'Trong một số trường hợp, bạn tạm thời không muốn nhóm này xuất hiện thì sử dụng chức năng này để ẩn nó đi.',
	
	'_eb_ads_target' => 'Mặc định, các URL trong quảng cáo sẽ được mở đè lên tab hiện tại, đánh dấu và lưu lại để mở URL trong tab mới.',
	'_eb_ads_name' => 'Khi chế độ này được thiết lập, dù quảng cáo có chiếu xạ tới bài viết hay phân nhóm nào đó thì tên và tóm tắt của quảng cáo vẫn được giữ nguyên.',
	'_eb_ads_for_post' => 'Nhập vào ID của Sản phẩm hoặc bài Blog mà bạn muốn quảng cáo này trỏ tới, khi đó, các dữ liệu như: Ảnh đại diện, tiêu đề, URL sẽ được lấy từ Sản phẩm/ Blog thay vì lấy từ quảng cáo.',
	'_eb_ads_for_category' => 'Nhập vào ID của Danh mục Sản phẩm hoặc Danh mục Blog mà bạn muốn quảng cáo này trỏ tới, khi đó, các dữ liệu như: Ảnh đại diện, tiêu đề, URL sẽ được lấy từ Danh mục Sản phẩm/ Blog thay vì lấy từ quảng cáo.',
	'_eb_ads_video_url' => 'Bạn có thể nhập vào URL video trên Youtube (Ví dụ: <strong>https://youtu.be/{ID}</strong>) hoặc URL video MP4, các định dạng khác hiện chưa được hỗ trợ.',
	
	'_eb_product_ngayhethan' => 'Nếu thời gian hết hạn được thiết lập, sản phẩm sẽ hiển thị chữ cháy hàng khi hết hạn.',
	'_eb_product_leech_sku' => 'Chức năng dùng để kiểm soát các tin đã tồn tại từ phiên bản cũ hơn (thường sử dụng khi chuyển đổi code khác sang wordpress).',
	
//	'_eb_product_size' => '',
	'_eb_product_giohethan' => 'Thiết lập giờ hết hạn cụ thể cho phần Ngày hết hạn ở trên. Nếu để trống trường này, giờ hết hạn sẽ là cuối ngày hôm đó (23:59)',
	'_eb_product_noindex' => 'Ngăn chặn các công cụ tìm kiếm đánh chỉ mục Bài viết này',
	'_eb_product_chinhhang' => 'Đánh dấu để hiển thị nhãn Đảm bảo chính hãng',
	
);
$eb_arr_placeholder_custom_meta_box['_eb_product_leech_source'] = $eb_arr_placeholder_custom_meta_box['_eb_product_old_url'];
$eb_arr_placeholder_custom_meta_box['_eb_category_leech_url'] = $eb_arr_placeholder_custom_meta_box['_eb_category_old_url'];




/*
* Nhiều form cần dùng đoạn này
*/
$eb_meta_custom_meta_box = array(
	'_eb_product_title' => WGR_admin_lang('_eb_product_title'),
	'_eb_product_keywords' => WGR_admin_lang('_eb_product_keywords'),
	'_eb_product_description' => WGR_admin_lang('_eb_product_description'),
	'_eb_product_noindex' => WGR_admin_lang('_eb_product_noindex'),
	
	//
	'_eb_product_avatar' => WGR_admin_lang('_eb_product_avatar'),
	'_eb_product_css' => WGR_admin_lang('_eb_product_css')
//	'_eb_product_leech_source' => WGR_admin_lang('_eb_product_leech_source')
);




/*
* Form sản phẩm
*/
$eb_arr_custom_meta_box = array(
	'_eb_product_status' => WGR_admin_lang('_eb_product_status'),
	'_eb_product_gender' => WGR_admin_lang('_eb_product_gender'),
	'_eb_product_color' => WGR_admin_lang('_eb_product_color'),
	'_eb_product_size' => WGR_admin_lang('_eb_product_size'),
	'_eb_product_searchkey' => WGR_admin_lang('_eb_product_searchkey'),
	'_eb_product_sku' => WGR_admin_lang('_eb_product_sku'),
	'_eb_product_leech_sku' => WGR_admin_lang('_eb_product_leech_sku'),
	'_eb_product_oldprice' => WGR_admin_lang('_eb_product_oldprice'),
	'_eb_product_price' => WGR_admin_lang('_eb_product_price'),
	'_eb_product_buyer' => WGR_admin_lang('_eb_product_buyer'),
	'_eb_product_quantity' => WGR_admin_lang('_eb_product_quantity'),
	'_eb_product_ngayhethan' => WGR_admin_lang('_eb_product_ngayhethan'),
	'_eb_product_giohethan' => WGR_admin_lang('_eb_product_giohethan'),
	'_eb_product_chinhhang' => WGR_admin_lang('_eb_product_chinhhang'),
	
	'_eb_product_rating_value' => WGR_admin_lang('_eb_product_rating_value'),
	'_eb_product_rating_count' => WGR_admin_lang('_eb_product_rating_count')
);

$eb_arr_gallery_meta_box = array(
	'_eb_product_gallery' => WGR_admin_lang('_eb_product_gallery')
);

$eb_arr_list_color_meta_box = array(
	'_eb_product_list_color' => WGR_admin_lang('_eb_product_list_color')
);

$eb_arr_dieukien_meta_box = array(
//	'_eb_product_dieukien' => WGR_admin_lang('_eb_product_dieukien')
	'_eb_product_dieukien' => EBE_get_lang('_eb_product_dieukien')
);

$eb_arr_noibat_meta_box = array(
//	'_eb_product_noibat' => WGR_admin_lang('_eb_product_noibat')
	'_eb_product_noibat' => EBE_get_lang('_eb_product_noibat')
);

$eb_arr_blog_2content_meta_box = array(
	'_eb_blog_2content' => WGR_admin_lang('_eb_blog_2content')
);

// thông tin phụ của trang sản phẩm
$eb_arr_phu_meta_box = array(
	'_eb_product_supper_shop' => WGR_admin_lang('_eb_product_supper_shop'),
	'_eb_product_leech_source' => WGR_admin_lang('_eb_product_leech_source'),
	'_eb_product_old_url' => WGR_admin_lang('_eb_product_old_url'),
	'_eb_product_video_url' => WGR_admin_lang('_eb_product_video_url')
);


function EchBayThongTinOutput( $post ) {
	global $eb_arr_custom_meta_box;
	global $eb_arr_type_custom_meta_box;
	
	//
	EchBayPrintHTMLOutput( $eb_arr_custom_meta_box, $eb_arr_type_custom_meta_box, $post );
}

function EchBayGalleryOutput( $post ) {
	global $eb_arr_gallery_meta_box;
	global $eb_arr_type_custom_meta_box;
	
	//
	EchBayPrintHTMLOutput( $eb_arr_gallery_meta_box, $eb_arr_type_custom_meta_box, $post );
}

function EchBayListColorOutput( $post ) {
	global $eb_arr_list_color_meta_box;
	global $eb_arr_type_custom_meta_box;
	
	//
	EchBayPrintHTMLOutput( $eb_arr_list_color_meta_box, $eb_arr_type_custom_meta_box, $post );
}

function EchBayDieukienOutput( $post ) {
	global $eb_arr_dieukien_meta_box;
	global $eb_arr_type_custom_meta_box;
	
	//
	EchBayPrintHTMLOutput( $eb_arr_dieukien_meta_box, $eb_arr_type_custom_meta_box, $post );
}

function EchBayNoibatOutput( $post ) {
	global $eb_arr_noibat_meta_box;
	global $eb_arr_type_custom_meta_box;
	
	//
	EchBayPrintHTMLOutput( $eb_arr_noibat_meta_box, $eb_arr_type_custom_meta_box, $post );
}

function EchBayPhuOutput( $post ) {
	global $eb_arr_phu_meta_box;
	global $eb_arr_type_custom_meta_box;
	
	//
	EchBayPrintHTMLOutput( $eb_arr_phu_meta_box, $eb_arr_type_custom_meta_box, $post );
}

function EchBayMetaOutput( $post ) {
	global $eb_meta_custom_meta_box;
	global $eb_arr_type_custom_meta_box;
	
	//
	EchBayPrintHTMLOutput( $eb_meta_custom_meta_box, $eb_arr_type_custom_meta_box, $post );
}

function EchBayBlog2Content ( $post ) {
	global $eb_arr_blog_2content_meta_box;
	global $eb_arr_type_custom_meta_box;
	
	//
	EchBayPrintHTMLOutput( $eb_arr_blog_2content_meta_box, $eb_arr_type_custom_meta_box, $post );
}




/*
* Form quảng cáo
*/
$eb_ads_custom_meta_box = array(
	'_eb_ads_for_post' => WGR_admin_lang('_eb_ads_for_post'),
	'_eb_ads_for_category' => WGR_admin_lang('_eb_ads_for_category'),
	'_eb_ads_url' => WGR_admin_lang('_eb_ads_url'),
	'_eb_ads_target' => WGR_admin_lang('_eb_ads_target'),
	'_eb_ads_name' => WGR_admin_lang('_eb_ads_name'),
	'_eb_ads_video_url' => WGR_admin_lang('_eb_ads_video_url'),
	'_eb_ads_status' => WGR_admin_lang('_eb_ads_status'),
	'_eb_product_avatar' => WGR_admin_lang('_eb_product_avatar')
);

function EchBayQuangCaoOutput( $post ) {
	global $eb_ads_custom_meta_box;
	global $eb_arr_type_custom_meta_box;
	
	//
	EchBayPrintHTMLOutput( $eb_ads_custom_meta_box, $eb_arr_type_custom_meta_box, $post );
}





/*
* Khai báo meta box cho các post
*/
function EchBayMetaBox () {
	// thông tin bổ sung cho sản phẩm
	add_meta_box( 'eb-product-info', 'Thông tin cơ bản', 'EchBayThongTinOutput', 'post' );
	
	// Các textarea khác
	add_meta_box( 'eb-product-gallery', 'Thư viện ảnh', 'EchBayGalleryOutput', 'post' );
	add_meta_box( 'eb-product-list-color', 'Danh sách màu sắc', 'EchBayListColorOutput', 'post' );
	add_meta_box( 'eb-product-dieukien', EBE_get_lang('_eb_product_dieukien'), 'EchBayDieukienOutput', 'post' );
	add_meta_box( 'eb-product-noibat', EBE_get_lang('_eb_product_noibat'), 'EchBayNoibatOutput', 'post' );
	
	// thẻ META cho sản phẩm
	if ( cf_on_off_echbay_seo == 1 ) {
		add_meta_box( 'eb-product-meta', 'Tùy chỉnh nội dung thẻ META', 'EchBayMetaOutput', 'post' );
		
		add_meta_box( 'eb-blog-meta', 'Tùy chỉnh nội dung thẻ META', 'EchBayMetaOutput', EB_BLOG_POST_TYPE );
		
		// thẻ META cho page
		add_meta_box( 'eb-page-meta', 'Tùy chỉnh nội dung thẻ META', 'EchBayMetaOutput', 'page' );
	}
	
	add_meta_box( 'eb-product-bosung', 'Thông tin bổ sung', 'EchBayPhuOutput', 'post' );
	
	
	// thẻ META cho blog
	add_meta_box( 'eb-blog-bosung', 'Thông tin bổ sung', 'EchBayPhuOutput', EB_BLOG_POST_TYPE );
	add_meta_box( 'eb-blog-2content', 'Nội dung phụ', 'EchBayBlog2Content', EB_BLOG_POST_TYPE );
	
	
	// thông tin bổ sung cho quảng cáo
	add_meta_box( 'eb-ads-info', 'Thông tin cơ bản', 'EchBayQuangCaoOutput', 'ads' );
}
add_filter( 'add_meta_boxes', 'EchBayMetaBox' );




/*
* Lưu dữ liệu meta box khi nhập vào
* @param post_id là ID của post hiện tại
*/
function EchBayThongTinRunSave ( $arr_box, $post_id ) {
	
	global $eb_arr_type_custom_meta_box;
	
	// cần phải lấy cả mảng dữ liệu cũ để save vào 1 thể, nếu không sẽ bị thiếu
//	$arr_save = _eb_get_object_post_meta( $post_id );
	
	foreach ( $arr_box as $k => $v ) {
		
		// lọc mã html với các input thường
		$loc_html = isset( $eb_arr_type_custom_meta_box[ $k ] ) ? $eb_arr_type_custom_meta_box[ $k ] : '';
		
		//
		if ( isset( $_POST[ $k ] ) ) {
//			echo $k . '<br>' . "\n";
			
			$val = $_POST[ $k ];
			
			// Bỏ qua với textarea
			if ( $loc_html == 'textarea_one' || $loc_html == 'textarea' ) {
//				$val = sanitize_textarea_field( $val );
//				$val = urlencode( $val );
//				$val = 'aaaaaaaaaaaaa';
				
				//
				if ( isset( $_POST[ $k .  '_forv5' ] ) ) {
					$val = $_POST[ $k .  '_forv5' ];
					
					//
					if ( trim( strip_tags( $val, '<img>' ) ) == '' ) {
						$val = '';
					}
				}
			}
			else if ( $loc_html == 'checkbox' ) {
				$val = 1;
			}
			else {
				if ( $k == '_eb_product_oldprice'
				|| $k == '_eb_product_price' ) {
					$val = _eb_float_only( $val, 2 );
				}
				else {
					$val = sanitize_text_field( $val );
				}
			}
			
			//
			$val = trim( WGR_stripslashes( $val ) );
			
			//
			WGR_update_meta_post( $post_id, $k, $val );
			if ( $val == '' ) {
				delete_post_meta( $post_id, $k );
			}
			
			//
//			$arr_save[ $k ] = $val;
			
			//
//			$arr_save[ $k ] = addslashes( $val );
		}
		// thử kiểm tra với checkbox
		else if ( $loc_html == 'checkbox' ) {
			// không có -> set là 0 luôn
//			WGR_update_meta_post( $post_id, $k, 0 );
			delete_post_meta( $post_id, $k );
		}
		
	}
	
	//
//	WGR_update_meta_post( $post_id, eb_post_obj_data, $arr_save );
	
//	print_r( $arr_save ); exit();
	
}


function EchBayThongTinSave ( $post_id ) {
	global $eb_meta_custom_meta_box;
	global $eb_arr_custom_meta_box;
	global $eb_ads_custom_meta_box;
	global $eb_arr_blog_2content_meta_box;
	
	global $eb_arr_gallery_meta_box;
	global $eb_arr_list_color_meta_box;
	global $eb_arr_dieukien_meta_box;
	global $eb_arr_noibat_meta_box;
	global $eb_arr_phu_meta_box;
	
	EchBayThongTinRunSave( $eb_meta_custom_meta_box, $post_id );
	
	EchBayThongTinRunSave( $eb_arr_custom_meta_box, $post_id );
	
	EchBayThongTinRunSave( $eb_ads_custom_meta_box, $post_id );
	
	EchBayThongTinRunSave( $eb_arr_gallery_meta_box, $post_id );
	
	EchBayThongTinRunSave( $eb_arr_list_color_meta_box, $post_id );
	
	EchBayThongTinRunSave( $eb_arr_dieukien_meta_box, $post_id );
	
	EchBayThongTinRunSave( $eb_arr_noibat_meta_box, $post_id );
	
	EchBayThongTinRunSave( $eb_arr_phu_meta_box, $post_id );
	
	EchBayThongTinRunSave( $eb_arr_blog_2content_meta_box, $post_id );
}
add_filter( 'save_post', 'EchBayThongTinSave' );












/*
* Custom fields for category extra
* https://en.bainternet.info/wordpress-category-extra-fields/
*/
//if ( cf_on_off_echbay_seo == 1 ) {



// cho phép HTML trong category
/*
foreach ( array( 'pre_term_description' ) as $filter ) {
	remove_filter( $filter, 'wp_filter_kses' );
}

foreach ( array( 'term_description' ) as $filter ) {
	remove_filter( $filter, 'wp_kses_data' );
}
*/



//
$arr_category_custom_fields = array();

//
$arr_category_custom_fields['_eb_category_avt'] = WGR_admin_lang('_eb_category_avt');
$arr_category_custom_fields['_eb_category_favicon'] = WGR_admin_lang('_eb_category_favicon');
$arr_category_custom_fields['_eb_category_custom_css'] = WGR_admin_lang('_eb_category_custom_css');
$arr_category_custom_fields['_eb_category_gender'] = WGR_admin_lang('_eb_product_gender');
$arr_category_custom_fields['_eb_category_status'] = WGR_admin_lang('_eb_category_status');
$arr_category_custom_fields['_eb_category_order'] = WGR_admin_lang('_eb_category_order');
$arr_category_custom_fields['_eb_category_content'] = WGR_admin_lang('_eb_category_content');


// Để tránh xung đột và thừa thãi -> chỉ kích hoạt cột liên quan đến SEO khi người dùng chọn bật nó, ngoài ra thì bỏ qua
if ( cf_on_off_echbay_seo == 1 ) {
	$arr_category_custom_fields['_eb_category_title'] = WGR_admin_lang('_eb_product_title');
	$arr_category_custom_fields['_eb_category_keywords'] = WGR_admin_lang('_eb_product_keywords');
	$arr_category_custom_fields['_eb_category_description'] = WGR_admin_lang('_eb_product_description');
	$arr_category_custom_fields['_eb_category_google_product'] = WGR_admin_lang('_eb_category_google_product');
	$arr_category_custom_fields['_eb_category_noindex'] = WGR_admin_lang('_eb_product_noindex');
}

// thuộc tính này luôn tồn tại cho category
$arr_category_custom_fields['_eb_category_hidden'] = WGR_admin_lang('_eb_category_hidden');
$arr_category_custom_fields['_eb_category_old_url'] = WGR_admin_lang('_eb_product_old_url');
$arr_category_custom_fields['_eb_category_leech_url'] = WGR_admin_lang('_eb_product_leech_source');
$arr_category_custom_fields['_eb_category_primary'] = WGR_admin_lang('_eb_category_primary');




// các trường dữ liệu
$arr_category_coupon_custom_fields = array();
// định dạng dữ liệu (mặc định là text)
$eb_arr_type_coupon_custom_meta_box = array();
// ghi chú
$eb_arr_placeholder_coupon_custom_meta_box = array();

//
$arr_category_coupon_custom_fields['_eb_category_coupon_giagiam'] = 'Giảm giá sản phẩm cố định';
//$eb_arr_type_coupon_custom_meta_box['_eb_category_coupon_giagiam'] = '';
$eb_arr_placeholder_coupon_custom_meta_box['_eb_category_coupon_giagiam'] = 'Nhập số tiền mã bạn muốn giảm trực tiếp cho mỗi đơn hàng';

$arr_category_coupon_custom_fields['_eb_category_coupon_phantramgiam'] = 'Giảm giá theo phần trăm';
$eb_arr_type_coupon_custom_meta_box['_eb_category_coupon_phantramgiam'] = 'number';
$eb_arr_placeholder_coupon_custom_meta_box['_eb_category_coupon_phantramgiam'] = 'Nhập số phần trăm mà bạn muốn giảm cho mỗi đơn hàng (tham số này sẽ phủ định tham số [Giảm giá sản phẩm cố định] ở trên nếu chúng được thiết lập cùng nhau)';

$arr_category_coupon_custom_fields['_eb_category_coupon_ngayhethan'] = 'Ngày hết hạn mã ưu đãi <span class="redcolor">*</span>';
//$eb_arr_type_coupon_custom_meta_box['_eb_category_coupon_ngayhethan'] = '';
$eb_arr_placeholder_coupon_custom_meta_box['_eb_category_coupon_ngayhethan'] = 'Mã sẽ hiển thị thông báo hết hạn khi qua ngày này. Thời gian tính vào giờ phút cuối cùng của ngày đó, ví dụ bạn thiết lập là: <strong>' . date('Y-m-d', date_time) . '</strong>, mã sẽ hết hạn vào lúc: <strong>' . date('Y-m-d', date_time) . ' 23:59</strong><br><span class="orgcolor">* Đây là trường bắt buộc phải điền, và bạn có thể đặt 5 năm, 10 năm tùy ý...</span>';

$arr_category_coupon_custom_fields['_eb_category_coupon_toithieu'] = 'Chi tiêu tối thiểu';
//$eb_arr_type_coupon_custom_meta_box['_eb_category_coupon_toithieu'] = '';
$eb_arr_placeholder_coupon_custom_meta_box['_eb_category_coupon_toithieu'] = 'Khi thông số này được thiết lập, đơn hàng phải đạt tổng thanh toán lớn hơn hoặc bằng số này thì Mã giảm giá mới được kích hoạt.';

$arr_category_coupon_custom_fields['_eb_category_coupon_toida'] = 'Chi tiêu tối đa';
//$eb_arr_type_coupon_custom_meta_box['_eb_category_coupon_toida'] = '';
$eb_arr_placeholder_coupon_custom_meta_box['_eb_category_coupon_toida'] = 'Khi thông số này được thiết lập, đơn hàng có tổng giá trị lớn hơn con số này thì phần chiếu khấu theo % cũng chỉ tính theo số đã nhập.';

$arr_category_coupon_custom_fields['_eb_category_coupon_product'] = 'Sản phẩm';
//$eb_arr_type_coupon_custom_meta_box['_eb_category_coupon_product'] = 'number';
$eb_arr_placeholder_coupon_custom_meta_box['_eb_category_coupon_product'] = 'Thiết lập khi muốn Mã giảm giá chỉ kích hoạt cho Sản phẩm cụ thể.';

$arr_category_coupon_custom_fields['_eb_category_coupon__product'] = 'Loại trừ sản phẩm';
//$eb_arr_type_coupon_custom_meta_box['_eb_category_coupon__product'] = 'number';
$eb_arr_placeholder_coupon_custom_meta_box['_eb_category_coupon__product'] = 'Thiết lập khi muốn mã giảm giá không áp dụng cho Sản phẩm nào đó.';

$arr_category_coupon_custom_fields['_eb_category_coupon_category'] = 'Danh mục sản phẩm';
//$eb_arr_type_coupon_custom_meta_box['_eb_category_coupon_category'] = 'number';
$eb_arr_placeholder_coupon_custom_meta_box['_eb_category_coupon_category'] = 'Thiết lập khi muốn Mã giảm giá chỉ kích hoạt cho Danh mục sản phẩm cụ thể.';

$arr_category_coupon_custom_fields['_eb_category_coupon__category'] = 'Loại trừ các danh mục';
//$eb_arr_type_coupon_custom_meta_box['_eb_category_coupon__category'] = 'number';
$eb_arr_placeholder_coupon_custom_meta_box['_eb_category_coupon__category'] = 'Thiết lập khi muốn mã giảm giá không áp dụng cho Danh mục sản phẩm nào đó.';

/*
$arr_category_coupon_custom_fields['_eb_category_coupon_email'] = 'Dành cho các email';
//$eb_arr_type_coupon_custom_meta_box['_eb_category_coupon_email'] = '';
$eb_arr_placeholder_coupon_custom_meta_box['_eb_category_coupon_email'] = '';
*/

$arr_category_coupon_custom_fields['_eb_category_coupon_max'] = 'Giới hạn sử dụng cho mỗi mã giảm giá';
$eb_arr_type_coupon_custom_meta_box['_eb_category_coupon_max'] = 'number';
$eb_arr_placeholder_coupon_custom_meta_box['_eb_category_coupon_max'] = 'Thiết lập khi muốn dừng chạy Mã giảm giá này nếu đạt ngưỡng sử dụng.';

$arr_category_coupon_custom_fields['_eb_category_coupon_using'] = 'Đã sử dụng';
$eb_arr_type_coupon_custom_meta_box['_eb_category_coupon_using'] = 'hidden';
$eb_arr_placeholder_coupon_custom_meta_box['_eb_category_coupon_using'] = 'Số lượt sử dụng mã giảm giá';





// Thêm trường dữ liệu cho phần category -> luôn kích hoạt
// https://developer.wordpress.org/reference/hooks/edit_category_form_fields/
//add_filter ( 'edit_category_form_fields', 'WGR_them_cot_du_lieu_cho_from_taxonomy');
add_filter ( 'category_edit_form_fields', 'WGR_them_cot_du_lieu_cho_from_taxonomy');
add_filter ( 'edited_category', 'WGR_luu_thong_tin_khi_submit_taxonomy');


// các trường còn lại, chỉ kích hoạt khi EchBay Seo plugin được bật
//if ( cf_on_off_echbay_seo == 1 ) {
	// https://developer.wordpress.org/reference/hooks/edit_tag_form_fields/
//	add_filter ( 'edit_tag_form_fields', 'WGR_them_cot_du_lieu_cho_from_taxonomy');
	
	//
	add_filter ( 'post_tag_edit_form_fields', 'WGR_them_cot_du_lieu_cho_from_taxonomy');
	add_filter ( 'post_options_edit_form_fields', 'WGR_them_cot_du_lieu_cho_from_taxonomy');
	add_filter ( 'discount_code_edit_form_fields', 'WGR_them_cot_du_lieu_cho_from_coupon');
	
	add_filter ( EB_BLOG_POST_LINK . '_edit_form_fields', 'WGR_them_cot_du_lieu_cho_from_taxonomy');
	
	
	// Lưu dữ liệu edited_ + tên của taxonomy
	add_filter ( 'edited_post_tag', 'WGR_luu_thong_tin_khi_submit_taxonomy');
	add_filter ( 'edited_post_options', 'WGR_luu_thong_tin_khi_submit_taxonomy');
	add_filter ( 'edited_discount_code', 'WGR_luu_thong_tin_khi_submit_coupon');
	
//	add_filter ( 'edited_blogs', 'WGR_luu_thong_tin_khi_submit_taxonomy');
	add_filter ( 'edited_' . EB_BLOG_POST_LINK, 'WGR_luu_thong_tin_khi_submit_taxonomy');
//}



// add extra fields to category edit form callback function
function WGR_them_cot_du_lieu_cho_from_taxonomy ( $tag ) {
	global $arr_category_custom_fields;
	global $eb_arr_type_custom_meta_box;
	global $eb_arr_placeholder_custom_meta_box;
	
	WGR_them_cot_du_lieu_cho_from_HTML_taxonomy( $tag, $arr_category_custom_fields, $eb_arr_type_custom_meta_box, $eb_arr_placeholder_custom_meta_box );
}

function WGR_them_cot_du_lieu_cho_from_coupon ( $tag ) {
	global $arr_category_coupon_custom_fields;
	global $eb_arr_type_coupon_custom_meta_box;
	global $eb_arr_placeholder_coupon_custom_meta_box;
	
	WGR_them_cot_du_lieu_cho_from_HTML_taxonomy( $tag, $arr_category_coupon_custom_fields, $eb_arr_type_coupon_custom_meta_box, $eb_arr_placeholder_coupon_custom_meta_box );
}
	
function WGR_them_cot_du_lieu_cho_from_HTML_taxonomy ( $tag, $arr_category, $arr_type, $arr_placeholder ) {
	//
//	print_r( $tag );
//	echo 'aaaaaaaaaaaaaaa';
	
	// Lấy ID của trường
//	$cat_id = $tag->term_id;
	
	//
	echo '<tbody class="wgr-custom-taxonomy">';
	
	//
	foreach ( $arr_category as $k => $v ) {
		
		//
		$tai = isset( $arr_type[ $k ] ) ? $tai = $arr_type[ $k ] : 'text';
//		echo $tai;
		
		// Giá trị (nếu có)
//		$val = get_post_meta( $tag->term_id, $k, true );
		$val = _eb_get_cat_object( $tag->term_id, $k );
		$val = esc_attr( $val );
		
		//
		$hidden_class = '';
		if ( $tai == 'hidden' ) {
			$hidden_class = 'display:none';
		}
		
		//
		$other_attr = '';
		
		//
//		$other_attr .= 'placeholder="' . ( isset($arr_placeholder[$k]) ? $arr_placeholder[$k] : '' ) . '"';
		if ( ! isset( $arr_placeholder[$k] ) ) {
			$arr_placeholder[$k] = '';
		}
		/*
		else {
			$arr_placeholder[$k] = '<div class="small">' . $arr_placeholder[$k] . '</div>';
		}
		*/
		
		// tạo class riêng cho textarea
		$description_wrap = 'term-echbay-wrap';
		/*
		if ( $tai == 'textarea' ) {
			// đang bị xung đột với yoast SEO nếu để thông số này
			$description_wrap = 'term-description-wrap';
		}
		*/
		
		//
		echo '
<tr class="form-field ' . $description_wrap . '" style="' . $hidden_class . '">
	<th scope="row"><label for="' . $k . '">' . $v . '</label></th>
	<td>';
		
		//
		if ( gettype( $tai ) == 'array' ) {
			echo '<select id="' . $k . '" name="' . $k . '">';
			
			foreach ( $tai as $k2 => $v2 ) {
				$sl = '';
				if ( $k2 == $val ) {
					$sl = ' selected="selected"';
				}
				
				//
				echo '<option value="' . $k2 . '"' . $sl . '>' . $v2 . '</option>';
			}
			
			echo '</select>';
		}
		else if ( $tai == 'checkbox' ) {
			echo '<label for="' . $k . '"><input type="checkbox" name="' . $k . '" id="' . $k . '" value="' . $val . '" class="" />' . $arr_placeholder[$k] . '</label>';
		}
		else if ( $tai == 'textarea' ) {
			// cho chỉnh sửa khi SEO echbay đang bật
			if ( cf_on_off_echbay_seo == 1 ) {
				wp_editor( html_entity_decode($val, ENT_QUOTES, 'UTF-8'), $k );
			}
			// còn lại thì hiển thị thông thường
			else {
				echo '<textarea id="' . $k . '" name="' . $k . '" ' . $other_attr . '>' .$val. '</textarea>';
			}
		}
		else {
			echo '<input type="' . $tai . '" name="' . $k . '" id="' . $k . '" value="' . $val . '" ' . $other_attr . ' class="m" />';
		}
		
		//
		if ( $tai != 'checkbox' ) {
			echo WGR_echo_label_for_edit_form( $arr_placeholder[$k] );
		}
		
		//
		echo '
	</td>
</tr>';
	}
	
	//
	echo '</tbody>';
}



// save extra category extra fields callback function
/*
https://developer.wordpress.org/reference/functions/delete_term_meta/
https://developer.wordpress.org/reference/functions/update_term_meta/
*/
function WGR_luu_thong_tin_khi_capnhat_taxonomy ( $term_id, $arr_category, $arr_type ) {
//	global $__cf_row;
	
	// cần phải lấy cả mảng dữ liệu cũ để save vào 1 thể, nếu không sẽ bị thiếu
//	$arr_save = _eb_get_object_post_meta( $term_id, eb_cat_obj_data );
	
	// chạy vòng lặp rồi kiểm tra trong POST có tương ứng thì update
	foreach ( $arr_category as $k => $v ) {
		
		// lọc mã html với các input thường
		$loc_html = isset( $arr_type[ $k ] ) ? $arr_type[ $k ] : '';
		
		if ( isset( $_POST[ $k ] ) ){
//			echo $k . '<br>' . "\n";
			
			$val = $_POST[ $k ];
			
			// Bỏ qua với textarea
			if ( $loc_html == 'textarea_one' || $loc_html == 'textarea' ) {
//				$val = 'aaaaaaaaaaaa';
			}
			// nếu là checkbox -> set giá trị là 1
			else if ( $loc_html == 'checkbox' ) {
				$val = 1;
			}
			// chỉ áp dụng nếu là input text
			else {
				$val = sanitize_text_field( $val );
			}
			
			//
			$val = trim( WGR_stripslashes( $val ) );
//			echo $val;
			
			//
			if ( $val == '' ) {
				delete_term_meta( $term_id, $k );
				
				// tạm thời sẽ xóa cả trong post meta phần này ======================================
				delete_post_meta( $term_id, $k );
			}
			else {
//				WGR_update_meta_post( $term_id, $k, $val );
				update_term_meta( $term_id, $k, $val );
				
				// tạm thời sẽ xóa cả trong post meta phần này ======================================
				delete_post_meta( $term_id, $k );
			}
			
			//
//			$arr_save[ $k ] = $val;
			
			//
//			$arr_save[ $k ] = addslashes( $val );
		}
		// thử kiểm tra với checkbox
		else if ( $loc_html == 'checkbox' ) {
			delete_term_meta( $term_id, $k );
			
			// không có -> set là 0 luôn
//			WGR_update_meta_post( $term_id, $k, 0 );
			// tạm thời sẽ xóa cả trong post meta phần này ======================================
			delete_post_meta( $term_id, $k );
		}
		
	}
//	exit();
	
	//
//	WGR_update_meta_post( $term_id, eb_cat_obj_data, $arr_save );
	
//	print_r( $arr_save ); exit();
	
}

function WGR_luu_thong_tin_khi_submit_taxonomy ( $term_id ) {
	global $arr_category_custom_fields;
	global $eb_arr_type_custom_meta_box;
	
	WGR_luu_thong_tin_khi_capnhat_taxonomy( $term_id, $arr_category_custom_fields, $eb_arr_type_custom_meta_box );
}

function WGR_luu_thong_tin_khi_submit_coupon ( $term_id ) {
	global $arr_category_coupon_custom_fields;
	global $eb_arr_type_coupon_custom_meta_box;
	
	WGR_luu_thong_tin_khi_capnhat_taxonomy( $term_id, $arr_category_coupon_custom_fields, $eb_arr_type_coupon_custom_meta_box );
}




//} // end check ON/ OFF seo module

