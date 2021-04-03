<?php


/*
* Chức năng export bài viết từ woocomerce để import sang echbaydotcom
*/




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
    //print_r($v);
    //die('fgh ddgd');
//	$p_link = web_link . _eb_p_link( $v->ID );
	$p_link = _eb_p_link( $v->ID );
	
	$trv_img = _eb_get_post_img( $v->ID );
	if ( strstr( $trv_img, '//' ) == false ) {
		$trv_img = web_link . $trv_img;
	}
	
	
	//$category_slug = '';
	//$category_name = '';
	
	
	//
echo '<item>
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
	<wp:post_type><![CDATA[post]]></wp:post_type>
	<wp:post_password><![CDATA[' . $v->post_password . ']]></wp:post_password>
	<wp:is_sticky>0</wp:is_sticky>';
	
	
	//
	$arr_post_options = wp_get_object_terms( $v->ID, 'product_cat' );
	//print_r( $arr_post_options );
	
	foreach($arr_post_options as $c){
		$cat = get_term( $c, 'product_cat' );
//		print_r( $cat );
		
		echo '<category domain="category" nicename="' . $cat->slug . '"><![CDATA[' . $cat->name . ']]></category>';
	}
	
	
	//
	$arr_post_options = wp_get_object_terms( $v->ID, 'product_tag' );
	//print_r( $arr_post_options );
	
	foreach($arr_post_options as $c){
		$cat = get_term( $c, 'product_tag' );
//		print_r( $cat );
		
		echo '<category domain="post_tag" nicename="' . $cat->slug . '"><![CDATA[' . $cat->name . ']]></category>';
	}
    
    
        
echo '<wp:postmeta>
		<wp:meta_key><![CDATA[_eb_product_status]]></wp:meta_key>
		<wp:meta_value><![CDATA[0]]></wp:meta_value>
	</wp:postmeta>
	<wp:postmeta>
		<wp:meta_key><![CDATA[_eb_product_sku]]></wp:meta_key>
		<wp:meta_value><![CDATA[]]></wp:meta_value>
	</wp:postmeta>
	<wp:postmeta>
		<wp:meta_key><![CDATA[_eb_product_oldprice]]></wp:meta_key>
		<wp:meta_value><![CDATA[' . _eb_get_post_object( $v->ID, '_regular_price' ) . ']]></wp:meta_value>
	</wp:postmeta>
	<wp:postmeta>
		<wp:meta_key><![CDATA[_eb_product_price]]></wp:meta_key>
		<wp:meta_value><![CDATA[' . _eb_get_post_object( $v->ID, '_regular_price' ) . ']]></wp:meta_value>
	</wp:postmeta>
	<wp:postmeta>
		<wp:meta_key><![CDATA[_eb_product_leech_sku]]></wp:meta_key>
		<wp:meta_value><![CDATA[' . _eb_get_post_object( $v->ID, '_sku' ) . ']]></wp:meta_value>
	</wp:postmeta>
	<wp:postmeta>
		<wp:meta_key><![CDATA[_eb_product_avatar]]></wp:meta_key>
		<wp:meta_value><![CDATA[' . _eb_get_post_img( $v->ID ) . ']]></wp:meta_value>
	</wp:postmeta>
</item>';
    
    
    //die('gfh f sf');
}


//
echo '</channel>
</rss>';




