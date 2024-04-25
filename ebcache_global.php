<?php

//
//$EB_THEME_CACHE = dirname(__DIR__) . '/uploads/ebcache/' . explode(':', str_replace('www.', '', $_SERVER['HTTP_HOST']))[0] . '/';
// thư mục ebcache luôn cho vào uploads để đảm bảo lệnh tạo thư mục sẽ luôn được thực thi do permission
$sub_dir_cache = ['uploads', 'ebcache'];
$cache_prefix = '_';
// bỏ cái chế độ cache theo tên miền đi -> 1 host ko nên chạy nhiều tên miền
// $cache_prefix = str_replace('www.', '', str_replace('.', '', str_replace('-', '_', explode(':', $_SERVER['HTTP_HOST'])[0])));

//
if (!function_exists('wp_is_mobile')) {
    // fake function wp_is_mobile of wordpress
    function WGR_is_mobile()
    {
        $is_mobile = false;
        if (!empty($_SERVER['HTTP_USER_AGENT'])) {
            $a = $_SERVER['HTTP_USER_AGENT'];
            if (
                // Many mobile devices (all iPhone, iPad, etc.)
                strpos($a, 'Mobile') !== false ||
                strpos($a, 'Android') !== false ||
                strpos($a, 'Silk/') !== false ||
                strpos($a, 'Kindle') !== false ||
                strpos($a, 'BlackBerry') !== false ||
                strpos($a, 'Opera Mini') !== false ||
                strpos($a, 'Opera Mobi') !== false
            ) {
                $is_mobile = true;
            }
        }

        //
        return $is_mobile;
    }

    //
    if (WGR_is_mobile()) {
        //$EB_THEME_CACHE .= 'm/';
        $cache_prefix .= '_m';
        // $sub_dir_cache[] = '_m';
    }
} else if (wp_is_mobile()) {
    //$EB_THEME_CACHE .= 'm/';
    $cache_prefix .= '_m';
    // $sub_dir_cache[] = '_m';
}
$sub_dir_cache[] = $cache_prefix;
//print_r($sub_dir_cache);

// tự động tạo thư mục cache nếu chưa có
$root_dir_cache = dirname(__DIR__);
foreach ($sub_dir_cache as $v) {
    $root_dir_cache .= '/' . $v;

    //
    if (!is_dir($root_dir_cache)) {
        mkdir($root_dir_cache, 0777);
        chmod($root_dir_cache, 0777) or die('ERROR chmod cache dir');
        echo $root_dir_cache . '<br>' . PHP_EOL;
    }
}

// thư mục cache có phân biệt mobile với desktop
//define('EB_THEME_CACHE', $EB_THEME_CACHE);
defined('EB_THEME_CACHE') || define('EB_THEME_CACHE', $root_dir_cache . '/');
//die(EB_THEME_CACHE);
//define( 'EB_SUB_THEME_CACHE', str_replace( ABSPATH, '', EB_THEME_CACHE ) );
//echo EB_SUB_THEME_CACHE . '<br>' . PHP_EOL;
//echo EB_THEME_CACHE . '<br>' . PHP_EOL;
//echo '<!-- ' . EB_THEME_CACHE . ' -->';

// thư mục cache dùng chung, không phân biệt mobile với desktop
//define('EB_GLOBAL_CACHE', $EB_THEME_CACHE);
//echo EB_GLOBAL_CACHE . '<br>';

// file nạp config kết nối database
define('EB_MY_CACHE_CONFIG', dirname(EB_THEME_CACHE) . '/my-config.php');
//echo EB_MY_CACHE_CONFIG . '<br>' . PHP_EOL;

//
if (is_file(EB_MY_CACHE_CONFIG)) {
    include EB_MY_CACHE_CONFIG;

    //
    if (!empty(phpversion('redis'))) {
        // xác định cache qua redis
        if (defined('REDIS_MY_HOST') && defined('REDIS_MY_PORT')) {
            define('EB_REDIS_CACHE', true);
        } else {
            // xóa file my-config nếu có -> vì có mà không có 2 tham số kia thì coi như lỗi
            if (defined('EB_MY_CACHE_CONFIG') && is_file(EB_MY_CACHE_CONFIG)) {
                echo 'Remove file ' . basename(EB_MY_CACHE_CONFIG) . ' because REDIS_MY_HOST not found!' . '<br>' . PHP_EOL;
                unlink(EB_MY_CACHE_CONFIG);
            }
            define('EB_REDIS_CACHE', false);
        }
    } else {
        define('EB_REDIS_CACHE', false);
    }
} else {
    define('EB_REDIS_CACHE', false);
}
//var_dump(EB_REDIS_CACHE);


//
$arr_current_blacklist_ip = [];
//$inc_current_blacklist_ip = EB_GLOBAL_CACHE . 'current_blacklist_ip.php';
$inc_current_blacklist_ip = EB_THEME_CACHE . 'current_blacklist_ip.php';

//
if (is_file($inc_current_blacklist_ip)) {
    include $inc_current_blacklist_ip;
    //print_r( $arr_current_blacklist_ip );
}
