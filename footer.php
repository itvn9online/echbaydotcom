<?php

// footer menu dưới dạng widget
//$eb_footer_widget = _eb_echbay_sidebar( 'eb_footer_global', 'eb-widget-footer cf', 'div', 1, 0, 1 );

// nếu không có nội dung trong widget -> lấy theo thiết kế mặc định
//if ( $eb_footer_widget == '' ) {
// nạp CSS -> vẫn phải chạy chức năng này để nó còn nạp CSS cho trang chủ
if ( $__cf_row[ 'cf_using_footer_default' ] == 1 ) {

    //
    ob_start();

    //	echo $eb_footer_widget;

    //	include EB_THEME_PLUGIN_INDEX . 'footer_default.php';

    //
    foreach ( $arr_includes_footer_file as $v ) {
        include $v;
    }


    //
    $footer_ajax_content = ob_get_contents();

    //ob_clean();
    //ob_end_flush();
    ob_end_clean();
} else {
    $footer_ajax_content = '<!-- cf_using_footer_default != 1 -->';
}

// cache cho phần footer-ajax -> sau trong file footer-ajax chỉ việc hiển thị ra là được
$strFooterAjaxCacheFilter = 'footer-ajax';
$check_footer_ajax_content = _eb_get_static_html( $strFooterAjaxCacheFilter );
if ( $check_footer_ajax_content == false ) {
    // lưu cache
    _eb_get_static_html( $strFooterAjaxCacheFilter, $footer_ajax_content );
}

// nếu người dùng đang đăng nhập vào web -> hiển thị luôn nội dung
$footer_lazyload = 'footer-lazyload';
if ( mtv_id > 0 || $__cf_row[ 'cf_lazy_load_home_footer' ] != 1 ) {
    $footer_content = file_get_contents( EB_THEME_PLUGIN_INDEX . 'html/footer-ajax.html', 1 );
    echo str_replace( '{tmp.footer_content}', $footer_ajax_content, $footer_content );

    // xóa ID load content = ajax đi
    $footer_lazyload = 'footer-no-lazyload';
}

?>
<div id="<?php echo $footer_lazyload; ?>"></div>
<!-- quick view video -->
<div class="quick-video">
    <div class="quick-video-close big cf">
        <div class="lf f40 show-if-mobile"><i title="Close" class="fas fa-times cur d-block"></i></div>
        <div class="text-right rf f20 hide-if-mobile"><i title="Close" class="fas fa-times cur d-block"></i></div>
    </div>
    <div class="quick-video-padding">
        <div id="quick-video-content"></div>
    </div>
</div>
<div id="oi_scroll_top" class="default-bg"><i class="fas fa-chevron-up"></i></div>
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
<!-- FOOTER by <?php echo $arr_private_info_setting['author']; ?> -->
<?php


//
echo $__cf_row[ 'cf_js_allpage' ];


//print_r($arr_object_post_meta);


//
if ( eb_code_tester == true ) {
    echo implode( "\n", $arr_for_show_html_file_load );
}


?>
<!-- // FOOTER by <?php echo $arr_private_info_setting['author']; ?> -->
<!-- Our project just needs Font Awesome Solid + Brands -->
<!--
<script src="<?php echo EB_URL_TUONG_DOI; ?>javascript/fontawesome.js"></script> 
-->
<script defer src="<?php echo EB_URL_TUONG_DOI; ?>outsource/fontawesome-free-5.15.1-web/js/brands.min.js"></script> 
<script defer src="<?php echo EB_URL_TUONG_DOI; ?>outsource/fontawesome-free-5.15.1-web/js/solid.min.js"></script> 
<script defer src="<?php echo EB_URL_TUONG_DOI; ?>outsource/fontawesome-free-5.15.1-web/js/fontawesome.min.js"></script>
<script defer src="<?php echo EB_URL_TUONG_DOI; ?>outsource/fontawesome-free-5.15.1-web/js/v4-shims.min.js"></script> 
</body></html>