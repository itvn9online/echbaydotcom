<?php


//
$___eb_lang = array();

// định dạng kiểu dữ liệu
$eb_type_lang = array();

// ghi chú
$eb_note_lang = array();
$eb_note_first_lang = array();

// URL file gốc từ github (nếu có)
$eb_ex_from_github = array();

// class CSS riêng (nếu có)
$eb_class_css_lang = array();

//
define( 'eb_key_for_site_lang', 'lang_eb_' );


//
$___eb_lang[eb_key_for_site_lang . 'home'] = 'Trang chủ';
$___eb_lang[eb_key_for_site_lang . 'widget_products_more'] = 'Xem thêm <span>&raquo;</span>';

//
$eb_note_first_lang[eb_key_for_site_lang . 'search'] = 'Tìm kiếm';
$___eb_lang[eb_key_for_site_lang . 'search'] = 'Tìm kiếm';
// placeholder for search
$___eb_lang[eb_key_for_site_lang . 'searchp'] = $___eb_lang[eb_key_for_site_lang . 'search'] . ' sản phẩm';
$___eb_lang[eb_key_for_site_lang . 'search_not_found'] = 'Dữ liệu bạn đang tìm kiếm không tồn tại hoặc đã bị xóa!';
// nếu phần mã này xuất hiện -> hiển thị mã này thay vì search_not_found ở trên -> ví dụ mã của trang tìm kiếm tùy chỉnh của google, mã tìm kiếm tự viết cho các site khác
$___eb_lang[eb_key_for_site_lang . 'search_addon'] = '';
$eb_type_lang[eb_key_for_site_lang . 'search_addon'] = 'textarea';
//$___eb_lang[eb_key_for_site_lang . 'search_title_addon'] = '';

//
$eb_note_first_lang[eb_key_for_site_lang . 'cart'] = 'Bản dịch cho trang Giỏ hàng';
$___eb_lang[eb_key_for_site_lang . 'cart'] = 'Giỏ hàng';
$___eb_lang[eb_key_for_site_lang . 'shopping_cart'] = $___eb_lang[eb_key_for_site_lang . 'cart'];
$___eb_lang[eb_key_for_site_lang . 'lienhe'] = 'Liên hệ';
$___eb_lang[eb_key_for_site_lang . 'muangay'] = 'Mua ngay';
$___eb_lang[eb_key_for_site_lang . 'add_to_cart'] = 'Cho vào giỏ hàng';
$___eb_lang[eb_key_for_site_lang . 'details_tu_van'] = '<span class="bold medium18">Tư vấn miễn phí</span> <i class="fas fa-phone-alt"></i> {tmp.cf_hotline}';
$___eb_lang[eb_key_for_site_lang . 'details2_tu_van'] = 'Tư vấn miễn phí';
$___eb_lang[eb_key_for_site_lang . 'details_share'] = 'Chia sẻ';

$___eb_lang[eb_key_for_site_lang . 'cart_str_list'] = 'Danh sách Sản phẩm';
$___eb_lang[eb_key_for_site_lang . 'cart_price'] = 'Giá';
//$___eb_lang[eb_key_for_site_lang . 'cart_soluong'] = 'Số lượng';
$___eb_lang[eb_key_for_site_lang . 'cart_str_total'] = 'Cộng';
$___eb_lang[eb_key_for_site_lang . 'cart_str_totals'] = 'Tổng cộng';
$___eb_lang[eb_key_for_site_lang . 'cart_is_null'] = 'Không có sản phẩm nào trong giỏ hàng của bạn';
$___eb_lang[eb_key_for_site_lang . 'cart_continue'] = '&laquo; Tiếp tục mua hàng';
$___eb_lang[eb_key_for_site_lang . 'cart_customer_info'] = 'Thông tin khách hàng';
$___eb_lang[eb_key_for_site_lang . 'cart_payment_method'] = 'Hình thức thanh toán';
$___eb_lang[eb_key_for_site_lang . 'cart_payment_cod'] = 'Thanh toán khi nhận hàng (COD)';
$___eb_lang[eb_key_for_site_lang . 'cart_payment_tt'] = 'Thanh toán trực tiếp tại cửa hàng';
$___eb_lang[eb_key_for_site_lang . 'cart_payment_bank'] = 'Thanh toán trực tuyến qua ngân hàng';
$___eb_lang[eb_key_for_site_lang . 'cart_payment_bk'] = 'Thanh toán trực tuyến qua Bảo Kim';
$___eb_lang[eb_key_for_site_lang . 'cart_payment_nl'] = 'Thanh toán trực tuyến qua Ngân Lượng';
$___eb_lang[eb_key_for_site_lang . 'cart_payment_pp'] = 'Thanh toán trực tuyến qua Paypal';

$___eb_lang[eb_key_for_site_lang . 'cart_done_madon'] = 'Số đơn hàng';
$___eb_lang[eb_key_for_site_lang . 'cart_done_khachhang'] = 'Khách hàng';
$___eb_lang[eb_key_for_site_lang . 'cart_done_dienthoai'] = 'Số điện thoại';
$___eb_lang[eb_key_for_site_lang . 'cart_done_diachi'] = 'Địa chỉ nhận hàng';
$___eb_lang[eb_key_for_site_lang . 'cart_done_ghichu'] = 'Ghi chú của khách hàng';
$___eb_lang[eb_key_for_site_lang . 'cart_done_trangthai'] = 'Trạng thái thanh toán: <strong>Chưa thanh toán</strong>';
$___eb_lang[eb_key_for_site_lang . 'cart_done_size'] = 'Kích thước';
//$___eb_lang[eb_key_for_site_lang . 'cart_done_quan'] = $___eb_lang[eb_key_for_site_lang . 'post_soluong'];
$___eb_lang[eb_key_for_site_lang . 'cart_done_list'] = 'Sản phẩm đặt mua';
$___eb_lang[eb_key_for_site_lang . 'cart_done_tong'] = 'Tổng giá trị đơn hàng';

//
$___eb_lang[eb_key_for_site_lang . 'cart_confirm_remove'] = 'Xác nhận xóa sản phẩm khỏi giỏ hàng!';
$___eb_lang[eb_key_for_site_lang . 'cart_post_null'] = 'Không xác định được sản phẩm';

//
$eb_note_first_lang[eb_key_for_site_lang . 'taikhoan'] = 'Bản dịch cho trang Tài khoản';
$___eb_lang[eb_key_for_site_lang . 'taikhoan'] = 'Tài khoản';
$___eb_lang[eb_key_for_site_lang . 'thoat'] = 'Thoát';
$___eb_lang[eb_key_for_site_lang . 'xacnhan_thoat'] = 'Xác nhận đăng xuất khỏi hệ thống';
$___eb_lang[eb_key_for_site_lang . 'dangnhap'] = 'Đăng nhập';
$___eb_lang[eb_key_for_site_lang . 'dangky'] = 'Đăng ký';

//
$___eb_lang[eb_key_for_site_lang . 'home_hot'] = '<i class="fa fa-dollar"></i> Sản phẩm HOT';
$___eb_lang[eb_key_for_site_lang . 'home_new'] = '<i class="fa fa-star"></i> Sản phẩm MỚI';

//
$eb_note_first_lang[eb_key_for_site_lang . 'order_by'] = 'Phần sắp xếp trong trang danh sách sản phẩm';
$___eb_lang[eb_key_for_site_lang . 'order_by'] = 'Sắp xếp theo';
$___eb_lang[eb_key_for_site_lang . 'order_view'] = 'Xem nhiều';
$___eb_lang[eb_key_for_site_lang . 'order_price_down'] = 'Giá giảm dần';
$___eb_lang[eb_key_for_site_lang . 'order_price_up'] = 'Giá tăng dần';
$___eb_lang[eb_key_for_site_lang . 'order_az'] = 'Tên sản phẩm ( từ A đến Z )';
$___eb_lang[eb_key_for_site_lang . 'order_za'] = 'Tên sản phẩm ( từ Z đến A )';

//
$eb_note_first_lang[eb_key_for_site_lang . 'post_giacu'] = 'Bản dịch cứng cho trang chi tiết sản phẩm';
$___eb_lang[eb_key_for_site_lang . 'post_giacu'] = 'Giá cũ';
$___eb_lang[eb_key_for_site_lang . 'post_giamgia'] = 'Giảm<br>giá';
$___eb_lang[eb_key_for_site_lang . 'post_giamoi'] = 'Giá bán';
$___eb_lang[eb_key_for_site_lang . 'post_zero'] = '<em>Liên hệ</em>';
$___eb_lang[eb_key_for_site_lang . 'post_luotmua'] = 'Lượt mua';
$___eb_lang[eb_key_for_site_lang . 'post_soluong'] = 'Số lượng';
$___eb_lang[eb_key_for_site_lang . 'post_time_discount'] = 'Thời gian khuyến mại còn lại:';
$___eb_lang[eb_key_for_site_lang . 'post_time_soldout'] = 'Sản phẩm tạm thời ngừng bán';
$___eb_lang[eb_key_for_site_lang . 'post_comment'] = 'Bình luận';
$___eb_lang[eb_key_for_site_lang . 'post_content'] = 'Thông tin sản phẩm';
$___eb_lang[eb_key_for_site_lang . 'post_other'] = 'Sản phẩm khác';
$___eb_lang[eb_key_for_site_lang . 'post_css_other'] = 'title-center title-line title-line50 title-bold';
$___eb_lang[eb_key_for_site_lang . 'post_column_other'] = 'thread-list33-example';
$___eb_lang[eb_key_for_site_lang . 'post_sku'] = 'Mã sản phẩm';
$___eb_lang[eb_key_for_site_lang . 'post_stock'] = 'Tình trạng';
$___eb_lang[eb_key_for_site_lang . 'post_instock'] = 'Sẵn hàng';
$___eb_lang[eb_key_for_site_lang . 'post_outstock'] = 'Hết hàng';


//
$eb_note_first_lang[eb_key_for_site_lang . 'post_size_color'] = 'Nút Mua ngay và phần Size, Color trong trang chi tiết';
$___eb_lang[eb_key_for_site_lang . 'post_size_color'] = 'post_size_color';
$eb_type_lang[eb_key_for_site_lang . 'post_size_color'] = 'textarea';
$eb_ex_from_github[eb_key_for_site_lang . 'post_size_color'] = 'https://github.com/itvn9online/echbaydotcom/blob/master/html/post_size_color.html';

$___eb_lang[eb_key_for_site_lang . 'post_buy_bottom'] = 'post_buy_bottom';
$eb_type_lang[eb_key_for_site_lang . 'post_buy_bottom'] = 'textarea';
$eb_ex_from_github[eb_key_for_site_lang . 'post_buy_bottom'] = 'https://github.com/itvn9online/echbaydotcom/blob/master/html/post_buy_bottom.html';

//
$eb_note_first_lang[eb_key_for_site_lang . 'share_to_social'] = 'Mã html để tạo vòng lặp các nút chia sẻ trên mạng xã hội';
$___eb_lang[eb_key_for_site_lang . 'share_to_social'] = 'share_to_social';
$eb_type_lang[eb_key_for_site_lang . 'share_to_social'] = 'textarea';
$eb_ex_from_github[eb_key_for_site_lang . 'share_to_social'] = 'https://github.com/itvn9online/echbaydotcom/blob/master/html/share_to_social.html';


//
$___eb_lang[eb_key_for_site_lang . 'thread_list_mua'] = '<i class="fa fa-shopping-cart"></i> Mua ngay';
$___eb_lang[eb_key_for_site_lang . 'thread_list_more'] = '<i class="fa fa-eye"></i> Xem';

//
$___eb_lang[eb_key_for_site_lang . 'cart_diachi'] = 'Địa chỉ';
$___eb_lang[eb_key_for_site_lang . 'cart_tinhthanh'] = 'Quận/ Huyện';
$___eb_lang[eb_key_for_site_lang . 'cart_tinhthanh2'] = 'Vui lòng nhập Quận/ Huyện cho việc giao hàng';
$___eb_lang[eb_key_for_site_lang . 'cart_dienthoai'] = 'Điện thoại';
$___eb_lang[eb_key_for_site_lang . 'cart_pla_dienthoai'] = $___eb_lang[eb_key_for_site_lang . 'cart_dienthoai'];
$___eb_lang[eb_key_for_site_lang . 'cart_hotline'] = 'Hotline';
$___eb_lang[eb_key_for_site_lang . 'cart_discount_code'] = 'Mã giảm giá';
$___eb_lang[eb_key_for_site_lang . 'cart_shipping_fee'] = 'Phí vận chuyển';
$___eb_lang[eb_key_for_site_lang . 'cart_shipping_content'] = '';

$___eb_lang[eb_key_for_site_lang . 'nav_mobile_dienthoai'] = $___eb_lang[eb_key_for_site_lang . 'cart_dienthoai'];
$___eb_lang[eb_key_for_site_lang . 'nav_mobile_hotline'] = $___eb_lang[eb_key_for_site_lang . 'cart_hotline'];


// các loại sản phẩm
$___eb_lang[eb_key_for_site_lang . 'products_hot'] = 'Sản phẩm HOT';
$___eb_lang[eb_key_for_site_lang . 'products_new'] = 'Sản phẩm MỚI';
$___eb_lang[eb_key_for_site_lang . 'products_selling'] = 'Sản phẩm BÁN CHẠY';
$___eb_lang[eb_key_for_site_lang . 'products_sales_off'] = 'Sản phẩm GIẢM GIÁ';
$___eb_lang[eb_key_for_site_lang . 'products_all'] = 'Sản phẩm';

// giờ vàng
$___eb_lang[eb_key_for_site_lang . 'golden_time'] = 'Giờ vàng';
//$___eb_lang[eb_key_for_site_lang . 'golden_desc_time'] = 'Giờ vàng GIÁ SỐC, khuyến mại tận GỐC';
//$___eb_lang[eb_key_for_site_lang . 'limit_golden_time'] = 50;
//$eb_type_lang[eb_key_for_site_lang . 'limit_golden_time'] = 'number';

//
$___eb_lang[eb_key_for_site_lang . 'favorite'] = 'Sản phẩm yêu thích';
//$___eb_lang[eb_key_for_site_lang . 'limit_favorite'] = 50;
//$eb_type_lang[eb_key_for_site_lang . 'limit_favorite'] = 'number';

//
$___eb_lang[eb_key_for_site_lang . 'limit_products_list'] = 36;
$eb_type_lang[eb_key_for_site_lang . 'limit_products_list'] = 'number';
$___eb_lang[eb_key_for_site_lang . 'css_products_list'] = 'title-center title-line title-line50 title-upper';


// default status
$eb_note_first_lang[eb_key_for_site_lang . 'ads_status1'] = 'Phân loại trạng thái Quảng cáo';
$___eb_lang[eb_key_for_site_lang . 'ads_status1'] = 'Banner chính ( 1366 x Auto )';
$___eb_lang[eb_key_for_site_lang . 'ads_status2'] = 'Chờ sử dụng';
$___eb_lang[eb_key_for_site_lang . 'ads_status3'] = $___eb_lang[eb_key_for_site_lang . 'ads_status2'];
$___eb_lang[eb_key_for_site_lang . 'ads_status4'] = 'Review của khách hàng';
$___eb_lang[eb_key_for_site_lang . 'ads_status5'] = 'Banner/ Logo đối tác ( chân trang )';
$___eb_lang[eb_key_for_site_lang . 'ads_status6'] = 'Video HOT (trang chủ)';
$___eb_lang[eb_key_for_site_lang . 'ads_status7'] = 'Bộ sưu tập/ Banner nổi bật (trang chủ)';
$___eb_lang[eb_key_for_site_lang . 'ads_status8'] = 'Địa chỉ/ Bản đồ (chân trang/ liên hệ)';
$___eb_lang[eb_key_for_site_lang . 'ads_status9'] = 'Banner chuyên mục ở trang chủ';
$___eb_lang[eb_key_for_site_lang . 'ads_status10'] = 'Slide ảnh theo phân nhóm (trang chi tiết)';
$___eb_lang[eb_key_for_site_lang . 'ads_status11'] = 'Noname';
$___eb_lang[eb_key_for_site_lang . 'ads_status12'] = $___eb_lang[eb_key_for_site_lang . 'ads_status11'];
$___eb_lang[eb_key_for_site_lang . 'ads_status13'] = $___eb_lang[eb_key_for_site_lang . 'ads_status11'];
$___eb_lang[eb_key_for_site_lang . 'ads_status14'] = $___eb_lang[eb_key_for_site_lang . 'ads_status11'];
$___eb_lang[eb_key_for_site_lang . 'ads_status15'] = $___eb_lang[eb_key_for_site_lang . 'ads_status11'];

//
$eb_note_first_lang[eb_key_for_site_lang . 'product_status0'] = 'Phân loại Sản phẩm';
$___eb_lang[eb_key_for_site_lang . 'product_status0'] = 'Mặc định';
$___eb_lang[eb_key_for_site_lang . 'product_status1'] = 'HOT';
$___eb_lang[eb_key_for_site_lang . 'product_status2'] = 'NEW';
$___eb_lang[eb_key_for_site_lang . 'product_status3'] = 'Best sales';
$___eb_lang[eb_key_for_site_lang . 'product_status4'] = 'Sale';
$___eb_lang[eb_key_for_site_lang . 'product_status5'] = 'Sản phẩm KHÁC';
$___eb_lang[eb_key_for_site_lang . 'product_status6'] = $___eb_lang[eb_key_for_site_lang . 'golden_time'];
$___eb_lang[eb_key_for_site_lang . 'product_status7'] = 'Hết hàng';
$___eb_lang[eb_key_for_site_lang . 'product_status8'] = $___eb_lang[eb_key_for_site_lang . 'ads_status11'];
$___eb_lang[eb_key_for_site_lang . 'product_status9'] = $___eb_lang[eb_key_for_site_lang . 'ads_status11'];
$___eb_lang[eb_key_for_site_lang . 'product_status10'] = $___eb_lang[eb_key_for_site_lang . 'ads_status11'];

//
$eb_note_first_lang[eb_key_for_site_lang . 'product_male_gender'] = 'Phân loại Sản phẩm theo giới tính (dùng để tạo bộ lọc cho Google product)';
$___eb_lang[eb_key_for_site_lang . 'product_male_gender'] = 'Nam';
$___eb_lang[eb_key_for_site_lang . 'product_female_gender'] = 'Nữ';
$___eb_lang[eb_key_for_site_lang . 'product_unisex_gender'] = 'Không phân loại';


// details
$___eb_lang[eb_key_for_site_lang . 'chitietsp'] = 'Chi tiết Sản phẩm';
$___eb_lang[eb_key_for_site_lang . 'tuongtu'] = 'Sản phẩm tương tự';

// footer
$eb_note_first_lang[eb_key_for_site_lang . 'copyright'] = 'Tùy chỉnh phần bản quyền dưới chân trang';
$___eb_lang[eb_key_for_site_lang . 'copyright'] = 'Bản quyền';
$___eb_lang[eb_key_for_site_lang . 'allrights'] = ' - Toàn bộ phiên bản.';
$___eb_lang[eb_key_for_site_lang . 'joinus'] = 'Kết nối với chúng tôi';
$___eb_lang[eb_key_for_site_lang . 'diachi'] = $___eb_lang[eb_key_for_site_lang . 'cart_diachi'] . ':';
$___eb_lang[eb_key_for_site_lang . 'dienthoai'] = $___eb_lang[eb_key_for_site_lang . 'cart_dienthoai'] . ':';
$___eb_lang[eb_key_for_site_lang . 'poweredby'] = 'Cung cấp bởi';

// echbay two footer sologan
$eb_note_first_lang[eb_key_for_site_lang . 'ebslogan1'] = 'Mẫu slogan mặc định';
$___eb_lang[eb_key_for_site_lang . 'ebslogan1'] = '<i class="fas fa-sync-alt"></i> Đổi hàng<br />trong 7 ngày';
$___eb_lang[eb_key_for_site_lang . 'ebslogan2'] = '<i class="fa fa-truck"></i> Giao hàng Miễn phí<br />Toàn Quốc';
$___eb_lang[eb_key_for_site_lang . 'ebslogan3'] = '<i class="fas fa-hand-holding-usd"></i> Thanh toán<br />khi nhận hàng';
$___eb_lang[eb_key_for_site_lang . 'ebslogan4'] = '<i class="fa fa-check-square"></i> Bảo hành VIP<br />12 tháng';

// quick cart
$eb_note_first_lang[eb_key_for_site_lang . 'cart_muangay'] = 'Mua hàng nhanh';
$___eb_lang[eb_key_for_site_lang . 'cart_muangay'] = $___eb_lang[eb_key_for_site_lang . 'muangay'];
$___eb_lang[eb_key_for_site_lang . 'cart_mausac'] = 'Màu sắc';
$___eb_lang[eb_key_for_site_lang . 'cart_kichco'] = 'Kích cỡ';
$___eb_lang[eb_key_for_site_lang . 'cart_soluong'] = $___eb_lang[eb_key_for_site_lang . 'post_soluong'];
$___eb_lang[eb_key_for_site_lang . 'cart_thanhtien'] = 'Thành tiền';
$___eb_lang[eb_key_for_site_lang . 'cart_hoten'] = 'Họ và tên';
$___eb_lang[eb_key_for_site_lang . 'cart_diachi2'] = 'Địa chỉ nhận hàng. Vui lòng nhập chính xác! trong trường hợp vận chuyển.';
$___eb_lang[eb_key_for_site_lang . 'cart_ghichu'] = 'Ghi chú';
$___eb_lang[eb_key_for_site_lang . 'cart_vidu'] = 'Ví dụ: Giao hàng trong giờ hành chính, gọi điện trước khi giao...';
$___eb_lang[eb_key_for_site_lang . 'cart_gui'] = '<i class="fa fa-shopping-cart"></i> Gửi đơn hàng';
$___eb_lang[eb_key_for_site_lang . 'cart_them'] = $___eb_lang[eb_key_for_site_lang . 'add_to_cart'];
$___eb_lang[eb_key_for_site_lang . 'cart_emailformat'] = 'Email không đúng định dạng';
$___eb_lang[eb_key_for_site_lang . 'billing_custom_style'] = '/* Thêm custom CSS cho trang in đơn hàng */';

// contact
$eb_note_first_lang[eb_key_for_site_lang . 'lh_lienhe'] = 'Liên hệ';
$___eb_lang[eb_key_for_site_lang . 'lh_lienhe'] = 'Liên hệ với chúng tôi';
$___eb_lang[eb_key_for_site_lang . 'lh_luuy'] = 'Để liên hệ với chúng tôi, bạn có thể gửi email tới <a href="mailto:{tmp.cf_email}" rel="nofollow">{tmp.cf_email}</a>, sử dụng phom liên hệ phía dưới hoặc liên hệ trực tiếp theo địa chỉ và số điện thoại chúng tôi cung cấp.';
$___eb_lang[eb_key_for_site_lang . 'lh_hoten'] = 'Họ và tên <span class="redcolor">*</span>';
$___eb_lang[eb_key_for_site_lang . 'lh_email'] = 'Email <span class="redcolor">*</span>';
$___eb_lang[eb_key_for_site_lang . 'lh_diachi'] = 'Địa chỉ';
$___eb_lang[eb_key_for_site_lang . 'lh_dienthoai'] = 'Điện thoại';
$___eb_lang[eb_key_for_site_lang . 'lh_noidung'] = 'Nội dung <span class="redcolor">*</span>';
$___eb_lang[eb_key_for_site_lang . 'lh_submit'] = 'Gửi liên hệ';
$___eb_lang[eb_key_for_site_lang . 'lh_note'] = 'là các trường bắt buộc phải điền.<br>Vui lòng cung đầy đủ thông tin để quá trình trao đổi được diễn ra thuận lợi hơn.';
$___eb_lang[eb_key_for_site_lang . 'lh_done'] = 'Cảm ơn bạn! thông tin của bạn đã được gửi đi, chúng tôi sẽ phản hồi sớm nhất có thể.';


// register
$eb_note_first_lang[eb_key_for_site_lang . 'reg_no_email'] = 'Đăng ký tài khoản';
$___eb_lang[eb_key_for_site_lang . 'reg_no_email'] = 'Dữ liệu đầu vào không chính xác';
$___eb_lang[eb_key_for_site_lang . 'reg_pass_short'] = 'Mật khẩu tối thiểu phải có 6 ký tự';
$___eb_lang[eb_key_for_site_lang . 'reg_pass_too'] = 'Mật khẩu xác nhận không chính xác';
$___eb_lang[eb_key_for_site_lang . 'reg_email_format'] = 'Email không đúng định dạng';
$___eb_lang[eb_key_for_site_lang . 'reg_thanks'] = 'Cảm ơn bạn đã đăng ký nhận tin!';
$___eb_lang[eb_key_for_site_lang . 'reg_email_exist'] = 'Email đã được sử dụng';
$___eb_lang[eb_key_for_site_lang . 'reg_done'] = 'Đăng ký nhận bản tin thành công';
$___eb_lang[eb_key_for_site_lang . 'reg_error'] = 'Lỗi chưa xác định!';

// quick register
$eb_note_first_lang[eb_key_for_site_lang . 'qreg_name'] = 'Đăng ký tài khoản (nhanh)';
$___eb_lang[eb_key_for_site_lang . 'qreg_name'] = 'Họ tên';
$___eb_lang[eb_key_for_site_lang . 'qreg_phone'] = 'Điện thoại';
$___eb_lang[eb_key_for_site_lang . 'qreg_email'] = 'Email';
$___eb_lang[eb_key_for_site_lang . 'qreg_submit'] = 'Gửi';

// profile
$eb_note_first_lang[eb_key_for_site_lang . 'pr_tonquan'] = 'Trang tài khoản';
$___eb_lang[eb_key_for_site_lang . 'pr_tonquan'] = 'Tổng quan về tài khoản';
$___eb_lang[eb_key_for_site_lang . 'pr_email'] = 'E-mail';
$___eb_lang[eb_key_for_site_lang . 'pr_id'] = 'Mã tài khoản';
$___eb_lang[eb_key_for_site_lang . 'pr_matkhau'] = 'Mật khẩu';
$___eb_lang[eb_key_for_site_lang . 'pr_doimatkhau'] = 'Thay đổi mật khẩu';
$___eb_lang[eb_key_for_site_lang . 'pr_hoten'] = 'Họ và tên';
$___eb_lang[eb_key_for_site_lang . 'pr_dienthoai'] = 'Điện thoại';
$___eb_lang[eb_key_for_site_lang . 'pr_diachi'] = 'Địa chỉ';
$___eb_lang[eb_key_for_site_lang . 'pr_ngaydangky'] = 'Ngày đăng ký';
$___eb_lang[eb_key_for_site_lang . 'pr_capnhat'] = 'Cập nhật';
$___eb_lang[eb_key_for_site_lang . 'pr_no_id'] = 'Không xác định được ID tài khoản';
$___eb_lang[eb_key_for_site_lang . 'pr_done'] = 'Cập nhật thông tin tài khoản thành công!';

$___eb_lang[eb_key_for_site_lang . 'pr_short_matkhau'] = 'Mật khẩu tối thiểu phải có 6 ký tự!';


// AMP
$eb_note_first_lang[eb_key_for_site_lang . 'amp_full_version'] = 'Bản AMP';
$___eb_lang[eb_key_for_site_lang . 'amp_full_version'] = 'Xem phiên bản đầy đủ';
$___eb_lang[eb_key_for_site_lang . 'amp_to_top'] = 'Về đầu trang';
$___eb_lang[eb_key_for_site_lang . 'amp_development'] = 'Nhà phát triển';
$___eb_lang[eb_key_for_site_lang . 'amp_copyright'] = 'Bản quyền';
$___eb_lang[eb_key_for_site_lang . 'amp_all_rights'] = 'Toàn bộ phiên bản';
$___eb_lang[eb_key_for_site_lang . 'amp_buy_now'] = '{tmp.web_link}cart?id={tmp.id}';
$eb_note_lang[eb_key_for_site_lang . 'amp_buy_now'] = 'Nhập đầy đủ cấu trúc URL dẫn tới giỏ hàng, nhập <strong>null</strong> để tắt tính năng này.';

// footer address
$eb_note_first_lang[eb_key_for_site_lang . 'fd_diachi'] = 'Thông tin liên hệ ở chân trang';
$___eb_lang[eb_key_for_site_lang . 'fd_diachi'] = '<strong>Địa chỉ:</strong> <i class="fa fa-map-marker"></i>';
$___eb_lang[eb_key_for_site_lang . 'fd_hotline'] = '<strong>Hotline:</strong> <i class="fas fa-phone-alt"></i>';
$___eb_lang[eb_key_for_site_lang . 'fd_dienthoai'] = '<strong>Điện thoại:</strong>';
$___eb_lang[eb_key_for_site_lang . 'fd_email'] = '<strong>Email:</strong> <i class="fas fa-envelope"></i>';

// footer address -> cho phép mọi người có thể tùy chỉnh HTML của khung địa chỉ
//$___eb_lang[eb_key_for_site_lang . 'footer_address'] = file_get_contents( EB_THEME_PLUGIN_INDEX . 'html/footer_address.html' );
$___eb_lang[eb_key_for_site_lang . 'footer_address'] = 'footer_address';
$eb_type_lang[eb_key_for_site_lang . 'footer_address'] = 'textarea';
$eb_ex_from_github[eb_key_for_site_lang . 'footer_address'] = 'https://github.com/itvn9online/echbaydotcom/blob/master/html/footer_address.html';




/*
* Đối với các phần textarea -> mặc định sẽ là 1 tham số, nếu đúng tham số này -> sẽ dùng file html
*/
// HTML cho giỏ hàng
//$___eb_lang[eb_key_for_site_lang . 'cart_html'] = file_get_contents( EB_THEME_PLUGIN_INDEX . 'html/cart.html' );
$___eb_lang[eb_key_for_site_lang . 'cart_html'] = 'cart';
$eb_type_lang[eb_key_for_site_lang . 'cart_html'] = 'textarea';
$eb_ex_from_github[eb_key_for_site_lang . 'cart_html'] = 'https://github.com/itvn9online/echbaydotcom/blob/master/html/cart.html';

$___eb_lang[eb_key_for_site_lang . 'cart_node'] = 'cart_node';
$eb_type_lang[eb_key_for_site_lang . 'cart_node'] = 'textarea';
$eb_ex_from_github[eb_key_for_site_lang . 'cart_node'] = 'https://github.com/itvn9online/echbaydotcom/blob/master/html/cart_node.html';

// booking done
//$___eb_lang[eb_key_for_site_lang . 'booking_done'] = file_get_contents( EB_THEME_PLUGIN_INDEX . 'html/hoan-tat.html' );
$___eb_lang[eb_key_for_site_lang . 'booking_done'] = 'booking_done';
$eb_type_lang[eb_key_for_site_lang . 'booking_done'] = 'textarea';
$eb_ex_from_github[eb_key_for_site_lang . 'booking_done'] = 'https://github.com/itvn9online/echbaydotcom/blob/master/html/hoan-tat.html';

$___eb_lang[eb_key_for_site_lang . 'hoantat_banking'] = 'Bill {tmp.hd_mahoadon}';
$___eb_lang[eb_key_for_site_lang . 'hoantat_time'] = 'Sẽ mất khoảng 1-2 ngày làm việc để chúng tôi kiểm tra và đối soát đơn hàng của bạn, và giao hàng cho bạn trong vòng 1-2 ngày đối với đơn giao nội thành hoặc 3-6 ngày đối với đơn hàng giao liên tỉnh. Không kể chủ nhật hoặc các ngày nghỉ lễ tết khác.';

// nội dung email đơn hàng
//$___eb_lang[eb_key_for_site_lang . 'booking_mail'] = file_get_contents( EB_THEME_PLUGIN_INDEX . 'html/mail/booking.html' );
$___eb_lang[eb_key_for_site_lang . 'booking_mail'] = 'booking_mail';
$eb_type_lang[eb_key_for_site_lang . 'booking_mail'] = 'textarea';
$eb_ex_from_github[eb_key_for_site_lang . 'booking_mail'] = 'https://github.com/itvn9online/echbaydotcom/blob/master/html/mail/booking.html';

// file mail mặc định
//$___eb_lang[eb_key_for_site_lang . 'mail_main'] = file_get_contents( EB_THEME_PLUGIN_INDEX . 'html/mail/mail.html' );
$___eb_lang[eb_key_for_site_lang . 'mail_main'] = 'mail_main';
$eb_type_lang[eb_key_for_site_lang . 'mail_main'] = 'textarea';
$eb_ex_from_github[eb_key_for_site_lang . 'mail_main'] = 'https://github.com/itvn9online/echbaydotcom/blob/master/html/mail/mail.html';

// mail khi đăng ký nhận tin
//$___eb_lang[eb_key_for_site_lang . 'quick_register_mail'] = file_get_contents( EB_THEME_PLUGIN_INDEX . 'html/mail/qregister.html' );
$___eb_lang[eb_key_for_site_lang . 'quick_register_mail'] = 'quick_register_mail';
$eb_type_lang[eb_key_for_site_lang . 'quick_register_mail'] = 'textarea';
$eb_ex_from_github[eb_key_for_site_lang . 'quick_register_mail'] = 'https://github.com/itvn9online/echbaydotcom/blob/master/html/mail/qregister.html';

// HTML cho trang liên hệ
$___eb_lang[eb_key_for_site_lang . 'contact_html'] = 'contact';
$eb_type_lang[eb_key_for_site_lang . 'contact_html'] = 'textarea';
$eb_ex_from_github[eb_key_for_site_lang . 'contact_html'] = 'https://github.com/itvn9online/echbaydotcom/blob/master/html/contact.html';




/*
* Mã nhúng ngoài của một số trang cụ thể -> custom code -> cc
*/
// Trang chi tiết sản phẩm
$___eb_lang[eb_key_for_site_lang . 'cc_head_product'] = '';
$eb_type_lang[eb_key_for_site_lang . 'cc_head_product'] = 'textarea';

$___eb_lang[eb_key_for_site_lang . 'cc_body_product'] = '';
$eb_type_lang[eb_key_for_site_lang . 'cc_body_product'] = 'textarea';





/*
* Phần này không hẳn là phần ngôn ngữ, mà nó là phần config nhanh, đỡ phải chỉnh nhiều
*/
// kích thước ảnh quảng cáo ở phần danh sách sản phẩm trang chủ: auto || 90/728
$___eb_lang[eb_key_for_site_lang . 'homelist_size'] = 'auto';
$eb_class_css_lang[eb_key_for_site_lang . 'homelist_size'] = 'fixed-size-for-config';

$___eb_lang[eb_key_for_site_lang . 'homelist_num'] = 1;
$eb_type_lang[eb_key_for_site_lang . 'homelist_num'] = 'number';


// tiêu đề của phần logo đối tác
$___eb_lang[eb_key_for_site_lang . 'doitac_title'] = '';

// số comment facebook mặc định
$___eb_lang[eb_key_for_site_lang . 'fb_comments'] = 10;
$eb_type_lang[eb_key_for_site_lang . 'fb_comments'] = 'number';

// số tin trên mỗi dòng
$___eb_lang[eb_key_for_site_lang . 'doitac_num'] = 5;
$eb_type_lang[eb_key_for_site_lang . 'doitac_num'] = 'number';

// kích thước của banner đối tác: auto || 1/2
$___eb_lang[eb_key_for_site_lang . 'doitac_size'] = 'auto';
$eb_class_css_lang[eb_key_for_site_lang . 'doitac_size'] = 'fixed-size-for-config';


// Số lượng banner lớn sẽ lấy cho mỗi trang
$___eb_lang[eb_key_for_site_lang . 'bigbanner_num'] = 5;
$eb_type_lang[eb_key_for_site_lang . 'bigbanner_num'] = 'number';

// thẻ H2 cho phần chi tiết tin tức
$___eb_lang[eb_key_for_site_lang . 'tag_blog_excerpt'] = 'h2';

//
$___eb_lang[eb_key_for_site_lang . 'search_autocomplete'] = 'off';
$eb_note_lang[eb_key_for_site_lang . 'search_autocomplete'] = 'on/ off';



// icon cho khối mạng xã hội
$eb_note_first_lang[eb_key_for_site_lang . 'schema_home_type'] = 'Định nghĩa Type cho phần dữ liệu có cấu trúc';
$___eb_lang[eb_key_for_site_lang . 'schema_home_type'] = 'Person';

// để product hay bị báo lỗi -> nên dùng article
$___eb_lang[eb_key_for_site_lang . 'schema_post_type'] = 'Product';
//$___eb_lang[eb_key_for_site_lang . 'schema_post_type'] = 'Article';

$___eb_lang[eb_key_for_site_lang . 'schema_product_type'] = 'product';
//$___eb_lang[eb_key_for_site_lang . 'schema_product_type'] = 'article';

$eb_ex_from_github[eb_key_for_site_lang . 'schema_post_type'] = 'https://developers.google.com/search/docs/data-types/product';
$___eb_lang[eb_key_for_site_lang . 'schema_blog_type'] = 'BlogPosting';
$eb_ex_from_github[eb_key_for_site_lang . 'schema_blog_type'] = 'https://developers.google.com/search/docs/data-types/article';



// icon cho khối mạng xã hội
$eb_note_first_lang[eb_key_for_site_lang . 'social_facebook'] = 'Icons cho phần mạng xã hội (sử dụng font awesome)';
$___eb_lang[eb_key_for_site_lang . 'social_facebook'] = 'fab fa-facebook-f';
$___eb_lang[eb_key_for_site_lang . 'social_instagram'] = 'fa fa-instagram';
$___eb_lang[eb_key_for_site_lang . 'social_twitter'] = 'fab fa-twitter';
$___eb_lang[eb_key_for_site_lang . 'social_youtube'] = 'fab fa-youtube';
$___eb_lang[eb_key_for_site_lang . 'social_google_plus'] = 'fab fa-google-plus-g';
$___eb_lang[eb_key_for_site_lang . 'social_pinterest'] = 'fa fa-pinterest';


// tự chỉnh câu chữ trong nút mua của bản mobile
$eb_note_first_lang[eb_key_for_site_lang . 'details_mobilemua_mua'] = 'Bản dịch cho nút Mua ngay trong trang chi tiết sản phẩm (phiên bản mobile)';
$___eb_lang[eb_key_for_site_lang . 'details_mobilemua_mua'] = '<i class="fa fa-shopping-cart"></i> <span>Mua ngay</span>';
$___eb_lang[eb_key_for_site_lang . 'details_mobilemua_top'] = '<i class="fa fa-arrow-up"></i> Đầu trang';


$eb_note_first_lang[eb_key_for_site_lang . 'dc_is_null'] = 'Bản dịch cho khâu kiểm tra sự tồn tại của mã giảm giá';
$___eb_lang[eb_key_for_site_lang . 'dc_is_null'] = 'Không xác định được Mã giảm giá!';
$___eb_lang[eb_key_for_site_lang . 'dc_too_short'] = 'Mã giảm giá quá ngắn, tối thiểu phải có 3 ký tự!';
$___eb_lang[eb_key_for_site_lang . 'dc_not_found'] = 'Không tìm thấy Mã giảm giá hợp lệ!';
$___eb_lang[eb_key_for_site_lang . 'dc_expires'] = 'Mã giảm giá đã hết hạn hoặc không còn sử dụng!';
$___eb_lang[eb_key_for_site_lang . 'dc_ok'] = 'Mã giảm giá hợp lệ!';


// ngôn ngữ riêng trong trang chi tiết sản phẩm, tin tức
$eb_note_first_lang[eb_key_for_site_lang . 'order_status_name-1'] = 'Phần bản dịch để đặt tên cho trạng thái của đơn hàng (trạng thái sẽ bị ẩn khi đặt là none)';
$___eb_lang[eb_key_for_site_lang . 'order_status_name-1'] = '[ XÓA ]';
$___eb_lang[eb_key_for_site_lang . 'order_status_name0'] = 'Chưa xác nhận';
$___eb_lang[eb_key_for_site_lang . 'order_status_name1'] = 'Xác nhận, chờ giao';
$___eb_lang[eb_key_for_site_lang . 'order_status_name2'] = 'Đơn giờ vàng';
$___eb_lang[eb_key_for_site_lang . 'order_status_name3'] = 'Đang xác nhận';
$___eb_lang[eb_key_for_site_lang . 'order_status_name4'] = '[ Đã hủy ]';
$___eb_lang[eb_key_for_site_lang . 'order_status_name5'] = 'Xác nhận, chờ hàng';
$___eb_lang[eb_key_for_site_lang . 'order_status_name6'] = 'Không liên lạc được';
$___eb_lang[eb_key_for_site_lang . 'order_status_name7'] = 'Liên hệ lại';
$___eb_lang[eb_key_for_site_lang . 'order_status_name8'] = 'Đặt trước, đã thanh toán';
$___eb_lang[eb_key_for_site_lang . 'order_status_name9'] = 'Hoàn tất';
$___eb_lang[eb_key_for_site_lang . 'order_status_name10'] = 'Xác nhận, chờ in';
$___eb_lang[eb_key_for_site_lang . 'order_status_name11'] = 'Đang vận chuyển';
$___eb_lang[eb_key_for_site_lang . 'order_status_name12'] = 'Danh sách đen';
$___eb_lang[eb_key_for_site_lang . 'order_status_name13'] = 'Ẩn';
$___eb_lang[eb_key_for_site_lang . 'order_status_name14'] = 'Đang nhập hàng';
$___eb_lang[eb_key_for_site_lang . 'order_status_name15'] = 'Hàng hoàn';
$___eb_lang[eb_key_for_site_lang . 'order_status_name16'] = 'none';
$___eb_lang[eb_key_for_site_lang . 'order_status_name17'] = 'none';
$___eb_lang[eb_key_for_site_lang . 'order_status_name18'] = 'none';
$___eb_lang[eb_key_for_site_lang . 'order_status_name19'] = 'none';


// phần ngôn ngữ riêng, để sử dụng cho các câu từ mà một số website sẽ dùng
$eb_note_first_lang[eb_key_for_site_lang . 'custom_text'] = 'Phần bản dịch được dựng sẵn để tùy biến cho các theme khác nhau';
$___eb_lang[eb_key_for_site_lang . 'custom_text'] = 'Custom text';
$___eb_lang[eb_key_for_site_lang . 'custom_text1'] = 'Custom text 1';
$___eb_lang[eb_key_for_site_lang . 'custom_text2'] = 'Custom text 2';
$___eb_lang[eb_key_for_site_lang . 'custom_text3'] = 'Custom text 3';
$___eb_lang[eb_key_for_site_lang . 'custom_text4'] = 'Custom text 4';
$___eb_lang[eb_key_for_site_lang . 'custom_text5'] = 'Custom text 5';
$___eb_lang[eb_key_for_site_lang . 'custom_text6'] = 'Custom text 6';
$___eb_lang[eb_key_for_site_lang . 'custom_text7'] = 'Custom text 7';
$___eb_lang[eb_key_for_site_lang . 'custom_text8'] = 'Custom text 8';
$___eb_lang[eb_key_for_site_lang . 'custom_text9'] = 'Custom text 9';


// ngôn ngữ riêng trong trang chi tiết sản phẩm, tin tức
$eb_note_first_lang[eb_key_for_site_lang . 'home_shortcode'] = 'Tạo mã để nhúng shortcode vào website (chủ yếu dùng cho page template), tránh trường hợp nhúng thẳng file tĩnh xong khách thay đổi hoặc xóa nhầm widget là đứt luôn code. Cách sử dụng: <strong>&lt;?php echo WGR_echo_shortcode(\'home_shortcode\'); ?&gt;</strong>';
$___eb_lang[eb_key_for_site_lang . 'home_shortcode'] = '[widget id="tmp_shortcode_____" note="Ghi chú"]';
$___eb_lang[eb_key_for_site_lang . 'home_shortcode1'] = '[widget id="tmp_shortcode_____" note="Ghi chú"]';
$___eb_lang[eb_key_for_site_lang . 'home_shortcode2'] = '[widget id="tmp_shortcode_____" note="Ghi chú"]';
$___eb_lang[eb_key_for_site_lang . 'home_shortcode3'] = '[widget id="tmp_shortcode_____" note="Ghi chú"]';
$___eb_lang[eb_key_for_site_lang . 'home_shortcode4'] = '[widget id="tmp_shortcode_____" note="Ghi chú"]';
$___eb_lang[eb_key_for_site_lang . 'home_shortcode5'] = '[widget id="tmp_shortcode_____" note="Ghi chú"]';
$___eb_lang[eb_key_for_site_lang . 'home_shortcode6'] = '[widget id="tmp_shortcode_____" note="Ghi chú"]';
$___eb_lang[eb_key_for_site_lang . 'home_shortcode7'] = '[widget id="tmp_shortcode_____" note="Ghi chú"]';
$___eb_lang[eb_key_for_site_lang . 'home_shortcode8'] = '[widget id="tmp_shortcode_____" note="Ghi chú"]';
$___eb_lang[eb_key_for_site_lang . 'home_shortcode9'] = '[widget id="tmp_shortcode_____" note="Ghi chú"]';
$___eb_lang[eb_key_for_site_lang . 'home_shortcode10'] = '[widget id="tmp_shortcode_____" note="Ghi chú"]';
$___eb_lang[eb_key_for_site_lang . 'home_shortcode11'] = '[widget id="tmp_shortcode_____" note="Ghi chú"]';
$___eb_lang[eb_key_for_site_lang . 'home_shortcode12'] = '[widget id="tmp_shortcode_____" note="Ghi chú"]';
$___eb_lang[eb_key_for_site_lang . 'home_shortcode13'] = '[widget id="tmp_shortcode_____" note="Ghi chú"]';
$___eb_lang[eb_key_for_site_lang . 'home_shortcode14'] = '[widget id="tmp_shortcode_____" note="Ghi chú"]';
$___eb_lang[eb_key_for_site_lang . 'home_shortcode15'] = '[widget id="tmp_shortcode_____" note="Ghi chú"]';
$___eb_lang[eb_key_for_site_lang . 'home_shortcode16'] = '[widget id="tmp_shortcode_____" note="Ghi chú"]';
$___eb_lang[eb_key_for_site_lang . 'home_shortcode17'] = '[widget id="tmp_shortcode_____" note="Ghi chú"]';
$___eb_lang[eb_key_for_site_lang . 'home_shortcode18'] = '[widget id="tmp_shortcode_____" note="Ghi chú"]';
$___eb_lang[eb_key_for_site_lang . 'home_shortcode19'] = '[widget id="tmp_shortcode_____" note="Ghi chú"]';
$___eb_lang[eb_key_for_site_lang . 'home_shortcode20'] = '[widget id="tmp_shortcode_____" note="Ghi chú"]';


// ngôn ngữ riêng trong trang chi tiết sản phẩm, tin tức
$eb_note_first_lang[eb_key_for_site_lang . 'post_custom_text'] = 'Phần bản dịch được dựng sẵn để tùy biến cho trang <strong>Chi tiết sản phẩm</strong>. Cách sử dụng: <strong>{tmp.lang_post_custom_text}</strong>';
$___eb_lang[eb_key_for_site_lang . 'post_custom_text'] = 'Post custom text';
$___eb_lang[eb_key_for_site_lang . 'post_custom_text1'] = 'Post custom text 1';
$___eb_lang[eb_key_for_site_lang . 'post_custom_text2'] = 'Post custom text 2';
$___eb_lang[eb_key_for_site_lang . 'post_custom_text3'] = 'Post custom text 3';
$___eb_lang[eb_key_for_site_lang . 'post_custom_text4'] = 'Post custom text 4';
$___eb_lang[eb_key_for_site_lang . 'post_custom_text5'] = 'Post custom text 5';
$___eb_lang[eb_key_for_site_lang . 'post_custom_text6'] = 'Post custom text 6';
$___eb_lang[eb_key_for_site_lang . 'post_custom_text7'] = 'Post custom text 7';
$___eb_lang[eb_key_for_site_lang . 'post_custom_text8'] = 'Post custom text 8';
$___eb_lang[eb_key_for_site_lang . 'post_custom_text9'] = 'Post custom text 9';
$___eb_lang[eb_key_for_site_lang . 'post_custom_text10'] = 'Post custom text 10';

$eb_note_first_lang[eb_key_for_site_lang . 'blog_views'] = 'Phần bản dịch được dựng sẵn để tùy biến cho trang <strong>Chi tiết Tin tức</strong>. Cách sử dụng: <strong>{tmp.lang_blog_custom_text}</strong>';
$___eb_lang[eb_key_for_site_lang . 'blog_views'] = 'Lượt xem:';
$___eb_lang[eb_key_for_site_lang . 'blog_other'] = 'Bài xem nhiều';
$___eb_lang[eb_key_for_site_lang . 'blog_custom_text'] = 'Blog custom text';
$___eb_lang[eb_key_for_site_lang . 'blog_custom_text1'] = 'Blog custom text 1';
$___eb_lang[eb_key_for_site_lang . 'blog_custom_text2'] = 'Blog custom text 2';
$___eb_lang[eb_key_for_site_lang . 'blog_custom_text3'] = 'Blog custom text 3';
$___eb_lang[eb_key_for_site_lang . 'blog_custom_text4'] = 'Blog custom text 4';
$___eb_lang[eb_key_for_site_lang . 'blog_custom_text5'] = 'Blog custom text 5';


// for admin
$___eb_lang[eb_key_for_site_lang . '_eb_product_noibat'] = 'Điểm nổi bật';
$___eb_lang[eb_key_for_site_lang . '_eb_product_dieukien'] = 'Điều kiện';


// URL của phần chính sách, quy định trong phần đặt hàng
$___eb_lang[eb_key_for_site_lang . 'url_chinhsach'] = '#';
$___eb_lang[eb_key_for_site_lang . 'chinhsach'] = 'Quý khách vui lòng tham khảo <a href="{tmp.url_chinhsach}" target="_blank">chính sách, quy định chung</a> của chúng tôi.';




// gọi tới function ngôn ngữ riêng của từng website
if ( function_exists('WGR_child_lang') ) {
	WGR_child_lang();
}





// lúc lấy lang thì không cần gán key đầy đủ, mà sẽ gán trong function này
function EBE_get_lang($k) {
	global $___eb_lang;
	
//	return isset( $___eb_lang[eb_key_for_site_lang . $k] ) ? $___eb_lang[eb_key_for_site_lang . $k] : '';
//	return '<eblang-element>' . $___eb_lang[eb_key_for_site_lang . $k] . '</eblang-element>';
	return $___eb_lang[eb_key_for_site_lang . $k];
}

function EBE_set_lang($key, $val) {
	
	// sử dụng option thay cho meta_post -> load nhanh hơn nhiều
//	$key = eb_key_for_site_lang . $key;
	
	// xóa option cũ đi cho đỡ lằng nhằng
//	delete_option( $key );
	WGR_delete_option( $key );
	
	// chỉ cập nhật khi có value, nếu không có thì sử dụng của bản default
	if ( $val != '' ) {
//		$val = WGR_stripslashes( $val );
		
		// thêm option mới
//		add_option( $key, $val, '', 'no' );
		WGR_set_option( $key, $val, 'no' );
	}
	
}

function EBE_get_lang_list() {
	
	global $wpdb;
	global $___eb_lang;
//	print_r( $___eb_lang );
	
	
	//
	$option_conf_name = eb_key_for_site_lang;
	
	$row = _eb_q("SELECT option_name, option_value
	FROM
		`" . $wpdb->options . "`
	WHERE
		option_name LIKE '{$option_conf_name}%'");
//	print_r( $row );
//	exit();
	
	//
	foreach ( $row as $k => $a ) {
		// chỉ hiện thị các lang được hỗ trợ
//		if ( isset( $___eb_lang[ $a->option_name ] ) ) {
			$___eb_lang[ $a->option_name ] = WGR_stripslashes( $a->option_value );
//		}
		// xóa các lang không tồn tại
//		else {
//			delete_option( $a->option_name );
//		}
	}
//	print_r( $___eb_lang );
	
}




// Nếu không phải tiếng Việt -> add mặc định tiếng anh
//echo $__cf_row['cf_content_language'];
if ( $__cf_row['cf_content_language'] != 'vi' ) {
	include EB_THEME_PLUGIN_INDEX . 'lang/en.php';
//	echo 'aaaaaaaaaaaa';
}
// phần ngôn ngữ cho admin
include EB_THEME_PLUGIN_INDEX . 'lang/admin.php';




//
$___eb_default_lang = $___eb_lang;
//$___eb_lang_default = $___eb_lang;
//$___eb_lang = array();
// EBE_get_lang('home')




