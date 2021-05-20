<?php
/*
 * file code mẫu cho cấu hình website wordpress
 */

//
define( 'WP_SITEURL', 'http://' . $_SERVER[ 'HTTP_HOST' ] . '/wordpress.org' ); // auto add by Echbaydotcom
define( 'WP_HOME', WP_SITEURL ); // auto add by Echbaydotcom


//Begin Really Simple SSL Load balancing fix
$server_opts = array( "HTTP_CLOUDFRONT_FORWARDED_PROTO" => "https", "HTTP_CF_VISITOR" => "https", "HTTP_X_FORWARDED_PROTO" => "https", "HTTP_X_FORWARDED_SSL" => "on", "HTTP_X_PROTO" => "SSL", "HTTP_X_FORWARDED_SSL" => "1" );
foreach ( $server_opts as $option => $value ) {
    if ( ( isset( $_ENV[ "HTTPS" ] ) && ( "on" == $_ENV[ "HTTPS" ] ) ) || ( isset( $_SERVER[ $option ] ) && ( strpos( $_SERVER[ $option ], $value ) !== false ) ) ) {
        $_SERVER[ "HTTPS" ] = "on";
        break;
    }
}
//END Really Simple SSL


define( 'WP_AUTO_UPDATE_CORE', false );
//define( 'FTP_HOST', $_SERVER['SERVER_ADDR'] );
//define( 'FTP_USER', '' );
//define( 'FTP_PASS', '' );
//
// test giao diện cho dễ -> kích hoạt multisite
//define( 'WP_ALLOW_MULTISITE', true );

// disable update theme, plugins
//define( 'DISALLOW_FILE_EDIT', true );
//define( 'DISALLOW_FILE_MODS', true );