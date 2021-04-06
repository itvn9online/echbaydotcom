<?php


/*
* Widget danh mục sản phẩm hiện tại đang xem
*/
class ___echbay_widget_open_popup extends WP_Widget {
	function __construct() {
		parent::__construct ( 'echbay_popup', 'EchBay popup', array (
			'description' => 'Tạo hiệu ứng BẬT/ TẮT popup bằng phím tắt, hẹn thời gian bật trở lại.' 
		) );
	}
	
	function form($instance) {
		global $arr_eb_category_status;
		
		//
		$default = array (
			'title' => 'EchBay popup',
			'id_event' => '',
			'close_icon' => '',
			'time_start' => 5,
			'time_end' => 30,
			'cookie_name' => '',
			'cookie_time' => 0,
			'cookie_time1' => 6,
			'cookie_time2' => 0
		);
		$instance = wp_parse_args ( ( array ) $instance, $default );
		foreach ( $instance as $k => $v ) {
			$$k = esc_attr ( $v );
		}
		
		_eb_widget_echo_widget_input_title( $this->get_field_name ( 'title' ), $title );
		
		
		//
		echo '<p>ID/ Class: <input type="text" class="widefat" name="' . $this->get_field_name ( 'id_event' ) . '" value="' . $id_event . '" /> ID hoặc Class CSS dùng để điều khiển popup. Vui lòng nhập đầy đủ dấu # hoặc dấu . nếu có.</p>';
        
        
        echo '<hr />';
		
		
		//
		echo '<p>Thời gian bắt đầu (Tính theo giây): <input type="number" class="widefat" name="' . $this->get_field_name ( 'time_start' ) . '" value="' . $time_start . '" /> Nhập số giây bạn muốn popup được bật lên sau khi khách vào website. Nhập 0 nếu bạn muốn bật càng sớm càng tốt.</p>';
		
		
		//
		echo '<p>Thời gian kết thúc (Tính theo giây): <input type="number" class="widefat" name="' . $this->get_field_name ( 'time_end' ) . '" value="' . $time_end . '" /> Là thời gian popup sẽ tự động tắt sau khi được bật. Nhập 0 nếu bạn không muốn popup tự tắt (người dùng sẽ bấm phím <em>ESC</em> hoặc nút close để tắt - cần bổ sung nút close nếu chưa có trong form).</p>';
		
		
		//
		echo '<p>Thời gian bật lại (Tính theo phút): <input type="number" class="widefat" name="' . $this->get_field_name ( 'cookie_time' ) . '" value="' . $cookie_time . '" /> Nhập số phút mà bạn muốn popup sẽ được tự động bật lại. Ví dụ: 120, nghĩa là 2 giờ sau sẽ tự động mở lại popup (tương ứng với 2 giờ thì có thể sử dụng luôn dữ liệu giờ ở dưới).</p>';
		
		
		//
		echo '<p>Thời gian bật lại (Tính theo giờ): <input type="number" class="widefat" name="' . $this->get_field_name ( 'cookie_time1' ) . '" value="' . $cookie_time1 . '" /> Nhập số giờ mà bạn muốn popup sẽ được tự động bật lại. Ví dụ: 48, nghĩa là 48 giờ sau sẽ tự động mở lại popup (tương ứng với 2 ngày thì có thể sử dụng luôn dữ liệu ngày ở dưới).</p>';
		
		
		//
		echo '<p>Thời gian bật lại (Tính theo ngày): <input type="number" class="widefat" name="' . $this->get_field_name ( 'cookie_time2' ) . '" value="' . $cookie_time2 . '" /> Nhập số ngày mà bạn muốn popup sẽ được tự động bật lại. Ví dụ: 7, nghĩa là 7 ngày sau sẽ tự động mở lại popup.</p>';
        
        
        echo '<hr />';
		
		
		//
		echo '<p>Tên cookie: <input type="text" class="widefat" name="' . $this->get_field_name ( 'cookie_name' ) . '" value="' . $cookie_name . '" /> Đây là tham số dùng để xác định việc popup đã được rồi hay chưa, và bao lâu sau sẽ tự kích hoạt lại, để trống thì tham số sẽ được xác định dựa theo ID/ Class.</p>';
		
		
		//
		echo '<p>Close icon: <input type="text" class="widefat" name="' . $this->get_field_name ( 'close_icon' ) . '" value="' . $close_icon . '" /> nếu có trường này, một nút bấm close popup sẽ được thêm vào. Ví dụ: <strong>fa-close</strong> (nút close nên tự thiết kế theo form để chuẩn giao diện)</p>';
		
		
		//
		WGR_show_widget_name_by_title();
		
	}
	
	function update($new_instance, $old_instance) {
		$instance = _eb_widget_parse_args ( $new_instance, $old_instance );
		return $instance;
	}
	
	function widget($args, $instance) {
		global $cid;
//		global $eb_wp_taxonomy;
		
//		print_r( $instance );
		
		extract ( $args );
		
		$title = apply_filters ( 'widget_title', $instance ['title'] );
		
		$id_event = isset( $instance ['id_event'] ) ? $instance ['id_event'] : '';
		$close_icon = isset( $instance ['close_icon'] ) ? $instance ['close_icon'] : '';
		$time_start = isset( $instance ['time_start'] ) ? $instance ['time_start'] : '';
		if ( $time_start == '' ) {
			$time_start = 0;
		}
		$time_end = isset( $instance ['time_end'] ) ? $instance ['time_end'] : '';
		if ( $time_end == '' ) {
			$time_end = 0;
		}
		$cookie_name = isset( $instance ['cookie_name'] ) ? $instance ['cookie_name'] : '';
		$cookie_time = isset( $instance ['cookie_time'] ) ? $instance ['cookie_time'] : '';
		if ( $cookie_time == '' ) {
			$cookie_time = 0;
		}
		$cookie_time1 = isset( $instance ['cookie_time1'] ) ? $instance ['cookie_time1'] : '';
		if ( $cookie_time1 == '' ) {
			$cookie_time1 = 0;
		}
		$cookie_time2 = isset( $instance ['cookie_time2'] ) ? $instance ['cookie_time2'] : '';
		if ( $cookie_time2 == '' ) {
			$cookie_time2 = 0;
		}
		
		
		
		//
		_eb_echo_widget_name( $this->name, $before_widget );
		
		//
		echo '<script>
jQuery(window).on("load", function () {
//jQuery(document).ready(function() {
	WGR_active_popup({
		id_event: "' . $id_event . '",
		close_icon: "' . $close_icon . '",
		time_start: ' . $time_start . ',
		time_end: ' . $time_end . ',
		cookie_name: "' . $cookie_name . '",
		cookie_time: ' . $cookie_time . ',
		cookie_time1: ' . $cookie_time1 . ',
		cookie_time2: ' . $cookie_time2 . '
	});
});
		</script>';
		
	}
}




