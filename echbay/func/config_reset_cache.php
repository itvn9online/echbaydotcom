<?php



// v2
$__eb_cache_conf = EB_THEME_CACHE . '___conf.php';
if (is_file($__eb_cache_conf)) {
	unlink($__eb_cache_conf);
}

$file_last_update = EB_THEME_CACHE . '___conf.txt';
if (is_file($file_last_update)) {
	unlink($file_last_update);
}



// v1
/*
$__eb_cache_conf = EB_THEME_CACHE . '___all.php';
if ( is_file( $__eb_cache_conf ) ) {
	unlink( $__eb_cache_conf );
}

$file_last_update = EB_THEME_CACHE . '___all.txt';
if ( is_file( $file_last_update ) ) {
	unlink( $file_last_update );
}
*/




//
_eb_set_config('cf_web_version', date('md.Hi', date_time));



//
echo '<strong>Remove</strong> config cache<br>' . "\n";
