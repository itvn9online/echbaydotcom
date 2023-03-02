<?php

$add_data_id = array(
    'date_time' => date_time,
    //	'tomorrow_time' => strtotime( date( 'Y/m/d', date_time + 24 * 3600 ) ),
    //	'check_lazyload' => $check_lazyload,
    //	'web_link' => '\'' . web_link . '\'',
    'base_url_href' => '\'' . web_link . '\'',
    'web_name' => '\'' . web_name . '\'',
    'wp_content' => '\'' . EB_DIR_CONTENT . '\'',
    'lang_taikhoan' => '\'' . EBE_get_lang('taikhoan') . '\'',
    'lang_thoat' => '\'' . EBE_get_lang('thoat') . '\'',
    'lang_xacnhan_thoat' => '\'' . EBE_get_lang('xacnhan_thoat') . '\'',
    'lang_dangnhap' => '\'' . EBE_get_lang('dangnhap') . '\'',
    'lang_dangky' => '\'' . EBE_get_lang('dangky') . '\'',
    'lang_order_by' => '\'' . EBE_get_lang('order_by') . '\'',
    'lang_order_view' => '\'' . EBE_get_lang('order_view') . '\'',
    'lang_order_price_down' => '\'' . EBE_get_lang('order_price_down') . '\'',
    'lang_order_price_up' => '\'' . EBE_get_lang('order_price_up') . '\'',
    'lang_order_az' => '\'' . EBE_get_lang('order_az') . '\'',
    'lang_order_za' => '\'' . EBE_get_lang('order_za') . '\'',
    'lang_details_time_discount' => '\'' . EBE_get_lang('post_time_discount') . '\'',
    'lang_details_time_soldout' => '\'' . EBE_get_lang('post_time_soldout') . '\'',
    'lang_cart_post_null' => '\'' . EBE_get_lang('cart_post_null') . '\'',
    'lang_cart_confirm_remove' => '\'' . EBE_get_lang('cart_confirm_remove') . '\'',
    //	'service_name' => '\'' . $service_name . '\'',
    //	'co_quick_register' => '\'c_quick_register\'',
    'isQuanly' => (mtv_id > 0 && current_user_can('manage_options')) ? 1 : 0,
    'isLogin' => mtv_id,
    'uEmail' => '\'' . mtv_email . '\'',
    'echbay_for_flatsome' => defined('WGR_APP_PATH') ? 1 : 0,
    'eb_wp_post_type' => '\'' . $eb_wp_post_type . '\'',
    'logout_url' => '\'' . (mtv_id > 0 ? wp_logout_url(eb_web_protocol . ':' . _eb_full_url()) : '') . '\'',
    //	'cf_categories_url' => $cf_categories_url,
    'parent_cid' => $parent_cid,
    'cid' => $cid,
    //	'sid' => $sid,
    //	'fid' => $fid,
    'pid' => $pid,
    'eb_product_price' => $eb_product_price,
    // chế độ kiểm thử -> dùng để console lỗi nếu chế độ này đang bật
    //	'cf_ga_id' => '\'' . $__cf_row['cf_ga_id'] . '\'',
    'cf_gtag_id' => '\'' . $__cf_row['cf_gtag_id'] . '\'',
    //	'cf_tester_mode' => $__cf_row['cf_tester_mode'],
    'cf_facebook_tracking' => '\'' . $__cf_row['cf_facebook_tracking'] . '\'',
    'cf_tester_mode' => '\'' . $__cf_row['cf_tester_mode'] . '\'',
    'cf_auto_nofollow' => '\'' . $__cf_row['cf_auto_nofollow'] . '\'',
    'cf_disable_tracking' => '\'' . $__cf_row['cf_disable_tracking'] . '\'',
    'cf_fontawesome_v5' => '\'' . $__cf_row['cf_fontawesome_v5'] . '\'',
    'cf_on_primary_slider' => '\'' . $__cf_row['cf_on_primary_slider'] . '\'',
    'cf_rand_slider' => '\'' . $__cf_row['cf_rand_slider'] . '\'',
    'cf_add_to_cart_iframe' => '\'' . $__cf_row['cf_add_to_cart_iframe'] . '\'',
    'cf_big_price_before' => '\'' . $__cf_row['cf_big_price_before'] . '\'',
    // tự động submit trong phần tìm kiếm nâng cao
    'cf_search_advanced_auto_submit' => '\'' . $__cf_row['cf_search_advanced_auto_submit'] . '\'',
    // chiều rộng khung
    'cf_blog_class_style' => '\'' . $__cf_row['cf_blog_class_style'] . '\'',
    'cf_post_class_style' => '\'' . $__cf_row['cf_post_class_style'] . '\'',
    'cf_gg_api_key' => '\'' . $__cf_row['cf_gg_api_key'] . '\'',
    'cf_current_sd_price' => '\'' . $__cf_row['cf_current_sd_price'] . '\'',
    // nút chuyển ảnh trên slider
    'cf_swipe_big_banner' => '\'' . $__cf_row['cf_swipe_big_banner'] . '\'',
    'cf_btn_big_banner' => '\'' . $__cf_row['cf_btn_big_banner'] . '\'',
    'cf_arrow_big_banner' => '\'' . $__cf_row['cf_arrow_big_banner'] . '\'',
    'cf_slider_big_play' => $__cf_row['cf_slider_big_play'],
    // tỷ lệ khung ảnh trên mobile
    'cf_product_details_mobile_size' => '\'' . $__cf_row['cf_product_details_mobile_size'] . '\'',
    'cf_product_mobile2_size' => '\'' . $__cf_row['cf_product_mobile2_size'] . '\'',
    'cf_post_index_content' => '\'' . $__cf_row['cf_post_index_content'] . '\'',
    'cf_blog_index_content' => '\'' . $__cf_row['cf_blog_index_content'] . '\'',
    'cf_on_off_echbay_logo' => '\'' . $__cf_row['cf_on_off_echbay_logo'] . '\'',
    'cf_wgr_ia' => '\'' . $__cf_row['cf_wgr_ia'] . '\' * 1',
    'private_info_setting_site_url' => '\'' . $arr_private_info_setting['site_url'] . '\'',
    'private_info_setting_site_upper' => '\'' . $arr_private_info_setting['site_upper'] . '\'',

    // load phần html cho bản mobile bằng ajax
    //	'cf_search_nav_mobile' => '\'' . $__cf_row['cf_search_nav_mobile'] . '\'',
    //	'cf_details_mobilemua' => '\'' . $__cf_row['cf_details_mobilemua'] . '\'',

    //	'tid' => $tid,
    //	'url_for_cat_js' => '\'' . $url_for_cat_js . '\'' ,
    'act' => '\'' . $act . '\''
);
//$data_id = '';
$data_id = array();
foreach ($add_data_id as $k => $v) {
    //	$data_id .= ',' . $k . '=' . $v;
    $data_id[] = $k . '=' . $v;
}



//
//echo 'var ' . substr( substr( json_encode( $cache_data_id . ',' . implode( ',', $data_id ) ), 1 ), 0, -1 ) . ';';
echo 'var ' . _eb_str_text_fix_js_content($cache_data_id . ',' . implode(',', $data_id)) . ';';
//echo 'var ' . $cache_data_id . 'aaaaaaaaaaaaaaaa' . implode( ',', $add_data_id ) . ';';
//$data_id = 'var ' . $cache_data_id . $data_id . ',site_group=[' . $site_group . '],brand_group=[' . $brand_group . '],city_group=[],arr_blog_group=[' . $js_blg_id . '];';


//
/*
$data_id .= '
</script>
<script type="text/javascript" src="' .$url_for_cat_js. '"></script>
<script type="text/javascript">
';
*/
