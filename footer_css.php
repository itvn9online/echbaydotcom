<?php

//get_footer();



// font awesome version 4
//echo '<link rel="stylesheet" href="' . EB_DIR_CONTENT . '/echbaydotcom/outsource/fa-4.7.0/i.css?v=' . web_version . '" type="text/css" media="all" />' . "\n";
// font awesome version 5 -> đồng bộ v4 và v5



// do xung đột với elementor nên chỉ nạp jquery riêng khi không đăng nhập
//if ( mtv_id == 0 ) {
//if ( 1 == 2 ) {
	if ($__cf_row['cf_fontawesome_v5'] != 1) {
		echo '<link rel="stylesheet" id="font-awesome-v4" href="' . EB_DIR_CONTENT . '/echbaydotcom/outsource/fa-4.7.0/i.css" media="all" crossorigin="anonymous" />' . "\n";
	}
	else {
		echo '<link rel="stylesheet" id="font-awesome-v5" href="' . EB_DIR_CONTENT . '/echbaydotcom/outsource/fa-5.3.0/css/i.css" media="all" crossorigin="anonymous" />' . "\n";
		echo '<link rel="stylesheet" id="font-awesome-v4-v5" href="' . EB_DIR_CONTENT . '/echbaydotcom/outsource/fa-5.3.0/css/v4-shims.min.css?v=' . web_version . '" type="text/css" media="all" crossorigin="anonymous" />' . "\n";
	}
//}
/*
echo '<script defer src="https://use.fontawesome.com/7a0fa4540f.js"></script>';
*/




// add css, js -> sử dụng hàm riêng để tối ưu file tĩnh trước khi in ra
//_eb_add_full_css( $arr_for_add_link_css, 'link' );
//_eb_add_compiler_link_css( $arr_for_add_link_css, 'link' );
foreach ($arr_for_add_link_css as $v) {
    echo '<link rel="stylesheet" href="' . $v . '" type="text/css" media="all" />' . "\n";
}

//
EBE_print_product_img_css_class($eb_background_for_post, 'Footer');





// add file danh sách nhóm
//_eb_add_full_js( array( web_link . EB_DIR_CONTENT . '/uploads/ebcache/cat.js' ) );
// các file add mà không cần compiler
/*
  _eb_add_full_js( array(
  //	$__cf_row['cf_dns_prefetch'] . EB_DIR_CONTENT . '/echbaydotcom/outsource/javascript/jquery.js',
  //	$__cf_row['cf_dns_prefetch'] . EB_DIR_CONTENT . '/echbaydotcom/outsource/javascript/jcarousellite.js',
  //	$__cf_row['cf_dns_prefetch'] . EB_DIR_CONTENT . '/echbaydotcom/outsource/javascript/lazyload.js',
  $__cf_row['cf_dns_prefetch'] . EB_DIR_CONTENT . '/uploads/ebcache/cat.js',
  //	$__cf_row['cf_dns_prefetch'] . EB_DIR_CONTENT . '/echbaydotcom/javascript/eb.js',
  //	$__cf_row['cf_dns_prefetch'] . EB_DIR_CONTENT . '/echbaydotcom/javascript/d.js',
  //	EB_URL_OF_THEME . 'javascript/display.js',
  //	$__cf_row['cf_dns_prefetch'] . EB_DIR_CONTENT . '/echbaydotcom/javascript/footer.js',
  ) );
 */






/*
 * Tạo cat dưới dạng JS
 */

// TEST
/*
  echo '<script>';
  echo 'console.log(' . _eb_get_full_category_v2 ( 0, 'category', 1 ) . ');';
  echo '</script>';
 */
//echo _eb_get_full_category_v2 ( 0, 'category', 1 );
//exit();
// file time
//echo date( 'r', $date_time ) . "\n";
$cat_js_file_name = (int) substr(date('i', date_time), 0, 1);

// nếu phút hiện tại là 0
if ($cat_js_file_name == 0) {
    $using_js_file_name = 5;
} else {
    $using_js_file_name = $cat_js_file_name - 1;
}

// file name
$cat_js_file_name = 'cat-' . $cat_js_file_name . '.js';
$using_js_file_name = 'cat-' . $using_js_file_name . '.js';


// -> file này nó liên qua tới cả file ebcache.php -> nên có thay đổi thì phải chỉnh cả 2 chỗ
if (!file_exists(EB_THEME_CACHE . $cat_js_file_name) || date_time - filemtime(EB_THEME_CACHE . $cat_js_file_name) > 1800) {
    _eb_create_file(EB_THEME_CACHE . $cat_js_file_name, 'var eb_site_group=[' . _eb_get_full_category_v2(0, 'category', 1) . '],eb_post_options_group=[' . _eb_get_full_category_v2(0, 'post_options', 1) . '],eb_blog_group=[' . _eb_get_full_category_v2(0, EB_BLOG_POST_LINK, 1) . '];');

    //
    if (!file_exists(EB_THEME_CACHE . $using_js_file_name)) {
        copy(EB_THEME_CACHE . $cat_js_file_name, EB_THEME_CACHE . $using_js_file_name);
        chmod(EB_THEME_CACHE . $using_js_file_name, 0777);
    }
}


echo '<script type="text/javascript" src="' . EB_DIR_CONTENT . '/uploads/ebcache/' . $using_js_file_name . '?v=' . date('ymd-Hi', date_time) . '" defer></script>';

/*
  echo '<script type="text/javascript" src="' . web_link . 'eb-load-quick-search" defer></script>';
 */
/*
  echo '<script type="text/javascript" src="' . EB_URL_OF_PLUGIN . 'outsource/javascript/jquery.js"></script>';
 */


//
include EB_THEME_PLUGIN_INDEX . 'jquery_load.php';





// JS ngoài
foreach ($arr_for_add_outsource_js as $v) {
    echo '<script type="text/javascript" src="' . $v . '"></script>' . "\n";
}



//
$arr_for_add_js[] = EB_THEME_PLUGIN_INDEX . 'javascript/d.js';

// nạp js từ child theme (nếu có)
//if ( using_child_wgr_theme == 1 && file_exists( EB_CHILD_THEME_URL . 'javascript/display.js' ) ) {
if ( using_child_wgr_theme == 1 ) {
	$arr_for_add_js[] = EB_CHILD_THEME_URL . 'javascript/display.js';
}
// mặc định là nạp từ theme
else {
	$arr_for_add_js[] = EB_THEME_THEME . 'javascript/display.js';
}

// thêm JS đồng bộ URL từ code EchBay cũ sang code WebGiaRe (nếu có)
/* -> chuyển sang sử dụng phiên bản php
  if ( $__cf_row['cf_echbay_migrate_version'] == 1 ) {
  $arr_for_add_js[] = EB_THEME_PLUGIN_INDEX . 'javascript/eb_migrate_version.js';
  }
 */

// file js riêng của từng theme
if (using_child_wgr_theme == 1) {
    $arr_for_add_js[] = EB_CHILD_THEME_URL . 'ui/d.js';
} else {
    $arr_for_add_js[] = EB_THEME_URL . 'ui/d.js';
}

// bổ sung js chân trang
$arr_for_add_js[] = EB_THEME_PLUGIN_INDEX . 'javascript/footer.js';



// làm chức năng hiển thị những ai đã mua hàng để khách có thể xem
//$__cf_row['cf_show_order_fomo'] = 11;
if ($__cf_row['cf_show_order_fomo'] > 0) {
	$sql = _eb_load_order( $__cf_row['cf_show_order_fomo'], array(
		'filter_by' => " AND order_status != 4 AND order_status != 13 "
	) );
//	print_r( $sql );
	
	$arr_fomo_order = array();
	
	foreach ( $sql as $v ) {
//		$v->order_products = json_decode( $v->order_products );
//		$v->order_customer = json_decode( $v->order_customer );
//		print_r( $v );
		
		//
		$fomo_id = '';
		$fomo_product = '';
		$fomo_img = '';
		$fomo_name = '';
		$fomo_phone = '';
		
		$new_fomo = explode('%7B%22id%22%3A', $v->order_products);
		if ( count( $new_fomo ) > 1 ) {
			$new_fomo = explode('%2C%22', $new_fomo[1]);
			$fomo_id = $new_fomo[0];
			$fomo_id = trim( str_replace('%22', '', $fomo_id) );
		}
		
		$new_fomo = explode('%2C%22name%22%3A%22', $v->order_products);
		if ( count( $new_fomo ) > 1 ) {
			$new_fomo = explode('%22%2C%22', $new_fomo[1]);
			$fomo_product = $new_fomo[0];
		}
		
		$new_fomo = explode('%2C%22color_img%22%3A%22', $v->order_products);
		if ( count( $new_fomo ) > 1 ) {
			$new_fomo = explode('%22%2C%22', $new_fomo[1]);
			$fomo_img = $new_fomo[0];
		}
		
		$new_fomo = explode('%7B%22hd_ten%22%3A%22', $v->order_customer);
		if ( count( $new_fomo ) > 1 ) {
			$new_fomo = explode('%22%2C%22', $new_fomo[1]);
			$fomo_name = $new_fomo[0];
		}
		
		$new_fomo = explode('%22%2C%22hd_dienthoai%22%3A%22', $v->order_customer);
		if ( count( $new_fomo ) > 1 ) {
			$new_fomo = explode('%22%2C%22', $new_fomo[1]);
			$fomo_phone = $new_fomo[0];
			$fomo_phone = trim( str_replace('%20', '', $fomo_phone) );
			$fomo_phone = substr( $fomo_phone, strlen( $fomo_phone ) - 4 );
		}
		
		//
//		echo $fomo_id . '<br>' . "\n";
//		echo $fomo_product . '<br>' . "\n";
//		echo $fomo_name . '<br>' . "\n";
//		echo $fomo_phone . '<br>' . "\n";
//		echo '------------------<br>' . "\n";
		
		//
		$arr_fomo_order[] = array(
			'fomo_id' => $fomo_id,
			'fomo_product' => $fomo_product,
			'fomo_img' => $fomo_img,
			'fomo_time' => $v->order_time,
			'fomo_name' => $fomo_name,
			'fomo_phone' => $fomo_phone
		);
	}
//	print_r( $arr_fomo_order );
	echo '<script>arr_fomo_order=' . json_encode( $arr_fomo_order ) . ',
		cf_delay_order_fomo="' . $__cf_row['cf_delay_order_fomo'] . '",
		cf_time_order_fomo="' . $__cf_row['cf_time_order_fomo'] . '";</script>';
	$arr_for_add_js[] = EB_THEME_PLUGIN_INDEX . 'javascript/fomo_order.js';
	
//	exit();
}




//print_r( $arr_for_add_js );
//
EBE_add_js_compiler_in_cache($arr_for_add_js, 'defer', 1);



// JS ngoài
foreach ($arr_for_add_outsource_async_js as $v) {
    echo '<script type="text/javascript" src="' . $v . '" defer></script>' . "\n";
}



/*
* JS cho hết xuống cuối trang
*/
/*
_eb_add_css_js_file( array(
	'jquery-1.11.0.min.js',
	'jcarousellite_1.0.1.min.js',
	'jquery.lazyload.pack.js',
), '.js', 1, EB_URL_OF_PLUGIN . 'outsource/' );
*/

// các file js ở chân trang
//$arr_for_add_js[] = 'javascript/display_wp.js';
//$arr_for_add_js[] = 'display.js';
//$arr_for_add_js[] = 'javascript/social.js';

//
//_eb_add_js( $arr_for_add_js );



