<?php

// kiểm tra nạp footer và kích hoạt cache cho web
require __DIR__ . '/header_cache.php';

/*
 * @daidq - 0984533228 - itvn9online@gmail.com
 * Chỉnh sửa và phát triển theo hướng chuyên cho thị trường Việt Nam
 * Bên dưới là header của flatsome
 */

?>
<!DOCTYPE html>
<!--[if IE 9 ]> <html <?php language_attributes(); ?> class="ie9 <?php flatsome_html_classes(); ?>"> <![endif]-->
<!--[if IE 8 ]> <html <?php language_attributes(); ?> class="ie8 <?php flatsome_html_classes(); ?>"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html <?php language_attributes(); ?> class="<?php flatsome_html_classes(); ?>">
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<base href="<?php echo web_link; ?>" />
<?php

// đặt tham số này để không nạp lại base nữa
//define( 'HAS_BASE_HREF', true );

wp_head();
?>
</head>
<body <?php body_class(); ?>>
<?php do_action( 'flatsome_after_body_open' ); ?>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main">
<?php esc_html_e( 'Skip to content', 'flatsome' ); ?>
</a>
<div id="wrapper">
<?php


/*
 * nạp top của echbaydotcom (nếu có)
 */
if ( file_exists( EB_CHILD_THEME_URL . 'ui/' . basename( EB_CHILD_THEME_URL ) . '-top1.php' ) ) {
    global $__cf_row;
    //print_r($__cf_row);
    include EB_CHILD_THEME_URL . 'ui/' . basename( EB_CHILD_THEME_URL ) . '-top1.php';
}
/*
 * không thì dùng top của flatsome
 */
else {
    do_action( 'flatsome_before_header' );
    ?>
<header id="header" class="header <?php flatsome_header_classes(); ?>">
    <div class="header-wrapper">
        <?php get_template_part( 'template-parts/header/header', 'wrapper' ); ?>
    </div>
</header>
<?php
do_action( 'flatsome_after_header' );
}

?>
<main id="main" class="<?php flatsome_main_classes(); ?>">
