<?php

//echo $act . '<br>' . "\n";

//
global $id_for_get_sidebar;
//global $group_go_to;
$group_go_to = [];
global $image_og_image;
//echo 'image_og_image: ' . $image_og_image . '<br>' . "\n";
global $url_og_url;
global $global_dymanic_meta;
//global $parent_cid;
$parent_cid = 0;

// ưu tiên views hiện tại
if (is_file(__DIR__ . '/' . $act . '.php')) {
    include __DIR__ . '/' . $act . '.php';
}
// sau đó mới sử dụng đến các views cũ
else {
    include_once EB_THEME_PLUGIN_INDEX . 'global/' . $act . '.php';
}
$group_go_to = implode(' ', $group_go_to);


//
get_header();

// xử lý nội dung trước khi in ra
/*
if ( $id_for_get_sidebar == '' ) {
    $id_for_get_sidebar = id_default_for_get_sidebar;
}
echo $id_for_get_sidebar . '<br>' . "\n";
*/
//include_once EB_THEME_PLUGIN_INDEX . 'themes/top/breadcrumb-top1.php';
include_once EB_THEME_PLUGIN_INDEX . 'themes/top/breadcrumb2-top1.php';
include_once EB_THEME_PLUGIN_INDEX . 'common_category_list.php';
include_once EB_THEME_PLUGIN_INDEX . 'common_content.php';

// chốt
echo $main_content;

get_footer();
