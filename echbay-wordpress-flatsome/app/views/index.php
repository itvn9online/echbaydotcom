<?php

//echo $act . '<br>' . "\n";

// ưu tiên views hiện tại
if ( file_exists( __DIR__ . '/' . $act . '.php' ) ) {
    include __DIR__ . '/' . $act . '.php';
}
// sau đó mới sử dụng đến các views cũ
else {
    include_once EB_THEME_PLUGIN_INDEX . 'global/' . $act . '.php';
}

get_header();

echo $main_content;

get_footer();