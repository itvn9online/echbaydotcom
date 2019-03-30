<?php



/*
* Widget bản đồ google
*/
class ___echbay_widget_google_map extends WP_Widget {
	function __construct() {
		parent::__construct ( 'echbay_gg_map', 'EchBay iframe (google map)', array (
				'description' => 'Nhúng iframe vào website (thường dùng cho việc nhúng google map)' 
		) );
	}
	
	function form($instance) {
		$default = array (
				'title' => 'EchBay GG Map',
				'url_video' => '',
				'width' => '',
				'height' => '',
				'css' => ''
		);
		$instance = wp_parse_args ( ( array ) $instance, $default );
		foreach ( $instance as $k => $v ) {
			$$k = esc_attr ( $v );
		}
		/*
		$title = esc_attr ( $instance ['title'] );
		$url_video = esc_attr ( $instance ['url_video'] );
		*/
		
		echo '<p>Title: <input type="text" class="widefat" name="' . $this->get_field_name ( 'title' ) . '" value="' . $title . '" /></p>';
		
		echo '<p>URL map: <input type="text" class="widefat" name="' . $this->get_field_name ( 'url_video' ) . '" value="' . $url_video . '" /></p>';
		
		echo '<p>Chiều rộng: <input type="number" class="tiny-text" name="' . $this->get_field_name ( 'width' ) . '" value="' . $width . '" /></p>';
		
		echo '<p>Chiều cao: <input type="number" class="tiny-text" name="' . $this->get_field_name ( 'height' ) . '" value="' . $height . '" /></p>';
		
		echo '<p>Custom css: <input type="text" class="widefat" name="' . $this->get_field_name ( 'css' ) . '" value="' . $css . '" /></p>';
	}
	
	function update($new_instance, $old_instance) {
		$instance = _eb_widget_parse_args ( $new_instance, $old_instance );
		return $instance;
	}
	
	function widget($args, $instance) {
//		global $func;
		
		extract ( $args );
		
		$title = apply_filters ( 'widget_title', $instance ['title'] );
		$url_video = isset( $instance ['url_video'] ) ? $instance ['url_video'] : '';
		$width = isset( $instance ['width'] ) ? $instance ['width'] : '';
		$height = isset( $instance ['height'] ) ? $instance ['height'] : '';
		$css = isset( $instance ['css'] ) ? $instance ['css'] : '';
		
		//
		_eb_echo_widget_name( $this->name, $before_widget );
		
		//
		_eb_echo_widget_title(
			$title,
			'echbay-widget-ggmap-title',
			$before_title
		);
		
		//
		echo '<div data-width="' . $width . '" data-height="' . $height . '" class="url-to-google-map d-none ' . $css . '">' . $url_video . '</div>';
		
		//
		echo $after_widget;
	}
}





