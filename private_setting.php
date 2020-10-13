<?php

/*
* File này dùng để thiết lập thông tin bản quyền cho nhà cung cấp code, copy file này cho vào thư mục theme cha: echbaytwo sau đó đổi các thông tin thành thông tin của nhà cung cấp là được.
*/

$arrs_private_info_setting = array(
	'echbaydotcom' => array(
		'parent_theme_default' => 'echbaytwo',
		'url_update_parent_theme' => 'https://github.com/itvn9online/echbaytwo/archive/master.zip',
		
		'child_theme_default' => 'echbaytwo-child',
		
		'url_check_WGR_version' => 'https://world.webgiare.org/wp-content/echbaydotcom/VERSION',
		'url_check_EB_theme_version' => 'https://world.webgiare.org/wp-content/themes/echbaytwo/VERSION',
		
//		'theme_author_email' => 'lienhe@echbay.com',
		'theme_site_upper' => 'WebGiaRe.org',
		'theme_site_url' => 'https://webgiare.org/',
		'theme_author' => 'WebGiáRẻ.org',
		
		'author_email' => 'lienhe@echbay.com',
		'site_upper' => 'EchBay.com',
		'site_url' => 'https://echbay.com/',
		'author' => 'ẾchBay.com'
	),
	'hostingviet' => array(
		'parent_theme_default' => 'hostingviet',
//		'url_update_parent_theme' => '',
		
		'child_theme_default' => 'hostingviet-child',
		
//		'url_check_WGR_version' => 'https://raw.githubusercontent.com/itvn9online/echbaydotcom/master/VERSION',
//		'url_check_EB_theme_version' => 'https://raw.githubusercontent.com/itvn9online/echbaytwo/master/VERSION',
		
		'theme_site_upper' => 'HostingViet.vn',
		'theme_site_url' => 'https://hostingviet.vn/',
		'theme_author' => 'HostingViet',
		
		'author_email' => 'hotro@hostingviet.vn',
		'site_upper' => 'HostingViet.vn',
		'site_url' => 'https://hostingviet.vn/',
		'author' => 'HostingViet'
	)
);
$arr_private_info_setting = $arrs_private_info_setting['echbaydotcom'];


// từ nhà phát hành khác (nếu có)
// -> kiểm tra xem có file ghi tên của nhà phát hành không -> làm cách này để thông tin nhà phát hành không thể tự thay đổi được -> chống kiểu clone vô tội vạ, lỡ ảnh hưởng tới thương hiệu gốc của tác giả
if ( file_exists( EB_THEME_URL . 'copyright' ) ) {
	$private_info_setting = file_get_contents( EB_THEME_URL . 'copyright', 1 );
	
	// có thì ghi đè dữ liệu lên thôi
	if ( isset( $arrs_private_info_setting[ $private_info_setting ] ) ) {
		// lấy logo
		if ( file_exists( EB_THEME_URL . 'private_setting.php' ) ) {
			include EB_THEME_URL . 'private_setting.php';
			if ( isset( $uri_for_author_logo ) ) {
				$arr_private_info_setting['author_logo'] = $uri_for_author_logo . '/logo.png';
			}
			/*
			if ( defined('WP_SITEURL') ) {
				$uri_for_author_logo = str_replace( ABSPATH, WP_SITEURL . '/', dirname( __FILE__ ) );
			}
			else {
				$uri_for_author_logo = str_replace( ABSPATH, '', dirname( __FILE__ ) );
			}
			*/
		}
		
		// các dữ liệu khác sẽ ghi đè từ theme
		foreach ( $arrs_private_info_setting[ $private_info_setting ] as $k => $v ) {
			$arr_private_info_setting[$k] = $v;
		}
		$arr_private_info_setting['url_check_WGR_version'] = 'https://raw.githubusercontent.com/itvn9online/echbaydotcom/master/VERSION';
		$arr_private_info_setting['url_check_EB_theme_version'] = 'https://raw.githubusercontent.com/itvn9online/echbaytwo/master/VERSION';
	}
}
//print_r($arr_private_info_setting);


