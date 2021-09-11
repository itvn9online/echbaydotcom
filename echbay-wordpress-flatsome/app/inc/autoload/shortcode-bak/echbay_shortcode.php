<?php

/*
* Chức năng gọi các function của echbaydotcom
[echbay_shortcode function="EBE_get_html_logo"]
*/

function action_echbay_shortcode( $ops = [] ) {
    if ( empty( $ops ) ) {
        return __FUNCTION__ . ' parameter function not found!';
    }
    //print_r( $ops );

    //
    if ( function_exists( $ops[ 'function' ] ) ) {
        return $ops[ 'function' ]();
    }
    return __FUNCTION__ . ' function ' . $ops[ 'function' ] . ' not exist!';
}
add_shortcode( 'echbay_shortcode', 'action_echbay_shortcode' );