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

// đã gọi đến header ở đây thì kiểm tra xem có file footer chưa, chưa có thì tạo luôn
if ( !file_exists( EB_CHILD_THEME_URL . 'footer.php' ) ) {
    if ( copy( __DIR__ . '/footer-tmp.php', EB_CHILD_THEME_URL . 'footer.php' ) == true ) {
        echo 'copy footer for child theme <br>' . "\n";
    } else {
        echo 'ERROR! copy footer for child theme <br>' . "\n";
    }
}
// không cache khi user đang đăng nhập
else if ( mtv_id > 0 ) {
    echo '<!-- EchBay Cache (ebcache) is enable, but not caching requests by known users -->' . "\n";

    // kiểm tra và nạp ebsuppercache nếu chưa có -> chỉ áp dụng khi người dùng đang đăng nhập -> thường thì admin mới đăng nhập
    //WGR_add_ebcache_php_to_index( $__cf_row );
}
// nếu cache đang được bật, nhưng lại dùng cache của đơn vị khác -> cũng hủy cache luôn
else if ( defined( 'WP_CACHE' ) && WP_CACHE == true ) {
    echo '<!-- EchBay Cache (ebcache) is enable, but an another plugin cache is enable too.
    If you want to using EchBay Cache, please set WP_CACHE = false or comment WP_CACHE in file wp-config.php -->' . "\n";
}
// tắt ép cache -> dùng với các custom page mà cần kiểu submit -> tìm kiếm, đặt hàng
else if ( defined( 'WGR_NO_CACHE' ) && WGR_NO_CACHE == true ) {
    echo '<!-- EchBay Cache (ebcache) not running because WGR_NO_CACHE enable -->';
}
// tắt khi người dùng đang tìm kiếm
else if ( isset( $_GET[ 'search_advanced' ] ) ) {
    echo '<!-- EchBay Cache (ebcache) not running in SEARCH method -->';
}
// không cache với post
else if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
    echo '<!-- EchBay Cache (ebcache) not running in POST method -->';
}
// thời gian tối thiểu để cache là 30s
else if ( $__cf_row[ 'cf_reset_cache' ] < 30 ) {
    echo '<!-- EchBay Cache (ebcache) is enable, but not time for reset cache too many short (' . $__cf_row[ 'cf_reset_cache' ] . ' secondes). Min 30 secondes -->';
}
// có file footer thì bật chế độ cache
else if ( is_home() ||
    is_front_page() ||
    is_single() ||
    //is_page() ||
    //is_page_template() ||
    //isset( $is_page_templates ) ||
    is_archive()
) {
    //echo '<!-- ' . basename( 'EB header cache' ) . ' -->' . "\n";

    // xác nhận có sử dụng ebcache
    define( 'HAS_USING_EBCACHE', true );

    // bắt đầu cache
    ob_start();
}