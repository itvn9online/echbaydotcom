<?php

/*
[echbay_footer_menu class="footer1-menu"]
*/

function action_echbay_footer_menu( $ops = [] ) {
    if ( !isset( $ops[ 'class' ] ) ) {
        $ops[ 'class' ] = '';
    }

    //
    return '<div class="ebe-scode-menu ' . $ops[ 'class' ] . '">' . EBE_echbay_footer_menu() . '</div>';
}
add_shortcode( 'echbay_footer_menu', 'action_echbay_footer_menu' );