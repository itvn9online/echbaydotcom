<?php

/*
 * Chức năng gọi tới 1 shortcode mà không in thêm thẻ DIV bên ngoài -> hạn chế độ sau cho HTML
 */
class ___echbay_widget_shortcode extends WP_Widget {
    function __construct() {
        parent::__construct( 'echbay_shortcode', 'EchBay call shortcode', array(
            'description' => 'Chức năng gọi tới 1 shortcode mà không in thêm thẻ DIV bên ngoài &rarr; hạn chế độ sau cho HTML'
        ) );
    }

    function form( $instance ) {
        $default = array(
            'title' => 'EchBay Shortcode',
            'shortcode_list' => '',
        );
        $instance = wp_parse_args( ( array )$instance, $default );
        foreach ( $instance as $k => $v ) {
            $$k = esc_attr( $v );
        }

        echo '<p>Title: <input type="text" class="widefat" name="' . $this->get_field_name( 'title' ) . '" value="' . $title . '" /></p>';

        echo '<p class="fix-textarea-height">Danh sách shortcode: <textarea class="widefat" name="' . $this->get_field_name( 'shortcode_list' ) . '" row="10">' . $shortcode_list . '</textarea> <span class="small">Có thể nhập nhiều shortcode khác nhau, mỗi shortcode trên 1 dòng.</span></p>';
    }

    function update( $new_instance, $old_instance ) {
        $instance = _eb_widget_parse_args( $new_instance, $old_instance );
        return $instance;
    }

    function widget( $args, $instance ) {
        //global $func;

        extract( $args );

        $title = apply_filters( 'widget_title', $instance[ 'title' ] );
        $shortcode_list = isset( $instance[ 'shortcode_list' ] ) ? trim( $instance[ 'shortcode_list' ] ) : '';

        //
        if ( trim( $shortcode_list ) == '' ) {
            echo 'Please set shortcode for widget: EchBay call shortcode';
            return false;
        }

        //
        $shortcode_list = explode( "\n", $shortcode_list );
        foreach ( $shortcode_list as $v ) {
            $v = trim( $v );

            echo do_shortcode( $v );
        }
    }
}