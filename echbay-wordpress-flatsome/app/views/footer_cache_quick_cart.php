<?php

/*
 * quick cart
 */
//if ( $act != 'cart' ) {
global $pid;
global $__post;
global $__cf_row;
if ( !defined( 'FLATSOME_BASIC_THEME' ) ) {
    include EB_THEME_PLUGIN_INDEX . 'quick_cart.php';
}
//}


/*
 * thêm NAV menu cho bản mobile
 */
if ( wp_is_mobile() ) {
    //echo 'cf_search_nav_mobile: ' . $__cf_row[ 'cf_search_nav_mobile' ] . '<br>' . "\n";
    if ( $__cf_row[ 'cf_search_nav_mobile' ] != 'none' ) {
        // menu dành cho bản mobile
        $str_nav_mobile_top = _eb_echbay_menu( 'nav-for-mobile' );

        //
        include EB_THEME_PLUGIN_INDEX . 'mobile/nav.php';

        //
        //echo $__cf_row['cf_search_nav_mobile'] . '<br>' . "\n";
        Wgr::$eb->BaseModelWgr->adds_css( [
            EB_THEME_PLUGIN_INDEX . 'html/search/' . $__cf_row[ 'cf_search_nav_mobile' ] . '.css',
        ] );

        //
        echo $html_search_nav_mobile;
    }
}