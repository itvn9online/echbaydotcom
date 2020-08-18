<div id="webgiare__footer">
	<?php

//
$strCacheFilter = 'footer-ajax';
$footer_ajax_content = _eb_get_static_html ( $strCacheFilter );
if ($footer_ajax_content == false) {
	//
	ob_start();
	
	
	//
	include EB_THEME_PLUGIN_INDEX . 'common_footer.php';
	
	
	// footer menu dưới dạng widget
//	$eb_footer_widget = _eb_echbay_sidebar( 'eb_footer_global', 'eb-widget-footer cf', 'div', 1, 0, 1 );
	
	// nếu không có nội dung trong widget -> lấy theo thiết kế mặc định
//	if ( $eb_footer_widget == '' ) {
	if ( $__cf_row['cf_using_footer_default'] == 1 ) {
//		echo $eb_footer_widget;
		
//		include EB_THEME_PLUGIN_INDEX . 'footer_default.php';
		
		//
		foreach ( $arr_includes_footer_file as $v ) {
			include $v;
		}
	}
	
	
	//
	$footer_ajax_content = ob_get_contents();
	
	//ob_clean();
	//ob_end_flush();
	ob_end_clean();
	
	
	
	// lưu cache
	_eb_get_static_html ( $strCacheFilter, $footer_ajax_content );
}
echo $footer_ajax_content;

?>
</div>
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
<?php



exit();



