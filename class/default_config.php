<?php



// mảng các tham số dành cho cấu hình website, cần thêm thuộc tính gì thì cứ thế add vào mảng này là được
$__cf_row_default = array(
	'cf_web_name' => '',
	'cf_chu_de_chinh' => '',
	
	'cf_smtp_email' => '',
	'cf_smtp_pass' => '',
	'cf_smtp_host' => '',
	'cf_smtp_port' => 25,
	// tls, ssl
	'cf_smtp_encryption' => '',
	
	'cf_title' => 'Một website sử dụng plugin thương mại điện tử của ' . $arr_private_info_setting['site_upper'],
	'cf_meta_title' => 'Một website sử dụng plugin thương mại điện tử của ' . $arr_private_info_setting['site_upper'],
	'cf_keywords' => 'wordpress e-commerce plugin, ' . $arr_private_info_setting['site_upper'] . ', thiet ke web chuyen nghiep',
	'cf_news_keywords' => 'wordpress e-commerce plugin, ' . $arr_private_info_setting['site_upper'] . ', thiet ke web chuyen nghiep',
	'cf_description' => 'Một website sử dụng plugin thương mại điện tử của ' . $arr_private_info_setting['site_upper'],
	'cf_abstract' => '',
	
	'cf_google_product_category' => '',
	'cf_gse' => '',
	'cf_ga_id' => '',
	'cf_gtag_id' => 'off',
	'cf_disable_tracking' => 'off',
	
	// mặc định sử dụng hàm PHP mail
//	'cf_sys_email' => '',
	// Mặc định sử dụng wp mail
	'cf_sys_email' => 'wpmail',
//	'cf_using_wpmail' => '',
	
	'cf_logo' => EB_URL_TUONG_DOI . 'images-global/echbay-wp-logo.png',
	// tạo H1 cho phần logo
	'cf_h1_logo' => 'off',
	'cf_auto_nofollow' => 1,
	'cf_size_logo' => 'auto', // 1/2
	'cf_height_logo' => 50,
	'cf_height_mobile_logo' => 0,
	// mảng chứa các thông tin điều chỉnh CSS cho logo -> dùng mảng JS để giảm bớt dữ liệu cho vào DB
//	'cf_css_logo' => '',
	'cf_favicon' => eb_default_vaficon,
	// ảnh đại diện mặc định khi chia sẻ trên fb
	'cf_og_image' => '',
	
	'cf_css_body' => '',
	'cf_css_theme_body' => '',
	
	'cf_ten_cty' => 'Thiết kế web giá rẻ',
//	'web_name' => '',
	
	'cf_email' => $arr_private_info_setting['author_email'],
	'cf_email_note' => '',
	
	'cf_phone_country_code' => '+84',
	'cf_dienthoai' => '',
	'cf_call_dienthoai' => '',
	'cf_hotline' => '',
	'cf_call_hotline' => '',
	'cf_structured_data_phone' => '',
	
	'cf_diachi' => '',
	'cf_p_diachi' => '',
	
	'cf_bank' => '',
	
	'cf_facebook_page' => '',
	'cf_facebook_page_id' => '',
	'cf_facebook_id' => '',
	'cf_facebook_admin_id' => '',
	'cf_instagram_page' => '',
	'cf_google_plus' => '',
	'cf_youtube_chanel' => '',
	'cf_twitter_page' => '',
	'cf_yahoo' => '',
	'cf_skype' => '',
	'cf_pinterest_page' => '',
	
	'cf_js_allpage' => '',
	'cf_js_allpage_full' => '',
	'cf_js_hoan_tat' => '',
	'cf_js_hoan_tat_full' => '',
	'cf_js_head' => '',
	'cf_js_head_full' => '',
	'cf_js_details' => '',
	'cf_js_details_full' => '',
	
	// mã ngoài cho phần admin
	'cf_js_admin' => '',
	
	// google font
	'cf_google_font' => '',
	
	// mã dành cho chơi GA
	'cf_js_gadsense' => '',
	'cf_js_gadsense_full' => '',
	
	// mã GA cho trang AMP
	'cf_gadsense_client_amp' => '',
	'cf_gadsense_slot_amp' => '',
	
	// màu cơ bản
	'cf_default_css' => '',
	'cf_default_themes_css' => '',
	
	'cf_default_body_bg' => '#ffffff',
	'cf_default_div_bg' => '#ffffff',
	'cf_default_color' => '#000000',
//	'cf_default_size' => 10,
	'cf_default_link_color' => '#1264aa',
	
	'cf_default_bg' => '#ff4400',
	'cf_default_2bg' => '#555555',
	'cf_default_bg_color' => '#ffffff',
	'cf_default_amp_bg' => '#0a89c0',
	
	
	'cf_product_size' => 1,
	'cf_product_mobile2_size' => '',
	'cf_product_mobile_size' => '140',
	'cf_product_table_size' => '200',
	'cf_product_details_size' => 1,
	'cf_product_details_mobile_size' => '',
	'cf_product_details_viewmore' => 0,
	'cf_slider_details_play' => 0,
	'cf_img_details_maxwidth' => 0,
	'cf_blog_size' => '2/3',
	'cf_top_banner_size' => 'auto', // 400/1366
	'cf_other_banner_size' => '2/3',
	
	'cf_cats_description_viewmore' => 200,
	
	'cf_num_home_hot' => 5,
	'cf_num_home_new' => 5,
//	'cf_num_home_view' => 0,
	'cf_num_home_list' => 5,
	'cf_num_limit_home_list' => 100,
//	'cf_num_thread_list' => 10,
	'cf_num_details_list' => 10,
	'cf_num_details2_list' => 0,
	'cf_num_details3_list' => 0,
	'cf_num_details_blog_list' => 10,
	// phần này để tạo 1 giá ảo trên trình duyệt cốc cốc, không cho cốc cốc biết được giá thật của sản phẩm
	'cf_coccoc_discount_price' => 50,
	
	// số tin trên mỗi trang của phần bog, mặc định là 0 -> dùng chung với sản phẩm
	'cf_blogs_per_page' => 0,
	'cf_blogs_content_bottom' => 'off',
	
	
	// kích thước mặc định của ảnh đại diện (post/ blog)
	// lấy ảnh đại diện kích thước medium ( chỉnh trong wp-admin/options-media.php )
	'cf_product_thumbnail_size' => 'medium',
//	'cf_product_thumbnail_size' => 'ebmobile',
//	'cf_product_thumbnail_size' => 'ebwebp',
	
	'cf_product_thumbnail_table_size' => 'medium',
//	'cf_product_thumbnail_table_size' => 'ebmobile',
//	'cf_product_thumbnail_table_size' => 'ebwebp',
	
	'cf_product_thumbnail_mobile_size' => 'ebmobile',
//	'cf_product_thumbnail_mobile_size' => 'ebwebp',
	
	
	// kích thước mặc định của ảnh đại diện (ads)
	'cf_ads_thumbnail_size' => 'full',
	'cf_ads_thumbnail_table_size' => 'medium_large',
	'cf_ads_thumbnail_mobile_size' => 'medium',
//	'cf_ads_thumbnail_mobile_size' => 'ebwebp',
	
	'cf_region' => '',
	'cf_placename' => 'Ha Noi',
	'cf_position' => '',
	'cf_content_language' => $default_all_site_lang,
	'cf_gg_api_key' => '',
	'cf_timezone' => $default_all_timezone,
	
	'cf_reset_cache' => eb_default_cache_time,
	'cf_dns_prefetch' => '',
	'cf_blog_public' => 1,
	'cf_tester_mode' => 'off',
	'cf_facebook_tracking' => 1,
	'cf_debug_mode' => 1,
//	'cf_js_optimize' => 1,
	'cf_js_optimize' => 'off',
	'cf_css_optimize' => 1,
	'cf_css_inline' => 1,
//	'cf_css_inline' => 'off',
	'cf_css2_inline' => 'off',
	'cf_fontawesome_v5' => 1,
	'cf_theme_dir' => '',
	
	// với 1 số website, chuyển từ URL cũ sang -> dùng chức năng này để đồng bộ nội dung từ cũ sang mới (chủ yếu là các link ảnh)
	'cf_old_domain' => '',
	'cf_replace_content' => '',
	'cf_replace_content_full' => '',
	'cf_replace_rss_content' => '',
	'cf_replace_rss_content_full' => '',
	'cf_google_sheet_backup' => '',
	
	// tạo trường tùy biến cho website dễ nhìn hơn trong phần quản trị bài viết
	'cf_truong_tuy_bien' => '',
	
	// cài đặt cho bản AMP
	'cf_blog_amp' => 1,
	'cf_blog_details_amp' => 1,
	'cf_product_amp' => 1,
	'cf_product_details_amp' => 0,
	'cf_product_buy_amp' => 0,
	
	
	// bật/ tắt chia sẻ dữ liệu qua JSON
	'cf_on_off_json' => 'off',
	'cf_on_off_xmlrpc' => 'off',
	
	// bật tắt cron -> chức năng tiêu tốn nhiều tài nguyên server
//	'cf_on_off_wpcron' => 'off',
	// các tính năng auto-update dùng cái này nên không tắt được
	'cf_on_off_wpcron' => 1,
	
	// bật tắt RSS
	'cf_on_off_feed' => 1,
    
    // lấy chi tiết danh mục khi xem danh sách sản phẩm
	'cf_category_in_list' => 0,
	
	// xóa URL cha của phân nhóm, custom taxonomy
	'cf_remove_category_base' => 'off',
	'cf_remove_post_option_base' => 'off',
	'cf_alow_post_option_index' => 'off',
	'cf_alow_post_tag_index' => 1,
	
	// plugin SEO của EchBay
	'cf_on_off_echbay_seo' => 1,
	'cf_excerpt_sync_content' => 1,
	'cf_excerpt_sync_yoast' => 1,
	'cf_lazy_load_home_footer' => 'off',
	
	// logo thiết kế bởi echbay
	'cf_on_off_echbay_logo' => 1,
	
	// on/ off AMP
	'cf_on_off_amp_logo' => 'off',
	'cf_on_off_amp_category' => 1,
	'cf_on_off_amp_product' => 'off',
	'cf_on_off_amp_blogs' => 1,
	'cf_on_off_amp_blog' => 1,
	
	// tự động cập nhật mã nguồn wordpress
	'cf_on_off_auto_update_wp' => 'off',
    
    // sử dụng ebsuppercache
	'cf_enable_ebsuppercache' => 1,
	
	// tự tìm và lấy link thumbnail bằng javascript
	'cf_disable_auto_get_thumb' => 'off',
	
	'cf_show_img_color' => 1,
	
	// nhúng URL vào các thẻ H1 ở trang chi tiết sản phẩm, bài viết, danh mục...
	'cf_set_link_for_h1' => 1,
	'cf_set_nofollow_for_h1' => 1,
	
	// Tạo mặt nạ bảo vệ copy nội dung theo cách thông thường
	'cf_set_mask_for_details' => 'off',
	
	// chuyển về 1 giá sau khi hết hạn khuyến mại
	'cf_update_price_if_hethan' => 'off',
	
	// vị trí của đơn vị tiền tệ
	'cf_current_price' => '',
	'cf_current_price_before' => 'off',
    // chuyển đổi đơn vị triệu, tỷ cho các mức giá quá lớn
	'cf_big_price_before' => 'off',
	// Đơn vị tiền tệ cho phần structured data
	'cf_current_sd_price' => 'VND',
	
	// ẩn các menu quan trọng trong admin
	'cf_hide_supper_admin_menu' => 1,
	
	// cho phép chính sửa theme, plugin
	'cf_alow_edit_theme_plugin' => 'off',
	'cf_alow_edit_plugin_theme' => 1,
	
	// đặt làm trang tin tức
	'cf_set_news_version' => 'off',
	'cf_set_raovat_version' => 'off',
	'cf_remove_raovat_meta' => 'off',
	
	// Với các website nâng cấp từ version 1 lên version, URL có thể sẽ khác nhau -> dùng chức năng này để đồng bộ
	'cf_echbay_migrate_version' => 'off',
	
	// tự động định dạng số điện thoại
	'cf_phone_detection' => 'off',
	
	// bật tắt chế độ tự động redirect sang url mới của wp
	'cf_redirecting_matched_slugs' => 1,
	
	// Hiển thị nút tìm kiếm nâng cao hoặc nhảy URL luôn
	'cf_search_advanced_auto_submit' => 1,
	
	// Sử dụng công cụ tìm kiếm của echbay
	'cf_search_by_echbay' => 'off',
	
	// Mặc định, banner lớn được load theo từng trang, nếu muốn load cho toàn bộ trang thì kích hoạt nó lên
	'cf_global_big_banner' => 'off',
	
	// Mặc định, big banner được load cả trong trang chi tiết
	'cf_post_big_banner' => 1,
	
	// mặc định thì sử dụng max-width thay cho width, trường hợp khách muốn tự custom size ảnh trong nội dung thì tắt chức năng này đi
//	'cf_post_rm_img_width' => 1,
	'cf_post_rm_img_width' => 'off',
	'cf_blog_rm_img_width' => 'off',
	'cf_blog_hide_excerpt' => 'off',
	
	// Tạo mục lục cho trang chi tiết -> nhập thẻ muốn lấy -> JS sẽ chạy vòng lặp cho thẻ đó
	'cf_post_index_content' => '',
	'cf_blog_index_content' => '',
	
	// nút chuyển ảnh trên slider
	'cf_btn_big_banner' => 1,
	'cf_arrow_big_banner' => 1,
	// bật tắt chế độ swipe trên mobile cho banner chính
	'cf_swipe_big_banner' => 'off',
	'cf_swipe_details_banner' => 'off',
	
	// tựu động lấy size cho phần quảng cáo theo ảnh đầu tiên trong chuỗi tìm được
	'cf_auto_get_ads_size' => 'off',
	'cf_slider_big_play' => 5000,
	
	//
	'cf_on_primary_slider' => 1,
	'cf_rand_slider' => 'rand',
	
	// đổi style cho sidebar trang danh mục sản phẩm
	'cf_list_sidebar_new_style' => 'off',
	'cf_list_avt_hover' => 'off',
	
	// đảo ngược nội dung và phần danh sách cho mục category
	'cf_column_reverse_cats' => 'off',
	
	// hệ thống comment qua fb
	'cf_show_fb_cmt_posts' => 1,
	'cf_show_fb_cmt_post' => 1,
	'cf_show_cmt_post' => 0,
	
	'cf_show_fb_cmt_blogs' => 1,
	'cf_show_fb_cmt_blog' => 1,
	'cf_show_cmt_blog' => 0,
	
	/*
	* Cấu hình slider cho trang chi tiết sản phẩm
	*/
	// tự động lấy ảnh trong content nếu slider bị trống
	'cf_details_content_slider' => 1,
	// Ẩn/ Hiện nút nhỏ nhỏ màu đỏ trên silder
	'cf_details_show_list_next' => 'off',
	'cf_details_show_list_thumb' => 1,
    
    // hiển thị form mua hàng nhanh
	'cf_details_show_quick_cart' => 1,
    // chuyển form mua hàng nhanh xuống dưới chân trang -> trên facebook
	'cf_details_bottom_quick_cart' => 'off',
    
	'cf_details_show_share_button' => 1,
	'cf_details_short_quick_cart' => 'off',
	'cf_donot_remove_quick_cart' => 'off',
	'cf_details_right_thumbnail' => 'off',
	'cf_details_left_thumbnail' => 'off',
	'cf_on_details_slider' => 1,
	'cf_add_to_cart_iframe' => 'off',
	
	// các chức năng bắt buộc ở giỏ hàng
	'cf_required_phone_cart' => 1,
	'cf_required_name_cart' => 'off',
	'cf_required_email_cart' => 'off',
	'cf_required_address_cart' => 'off',
	
	// giao diện nút mua top
	'cf_details_buttonmua' => 'mua',
	// giao diện nút mua ngay trên mobile
	'cf_details_mobilemua' => 'mobilemua1',
	
	// menu trên bản mobile
	'cf_search_nav_mobile' => 'nav_mobile1',
	
	// chế độ điều khiển post excerpt cho trang chi tiết sản phẩm
	'cf_details_excerpt' => 1,
	'cf_details_bold_excerpt' => 'off',
	'cf_options_excerpt' => 'off',
	'cf_details_ul_options' => 'off',
	
	// mặc định sẽ cắt nội dung trong phần content nếu excerpt để trống
	'cf_content_for_excerpt_null' => 130,
	
	//
	'cf_max_revision_cleanup' => 1000,
	'cf_max_post_cleanup' => 10000,
	
	// chức năng mở liên kết trong iframe để tăng tốc xem những trang đã được xem trước đó
	'cf_wgr_ia' => 0,
	
	
	/*
	* giao diện HTML mặc định
	*/
	// class bao viền (w99, w90)
	'cf_blog_class_style' => '',
	'cf_current_theme_using' => '',
	
	'cf_main_include_file' => '',
	
	'cf_using_top_default' => 1,
	'cf_top_class_style' => '',
	'cf_top1_include_file' => '',
	'cf_top2_include_file' => '',
	'cf_top3_include_file' => '',
	'cf_top4_include_file' => '',
	'cf_top5_include_file' => '',
	'cf_top6_include_file' => '',
	
	'cf_using_footer_default' => 1,
	'cf_footer_class_style' => '',
	'cf_footer1_include_file' => '',
	'cf_footer2_include_file' => '',
	'cf_footer3_include_file' => '',
	'cf_footer4_include_file' => '',
	'cf_footer5_include_file' => '',
	'cf_footer6_include_file' => '',
	
	// khung sản phẩm
	'cf_threadnode_include_file' => '',
	'cf_threadsearchnode_include_file' => '',
	// Thay đổi thẻ cho phần tiêu đề, tùy các SEOer muốn là thẻ gì thì chọn thẻ đấy, mặc định DIV
	'cf_threadnode_title_tag' => 'h3',
	
	// HTML trang chi tiết sản phẩm
	'cf_threaddetails_include_file' => '',
	
	// thẻ bao ngoài cho phần thẻ A của thread-home-c2
	'cf_home_sub_cat_tag' => '',
//	'cf_home_sub_cat_tag' => 'h2',
	
	
	// Chiều rộng chung của toàn website -> nếu chiều rộng này được set, cả website sẽ sử dụng nó
	'cf_global_width_sidebar' => 0,
	// -> nếu không, sẽ sử dụng chiều rộng riêng của từng trang
	'cf_home_width_sidebar' => 0,
	'cf_cats_width_sidebar' => 0,
	'cf_post_width_sidebar' => 0,
	'cf_blogs_width_sidebar' => 0,
	'cf_blog_width_sidebar' => 0,
	'cf_page_width_sidebar' => 0,
	
	// chức năng hiển thị đơn đã mua
	'cf_show_order_fomo' => 11,
	'cf_delay_order_fomo' => 6,
	'cf_time_order_fomo' => 30,
	
	
	// cấu trúc bảng tin ở trang chủ
	'cf_home_column_style' => '',
	'cf_using_home_default' => 1,
	'cf_home_class_style' => '',
	'cf_home1_include_file' => '',
	'cf_home2_include_file' => '',
	'cf_home3_include_file' => '',
	'cf_home4_include_file' => '',
	'cf_home5_include_file' => '',
	'cf_home6_include_file' => '',
	'cf_home7_include_file' => '',
	'cf_home8_include_file' => '',
	'cf_home9_include_file' => '',
	
	
	// danh sách tin -> tổng quan
	'cf_cats_class_style' => '',
	'cf_cats_column_style' => '',
	// danh sách tin -> html cho phần node
	'cf_cats_node_html' => '',
	// danh sách tin -> số tin trên mỗi dòng
	'cf_cats_num_line' => '',
	
	// file main cho category
	'cf_catsmain_include_file' => '',
	
	'cf_using_cats_default' => 1,
	'cf_cats1_include_file' => '',
	'cf_cats2_include_file' => '',
	'cf_cats3_include_file' => '',
	'cf_cats4_include_file' => '',
	'cf_cats5_include_file' => '',
	'cf_cats6_include_file' => '',
	
	// chi tiết -> tổng quan
	'cf_post_class_style' => '',
	'cf_post_column_style' => '',
	// chi tiết -> html cho phần node
	'cf_post_node_html' => '',
	
	
	// danh sách tin -> tổng quan
	'cf_blogs_class_style' => '',
	'cf_blogs_column_style' => '',
	// danh sách tin -> html cho phần node
	'cf_blogs_node_html' => '',
	// danh sách tin -> số tin trên mỗi dòng
	'cf_blogs_num_line' => '',
	
	// chi tiết -> tổng quan
	'cf_blogd_class_style' => '',
	'cf_blog_column_style' => '',
	// chi tiết -> html cho phần node
	'cf_blog_node_html' => '',
	'cf_blog_num_line' => '',
	
	
	// class bao viền (w99, w90)
	'cf_page_class_style' => '',
	// chi tiết -> tổng quan
	'cf_page_column_style' => '',
	
	
	
	// thanh toán trực tuyến
	'cf_baokim_email' => '',
	'cf_nganluong_email' => '',
	'cf_paypal_email' => '',
	
	
	
	// thư mục gốc của tài khoản FTP
	'cf_ftp_root_dir' => '',
	
	
	'cf_web_version' => 1.0,
	'cf_ngay' => date_time,
);

//
$__cf_row = $__cf_row_default;
//print_r($__cf_row_default);




