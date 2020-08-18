<?php
/*
* Mọi code dùng chung cho trang chủ sản phẩm, lấy hay không sẽ dựa vào config của khách
*/

// cache
$strCacheFilter = 'home-ajax';
$main_content = _eb_get_static_html ( $strCacheFilter );
if ($main_content == false) {
	$str_sidebar = _eb_echbay_sidebar( 'home_sidebar' );
	$home_content_bottom_sidebar = _eb_echbay_get_sidebar( 'home_content_bottom_sidebar' );
	
	
	// trả về null nếu không có dữ liệu
	if ( $__cf_row['cf_using_home_default'] == 1 ) {
		$home_with_cat = '';
		
		
		
		//
		ob_start();
	
		
		// Kiểm tra và load các file home tương ứng
		$arr_includes_home_file = WGR_load_module_name_css( 'home' );
		
		if ( count( $arr_includes_home_file ) == 0 ) {
			include EB_THEME_PLUGIN_INDEX . 'global/home_default.php';
		}
//		print_r( $arr_includes_home_file );
		
		foreach ( $arr_includes_home_file as $v ) {
			include $v;
		}
		
		
		//
		$home_with_cat = ob_get_contents();
		
		//ob_clean();
		//ob_end_flush();
		ob_end_clean();
		
		
	}
	else {
		$home_with_cat = _eb_echbay_get_sidebar( 'home_content_sidebar' );
	}
	
	//
	$html_v2_file = 'home-ajax';
	$custom_home_flex_css = EBE_get_html_file_addon( 'home', $__cf_row['cf_home_column_style'] );
	
	/*
	* Gắn widget vào trước
	*/
	$main_content = EBE_html_template( EBE_get_page_template( $html_v2_file ), array(
		'tmp.home_content_bottom_sidebar' => $home_content_bottom_sidebar,
		'tmp.str_sidebar' => $str_sidebar,
		'tmp.home_with_cat' => $home_with_cat
	) );
	
	
	
	
	
	/*
	* Những cái khác gắn sau -> nếu có code riêng thì sẽ không bị ảnh hưởng
	*/
	$arr_main_content = array(
		'tmp.cf_home_class_style' => $__cf_row ['cf_home_class_style'],
		'tmp.home_cf_title' => $__cf_row ['cf_title'],
		'tmp.custom_home_flex_css' => $custom_home_flex_css,
	);
	
	// tìm và tạo sidebar luôn
	// $arr_main_content['tmp.str_sidebar'] = _eb_echbay_sidebar( $id_for_get_sidebar );
	
	
	// lấy HTML riêng của từng site
	if ( function_exists('eb_home_for_current_domain') ) {
		$arr_main_new_content = eb_home_for_current_domain();
		
		// -> chạy vòng lặp, ghi đè lên mảng cũ
		foreach ( $arr_main_new_content as $k => $v ) {
			$arr_main_content[$k] = $v;
		}
	}
	
	
	//
	$main_content = EBE_arr_tmp( $arr_main_content, $main_content, '' );
	include EB_THEME_PLUGIN_INDEX . 'common_content.php';
	
	
	
	// lưu cache
	_eb_get_static_html ( $strCacheFilter, $main_content );
}


echo $main_content;



exit();

