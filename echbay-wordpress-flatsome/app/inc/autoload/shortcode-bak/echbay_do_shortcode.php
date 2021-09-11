<?php
/*
 * Tự gọi tới shortcode để không bị add những mã lạ vào
 */
function add_echbay_do_shortcode() {
    add_ux_builder_shortcode( 'echbay_do_shortcode', array(
        'name' => 'Echbay do Shortcode',
        'category' => 'Echbay',
        //'priority' => 1,
        'options' => array(
            'for_shortcode' => array(
                'type' => 'textarea',
                //'heading' => 'Shortcode',
                'default' => '',
                'placeholder' => 'Nhập shortcode',
                'auto_focus' => true,
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
//add_action( 'ux_builder_setup', 'add_echbay_do_shortcode' );

// gọi short code từ UX Builder
function action_echbay_do_shortcode( $atts ) {
    extract( shortcode_atts( array(
        'for_shortcode' => '',
        'custom_class' => '',
    ), $atts ) );

    //
    if ( empty( $for_shortcode ) ) {
        return __FUNCTION__ . ' for_shortcode is empty!';
    }

    //
    //$a = wp_list_widgets();
    //print_r( $a );
    return $for_shortcode;
    $html = do_shortcode( $for_shortcode );
    if ( !empty( $custom_class ) ) {
        $html = '<div class="' . $custom_class . '">' . $html . '</div>';
    }
    return $html;
}
//add_shortcode( 'echbay_do_shortcode', 'action_echbay_do_shortcode' );