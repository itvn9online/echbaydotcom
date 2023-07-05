<?php

//
include_once __DIR__ . '/ebcache_global.php';
//echo EB_GLOBAL_CACHE . '<br>';

// kiểm tra xem IP này có trong blacklist thì block luôn
/*
if ( isset( $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ) ) {
    $check_blacklist_ip = $_SERVER[ 'HTTP_X_FORWARDED_FOR' ];
} else if ( isset( $_SERVER[ 'HTTP_X_REAL_IP' ] ) ) {
    $check_blacklist_ip = $_SERVER[ 'HTTP_X_REAL_IP' ];
} else if ( isset( $_SERVER[ 'HTTP_CLIENT_IP' ] ) ) {
    $check_blacklist_ip = $_SERVER[ 'HTTP_CLIENT_IP' ];
} else {
    $check_blacklist_ip = $_SERVER[ 'REMOTE_ADDR' ];
}
if ( in_array( $check_blacklist_ip, $arr_current_blacklist_ip ) ) {
    die( '<h1>Unauthorized!</h1>' );
}
*/


//
if (!function_exists('wp_is_mobile')) {
    // fake function wp_is_mobile of wordpress
    function WGR_is_mobile()
    {
        if (empty($_SERVER['HTTP_USER_AGENT'])) {
            $is_mobile = false;
        }
        // Many mobile devices (all iPhone, iPad, etc.)
        else if (
            strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false ||
            strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false ||
            strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false ||
            strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false ||
            strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false ||
            strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false ||
            strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false
        ) {
            $is_mobile = true;
        } else {
            $is_mobile = false;
        }

        //
        return $is_mobile;
    }

    //
    if (WGR_is_mobile()) {
        $EB_THEME_CACHE .= 'm/';
    }
} else if (wp_is_mobile()) {
    $EB_THEME_CACHE .= 'm/';
}
// thư mục cache có phân biệt mobile với desktop
define('EB_THEME_CACHE', $EB_THEME_CACHE);
//die( EB_THEME_CACHE );
//define( 'EB_SUB_THEME_CACHE', str_replace( ABSPATH, '', EB_THEME_CACHE ) );
//echo EB_SUB_THEME_CACHE . '<br>' . "\n";
//echo EB_THEME_CACHE . '<br>' . "\n";
//echo '<!-- ' . EB_THEME_CACHE . ' -->';

//
include_once __DIR__ . '/main_function.php';
$ebsuppercache_filename = ___eb_cache_getUrl();
//die($ebsuppercache_filename);

//
//echo date('Y-m-d H:i:s', date_time);

// nếu tồn tại cookie wgr_ebsuppercache_timeout -> người dùng đang đăng nhập -> bỏ
if (isset($_COOKIE['wgr_ebsuppercache_timeout']) || $_SERVER['REQUEST_METHOD'] == 'POST') {
    // đăng nhập rồi thì bỏ qua -> không nạp cache
    //echo 'wgr_ebsuppercache_timeout';
}
//
else if (file_exists($ebsuppercache_filename)) {
    //die( $ebsuppercache_filename );

    //
    //$time_for_begin_reset_cache = 168;
    // tạo thời gian reset cache ngẫu nhiên để trường hợp web quá nhiều truy cập thì cũng
    // hạn chế được người tham gia vào quá trình reset cache
    $time_for_begin_reset_cache = rand(250, 350);

    //
    WGR_display($ebsuppercache_filename, $time_for_begin_reset_cache);
}
