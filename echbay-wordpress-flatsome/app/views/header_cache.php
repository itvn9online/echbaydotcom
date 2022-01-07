<?php

//echo EB_CHILD_THEME_URL;
//echo EB_THEME_PLUGIN_INDEX;
// mảng các trang sẽ sử dụng ebcache
/*
$arr_eb_cache_allow = [
    'archive',
    'single',
    'page',
];
*/

//
global $__cf_row;
//print_r( $__cf_row );

//
include_once EB_THEME_PLUGIN_INDEX . 'main_function.php';

//
global $why_ebcache_not_active;

// sử dụng ebcache
$active_using_ebcache = false;
// kích hoạt chế độ ob start
$active_ob_start = false;

// đã gọi đến header ở đây thì kiểm tra xem có file footer chưa, chưa có thì tạo luôn
if ( !file_exists( EB_CHILD_THEME_URL . 'footer.php' ) ) {
    if ( copy( __DIR__ . '/footer-tmp.php', EB_CHILD_THEME_URL . 'footer.php' ) == true ) {
        echo 'copy footer for child theme <br>' . "\n";
    } else {
        _eb_create_file( EB_CHILD_THEME_URL . 'footer.php', file_get_contents( __DIR__ . '/footer-tmp.php', 1 ) );
        if ( file_exists( EB_CHILD_THEME_URL . 'footer.php' ) ) {
            echo 'CREATE footer for child theme <br>' . "\n";
        } else {
            echo 'ERROR! copy footer for child theme <br>' . "\n";
        }
    }
}
// không cache khi user đang đăng nhập
else if ( mtv_id > 0 ) {
    $why_ebcache_not_active = '<!-- EchBay Cache (ebcache) is enable, but not caching requests by known users -->';

    // kiểm tra và nạp ebsuppercache nếu chưa có -> chỉ áp dụng khi người dùng đang đăng nhập -> thường thì admin mới đăng nhập
    //WGR_add_ebcache_php_to_index( $__cf_row );

    //
    $active_ob_start = true;
}
// nếu cache đang được bật, nhưng lại dùng cache của đơn vị khác -> cũng hủy cache luôn
else if ( defined( 'WP_CACHE' ) && WP_CACHE == true ) {
    $why_ebcache_not_active = '<!-- EchBay Cache (ebcache) is enable, but an another plugin cache is enable too.
    If you want to using EchBay Cache, please set WP_CACHE = false or comment WP_CACHE in file wp-config.php -->';
}
// tắt ép cache -> dùng với các custom page mà cần kiểu submit -> tìm kiếm, đặt hàng
else if ( defined( 'WGR_NO_CACHE' ) && WGR_NO_CACHE == true ) {
    $why_ebcache_not_active = '<!-- EchBay Cache (ebcache) not running because WGR_NO_CACHE enable -->';
}
// tắt khi người dùng đang tìm kiếm
else if ( isset( $_GET[ 'search_advanced' ] ) ) {
    $why_ebcache_not_active = '<!-- EchBay Cache (ebcache) not running in SEARCH method -->';

    //
    $active_ob_start = true;
}
// không cache với post
else if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
    $why_ebcache_not_active = '<!-- EchBay Cache (ebcache) not running in POST method -->';
}
// thời gian tối thiểu để cache là 30s
else if ( $__cf_row[ 'cf_reset_cache' ] < 30 ) {
    $why_ebcache_not_active = '<!-- EchBay Cache (ebcache) is enable, but not time for reset cache too many short (' . $__cf_row[ 'cf_reset_cache' ] . ' secondes). Min 30 secondes -->';

    //
    $active_ob_start = true;
}
//
/*
//else if ( isset( $is_page_templates ) ) {
else if ( is_front_page() ) {
    echo '<!-- ' . basename( 'TEST header cache' ) . ' -->' . "\n";
}
*/
// có file footer thì bật chế độ cache
else if ( is_home() ||
    is_front_page() ||
    is_single() ||
    is_archive()
) {
    //echo '<!-- ' . basename( 'EB header cache' ) . ' -->' . "\n";

    // xác nhận có sử dụng ebcache
    $active_using_ebcache = true;

    // kích hoạt ob_start để còn replace content ở footer
    $active_ob_start = true;
}
// page template
else if ( isset( $is_page_templates ) ||
    is_page_template() ) {
    $why_ebcache_not_active = '<!-- EchBay Cache disable in page template -->';
    //echo $why_ebcache_not_active;

    // xác nhận có sử dụng ebcache
    //$active_using_ebcache = true;

    // kích hoạt ob_start để còn replace content ở footer
    $active_ob_start = true;
}
// page
else if ( is_page() ) {
    //echo '<!-- ' . basename( 'EB header page cache' ) . ' -->' . "\n";

    // xác nhận có sử dụng ebcache
    $active_using_ebcache = true;

    // kích hoạt ob_start để còn replace content ở footer
    $active_ob_start = true;
}
// chỉ cache với 1 số trang cụ thể thôi
else {
    $why_ebcache_not_active = '<!-- EchBay Cache cache only home page, category page, post details page -->';

    //
    $active_ob_start = true;
}


// kích hoạt ob_start để còn replace content ở footer
if ( $active_ob_start == true ) {
    define( 'HAS_USING_EB_START', true );

    // -> kích hoạt ebcache (chỉ hoạt động khi ob start được kích hoạt)
    if ( $active_using_ebcache == true ) {
        define( 'HAS_USING_EBCACHE', true );
    }

    ob_start();
}