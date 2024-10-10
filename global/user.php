<?php


//
$global_module_name = EBE_get_lang('taikhoan');


//
//$group_go_to[] = ' <li><a href="./profile" rel="nofollow">' . $global_module_name . '</a></li>';
$group_go_to[] = ' <li>' . $global_module_name . '</li>';


$user_temp = EBE_str_template('user.html', array(
    'tmp.user_frm' => EBE_str_template($act . '.html', array(), EB_THEME_PLUGIN_INDEX . 'html/'),
), EB_THEME_PLUGIN_INDEX . 'html/');


// kiểm tra menu động cho user có không
$user_menu = '';
if (has_nav_menu('profile-menu-wgr')) {
    $user_menu = _eb_echbay_menu('profile-menu-wgr');
}


// còn đây là menu tĩnh, website nào cũng có menu này
$user_primary_menu = '';
$arr_primary_menu = array(
    'profile' => $global_module_name,
    'password' => EBE_get_lang('pr_doimatkhau')
);

foreach ($arr_primary_menu as $k => $v) {
    $sl = '';
    if ($k == $act) {
        $sl = 'bold redcolor';
    }

    //
    $user_primary_menu .= '<li><a href="./' . $k . '" rel="nofollow" class="' . $sl . '">' . $v . '</a></li>';
}
$user_primary_menu .= '<li><a href="ebe-logout?redirect_to=' . urlencode(web_link) . '" onClick="return confirm(\'Xác nhận đăng xuất khỏi hệ thống\');">' . EBE_get_lang('pr_logout') . '</a></li>';


//
$user_temp = EBE_html_template($user_temp, array(
    'tmp.user_primary_menu' => $user_primary_menu,
    'tmp.user_menu' => $user_menu,
    'tmp.css_link' => str_replace(ABSPATH, web_link, EB_THEME_PLUGIN_INDEX)
));
