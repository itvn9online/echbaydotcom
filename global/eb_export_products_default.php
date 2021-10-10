<?php


//exit();


echo '<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:wp="http://wordpress.org/export/1.2/">
	<channel>
		<title>Web giá rẻ</title>
		<link>http://webgiare.org</link>
		<description>Thiết kế web giá rẻ, wordpress giá rẻ</description>
		<pubDate>Tue, 10 Jan 2017 15:01:49 +0000</pubDate>
		<language>en-US</language>
		<wp:wxr_version>1.2</wp:wxr_version>
		<wp:base_site_url>http://webgiare.org</wp:base_site_url>
		<wp:base_blog_url>http://webgiare.org</wp:base_blog_url>
		<wp:author>
			<wp:author_id>1</wp:author_id>
			<wp:author_login>
				<![CDATA[itvn9online]]>
			</wp:author_login>
			<wp:author_email>
				<![CDATA[itvn9online@gmail.com]]>
			</wp:author_email>
			<wp:author_display_name>
				<![CDATA[itvn9online]]>
			</wp:author_display_name>
			<wp:author_first_name>
				<![CDATA[]]>
			</wp:author_first_name>
			<wp:author_last_name>
				<![CDATA[]]>
			</wp:author_last_name>
		</wp:author>
		<generator>https://wordpress.org/?v=4.7</generator>';


//
foreach ( $sql as $v ) {
	
	//
//	print_r( $v );
	
	//
//	$p_link = web_link . _eb_p_link( $v->ID );
	$p_link = _eb_p_link( $v->ID );
	
	//
	$trv_img = _eb_get_post_img( $v->ID );
	if ( strpos( $trv_img, '//' ) == false ) {
		$trv_img = web_link . $trv_img;
	}
	
	
	//
	echo '
<item>
	<title><![CDATA[' . $v->post_title . ']]></title>
	<link>' . $p_link . '</link>
	<pubDate>' . $v->post_date . '</pubDate>
	<dc:creator><![CDATA[itvn9online]]></dc:creator>
	<guid isPermaLink="false">' . $p_link . '</guid>
	<description><![CDATA[' . $v->post_excerpt . ']]></description>
	<content:encoded><![CDATA[' . $v->post_content . ']]></content:encoded>
	<excerpt:encoded><![CDATA[' . $v->post_excerpt . ']]></excerpt:encoded>
	<wp:post_id>' . $v->ID . '</wp:post_id>
	<wp:post_date><![CDATA[' . $v->post_date . ']]></wp:post_date>
	<wp:post_date_gmt><![CDATA[' . $v->post_date_gmt . ']]></wp:post_date_gmt>
	<wp:comment_status><![CDATA[' . $v->comment_status . ']]></wp:comment_status>
	<wp:ping_status><![CDATA[' . $v->ping_status . ']]></wp:ping_status>
	<wp:post_name><![CDATA[' . $v->post_name . ']]></wp:post_name>
	<wp:status><![CDATA[' . $v->post_status . ']]></wp:status>
	<wp:post_parent>' . $v->post_parent . '</wp:post_parent>
	<wp:menu_order>' . $v->menu_order . '</wp:menu_order>
	<wp:post_type><![CDATA[' . $v->post_type . ']]></wp:post_type>
	<wp:post_password><![CDATA[' . $v->post_password . ']]></wp:post_password>
	<wp:is_sticky>0</wp:is_sticky>';
	
	
	//
	$post_categories = wp_get_post_categories( $v->ID );
//	print_r( $post_categories );
	foreach($post_categories as $c){
		$cat = get_term( $c );
//		print_r( $cat );
		
		echo '<category domain="' . $cat->taxonomy . '" nicename="' . $cat->slug . '"><![CDATA[' . $cat->name . ']]></category>';
	}
	
	
	//
	$arr_post_options = wp_get_object_terms( $v->ID, 'post_tag' );
//	print_r( $arr_post_options );
	
	foreach($arr_post_options as $c){
		$cat = get_term( $c, 'post_tag' );
//		print_r( $cat );
		
		echo '<category domain="' . $cat->taxonomy . '" nicename="' . $cat->slug . '"><![CDATA[' . $cat->name . ']]></category>';
	}
	
	
	//
	$arr_post_options = wp_get_object_terms( $v->ID, 'post_options' );
//	print_r( $arr_post_options );
	
	foreach($arr_post_options as $c){
		$cat = get_term( $c, 'post_options' );
//		print_r( $cat );
		
		echo '<category domain="' . $cat->taxonomy . '" nicename="' . $cat->slug . '"><![CDATA[' . $cat->name . ']]></category>';
	}
	
	
	//
	$arr_post_options = wp_get_object_terms( $v->ID, 'blogs' );
//	print_r( $arr_post_options );
	
	foreach($arr_post_options as $c){
		$cat = get_term( $c, 'blogs' );
//		print_r( $cat );
		
		echo '<category domain="' . $cat->taxonomy . '" nicename="' . $cat->slug . '"><![CDATA[' . $cat->name . ']]></category>';
	}
	
	
	//
	$arr_post_options = wp_get_object_terms( $v->ID, 'blog_tag' );
//	print_r( $arr_post_options );
	
	foreach($arr_post_options as $c){
		$cat = get_term( $c, 'blog_tag' );
//		print_r( $cat );
		
		echo '<category domain="' . $cat->taxonomy . '" nicename="' . $cat->slug . '"><![CDATA[' . $cat->name . ']]></category>';
	}
	
	
	
	// nạp lại các post meta của post này
	_eb_get_post_object( $v->ID, '_eb_product_price' );
//	print_r( $arr_object_post_meta );
	
	//
	$arr_meta = $arr_object_post_meta[ 'id' . $v->ID ];
//	print_r( $arr_meta );
	
	// nếu không tồn tại ảnh đại diện hoặc không có -> lấy
	$avt_key = '_eb_product_avatar';
//	if ( $v->post_type == 'ads' ) {
//		$avt_key = '';
//	}
//	if ( ! isset( $arr_meta['_eb_product_avatar'] ) || $arr_meta['_eb_product_avatar'] == '' ) {
		$arr_meta['_eb_product_avatar'] = _eb_get_post_img($v->ID, 'medium_large');
//	}
	
	foreach ( $arr_meta as $k2 => $v2 ) {
		if (
			strpos( $k2, '_eb_product_' ) != false
			|| strpos( $k2, '_eb_ads_' ) != false
			|| strpos( $k2, '_eb_blog_' ) != false
		) {
			echo '
			<wp:postmeta>
				<wp:meta_key>' . $k2 . '</wp:meta_key>
				<wp:meta_value><![CDATA[' . $v2 . ']]></wp:meta_value>
			</wp:postmeta>';
		}
	}
	
	
	
	//
	echo '
</item>';
		
	//
}


//
echo '</channel>
</rss>';


