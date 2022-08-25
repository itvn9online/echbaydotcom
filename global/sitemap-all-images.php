<?php


// lấy tất cả các ảnh không có parent cho về trang chủ


// thư viện dùng chung
include EB_THEME_PLUGIN_INDEX . 'global/sitemap_function.php';


/*
 * Danh sách post (sản phẩm)
 */
$strCacheFilter = sitemapCreateStrCacheFilter( basename( __FILE__, '.php' ) );
if ( $time_for_relload_sitemap > 0 ) {
    $get_list_sitemap = _eb_get_static_html( $strCacheFilter, '', '', $time_for_relload_sitemap );
}

if ( $get_list_sitemap == false || eb_code_tester == true ) {


    //
    $get_list_sitemap = '';


    /*
     * media
     */

    // v3

    // phân trang
    $trang = isset( $_GET[ 'trang' ] ) ? ( int )$_GET[ 'trang' ] : 1;

    $sql = _eb_q( "SELECT ID
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
		ID ASC" );
    $totalThread = count( $sql );
    //	echo 'totalThread --> ' . $totalThread . '<br>' . "\n";
    $threadInPage = $limit_post_get;
    //	$threadInPage = 10;

    $totalPage = ceil( $totalThread / $threadInPage );
    if ( $totalPage < 1 ) {
        $totalPage = 1;
    }

    if ( $trang > $totalPage ) {
        $trang = $totalPage;
    } else if ( $trang < 1 ) {
        $trang = 1;
    }

    $offset = ( $trang - 1 ) * $threadInPage;

    //
    $sql = _eb_q( "SELECT post_parent
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
		ID ASC
	LIMIT " . $offset . ", " . $threadInPage );
    //	print_r( $sql );
    //	exit();

    foreach ( $sql as $v ) {
        $strsql = _eb_q( "SELECT *
		FROM
			`" . wp_posts . "`
		WHERE
			( post_type = 'attachment' OR post_type = 'ebarchive' )
			AND post_status = 'inherit'
			AND post_parent = " . $v->post_parent . "
		ORDER BY
			ID ASC
		LIMIT 0, 10" );

        //
        $get_list_img_sitemap = '';
        foreach ( $strsql as $v2 ) {
            $img = str_replace( ABSPATH, web_link, $v2->guid );

            $name = $v2->post_excerpt;
            if ( $name == '' && $v2->post_title != '' ) {
                $name = str_replace( '-', ' ', $v2->post_title );
            }

            //
            $get_list_img_sitemap .= '
<image:image>
	<image:loc><![CDATA[' . $img . ']]></image:loc>
	<image:title><![CDATA[' . $name . ']]></image:title>
</image:image>';
        }

        // lấy tất cả các ảnh không có parent cho về trang chủ
        $get_list_sitemap .= '
<url>
<loc><![CDATA[' . _eb_p_link( $v->post_parent ) . ']]></loc>' . $get_list_img_sitemap . '
</url>';

    }


    //
    $get_list_sitemap = trim( $get_list_sitemap );

    //
    if ( $__cf_row[ 'cf_replace_content' ] != '' ) {
        $get_list_sitemap = WGR_replace_for_all_content( $__cf_row[ 'cf_replace_content' ], $get_list_sitemap );
    }

    // lưu cache
    _eb_get_static_html( $strCacheFilter, $get_list_sitemap, '', 1 );


}


//
WGR_echo_sitemap_css();

echo '
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
' . $get_list_sitemap . '
</urlset>';


exit();