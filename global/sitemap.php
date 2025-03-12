<?php



// tham khảo
// https://support.google.com/webmasters/answer/178636?hl=vi




// thư viện dùng chung
include EB_THEME_PLUGIN_INDEX . 'global/sitemap_function.php';




/**
 * Tạo danh sách sitemap cho toàn bộ website
 */
$strCacheFilter = basename(__FILE__, '.php');
if ($time_for_relload_sitemap > 0) {
	$get_list_sitemap = _eb_get_static_html($strCacheFilter, '', '', $time_for_relload_sitemap);
}
//$get_list_sitemap = false;

if ($get_list_sitemap == false || eb_code_tester == true) {


	//
	$get_list_sitemap = '';



	// auto
	/*
	foreach ( $arr_active_for_404_page as $k => $v ) {
//		echo $k . '<br>' . "\n";
		
		//
//		if ( $k == 'sitemap' || strpos( $k, 'sitemap-' ) !== false ) {
		if ( strpos( $k, 'sitemap-' ) !== false ) {
			$get_list_sitemap .= WGR_echo_sitemap_node( web_link . $k, $sitemap_current_time );
		}
	}
	*/

	// manual -> chuẩn hơn trong trường hợp không có bài viết tương ứng thì sitemap không được kích hoạt
	$get_list_sitemap .= WGR_echo_sitemap_node(web_link . 'sitemap-tags', $sitemap_current_time);

	$count_post = WGR_get_sitemap_total_post();
	//	echo 'post -> ' . $count_post . '<br>' . "\n";
	if ($count_post > 0) {
		$get_list_sitemap .= WGR_echo_sitemap_node(web_link . 'sitemap-post', $sitemap_current_time);
		//		$get_list_sitemap .= WGR_echo_sitemap_node( web_link . 'sitemap-images', $sitemap_current_time );

		// phân trang cho sitemap (lấy từ trang 2 trở đi)
		$get_list_sitemap .= WGR_sitemap_part_page($count_post);
	}

	$count_post = WGR_get_sitemap_total_post('blog');
	//	echo 'blog -> ' . $count_post . '<br>' . "\n";
	if ($count_post > 0) {
		$get_list_sitemap .= WGR_echo_sitemap_node(web_link . 'sitemap-blog', $sitemap_current_time);
		//		$get_list_sitemap .= WGR_echo_sitemap_node( web_link . 'sitemap-blog-images', $sitemap_current_time );

		// phân trang cho sitemap (lấy từ trang 2 trở đi)
		$get_list_sitemap .= WGR_sitemap_part_page($count_post, 'blog', 'sitemap-blog', 'sitemap-blog-images');
	}

	$count_post = WGR_get_sitemap_total_post('product');
	//	echo 'product -> ' . $count_post . '<br>' . "\n";
	if ($count_post > 0) {
		$get_list_sitemap .= WGR_echo_sitemap_node(web_link . 'sitemap-product', $sitemap_current_time);
		//		$get_list_sitemap .= WGR_echo_sitemap_node( web_link . 'sitemap-product-images', $sitemap_current_time );

		// phân trang cho sitemap (lấy từ trang 2 trở đi)
		$get_list_sitemap .= WGR_sitemap_part_page($count_post, 'product', 'sitemap-product', 'sitemap-product-images');
	}

	$count_post = WGR_get_sitemap_total_post('page');
	//	echo 'page -> ' . $count_post . '<br>' . "\n";
	if ($count_post > 0) {
		$get_list_sitemap .= WGR_echo_sitemap_node(web_link . 'sitemap-page', $sitemap_current_time);
		//		$get_list_sitemap .= WGR_echo_sitemap_node( web_link . 'sitemap-page-images', $sitemap_current_time );

		// phân trang cho sitemap (lấy từ trang 2 trở đi)
		$get_list_sitemap .= WGR_sitemap_part_page($count_post, 'page', 'sitemap-page', 'sitemap-page-images');
	}


	// lấy toàn bộ danh sách ảnh
	/*
	$count_post = WGR_get_sitemap_total_post( 'attachment', array(
		'co_post_parent' => 1
	) );
	echo 'co attachment -> ' . $count_post . '<br>' . "\n";
	$sql = WGR_get_sitemap_post( 'attachment', array(
		'co_post_parent' => 1
	) );
//	print_r( $sql );
	echo count( $sql ) . '<br>' . "\n";
	*/
	$sql = _eb_q("SELECT ID
	FROM
		`" . wp_posts . "`
	WHERE
		( post_type = 'attachment' OR post_type = 'ebarchive' )
		AND post_status = 'inherit'
		AND post_parent > 0
		AND post_parent NOT IN ( select ID from `" . wp_posts . "` where post_type = 'ads' )
		AND post_parent NOT IN ( select ID from `" . wp_posts . "` where post_status != 'publish' )
	GROUP BY
		post_parent
	ORDER BY
		ID ASC");
	$count_post = count($sql);
	//	echo 'count_post --> ' . $count_post . '<br>' . "\n";
	if ($count_post > 0) {
		$get_list_sitemap .= WGR_echo_sitemap_node(web_link . 'sitemap-all-images', $sitemap_current_time);

		// phân trang cho sitemap (lấy từ trang 2 trở đi)
		$get_list_sitemap .= WGR_sitemap_part_page($count_post, 'post', 'sitemap-all-images', 'sitemap-all-images');
	}


	// phần ảnh ở trang chủ (không có parent)
	$count_post = WGR_get_sitemap_total_post('attachment', array(
		'post_parent' => 0
	));
	//	echo 'attachment -> ' . $count_post . '<br>' . "\n";
	if ($count_post > 0) {
		$get_list_sitemap .= WGR_echo_sitemap_node(web_link . 'sitemap-images', $sitemap_current_time);
		//		$get_list_sitemap .= WGR_echo_sitemap_node( web_link . 'sitemap-page-images', $sitemap_current_time );

		// phân trang cho sitemap (lấy từ trang 2 trở đi)
		// -> trang chủ -> ảnh vô chủ -> không cần lấy nhiều
		$get_list_sitemap .= WGR_sitemap_part_page($count_post, 'attachment', 'sitemap-images', '', array(
			'post_parent' => 0
		));
	}

	/*
	$count_post = WGR_get_sitemap_total_post( 'ads' );
//	echo 'ads -> ' . $count_post . '<br>' . "\n";
	if ( $count_post > 0 ) {
		$get_list_sitemap .= WGR_echo_sitemap_node( web_link . 'sitemap-other-images', $sitemap_current_time );
//		$get_list_sitemap .= WGR_echo_sitemap_node( web_link . 'sitemap-page', $sitemap_current_time );
//		$get_list_sitemap .= WGR_echo_sitemap_node( web_link . 'sitemap-page-images', $sitemap_current_time );
		
		// phân trang cho sitemap (lấy từ trang 2 trở đi)
		$get_list_sitemap .= WGR_sitemap_part_page( $count_post, 'ads', 'sitemap-ads', 'sitemap-other-images' );
	}
	*/



	//
	$get_list_sitemap = trim($get_list_sitemap);

	//
	$get_list_sitemap = WGR_sitemap_fixed_old_content($__cf_row['cf_replace_content'], $get_list_sitemap);

	// lưu cache
	_eb_get_static_html($strCacheFilter, $get_list_sitemap);
}



// print
WGR_echo_sitemap_css();

//echo '<sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd">
echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
' . $get_list_sitemap . '
</sitemapindex>
<!-- Sitemap content created by ' . $arr_private_info_setting['site_upper'] . ' -->';



exit();
