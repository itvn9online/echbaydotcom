<?php



/*
* Widget blog ngẫu nhiên
*/
class ___echbay_widget_advanced_run_slider extends WP_Widget {
	function __construct() {
		parent::__construct ( 'echbay_run_slider', 'zEchBay run slider', array (
				'description' => 'Tạo mã kích hoạt slider của EchBay' 
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
			'sliderArrowLeft' => '',
			'sliderArrowRight' => '',
			'sliderArrowSize' => '',
			'sliderArrowWidthLeft' => '',
			'sliderArrowWidthRight' => '',
			'speed' => '',
			'speedNext' => '',
			'thumbnail' => '',
			'thumbnailWidth' => '',
			'thumbnailHeight' => '',
			'thumbnailSlider' => '',
			'visible' => '',
			'showRandom' => ''
		);
		$instance = wp_parse_args ( ( array ) $instance, $default );
		foreach ( $instance as $k => $v ) {
			$$k = esc_attr ( $v );
		}
		
		
		//
		_eb_widget_echo_widget_input_title( $this->get_field_name('id_class'), $id_class, 'ID/ Class' );
		echo '<p>ID hoặc class của thẻ HTML cần tạo slider. Ví dụ: .slider1, #slider2, <em>.oi_big_banner, .thread-details-mobileAvt, .banner-chan-trang</em></p>';
		
		//
		_eb_widget_echo_widget_input_checkbox( $this->get_field_name('autoplay'), $autoplay, 'Tự động chạy' );
		_eb_widget_echo_widget_input_title( $this->get_field_name('speedNext'), $speedNext, 'Giãn cách chuyển slider nếu tính năng tự động chạy được bật', 'Nhập số giây (số hoặc số thập phân)', '', array(
			'type' => 'number'
		) );
		
		_eb_widget_echo_widget_input_checkbox( $this->get_field_name('swipemobile'), $swipemobile, 'Cho phép chuyển ảnh trên mobile bẳng touch' );
		_eb_widget_echo_widget_input_checkbox( $this->get_field_name('buttonListNext'), $buttonListNext, 'Hiển thị nút bấm chuyển ảnh' );
		
		_eb_widget_echo_widget_input_title( $this->get_field_name('size'), $size, 'Tỷ lệ giữa chiều cao và chiều rộng, thiết lập dưới dạng: height/width (thay height và width bằng các con số cụ thể)', 'Height/Width or auto or full' );
		echo '<p>- <strong>height/width</strong>: thiết lập tỷ lệ cố định<br>
		- <strong>auto</strong>: thiết lập tỷ lệ tự động<br>
		- <strong>full</strong>: thiết lập tỷ lệ bằng với màn hình thiết bị của người dùng<br>
		- <strong>li</strong>: thiết lập tỷ lệ bằng với kích thước của thẻ LI đầu tiên.</p>';
		
		_eb_widget_echo_widget_input_title( $this->get_field_name('for_class'), $for_class, 'Mặc định là điều khiển các thẻ LI làm slider, nếu muốn thẻ khác hãy đặt theo class hoặc ID. Ví dụ: .node1, #node2', '.node1, #node2' );
		
		
		
		
		_eb_widget_echo_widget_input_checkbox( $this->get_field_name('sliderArrow'), $sliderArrow, 'Nút bấm chuyển ảnh trên slider' );
		_eb_widget_echo_widget_input_title( $this->get_field_name('sliderArrowLeft'), $sliderArrowLeft, 'Icon cho nút bấm bên trái', 'fa-angle-left' );
		_eb_widget_echo_widget_input_title( $this->get_field_name('sliderArrowRight'), $sliderArrowRight, 'Icon cho nút bấm bên phải', 'fa-angle-right' );
		echo '<p>Các icon trên web sử dụng Font Awesome tại đây: <a href="https://fontawesome.com/icons?d=gallery&m=free" target="_blank" rel="nofollow">https://fontawesome.com/icons?d=gallery&m=free</a></p>';
		
		_eb_widget_echo_widget_input_title( $this->get_field_name('sliderArrowSize'), $sliderArrowSize, 'Kích thước cho nút bấm (font-size), tính theo pixel (px)', '30', array(
			'type' => 'number'
		) );
		
		
		
		
		_eb_widget_echo_widget_input_title( $this->get_field_name('sliderArrowWidthLeft'), $sliderArrowWidthLeft, 'Chiều rộng cố định cho nút bấm bên trái (nhập đầy đủ cả đơn vị, ví dụ: 40% hoặc 200px)', '40%' );
		_eb_widget_echo_widget_input_title( $this->get_field_name('sliderArrowWidthRight'), $sliderArrowWidthRight, 'Chiều rộng cố định cho nút bấm bên phải (nhập đầy đủ cả đơn vị, ví dụ: 40% hoặc 200px)', '60%' );
		
		
		
		_eb_widget_echo_widget_input_title( $this->get_field_name('speed'), $speed, 'Tốc độ chuyển slider', 'Nhập số giây', array(
			'type' => 'number'
		) );
		
		
		
		_eb_widget_echo_widget_input_checkbox( $this->get_field_name('thumbnail'), $thumbnail, 'Tạo ảnh nhỏ (thumbnail)' );
		_eb_widget_echo_widget_input_title( $this->get_field_name('thumbnailWidth'), $thumbnailWidth, 'Chiều rộng của bộ ảnh nhỏ', '90', array(
			'type' => 'number'
		) );
		_eb_widget_echo_widget_input_title( $this->get_field_name('thumbnailHeight'), $thumbnailHeight, 'Chiều cao của bộ ảnh nhỏ', '90', array(
			'type' => 'number'
		) );
		_eb_widget_echo_widget_input_checkbox( $this->get_field_name('thumbnailSlider'), $thumbnailSlider, 'Tạo slider cho thumbnail' );
		
		
		
		_eb_widget_echo_widget_input_title( $this->get_field_name('visible'), $visible, 'Số lượng thẻ LI muốn hiển thị trên mỗi loạt slider', '1', array(
			'type' => 'number'
		) );
		
		
		_eb_widget_echo_widget_input_checkbox( $this->get_field_name('showRandom'), $showRandom, 'Kích hoạt ngẫu nhiên 1 ảnh bất kỳ trong slider' );
		
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
		$code = '';
		
		
		//
		$id_class = isset( $instance ['id_class'] ) ? $instance ['id_class'] : '';
		$autoplay = isset( $instance ['autoplay'] ) ? $instance ['autoplay'] : 'off';
		$swipemobile = isset( $instance ['swipemobile'] ) ? $instance ['swipemobile'] : 'off';
		$buttonListNext = isset( $instance ['buttonListNext'] ) ? $instance ['buttonListNext'] : 'off';
		$size = isset( $instance ['size'] ) ? $instance ['size'] : '';
		$for_class = isset( $instance ['for_class'] ) ? $instance ['for_class'] : '';
		$sliderArrow = isset( $instance ['sliderArrow'] ) ? $instance ['sliderArrow'] : 'off';
		$sliderArrowLeft = isset( $instance ['sliderArrowLeft'] ) ? $instance ['sliderArrowLeft'] : '';
		$sliderArrowRight = isset( $instance ['sliderArrowRight'] ) ? $instance ['sliderArrowRight'] : '';
		$sliderArrowSize = isset( $instance ['sliderArrowSize'] ) ? $instance ['sliderArrowSize'] : '';
		$sliderArrowWidthLeft = isset( $instance ['sliderArrowWidthLeft'] ) ? $instance ['sliderArrowWidthLeft'] : '';
		$sliderArrowWidthRight = isset( $instance ['sliderArrowWidthRight'] ) ? $instance ['sliderArrowWidthRight'] : '';
		$speed = isset( $instance ['speed'] ) ? $instance ['speed'] : '';
		$speedNext = isset( $instance ['speedNext'] ) ? $instance ['speedNext'] : '';
		$thumbnail = isset( $instance ['thumbnail'] ) ? $instance ['thumbnail'] : 'off';
		$thumbnailWidth = isset( $instance ['thumbnailWidth'] ) ? $instance ['thumbnailWidth'] : '';
		$thumbnailHeight = isset( $instance ['thumbnailHeight'] ) ? $instance ['thumbnailHeight'] : '';
		$thumbnailSlider = isset( $instance ['thumbnailSlider'] ) ? $instance ['thumbnailSlider'] : 'off';
		$visible = isset( $instance ['visible'] ) ? $instance ['visible'] : '';
		$showRandom = isset( $instance ['showRandom'] ) ? $instance ['showRandom'] : '';
		
		//
		if ( $autoplay == 'on' ) $code .= ',autoplay: true';
		else $code .= ',autoplay: false';
		
		if ( $swipemobile == 'on' ) $code .= ',swipemobile: true';
		else $code .= ',swipemobile: false';
		
		if ( $buttonListNext == 'on' ) $code .= ',buttonListNext: true';
		else $code .= ',buttonListNext: false';
		
		if ( $sliderArrow == 'on' ) $code .= ',sliderArrow: true';
		else $code .= ',sliderArrow: false';
		
		if ( $thumbnail == 'on' ) $code .= ',thumbnail: true';
		else $code .= ',thumbnail: false';
		
		if ( $thumbnailSlider == 'on' ) $code .= ',thumbnailSlider: true';
		else $code .= ',thumbnailSlider: false';
		
		if ( $showRandom == 'on' ) $code .= ',showRandom: true';
		else $code .= ',showRandom: false';
		
		//
		if ( $size != '' ) $code .= ',size:"' . $size . '"';
		
		if ( $for_class != '' ) $code .= ',for_class:"' . $for_class . '"';
		
		if ( $sliderArrowLeft != '' ) $code .= ',sliderArrowLeft:"' . $sliderArrowLeft . '"';
		
		if ( $sliderArrowRight != '' ) $code .= ',sliderArrowRight:"' . $sliderArrowRight . '"';
		
		if ( $sliderArrowSize != '' ) $code .= ',sliderArrowSize:"' . $sliderArrowSize . '"';
		
		if ( $sliderArrowWidthLeft != '' ) $code .= ',sliderArrowWidthLeft:"' . $sliderArrowWidthLeft . '"';
		
		if ( $sliderArrowWidthRight != '' ) $code .= ',sliderArrowWidthRight:"' . $sliderArrowWidthRight . '"';
		
		if ( $speed != '' ) $code .= ',speed:"' . $speed . '"';
		
		if ( $speedNext != '' ) $code .= ',speedNext:"' . $speedNext . '"';
		
		if ( $thumbnailWidth != '' ) $code .= ',thumbnailWidth:"' . $thumbnailWidth . '"';
		
		if ( $thumbnailHeight != '' ) $code .= ',thumbnailHeight:"' . $thumbnailHeight . '"';
		
		if ( $visible != '' ) $code .= ',visible:"' . $visible . '"';
		
		
		
		//
//		_eb_echo_widget_name( $this->name, $before_widget );
		echo '<!-- ' . $this->name . ' (' . $id_class . ') -->';
		
		//
		if ( $code != '' ) {
			$code = substr( $code, 1 );
		}
		
		// nếu đầu vào không chỉ rõ là ID hay class -> mặc định là class
		$first_chart = substr( $id_class, 0, 1 );
		if ( $first_chart == '.' || $first_chart == '#' ) { }
		else {
			$id_class = '.' . $id_class;
		}
		
		//
		echo '<script>
jQuery(window).on("load", function () {
	jEBE_slider( "' . $id_class . '", {' . $code . '} );
});
		</script>';
	}
}




