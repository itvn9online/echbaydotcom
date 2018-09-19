<!-- Plugin by EchBay.com - Theme by WebGiaRe.org -->
<!DOCTYPE html>
<html lang="<?php echo $__cf_row['cf_content_language']; ?>" class="no-js no-svg" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
<head>
<!-- header -->
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<link rel="profile" href="http://gmgp.org/xfn/11" />
<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php endif; ?>
<?php

echo _eb_tieu_de_chuan_seo( $__cf_row ['cf_title'] );
echo WGR_show_header_favicon();

?>
<meta http-equiv="x-dns-prefetch-control" content="on">
<link rel="dns-prefetch" href="//www.google-analytics.com" />
<link rel="dns-prefetch" href="//connect.facebook.net" />
<!-- META for design by EchBay - http://echbay.com/ -->
<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
<meta name="RESOURCE-TYPE" content="DOCUMENT" />
<meta name="DISTRIBUTION" content="GLOBAL" />
<meta name="CODE AUTHOR" content="EchBay.Com" />
<meta name="COPYRIGHT" content="Copyright (c) 2011 by EchBay.com - lienhe@echbay.com" />
<!-- // META for design by EchBay -->
<?php
include EB_THEME_PLUGIN_INDEX . 'seo.php';
?>
<!-- Global site format by EchBay -->
<base href="<?php echo web_link; ?>" />
<?php

// thêm 1 số dns-prefetch cần thiết
/*
if ( $__cf_row['cf_ga_id'] != '' ) {
	echo '<link rel="dns-prefetch" href="//www.google-analytics.com" />';
}

if ( $__cf_row['cf_facebook_id'] != '' ) {
	echo '<link rel="dns-prefetch" href="//connect.facebook.net" />';
}
*/



// thêm NAV menu cho bản mobile
if ( $__cf_row['cf_search_nav_mobile'] != 'none' ) {
	
	
	//
	$html_search_nav_mobile = EBE_html_template(
		EBE_get_custom_template( $__cf_row['cf_search_nav_mobile'], 'search' ),
		/*
		EBE_get_page_template(
			$__cf_row['cf_search_nav_mobile'],
			EB_THEME_PLUGIN_INDEX . 'html/search/',
			EB_THEME_PLUGIN_INDEX . 'html/search/' . $__cf_row['cf_search_nav_mobile'] . '.css'
		),
		*/
		array(
			'tmp.str_nav_mobile_top' => $str_nav_mobile_top,
			
			'tmp.cart_dienthoai' => EBE_get_lang('cart_dienthoai'),
			'tmp.cart_hotline' => EBE_get_lang('cart_hotline'),
			
			'tmp.cf_logo' => $__cf_row['cf_logo'],
			'tmp.cf_dienthoai' => $__cf_row['cf_dienthoai'],
			'tmp.cf_call_dienthoai' => $__cf_row['cf_call_dienthoai'],
			'tmp.cf_hotline' => $__cf_row['cf_hotline'],
			'tmp.cf_call_hotline' => $__cf_row['cf_call_hotline'],
		)
	);
}
else {
	$__cf_row['cf_default_css'] .= 'body.style-for-mobile{margin-top:0}';
}




// một số css liên quan tới việc hiển thị màn hình đầu tiên, giúp tăng điểm trên google page speed
//_eb_add_css( $arr_for_add_css );
//_eb_add_compiler_css( $arr_for_add_css + $arr_for_add_theme_css );
_eb_add_compiler_css( $arr_for_add_css );
//print_r( $arr_for_add_css );


// các css ít quan trọng hơn sẽ được add về phía sau, dưới dạng link
//_eb_add_compiler_link_css( $arr_for_add_link_css, 'link' );
//_eb_add_compiler_css( $arr_for_add_theme_css );


// phần style này phải đặt ở cuối cùng, để nó replace tất cả các style tĩnh trước đó
?>
<style type="text/css">
/* EchBay custom CSS for replace default CSS by plugin or theme */
<?php

echo $__cf_row['cf_default_css'] . $__cf_row['cf_default_themes_css'];

?>
</style>
<script type="text/javascript">
/* data_id */
<?php include EB_THEME_PLUGIN_INDEX . 'data_id.php'; ?>
var web_link = '<?php echo web_link; ?>';
/*
if ( document.domain != 'localhost' ) {
	web_link = window.location.protocol + '//' + document.domain + '/';
	if ( web_link != base_url_href ) {
		document.getElementsByTagName("base")[0].setAttribute("href", web_link);
	}
}
*/
</script>
<!-- HEAD by EchBay -->
<?php echo $__cf_row['cf_js_head']; ?>
<!-- // Global site format by EchBay -->
<?php

wp_head();



//
EBE_print_product_img_css_class( $eb_background_for_post );

// reset lại mục này, để còn insert CSS xuống footer nếu có
$eb_background_for_post = array();

//
//print_r( $css_m_css );

?>
</head>
<!--Thêm class tượng trưng cho mỗi trang lên BODY để tùy biến-->
<body class="<?php echo trim( implode( ' ', $css_m_css ) . $class_css_of_post ); ?>">
