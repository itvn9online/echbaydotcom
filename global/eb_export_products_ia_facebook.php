<?php



/*
* Đây là ví dụ về nguồn cấp RSS 2.0 được tối ưu hóa cho Bài viết tức thời:
https://developers.facebook.com/docs/instant-articles/publishing/setup-rss-feed#sample-feed

Hoặc cài thêm Plugin IA Facebook rồi xem theo link sau:
http://www.yoursite.com/feed/instant-articles
*/


function FB_IA_change_tag ( $str ) {
	$arr = array(
		'img' => 'figure-img'
	);
	
	foreach ( $arr as $k => $v ) {
		$str = FB_IA_change_tag2 ( $str, $k, $v );
	}
	
	//
	return $str;
}

function FB_IA_change_tag2 ( $str, $tag, $new_tag, $end_tag = '>' ) {
		
		//
		$c = explode( '<' . $tag . ' ', $str );
//		print_r( $c );
		
		$new_str = '';
		foreach ( $c as $k => $v ) {
			
			// bỏ qua mảng số 0
			if ( $k > 0 ) {
				$v2 = explode( '>', $v );
				$v2 = $v2[0];
	//			echo $v2. "\n";
	//			echo substr( $v2, -1 ) . "\n";
	//			echo substr( $v2, 0, -1 ) . "\n";
				
				// xóa đoạn
				$v = str_replace( $v2, '', $v );
				$v = substr( $v, 1 );
				
				//
				if ( substr( $v2, -1 ) == '/' ) {
					$v2 = substr( $v2, 0, -1 );
				}
				$v2 = trim($v2);
				
				// với hình ảnh, nếu thiếu layout thì bổ sung
				if ( $new_tag == 'figure-img' ) {
//					$new_tag = 'figure';
					
					$img_src = explode( 'src="', $v2 );
					$img_src = $img_src[1];
					$img_src = explode( '"', $img_src );
					$img_src = $img_src[0];
//					echo $img_src . "\n";
					
					//
					$v2 = '><img src="' . $img_src . '"/';
				}
				
				// tổng hợp nội dung lại
				$v = '<' . $new_tag . ' ' . $v2 . '></' . $new_tag . '>' . $v;
			}
			
			//
			$new_str .= $v;
		}
		$new_str = str_replace( '<figure-img ', '<figure', $new_str );
		$new_str = str_replace( '</figure-img>', '</figure>', $new_str );
		
		return $new_str;
}

function FB_IA_remove_attr ( $str ) {
	
	//
	$arr = array(
		'id',
		'class',
		'style',
		'dir',
		'type',
		'border',
		'align',
		'loading',
		
		// iframe
		'frameborder',
		'scrolling',
		'allowfullscreen',
		
		//
		'longdesc'
	);
	
	// xóa từng attr đã được chỉ định
	foreach ( $arr as $v ) {
		$str = FB_IA_remove_attr2 ( $str, ' ' . $v . '="', '"' );
		$str = FB_IA_remove_attr2 ( $str, " " . $v . "='", "'" );
	}
	
	
	
	
	// xóa các thẻ không còn được hỗ trợ
	/*
	$arr = array(
		'style',
		'font'
	);
	
	//
	foreach ( $arr as $v ) {
		$str = $this->remove_tag ( $str, $v );
	}
	*/
	
	
	
	//
	return $str;
}

function FB_IA_remove_attr2 ( $str, $attr, $end_attr = '"' ) {
	
	// cắt mảng theo attr nhập vào
	$c = explode( $attr, $str );
//		print_r( $c );
	
	$new_str = '';
	foreach ( $c as $k => $v ) {
		// chạy vòng lặp -> bỏ qua mảng đầu tiên
		if ( $k > 0 ) {
			// dữ liệu mới bắt đầu từ đoạn kết thúc trước đó
			$v = strstr( $v, $end_attr );
			
			// cắt bỏ đoạn thừa
			$v = substr( $v, strlen( $end_attr ) );
		}
		
		//
		$new_str .= $v;
	}
	
	// done
	return $new_str;
}



//
//$rssCacheFilter = 'rss-' . $export_type;
$rss_content = _eb_get_static_html ( $rssCacheFilter, '', '', 300 );
//$rss_content = false;
if ($rss_content == false || isset($_GET['wgr_real_time']) || eb_code_tester == true) {
	
	
	
	//
$rss_content = '';


//
foreach ( $sql as $v ) {
//	print_r($v);
	
	$p_link = _eb_p_link( $v->ID );
	
	
	// tạo HTML cho phần content
	$content = apply_filters('the_content', $v->post_content);
	
	$pubDate = explode( ' ', $v->post_date );
	$pubModified = explode( ' ', $v->post_modified );
	
	$_eb_product_source_author = _eb_get_post_object( $v->ID, '_eb_product_source_author' );
	if ( $_eb_product_source_author == '' ) {
		$_eb_product_source_author = get_the_author_meta('display_name', $v->post_author);
	}
	
	
	//
	$content = FB_IA_remove_attr( $content );
	$content = FB_IA_change_tag( $content );
	
	//
	$op_kicker = '';
	$post_categories = wp_get_post_categories( $v->ID );
//	print_r( $post_categories );
	if ( ! empty( $post_categories ) ) {
		$cat = get_term( $post_categories[0] );
//		print_r( $cat );
		
//		if ( ! empty( $cat ) ) {
		if ( isset( $cat->name ) ) {
			$op_kicker = '<h3 class="op-kicker">' . $cat->name . '</h3>';
		}
	}
	
	
	// IA HTML content
	$IA_HTML_content = '<!doctype html>
<html>
  <head>
    <link rel="canonical" href="' . $p_link . '"/>
    <meta charset="utf-8"/>
    <meta property="op:generator" content="facebook-instant-articles-sdk-php"/>
    <meta property="op:generator:version" content="1.10.0"/>
    <meta property="op:generator:application" content="facebook-instant-articles-wp"/>
    <meta property="op:generator:application:version" content="4.2.1"/>
    <meta property="op:generator:transformer" content="facebook-instant-articles-sdk-php"/>
    <meta property="op:generator:transformer:version" content="1.10.0"/>
    <meta property="op:markup_version" content="v1.0"/>
    <meta property="fb:article_style" content="default"/>
  </head>
  <body>
    <article>
      <header>
        <h1>' . $v->post_title . '</h1>
        <time class="op-published" datetime="' . $pubDate[0] . 'T' . $pubDate[1] . '+07:00">' . date( 'F jS, H:ia', strtotime( $v->post_date ) ) . '</time>
        <time class="op-modified" datetime="' . $pubModified[0] . 'T' . $pubModified[1] . '+07:00">' . date( 'F jS, H:ia', strtotime( $v->post_modified ) ) . '</time>
        <address><a>' . $_eb_product_source_author . '</a></address>
		' . $op_kicker . '
      </header>
	' . $content . '
    </article>
  </body>
</html>';
	
	// v1
	/*
	$IA_HTML_content = '<!doctype html>
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
</html>';
	*/
	
	
	//
$rss_content .= '<item>
<title>' . $v->post_title . '</title>
<link>' . $p_link . '</link>
<content:encoded><![CDATA[' . $IA_HTML_content . ']]></content:encoded>
<guid isPermaLink="false">' . $p_link . '</guid>
<description><![CDATA[' . $v->post_excerpt . ']]></description>
<pubDate>' . $pubDate[0] . 'T' . $pubDate[1] . '+07:00</pubDate>
<modDate>' . $pubModified[0] . 'T' . $pubModified[1] . '+07:00</modDate>
<author>' . $_eb_product_source_author . '</author>
</item>';

}




// tổng hợp lại
$rss_content = '<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/">
<channel>
<title>' . $__cf_row['cf_web_name'] . ' RSS</title>
<link>' . web_link . '</link>
<description>' . $__cf_row['cf_description'] . '</description>
' . $rss_content . '
<lastBuildDate>' . date('Y-m-d', date_time) . 'T' . date('H:m:i', date_time) . '+07:00</lastBuildDate>
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



