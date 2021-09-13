<?php
/*
 * Lấy danh sách sản phẩm và hiển thị dạng thu gọn
 */
function add_echbay_product_small() {
    if ( !class_exists( '___echbay_widget_random_product' ) ) {
        die( 'class ___echbay_widget_random_product not exist!' );
    }
    $product_small = new ___echbay_widget_random_product();

    //
    add_ux_builder_shortcode( 'echbay_product_small', array(
        'name' => 'Echbay product small',
        'category' => 'Echbay',
        //'priority' => 1,
        'options' => array(
            'for_shortcode' => array(
                'type' => 'select',
                'heading' => 'Shortcode',
                'default' => '',
                'options' => [],
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
//add_action( 'ux_builder_setup', 'add_echbay_product_small' );

// gọi short code từ UX Builder
function action_echbay_product_small( $atts ) {
    extract( shortcode_atts( array(
        'for_shortcode' => '',
        'custom_class' => '',
    ), $atts ) );

    //
    if ( empty( $for_shortcode ) ) {
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
//add_shortcode( 'echbay_product_small', 'action_echbay_product_small' );