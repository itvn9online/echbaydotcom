<?php
/*
 * Đây là file index đã thêm chức năng ebcache vào, nhằm giúp cho việc xử lý dữ liệu được dễ dàng hơn
 */
// tham số để không nạp lại chức năng này nhiều lần
define( 'WP_ACTIVE_WGR_SUPPER_CACHE', 1 );

// file tạo cache
include __DIR__ . '/wp-content/echbaydotcom/ebcache.php';

/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define( 'WP_USE_THEMES', true );

/** Loads the WordPress Environment and Template */
require __DIR__ . '/wp-blog-header.php';