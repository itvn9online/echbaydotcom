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
				'localtion' => '',
				'url_video' => '',
				'width' => '',
				'height' => '',
				'zoom' => 14,
				'scrolling' => 1,
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
		
		echo '<p class="fix-textarea-height">Địa chỉ (map): <textarea class="widefat" name="' . $this->get_field_name ( 'localtion' ) . '">' . $localtion . '</textarea> <span class="small">Chỉ dùng cho google map. Khi bạn có nhiều địa chỉ khác nhau, hãy liệt kê các địa chỉ này ra đây! Mỗi địa chỉ trên một dòng.</span></p>';
		
		echo '<p>URL map hoặc iframe: <input type="text" class="widefat" name="' . $this->get_field_name ( 'url_video' ) . '" value="' . $url_video . '" /></p>';
		
		echo '<p>Chiều rộng: <input type="number" class="tiny-text" name="' . $this->get_field_name ( 'width' ) . '" value="' . $width . '" /></p>';
		
		echo '<p>Chiều cao: <input type="number" class="tiny-text" name="' . $this->get_field_name ( 'height' ) . '" value="' . $height . '" /></p>';
		
		echo '<p>Zoom map: <input type="number" class="tiny-text" name="' . $this->get_field_name ( 'zoom' ) . '" value="' . $zoom . '" /></p>';
		
		_eb_widget_echo_widget_input_checkbox( $this->get_field_name ( 'scrolling' ), $scrolling, 'Ngăn chặn cuộn! Giúp thao tác cuộn chuột trên website không bị gián đoạn.' );
		
		echo '<p>Custom css: <input type="text" class="widefat" name="' . $this->get_field_name ( 'css' ) . '" value="' . $css . '" /></p>';
		
		echo '<script>fix_textarea_height();</script>';
	}
	
	function update($new_instance, $old_instance) {
		$instance = _eb_widget_parse_args ( $new_instance, $old_instance );
		return $instance;
	}
	
	function widget($args, $instance) {
//		global $func;
		
		extract ( $args );
		
		$title = apply_filters ( 'widget_title', $instance ['title'] );
		$localtion = isset( $instance ['localtion'] ) ? trim( $instance ['localtion'] ) : '';
		$url_video = isset( $instance ['url_video'] ) ? $instance ['url_video'] : '';
		$width = isset( $instance ['width'] ) ? $instance ['width'] : '';
		$height = isset( $instance ['height'] ) ? $instance ['height'] : '';
		$zoom = isset( $instance ['zoom'] ) ? $instance ['zoom'] : '';
		$css = isset( $instance ['css'] ) ? $instance ['css'] : '';
		$scrolling = 'on';
		if ( ! isset( $instance ['scrolling'] ) || $instance ['scrolling'] != 'on' ) {
			$scrolling = 'off';
		}
		
		//
		_eb_echo_widget_name( $this->name, $before_widget );
		
		//
		_eb_echo_widget_title(
			$title,
			'echbay-widget-ggmap-title',
			$before_title
		);
		
		//
		$aria_label = '';
		if ( $localtion != '' ) {
			$localtion = explode( "\n", $localtion );
			$aria_label = array();
			foreach ( $localtion as $v ) {
				$v = trim( $v );
				if ( $v != '' ) {
					$aria_label[] = '"' . $v . '"';
				}
			}
			$aria_label = str_replace( '"', '&quot;', implode( ' ', $aria_label ) );
		}
		
		//
		echo '<div data-width="' . $width . '" data-height="' . $height . '" data-localtion="' . $aria_label . '" data-scrolling="' . $scrolling . '" data-zoom="' . $zoom . '" class="url-to-google-map d-none ' . $css . '">' . $url_video . '</div>';
		
		//
		echo $after_widget;
	}
}





