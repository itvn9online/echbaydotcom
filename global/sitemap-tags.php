<?php


// thư viện dùng chung
include EB_THEME_PLUGIN_INDEX . 'global/sitemap_function.php';





/*
* Danh sách category, tags, options....
*/
$strCacheFilter = basename(__FILE__, '.php');
if ($time_for_relload_sitemap > 0) {
	$get_list_sitemap = _eb_get_static_html($strCacheFilter, '', '', $time_for_relload_sitemap);
}

if ($get_list_sitemap == false || eb_code_tester == true) {


	//
	$get_list_sitemap = '';



	/*
	* home
	*/
	$get_list_sitemap .= WGR_echo_sitemap_url_node(
		web_link,
		1.0,
		$sitemap_current_time
	);




	/*
	* catagory
	*/
	$get_list_sitemap .= WGR_get_sitemap_taxonomy();


	// post_tag
	if ($__cf_row['cf_alow_post_tag_index'] == 1) {
		$get_list_sitemap .= WGR_get_sitemap_taxonomy('post_tag', 0.8);
	}


	// post_options
	if ($__cf_row['cf_alow_post_option_index'] == 1) {
		$get_list_sitemap .= WGR_get_sitemap_taxonomy('post_options', 0.7);
	}


	// blog
	$get_list_sitemap .= WGR_get_sitemap_taxonomy('blogs', 0.6);



	//
	$get_list_sitemap = trim($get_list_sitemap);

	//
	$get_list_sitemap = WGR_sitemap_fixed_old_content($__cf_row['cf_replace_content'], $get_list_sitemap);

	// lưu cache
	_eb_get_static_html($strCacheFilter, $get_list_sitemap);
}






//
WGR_echo_sitemap_css();

echo WGR_echo_sitemap_urlset() . $get_list_sitemap . '
</urlset>';



exit();
