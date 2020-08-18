<?php




/*
* file common với các tham số dùng cho footer
*/



// Mảng list các file dùng để tạo top, footer
$arr_includes_footer_file = array();
if ( $__cf_row['cf_using_footer_default'] == 1 ) {
//	$arr_for_add_css[ EB_THEME_PLUGIN_INDEX . 'css/footer_default.css' ] = 1;
	
	// Kiểm tra và load các file footer tương ứng
	$arr_includes_footer_file = WGR_load_module_name_css( 'footer' );
	
	//
	if ( count( $arr_includes_footer_file ) == 0 ) {
		include EB_THEME_PLUGIN_INDEX . 'footer_default.php';
	}
//	print_r( $arr_includes_footer_file );
}



