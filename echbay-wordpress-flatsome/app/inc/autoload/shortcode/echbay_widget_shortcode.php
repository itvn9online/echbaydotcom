<?php
/*
 * Echo widget thông qua plugin widget shortcode
 */
function add_echbay_widget_shortcode() {
    // lấy danh sách widget đã được tạo
    $arr_list_widget = get_option( 'sidebars_widgets' );
    if ( empty( $arr_list_widget ) ) {
        return false;
    }

    //
    $ops_list = [
        '' => '- Chọn widget -',
    ];
    foreach ( $arr_list_widget as $k => $v ) {
        if ( !is_array( $v ) || empty( $v ) ) {
            continue;
        }

        //
        foreach ( $v as $k2 => $v2 ) {
            $ops_list[ $v2 ] = $v2;
        }
    }
    ksort( $ops_list );

    //
    add_ux_builder_shortcode( 'echbay_widget_shortcode', array(
        'name' => 'Echbay widget Shortcode',
        'category' => 'Echbay',
        //'priority' => 1,
        'options' => array(
            'for_shortcode' => array(
                'type' => 'select',
                'heading' => 'Shortcode',
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
add_action( 'ux_builder_setup', 'add_echbay_widget_shortcode' );

// gọi short code từ UX Builder
function action_echbay_widget_shortcode( $atts ) {
    extract( shortcode_atts( array(
        'for_shortcode' => '',
        'custom_class' => '',
    ), $atts ) );

    //
    if ( $for_shortcode == '' ) {
        return __FUNCTION__ . ' for_shortcode is empty!';
    }

    //
    //print_r( $GLOBALS[ 'wp_widget_factory' ] );
    //print_r( get_option( 'sidebars_widgets' ) );
    //print_r( $GLOBALS[ 'wp_widget_factory' ]->widgets );
    //
    //$a = wp_list_widgets();
    //print_r( $a );
    //return $for_shortcode;

    // gọi tới function của widget shortcode
    $html = do_shortcode( '[widget id="' . $for_shortcode . '"]' );
    if ( !empty( $custom_class ) ) {
        $html = '<div class="' . $custom_class . '">' . $html . '</div>';
    }
    return $html;
}
add_shortcode( 'echbay_widget_shortcode', 'action_echbay_widget_shortcode' );