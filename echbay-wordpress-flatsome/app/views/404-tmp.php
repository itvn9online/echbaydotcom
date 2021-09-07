<?php

/*
 * file 404 ở đây là bắt buộc -> nó sẽ phủ định file 404 của flatsome
 */

$act = basename( __FILE__, '.php' );
//echo __FILE__ . '<br>' . "\n";
//echo EB_THEME_URL . '<br>' . "\n";

require WGR_APP_PATH . 'views/index.php';