<?php

/*
* File này dùng để thiết lập thông tin bản quyền cho nhà cung cấp code, copy file này cho vào thư mục theme cha: echbaytwo sau đó đổi các thông tin thành thông tin của nhà cung cấp là được.
*/

$arrs_private_info_setting = array(
	'echbaydotcom' => array(
		'parent_theme_default' => 'echbaytwo',
		'url_update_parent_theme' => 'https://github.com/itvn9online/echbaytwo/archive/master.zip',
		
		'child_theme_default' => 'echbaytwo-child',
		
//		'url_check_WGR_version' => 'https://world.webgiare.org/wp-content/echbaydotcom/VERSION',
		
//		'theme_author_email' => 'lienhe@echbay.com',
		'theme_site_upper' => 'WebGiaRe.org',
		'theme_site_url' => 'https://webgiare.org/',
		'theme_author' => 'WebGiáRẻ.org',
		
		'author_email' => 'lienhe@echbay.com',
		'site_upper' => 'EchBay.com',
		'site_url' => 'https://echbay.com/',
		'author' => 'ẾchBay.com'
	)
);
$arr_private_info_setting = $arrs_private_info_setting['echbaydotcom'];


// từ nhà phát hành khác (nếu có)
// -> kiểm tra xem có file ghi tên của nhà phát hành không -> làm cách này để thông tin nhà phát hành không thể tự thay đổi được -> chống kiểu clone vô tội vạ, lỡ ảnh hưởng tới thương hiệu gốc của tác giả
if ( file_exists( EB_THEME_URL . 'copyright' ) ) {
	$private_info_setting = file_get_contents( EB_THEME_URL . 'copyright', 1 );
	
	// có thì ghi đè dữ liệu lên thôi
	if ( isset( $arrs_private_info_setting[ $private_info_setting ] ) ) {
		foreach ( $arrs_private_info_setting[ $private_info_setting ] as $k => $v ) {
			$arr_private_info_setting[$k] = $v;
		}
	}
}
/*
if ( file_exists( EB_THEME_URL . 'private_setting.php' ) ) {
	include EB_THEME_URL . 'private_setting.php';
}
*/
//print_r($arr_private_info_setting);



