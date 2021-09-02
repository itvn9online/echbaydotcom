<!-- META for design by <?php echo $arr_private_info_setting['author']; ?> - <?php echo $arr_private_info_setting['site_url']; ?> -->
<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
<meta name="RESOURCE-TYPE" content="DOCUMENT" />
<meta name="DISTRIBUTION" content="GLOBAL" />
<meta name="CODE AUTHOR" content="<?php echo $arr_private_info_setting['site_upper']; ?>" />
<meta name="COPYRIGHT" content="Copyright (c) 2011 by <?php echo $arr_private_info_setting['site_upper']; ?> - <?php echo $arr_private_info_setting['author_email']; ?>" />
<!-- // META for design by <?php echo $arr_private_info_setting['author']; ?> -->
<!-- <?php echo $arr_private_info_setting['author']; ?> SEO plugin - <?php echo $arr_private_info_setting['site_url']; ?> -->
<?php

// thêm 1 số dns-prefetch cần thiết
if ( $__cf_row['cf_ga_id'] != '' ) {
	$echo_dns_prefetch[] = '<link rel="dns-prefetch" href="//www.google-analytics.com" />';
}

if ( $__cf_row['cf_facebook_id'] != '' ) {
	$echo_dns_prefetch[] = '<link rel="dns-prefetch" href="//connect.facebook.net" />';
}


if ( ! empty( $echo_dns_prefetch ) ) {
	echo '<meta http-equiv="x-dns-prefetch-control" content="on">';
	echo implode("\n", $echo_dns_prefetch);
}


/*
<link rel="dns-prefetch" href="//<?php echo str_replace( 'www.', '', $_SERVER['HTTP_HOST'] ); ?>" />
<link rel="dns-prefetch" href="//www.<?php echo str_replace( 'www.', '', $_SERVER['HTTP_HOST'] ); ?>" />
<link rel="dns-prefetch" href="//www.google-analytics.com" />
<link rel="dns-prefetch" href="//fonts.googleapis.com" />
<link rel="dns-prefetch" href="//fonts.gstatic.com" />
<link rel="dns-prefetch" href="//www.googletagmanager.com" />
<link rel="dns-prefetch" href="//www.google.com" />
<link rel="dns-prefetch" href="//ajax.googleapis.com" />
<link rel="dns-prefetch" href="//connect.facebook.net" />
*/


// chế độ index website
// đối với các trang riêng của plugin
if ( $act != '' && isset( $arr_active_for_404_page[ $act ] ) ) {
	if ( $__cf_row ["cf_blog_public"] == 0 ) {
		echo '<meta name="robots" content="noindex,follow" />' . "\n";
	}
}
else if ( $__cf_row ["cf_blog_public"] == 0 ) {
	// chỉ áp dụng khi giá trị của cf_blog_public khác với option blog_public
	if ( get_option( 'blog_public' ) != $__cf_row ["cf_blog_public"] ) {
//		wp_no_robots();
		echo '<meta name="robots" content="noindex,follow" />' . "\n";
	}
}


//
if ( $__cf_row ["cf_phone_detection"] != 1 ) {
	echo '<meta name="format-detection" content="telephone=no">' . "\n";
}


// các thẻ META không bị không chế bởi option cf_on_off_echbay_seo
echo $global_dymanic_meta;



// trường hợp khách hàng không sử dụng plugin SEO khác thì mới dùng plugin SEO của EchBay
//echo 'cf_on_off_echbay_seo: ' . cf_on_off_echbay_seo . '<br>' . "\n";
if ( cf_on_off_echbay_seo == 1 ) {
	
	
	ob_start();



//
echo $dynamic_meta;



// cho phép google index
//echo get_option( 'blog_public' );
/*
if ( $__cf_row ["cf_blog_public"] == 1 ) {
	echo '<meta name="robots" content="noodp,noydir" />';
}
*/
// chặn index nếu chưa có
/*
else {
	if ( get_option( 'blog_public' ) == 0 ) {
		echo '<meta name="robots" content="noindex,follow" />';
	}
	*/
	
	/*
	$sql = _eb_q("SELECT option_value
	FROM
		" . $wpdb->options . "
	WHERE
		option_name = 'blog_public'
	ORDER BY
		option_id DESC
	LIMIT 0, 1");
//	print_r($sql);
	
	//
	if ( isset( $sql[0]->option_value ) && $sql[0]->option_value == 0 ) {
		echo '<meta name="robots" content="noindex,follow" />';
	}
	*/
//}


//
$__cf_row ['cf_title'] = str_replace( '"', '&quot;', $__cf_row ['cf_title'] );
$__cf_row ['cf_keywords'] = str_replace( '"', '&quot;', $__cf_row ['cf_keywords'] );
$__cf_row ['cf_description'] = str_replace( '"', '&quot;', $__cf_row ['cf_description'] );
    
    
    //print_r($__cf_row);



?>
<meta name="revisit-after" content="1 days" />
<meta name="title" content="<?php echo $__cf_row ['cf_title']; ?>" />
<meta name="keywords" content="<?php echo $__cf_row ['cf_keywords']; ?>" />
<meta name="news_keywords" content="<?php echo $__cf_row ['cf_keywords']; ?>" />
<meta name="description" content="<?php echo $__cf_row ['cf_description']; ?>" />
<meta name="abstract" content="<?php echo $__cf_row ['cf_abstract'] != '' ? str_replace( '"', '&quot;', $__cf_row ['cf_abstract'] ) : $__cf_row ['cf_description']; ?>" />
<meta name="RATING" content="GENERAL" />
<meta name="GENERATOR" content="<?php echo $arr_private_info_setting['site_upper']; ?> eCommerce Software" />
<meta itemprop="name" content="<?php echo $__cf_row ['cf_title']; ?>" />
<meta itemprop="description" content="<?php echo $__cf_row ['cf_description']; ?>" />
<meta property="og:title" content="<?php echo $__cf_row ['cf_title']; ?>" />
<meta property="og:description" content="<?php echo $__cf_row ['cf_description']; ?>" />
<meta property="og:type" content="<?php echo $web_og_type; ?>" />
<meta property="og:site_name" content="<?php echo $web_name; ?>" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:description" content="<?php echo $__cf_row ['cf_description']; ?>" />
<meta name="twitter:title" content="<?php echo $__cf_row ['cf_title']; ?>" />
<?php
	
	
	//
	$header_seo_content = ob_get_contents();
	
	//ob_clean();
	//ob_end_flush();
	ob_end_clean();
	
	
	if ( $__cf_row['cf_replace_content'] != '' ) {
		$header_seo_content = WGR_replace_for_all_content( $__cf_row['cf_replace_content'], $header_seo_content );
	}
	echo $header_seo_content;
	
}
else {
?>
<!-- // <?php echo $arr_private_info_setting['author']; ?> SEO plugin disable by customer -->
<?php
}


// google analytics
if ( $__cf_row['cf_ga_id'] != '' && $__cf_row['cf_tester_mode'] == 'off' ) {
	// gtag
	if ( $__cf_row['cf_gtag_id'] == 1 ) {
?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $__cf_row['cf_ga_id']; ?>"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','<?php echo $__cf_row['cf_ga_id']; ?>');</script>
<?php
	}
	// analytic
	else {
?>
<script type="text/javascript">(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');ga('create','<?php echo $__cf_row['cf_ga_id']; ?>','auto');ga('require','displayfeatures');<?php echo $import_ecommerce_ga; ?>ga('send','pageview');</script>
<?php
	}
}

?>
<!-- // <?php echo $arr_private_info_setting['author']; ?> SEO plugin -->
