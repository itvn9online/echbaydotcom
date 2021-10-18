<?php
/*
 * Chức năng gọi tới các menu dựng sẵn của echbaydotcom
 */
function add_echbay_call_menu() {
    global $arr_to_add_menu;

    $ops_list = [
        '' => '- Chọn menu -',
    ];
    foreach ( $arr_to_add_menu as $k => $v ) {
        $ops_list[ $k ] = $v;
    }

    //
    add_ux_builder_shortcode( 'echbay_call_menu', array(
        'name' => 'Echbay Call Menu',
        'category' => 'Echbay',
        //'priority' => 1,
        'options' => array(
            'call_menu' => array(
                'type' => 'select',
                'heading' => 'Menu',
                'default' => '',
                'options' => $ops_list,
            ),
            'custom_class' => array(
                'type' => 'textfield',
                'heading' => 'Class CSS',
                'default' => '',
                'placeholder' => 'Tùy chỉnh CSS',
            ),
        ),
    ) );
}
add_action( 'ux_builder_setup', 'add_echbay_call_menu' );

// gọi short code từ UX Builder
function action_echbay_call_menu( $atts ) {
    extract( shortcode_atts( array(
        'call_menu' => '',
        'custom_class' => '',
    ), $atts ) );

    //
    if ( $call_menu == '' ) {
        return __FUNCTION__ . ' call_menu is empty!';
    }

    //
    return _eb_echbay_menu( $call_menu );
}
add_shortcode( 'echbay_call_menu', 'action_echbay_call_menu' );