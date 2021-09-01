<?php

/*
 * File này dùng để thiết lập thông tin bản quyền cho nhà cung cấp code, copy file này cho vào thư mục theme cha: echbaytwo sau đó đổi các thông tin thành thông tin của nhà cung cấp là được.
 */

$arrs_private_info_setting = array(
    'echbaydotcom' => array(
        'parent_theme_default' => 'echbaytwo',
        'url_update_parent_theme' => 'https://github.com/itvn9online/echbaytwo/archive/master.zip',
        'dir_theme_unzip_to' => 'echbaytwo-master',

        //'child_theme_default' => 'echbaytwo-child',

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
        //'dir_theme_unzip_to' => 'hostingviet-main',

        //'child_theme_default' => 'hostingviet-child',

        'theme_site_upper' => 'HostingViet.vn',
        'theme_site_url' => 'https://hostingviet.vn/',
        'theme_author' => 'HostingViet',

        'author_email' => 'hotro@hostingviet.vn',
        'site_upper' => 'HostingViet.vn',
        'site_url' => 'https://hostingviet.vn/',
        'author' => 'HostingViet'
    ),
    'ifoxvn' => array(
        'parent_theme_default' => 'ifoxvn',
        //'dir_theme_unzip_to' => 'ifoxvn-main',

        //'child_theme_default' => 'ifoxvn-child',

        'theme_site_upper' => 'ifoxvn',
        'theme_site_url' => 'https://ifox.vn/',
        'theme_author' => 'ifoxvn',

        'author_email' => 'hotro@ifox.vn',
        'site_upper' => 'ifox.vn',
        'site_url' => 'https://ifox.vn/',
        'author' => 'ifoxvn'
    )
);
$arr_private_info_setting = $arrs_private_info_setting[ 'echbaydotcom' ];


// từ nhà phát hành khác (nếu có)
// -> kiểm tra xem có file ghi tên của nhà phát hành không -> làm cách này để thông tin nhà phát hành không thể tự thay đổi được -> chống kiểu clone vô tội vạ, lỡ ảnh hưởng tới thương hiệu gốc của tác giả
//echo EB_THEME_URL . '<br>' . "\n";
//if ( file_exists( EB_THEME_URL . 'copyright' ) ) {
//  $private_info_setting = file_get_contents( EB_THEME_URL . 'copyright', 1 );
if ( defined( 'EB_THEME_URL' ) ) {
    $private_info_setting = basename( EB_THEME_URL );
    //echo $private_info_setting . '<br>' . "\n";

    // có thì ghi đè dữ liệu lên thôi
    if ( $private_info_setting != 'echbaytwo' && isset( $arrs_private_info_setting[ $private_info_setting ] ) ) {
        // các dữ liệu khác sẽ ghi đè từ theme
        foreach ( $arrs_private_info_setting[ $private_info_setting ] as $k => $v ) {
            $arr_private_info_setting[ $k ] = $v;
        }
        $arr_private_info_setting[ 'author_logo' ] = str_replace( ABSPATH, '', EB_THEME_URL ) . 'logo.png';
        $arr_private_info_setting[ 'url_check_WGR_version' ] = 'https://raw.githubusercontent.com/itvn9online/echbaydotcom/master/VERSION';
        $arr_private_info_setting[ 'url_check_EB_theme_version' ] = 'https://raw.githubusercontent.com/itvn9online/echbaytwo/master/VERSION';
    }
}
//print_r($arr_private_info_setting);