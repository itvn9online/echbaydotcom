<?php


//exit();


function WGR_eb_export_products_xm_backup_get($content, $node)
{
    $content = explode(']]></' . $node . '>', $content);

    //
    //echo count( $content ) . ' aaaaaaaaaaaaaaaaa';
    //print_r( $content );
    //echo 'bbbbbbbbbbbbbbbbbb';

    //
    if (count($content) == 1) {
        return '';
    }
    $content = $content[0];
    $content = explode('<' . $node . '><![CDATA[', $content);

    //
    return $content[1];
}


function WGR_eb_export_products_xm_backup_replace($find, $replace, $c)
{
    $c = str_replace('<' . $find . '>', '<' . $replace . '>', $c);
    $c = str_replace('</' . $find . '>', '</' . $replace . '>', $c);

    return $c;
}

function WGR_eb_export_products_xm_backup_print($v)
{
    //print_r( $v );


    //
    /*
    $post_categories = wp_get_post_categories( $v->post_id );
    print_r( $post_categories );
    
    //
    $arr_post_options = wp_get_object_terms( $v->post_id, 'post_tag' );
    print_r( $arr_post_options );
    
    //
    $arr_post_options = wp_get_object_terms( $v->post_id, 'post_options' );
    print_r( $arr_post_options );
    
    //
    $arr_post_options = wp_get_object_terms( $v->post_id, 'blogs' );
    print_r( $arr_post_options );
    
    //
    $arr_post_options = wp_get_object_terms( $v->post_id, 'blog_tag' );
    print_r( $arr_post_options );
    */

    //
    //die( 'df dhdfhdfh f' );


    //
    $post_type = WGR_eb_export_products_xm_backup_get($v->bpx_content, 'post_type');
    // chỉ lấy post và blog
    if ($post_type == 'post' || $post_type == EB_BLOG_POST_TYPE) {
        // ok ok
    } else {
        return false;
    }

    //
    $post_status = WGR_eb_export_products_xm_backup_get($v->bpx_content, 'post_status');
    // không lấy bản nháp tự động
    if ($post_status == 'auto-draft') {
        return false;
    }

    //
    $v->bpx_content = str_replace('<post_mime_type><![CDATA[]]></post_mime_type>', '', $v->bpx_content);
    $v->bpx_content = str_replace('<to_ping><![CDATA[]]></to_ping>', '', $v->bpx_content);

    $v->bpx_content = WGR_eb_export_products_xm_backup_replace('post_title', 'title', $v->bpx_content);
    $v->bpx_content = WGR_eb_export_products_xm_backup_replace('post_author', 'dc:creator', $v->bpx_content);
    $v->bpx_content = WGR_eb_export_products_xm_backup_replace('post_content', 'content:encoded', $v->bpx_content);
    $v->bpx_content = WGR_eb_export_products_xm_backup_replace('post_excerpt', 'excerpt:encoded', $v->bpx_content);
    $v->bpx_content = WGR_eb_export_products_xm_backup_replace('ID', 'wp:post_id', $v->bpx_content);
    $v->bpx_content = WGR_eb_export_products_xm_backup_replace('comment_status', 'wp:comment_status', $v->bpx_content);
    $v->bpx_content = WGR_eb_export_products_xm_backup_replace('ping_status', 'wp:ping_status', $v->bpx_content);
    $v->bpx_content = WGR_eb_export_products_xm_backup_replace('post_status', 'wp:status', $v->bpx_content);
    $v->bpx_content = WGR_eb_export_products_xm_backup_replace('menu_order', 'wp:menu_order', $v->bpx_content);


    //$v->bpx_content = WGR_eb_export_products_xm_backup_replace( 'post_date_gmt', 'pubDate', $v->bpx_content );
    //$v->bpx_content = WGR_eb_export_products_xm_backup_replace( 'post_date', 'wp:post_date', $v->bpx_content );
    //$v->bpx_content = WGR_eb_export_products_xm_backup_replace( 'post_date_gmt', 'wp:post_date_gmt', $v->bpx_content );
    //$v->bpx_content = WGR_eb_export_products_xm_backup_replace( 'ID', 'wp:post_id', $v->bpx_content );
    //$v->bpx_content = WGR_eb_export_products_xm_backup_replace( 'ID', 'wp:post_id', $v->bpx_content );
    //$v->bpx_content = WGR_eb_export_products_xm_backup_replace( 'ID', 'wp:post_id', $v->bpx_content );
    //$v->bpx_content = WGR_eb_export_products_xm_backup_replace( 'ID', 'wp:post_id', $v->bpx_content );
    //$v->bpx_content = WGR_eb_export_products_xm_backup_replace( 'post_name', 'wp:post_name', $v->bpx_content );
    //$v->bpx_content = WGR_eb_export_products_xm_backup_replace( 'aaaaaaaaaaaaa', 'aaaaaaaaaaaa', $v->bpx_content );
    //$v->bpx_content = WGR_eb_export_products_xm_backup_replace( 'aaaaaaaaaaaaa', 'aaaaaaaaaaaa', $v->bpx_content );
    //$v->bpx_content = WGR_eb_export_products_xm_backup_replace( 'aaaaaaaaaaaaa', 'aaaaaaaaaaaa', $v->bpx_content );
    //$v->bpx_content = WGR_eb_export_products_xm_backup_replace( 'aaaaaaaaaaaaa', 'aaaaaaaaaaaa', $v->bpx_content );

    // xóa các phân loại lỗi -> trống thông tin
    $v->bpx_content = str_replace('<category domain="" nicename=""><![CDATA[]]></category>', '', $v->bpx_content);

    //
    $v->bpx_content = str_replace('<post_', '<wp:post_', $v->bpx_content);
    $v->bpx_content = str_replace('</post_', '</wp:post_', $v->bpx_content);

    //
    return '
<item>
' . $v->bpx_content . '
</item>';
}


//
echo '<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:wp="http://wordpress.org/export/1.2/">
<channel>
<title>Web giá rẻ</title>
<link>

http://webgiare.org
</link>
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
<generator>https://wordpress.org/?v=4.7</generator>
';


//$limit = isset( $_GET[ 'limit' ] ) ? ( int )$_GET[ 'limit' ] : 100;
$limit = 999;
//$limit = 5;
$trang = isset($_GET['trang']) ? (int)$_GET['trang'] : 1;
$offset = ($trang - 1) * $limit;


// lấy dữ liệu từ thùng rác
if (isset($_GET['trash'])) {
    $sql = _eb_q("SELECT *
	FROM
		`eb_backup_post_xml`
    GROUP BY
        post_id
    ORDER BY
        bpx_id DESC
    LIMIT " . $offset . ", " . $limit);
    //print_r( $sql );
    foreach ($sql as $v) {
        echo WGR_eb_export_products_xm_backup_print($v);
        //break;
    }
}
// mặc định chỉ lấy từ phần backup tự động
else {
    $sql = _eb_q("SELECT *
	FROM
		`eb_post_xml`
    GROUP BY
        post_id
    ORDER BY
        bpx_id DESC
    LIMIT " . $offset . ", " . $limit);
    //print_r( $sql );
    foreach ($sql as $v) {
        echo WGR_eb_export_products_xm_backup_print($v);
        //break;
    }
}


//
echo '
</channel>
</rss>
';
