<?php

//
include_once __DIR__ . '/ebcache_global.php';

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
include_once __DIR__ . '/main_function.php';
$ebsuppercache_filename = ___eb_cache_getUrl();
// if (isset($_GET['sdgs34fgjdfdf34dhdhdf'])) {
//     die($ebsuppercache_filename);
// }

//
// echo date('Y-m-d H:i:s', date_time);

// nếu tồn tại cookie wgr_ebsuppercache_timeout -> người dùng đang đăng nhập -> bỏ
if (isset($_COOKIE['wgr_ebsuppercache_timeout']) || $_SERVER['REQUEST_METHOD'] == 'POST') {
    // đăng nhập rồi thì bỏ qua -> không nạp cache
    // echo 'wgr_ebsuppercache_timeout' . '<br>' . PHP_EOL;
    // echo $ebsuppercache_filename . '<br>' . PHP_EOL;
}
// nếu có dùng redis cache thì nhảy vào đây
else if (EB_REDIS_CACHE == true) {
    /**
     * tạo thời gian reset cache ngẫu nhiên để trường hợp web quá nhiều truy cập thì cũng
     * hạn chế được người tham gia vào quá trình reset cache
     */
    WGR_display($ebsuppercache_filename, mt_rand(250, 350));
}
//
else if (is_file($ebsuppercache_filename)) {
    /**
     * tạo thời gian reset cache ngẫu nhiên để trường hợp web quá nhiều truy cập thì cũng
     * hạn chế được người tham gia vào quá trình reset cache
     */
    WGR_display($ebsuppercache_filename, mt_rand(250, 350));
}
