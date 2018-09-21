<?php



/*
* Widget blog ngẫu nhiên
*/
class ___echbay_widget_facebook_likebox extends WP_Widget {
	function __construct() {
		parent::__construct ( 'echbay_set_social_like_box', 'zEchBay Facebook Likebox', array (
				'description' => 'Thêm hộp like của Facebook vào website' 
		) );
	}
	
	function form($instance) {
		global $arr_to_add_menu;
		
		$default = array (
			'title' => 'EchBay Facebook Likebox',
			'custom_style' => '',
			'full_mobile' => '',
			'hide_mobile' => ''
		);
		$instance = wp_parse_args ( ( array ) $instance, $default );
		foreach ( $instance as $k => $v ) {
			$$k = esc_attr ( $v );
		}
		
		//
		$arr_field_name = array();
		foreach ( $default as $k => $v ) {
			$arr_field_name[ $k ] = $this->get_field_name ( $k );
		}
		
		
		// form dùng chung cho phần top, footer
		_eb_top_footer_form_for_widget( $instance, $arr_field_name );
		
	}
	
	function update($new_instance, $old_instance) {
		$instance = _eb_widget_parse_args ( $new_instance, $old_instance );
		return $instance;
	}
	
	function widget($args, $instance) {
		
		extract ( $args );
		
//		$title = apply_filters ( 'widget_title', $instance ['title'] );
		$title = isset( $instance ['title'] ) ? $instance ['title'] : '';
		
		$custom_style = isset( $instance ['custom_style'] ) ? $instance ['custom_style'] : '';
		
		$hide_mobile = isset( $instance ['hide_mobile'] ) ? $instance ['hide_mobile'] : 'off';
//		$hide_mobile = $hide_mobile == 'on' ? ' hide-if-mobile' : '';
		if ( $hide_mobile == 'on' ) $custom_style .= ' hide-if-mobile';
		
		$full_mobile = isset( $instance ['full_mobile'] ) ? $instance ['full_mobile'] : 'off';
		if ( $full_mobile == 'on' ) $custom_style .= ' fullsize-if-mobile';
		
		
		//
//		_eb_echo_widget_name( $this->name, $before_widget );
		echo '<!-- ' . $this->name . ' (' . $title . ') -->';
		
		//
		echo '<div class="' . str_replace( '  ', ' ', trim( $custom_style ) ) . '">';
		
		//
		if ( $title != '' ) {
			echo '<div class="facebook-likebox-title">' . $title . '</div>';
		}
		
		//
		echo WGR_get_fb_like_box();
		
		echo '</div>';
		
		//
//		echo $after_widget;
	}
}




