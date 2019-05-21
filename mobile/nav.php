<?php



$html_search_nav_mobile = EBE_html_template(
	EBE_get_custom_template( $__cf_row['cf_search_nav_mobile'], 'search' ),
	/*
	EBE_get_page_template(
		$__cf_row['cf_search_nav_mobile'],
		EB_THEME_PLUGIN_INDEX . 'html/search/',
		EB_THEME_PLUGIN_INDEX . 'html/search/' . $__cf_row['cf_search_nav_mobile'] . '.css'
	),
	*/
	array(
		'tmp.str_nav_mobile_top' => $str_nav_mobile_top,
		
//			'tmp.cart_dienthoai' => EBE_get_lang('cart_dienthoai'),
//			'tmp.cart_hotline' => EBE_get_lang('cart_hotline'),
		'tmp.nav_mobile_dienthoai' => EBE_get_lang('nav_mobile_dienthoai'),
		'tmp.nav_mobile_hotline' => EBE_get_lang('nav_mobile_hotline'),
		
		'tmp.cf_logo' => $__cf_row['cf_logo'],
		'tmp.cf_dienthoai' => $__cf_row['cf_dienthoai'],
		'tmp.cf_call_dienthoai' => $__cf_row['cf_call_dienthoai'],
		'tmp.cf_hotline' => $__cf_row['cf_hotline'],
		'tmp.cf_call_hotline' => $__cf_row['cf_call_hotline'],
	)
);


