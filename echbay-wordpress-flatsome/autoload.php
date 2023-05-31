<?php
//if ( !defined( 'ABSPATH' ) )die( 'No direct script access allowed #' . basename( __FILE__ . ':' . __LINE__ ) );

/*
 * Thêm đoạn code này vào file function trong child-theme flatsome
require dirname( dirname( __DIR__ ) ) . '/echbay-wordpress-flatsome/autoload.php';
 */

/*
 * Do đoạn code viewport của flatsome chưa đạt chuẩn điểm của google page speed -> cần phải chỉnh lại
 */
// Remove Header Viewport Meta 
function remove_flatsome_viewport_meta()
{
    remove_action('wp_head', 'flatsome_viewport_meta', 1);
}
add_action('init', 'remove_flatsome_viewport_meta', 15);


define('WGR_APP_PATH', __DIR__ . '/app/');
//define( 'WGR_PUBLIC_PATH', __DIR__ . '/public/' );
//define( 'WGR_DEFAULT_SIDEBAR', 'main_sidebar' );

/*
 * Kết nối tới theme echbaytwo
 */
if (!defined('ECHBAY_TWO_THEME')) {
    define('ECHBAY_TWO_THEME', dirname(EB_CHILD_THEME_URL) . '/echbaytwo/');
}
//die( ECHBAY_TWO_THEME );
require ECHBAY_TWO_THEME . 'functions.php';


// global static class
class Wgr
{
    public static $eb = [];
}

// autoload MVC file
$arr_wgr_autoload = [
    // autoload system file (main file)
    'system',
    // autoload model
    'app/models',
    // autoload controllers
    'app/controllers',
];

foreach ($arr_wgr_autoload as $v) {
    foreach (glob(__DIR__ . '/' . $v . '/*.php') as $filename) {
        //echo $filename . '<br>' . "\n";
        include $filename;

        //
        $classNoExt = basename($filename, '.php');
        //echo $classNoExt . '<br>' . "\n";
        Wgr::$eb[$classNoExt] = new $classNoExt();
    }
}

// autoload library and conts file
foreach (glob(__DIR__ . '/app/libraries/*.php') as $filename) {
    //echo $filename . '<br>' . "\n";
    include $filename;
}

// conver to object
//print_r( Wgr::$eb );
Wgr::$eb = (object)Wgr::$eb;
//print_r( Wgr::$eb );

// TEST autoload
//Wgr::$eb->BaseModelWgr->a();
//Wgr::$eb->PostModelWgr->test();
//echo PostType::SHOW . '<br>' . "\n";
/*
$baseModelWgr = new BaseModelWgr();
$baseModelWgr->a();
$postModelWgr = new PostModelWgr();
$postModelWgr->test();
*/

// nạp tất cả các file trong autoload
foreach (glob(WGR_APP_PATH . 'inc/autoload/*.php') as $filename) {
    //echo $filename . '<br>' . "\n";
    include $filename;
}

foreach (glob(WGR_APP_PATH . 'inc/autoload/shortcode/*.php') as $filename) {
    //echo $filename . '<br>' . "\n";
    include $filename;
}

//
if (
    $_SERVER['HTTP_HOST'] != 'webgiare.org'
    && $_SERVER['HTTP_HOST'] != 'www.webgiare.org'
    && strpos($_SERVER['HTTP_HOST'], 'world.webgiare.org') !== false
    && is_admin()
    //&& defined('EB_CHILD_THEME_URL')
) {
    $flatsome_function_update = dirname(EB_CHILD_THEME_URL) . '/flatsome/inc/functions/function-update.php';
    //echo $flatsome_function_update . PHP_EOL;
    // nếu còn tồn tại chuỗi _site_transient_update_themes -> vẫn còn đang dùng code của flatsome
    if (
        file_exists($flatsome_function_update)
        && strpos(file_get_contents($flatsome_function_update), 'webgiare_v3_update_themes') === false
    ) {
        //die(__FILE__ . ':' . __LINE__);
        echo $flatsome_function_update . PHP_EOL;

        // copy file mẫu ghi đè vào file của flatsome
        copy('https://raw.githubusercontent.com/itvn9online/webgiareorg/main/function-update.php', $flatsome_function_update);
    }
}
//die(WGR_APP_PATH);
//die(EB_CHILD_THEME_URL);
//die(__FILE__ . ':' . __LINE__);
