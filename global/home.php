<?php

/**
 * Mọi code dùng chung cho trang chủ sản phẩm, lấy hay không sẽ dựa vào config của khách
 */


// fixed cứng tên file cache cho 1 số trường hợp
define('MY_FXIED_CACHE_FILENAME', '-');
// echo __FILE__ . ':' . __LINE__ . '<br>' . PHP_EOL;


//
$dynamic_meta .= '<link rel="canonical" href="' . web_link . '" />';
$url_og_url = web_link;




// Nếu config không tạo menu -> không load sidebar
if ($__cf_row['cf_home_column_style'] == '') {
	$id_for_get_sidebar = '';
} else {
	$id_for_get_sidebar = 'home_sidebar';
}




// Chỉ lấy banner riêng khi chế độ global không được kích hoạt
if ($__cf_row['cf_global_big_banner'] != 1) {
	$str_big_banner = EBE_get_big_banner(EBE_get_lang('bigbanner_num'), array(
		'category__not_in' => ''
	));
}




// cache
/*
$strCacheFilter = 'home';
$main_content = _eb_get_static_html ( $strCacheFilter );
if ($main_content == false) {
	*/



//
$home_content_top_sidebar = _eb_echbay_get_sidebar('home_content_top_sidebar');




//
$home_content_bottom_sidebar = _eb_echbay_get_sidebar('home_content_bottom_sidebar');

// load default nếu không có dữ liệu
//	$home_content_sidebar = _eb_echbay_sidebar( 'home_content_sidebar' );

// trả về null nếu không có dữ liệu
if ($__cf_row['cf_using_home_default'] == 1) {
	$home_with_cat = '';



	//
	ob_start();


	// Kiểm tra và load các file home tương ứng
	$arr_includes_home_file = WGR_load_module_name_css('home');

	if (count($arr_includes_home_file) == 0) {
		include EB_THEME_PLUGIN_INDEX . 'global/home_default.php';
	}
	//		print_r( $arr_includes_home_file );

	foreach ($arr_includes_home_file as $v) {
		include $v;
	}


	//
	$home_with_cat = ob_get_contents();

	//ob_clean();
	//ob_end_flush();
	ob_end_clean();
} else {
	$home_with_cat = _eb_echbay_get_sidebar('home_content_sidebar');
}

//
//	echo $home_with_cat;

// nếu không có home -> load mặc định theo thiết kế dựng sẵn
/*
	if ( $home_with_cat == '' ) {
		include EB_THEME_PLUGIN_INDEX . 'global/home_default.php';
	}
	*/



// lấy template theo từng trang
//	echo EB_THEME_PLUGIN_INDEX . 'html/<br>';

//	$main_content = EBE_str_template( 'home.html', $arr_main_content );
//	$html_v2_file = EBE_get_html_file_addon( 'home', $__cf_row['cf_home_column_style'] );
$html_v2_file = 'home';
$custom_home_flex_css = EBE_get_html_file_addon('home', $__cf_row['cf_home_column_style']);

/*
	* Gắn widget vào trước
	*/
$main_content = EBE_html_template(EBE_get_page_template($html_v2_file), array(
	//		'tmp.home_content_bottom_sidebar' => $home_content_bottom_sidebar,
	//		'tmp.home_with_cat' => $home_with_cat,
	//		'tmp.home_content_sidebar' => $home_content_sidebar,
	'tmp.home_content_top_sidebar' => $home_content_top_sidebar
));





/*
	* Những cái khác gắn sau -> nếu có code riêng thì sẽ không bị ảnh hưởng
	*/
$arr_main_content = array(
	'tmp.cf_home_class_style' => $__cf_row['cf_home_class_style'],
	'tmp.home_cf_title' => $__cf_row['cf_title'],
	'tmp.custom_home_flex_css' => $custom_home_flex_css
);

// tìm và tạo sidebar luôn
//	$arr_main_content['tmp.str_sidebar'] = _eb_echbay_sidebar( $id_for_get_sidebar );


// lấy HTML riêng của từng site
if (function_exists('eb_home_for_current_domain')) {
	$arr_main_new_content = eb_home_for_current_domain();

	// -> chạy vòng lặp, ghi đè lên mảng cũ
	foreach ($arr_main_new_content as $k => $v) {
		$arr_main_content[$k] = $v;
	}
}


//
$main_content = EBE_arr_tmp($arr_main_content, $main_content, '');




//
$home_str_sidebar = $id_for_get_sidebar == '' ? '' : _eb_echbay_sidebar($id_for_get_sidebar);


// cache cho phần home-ajax -> sau trong file home-ajax chỉ việc hiển thị ra là được
$strHomeAjaxCacheFilter = 'home-ajax';
$home_ajax_content = _eb_get_static_html($strHomeAjaxCacheFilter);
if ($home_ajax_content == false) {
	$home_ajax_content = EBE_html_template(EBE_get_page_template($strHomeAjaxCacheFilter), array(
		'tmp.home_content_bottom_sidebar' => $home_content_bottom_sidebar,
		'tmp.str_sidebar' => $home_str_sidebar,
		'tmp.home_with_cat' => $home_with_cat
	));
	$home_ajax_content = EBE_arr_tmp($arr_main_content, $home_ajax_content, '');

	// lưu cache
	_eb_get_static_html($strHomeAjaxCacheFilter, $home_ajax_content);
}

// nếu người dùng đang đăng nhập vào web -> hiển thị luôn nội dung
$home_lazyload = 'home-lazyload';
// 2021-06-30: tạm bỏ chức năng lazzyload do còn nhiều bất cập
//if ( mtv_id > 0 || $__cf_row['cf_lazy_load_home_footer'] != 1 ) {
$home_ajax_content = EBE_html_template(EBE_get_page_template($strHomeAjaxCacheFilter), array(
	'tmp.home_content_bottom_sidebar' => $home_content_bottom_sidebar,
	'tmp.str_sidebar' => $home_str_sidebar,
	'tmp.home_with_cat' => $home_with_cat
));
$home_ajax_content = EBE_arr_tmp($arr_main_content, $home_ajax_content, '');

/*
	$strHomeAjaxCacheFilter = EB_THEME_CACHE . 'home-ajax.txt';
	if ( is_file( $strHomeAjaxCacheFilter ) ) {
		$home_ajax_content = file_get_contents( $strHomeAjaxCacheFilter, 1 );
	}
	*/

//
$main_content = str_replace('{tmp.user_login_home_content}', $home_ajax_content, $main_content);

// xóa ID load content = ajax đi
$home_lazyload = 'home-no-lazyload';
/*
}
else {
	$main_content = str_replace( '{tmp.user_login_home_content}', '', $main_content );
}
*/
$main_content = str_replace('{tmp.home_lazyload}', $home_lazyload, $main_content);




// lưu cache
/*
	_eb_get_static_html ( $strCacheFilter, $main_content );
	

} // end cache
*/




// loại bỏ chức năng bỏ qua sản phẩm đã lấy, để custom code còn hoạt động được
$___eb_post__not_in = '';
