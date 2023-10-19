<?php

// set giãn cách xóa cache nếu chưa có
if (!isset($_GET['time_auto_cleanup_cache'])) {
	$_GET['time_auto_cleanup_cache'] = 120;
}
//print_r( $_GET ); exit();
//echo EB_THEME_CACHE . '<br>' . PHP_EOL;
//echo rtrim(EB_THEME_CACHE, '/') . '_m/' . '<br>' . PHP_EOL;
//die(__FILE__ . ':' . __LINE__);


$strCacheFilter = 'cleanup_cache';
$check_Cleanup_cache = _eb_get_static_html($strCacheFilter, '', '', $_GET['time_auto_cleanup_cache']);
if ($check_Cleanup_cache == false) {
	$dir_m_cache = rtrim(EB_THEME_CACHE, '/') . '_m/';

	// nếu có lệnh xóa cache -> xóa luôn cả thư mục noclean
	if (isset($_GET['tab'])) {
		_eb_remove_ebcache_content(EB_THEME_CACHE, 0, true);
		if (is_dir($dir_m_cache)) {
			_eb_remove_ebcache_content($dir_m_cache, 0, true);
		}
	}
	// còn xóa định kỳ thì thôi
	else {
		_eb_remove_ebcache_content();
		if (is_dir($dir_m_cache)) {
			_eb_remove_ebcache_content($dir_m_cache);
		}
		_eb_log_user('Cleanup cache (auto after ' . $_GET['time_auto_cleanup_cache'] . 's): ' . $_SERVER['REQUEST_URI']);
	}

	// ép lưu cache
	_eb_get_static_html($strCacheFilter, date('r', date_time), '', 60);


	// in thông báo nếu là xóa cache thủ công
	if (isset($_GET['tab'])) {
		//		echo '<p>Xóa bộ nhớ tạm thành công!</p>';

		_eb_alert('Xóa bộ nhớ tạm thành công!');
	}
} else {
	if (isset($_GET['tab'])) {
		_eb_alert('Giãn cách mỗi lần xóa tối thiểu là ' . $_GET['time_auto_cleanup_cache'] . ' giây!');
	}
}
//exit();
