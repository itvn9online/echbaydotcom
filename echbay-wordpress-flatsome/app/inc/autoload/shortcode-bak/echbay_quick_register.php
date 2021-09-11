<?php

/*
[echbay_quick_register name="frm_dk_nhantin"]
*/

function action_echbay_quick_register( $ops = [] ) {
    if ( empty( $ops ) ) {
        $ops = [];
    }
    //print_r( $ops );

    //
    if ( !isset( $ops[ 'name' ] ) ) {
        $ops[ 'name' ] = 'frm_dk_nhantin';
    }

    //
    return WGR_get_quick_register( $ops[ 'name' ] );
}
add_shortcode( 'echbay_quick_register', 'action_echbay_quick_register' );