<?php



/*
 * Do quá trình cache file config dễ bị lỗi file -> gây lỗi syntax -> nên phần config này sẽ update khi người dùng cập nhật web
 */
$__eb_cache_only_lang = EB_THEME_CACHE . '___lang.php';
$__eb_txt_only_lang = EB_THEME_CACHE . '___lang.txt';
WGR_check_syntax($__eb_cache_only_lang, $__eb_txt_only_lang);


// chỉ tạo khi không có file cache config, hoặc người dùng đang đăng nhập thì lấy config theo thời gian thực
if (mtv_id > 0 || !file_exists($__eb_txt_only_lang)) {

	// tạo file này càng sớm càng tốt -> để hạn chế nhiều người cùng tạo file 1 lúc
	if (!file_exists($__eb_txt_only_lang)) {
		_eb_create_file($__eb_txt_only_lang, __FILE__);
	}


	/*
	 * Danh sách bản dịch
	 */
	$___eb_lang = $___eb_default_lang;
	EBE_get_lang_list();
	//	print_r( $___eb_lang );

	// -> tạo chuỗi để lưu cache
	$__eb_cache_content = '';
	foreach ($___eb_lang as $k => $v) {
		$__eb_cache_content .= '$___eb_lang[\'' . $k . '\']="' . str_replace('"', '\"', str_replace('$', '\$', $v)) . '";' . "\n";
	}



	//
	if (!file_exists($__eb_cache_only_lang)) {
		//		echo '<!-- ' . $__eb_cache_only_lang . ' (!!!!!) -->' . "\n";
		_eb_create_file($__eb_cache_only_lang, '<?php ' . str_replace('\\\"', '\"', $__eb_cache_content));

		//
		_eb_log_user('Update cache_lang: ' . $_SERVER['REQUEST_URI']);

		// kiểm tra lại sau khi tạo file
		$error_admin_log_cache = WGR_check_syntax($__eb_cache_only_lang, $__eb_txt_only_lang, false, true);
		if ($error_admin_log_cache != '') {
			_eb_log_admin($error_admin_log_cache);
		}
	}

} else {
	include_once $__eb_cache_only_lang;
}