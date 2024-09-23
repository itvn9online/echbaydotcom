<?php


/*
 * xem thêm quyền hạn của các loại tài khoản: https://wordpress.org/support/article/roles-and-capabilities/
 */


// optimize các file tĩnh (css, js...)
//print_r( $_SERVER );
// khi truy cập các link từ các file sau thì mới thực hiện lệnh optimize
$whitelist_optimize = [
    'admin.php',
    'edit.php',
    'edit-tags.php',
    //'admin.php',
];
if (in_array(basename($_SERVER['PHP_SELF']), $whitelist_optimize)) {
    WGR_optimize_static_code();
}
//echo __FILE__ . ':' . __LINE__ . '<br>' . "\n";


// tạo URL động trong admin, hạn chế lỗi URL
$web_ad_link = explode('/', web_link);
$web_ad_link[0] = eb_web_protocol . ':';
$web_ad_link[2] = $_SERVER['HTTP_HOST'];
$web_ad_link = implode('/', $web_ad_link);
//echo $web_ad_link; exit();


/*
* admin menu

$page_title: tiêu đề page (nội dung thẻ title) khi lựa chọn vào menu.
$menu_title: tên hiển thị của menu.
$capability: quyền hạn quyết định menu này hiển thị với những user nào?
$menu_slug: slug của menu hiển thị trên url. Từ phiên bản wordpress 3.0 trở lên tham số này có thể là 1 file php. Nếu không chỉ định tham số $function thì tham số này sẽ trỏ tới file php để làm nội dung của menu.
$function: hàm hiển thị nội dung của menu.
$icon_url: URL ảnh để làm icon cho menu. Mặc định không chỉ định. Kích thước của icon là 20×20 hoặc nhỏ hơn. Có thể sử dụng hàm plugin_dir_url( __FILE__ ) để lấy đường dẫn của thư mục plugin của bạn và trỏ vào 1 file ảnh.
$position (integer): Chỉ số Thứ tự hiển thị menu so với các menu khác. Theo mặc định thì menu sẽ hiển thị ở phía dưới cùng. Chú ý nếu 2 menu sử dụng cùng chỉ số thứ tự thì chỉ hiển thị menu thứ 2. Do vậy để tránh nhầm lẫn và theo quy ước các chỉ số bạn nên sử dụng số thập phân thay vì dùng số nguyên, e.g: 24.5 thay vì dùng 24. Và dùng giá trị chuỗi ‘24.5’ cho tham số này.

*/


/*
* admin sub-menu

$parent_slug: chỉ định tên menu lớn, menu con này sẽ hiển thị dưới menu lớn này. Với những parent menu hệ thống thì sử dụng tên file php. ví dụ: options.php (menu Settings), edit.php (menu Posts),..Bạn có thể tham khảo tại đây: http://codex.wordpress.org/Function_Reference/add_submenu_page. Nếu $parent_slug=NULL thì mặc định tham số này là “options.php”
$page_title: tiêu đề trang là thẻ title.
$menu_title: tên hiển thị menu con này.
$capability: quyền hạn quyết định menu này hiển thị với những user nào?
$menu_slug: page slug của menu này.
$function: hàm hiển thị nội dung của menu. Khi lựa chọn menu thì sẽ gọi hàm này để hiển thị nội dung. Hàm được gọi theo 2 cách: Hàm là thành viên của class thì được gọi theo dạng array( $this, ‘function_name’ ) or hàm tự do nằm ngoài class thì cung tên nguyên tên hàm là đủ.

*/


//
function func_include_eb_private_code()
{
    include ECHBAY_PRI_CODE . 'index.php';
}

function register_mysettings()
{
    register_setting('mfpd-settings-group', 'mfpd_option_name');
}

// tạo menu admin
function echbay_create_admin_menu()
{
    global $arr_private_info_setting;

    // hiển thị menu dựa theo quyền
    // từ quyền tác giả là được vào xem đơn
    if (current_user_can('publish_posts')) {
        $parent_slug = 'eb-order';
    }
    // dưới đó thì chỉ xem thông tin cơ bản
    else if (current_user_can('edit_posts')) {
        $parent_slug = 'eb-dashboard';
    }
    // dưới nữa thì cho xem giới thiệu
    else {
        $parent_slug = 'eb-about';
    }

    /**
     * EchBay menu -> mọi người đều có thể nhìn thấy menu này
     */
    add_menu_page('Danh sách đơn hàng', $arr_private_info_setting['site_upper'], 'read', $parent_slug, 'func_include_eb_private_code', NULL, 6);


    /**
     * submenu -> Super Admin, Administrator, Editor, Author
     */
    add_submenu_page($parent_slug, 'Danh sách đơn hàng', 'Đơn hàng', 'publish_posts', 'eb-order', 'func_include_eb_private_code');


    /**
     * submenu -> Super Admin, Administrator, Editor, Author, Contributor
     */
    add_submenu_page($parent_slug, 'Tổng quan về website', 'Tổng quan', 'edit_posts', 'eb-dashboard', 'func_include_eb_private_code');

    //	add_submenu_page( $parent_slug, 'Danh sách banner quảng cáo', 'Quảng cáo', 'manage_options', 'eb-ads', 'func_include_eb_private_code' );


    // Danh sách thành viên trên website -> một dạng dữ liệu khác để tiện quản lý -> Super Admin, Administrator, Editor
    add_submenu_page($parent_slug, 'Danh sách Thành viên/ Khách hàng', 'Thành viên', 'publish_pages', 'eb-members', 'func_include_eb_private_code');


    // menu chỉnh sửa sản phẩm nhanh -> Super Admin, Administrator, Editor
    add_submenu_page($parent_slug, 'Công cụ hỗ trợ chỉnh sửa nhanh dữ liệu', 'Chỉnh sửa nhanh', 'publish_pages', 'eb-products', 'func_include_eb_private_code');


    /**
     * Super Admin, Administrator, Editor
     */
    add_submenu_page($parent_slug, 'Cấu hình website', 'Cấu hình website', 'publish_pages', 'eb-config', 'func_include_eb_private_code');

    add_submenu_page($parent_slug, 'Cài đặt và chỉnh sửa giao diện mặc định', 'Cài đặt giao diện', 'manage_options', 'eb-config_theme', 'func_include_eb_private_code');

    add_submenu_page($parent_slug, 'Các chức năng danh cho kỹ thuật viên', 'Kỹ thuật', 'manage_options', 'eb-coder', 'func_include_eb_private_code');

    add_submenu_page($parent_slug, 'Lịch sử các thay đổi dữ liệu hệ thống', 'Lịch sử', 'manage_options', 'eb-log', 'func_include_eb_private_code');

    //	add_submenu_page( $parent_slug, 'Công cụ tạo sơ đồ website tự động', 'Sitemap', 'administrator', 'eb-sitemap', 'func_include_eb_private_code' );

    //	add_submenu_page( $parent_slug, 'Kiểm tra độ chuẩn của HTML cơ bản', 'Kiểm tra mã HTML', 'administrator', 'eb-check-html', 'func_include_eb_private_code' );

    //	add_submenu_page( $parent_slug, 'Thông tin server', 'Thông tin server', 'administrator', 'eb-server-info', 'func_include_eb_private_code' );

    //	add_submenu_page( $parent_slug, 'Dọn dẹp các dữ liệu tạm trong quá trình sử dụng website', 'Xóa bộ nhớ tạm', 'administrator', 'eb-cleanup-cache', 'func_include_eb_private_code' );


    /**
     * Mọi người đều có thể nhìn thấy menu này
     */
    add_submenu_page($parent_slug, 'Giới thiệu về tác giả', 'Giới thiệu', 'read', 'eb-about', 'func_include_eb_private_code');


    /**
     * Bản nâng cao thì chỉ cần admin nhìn thôi, người khác không quan trọng
     */
    if ($arr_private_info_setting['parent_theme_default'] == 'echbaytwo') {
        add_submenu_page($parent_slug, 'Phiên bản cao cấp, hỗ trợ nhiều tính năng hơn', 'Phiên bản ', 'manage_options', 'eb-licenses', 'func_include_eb_private_code');
    }

    //
    //add_submenu_page(null, '', '', 'manage_options', 'wgr-version-flatsome', '__wgr_v2_version_flatsome');


    //
    //	add_filter( 'admin_init', 'register_mysettings' );
}

add_filter('admin_menu', 'echbay_create_admin_menu');


// Tạo page để xem thông tin phiên bản
function __wgr_v2_version_flatsome()
{
    //$a = file_get_contents('https://raw.githubusercontent.com/itvn9online/webgiareorg/main/changes.txt');
    $a = file_get_contents('https://webgiare.org/wp-content/themes/flatsome/changes.txt');
    echo nl2br($a);
}


//
$str_list_wordpress_rule = '';
if (mtv_id > 0 && strpos($_SERVER['REQUEST_URI'], '/options-permalink.php') !== false) {
    add_filter('rewrite_rules_array', 'get_all_rules_for_nginx');

    function get_all_rules_for_nginx($rules)
    {
        global $str_list_wordpress_rule;

        //		print_r( $rules );

        $str_list_wordpress_rule = '<script type="text/javascript">' . "\n";
        $str_list_wordpress_rule .= 'var arr_wordpress_rules = {};' . "\n";
        foreach ($rules as $k => $v) {
            $str_list_wordpress_rule .= 'arr_wordpress_rules["' . $k . '"] = "' . $v . '";' . "\n";
        }
        $str_list_wordpress_rule .= '</script>';

        return $rules;
    }
}


/**
 * Nhúng css cho phần admin
 */
function echbay_admin_styles()
{
    global $str_list_wordpress_rule;
    global $__cf_row;
    global $wpdb;
    global $client_ip;
    global $year_curent;
    global $month_curent;
    global $web_ad_link;
    //	global $web_link;


    //
    $vdate_time = date('Ym-dh', date_time);


    // lấy số STT lớn nhất của bài viết/ sản phẩm -> gán cho nó sản phẩm mới thêm sẽ luôn được lên đầu
    $order_max_post_new = 0;
    if (strpos($_SERVER['REQUEST_URI'], '/post-new.php') !== false) {
        $order_by_post_type = 'post';
        if (isset($_GET['post_type'])) {
            $order_by_post_type = $_GET['post_type'];
        }
        $sql = _eb_q("SELECT menu_order
		FROM
			`" . wp_posts . "`
		WHERE
			post_type = '" . $order_by_post_type . "'
			AND post_status = 'publish'
			AND menu_order > 0
		ORDER BY
			menu_order DESC
		LIMIT 0, 1");
        //		print_r( $sql );
        if (!empty($sql)) {
            $order_max_post_new = $sql[0]->menu_order;
        }
    }

    //
    global $arr_eb_ads_status;
    global $arr_eb_product_status;

    //
    $str_ads_status = '';
    foreach ($arr_eb_ads_status as $k => $v) {
        //		$str_ads_status .= ',{id:' . $k . ',ten:"' . str_replace( '"', '\"', $v ) . '"}';
        $str_ads_status .= ',{id:' . $k . ',ten:"' . _eb_str_block_fix_content($v) . '"}';
    }

    //
    $str_product_status = '';
    foreach ($arr_eb_product_status as $k => $v) {
        //		$str_product_status .= ',{id:' . $k . ',ten:"' . str_replace( '"', '\"', $v ) . '"}';
        $str_product_status .= ',{id:' . $k . ',ten:"' . _eb_str_block_fix_content($v) . '"}';
    }

    //
    include EB_THEME_PLUGIN_INDEX . 'class/custom/admin-js.php';


    // lấy thời gian cập nhật cuối của file css -> update lại toàn bộ các file khác
    //	$last_update_js = date( 'Y-m-d.H-i', filemtime( EB_THEME_PLUGIN_INDEX . 'javascript/eb.js' ) );
    //	$last_update_css = date( 'Y-m-d.H-i', filemtime( EB_THEME_PLUGIN_INDEX . 'css/d.css' ) );

    //
    /*
	_eb_add_full_css( EBE_admin_set_realtime_for_file ( array(
//		$web_ad_link . EB_DIR_CONTENT . '/themes/echbaytwo/outsource/fonts/font-awesome.css',
		EB_THEME_OUTSOURCE . 'fonts/font-awesome.css',
		EB_URL_OF_PLUGIN . 'css/d.css',
		EB_URL_OF_PLUGIN . 'css/d2.css',
		EB_URL_OF_PLUGIN . 'css/admin.css',
//		EB_URL_OF_PLUGIN . 'css/admin.css',
	) ), 'link' );
	*/
    $a = array(
        // trong admin có gắn thêm bản font-fontawesome-5.JS -> chỉ load bản font-fontawesome-4.CSS thì nó mới không xung đột
        //EB_THEME_OUTSOURCE . 'fa-4.7.0/i.css',
        //EB_THEME_OUTSOURCE . 'fontawesome-free-5.0.6/css/fontawesome.css',
        // awesome v5
        //EB_THEME_OUTSOURCE . 'fa-5.3.0/css/i.css',
        //EB_THEME_OUTSOURCE . 'fa-5.3.0/css/v4-shims.min.css',
        EB_THEME_OUTSOURCE . 'fontawesome-free-5.15.4-web/css/fontawesome.min.css',
        EB_THEME_OUTSOURCE . 'fontawesome-free-5.15.4-web/css/brands.min.css',
        EB_THEME_OUTSOURCE . 'fontawesome-free-5.15.4-web/css/solid.min.css',
        EB_THEME_OUTSOURCE . 'fontawesome-free-5.15.4-web/css/v4-shims.min.css',
        //
        EB_THEME_PLUGIN_INDEX . 'css/d.css',
        EB_THEME_PLUGIN_INDEX . 'css/d2.css',
        EB_THEME_PLUGIN_INDEX . 'css/admin.css',
        EB_THEME_PLUGIN_INDEX . 'css/admin-blog-widget.css'
    );
    //	echo EB_THEME_PLUGIN_INDEX . 'aaaaaaaaaaaaaaaaaa' . "\n";
    //	echo ABSPATH . 'aaaaaaaaaaaaaaaaaa' . "\n";
    //	echo web_link . 'aaaaaaaaaaaaaaaaaa' . "\n";
    //	echo WGR_path_to_lnk('aaaaaaaa') . 'aaaaaaaaaaaaaaaaaa' . "\n";
    foreach ($a as $v) {
        //		$k = EB_THEME_PLUGIN_INDEX . $v;
        //		echo $k . '<br>' . "\n";
        //		if ( is_file( $v ) ) {
        echo '<link rel="stylesheet" href="' . WGR_path_to_lnk($v) . '?v=' . filemtime($v) . '" type="text/css" media="all" />' . "\n";
        //		}
    }


    //
    /*
	_eb_add_full_js( EBE_admin_set_realtime_for_file ( array(
//		$web_ad_link . 'wp-includes/js/jquery/jquery.js',
//		EB_URL_OF_PLUGIN . 'javascript/eb_wp.js',
		EB_URL_OF_PLUGIN . 'javascript/eb.js',
//		EB_URL_OF_THEME . 'javascript/eb.js',
		EB_URL_OF_PLUGIN . 'javascript/all.js',
		EB_URL_OF_PLUGIN . 'javascript/edit_post.js',
//		EB_THEME_OUTSOURCE . 'javascript/jquery.caret.1.02.min.js',
//		EB_URL_OF_PLUGIN . 'javascript/a.js',
	) ), 'add' );
	*/
    $a = array(
        EB_THEME_PLUGIN_INDEX . 'javascript/functions.js',
        EB_THEME_PLUGIN_INDEX . 'javascript/eb.js',
        EB_THEME_PLUGIN_INDEX . 'javascript/all.js',
        EB_THEME_PLUGIN_INDEX . 'javascript/edit_menu.js',
        EB_THEME_PLUGIN_INDEX . 'javascript/edit_post.js',
        EB_THEME_PLUGIN_INDEX . 'javascript/edit_widgets.js'
    );
    foreach ($a as $v) {
        //		$k = EB_THEME_PLUGIN_INDEX . $v;
        //		echo $k . '<br>' . "\n";
        //		if ( is_file( $v ) ) {
        echo '<script type="text/javascript" src="' . WGR_path_to_lnk($v) . '?v=' . filemtime($v) . '"></script>' . "\n";
        //		}
    }
    //	echo 'aaaaaaaa'; exit();

    //
    //	echo WGR_show_header_favicon( $web_ad_link . eb_default_vaficon . '?v=' . EBE_admin_get_realtime_for_file( $web_ad_link . eb_default_vaficon ) ) . '

    //
    /*
	echo WGR_show_header_favicon( $web_ad_link . eb_default_vaficon . '?v=' . $vdate_time ) . '
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<script src="' . $web_ad_link . 'eb-load-quick-search"></script>';
*/

    //
    echo WGR_show_header_favicon($web_ad_link . eb_default_vaficon . '?v=' . $vdate_time) . '
<script src="' . $web_ad_link . 'eb-load-quick-search"></script>';

    //
    // không nạp jquery-ui trong các page dễ gây xung đột -> sử dụng jquery-ui của wordpress
    /*
	if ( strpos( $_SERVER[ 'REQUEST_URI' ], '/widgets.php' ) !== false ) {
		echo '<script>console.log("%c Không load echbay-jquery-ui trong widgets", "color: red;");</script>';
	} else if ( isset( $_GET[ 'post' ] ) && get_post_type( $_GET[ 'post' ] ) == 'product' ) {
		echo '<script>console.log("%c Không load echbay-jquery-ui trong EDIT post_type product", "color: red;");</script>';
	} else if ( isset( $_GET[ 'post_type' ] ) && $_GET[ 'post_type' ] == 'product' ) {
		echo '<script>console.log("%c Không load echbay-jquery-ui trong ADD post_type product", "color: red;");</script>';
	} else {
		echo '
<link rel="stylesheet" href="' . EB_URL_OUTSOURCE . 'jquery-ui/jquery-ui-1.11.2.css" />
<script src="' . EB_URL_OUTSOURCE . 'jquery-ui/jquery-ui.min.js"></script>';
	}
*/


    // nếu là phiên bản web giá rẻ -> ẩn các menu admin quan trọng đi, chỉ hiện thị với supper admin
    //	if ( mtv_id != 1 && webgiare_dot_org_install == true ) {
    //	if ( mtv_id != 1 ) {
    //		echo _eb_getCucki('ebe_click_show_hidden_menu') . ' ------- aaaaaaaaaaaaaaaaaaaaaaa';

    // nếu thuộc tính ẩn menu admin đang được kích hoạt -> ẩn
    if ($__cf_row['cf_hide_supper_admin_menu'] == 1 && _eb_getCucki('ebe_click_show_hidden_menu') == '') {
        /*
        _eb_add_full_css( EBE_admin_set_realtime_for_file ( array(
        	EB_URL_OF_PLUGIN . 'css/administrator.css',
        ) ), 'link' );
        */

        echo '<link rel="stylesheet" href="' . EB_URL_OF_PLUGIN . 'css/admin-hide-menu.css?v=' . EBE_admin_get_realtime_for_file(EB_URL_OF_PLUGIN . 'css/admin-hide-menu.css') . '" type="text/css" media="all" id="admin-hide-menu" />';
    }
    //	}


    // add css cho trang tin tức
    if ($__cf_row['cf_set_news_version'] == 1) {
        _eb_add_full_css(EBE_admin_set_realtime_for_file(array(
            EB_URL_OF_PLUGIN . 'css/admin_news.css',
        )), 'link');
    }


    //
    echo $str_list_wordpress_rule;


    // do có sử dụng google analytics trong này, nên chặn index luôn
    echo '<meta name="robots" content="noindex,follow" />' . "\n";
    //	print_r( $_GET );

    // nếu là trang chi tiết đơn -> hiển thị cả google analytics
    echo '<script type="text/javascript">WGR_load_GA_for_admin_tracking();</script>';
}
add_filter('admin_head', 'echbay_admin_styles');


/**
 * Load jQuery datepicker.
 *
 * By using the correct hook you don't need to check `is_admin()` first.
 * If jQuery hasn't already been loaded it will be when we request the
 * datepicker script.
 */
function WGR_enqueue_datepicker()
{
    //echo 'aaaaaaaaaaaaaa';
    // Load the datepicker script (pre-registered in WordPress).
    wp_enqueue_script('jquery-ui-datepicker', '', [], false, false);

    // You need styling for the datepicker. For simplicity I've linked to Google's hosted jQuery UI CSS.
    //wp_register_style( 'jquery-ui', 'http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css' );
    wp_register_style('jquery-ui', EB_URL_OF_PARENT_THEME . 'outsource/jquery-ui/jquery-ui-1.11.2.css');
    wp_enqueue_style('jquery-ui');
}
add_filter('admin_head', 'WGR_enqueue_datepicker');
//echo 'sdfsgsgsgs';


//
function echbay_admin_footer_styles()
{
    global $arr_private_info_setting;
    global $web_ad_link;
    global $__cf_row;


    // giới thiệu chung về cách phân quyền trong code
    echo '<div id="echbay_role_user_note" class="d-none">';
    include ECHBAY_PRI_CODE . 'role_user.php';
    echo '</div>';


    /**
     * các chức năng khác
     */
    include ECHBAY_PRI_CODE . 'global_html.php';

    echo file_get_contents(ECHBAY_PRI_CODE . 'html/size_edit.html', 1);


    // lấy thời gian cập nhật cuối của file css -> update lại toàn bộ các file khác
    //	$last_update_js = date( 'Y-m-d.H-i', filemtime( EB_THEME_PLUGIN_INDEX . 'javascript/eb.js' ) );
    //	$last_update_css = date( 'Y-m-d.H-i', filemtime( EB_THEME_PLUGIN_INDEX . 'css/d.css' ) );
    //	$last_update_js = date_time;


    // kiểm tra phiên bản hiện tại của web và phiên bản mới trên github
    // hiện tại
    $current_version = file_get_contents(EB_THEME_PLUGIN_INDEX . 'VERSION', 1);

    // trên github
    $strCacheFilter = 'github_version';
    $current_github_version = _eb_get_static_html($strCacheFilter, '', '', 12 * 3600);
    if ($current_github_version == false) {
        $current_github_version = _eb_getUrlContent($arr_private_info_setting['url_check_WGR_version']);
        /*
        $current_github_version = _eb_getUrlContent( 'https://world.webgiare.org/wp-content/echbaydotcom/VERSION' );
        */
        //		$current_github_version = _eb_getUrlContent( 'https://raw.githubusercontent.com/itvn9online/echbaydotcom/master/VERSION' );
        $current_github_version = _eb_del_line(strip_tags($current_github_version));

        _eb_get_static_html($strCacheFilter, $current_github_version, '', 60);
    }


    // phiên bản của theme
    $current_theme_version = '';
    if (is_file(EB_THEME_URL . 'VERSION')) {
        $current_theme_version = file_get_contents(EB_THEME_URL . 'VERSION', 1);
    }

    // trên github
    $strCacheFilter = 'github_theme_version';
    $current_theme_github_version = _eb_get_static_html($strCacheFilter, '', '', 12 * 3600);
    if ($current_theme_github_version == false) {
        $current_theme_github_version = _eb_getUrlContent($arr_private_info_setting['url_check_EB_theme_version']);
        /*
        $current_theme_github_version = _eb_getUrlContent( 'https://world.webgiare.org/wp-content/themes/echbaytwo/VERSION' );
        */
        //		$current_theme_github_version = _eb_getUrlContent( 'https://raw.githubusercontent.com/itvn9online/echbaytwo/master/VERSION' );
        $current_theme_github_version = _eb_del_line(strip_tags($current_theme_github_version));

        _eb_get_static_html($strCacheFilter, $current_theme_github_version, '', 60);
    }


    //
    echo '<script type="text/javascript">var cf_chu_de_chinh = "' . _eb_str_block_fix_content(str_replace('"', '\"', $__cf_row['cf_chu_de_chinh'])) . '",
	
	cf_content_for_excerpt_null = "' . $__cf_row['cf_content_for_excerpt_null'] . '",
	cf_excerpt_sync_content = "' . $__cf_row['cf_excerpt_sync_content'] . '",
	cf_excerpt_sync_yoast = "' . $__cf_row['cf_excerpt_sync_yoast'] . '",
	
	cf_replace_content = "' . urlencode($__cf_row['cf_replace_content']) . '",
	
	private_info_setting_site_upper = "' . $arr_private_info_setting['site_upper'] . '",
	private_info_setting_parent_theme_default = "' . $arr_private_info_setting['parent_theme_default'] . '",
	
	wgr_plugin_current_version = "' . $current_version . '",
	url_check_WGR_version = "' . $arr_private_info_setting['url_check_WGR_version'] . '",
	wgr_plugin_github_version = "' . $current_github_version . '",
    
	ELEMENTOR_cPRO_VERSION = "' . (defined('ELEMENTOR_cPRO_VERSION') ? ELEMENTOR_cPRO_VERSION : 0) . '",
	
	wgr_theme_current_version = "' . $current_theme_version . '",
	wgr_theme_github_version = "' . $current_theme_github_version . '";</script>';


    //
    /*
	_eb_add_full_js( EBE_admin_set_realtime_for_file ( array(
		EB_URL_OF_PLUGIN . 'javascript/a.js',
//		EB_URL_OF_PLUGIN . 'javascript/a.js',
	) ), 'add' );
	*/
    $a = array(
        EB_THEME_PLUGIN_INDEX . 'javascript/a.js',
    );

    // Thêm file admin của child theme nếu có
    if (using_child_wgr_theme == 1) {
        if (is_file(EB_CHILD_THEME_URL . 'ui/a.js')) {
            $a[] = EB_CHILD_THEME_URL . 'ui/a.js';
        }
    }
    //	print_r( $a );
    //	echo 'aaaaaaaaaaaaaaaaaaaaaa/' . EB_DIR_CONTENT . "\n";

    // fontawesome-free
    /*
    $a[] = EB_THEME_OUTSOURCE . 'fontawesome-free-5.15.4-web/js/brands.min.js';
    $a[] = EB_THEME_OUTSOURCE . 'fontawesome-free-5.15.4-web/js/solid.min.js';
    $a[] = EB_THEME_OUTSOURCE . 'fontawesome-free-5.15.4-web/js/fontawesome.min.js';
    $a[] = EB_THEME_OUTSOURCE . 'fontawesome-free-5.15.4-web/js/v4-shims.min.js';
    */
    //print_r($a);

    //
    foreach ($a as $v) {
        //		$k = EB_THEME_PLUGIN_INDEX . $v;
        //		$k =  $v;
        //		echo $k . '<br>' . "\n";
        //		if ( is_file( $v ) ) {
        echo '<script type="text/javascript" src="' . WGR_path_to_lnk($v) . '?v=' . filemtime($v) . '"></script>' . "\n";
        //		}
    }


    // add javascript cho trang tin tức
    if ($__cf_row['cf_set_news_version'] == 1) {
        _eb_add_full_js(EBE_admin_set_realtime_for_file(array(
            EB_URL_OF_PLUGIN . 'javascript/admin_news.js',
        )), 'add');
    }


    // mã nhúng ngoài cho phần admin
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        //		echo '<!-- Custom code for admin -->' . "\n\r";
        //		echo $__cf_row['cf_js_admin'];
    }
    //	echo htmlentities( $__cf_row['cf_js_admin'], ENT_QUOTES, "UTF-8" );


}
add_filter('admin_footer', 'echbay_admin_footer_styles');


// Thay footer trong wp bằng link của echbay
function eb_change_footer_admin()
{
    global $arr_private_info_setting;

    echo 'Designed by <a href="' . $arr_private_info_setting['site_url'] . '" target="_blank" rel="nofollow">' . $arr_private_info_setting['site_upper'] . '</a> using <a href="https://wordpress.org/" target="_blank" rel="nofollow">WordPress</a> CMS.</span> - <span class="cur graycolor click-show-eb-target">Show process</span> - <span class="cur graycolor click-show-no-customize">Show no-customize</span>.';
}
add_filter('admin_footer_text', 'eb_change_footer_admin');


// kích hoạt các nút soạn thảo ẩn
function ilc_mce_buttons($buttons)
{
    array_push(
        $buttons,
        //		"alignjustify",
        "backcolor",
        "anchor",
        "hr",
        "sub",
        "sup",
        "fontselect",
        "fontsizeselect",
        "styleselect",
        "cleanup"
    );
    return $buttons;
}

// muốn ở dòng nào thì add vào dòng đấy: mce_buttons_2, mce_buttons_3, mce_buttons_4
add_filter("mce_buttons_2", "ilc_mce_buttons");
//add_filter("mce_buttons_3", "ilc_mce_buttons");
//add_filter("mce_buttons_4", "ilc_mce_buttons");


// thêm cột cho post
// https://hocwp.net/guide/them-cot-vao-bang-quan-ly-bai-viet-wordpress/
function eb_add_post_column_head($columns)
{
    $columns['gia'] = 'Giá cũ/ Mới';
    $columns['img'] = 'Ảnh';
    $columns['stt'] = 'STT';

    return $columns;
}

// thêm cột cho blog
function eb_add_blog_column_head($columns)
{
    $columns['img'] = 'Ảnh';
    $columns['stt'] = 'STT';

    return $columns;
}

// thêm cột cho ads
function eb_add_ads_column_head($columns)
{
    $columns['ads_status'] = 'Trạng thái';
    $columns['img'] = 'Ảnh';
    $columns['stt'] = 'STT';

    return $columns;
}

// hàm xử lý nội dung và tạo cột
function eb_run_post_column_content($column, $post_id)
{
    global $web_ad_link;

    // giá bán
    if ('gia' == $column) {
        //		$a = _eb_get_post_meta ( $post_id, '_eb_product_oldprice', true, 0 );
        $a = _eb_float_only(_eb_get_post_object($post_id, '_eb_product_oldprice'));
        //		$b = _eb_get_post_meta ( $post_id, '_eb_product_price', true, 0 );
        $b = _eb_float_only(_eb_get_post_object($post_id, '_eb_product_price'));

        echo '<span class="ebe-currency ebe-currency-format">' . $a . '</span>/ <strong class="ebe-currency ebe-currency-format">' . $b . '</strong>';
    }
    // ảnh đại diện
    else if ($column == 'img') {
        //		$a = _eb_get_post_img ( $post_id, 'thumbnail' );

        //
        //		echo '<div class="eb-wp-admin-list-img" style="background-image:url(\'' . $a . '\');">&nbsp;</div>';

        //
        $a = _eb_get_post_img($post_id);
        echo '<div data-img="' . $a . '" data-id="' . $post_id . '" title="Nhấp đúp để sửa nhanh ảnh đại diện" class="admin-list-post-avt each-to-bgimg cur">&nbsp;</div>';
    }
    // trạng thái
    else if ($column == 'ads_status') {
        global $arr_eb_ads_status;

        //		$a = _eb_get_post_meta ( $post_id, '_eb_ads_status', true, 0 );
        $a = _eb_get_post_object($post_id, '_eb_ads_status', 0);

        //
        if (isset($arr_eb_ads_status[$a])) {
            echo '<span class="small"><a href="' . $web_ad_link . WP_ADMIN_DIR . '/edit.php?post_type=ads&ads_filter_status=' . $a . '">' . $arr_eb_ads_status[$a] . '</a></span>';
        } else {
            echo '<em>NULL</em>';
        }
    }
    // trạng thái
    else if ($column == 'stt') {
        global $post;

        //
        echo '<span>' . $post->menu_order . '</span>';
    }
}

function WGR_admin_load_ads_by_status($query)
{
    $ads_filter_status = isset($_GET['ads_filter_status']) ? trim(strtolower($_GET['ads_filter_status'])) : '';
    $post_filter_status = isset($_GET['post_filter_status']) ? trim(strtolower($_GET['post_filter_status'])) : '';

    //
    if ($ads_filter_status != '') {
        $status_in = array(
            'key' => '_eb_ads_status',
            'value' => $ads_filter_status,
            'compare' => '=',
            'type' => 'NUMERIC'
        );

        $query->set('meta_query', array($status_in));
    } else if ($post_filter_status != '') {
        $status_in = array(
            'key' => '_eb_product_status',
            'value' => $post_filter_status,
            'compare' => '=',
            'type' => 'NUMERIC'
        );

        $query->set('meta_query', array($status_in));
    }
}


// để cho nhẹ code, chỉ chạy chức năng tương ứng với request
if (strpos($_SERVER['REQUEST_URI'], '/edit.php') !== false) {
    // cho quảng cáo
    if (strpos($_SERVER['REQUEST_URI'], 'post_type=ads') !== false) {
        $post_type = 'ads';

        add_filter('manage_' . $post_type . '_posts_columns', 'eb_add_ads_column_head');
        add_filter('manage_' . $post_type . '_posts_custom_column', 'eb_run_post_column_content', 10, 2);
        add_filter('pre_get_posts', 'WGR_admin_load_ads_by_status');
    }
    // cho blog
    else if (strpos($_SERVER['REQUEST_URI'], 'post_type=blog') !== false) {
        $post_type = 'blog';

        add_filter('manage_' . $post_type . '_posts_columns', 'eb_add_blog_column_head');
        add_filter('manage_' . $post_type . '_posts_custom_column', 'eb_run_post_column_content', 10, 2);
    }
    // cho post
    else {
        $post_type = 'post';

        add_filter('manage_' . $post_type . '_posts_columns', 'eb_add_post_column_head');
        add_filter('manage_' . $post_type . '_posts_custom_column', 'eb_run_post_column_content', 10, 2);
    }
}


/*
function eb_custom_dashboard_widgets() {
	global $wp_meta_boxes;
	
	wp_add_dashboard_widget('eb_custom_help_widget', 'EchBay Theme Support', 'eb_custom_dashboard_help');
}

function eb_custom_dashboard_help() {
	
}

add_filter('wp_dashboard_setup', 'eb_custom_dashboard_widgets');
*/


/**
 * Loại bỏ tiếng Việt có dấu ở trong tên của file upload lên
 */
//$global_file_name_after_upload = array();

function __eb_sanitize_file_name($filename)
{
    //	global $global_file_name_after_upload;

    $a = _eb_non_mark_seo($filename);

    //	$global_file_name_after_upload[] = $a;

    //
    /*
	$arr = wp_upload_dir();
//	print_r( $arr );
	
	// kiểm tra nếu có file rồi -> đổi tên file luôn
	$path = $arr['path'] . '/' . $a;
	if ( is_file( $path ) ) {
		$arr_file = explode( '.', $a );
		$file_type = $arr_file[ count($arr_file) - 1 ];
		for ( $i = 0; $i < 50; $i++ ) {
			$a2 = $arr_file;
			$a2[ count( $a2 ) - 1 ] = '-' . $i . '.' . $file_type;
			$a2 = implode( '.', $a2 );
			if ( ! is_file( $a2 ) ) {
				$a = $a2;
				break;
			}
		}
	}
	*/

    //
    //	_eb_create_file( $arr['path'] . '/z.txt', $a . "\n", 1 );

    return $a;
}

add_filter('sanitize_file_name', '__eb_sanitize_file_name', 10, 1);


//
/*
function EBE_resizeafter_upload_media($attachment_ID) {          
}

add_filter("add_attachment", 'EBE_resizeafter_upload_media');
*/


// cập nhật lại rule mới cho phân nhóm khi người dùng vào sửa nhóm
if ($__cf_row['cf_remove_category_base'] == 1) {
    if (
        strpos($_SERVER['REQUEST_URI'], '/term.php?taxonomy=category') !== false ||
        strpos($_SERVER['REQUEST_URI'], '/edit-tags.php?taxonomy=category') !== false
    ) {
        add_filter('shutdown', 'flush_rewrite_rules');
        //		flush_rewrite_rules();
    }
}


// Backup bài viết dưới dạng xml trước khi xóa hẳn
// https://codex.wordpress.org/Plugin_API/Action_Reference/before_delete_post
add_filter('before_delete_post', 'WGR_backup_post_before_delete');

function WGR_backup_post_before_delete($postid)
{
    return WGR_save_post_xml($postid);
}


//
include EB_THEME_CORE . 'custom/admin/create-echbay-table.php';
if ($__cf_row['cf_on_off_auto_update_wp'] != 1) {
    include EB_THEME_CORE . 'custom/admin/disable-update.php';
}
include EB_THEME_PLUGIN_INDEX . 'cronjob/auto-clean.php';
