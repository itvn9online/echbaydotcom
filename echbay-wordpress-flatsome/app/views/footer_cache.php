<?php

/*
 * quick cart
 */
//if ( $act != 'cart' ) {
global $pid;
global $__post;
global $__cf_row;
include EB_THEME_PLUGIN_INDEX . 'quick_cart.php';
//}


/*
 * thêm NAV menu cho bản mobile
 */
if ( wp_is_mobile() ) {
    if ( $__cf_row[ 'cf_search_nav_mobile' ] == 'none' ) {
        $__cf_row[ 'cf_default_css' ] .= '@media only screen and (max-width:788px) { body{margin-top:0} }';
    }
    // chuyển sang nạp thông qua ajax xem có nhanh web hơn không
    else {
        // menu dành cho bản mobile
        $str_nav_mobile_top = _eb_echbay_menu( 'nav-for-mobile' );

        //
        include EB_THEME_PLUGIN_INDEX . 'mobile/nav.php';

        //
        //echo $__cf_row['cf_search_nav_mobile'] . '<br>' . "\n";
        Wgr::$eb->BaseModelWgr->adds_css( [
            EB_THEME_PLUGIN_INDEX . 'html/search/' . $__cf_row['cf_search_nav_mobile'] . '.css',
        ] );

        //
        echo $html_search_nav_mobile;
    }
}


// kết thúc cache -> lấy ra nội dung để in ra
if ( defined( 'HAS_USING_EBCACHE' ) ) {
    $main_content = ob_get_contents();
    ob_end_clean();

    // bắt đầu cache
    $filename = ___eb_cache_getUrl();
    //echo $filename . '<br>' . "\n";
    //echo date_time . '<br>' . "\n";
    //echo mtv_id . '<br>' . "\n";

    // nếu không tồn tại file/
    if ( !file_exists( $filename ) ) {
        //
        /*
        file_put_contents( $filename, '.', LOCK_EX ) or die('ERROR: create cache file');
        chmod($filename, 0777);
        */
        //exit();

        // -> tạo file và trả về tên file
        $filew = fopen( $filename, 'x+' );
        // nhớ set 777 cho file
        chmod( $filename, 0777 );
        fclose( $filew );
    }


    // thêm câu báo rằng đang lấy nội dung trong cache
    $eb_cache_note = WGR_create_eb_cache_note();

    // END
    //echo $eb_cache_note;
    echo $main_content;

    // ghi lại cache để sử dụng
    //echo $filename . '<br>' . "\n";
    $arr_cat_js_cache = WGR_cat_js_cache();
    //print_r( $arr_cat_js_cache );

    //
    $cat_js_file_name = $arr_cat_js_cache[ 'cat_js_file_name' ];
    $using_js_file_name = $arr_cat_js_cache[ 'using_js_file_name' ];
    WGR_content_cat_js_cache( $cat_js_file_name, $using_js_file_name );

    //
    ___eb_cache_cache( $filename, $main_content, $eb_cache_note );
} else {
    global $why_ebcache_not_active;
    echo $why_ebcache_not_active;
}