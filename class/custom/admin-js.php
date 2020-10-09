<?php



//
echo '<script type="text/javascript">
var web_link = "' . str_replace( '/', '\/', $web_ad_link ) . '",
admin_link = "' . str_replace( '/', '\/', $web_ad_link ) . WP_ADMIN_DIR . '\/",

mtv_id = ' . mtv_id . ',
isLogin = mtv_id,

// luôn luôn cho phép tracking để có thể lưu log website qua google
cf_disable_tracking = "off",
cf_ga_id = "' . $__cf_row['cf_ga_id'] . '",
cf_gtag_id = "' . $__cf_row['cf_gtag_id'] . '",

date_time = ' . date_time . ',
date_real_time = ' . date_time . ',
lang_date_time_format = "' . _eb_get_option('date_format') . ' ' . _eb_get_option('time_format') . '",
lang_date_format = "' . _eb_get_option('date_format') . '",
lang_time_format = "' . _eb_get_option('time_format') . '",
year_curent = ' . $year_curent . ',
month_curent = "' . $month_curent . '",

client_ip = "' . $client_ip . '",

cf_old_domain = "' . $__cf_row['cf_old_domain'] . '",
order_max_post_new = ' . $order_max_post_new . ',

cf_tester_mode = "' . $__cf_row['cf_tester_mode'] . '",
cf_hide_supper_admin_menu = "' . $__cf_row['cf_hide_supper_admin_menu'] . '",

arr_cf_truong_tuy_bien = ' . json_encode( explode( "\n", $__cf_row['cf_truong_tuy_bien'] ) ) . ',

arr_eb_ads_status = [' . substr( $str_ads_status, 1 ) . '],
arr_eb_product_status = [' . substr( $str_product_status, 1 ) . '];


setInterval(function () {
	date_real_time++;
}, 1000);


';

//
echo '</script>';




