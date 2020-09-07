<?php



/*
* Widget tạo list video youtube
*/
class ___echbay_widget_youtube_video extends WP_Widget {
	function __construct() {
		parent::__construct ( 'echbay_youtube', 'EchBay Youtube', array (
				'description' => 'Tạo danh sách phát Video Youtube' 
		) );
	}
	
	function form($instance) {
		$default = array (
				'title' => 'EchBay Youtube',
				'url_video' => '',
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
		
		echo '<p>URL youtube: <textarea class="widefat" name="' . $this->get_field_name ( 'url_video' ) . '" placeholder="//www.youtube.com/embed/FoxruhmPLs4">' . $url_video . '</textarea></p>';
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
		
		//
		_eb_echo_widget_name( $this->name, $before_widget );
		
		//
		echo '<div class="echbay-widget-youtube-padding">';
		
		//
		_eb_echo_widget_title(
			$title,
			'echbay-widget-youtube-title',
			$before_title
		);
		
		//
		$url_img_video = '';
		$arr_url_video = explode("\n", $url_video);
		foreach ( $arr_url_video as $v ) {
			$v = trim( $v );
			
			if ( $v != '' ) {
				$v = _eb_get_youtube_id( $v );
				
				if ( $v != '' ) {
					$url_img_video .= '<div><img src="' . _eb_get_youtube_img($v) . '" /></div>';
				}
			}
		}
		if ( $url_img_video == '' ) {
			$url_img_video = 'Widget EchBay Youtube video';
		}
		
		//
		echo '<div class="vhidden-xoa">
			<div class="echbay-widget-youtube-remove">' . $url_img_video . '</div>
			<div class="img-max-width vhidden">' . $url_video . '</div>
		</div>';
		
		//
		echo '</div>';
		
		//
		echo $after_widget;
	}
}




