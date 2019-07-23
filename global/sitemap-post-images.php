<?php





// thư viện dùng chung
include EB_THEME_PLUGIN_INDEX . 'global/sitemap_function.php';




//
if ( ! isset( $_GET['sitemap_post_type'] ) ) {
	$_GET['sitemap_post_type'] = 'post';
}





/*
* Danh sách post (sản phẩm)
*/
$strCacheFilter = sitemapCreateStrCacheFilter( 'sitemap-' . $_GET['sitemap_post_type'] . '-images' );
if ( $time_for_relload_sitemap > 0 ) {
	$get_list_sitemap = _eb_get_static_html ( $strCacheFilter, '', '', $time_for_relload_sitemap );
}

if ( $get_list_sitemap == false || eb_code_tester == true ) {
	
	
	//
	$get_list_sitemap = '';
	
	
	
	
	
	/*
	* media
	*/
	
	// v2
	$sql = WGR_get_sitemap_post( $_GET['sitemap_post_type'] );
	
	// ảnh ở mục ads thì cho về hết trang chủ
	if ( $_GET['sitemap_post_type'] == 'ads' ) {
		$str = '';
		foreach ( $sql as $v ) {
			// lấy danh sách ảnh của post này
			$strsql = WGR_get_sitemap_post( 'attachment', array(
				'post_parent' => $v->ID
			) );
			
			foreach ( $strsql as $v2 ) {
				$img = $v2->guid;
				
				$name = $v2->post_excerpt;
				if ( $name == '' && $v2->post_title != '' ) {
					$name = str_replace( '-', ' ', $v2->post_title );
				}
				
				//
				$str .= '
<image:image>
	<image:loc>' . $img . '</image:loc>
	<image:title><![CDATA[' . $name . ']]></image:title>
</image:image>';
				
			}
		}
		
		//
		if ( $str != '' ) {
			$get_list_sitemap .= '
<url>
	<loc>' . web_link . '</loc>
	' . $str . '
</url>';
			
		}
	}
	// còn lại thì của mục nào, cho về mục đó
	else {
		foreach ( $sql as $v ) {
			// lấy danh sách ảnh của post này
			$strsql = WGR_get_sitemap_post( 'attachment', array(
				'post_parent' => $v->ID
			) );
			
			$str = '';
			foreach ( $strsql as $v2 ) {
				$img = $v2->guid;
				
				$name = $v2->post_excerpt;
				if ( $name == '' && $v2->post_title != '' ) {
					$name = str_replace( '-', ' ', $v2->post_title );
				}
				
				//
				$str .= '
<image:image>
	<image:loc>' . $img . '</image:loc>
	<image:title><![CDATA[' . $name . ']]></image:title>
</image:image>';
				
			}
			
			
			//
			if ( $str != '' ) {
				$get_list_sitemap .= '
<url>
	<loc>' . _eb_p_link( $v->ID ) . '</loc>
	' . $str . '
</url>';
				
			}
		}
		
		
		/*
		$get_list_sitemap .= WGR_echo_sitemap_image_node(
			_eb_p_link( $v->ID ),
			_eb_get_post_img( $v->ID ),
			$v->post_title
		);
		*/
	}
	
	/*
	// v1
	$sql = new WP_Query( array(
		'posts_per_page' => $limit_image_get,
//		'orderby' => 'menu_order',
		'orderby' => 'ID',
		'order' => 'DESC',
		'post_type' => $_GET['sitemap_post_type'],
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




