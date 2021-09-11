<?php

/*
[echbay_top_menu class="top1-menu"]
*/

function action_echbay_top_menu( $ops = [] ) {
    global $i_echbay_top_menu;

    //
    if ( empty( $ops ) ) {
        $ops = [];
    }
    //print_r( $ops );

    //
    if ( !isset( $ops[ 'class' ] ) ) {
        $ops[ 'class' ] = '';
    }

    //
    return '<div class="ebe-scode-menu top' . $i_echbay_top_menu . '-scode-menu ' . $ops[ 'class' ] . '">' . EBE_echbay_top_menu() . '</div>';
}
add_shortcode( 'echbay_top_menu', 'action_echbay_top_menu' );