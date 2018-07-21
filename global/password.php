<?php



//
if ( mtv_id > 0 ) {
	$__cf_row ['cf_title'] = EBE_get_lang('pr_doimatkhau');
	
	
	//
	include EB_THEME_PLUGIN_INDEX . 'global/user.php';
	
	//
	$group_go_to[] = ' <li><a href="./password" rel="nofollow">' . $__cf_row ['cf_title'] . '</a></li>';
	
	
	//
//	$main_content = EBE_str_template( 'password.html', array(
	$main_content = EBE_html_template( $user_temp, array(
		'tmp.user_module_name' => EBE_get_lang('pr_doimatkhau'),
		'tmp.pr_short_matkhau' => EBE_get_lang('pr_short_matkhau'),
		'tmp.pr_matkhau' => EBE_get_lang('pr_matkhau'),
		'tmp.pr_capnhat' => EBE_get_lang('pr_capnhat')
//	), EB_THEME_PLUGIN_INDEX . 'html/' );
	) );
}
else {
	// không được include file 404 -> vì sẽ gây lỗi vòng lặp liên tọi
	$main_content = '<br><h1 class="text-center">Permission ERROR!</h1><br>';
}


