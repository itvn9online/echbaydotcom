<?php

/*
[echbay_address title="Liên hệ"]
*/

function action_echbay_address( $ops = [] ) {
    EBE_html_address( $ops );
}
add_shortcode( 'echbay_address', 'action_echbay_address' );