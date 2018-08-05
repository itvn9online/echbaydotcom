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
echo $html_search_nav_mobile;



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