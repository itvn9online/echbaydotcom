<?php
//if ( !defined( 'ABSPATH' ) )die( 'No direct script access allowed #' . basename( __FILE__ . ':' . __LINE__ ) );

/*
 * Thêm đoạn code này vào file function trong child-theme flatsome
require dirname( dirname( __DIR__ ) ) . '/echbay-wordpress-flatsome/autoload.php';
 */

define( 'WGR_APP_PATH', __DIR__ . '/app/' );
//define( 'WGR_PUBLIC_PATH', __DIR__ . '/public/' );
//define( 'WGR_DEFAULT_SIDEBAR', 'main_sidebar' );

/*
 * Kết nối tới theme echbaytwo
 */
define( 'ECHBAY_TOW_THEME', dirname( EB_CHILD_THEME_URL ) . '/echbaytwo/' );
//die( ECHBAY_TOW_THEME );
require ECHBAY_TOW_THEME . 'functions.php';


// global static class
class Wgr {
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

foreach ( $arr_wgr_autoload as $v ) {
    foreach ( glob( __DIR__ . '/' . $v . '/*.php' ) as $filename ) {
        //echo $filename . '<br>' . "\n";
        include $filename;

        //
        $classNoExt = basename( $filename, '.php' );
        //echo $classNoExt . '<br>' . "\n";
        Wgr::$eb[ $classNoExt ] = new $classNoExt();
    }
}

// autoload library and conts file
foreach ( glob( __DIR__ . '/app/libraries/*.php' ) as $filename ) {
    //echo $filename . '<br>' . "\n";
    include $filename;
}

// conver to object
//print_r( Wgr::$eb );
Wgr::$eb = ( object )Wgr::$eb;
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
foreach ( glob( WGR_APP_PATH . 'inc/autoload/*.php' ) as $filename ) {
    //echo $filename . '<br>' . "\n";
    include $filename;
}