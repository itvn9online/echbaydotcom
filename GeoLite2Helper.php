<?php



/*
* Tài liệu tham khảo:
http://nktgl.blogspot.com/2015/04/php-cach-kiem-tra-ia-chi-ip-co-en-tu.html
http://dev.maxmind.com/geoip/geoip2/geolite2/

Download code:
https://drive.google.com/file/d/0B7AGuhjjjSK5b0N2T05RZ2hsVGc/view?usp=sharing

Download database:
https://dev.maxmind.com/geoip/geoip2/geolite2/
*/




//
//define('GeoLite2Helper_PATH', dirname(__FILE__));
//define('GeoLite2Helper_PATH', WP_CONTENT_DIR . '/echbaydotcom-pro/geolite2');
define('GeoLite2Helper_PATH', EB_THEME_PLUGIN_INDEX . 'geolite2');
//echo GeoLite2Helper_PATH . '<br>';

//
//if ( is_dir( GeoLite2Helper_PATH ) ) {
	
	// path dành riêng cho EchBay Hosting
	define('GeoLite2Helper_EBPATH', '/root/lib/geolite2-db');
	
	// file db sẽ để trong thư mục upload -> theo woo
	$arr = wp_upload_dir();
	define('GeoLite2Helper_UploadPATH', $arr['basedir']);
//	echo GeoLite2Helper_UploadPATH . '<br>';
	
	// URL phụ, dùng để test trên localhost thôi
	define('GeoLite2Helper_DBPATH', ABSPATH . 'geolite2-db');
//	echo GeoLite2Helper_DBPATH . '<br>';
	
	//
	include_once EB_THEME_PLUGIN_INDEX . 'GeoLite2HelperInc.php';
	
	$cGeoLite2 = new WGR_GeoLite2Helper();
	/*
}
else {
	$cGeoLite2 = NULL;
}
*/


