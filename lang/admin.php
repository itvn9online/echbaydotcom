<?php



function WGR_admin_lang ( $k ) {
	$a = array(
		//
		'_eb_category_hidden' => 'Ẩn nhóm này',
//		'_eb_category_old_url' => 'URL cũ (nếu có)',
//		'_eb_category_leech_url' => 'URL đồng bộ',
		'_eb_category_primary' => 'Đặt làm nhóm chính',
		'_eb_category_in_list' => 'Hiển thị trong danh mục',
		
		//
//		'_eb_category_title' => 'Title',
//		'_eb_category_keywords' => 'Keywords',
//		'_eb_category_description' => 'Description',
		'_eb_category_google_product' => 'Google product category',
//		'_eb_category_noindex' => 'Noindex',
		
		//
		'_eb_category_avt' => 'Ảnh đại diện lớn (banner)',
		'_eb_category_favicon' => 'Ảnh đại diện nhỏ (favicon)',
		'_eb_category_custom_css' => 'CSS riêng',
//		'_eb_category_gender' => 'Giới tính',
		'_eb_category_status' => 'Trạng thái/ Định dạng',
		'_eb_category_order' => 'Số thứ tự',
		'_eb_category_content' => 'Giới thiệu',
        
		'_eb_category_header_outsource' => 'Mã nhúng ngoài (HEAD)',
		'_eb_category_footer_outsource' => 'Mã nhúng ngoài (FOOTER)',
		
		
		//
		'_eb_ads_for_post' => 'ID Sản phẩm/ Blog/ Page',
		'_eb_ads_for_category' => 'Chuyên mục/ Danh mục',
		'_eb_ads_url' => 'Đường dẫn',
		'_eb_ads_target' => 'Mở trong tab mới',
		'_eb_ads_name' => 'Giữ nguyên tên',
		'_eb_ads_video_url' => 'URL Video',
		'_eb_ads_status' => 'Khu vực hiển thị',
//		'_eb_product_avatar' => 'Ảnh đại diện',
		
		
		//
		'_eb_product_title' => 'Title',
		'_eb_product_keywords' => 'Keywords',
		'_eb_product_description' => 'Description',
		'_eb_product_noindex' => 'Noindex',
		
		//
		'_eb_product_avatar' => 'Ảnh đại diện',
		'_eb_product_css' => 'Tùy chỉnh CSS',
		'_eb_product_link_stylesheet' => 'Tùy chỉnh stylesheet',
		'_eb_product_link_javascript' => 'Tùy chỉnh javascript',
//		'_eb_product_leech_source' => 'URL đồng bộ',
		
		//
		'_eb_product_supper_shop' => 'Liên kết sàn TMĐT',
		'_eb_product_old_url' => 'URL cũ (nếu có)',
		'_eb_product_leech_source' => 'URL đồng bộ',
		'_eb_product_video_url' => 'URL Youtube video',
		'_eb_product_source_author' => 'Tác giả bài viết',
		
		'_eb_blog_2content' => 'Nội dung phụ',
//		'_eb_product_noibat' => 'Điểm nổi bật',
//		'_eb_product_dieukien' => 'Điều kiện',
		'_eb_product_list_color' => 'Danh sách màu sắc',
		'_eb_product_gallery' => 'Thư viện ảnh',
		
		//
		'_eb_product_status' => 'Trạng thái',
		'_eb_product_gender' => 'Giới tính',
		'_eb_product_color' => 'Màu sắc',
		'_eb_product_size' => 'Kích thước',
		'_eb_product_searchkey' => 'EB Search',
//		'_eb_product_sku' => 'Mã sản phẩm',
		'_eb_product_sku' => 'SKU',
		'_eb_product_leech_sku' => 'SKU (leech data)',
		
		'_eb_product_oldprice' => EBE_get_lang('post_giacu') . ' (nếu có)',
		'_eb_product_price' => EBE_get_lang('post_giamoi'),
		'_eb_product_baseprice' => 'Giá sau khuyến mại (nếu có)',
		
		'_eb_product_buyer' => EBE_get_lang('post_luotmua'),
		'_eb_product_quantity' => EBE_get_lang('post_soluong'),
		'_eb_product_ngayhethan' => 'Ngày hết hạn',
		'_eb_product_giohethan' => 'Giờ hết hạn',
		'_eb_product_chinhhang' => 'Đảm bảo chính hãng',
		
		'_eb_product_rating_value' => 'Điểm đánh giá',
		'_eb_product_rating_count' => 'Tổng số đánh giá'
	);
	
	//
	return $a[ $k ];
}




