<?php

//
$EB_THEME_CACHE = dirname(__DIR__) . '/uploads/ebcache/' . explode(':', str_replace('www.', '', $_SERVER['HTTP_HOST']))[0] . '/';

// thư mục cache dùng chung, không phân biệt mobile với desktop
define('EB_GLOBAL_CACHE', $EB_THEME_CACHE);


//
$arr_current_blacklist_ip = [];
$inc_current_blacklist_ip = EB_GLOBAL_CACHE . 'current_blacklist_ip.php';

//
if (file_exists($inc_current_blacklist_ip)) {
    include $inc_current_blacklist_ip;
    //print_r( $arr_current_blacklist_ip );
}
