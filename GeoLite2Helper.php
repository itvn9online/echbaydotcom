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
//define('GeoLite2Helper_PATH', dirname(__FILE__) . '/');
define('GeoLite2Helper_PATH', WP_CONTENT_DIR . '/echbaydotcom-pro/geolite2/');
//echo GeoLite2Helper_PATH . '<br>';

//
if ( is_dir( GeoLite2Helper_PATH ) ) {
	include_once EB_THEME_PLUGIN_INDEX . 'GeoLite2HelperInc.php';
	
	$cGeoLite2 = new GeoLite2Helper();
}
else {
	$cGeoLite2 = NULL;
}


