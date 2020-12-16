<?php


//
function ___eb_cache_getUrl( $cache_dir = 'all' ) {
    if ( isset( $_SERVER[ 'REQUEST_URI' ] ) ) {
        $url = $_SERVER[ 'REQUEST_URI' ];
    } else {
        $url = $_SERVER[ 'SCRIPT_NAME' ];
        $url .= ( !empty( $_SERVER[ 'QUERY_STRING' ] ) ) ? '?' . $_SERVER[ 'QUERY_STRING' ] : '';
    }
    if ( $url == '/' || $url == '' ) {
        $url = '-';
    } else {
        $arr_cat_social_parameter = array(
            'fbclid=',
            'gclid=',
            'fb_comment_id=',
            'utm_'
        );
        foreach ( $arr_cat_social_parameter as $v ) {
            $url = explode( '?' . $v, $url );
            $url = explode( '&' . $v, $url[ 0 ] );
            $url = $url[ 0 ];
        }
        /*
        $url = explode( '?gclid=', $url );
        $url = explode( '&gclid', $url[0] );
        $url = explode( '?utm_', $url[0] );
        $url = explode( '&utm_', $url[0] );
        $url = explode( '?fb_comment_id=', $url[0] );
        $url = explode( '&fb_comment_id', $url[0] );
        $url = $url[0];
        */

        //
        if ( strlen( $url ) > 200 ) {
            $url = md5( $url );
        } else {
            $url = preg_replace( "/\/|\?|\&|\,|\=/", '-', $url );
        }
    }

    //
    $url = EB_THEME_CACHE . $cache_dir . '/' . $url . '.txt';

    //
    return $url;
}