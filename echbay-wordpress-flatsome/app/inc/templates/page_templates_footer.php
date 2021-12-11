<?php

//
if ( !isset( $id_for_get_sidebar ) ) {
    $id_for_get_sidebar = '';
}

// chuẩn hóa nội dung theo tiêu chuẩn
include EB_THEME_PLUGIN_INDEX . 'common_content.php';

//
echo $main_content . '<br>' . "\n";

//
do_action( 'flatsome_after_page' );

get_footer();