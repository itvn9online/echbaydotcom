<?php



/*
* Widget blog ngẫu nhiên
*/
class ___echbay_widget_advanced_run_slider extends WP_Widget {
	function __construct() {
		parent::__construct ( 'echbay_set_adsense_code', 'zEchBay GAdsense', array (
				'description' => 'Nhúng mã Google Adsense vào website' 
		) );
	}
	
	function form($instance) {
		global $arr_to_add_menu;
		
		$default = array (
			'id_class' => '',
			'autoplay' => '',
			'swipemobile' => '',
			'buttonListNext' => '',
			'size' => '',
			'for_class' => '',
			'sliderArrow' => '',
			'sliderArrowLeft' => 'fa-angle-left',
			'sliderArrowRight' => '',
			'sliderArrowSize' => '30',
			'sliderArrowWidthLeft' => '',
			'sliderArrowWidthRight' => '',
			'speed' => '',
			'speedNext' => '',
			'thumbnail' => '',
			'thumbnailWidth' => '',
			'thumbnailHeight' => '',
			'thumbnailSlider' => '',
			'visible' => '1'
		);
		$instance = wp_parse_args ( ( array ) $instance, $default );
		foreach ( $instance as $k => $v ) {
			$$k = esc_attr ( $v );
		}
		
		
		//
		_eb_widget_echo_widget_input_title( $this->get_field_name('id_class'), $id_class, 'ID/ Class' );
		echo '<p>ID hoặc class của thẻ HTML cần tạo slider. Ví dụ: .slider1, #slider2</p>';
		
		//
		_eb_widget_echo_widget_input_checkbox( $this->get_field_name('autoplay'), $autoplay, 'Tự động chạy' );
		_eb_widget_echo_widget_input_checkbox( $this->get_field_name('speedNext'), $speedNext, 'Giãn cách chuyển slider nếu tính năng tự động chạy được bật' );
		_eb_widget_echo_widget_input_checkbox( $this->get_field_name('swipemobile'), $swipemobile, 'Cho phép chuyển ảnh trên mobile bẳng touch' );
		_eb_widget_echo_widget_input_checkbox( $this->get_field_name('buttonListNext'), $buttonListNext, 'Hiển thị nút bấm chuyển ảnh' );
		_eb_widget_echo_widget_input_checkbox( $this->get_field_name('size'), $size, 'Tỷ lệ giữa chiều cao và chiều rộng, thiết lập dưới dạng: height/width (thay height và width bằng các con số cụ thể)' );
		_eb_widget_echo_widget_input_checkbox( $this->get_field_name('for_class'), $for_class, 'Mặc định là điều khiển các thẻ LI làm slider, nếu muốn thẻ khác hãy đặt theo class hoặc ID. Ví dụ: .node1, #node2' );
		
		_eb_widget_echo_widget_input_checkbox( $this->get_field_name('sliderArrow'), $sliderArrow, 'Nút bấm chuyển ảnh trên slider' );
		_eb_widget_echo_widget_input_checkbox( $this->get_field_name('sliderArrowLeft'), $sliderArrowLeft, 'Icon cho nút bấm bên trái' );
		_eb_widget_echo_widget_input_checkbox( $this->get_field_name('sliderArrowRight'), $sliderArrowRight, 'Icon cho nút bấm bên phải' );
		echo '<p>Các icon trên web sử dụng Font Awesome tại đây: <a href="https://fontawesome.com/icons?d=gallery&m=free" target="_blank" rel="nofollow">https://fontawesome.com/icons?d=gallery&m=free</a></p>';
		
		_eb_widget_echo_widget_input_checkbox( $this->get_field_name('sliderArrowSize'), $sliderArrowSize, 'Kích thước cho nút bấm (font-size), tính theo pixel (px)' );
		
		_eb_widget_echo_widget_input_checkbox( $this->get_field_name('sliderArrowWidthLeft'), $sliderArrowWidthLeft, 'Chiều rộng cố định cho nút bấm bên trái (nhập đầy đủ cả đơn vị, ví dụ: 40% hoặc 200px)' );
		_eb_widget_echo_widget_input_checkbox( $this->get_field_name('sliderArrowWidthRight'), $sliderArrowWidthRight, 'Chiều rộng cố định cho nút bấm bên phải (nhập đầy đủ cả đơn vị, ví dụ: 40% hoặc 200px)' );
		_eb_widget_echo_widget_input_checkbox( $this->get_field_name('speed'), $speed, 'Tốc độ chuyển slider' );
		_eb_widget_echo_widget_input_checkbox( $this->get_field_name('thumbnail'), $thumbnail, 'aaaaaaaa' );
		_eb_widget_echo_widget_input_checkbox( $this->get_field_name('thumbnailWidth'), $thumbnailWidth, 'aaaaaaaa' );
		_eb_widget_echo_widget_input_checkbox( $this->get_field_name('thumbnailSlider'), $thumbnailSlider, 'Tạo slider cho thumbnail' );
		_eb_widget_echo_widget_input_checkbox( $this->get_field_name('visible'), $visible, 'Số lượng thẻ LI muốn hiển thị trên mỗi loạt slider' );
		
	}
	
	function update($new_instance, $old_instance) {
		$instance = _eb_widget_parse_args ( $new_instance, $old_instance );
		return $instance;
	}
	
	function widget($args, $instance) {
		global $__cf_row;
		global $act;
		global $arr_active_for_404_page;
		
		extract ( $args );
		
		
		//
		$id_class = isset( $instance ['id_class'] ) ? $instance ['id_class'] : '';
//		$hide_quick_view = isset( $instance ['hide_quick_view'] ) ? $instance ['hide_quick_view'] : 'off';
		
		
		//
//		_eb_echo_widget_name( $this->name, $before_widget );
		echo '<!-- ' . $this->name . ' (' . $id_class . ') -->';
		
		//
		echo $code;
	}
}




