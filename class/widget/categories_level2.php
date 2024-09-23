<?php


/*
 * Widget hiển thị danh mục sản phẩm cấp 1 với các mục cấp 2 có đếm số lượng sản phẩm trong đó
 */
class ___echbay_widget_category_level2_list extends WP_Widget
{
	function __construct()
	{
		parent::__construct('echbay_category_level2', 'EchBay Category level 2', array(
			'description' => 'Hiển thị danh mục sản phẩm cấp 1 với các mục cấp 2 có đếm số lượng sản phẩm'
		));
	}

	function form($instance)
	{
		$default = WGR_default_for_home_list_and_blog();
		$default['auto_cat_id'] = '';
		//print_r( $default );

		// tiêu đề add thêm vào với tên của danh mục chính
		$default['add_title'] = '';
		$default['custom_size'] = '';

		$instance = wp_parse_args((array)$instance, $default);
		//print_r( $instance );

		$this_value = array();
		foreach ($default as $k => $v) {
			$this_value[$k] = $this->get_field_name($k);
		}

		//
		echo '<p>Tiêu đề được thêm cùng với tên danh mục cấp 1:</p>';
		echo '<input type="text" class="widefat" name="' . $this_value['add_title'] . '" value="' . $instance['add_title'] . '" />';

		_eb_widget_echo_widget_input_checkbox($this_value['auto_cat_id'], $instance['auto_cat_id'], 'Tự động lấy nhóm con của nhóm hiện tại (thường dùng trong danh mục sản phẩm).');

		WGR_phom_for_home_list_and_blog($instance, $default, $this_value);

		//
		echo '<p><strong>Tùy chỉnh size ảnh:</strong>';
		echo '<input type="text" class="widefat" name="' . $this_value['custom_size'] . '" value="' . $instance['custom_size'] . '" />';
		echo '* Điều chỉnh size ảnh theo kích thước riêng (nếu có), có thể đặt auto để lấy kích thước tự động của ảnh!</p>';
	}

	function update($new_instance, $old_instance)
	{
		$instance = _eb_widget_parse_args($new_instance, $old_instance);
		return $instance;
	}

	function widget($args, $instance)
	{

		//
		$post_number = isset($instance['post_number']) ? $instance['post_number'] : 0;
		$num_line = isset($instance['num_line']) ? $instance['num_line'] : '';
		//echo $num_line;

		$add_title = isset($instance['add_title']) ? $instance['add_title'] : '';
		$custom_size = isset($instance['custom_size']) ? $instance['custom_size'] : '';

		$auto_cat_id = isset($instance['auto_cat_id']) ? $instance['auto_cat_id'] : '';
		//echo $auto_cat_id . '<br>' . "\n";

		$custom_style = isset($instance['custom_style']) ? $instance['custom_style'] : '';
		$custom_style .= WGR_add_option_class_for_post_widget($instance);

		$post_cloumn = isset($instance['post_cloumn']) ? $instance['post_cloumn'] : '';
		if ($post_cloumn != '') {
			$custom_style .= ' blogs_node_' . $post_cloumn;
		}

		//
		$widget_select_categories = array();
		// tự động lấy theo danh mục sản phẩm đang xem
		if ($auto_cat_id == 'on') {
			global $cid;
			//echo $cid . '<br>' . "\n";
			if ($cid > 0) {
				$t = get_term($cid);
				$widget_select_categories[] = $t;
			}
		}
		// hoặc lấy theo danh mục đã được chỉ định
		else {
			$cat_ids = isset($instance['cat_ids']) ? $instance['cat_ids'] : '';
			if ($cat_ids != '') {
				$cat_ids = explode(',', $cat_ids);

				foreach ($cat_ids as $v) {
					$t = get_term($v);
					if (!isset($t->errors)) {
						//print_r( $t );
						$widget_select_categories[] = $t;
					}
				}
			}
		}
		//print_r( $widget_select_categories );

		// nếu không có nhóm nào được chọn -> lấy mặc định các nhóm cấp 1
		if (empty($widget_select_categories)) {
			$args = array(
				'parent' => 0,
				'hide_empty' => eb_code_tester == true ? 0 : 1,
			);
			$widget_select_categories = get_categories($args);
			//print_r( $widget_select_categories );
		}

		//
		$arr_for_add_css = array();

		echo '<div class="' . trim('widget-ledmart-echbay-category-list ' . $custom_style) . '">';

		//
		if (empty($widget_select_categories)) {
			echo 'empty category';
		} else {
			$html = file_get_contents(__DIR__ . '/categories_level2.html', 1);
			//$html_sub = file_get_contents( __DIR__ . '/categories_sub_level2.html', 1 );
			$html_sub = file_get_contents(EB_THEME_PLUGIN_INDEX . 'html/blogs_node.html', 1);
			//echo $html_sub . '<br>' . "\n";
			if ($custom_size != '') {
				$html_sub = str_replace('{tmp.cf_blog_size}', $custom_size, $html_sub);
				$html_sub = str_replace('{tmp.cf_product_size}', $custom_size, $html_sub);
			}
			$html_sub = str_replace('dynamic_title_tag', 'div', $html_sub);

			//
			//echo $post_number . '<br>' . "\n";
			foreach ($widget_select_categories as $k => $v) {
				// var_dump($v);
				// print_r($v);
				if ($v == null) {
					continue;
				}
				$v->c_link = _eb_c_link($v->term_id, $v->taxonomy);

				//
				$sub_cat = get_categories([
					'parent' => $v->term_id,
					'hide_empty' => eb_code_tester == true ? 0 : 1,
				]);

				//
				$cat_sub_list = '';
				if (!empty($sub_cat)) {
					//print_r( $sub_cat );

					$max_for = 1;
					foreach ($sub_cat as $k_sub => $v_sub) {
						//echo $max_for . '<br>' . "\n";
						if ($post_number > 0 && $max_for > $post_number) {
							continue;
							break;
						}
						$max_for++;

						//
						//echo get_option( 'z_taxonomy_image' . $v_sub->term_id ) . '<br>' . "\n";
						//print_r( $v_sub );
						$v_sub->c_link = _eb_c_link($v_sub->term_id, $v_sub->taxonomy);
						$v_sub->p_link = $v_sub->c_link;
						$v_sub->trv_tieude = $v_sub->name . ' <span class="cat-sub-count">' . $v_sub->category_count . '<span>sản phẩm</span></span>';
						$v_sub->trv_title = $v_sub->name;
						$v_sub->post_type = $v_sub->taxonomy;
						$v_sub->ID = $v_sub->term_id;
						$v_sub->trv_gioithieu = $v_sub->category_description;
						//$v_sub->trv_img = _eb_get_cat_object( $v_sub->term_id, 'zci_taxonomy_image' );
						// sử dụng kết hợp với plugin Categories Images -> https://vi.wordpress.org/plugins/categories-images/
						$v_sub->trv_img = get_option('z_taxonomy_image' . $v_sub->term_id);
						$v_sub->trv_table_img = $v_sub->trv_img;
						$v_sub->trv_mobile_img = $v_sub->trv_img;
						$v_sub->blog_link_option = '';
						$v_sub->ngaycapnhat = '';
						$v_sub->ant_ten = '';

						//
						$cat_sub_list .= EBE_arr_tmp($v_sub, $html_sub);
					}
				} else {
					//echo 'empty sub category';
					continue;
				}

				//
				echo EBE_arr_tmp([
					//'cf_blog_size' => $custom_size,
					'add_title' => $add_title,
					'num_line' => $num_line,
					'cat_sub_list' => $cat_sub_list
				], EBE_arr_tmp($v, $html));
			}
		}

		//
		echo '</div>';

		//
		$arr_for_add_css[__DIR__ . '/categories_level2.css'] = 1;

		_eb_add_compiler_css($arr_for_add_css);
	}
}
