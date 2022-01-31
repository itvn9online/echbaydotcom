<?php


include EB_THEME_CORE . 'config.php';
//print_r($d);

//include EB_THEME_OUTSOURCE . 'phpmailer/PHPMailerAutoload.php';

include EB_THEME_CORE . 'curl.php';
include EB_THEME_CORE . 'email_via_echbay.php';


/*
 * class riêng của echbay -> chuyển qua dùng function hết cho tiện, đỡ phải global
 */
include EB_THEME_CORE . 'func.php';

//
include EB_THEME_PLUGIN_INDEX . 'Libraries/OrderStatus.php';


//
//$connect_mysqli = mysqli_connect( $dbhost, $d[1], $d[2], $d[0] ) or die ( 'c1' );
//mysqli_query($connect_mysqli, "SET NAMES 'UTF8'");


//
//$default_all_timezone = 'Asia/Saigon';
$default_all_timezone = 'Asia/Ho_Chi_Minh';

// lấy múi giờ trong config -> nếu không có thì set theo múi mặc định của VN
$tz = get_option( 'timezone_string' );
//echo 'tz: ' . $tz . '<br>' . "\n";
if ( empty( $tz ) ) {
    date_default_timezone_set( $default_all_timezone );
} else {
    date_default_timezone_set( $tz );

    // cập nhật tự động múi giờ mới
    /*
    $gtm = get_option('gmt_offset');
    if ( $gmt == 7 ) {
    	update_option('timezone_string', $default_all_timezone);
    }
    */
}

/*
if ( file_exists ( EB_THEME_CACHE . '___timezone.txt' ) ) {
	date_default_timezone_set ( file_get_contents( EB_THEME_CACHE . '___timezone.txt' ) );
}
// Sử dụng timezon mặc định cho khách VN
else {
	date_default_timezone_set ( $default_all_timezone );
}
*/
/*
echo get_option('gmt_offset');
echo get_option('timezone_string');
$get_timezone_file = EB_THEME_CACHE . '___timezone.txt';
//echo $get_timezone_file;
if ( file_exists ( $get_timezone_file ) ) {
	$default_all_timezone = file_get_contents( $get_timezone_file );
}
//date_default_timezone_set ( $default_all_timezone );
date_default_timezone_set ( get_option('gmt_offset') );
*/

//
/*
$tz = get_option('timezone_string');
if ( $tz != '' ) {
	date_default_timezone_set ( $tz );
	$date_time = time ();
}
else {
	date_default_timezone_set ( 'UTC' );
	$date_time = time() + get_option('gmt_offset') * HOUR_IN_SECONDS;
}
*/
$date_time = time();
//$date_time = current_time( 'timestamp' );
//$date_time = time() + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );

$date_server = date( 'Y-m-d', $date_time );
$time_server = date( 'H:i:s', $date_time );
$year_curent = substr( $date_server, 0, 4 );
$month_curent = substr( $date_server, 5, 2 );
$day_curent = substr( $date_server, 8, 2 );

//
define( 'date_time', $date_time );

/*
echo '<!-- ';
echo 'timezone: ' . date_default_timezone_get() . "\n";
echo 'timezone_string: ' . get_option( 'timezone_string' ) . "\n";

//echo 'date_time: ' + date_time . "\n";
echo 'date_time: ' . date( 'r', date_time ) . "\n";

echo 'HOUR_IN_SECONDS: ' . HOUR_IN_SECONDS . "\n";

//echo current_time( 'timestamp', 1 ) . "\n";
echo 'timestamp: ' . date( 'r', current_time( 'timestamp', 1 ) ) . "\n";

//echo current_time( 'mysql', 1 ) . "\n";
echo 'mysql: ' . date( 'r', strtotime( current_time( 'mysql', 1 ) ) ) . "\n";
//echo file_get_contents( EB_THEME_CACHE . '___timezone.txt' ) . "\n";
echo ' -->';
*/

//
define( 'date_server', $date_server );
//echo date( 'r', date_time ) . '<br>';


//
$eb_background_for_post = array();


//
if ( isset( $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ) ) {
    $client_ip = $_SERVER[ 'HTTP_X_FORWARDED_FOR' ];
} else if ( isset( $_SERVER[ 'HTTP_X_REAL_IP' ] ) ) {
    $client_ip = $_SERVER[ 'HTTP_X_REAL_IP' ];
} else if ( isset( $_SERVER[ 'HTTP_CLIENT_IP' ] ) ) {
    $client_ip = $_SERVER[ 'HTTP_CLIENT_IP' ];
} else {
    $client_ip = $_SERVER[ 'REMOTE_ADDR' ];
}


/*
 * Để đỡ phải tạo thêm bảng -> sử dụng post ID để phân biệt giữa các dữ liệu mới
 * Tạo số thật lớn, những số mà với website bình thường chả bao giwof đạt được post ID như vậy
 */

/*
 * sử dụng bảng wp_postmeta
 */
// cấu hình website
define( 'eb_config_id_postmeta', 100000000 );

// log click
//define( 'eb_log_click_id_postmeta', 100000010 );

// log user và log admin phân biệt bởi tên
//define( 'eb_log_user_id_postmeta', 100000020 );

// log tìm kiếm
//define( 'eb_log_search_id_postmeta', 100000030 );

// chức năng thay đổi ngôn ngữ trên website
//define( 'eb_languages_id_postmeta', 100000099 );

// 404 monitor
define( 'eb_log_404_id_postmeta', 100000404 );

/*
 * sử dụng bảng wp_comments
 */
// đơn hàng
//define( 'eb_order_id_comments', 200000000 );

// cho chức năng liên hệ
define( 'eb_contact_id_comments', 200000090 );


// thuộc tính dùng để lưu và lấy thông tin khác của post
define( 'eb_post_obj_data', '_eb_post_obj_data' );
define( 'eb_cat_obj_data', '_eb_cat_obj_data' );


//
define( 'eb_conf_obj_option', '_eb_cf_obj' );


// thời gian mặc định cho cache
define( 'eb_default_cache_time', 120 );

//
define( 'eb_default_vaficon', EB_URL_TUONG_DOI . 'favicon.png' );
$default_all_site_lang = 'vi';


// cấu hình mặc định của web
include EB_THEME_CORE . 'default_config.php';

// ngôn ngũ/ bản dịch mặc định của web -> tiếng Việt
include EB_THEME_PLUGIN_INDEX . 'lang/vi.php';


/*
 * Kiểm tra người dùng đăng nhập chưa
 */
$mtv_id = 0;
$mtv_email = '';

//
//echo is_user_logged_in();
if ( is_user_logged_in() ) {
    $eb_user_info = wp_get_current_user();
    //	print_r( $eb_user_info );

    //
    $mtv_id = $eb_user_info->ID;
    //echo $mtv_id;

    $mtv_email = $eb_user_info->user_email;
    //echo $mtv_email;
}
define( 'mtv_id', $mtv_id );
define( 'mtv_email', $mtv_email );


// số điện thoại người dùng


// thời gian lưu cache
//$cf_reset_cache = 120;
//echo $localhost . '<br>' . "\n";
//echo WP_DEBUG . '<br>';
if ( $localhost == 1 ) {
    //if ( WP_DEBUG == true || $localhost == 1 ) {
    //if ( eb_code_tester == true || $localhost == 1 ) {
    //	$cf_reset_cache = 5;
    $__cf_row[ 'cf_reset_cache' ] = 10;
    /*
}
else {
	$cf_reset_cache = $__cf_row['cf_reset_cache'];
	*/
}


//
$web_name = '';
$web_link = '';
$dynamic_meta = '';


//
//print_r( $__cf_row );
include EB_THEME_CORE . 'cache.php';
//print_r( $__cf_row );


// chế độ kiểm thử
//define( 'cf_tester_mode', true );
if ( $__cf_row[ 'cf_tester_mode' ] == 1 ) {
    define( 'eb_code_tester', true );
} else {
    define( 'eb_code_tester', false );
}

// chế độ riêng của trang rao vặt
define( 'cf_set_raovat_version', $__cf_row[ 'cf_set_raovat_version' ] );
define( 'cf_remove_raovat_meta', $__cf_row[ 'cf_remove_raovat_meta' ] );


//
//echo $__cf_row['cf_reset_cache'] . '<br>';


// ngôn ngữ trên website
/*
include EB_THEME_PLUGIN_INDEX . 'lang/' . $default_all_site_lang . '.php';
if ( $__cf_row['cf_content_language'] != $default_all_site_lang && $__cf_row['cf_content_language'] != '' ) {
	include EB_THEME_PLUGIN_INDEX . 'lang/' . $__cf_row['cf_content_language'] . '.php';
}

// cập nhật lang vào file js
$strCacheFilter = 'lang';
$check_update_lang = _eb_get_static_html ( $strCacheFilter, '', '', 3600 );
if ($check_update_lang == false) {
//	print_r($___eb_lang);
	
	//
	$str_js_lang = 'var ___eb_lang = [];' . "\n";
	foreach ( $___eb_lang as $k => $v ) {
		$str_js_lang .= '___eb_lang["' . $k . '"] = "' . _eb_str_block_fix_content( $v ) . '";' . "\n";
	}
	
	//
	_eb_create_file ( EB_THEME_CACHE . 'lang.js', $str_js_lang );
	
	//
	_eb_get_static_html ( $strCacheFilter, date( 'r', time() ), '', 60 );
}
$url_for_js_lang = EB_DIR_CONTENT . '/uploads/ebcache/lang.js';
*/


// trạng thái đơn
$arr_hd_trangthai = array( -1 => EBE_get_lang( 'order_status_name-1' ),
    0 => EBE_get_lang( 'order_status_name0' ),
    1 => EBE_get_lang( 'order_status_name1' ),
    2 => EBE_get_lang( 'order_status_name2' ),
    3 => EBE_get_lang( 'order_status_name3' ),
    4 => EBE_get_lang( 'order_status_name4' ),
    5 => EBE_get_lang( 'order_status_name5' ),
    6 => EBE_get_lang( 'order_status_name6' ),
    7 => EBE_get_lang( 'order_status_name7' ),
    8 => EBE_get_lang( 'order_status_name8' ),
    9 => EBE_get_lang( 'order_status_name9' ),
    10 => EBE_get_lang( 'order_status_name10' ),
    11 => EBE_get_lang( 'order_status_name11' ),
    OrderStatus::BLACK_LIST => EBE_get_lang( 'order_status_name12' ),
    13 => EBE_get_lang( 'order_status_name13' ),
    14 => EBE_get_lang( 'order_status_name14' ),
    15 => EBE_get_lang( 'order_status_name15' ),
    16 => EBE_get_lang( 'order_status_name16' ),
    17 => EBE_get_lang( 'order_status_name17' ),
    18 => EBE_get_lang( 'order_status_name18' ),
    19 => EBE_get_lang( 'order_status_name19' )
);


//
$arr_eb_category_status = array(
    0 => '[ Mặc định ]',
    1 => 'Ưu tiên cấp 1',
    2 => 'Ưu tiên cấp 2',
    3 => 'Ưu tiên cấp 3',
    4 => 'Ưu tiên cấp 4',
    5 => 'Ưu tiên cấp 5',
    6 => 'Định dạng cho kích cỡ',
    7 => 'Định dạng cho màu sắc',
    8 => 'Định dạng cho khoảng giá',
    9 => 'Định dạng cho Thương hiệu'
);


/*
 * Global setting
 */
$act = '';

//
define( 'web_name', $web_name );
//echo web_name;


// bật tắt EchBay SEO plugin
define( 'cf_on_off_echbay_seo', $__cf_row[ 'cf_on_off_echbay_seo' ] );


//
$___eb_post__not_in = '';
$___eb_ads__not_in = '';


//
$arr_active_for_404_page = array(
    "eb_export_products" => 1,
    "order_export" => 1,

    "test_email" => 1,
    "billing_print" => 1,

    "cart" => 1,
    "contact" => 1,

    "favorite" => 1,
    "golden_time" => 1,
    "products_hot" => 1,
    "products_new" => 1,
    "products_selling" => 1,
    "products_sales_off" => 1,
    "products_all" => 1,

    "hoan-tat" => 1,
    "ebsearch" => 1,
    "duplicate_post" => 1,

    // sitemap tổng
    "sitemap" => 1,
    // cho category, tags, post options
    "sitemap-tags" => 1,
    // sitemap cho post
    "sitemap-post" => 1,
    "sitemap-post-images" => 1,
    "sitemap-all-images" => 1,
    // product cuar woocomerce
    "sitemap-product" => 1,
    "sitemap-product-images" => 1,
    // cho blogs
    //	"sitemap-blogs" => 1,
    // cho blog
    "sitemap-blog" => 1,
    // sitemap sitemap cho hình ảnh (sản phẩm)
    "sitemap-images" => 1,
    // sitemap sitemap cho hình ảnh (blog)
    "sitemap-blog-images" => 1,
    // for page
    "sitemap-page" => 1,
    "sitemap-page-images" => 1,
    "sitemap-other-images" => 1,

    "temp" => 1,

    // cài đặt tự động tài khoản admin
    "wgr-install" => 1,

    "profile" => 1,
    "password" => 1,
    "process" => 1,

    "eb-login" => 1,
    "eb-register" => 1,
    "eb-quick-register" => 1,
    "eb-fogotpassword" => 1,
    "resetpassword" => 1,

    "eb-ajaxservice" => 1,
    "download_img_to_site" => 1,
    "get_post_id_for_menu" => 1,

    "php_info" => 1,
    "eb-load-quick-search" => 1
);


// phiên bản
$web_version = $__cf_row[ 'cf_web_version' ];
if ( $localhost == 1 )$web_version = $date_time;
define( 'web_version', $web_version );
//echo $web_version;