<?php



/*
* Widget blog ngẫu nhiên
*/
class ___echbay_widget_banner_big extends WP_Widget {
	function __construct() {
		parent::__construct ( 'echbay_banner_big', 'zEchBay Big Banner', array (
				'description' => 'Nhúng banner loại lớn vào website' 
		) );
	}
	
	function form($instance) {
		$default = array (
			'title' => 'Big Banner',
			'width' => '',
			'custom_style' => '',
			'full_mobile' => '',
			'hide_mobile' => '',
			'cat_ids' => 0,
			'cat_type' => 'post_options',
			'custom_size' => '',
			'num_for_get' => '',
			'for_home' => ''
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
		
		
		//
		_eb_widget_echo_widget_input_checkbox( $arr_field_name['for_home'], $for_home, 'For home', 'Khi sử dụng template page, chức năng load big banner không hoạt động, khi đó cần phải kích hoạt chức năng này để nó có thể lấy big banner theo tiêu chuẩn mặc định.' );
		
		
		
		//
		echo '<p class="bold">Thiết lập big banner riêng theo post_options:</p>';
		
		//
		__eb_widget_load_cat_select ( array(
//			'cat_ids_name' => $this->get_field_name ( 'cat_ids' ),
			'cat_ids_name' => $arr_field_name['cat_ids'],
			'cat_ids' => $cat_ids,
//			'cat_type_name' => $this->get_field_name ( 'cat_type' ),
			'cat_type_name' => $arr_field_name['cat_type'],
			'cat_type' => 'post_options'
		), 'post_options', false );
		
		echo '<p>* Khi muốn lấy banner trong một <strong>post_options</strong> cụ thể nào đó, thì có thể chọn tại đây. Câu lệnh chỉ lấy các banner có trạng thái là: <strong>0</strong></p>';
		
		
		//
		echo '<p><strong>Tùy chỉnh size ảnh</strong>: <input type="text" class="widefat fixed-size-for-config" name="' . $arr_field_name['custom_size'] . '" value="' . $custom_size . '" /> * Điều chỉnh size ảnh theo kích thước riêng (nếu có), có thể đặt <strong>auto</strong> để lấy kích thước tự động của ảnh!</p>';
		
		
		//
		echo '<p><strong>Số lượng hiển thị</strong>: <input type="text" class="widefat" name="' . $arr_field_name['num_for_get'] . '" value="' . $num_for_get . '" /> * Mặc định sẽ lấy theo số lượng được nhập <a href="' . admin_link . 'admin.php?page=eb-coder&edit_key=lang_eb_bigbanner_num" target="_blank">tại đây</a>, nếu bạn muốn tùy chỉnh nó thì nhập một con số khác vào.</p>';
		
	}
	
	function update($new_instance, $old_instance) {
		$instance = _eb_widget_parse_args ( $new_instance, $old_instance );
		return $instance;
	}
	
	function widget($args, $instance) {
		global $str_big_banner;
		
		extract ( $args );
		
//		$title = apply_filters ( 'widget_title', $instance ['title'] );
		$title = isset( $instance ['title'] ) ? $instance ['title'] : '';
		$width = isset( $instance ['width'] ) ? $instance ['width'] : '';
		if ( $width != '' ) $width .= ' lf';
		
		$custom_style = isset( $instance ['custom_style'] ) ? $instance ['custom_style'] : '';
		
		$hide_mobile = isset( $instance ['hide_mobile'] ) ? $instance ['hide_mobile'] : 'off';
//		$hide_mobile = $hide_mobile == 'on' ? ' hide-if-mobile' : '';
		if ( $hide_mobile == 'on' ) $width .= ' hide-if-mobile';
		
		$full_mobile = isset( $instance ['full_mobile'] ) ? $instance ['full_mobile'] : 'off';
		if ( $full_mobile == 'on' ) $width .= ' fullsize-if-mobile';
		
		$cat_ids = isset( $instance ['cat_ids'] ) ? $instance ['cat_ids'] : 0;
		$custom_size = isset( $instance ['custom_size'] ) ? $instance ['custom_size'] : '';
		$num_for_get = isset( $instance ['num_for_get'] ) ? $instance ['num_for_get'] : '';
		
		$for_home = isset( $instance ['for_home'] ) ? $instance ['for_home'] : 'off';
		
		
		//
//		_eb_echo_widget_name( $this->name, $before_widget );
		echo '<!-- ' . $this->name . ' -->';
		
		
		//
		if ( $num_for_get == '' ) {
			$num_for_get = EBE_get_lang('bigbanner_num');
		}
		$num_for_get *= 1;
		
		
		//
		$echo_banner = '';
		
		
		// nếu có lấy theo ID của nhóm -> lấy luôn theo ID nhóm đó
//		echo $cat_ids;
		if ( $cat_ids > 0 ) {
			// tắt chế độ lấy theo trang chủ
//			$for_home = 'off';
			
			//
			$echo_banner = EBE_get_big_banner( $num_for_get, array(
				'tax_query' => array(
					array (
						'taxonomy' => 'post_options',
						'field' => 'term_id',
						'terms' => $cat_ids,
						'operator' => 'IN'
					)
				)
			), array(
				// với những banner này -> chỉ lấy trạng thái là 0
				'by_status' => 0,
				// nạp riêng class để gọi tới chức năng tạo slider -> tránh xung đột
				'class_big_banner' => 'oi_big_banner' . $cat_ids . ' each_big_banner',
				'set_size' => $custom_size
			) );
		}
		else {
			if ( $str_big_banner == '' ) {
				if ( $for_home == 'on' ) {
					global $__cf_row;
					
					if ( $__cf_row['cf_global_big_banner'] != 1 ) {
						$str_big_banner = EBE_get_big_banner( $num_for_get, array(
							'category__not_in' => ''
						), array(
							'set_size' => $custom_size
						) );
					}
					
					//
					if ( $str_big_banner == '' ) {
						return false;
					}
				}
				else {
					return false;
				}
			}
			$echo_banner = $str_big_banner;
		}
		
		//
		echo '<div class="' . str_replace( '  ', ' ', trim( 'top-footer-css ' . $width ) ) . '">';
		
		//
//		_eb_echo_widget_title( $title, 'echbay-widget-blogs-title', $before_title );
		
		
		//
//		echo '<div class="' . str_replace( '  ', ' ', trim( 'oi_big_banner ' . $custom_style ) ) . '">' . $str_big_banner . '</div>';
		echo '<div class="' . $custom_style . '">' . $echo_banner . '</div>';
		
		
		//
		echo '</div>';
		
		//
//		echo $after_widget;
	}
}




