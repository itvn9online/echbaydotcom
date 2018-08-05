<?php

// footer menu dưới dạng widget
//$eb_footer_widget = _eb_echbay_sidebar( 'eb_footer_global', 'eb-widget-footer cf', 'div', 1, 0, 1 );

// nếu không có nội dung trong widget -> lấy theo thiết kế mặc định
//if ( $eb_footer_widget == '' ) {
if ( $__cf_row['cf_using_footer_default'] == 1 ) {
//	echo $eb_footer_widget;
	
//	include EB_THEME_PLUGIN_INDEX . 'footer_default.php';
	
	//
	foreach ( $arr_includes_footer_file as $v ) {
		include $v;
	}
}

?>
<!-- quick view video -->

<div class="quick-video">
	<div class="quick-video-close big cf">
		<div class="lf f40 show-if-mobile"><i title="Close" class="fa fa-remove cur d-block"></i></div>
		<div class="text-right rf f20 hide-if-mobile"><i title="Close" class="fa fa-remove cur d-block"></i></div>
	</div>
	<div class="quick-video-padding">
		<div id="quick-video-content"></div>
	</div>
</div>
<!-- -->
<div id="oi_scroll_top" class="fa fa-chevron-up default-bg"></div>
<div id="fb-root"></div>
<div id="oi_popup"></div>
<!-- mobile menu -->
<?php



// kiểm tra NAV mobile theo theme, nếu không có -> dùng bản dùng chung
if ( $__cf_row['cf_search_nav_mobile'] != '' ) {
	
	// file html
	$file_search_nav_mobile = EB_THEME_THEME . 'html/' . $__cf_row['cf_search_nav_mobile'] . '.html';
	
	// kiểm tra trong child theme
	$tmp_child_theme = '';
	if ( using_child_wgr_theme == 1 ) {
		$tmp_child_theme = EB_CHILD_THEME_URL . 'html/' . $__cf_row['cf_search_nav_mobile'] . '.html';
	}
	
	// child theme
	if ( $tmp_child_theme != '' && file_exists($tmp_child_theme) ) {
		$html_search_nav_mobile = file_get_contents($tmp_child_theme, 1);
		
		$arr_for_add_css[ EB_CHILD_THEME_URL . 'css/' . $__cf_row['cf_search_nav_mobile'] . '.css' ] = 1;
	}
	// kiểm tra trong theme
	else if ( file_exists($file_search_nav_mobile) ) {
		$html_search_nav_mobile = file_get_contents($file_search_nav_mobile, 1);
		
		$arr_for_add_css[ EB_THEME_THEME . 'css/' . $__cf_row['cf_search_nav_mobile'] . '.css' ] = 1;
	}
	// lấy mặc định trong plugin
	else {
		$file_search_nav_mobile = EB_THEME_PLUGIN_INDEX . 'html/search/' . $__cf_row['cf_search_nav_mobile'] . '.html';
		
		$html_search_nav_mobile = file_get_contents($file_search_nav_mobile, 1);
		
		$arr_for_add_css[ EB_THEME_PLUGIN_INDEX . 'html/search/' . $__cf_row['cf_search_nav_mobile'] . '.css' ] = 1;
	}
	
	//
	echo EBE_html_template(
		$html_search_nav_mobile,
		array(
			'tmp.str_nav_mobile_top' => $str_nav_mobile_top,
			
			'tmp.cart_dienthoai' => EBE_get_lang('cart_dienthoai'),
			'tmp.cart_hotline' => EBE_get_lang('cart_hotline'),
			
			'tmp.cf_logo' => $__cf_row['cf_logo'],
			'tmp.cf_dienthoai' => $__cf_row['cf_dienthoai'],
			'tmp.cf_call_dienthoai' => $__cf_row['cf_call_dienthoai'],
			'tmp.cf_hotline' => $__cf_row['cf_hotline'],
			'tmp.cf_call_hotline' => $__cf_row['cf_call_hotline'],
		)
	);
}



//
//echo $act;
if ( $act != 'cart' ) {
	include EB_THEME_PLUGIN_INDEX . 'quick_cart.php';
}



//
include EB_THEME_PLUGIN_INDEX . 'footer_css.php';


// nạp footer cho website -> các theme khác có thể sẽ dùng
get_footer();

// hàm để các plugin khác còn nhúng dữ liệu vào chân trang
wp_footer();




//
echo $__cf_row['cf_js_allpage'];






//print_r($arr_object_post_meta);




//
if ( eb_code_tester == true ) {
	echo implode( "\n", $arr_for_show_html_file_load );
}




?>
</body></html>