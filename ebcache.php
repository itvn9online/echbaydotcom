<?php

//
define( 'EB_THEME_CACHE', dirname( __DIR__ ) . '/uploads/ebcache/' . $_SERVER[ 'HTTP_HOST' ] . '/' );
//die( EB_THEME_CACHE );
//define( 'EB_SUB_THEME_CACHE', str_replace( ABSPATH, '', EB_THEME_CACHE ) );
//echo EB_SUB_THEME_CACHE . '<br>' . "\n";

//
include_once __DIR__ . '/main_function.php';
$ebsuppercache_filename = ___eb_cache_getUrl();
//die( $ebsuppercache_filename );

// nếu tồn tại cookie wgr_ebsuppercache_timeout -> người dùng đang đăng nhập -> bỏ
if ( isset( $_COOKIE[ 'wgr_ebsuppercache_timeout' ] ) || $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
    // đăng nhập rồi thì bỏ qua -> không nạp cache
    //echo 'wgr_ebsuppercache_timeout';
} else if ( file_exists( $ebsuppercache_filename ) ) {
    //die( $ebsuppercache_filename );

    // thời gian để nạp lại cache cho phần này
    $time_reset_ebsuppercache = time() - filemtime( $ebsuppercache_filename );
    $time_for_begin_reset_cache = 168;
    if ( $time_reset_ebsuppercache < $time_for_begin_reset_cache ) {
        $arr_cat_js_cache = WGR_cat_js_cache();

        //
        $cat_js_file_name = $arr_cat_js_cache[ 'cat_js_file_name' ];
        $using_js_file_name = $arr_cat_js_cache[ 'using_js_file_name' ];

        // -> done
        if ( file_exists( EB_THEME_CACHE . $cat_js_file_name ) && file_exists( EB_THEME_CACHE . $using_js_file_name ) ) {
            die( file_get_contents( $ebsuppercache_filename, 1 ) . ' <!-- generated by ebsuppercache (' . $time_reset_ebsuppercache . ') -->' );
        } else {
            //echo $cat_js_file_name . ' not exist<br>' . "\n";
        }
    } else {
        //echo 'time_reset_ebsuppercache: ' . $time_reset_ebsuppercache . ' > time_for_begin_reset_cache: ' . $time_for_begin_reset_cache . '<br>' . "\n";
    }
}