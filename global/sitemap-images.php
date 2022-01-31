<?php



// lấy tất cả các ảnh không có parent cho về trang chủ



// thư viện dùng chung
include EB_THEME_PLUGIN_INDEX . 'global/sitemap_function.php';





/*
* Danh sách post (sản phẩm)
*/
$strCacheFilter = sitemapCreateStrCacheFilter( basename( __FILE__, '.php' ) );
if ( $time_for_relload_sitemap > 0 ) {
	$get_list_sitemap = _eb_get_static_html ( $strCacheFilter, '', '', $time_for_relload_sitemap );
}

if ( $get_list_sitemap == false || eb_code_tester == true ) {
	
	
	//
	$get_list_sitemap = '';
	
	
	
	
	
	/*
	* media
	*/
	
	// v3
	$sql = WGR_get_sitemap_post( 'attachment', array(
		'post_parent' => 0
	) );
//	print_r( $sql );
	
	foreach ( $sql as $v ) {
        $img = str_replace( ABSPATH, web_link, $v->guid );
		
		$name = $v->post_excerpt;
		if ( $name == '' && $v->post_title != '' ) {
			$name = str_replace( '-', ' ', $v->post_title );
		}
		
		//
		$get_list_sitemap .= '
<image:image>
	<image:loc><![CDATA[' . $img . ']]></image:loc>
	<image:title><![CDATA[' . $name . ']]></image:title>
</image:image>';
		
	}
	
	// lấy tất cả các ảnh không có parent cho về trang chủ
	$get_list_sitemap = '
<url>
<loc><![CDATA[' . web_link . ']]></loc>' . $get_list_sitemap . '
</url>';
	
	
//	$get_list_sitemap .= WGR_create_sitemap_image_node( $sql );
	/*
	$sql = WGR_get_sitemap_post( 'attachment' );
//	print_r( $sql );
	foreach ( $sql as $v ) {
		$img = $v->guid;
		
		$name = $v->post_excerpt;
		if ( $name == '' && $v->post_title != '' ) {
			$name = str_replace( '-', ' ', $v->post_title );
		}
		
		$url = $img;
		if ( $v->post_parent > 0 ) {
			$url = _eb_p_link( $v->post_parent );
		}
		
		$get_list_sitemap .= WGR_echo_sitemap_image_node(
			$url,
			$img,
			$name
		);
	}
	*/
	
	// v2
	/*
	$sql = WGR_get_sitemap_post();
	foreach ( $sql as $v ) {
		$get_list_sitemap .= WGR_echo_sitemap_image_node(
			_eb_p_link( $v->ID ),
			_eb_get_post_img( $v->ID ),
			$v->post_title
		);
	}
	*/
	
	/*
	// v1
	$sql = new WP_Query( array(
		'posts_per_page' => $limit_image_get,
//		'orderby' => 'menu_order',
		'orderby' => 'ID',
		'order' => 'DESC',
		'post_type' => 'post',
		'post_status' => 'publish'
	));
	//print_r( $sql );
	while ( $sql->have_posts() ) : $sql->the_post();
//		print_r($sql->post);
		
		$get_list_sitemap .= WGR_echo_sitemap_image_node(
			_eb_p_link( $sql->post->ID ),
			_eb_get_post_img( $sql->post->ID ),
			$sql->post->post_title
		);
	endwhile;
	*/
	
	
	
	//
	$get_list_sitemap = trim($get_list_sitemap);
	
	//
	if ( $__cf_row['cf_replace_content'] != '' ) {
		$get_list_sitemap = WGR_replace_for_all_content( $__cf_row['cf_replace_content'], $get_list_sitemap );
	}
	
	// lưu cache
	_eb_get_static_html ( $strCacheFilter, $get_list_sitemap, '', 1 );
	
	
}






//
WGR_echo_sitemap_css();

echo '
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
' . $get_list_sitemap . '
</urlset>';



exit();




