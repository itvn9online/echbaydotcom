<?php



/*
* Đây là ví dụ về nguồn cấp RSS 2.0 được tối ưu hóa cho Bài viết tức thời:
https://developers.facebook.com/docs/instant-articles/publishing/setup-rss-feed#sample-feed*/




//
//$rssCacheFilter = 'rss-' . $export_type;
$rss_content = _eb_get_static_html ( $rssCacheFilter, '', '', 300 );
//$rss_content = false;
if ($rss_content == false || isset($_GET['wgr_real_time'])) {
	
	
	
	//
$rss_content = '';


//
foreach ( $sql as $v ) {
//	print_r($v);
	
	$p_link = _eb_p_link( $v->ID );
	
	
	// tạo HTML cho phần content
	$content = apply_filters('the_content', $v->post_content);
	
	$pubDate = explode( ' ', $v->post_date );
	
	
	//
$rss_content .= ' <item>
<title><![CDATA[' . $v->post_title . ']]></title>
<link>' . $p_link . '</link>
<guid>' . $v->ID . '</guid>
<pubDate>' . $pubDate[0] . 'T' . $pubDate[1] . 'Z</pubDate>
<author><![CDATA[' . get_the_author_meta('display_name', $v->post_author) . ']]></author>
<description><![CDATA[' . $v->post_excerpt . ']]></description>
<content:encoded><![CDATA[<!doctype html>
<html lang="' . $__cf_row['cf_content_language'] . '" prefix="op: http://media.facebook.com/op#">
<head>
<meta charset="utf-8">
<link rel="canonical" href="' . $p_link . '">
<meta property="op:markup_version" content="v1.0">
</head>
<body>
<article>
<header>
<!— Article header goes here -->
</header>
' . $content . '
<footer>
<!— Article footer goes here -->
</footer>
</article>
</body>
</html>]]></content:encoded>
</item>';

}




// tổng hợp lại
$rss_content = '<rss version="2.0"
xmlns:content="http://purl.org/rss/1.0/modules/content/">
<channel>
<title>News Publisher</title>
<link>
http://www.example.com/
</link>
<description> Read our awesome news, every day. </description>
<language>' . $__cf_row['cf_content_language'] . '</language>
<lastBuildDate>' . date('Y-m-d', date_time) . 'T' . date('H:m:i', date_time) . 'Z</lastBuildDate>
' . $rss_content . '
</channel>
</rss>';
	
	
	
	
	// ép lưu cache
	_eb_get_static_html ( $rssCacheFilter, $rss_content, '', 60 );
	
}


//
if ( $__cf_row['cf_replace_rss_content'] != '' ) {
	$rss_content = WGR_replace_for_all_content( $__cf_row['cf_replace_rss_content'], $rss_content );
}

echo $rss_content;



