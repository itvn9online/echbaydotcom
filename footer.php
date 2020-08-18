<div id="footer-lazyload"></div>
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



?>
<div id="wordpress__footer">
	<?php
	
// nạp footer cho website -> các theme khác có thể sẽ dùng
get_footer();

// hàm để các plugin khác còn nhúng dữ liệu vào chân trang
wp_footer();

?>
</div>
<!-- FOOTER by EchBay -->
<?php



//
echo $__cf_row['cf_js_allpage'];






//print_r($arr_object_post_meta);




//
if ( eb_code_tester == true ) {
	echo implode( "\n", $arr_for_show_html_file_load );
}




?>
<!-- // FOOTER by EchBay -->
</body></html>