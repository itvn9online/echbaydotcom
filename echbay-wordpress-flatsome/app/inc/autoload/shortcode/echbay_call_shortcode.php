<?php
/*
 * Echo shortcode trong flatsome thay vì dùng text của flatsome, hay bị đính kèm thẻ P
 */
function add_echbay_call_shortcode() {
    add_ux_builder_shortcode( 'echbay_call_shortcode', array(
        'name' => 'Echbay call Shortcode',
        'category' => 'Echbay',
        //'priority' => 1,
        'options' => array(
            'for_shortcode' => array(
                'type' => 'textfield',
                'heading' => 'Shortcode',
                'default' => '',
                'placeholder' => 'Shortcode',
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
add_action( 'ux_builder_setup', 'add_echbay_call_shortcode' );

// gọi short code từ UX Builder
function action_echbay_call_shortcode( $atts ) {
    extract( shortcode_atts( array(
        'for_shortcode' => '',
        'custom_class' => '',
    ), $atts ) );

    //
    if ( $for_shortcode == '' ) {
        return __FUNCTION__ . ' for_shortcode is empty!';
    }

    // gọi tới function của widget shortcode
    $html = do_shortcode( '[' . ltrim( rtrim( $for_shortcode, ']' ), '[' ) . ']' );
    if ( $custom_class != '' ) {
        $html = '<div class="' . $custom_class . '">' . $html . '</div>';
    }
    return $html;
}
add_shortcode( 'echbay_call_shortcode', 'action_echbay_call_shortcode' );