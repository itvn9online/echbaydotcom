<?php


/*
* Widget danh mục sản phẩm hiện tại đang xem
*/
class ___echbay_categorys_search_advanced extends WP_Widget
{
	function __construct()
	{
		parent::__construct('echbay_categorys_search_advanced', 'CategoryS search advanced', array(
			'description' => 'Chức năng tìm kiếm nâng cao theo danh mục sản phẩm, thuộc tính sản phẩm hoặc blog tin tức (thiết lập nhiều nhóm 1 lúc) * chỉ hoạt động trong sidebar: search product options'
		));
	}

	function form($instance)
	{
		global $arr_eb_category_status;

		//
		$default = array(
			'title' => 'Tìm kiếm nâng cao',
			'show_count' => '',
			'cat_ids' => '',
			//			'cat_status' => 0,
			//			'cat_primary' => 0,
			'cat_type' => 'category',
			'list_tyle' => '',
			//			'get_child' => '',
			//			'get_parent' => '',
			'show_for_search_advanced' => '',
			'show_image' => '',
			'dynamic_tag' => 'div',
			'custom_style' => ''
		);
		$instance = wp_parse_args((array) $instance, $default);
		foreach ($instance as $k => $v) {
			$$k = esc_attr($v);
		}
		/*
		$title = esc_attr ( $instance ['title'] );
		$cat_type = esc_attr ( $instance ['cat_type'] );
		$show_count = esc_attr ( $instance ['show_count'] );
		*/

		//		echo '<p>Title: <input type="text" class="widefat" name="' . $this->get_field_name ( 'title' ) . '" value="' . $title . '" /></p>';
		_eb_widget_echo_widget_input_title($this->get_field_name('title'), $title);



		$arr_category_post_options = array(
			'category' => 'Danh mục sản phẩm',
			'post_options' => 'Thuộc tính sản phẩm',
			EB_BLOG_POST_LINK => 'Danh mục tin tức',
			'post_tag' => 'Thẻ (sản phẩm)'
		);



		//
		/*
		$animate_id = __eb_widget_load_cat_select ( array(
			'cat_ids_name' => $this->get_field_name ( 'cat_ids' ),
			'cat_ids' => $cat_ids,
			'cat_type_name' => $this->get_field_name ( 'cat_type' ),
			'cat_type' => $cat_type,
			'cat_input_type' => 'select'
		) );
		*/

		//
		$id_for = '_label_for_widget_home_list_' . mt_rand(0, 1000000);

		echo '<div id="' . $id_for . '">';

		//
		echo '<p><strong>Phân loại danh mục</strong>: ';

		__eb_widget_load_select(
			$arr_category_post_options,
			$this->get_field_name('cat_type'),
			$cat_type
		);

		echo '</p>';

		echo '<p class="redcolor small">* Chỉ chọn các nhóm có nhóm con bên trong thì chức năng mới hoạt động. Nếu widget mới được thêm vào, vui lòng bấm [Saved] để bắt đầu quá trình cầu hình mặc định.</p>';

		echo '<div class="div-widget-home_list">';


		echo '<p class="d-none"><input type="text" class="widefat" data-name="' . $id_for . '" name="' . $this->get_field_name('cat_ids') . '" value="' . $cat_ids . '" /></p>';


		//
		echo '<ul class="ul-widget-categorys_search_advanced">';

		foreach ($arr_category_post_options as $k0 => $v0) {
			//			echo '<li><strong>' . $v0 . '</strong></li>';

			$categories = get_categories(array(
				'hide_empty' => 0,
				'taxonomy' => $k0,
				'parent' => 0
			));
			//			print_r( $categories );

			//
			foreach ($categories as $v) {
				$arrs_child_cats = get_categories(array(
					'hide_empty' => 0,
					'taxonomy' => $k0,
					//					'orderby' => 'slug',
					//					'order'   => 'ASC',
					'parent' => $v->term_id
				));
				//				print_r($arrs_child_cats);

				if (! empty($arrs_child_cats)) {
					echo '<li data-taxonomy="' . $k0 . '">';

					echo '<label for="' . $id_for . $v->term_id . '" class="category-for-home_list"><input type="checkbox" id="' . $id_for . $v->term_id . '" data-id="' . $v->term_id . '" data-class="' . $id_for . '" class="click-get-category-id-home_list" /> <strong>' . $v->name . ' (' . count($arrs_child_cats) . ')</strong></label>';

					echo '</li>';
				}
			}
		}

		echo '</ul>';

		echo '</div>';

		echo '</div>';

		echo '<script>
WGR_category_for_home_list("' . $id_for . '", 1);
WGR_category_for_categorys_search_advanced("' . $id_for . '", "' . $this->get_field_name('cat_type') . '");
</script>';


		//
		/*
		echo '<p>Trạng thái danh mục: ';
		
		__eb_widget_load_select(
			$arr_eb_category_status,
			 $this->get_field_name ( 'cat_status' ),
			$cat_status
		);
		
		echo '</p>';
		*/


		//
		//		$input_name = $this->get_field_name ( 'cat_primary' );
		//		echo $instance[ 'cat_primary' ];

		//		_eb_widget_echo_widget_input_checkbox( $input_name, $instance[ 'cat_primary' ], 'Hiện các nhóm được đánh dấu sao' );


		//
		$input_name = $this->get_field_name('show_count');
		//		echo $instance[ 'show_count' ];

		_eb_widget_echo_widget_input_checkbox($input_name, $instance['show_count'], 'Hiện số bài viết');


		//
		$input_name = $this->get_field_name('list_tyle');
		//		echo $instance[ 'list_tyle' ];

		_eb_widget_echo_widget_input_checkbox($input_name, $instance['list_tyle'], 'Hiển thị dưới dạng Select Box');


		//
		//		$input_name = $this->get_field_name ( 'get_child' );
		//		echo $instance[ 'get_child' ];

		//		_eb_widget_echo_widget_input_checkbox( $input_name, $instance[ 'get_child' ], 'Lấy danh sách nhóm con (thường dùng cho phần danh sách sản phẩm, danh sách bài viết)' );



		//
		//		$input_name = $this->get_field_name ( 'get_parent' );
		//		echo $instance[ 'get_child' ];

		//		_eb_widget_echo_widget_input_checkbox( $input_name, $instance[ 'get_parent' ], 'Tự tìm các nhóm cùng cha (thường dùng cho phần chi tiết sản phẩm, chi tiết bài viết)' );


		$input_name = $this->get_field_name('show_for_search_advanced');
		//		echo $instance[ 'show_for_search_advanced' ];

		_eb_widget_echo_widget_input_checkbox($input_name, $instance['show_for_search_advanced'], 'Chỉ hiện thị nhóm có sản phẩm (tìm kiếm nâng cao) <span class="redcolor small d-block">* Tính năng này có thể làm chậm website của bạn!</span>');


		$input_name = $this->get_field_name('show_image');
		//		echo $instance[ 'show_image' ];

		_eb_widget_echo_widget_input_checkbox($input_name, $instance['show_image'], 'Hiển thị ảnh đại diện của nhóm');


		//
		echo '<p>HTML tag cho tiêu đề: ';

		__eb_widget_load_select(
			array(
				'' => '[ Trống ]',
				'div' => 'DIV',
				'p' => 'P',
				'li' => 'LI',
				'h2' => 'H2',
				'h3' => 'H3',
				'h4' => 'H4',
				'h5' => 'H5',
				'h6' => 'H6'
			),
			$this->get_field_name('dynamic_tag'),
			$dynamic_tag
		);

		echo '</p>';


		//
		echo '<p>Custom style: <input type="text" class="widefat" name="' . $this->get_field_name('custom_style') . '" value="' . $custom_style . '" /></p>';


		//
		WGR_show_widget_name_by_title();
	}

	function update($new_instance, $old_instance)
	{
		$instance = _eb_widget_parse_args($new_instance, $old_instance);
		return $instance;
	}

	function widget($args, $instance)
	{
		global $cid;
		//		global $eb_wp_taxonomy;

		//		print_r( $instance );

		extract($args);

		$title = apply_filters('widget_title', $instance['title']);

		$show_count = isset($instance['show_count']) ? $instance['show_count'] : 'off';
		//		echo $show_count;
		//		$show_count = ( $show_count == 'on' ) ? true : false;

		$cat_ids = isset($instance['cat_ids']) ? $instance['cat_ids'] : '';
		$cat_type = isset($instance['cat_type']) ? $instance['cat_type'] : 'category';
		//		$cat_status = isset( $instance ['cat_status'] ) ? $instance ['cat_status'] : 0;

		$list_tyle = isset($instance['list_tyle']) ? $instance['list_tyle'] : 'off';
		$list_tyle = ($list_tyle == 'on') ? 'widget-category-selectbox' : '';
		$list_tyle .= ' widget-category-padding';

		//		$cat_primary = isset( $instance ['cat_primary'] ) ? $instance ['cat_primary'] : 'off';
		//		$cat_primary = ( $cat_primary == 'on' ) ? true : false;

		//		$get_child = isset( $instance ['get_child'] ) ? $instance ['get_child'] : 'off';
		//		$get_child = ( $get_child == 'on' ) ? true : false;

		//		$get_parent = isset( $instance ['get_parent'] ) ? $instance ['get_parent'] : 'off';
		//		$get_parent = ( $get_parent == 'on' ) ? true : false;

		$show_for_search_advanced = isset($instance['show_for_search_advanced']) ? $instance['show_for_search_advanced'] : 'off';
		//		$show_for_search_advanced = ( $show_for_search_advanced == 'on' ) ? true : false;

		$show_image = isset($instance['show_image']) ? $instance['show_image'] : 'off';

		$dynamic_tag = isset($instance['dynamic_tag']) ? $instance['dynamic_tag'] : '';
		$dynamic_tag_begin = '';
		$dynamic_tag_end = '';
		if ($dynamic_tag != '') {
			$dynamic_tag_begin = '<' . $dynamic_tag . '>';
			$dynamic_tag_end = '</' . $dynamic_tag . '>';
		}

		$custom_style = isset($instance['custom_style']) ? $instance['custom_style'] : '';


		//
		$cats_info = array();

		// tự động lấy danh mục cùng nhóm
		// nếu không có nhóm được chỉ định
		// thuộc tính tự động tìm nhóm được thiết lập
		/*
		if ( $cat_ids == 0 && $get_parent != 'off' ) {
//			global $cid;
			global $parent_cid;
//			global $pid;
			
			//
			if ( $parent_cid > 0 ) {
				$cat_ids = $parent_cid;
			}
			else {
				$cat_ids = $cid;
			}
//			$cats_info = EBE_widget_get_parent_cat( $cid, $cat_type );
//			print_r( $cats_info );
			
			//
			/*
			if ( ! empty ( $cats_info ) ) {
				$cat_ids = $cats_info->term_id;
				$cat_type = $cats_info->taxonomy;
			}
			*/
		//			echo $cat_ids;
		//		}


		//
		if ($cat_ids == '') {
			echo '#cat_ids not found!';
			return false;
		}
		$arr_cat_ids = explode(',', $cat_ids);
		//		print_r( $arr_cat_ids );

		//
		_eb_echo_widget_name($this->name, $before_widget);

		// title
		_eb_echo_widget_title(
			$title,
			'echbay-widget-category-title'
			//			$before_title
		);


		// for cat_ids
		foreach ($arr_cat_ids as $cat_ids) {
			//			echo $cat_ids . '<br>';
			//			echo $cat_type . '<br>';


			// tìm nhóm cha -> để các nhóm sau sẽ lấy theo nhóm này
			if ($cat_ids > 0) {
				// lấy lại taxonomy
				/*
				$cat_type = WGR_get_taxonomy_name( $cat_ids, $cat_type );
				if ( $cat_type == '' ) {
					echo 'taxonomy for #' . $cat_ids . ' not found!';
					return false;
				}
				*/

				// nếu có lệnh tìm nhóm cha -> tìm theo nhóm cha
				/*
				if ( $get_parent != 'off' ) {
					$cats_info = EBE_widget_get_parent_cat( $cat_ids, $cat_type );
				}
				// còn không thì lấy theo nhóm đã được chỉ định
				else {
					*/
				$cats_info = get_term($cat_ids, $cat_type);
				//				}

				//
				/*
				if ( ! empty ( $cats_info ) ) {
					$cat_type = $cats_info->taxonomy;
				}
				*/
				if (empty($cats_info)) {
					echo 'taxonomy for #' . $cat_ids . ' not found!';
					return false;
				}
			}
			//		print_r( $cats_info );
			//		echo $cat_type . '<br>' . "\n";
			//		echo eb_code_tester . '<br>' . "\n";
			//		echo $show_for_search_advanced . '<br>' . "\n";


			// lấy danh sách nhóm chính
			$arrs_cats = get_categories(array(
				'taxonomy' => $cat_type,
				//			'hide_empty' => eb_code_tester == true ? 0 : 1,
				'hide_empty' => $show_for_search_advanced == 'off' ? 0 : 1,
				/*
			'orderby' => 'meta_value_num',
			'meta_query' => array(
				'key' => '_eb_category_order',
				'type' => 'NUMERIC'
			),
			*/
				'orderby' => 'slug',
				//			'order'   => 'ASC',
				'parent' => $cat_ids
			));
			$arrs_cats = WGR_order_and_hidden_taxonomy($arrs_cats);

			// nếu có lệnh kiểm tra sản phẩm tồn tại -> kiểm tra theo CID
			//		if ( mtv_id == 1 ) {
			//		print_r($arrs_cats);

			/*
		if ( $show_for_search_advanced != 'off' && $cid > 0 && ! empty( $arrs_cats ) ) {
//			$get_taxonomy_name = get_term_by( 'id', $cid, $eb_wp_taxonomy );
//			$get_taxonomy_name = get_term( $cid, $eb_wp_taxonomy );
//			print_r( $get_taxonomy_name );
			
			//
			foreach ( $arrs_cats as $k => $v ) {
				if ( $v->taxonomy == 'category' ) {
					if ( $v->count == 0 ) {
						$arrs_cats[$k] = NULL;
					}
				}
				else if ( WGR_custom_check_post_in_multi_taxonomy( $cid, $v->term_id ) == 0 ) {
					$arrs_cats[$k] = NULL;
				}
			}
		}
		*/

			//		}



			//
			//		if ( $cats_info != NULL && $title == '' ) {
			/*
		if ( $title == '' && ! empty ( $cats_info ) ) {
			$title = $cats_info->name;
		}
		*/



			//
			echo '<div class="' . trim($list_tyle . ' ' . $custom_style) . '">';

			// title -> vf sử dụng multi category nên in luôn tên nhóm cha làm widget title
			_eb_echo_widget_title(
				$cats_info->name,
				'echbay-widget-category-title'
				//			$before_title
			);

			//
			echo '<ul class="category-search-advanced cf">';
			//		echo '<ul>';



			// nếu hiển thị theo status được chỉ định -> dùng vòng lặp riêng
			$arr_for_get_cat = array();
			/*
		if ( $cat_status > 0 ) {
			foreach ( $arrs_cats as $v ) {
				// lấy các nhóm có trạng thái như chỉ định
				if ( $v != NULL && (int) _eb_get_cat_object( $v->term_id, '_eb_category_status', 0 ) == $cat_status ) {
					$arr_for_get_cat[] = $v;
				}
			}
		}
		else if ( $cat_primary != 'off' ) {
			foreach ( $arrs_cats as $v ) {
				// lấy các nhóm có trạng thái như chỉ định
				if ( $v != NULL && (int) _eb_get_cat_object( $v->term_id, '_eb_category_primary', 0 ) == 1 ) {
					$arr_for_get_cat[] = $v;
				}
			}
		}
		//
		else {
			*/
			foreach ($arrs_cats as $v) {
				if ($v != NULL) {
					$arr_for_get_cat[] = $v;
				}
			}
			//		}

			//
			foreach ($arr_for_get_cat as $v) {
				//			if ( _eb_get_cat_object( $v->term_id, '_eb_category_hidden', 0 ) != 1 ) {
				$hien_thi_sl = '';
				if ($show_count != 'off') {
					$hien_thi_sl = ' (' . $v->count . ')';
				}

				//
				$hien_thi_img = '';
				//				echo $show_image . '<br>';
				//				print_r($v);
				//				echo _eb_get_cat_object( $v->term_id, '_eb_category_avt' ) . '<br>';
				//				echo _eb_get_cat_object( $v->term_id, 'taxonomy_image' ) . '<br>';
				if ($show_image != 'off') {
					// chức năng lấy ảnh đại diện nhóm là sử dụng kết hợp với plugin khác
					// https://vi.wordpress.org/plugins/categories-images/
					$hien_thi_img = _eb_get_cat_object($v->term_id, '_eb_category_avt', _eb_get_option('z_taxonomy_image' . $v->term_id));
					if ($hien_thi_img == '') {
						$hien_thi_img = EB_URL_TUONG_DOI . 'images-global/noavatar.png';
					}
					$hien_thi_img = '<div class="category-img"><img src="' . $hien_thi_img . '" /></div>';
				}

				//
				echo '<li class="cat-item cat-item-' . $v->term_id . '">' . $dynamic_tag_begin . '<a data-taxonomy="' . $cat_type . '" data-id="' . $v->term_id . '" data-parent="' . $cat_ids . '" data-node-id="' . $this->id . '" title="' . $v->name . '" href="' . _eb_c_link($v->term_id) . '"><i class="fas fa-check-square"></i>' . $hien_thi_img . $v->name . $hien_thi_sl . '</a>' . $dynamic_tag_end;

				//
				/*
				if ( $get_child != 'off' ) {
					EBE_widget_categories_get_child( $v->term_id, $cat_type, $show_count, $this->id );
				}
				*/

				echo '</li>';
				//			}
			}

			//
			echo '</ul>';
			echo '</div>';
		} // end for cat_ids

		//
		echo $after_widget;
	}
}
