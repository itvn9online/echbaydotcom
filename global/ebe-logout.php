<?php

// https://thegioidoda.vn/wp-login.php?action=logout&_wpnonce=f1fa24909d
// print_r($_SERVER);

// 
$redirect_to = web_link;
if (isset($_GET['redirect_to']) && !empty($_GET['redirect_to'])) {
    $redirect_to = $_GET['redirect_to'];
} else if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
    $redirect_to = $_SERVER['HTTP_REFERER'];
}

// echo $redirect_to . '<br>' . PHP_EOL;
// $redirect_to = wp_logout_url($redirect_to);
// $redirect_to = wp_logout_url();
echo $redirect_to . '<br>' . PHP_EOL;
// echo wp_logout_url(eb_web_protocol . ':' . _eb_full_url());
// exit();

// 
wp_logout();

// 
header("HTTP/1.1 301 Moved Permanently");
header("Location: " . $redirect_to);
exit();
