<?php




//
//if ( ! class_exists( 'EchBayCommerce' ) ) {



class EchBayCommerce
{



	// lấy sản phẩm theo mẫu chung
	function select_thread_list_all($post, $html = __eb_thread_template, $pot_tai = 'category')
	{
		return EBE_select_thread_list_all($post, $html, $pot_tai);
	}

	function arr_tmp($row = array(), $str = '')
	{
		return EBE_arr_tmp($row, $str);
	}

	function str_template($f, $arr = array(), $dir = EB_THEME_HTML)
	{
		return EBE_str_template($f, $arr, $dir);
	}

	// thay thế các văn bản trong html tìm được
	function html_template($html, $arr = array())
	{
		return EBE_html_template($html, $arr);
	}

	function get_page_template($page_name = '', $dir = EB_THEME_HTML)
	{
		return EBE_get_page_template($page_name, $dir);
	}



	/*
	* Chức năng tạo head riêng của Echbay
	* Các file tĩnh như css, js sẽ được cho vào vòng lặp để chạy 1 phát cho tiện
	*/
	function add_css($arr = array(), $include_now = 0)
	{
		_eb_add_css_js_file($arr, '.css', $include_now);
	}

	function add_full_css($arr = array(), $type_add = 'import')
	{
		_eb_add_full_css($arr, $type_add);
	}

	function add_js($arr = array(), $include_now = 0)
	{
		_eb_add_css_js_file($arr, '.js', $include_now);
	}

	function add_full_js($arr = array(), $type_add = 'import')
	{
		_eb_add_full_js($arr, $type_add);
	}

	// một số host không dùng được hàm end
	function _end($arr)
	{
		return _eb_end($arr);
	}
	function _last($arr)
	{
		return _eb_last($arr);
	}
	function _begin($arr)
	{
		return _eb_begin($arr);
	}
	function _first($arr)
	{
		return _eb_first($arr);
	}

	function add_css_js_file($arr, $file_type = '.css', $include_now = 0, $include_url = EB_URL_OF_THEME)
	{
		return _eb_add_css_js_file($arr, $file_type, $include_now, $include_url);
	}

	function import_js($js)
	{
		return _eb_import_js($js);
	}

	//
	function replace_css_space($str, $new_array = array())
	{
		return _eb_replace_css_space($str, $new_array);
	}

	// add css thẳng vào HTML
	function add_compiler_css($arr)
	{
		_eb_add_compiler_css($arr);
	}

	// add css dưới dạng <link>
	function add_compiler_link_css($arr)
	{
		_eb_add_compiler_link_css($arr);
	}


	// Thiết lập hàm hiển thị logo
	function echbay_logo()
	{
		_eb_echbay_logo();
	}


	/*
	* Thiết lập hàm hiển thị menu
	* https://developer.wordpress.org/reference/functions/wp_nav_menu/
	* tag_menu_name: nếu muốn lấy cả tên menu thì gán thêm hàm này vào
	* tag_close_menu_name: thẻ đóng html của tên menu
	*/
	function echbay_menu($slug, $menu = array(), $in_cache = 1, $tag_menu_name = '', $tag_close_menu_name = '</div>')
	{
		return _eb_echbay_menu($slug, $menu, $in_cache, $tag_menu_name, $tag_close_menu_name);
	}


	/*
	* https://codex.wordpress.org/Function_Reference/dynamic_sidebar
	*/
	function echbay_sidebar($slug, $css = '', $div = 'div', $in_cache = 1)
	{
		return _eb_echbay_sidebar($slug, $css, $div, $in_cache);
	}


	function q($str)
	{
		return _eb_q($str);
	}

	function full_url()
	{
		return _eb_full_url();
	}


	// Lưu log error vào file
	function log_file($str)
	{
		_eb_log_file($str);
	}


	//
	function sd($arr, $tbl)
	{
		_eb_sd($arr, $tbl);
	}
	function set_data($arr, $tbl)
	{
		_eb_sd($arr, $tbl);
	}




	/*
	* Chức năng lấy dữ liệu trong cache
	*/
	// https://www.smashingmagazine.com/2012/06/diy-caching-methods-wordpress/
	function get_static_html($f, $c = '', $file_type = '', $cache_time = 0)
	{
		return _eb_get_static_html($f, $c, $file_type, $cache_time);
	}


	function check_email_type($e_mail = '')
	{
		return _eb_check_email_type($e_mail);
	}


	function mdnam($str)
	{
		return _eb_mdnam($str);
	}

	// function tạo chuỗi vô định bất kỳ cho riêng mềnh
	function code64($str, $code = 0)
	{
		return _eb_code64($str, $code);
	}



	function eb_postmeta($id, $key, $val)
	{
		_eb_postmeta($id, $key, $val);
	}

	function set_config($key, $val)
	{
		_eb_set_config($key, $val);
	}
	function get_config($real_time = false)
	{
		_eb_get_config($real_time);
	}

	function log_click($m)
	{
		_eb_log_click($m);
	}
	function get_log_click($limit = '')
	{
		return _eb_get_log_click($limit);
	}

	function log_user($m)
	{
		_eb_log_user($m);
	}
	function get_log_user($limit = '')
	{
		return _eb_get_log_user($limit);
	}

	function log_admin($m)
	{
		_eb_log_admin($m);
	}
	function get_log_admin($limit = '')
	{
		return _eb_get_log_admin($limit);
	}

	function log_admin_order($m, $order_id)
	{
		return _eb_log_admin_order($m, $order_id);
	}
	function get_log_admin_order($order_id, $limit = '')
	{
		return _eb_get_log_admin_order($order_id, $limit);
	}

	function log_search($m)
	{
		_eb_log_search($m);
	}
	function get_log_search($limit = '')
	{
		return _eb_get_log_search($limit);
	}







	function non_mark_seo($str)
	{
		return _eb_non_mark_seo($str);
	}
	function non_mark($str)
	{
		return _eb_non_mark($str);
	}




	function build_mail_header($from_email)
	{
		return _eb_build_mail_header($from_email);
	}

	function lnk_block_email($em)
	{
		return _eb_lnk_block_email($em);
	}

	function send_email($to_email, $title, $message, $headers = '', $bcc_email = '', $add_domain = 1)
	{
		return _eb_send_email($to_email, $title, $message, $headers, $bcc_email, $add_domain);
	}

	function send_mail_phpmailer($to, $to_name, $subject, $message, $from_reply = '', $bcc_email = '')
	{
		return _eb_send_mail_phpmailer($to, $to_name, $subject, $message, $from_reply, $bcc_email);
	}



	function ssl_template($c)
	{
		return _eb_ssl_template($c);
	}






	/*
	* https://codex.wordpress.org/Class_Reference/WP_Query
	* https://gist.github.com/thachpham92/d57b18cf02e3550acdb5
	*/
	function load_ads_v2($type = 0, $posts_per_page = 20, $_eb_query = array(), $op = array())
	{
		return _eb_load_ads_v2($type, $posts_per_page, $_eb_query, $op);
	}

	function load_ads($type = 0, $posts_per_page = 20, $data_size = 1, $_eb_query = array(), $offset = 0, $html = '')
	{
		return _eb_load_ads($type, $posts_per_page, $data_size, $_eb_query, $offset, $html);
	}

	function load_post_obj($posts_per_page, $_eb_query)
	{

		//
		$arr['post_type'] = 'post';
		$arr['posts_per_page'] = $posts_per_page;
		$arr['orderby'] = 'menu_order';
		$arr['order'] = 'DESC';
		$arr['post_status'] = 'publish';

		//
		foreach ($_eb_query as $k => $v) {
			$arr[$k] = $v;
		}
		//		print_r( $_eb_query );
		//		print_r( $arr );

		// https://codex.wordpress.org/Class_Reference/WP_Query
		return new WP_Query($arr);
	}

	/*
	* Load danh sách đơn hàng
	*/
	function load_order($posts_per_page = 68, $_eb_query = array())
	{
		global $wpdb;

		//
		$strFilter = "";
		if (isset($_eb_query['p']) && $_eb_query['p'] > 0) {
			$strFilter .= " AND ID = " . $_eb_query['p'];
		}

		//
		$sql = $this->q("SELECT *
		FROM
			" . wp_posts . "
		WHERE
			post_type = 'shop_order'
			AND post_status = 'private'
			" . $strFilter . "
		ORDER BY
			ID DESC
		LIMIT 0, " . $posts_per_page);

		return $sql;


		//
		/*
		$_eb_query['post_type'] = 'shop_order';
		$_eb_query['post_status'] = 'private';
		$_eb_query['orderby'] = 'ID';
		$_eb_query['order'] = 'DESC';
		
		return $this->load_post_obj( $posts_per_page, $_eb_query );
		*/
	}

	/*
	* https://codex.wordpress.org/Class_Reference/WP_Query
	* posts_per_page: số lượng bài viết cần lấy
	* _eb_query: gán giá trị để thực thi wordpres query
	* html: mặc định là sử dụng HTML của theme, file thread_node.html, nếu muốn sử dụng HTML riêng thì truyền giá trị HTML mới vào
	* not_set_not_in: mặc định là lọc các sản phẩm trùng lặp trên mỗi trang, nếu để bằng 1, sẽ bỏ qua chế độ lọc -> chấp nhận lấy trùng
	*/
	function load_post($posts_per_page = 20, $_eb_query = array(), $html = __eb_thread_template, $not_set_not_in = 0)
	{
		global $___eb_post__not_in;
		//		echo 'POST NOT IN: ' . $___eb_post__not_in . '<br>' . "\n";

		// lọc các sản phẩm trùng nhau
		if ($___eb_post__not_in != '' && $not_set_not_in == 0) {
			$_eb_query['post__not_in'] = explode(',', substr($___eb_post__not_in, 1));
		}

		//
		$sql = $this->load_post_obj($posts_per_page, $_eb_query);

		//
		//		if ( $_eb_query['post_type'] == 'blog' ) {
		//			print_r( $sql );
		//			print_r( $_eb_query );
		//			exit();
		//		}

		//
		$str = '';

		//
		while ($sql->have_posts()) {

			$sql->the_post();
			//			the_content();

			//
			if ($not_set_not_in == 0) {
				$___eb_post__not_in .= ',' . $sql->post->ID;
			}

			//
			$str .= $this->select_thread_list_all($sql->post, $html);
		}

		//
		wp_reset_postdata();

		return $str;
	}






	function checkPostServerClient()
	{
		return _eb_checkPostServerClient();
	}



	function checkDevice()
	{
		if (isset($_SERVER['HTTP_USER_AGENT'])) {
			// lấy thông tin hệ điều hành của người dùng
			$_ebArrUAAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
			// mảng các thiết bị mobile chuyên dụng
			$_ebArrMobi = array('midp', 'j2me', 'avantg', 'ipad', 'iphone', 'docomo', 'novarra', 'palmos', 'palmsource', '240x320', 'opwv', 'chtml', 'pda', 'windows ce', 'mmp/', 'mib/', 'symbian', 'wireless', 'nokia', 'hand', 'mobi', 'phone', 'cdm', 'up.b', 'audio', 'sie-', 'sec-', 'samsung', 'htc', 'mot-', 'mitsu', 'sagem', 'sony', 'alcatel', 'lg', 'erics', 'vx', 'nec', 'philips', 'mmm', 'xx', 'panasonic', 'sharp', 'wap', 'sch', 'rover', 'pocket', 'benq', 'java', 'pt', 'pg', 'vox', 'amoi', 'bird', 'compal', 'kg', 'voda', 'sany', 'kdd', 'dbt', 'sendo', 'sgh', 'gradi', 'jb', 'dddi', 'moto', 'opera mobi', 'opera mini', 'android');
			foreach ($_ebArrMobi as $k => $v) {
				// nếu không xác định được chuỗi
				if (strpos($_ebArrUAAgent, $v) === false) {
					// ~> bỏ qua ko làm gì cả
				}
				// nếu tìm được -> trả về thông tin rằng đây là thiết bị mobile
				else {
					return 'mobile';
					break;
				}
			}
		}
		// mặc định cho rằng đây là PC
		return 'pc';
	}



	function un_money_format($str)
	{
		return $this->number_only($str);
	}



	// Chuyển ký tự UTF-8 -> ra bảng mã mới
	function str_block_fix_content($str)
	{
		return _eb_str_block_fix_content($str);
	}




	function postUrlContent($url, $data = '', $head = 0)
	{
		return _eb_postUrlContent($url, $data, $head);
	}
	function getUrlContent($url, $agent = '', $options = array(), $head = 0)
	{
		return _eb_getUrlContent($url, $agent, $options, $head);
	}




	// fix URL theo 1 chuẩn nhất định
	function fix_url($url)
	{
		return _eb_fix_url($url);
	}
	// short link
	function _s_link($id, $seo = 'p')
	{
		return _eb_s_link($id);
	}
	// link cho sản phẩm
	function _p_link($id, $seo = '')
	{
		return _eb_p_link($id);
	}
	// link cho phân nhóm
	function _c_link($id, $taxx = 'category')
	{
		return _eb_c_link($id, $taxx);
	}
	// blog
	function _b_link($id, $seo = '')
	{
		return _eb_p_link($id);
	}
	// blog group
	function _bs_link($id, $seo = '')
	{
		return _eb_c_link($id);
	}



	function create_file($file_, $content_, $add_line = '')
	{

		//
		if (!is_file($file_)) {
			$filew = fopen($file_, 'x+');
			// nhớ set 777 cho file
			chmod($file_, 0777);
			fclose($filew);
		}

		//
		if ($add_line != '') {
			file_put_contents($file_, $content_, FILE_APPEND) or die('ERROR: add to file');
			//			chmod($file_, 0777);
		}
		//
		else {
			//			file_put_contents( $file_, $content_, LOCK_EX ) or die('ERROR: write to file');
			file_put_contents($file_, $content_) or die('ERROR: write to file');
			//			chmod($file_, 0777);
		}



		/*
		* add_line: thêm dòng mới
		*/
		//	$content_ = str_replace('\"', '"', $content_);
		//	$content_ = str_replace("\'", "'", $content_);

		/*
		// nếu tồn tại file rồi -> sửa
		if (is_file($file_)) {
//			if( flock( $file_, LOCK_EX ) ) {
				// open
		//		$fh = fopen($file_, 'r+') or die('ERROR: open 1');
		//		$str_data = fread($fh, filesize($file_));
				if ($add_line != '') {
					$fh = fopen($file_, 'a+') or die('ERROR: add to file');
				} else {
					$fh = fopen($file_, 'w+') or die('ERROR: write to file');
				}
//			}
		}
		// chưa tồn tại file -> tạo
		else {
			// open
			$fh = fopen($file_, 'x+') or die('ERROR: create file');
			chmod($file_, 0777);
		}
		
		// write
		fwrite($fh, $content_) or die('ERROR: write');
		// close
		fclose($fh) or die('ERROR: close');
		*/
	}




	function setCucki($c_name, $c_value = 0, $c_time = 0, $c_path = '/')
	{
	}

	function getCucki($c_name, $default_value = '')
	{
		return _eb_getCucki($c_name, $default_value);
	}



	function alert($m)
	{
		die('<script type="text/javascript">alert("' . $m . '");</script>');
	}



	function number_only($str = '')
	{
		if ($str == '') {
			return 0;
		}
		return preg_replace('/[^0-9]+/', '', $str);
	}
	function text_only($str = '')
	{
		if ($str == '') {
			return '';
		}
		return preg_replace('/[^a-zA-Z0-9\-\.]+/', '', $str);
	}



	function remove_ebcache_content($dir = EB_THEME_CACHE, $remove_dir = 0)
	{
		_eb_remove_ebcache_content($dir, $remove_dir);
	}


	function create_account_auto($arr = array())
	{
		return _eb_create_account_auto($arr);
	}

	/*
	* Tự động tạo trang nếu chưa có
	*/
	function create_page($page_url, $page_name, $page_template = '')
	{
		_eb_create_page($page_url, $page_name, $page_template);
	}


	function create_breadcrumb($url, $tit)
	{
		global $breadcrumb_position;

		//
		//		echo $breadcrumb_position . "\n";

		$breadcrumb_position++;

		//
		return '
		, {
			"@type": "ListItem",
			"position": ' . $breadcrumb_position . ',
			"item": {
				"@id": "' . str_replace('/', '\/', $url) . '",
				"name": "' . str_replace('"', '&quot;', $tit) . '"
			}
		}';
	}
	function create_html_breadcrumb($c)
	{
		return _eb_create_html_breadcrumb($c);
	}

	function echbay_category_menu($id, $tax = 'category')
	{
		$str = '';

		$strCacheFilter = 'eb_cat_menu' . $id;
		//		echo $strCacheFilter;

		$str = $this->get_static_html($strCacheFilter);

		if ($str == false) {

			// parent
			$parent_cat = get_term_by('id', $id, $tax);
			//		print_r( $parent_cat );

			// sub
			$sub_cat = get_categories(array(
				//			'hide_empty' => 0,
				'parent' => $parent_cat->term_id
				//			'child_of' => $parent_cat->term_id
			));
			//		print_r( $sub_cat );

			foreach ($sub_cat as $k => $v) {
				$str .= '<li><a href="' . $this->_c_link($v->term_id) . '">' . $v->name . '</a></li>';
			}

			if ($str != '') {
				$str = '<ul class="sub-menu">' . $str . '</ul>';
			}

			// tổng hợp
			$str = '<ul><li><a href="' . $this->_c_link($parent_cat->term_id) . '">' . $parent_cat->name . '</a>' . $str . '</li></ul>';

			//
			$this->get_static_html($strCacheFilter, $str);
		}

		//
		return $str;
	}

	function get_youtube_id($url)
	{
		if ($url == '') {
			return '';
		}

		parse_str(parse_url($url, PHP_URL_QUERY), $a);

		if (isset($a['v'])) {
			return $a['v'];
		} else {
			$a = explode('/embed/', $url);
			if (isset($a[1])) {
				$a = explode('?', $a[1]);
				$a = explode('&', $a[0]);

				return $a[0];
			}
		}

		return '';
	}

	// tiêu đề tiêu chuẩn của google < 70 ký tự
	function tieu_de_chuan_seo($str)
	{
		global $__cf_row;

		if (strlen($str) < 35 && $__cf_row['cf_abstract'] != '') {
			$str .= ' - ' . $__cf_row['cf_abstract'];

			//
			if (strlen($str) > 70) {
				$str = $this->short_string($str, 70);
			}
		}

		//
		return $str;
	}

	function short_string($str, $len, $more = 1)
	{
		return _eb_short_string($str, $len, $more);
	}

	function del_line($str, $re = "", $pe = "/\r\n|\n\r|\n|\t/i")
	{
		return preg_replace($pe, $re, trim($str));
	}

	function lay_email_tu_cache($id)
	{
		return _eb_lay_email_tu_cache($id);
	}

	function categories_list_list_v3($taxx = 'category')
	{
		$arr = get_categories(array(
			'taxonomy' => $taxx,
			'hide_empty' => 0,
		));
		//		print_r($arr);

		//
		//		echo count( $arr ) . "\n";

		//
		$str = '';

		foreach ($arr as $v) {
			$str .= '<option data-parent="' . $v->category_parent . '" value="' . $v->term_id . '">' . $v->name . '</option>';
		}

		return $str;
	}

	function categories_list_v3($select_name = 't_ant', $taxx = 'category')
	{
		$str = '<option value="0">[ Lựa chọn phân nhóm ]</option>';

		$str .= $this->categories_list_list_v3($taxx);

		$str .= '<option data-show="1" data-href="' . admin_link . 'edit-tags.php?taxonomy=category">[+] Thêm phân nhóm mới</option>';

		return '<select name="' . $select_name . '">' . $str . '</select>';
	}


	function get_post_img($id)
	{
		return _eb_get_post_img($id);
	}

	function get_post_meta($id, $key, $sing = true, $default_value = '')
	{
		return _eb_get_post_meta($id, $key, $sing, $default_value);
	}


	// kiểm tra nếu có file html riêng -> sử dụng html riêng
	function get_html_for_module($check_file)
	{
		// kiểm tra ở thư mục code riêng
		if (is_file(EB_THEME_HTML . $check_file)) {
			$f = EB_THEME_HTML . $check_file;
		}
		// nếu không -> kiểm tra ở thư mục dùng chung
		else if (is_file(EB_THEME_PLUGIN_INDEX . 'html/' . $check_file)) {
			$f = EB_THEME_PLUGIN_INDEX . 'html/' . $check_file;
		}

		return file_get_contents($f, 1);
	}

	function get_private_html($f, $f2 = '')
	{
		return _eb_get_private_html($f, $f2);
	}



	//
	function get_full_category_v2($this_id = 0, $taxx = 'category')
	{
		return _eb_get_full_category_v2($this_id, $taxx);
	}
}



//}


$func = new EchBayCommerce();
