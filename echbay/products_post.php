<?php


//
echo '<link rel="stylesheet" href="' . web_link . EB_DIR_CONTENT . '/echbaydotcom/css/products_post.css?v=' . filemtime( EB_THEME_PLUGIN_INDEX . 'css/products_post.css' ) . '" type="text/css" media="all" />' . "\n";


//
$by_cat_id = isset( $_GET[ 'by_cat_id' ] ) ? ( int )$_GET[ 'by_cat_id' ] : 0;
$by_option_id = isset( $_GET[ 'by_option_id' ] ) ? ( int )$_GET[ 'by_option_id' ] : 0;


// tham khảo custom query: https://codex.wordpress.org/Displaying_Posts_Using_a_Custom_Select_Query

// theo định dạng post
$strFilter = " `" . wp_posts . "`.post_type = '" . $by_post_type . "' ";


$joinFilter = "";
$strAjaxLink = '';
$strExportLink = '';

$strLinkPager .= '&by_post_type=' . $by_post_type;
$strAjaxLink .= '&by_post_type=' . $by_post_type;


// theo trạng thái post -> mặc định chỉ lấy publish
$by_post_status = isset( $_GET[ 'by_post_status' ] ) ? $_GET[ 'by_post_status' ] : 'publish';
$link_for_post_filter = '';

// lấy các post_status phổ biến
if ( $by_post_status == '' || $by_post_status == 'all' ) {
    $strFilter .= " AND ( `" . wp_posts . "`.post_status = 'publish' OR `" . wp_posts . "`.post_status = 'pending' OR `" . wp_posts . "`.post_status = 'draft' ) ";
} else {
    $strFilter .= " AND `" . wp_posts . "`.post_status = '" . $by_post_status . "' ";
    $strLinkPager .= '&by_post_status=' . $by_post_status;
    $strAjaxLink .= '&by_post_status=' . $by_post_status;
    $link_for_post_filter = '&by_post_status=' . $by_post_status;
    $strExportLink .= '&by_post_status=' . $by_post_status;
}


$cats_type = ( $by_post_type == 'blog' ) ? 'blogs' : 'category';


//
if ( $by_cat_id > 0 ) {

    //
    //	echo $by_cat_id . '<br>';

    $strLinkPager .= '&by_cat_id=' . $by_cat_id;
    $strAjaxLink .= '&by_cat_id=' . $by_cat_id;

    //
    $arrs_cats = array(
        'taxonomy' => $cats_type,
        'hide_empty' => 0,
        'parent' => $by_cat_id
    );

    $arrs_cats = get_categories( $arrs_cats );
    //	print_r( $arrs_cats );

    $by_child_cat_id = '';
    if ( !empty( $arrs_cats ) ) {
        foreach ( $arrs_cats as $v ) {
            $by_child_cat_id .= ',' . $v->term_id;
        }
        //		echo $by_child_cat_id . '<br>';
    }


    // câu lệnh lọc theo taxonomy
    $strFilter .= " AND `" . $wpdb->term_taxonomy . "`.taxonomy = '" . $cats_type . "'
		AND `" . $wpdb->term_taxonomy . "`.term_id IN (" . $by_cat_id . $by_child_cat_id . ") ";

    // câu lệnh jion các bảng lại với nhau
    $joinFilter = " LEFT JOIN `" . $wpdb->term_relationships . "` ON ( `" . wp_posts . "`.ID = `" . $wpdb->term_relationships . "`.object_id)
		LEFT JOIN `" . $wpdb->term_taxonomy . "` ON ( `" . $wpdb->term_relationships . "`.term_taxonomy_id = `" . $wpdb->term_taxonomy . "`.term_taxonomy_id ) ";
    //	$joinFilter = ", `" . $wpdb->term_taxonomy . "`, `" . $wpdb->term_relationships . "` ";

}
//echo $strFilter . '<br>' . "\n";
//echo $joinFilter . '<br>' . "\n";


//
if ( $by_option_id > 0 ) {

    //
    //	echo $by_option_id . '<br>';

    $strLinkPager .= '&by_option_id=' . $by_option_id;
    $strAjaxLink .= '&by_option_id=' . $by_option_id;

    //
    $arrs_cats = array(
        'taxonomy' => 'post_options',
        'hide_empty' => 0,
        'parent' => $by_option_id
    );

    $arrs_cats = get_categories( $arrs_cats );
    //	print_r( $arrs_cats );

    $by_child_cat_id = '';
    if ( !empty( $arrs_cats ) ) {
        foreach ( $arrs_cats as $v ) {
            $by_child_cat_id .= ',' . $v->term_id;
        }
        //		echo $by_child_cat_id . '<br>';
    }


    // câu lệnh lọc theo taxonomy
    $strFilter .= " AND `" . $wpdb->term_taxonomy . "`.taxonomy = 'post_options'
		AND `" . $wpdb->term_taxonomy . "`.term_id IN (" . $by_option_id . $by_child_cat_id . ") ";

    // câu lệnh jion các bảng lại với nhau
    $joinFilter = " LEFT JOIN `" . $wpdb->term_relationships . "` ON ( `" . wp_posts . "`.ID = `" . $wpdb->term_relationships . "`.object_id)
		LEFT JOIN `" . $wpdb->term_taxonomy . "` ON ( `" . $wpdb->term_relationships . "`.term_taxonomy_id = `" . $wpdb->term_taxonomy . "`.term_taxonomy_id ) ";
    //	$joinFilter = ", `" . $wpdb->term_taxonomy . "`, `" . $wpdb->term_relationships . "` ";

}
//echo $strFilter . '<br>' . "\n";
//echo $joinFilter . '<br>' . "\n";


// hỗ trợ tìm kiếm sản phẩm/ blog
$by_search_key = isset( $_GET[ 'q' ] ) ? trim( $_GET[ 'q' ] ) : '';

if ( $by_search_key != '' ) {

    $by_search_key = urldecode( $by_search_key );

    if ( strlen( $by_search_key ) > 2 ) {

        $by_slug_key = _eb_non_mark_seo( urldecode( $by_search_key ) );

        $strFilter .= " AND ( `" . wp_posts . "`.post_name LIKE '%{$by_slug_key}%' OR `" . wp_posts . "`.post_title LIKE '%{$by_search_key}%' ) ";

    }

}


// lọc theo trạng thái post
// tam khảo bản dịch tại: wp-admim/includes/meta-boxes.php
$arrs_by_post_status = array(
    'publish' => __( 'Published' ),
    'pending' => __( 'Pending Review' ),
    'draft' => __( 'Draft' ),
    'private' => __( 'Privately Published' ),
    'future' => __( 'Scheduled' ),
    'all' => 'Tất cả'
);

echo '<ul class="cf admin-products_post-category"><li><span>Trạng thái sản phẩm: </span></li>';

foreach ( $arrs_by_post_status as $k => $v ) {
    $sl = '';

    if ( $by_post_status == '' && $k == 0 ) {
        $sl = 'bold redcolor';
    } else if ( $k == $by_post_status ) {
        $sl = 'bold redcolor';
    }

    //
    echo '<li><a href="' . admin_link . 'admin.php?page=eb-products&by_post_type=' . $by_post_type . '&by_post_status=' . $k . '" class="' . $sl . '">' . $v . '</a></li>';
}

echo '</ul>';


//
$arrs_cats = array(
    'taxonomy' => $cats_type,
    'hide_empty' => 0,
    'parent' => 0
);

//
$arrs_cats = get_categories( $arrs_cats );
//print_r( $arrs_cats );

echo '<ul class="cf admin-products_post-category">
	<li><span>' . ( $cats_type == 'blogs' ? 'Danh mục tin tức' : 'Chuyên mục sản phẩm' ) . ': </span></li>
	<li><a href="' . admin_link . 'admin.php?page=eb-products&by_post_type=' . $by_post_type . $link_for_post_filter . '" class="bold">Tất cả</a></li>';

foreach ( $arrs_cats as $v ) {
    $sl = '';
    if ( $v->term_id == $by_cat_id ) {
        $sl = 'bold redcolor';
    }

    //
    echo '<li><a href="' . admin_link . 'admin.php?page=eb-products&by_post_type=' . $by_post_type . $link_for_post_filter . '&by_cat_id=' . $v->term_id . '" class="' . $sl . '">' . $v->name . ' (' . $v->count . ')</a>';


    // lấy nhóm con thuộc nhóm này -> lọc thêm nhóm cấp 2
    $arrs_sub_cats = array(
        'taxonomy' => $cats_type,
        'hide_empty' => 0,
        'parent' => $v->term_id
    );

    //
    $arrs_sub_cats = get_categories( $arrs_sub_cats );
    //	print_r( $arrs_sub_cats );
    if ( !empty( $arrs_sub_cats ) ) {
        echo '<ul class="sub-menu cf">';

        foreach ( $arrs_sub_cats as $v2 ) {
            $sl = '';
            if ( $v2->term_id == $by_cat_id ) {
                $sl = 'bold';
            }

            //
            echo '<li><a href="' . admin_link . 'admin.php?page=eb-products&by_post_type=' . $by_post_type . $link_for_post_filter . '&by_cat_id=' . $v2->term_id . '" class="' . $sl . '">' . $v2->name . ' (' . $v2->count . ')</a>';
        }

        echo '</ul>';
    }

    //
    echo '</li>';
}
echo '</ul>';


// Thêm phần lọc theo thông số sản phẩm
if (
    $by_post_type == 'post' ||
    $by_post_type == 'ads'
) {
    $arrs_cats = array(
        'taxonomy' => 'post_options',
        'hide_empty' => 0,
        'parent' => 0
    );

    //
    $arrs_cats = get_categories( $arrs_cats );
    //	print_r( $arrs_cats );

    echo '<ul class="cf admin-products_post-category">
		<li><span>Thông số khác: </span></li>
		<li><a href="' . admin_link . 'admin.php?page=eb-products&by_post_type=' . $by_post_type . $link_for_post_filter . '" class="bold">Tất cả</a></li>';

    foreach ( $arrs_cats as $v ) {
        $sl = '';
        if ( $v->term_id == $by_option_id ) {
            $sl = 'bold redcolor';
        }

        //
        echo '<li><a href="' . admin_link . 'admin.php?page=eb-products&by_post_type=' . $by_post_type . $link_for_post_filter . '&by_option_id=' . $v->term_id . '" class="' . $sl . '">' . $v->name . ' (' . $v->count . ')</a>';


        // lấy nhóm con thuộc nhóm này -> lọc thêm nhóm cấp 2
        $arrs_sub_cats = array(
            'taxonomy' => 'post_options',
            'hide_empty' => 0,
            'parent' => $v->term_id
        );

        //
        $arrs_sub_cats = get_categories( $arrs_sub_cats );
        //	print_r( $arrs_sub_cats );
        if ( !empty( $arrs_sub_cats ) ) {
            echo '<ul class="sub-menu cf">';

            foreach ( $arrs_sub_cats as $v2 ) {
                $sl = '';
                if ( $v2->term_id == $by_option_id ) {
                    $sl = 'bold';
                }

                //
                echo '<li><a href="' . admin_link . 'admin.php?page=eb-products&by_post_type=' . $by_post_type . $link_for_post_filter . '&by_option_id=' . $v2->term_id . '" class="' . $sl . '">' . $v2->name . ' (' . $v2->count . ')</a>';
            }

            echo '</ul>';
        }

        //
        echo '</li>';
    }
    echo '</ul>';
}


//
/*
if ( isset( $_GET['tab'] ) ) {
	$status_by = (int)$_GET['tab'];
	
	$strFilter .= " AND order_status = " . $status_by;
	
	$strLinkPager .= '&tab=' . $status_by;
}
*/

// tổng số đơn hàng
$totalThread = _eb_c( "SELECT COUNT(ID) AS c
	FROM
		`" . wp_posts . "`
		" . $joinFilter . "
	WHERE
		" . $strFilter );
//echo $strFilter . '<br>' . "\n";
//echo $totalThread . '<br>' . "\n";


// phân trang bình thường
$totalPage = ceil( $totalThread / $threadInPage );
if ( $totalPage < 1 ) {
    $totalPage = 1;
}
//echo $totalPage . '<br>' . "\n";
if ( $trang > $totalPage ) {
    $trang = $totalPage;
} else if ( $trang < 1 ) {
    $trang = 1;
}
//echo $trang . '<br>' . "\n";
$offset = ( $trang - 1 ) * $threadInPage;
//echo $offset . '<br>' . "\n";

//
$strAjaxLink .= '&trang=' . $trang;


?>
<div class="class-for-<?php echo $by_post_type; ?>">
    <div class="quick-show2-if-post-xoa">
        <div class="orgcolor">* Bạn có thể chọn và lọc sản phẩm theo danh mục ở trên, sau đó bấm chọn nút Export sản phẩm ở bên để có danh sách sản phẩm phù hợp giúp cho việc chạy quảng cáo hiệu quả hơn.</div>
        <br>
        <div class="text-right cf div-inline-block">
            <?php

            $arr_button_export = array(
                'csv' => 'for CSV',
                'google' => 'for Google',
                'facebook' => 'for Facebook',
                'ia_facebook' => 'for IA Facebook',
                'wgr_xm_backup' => 'XML backup', // các bài viết bị xóa có thể được tìm thấy và khôi phục lại tại đây
                'wgr_xm_backup&trash=1' => 'XML trash backup', // các bài viết bị xóa có thể được tìm thấy và khôi phục lại tại đây
                'echbaydotcom' => 'from Echbaydotcom', // backup các bài viết từ echbaydotcom sang echbaydotcom
                'woo' => 'from Woocommerce', // backup các bài viết từ woocommerce sang echbaydotcom
            );

            $export_token = _eb_mdnam( $_SERVER[ 'HTTP_HOST' ] );

            foreach ( $arr_button_export as $k => $v ) {
                echo '<div><a href="' . web_link . 'eb_export_products?export_type=' . $k . '&token=' . $export_token . '&by_cat_id=' . $by_cat_id . '&cats_type=' . $cats_type . '&by_post_type=' . $by_post_type . '&user_export=' . mtv_id . '&trang=' . $trang . '&limit=' . $threadInPage . $strExportLink . '" target="_blank" class="rf d-block blue-button whitecolor">' . $v . '</a></div> ';
            }

            ?>
        </div>
        <br>
        <div class="thread-edit-tools">
            <div class="cf">
                <div class="lf f50">
                    <input type="checkbox" id="thread-all-checkbox" value="0" class="thread-multi-checkbox" />
                    <button type="button" class="small bold click-show-tools">Công cụ chỉnh sửa nhiều và nhanh <i class="fa fa-caret-down"></i></button>
                </div>
                <div align="right" class="lf f50"> Số sản phẩm trên mỗi trang
                    <select id="change_set_thread_show_in_page" style="padding:3px;">
                    </select>
                </div>
            </div>
            <div class="show-if-click-tools thread-multi-edit d-none">
                <form name="frm_multi_edit_post" method="post" action="process/?set_module=multi_edit_post" target="target_eb_iframe">
                    <div class="d-none">
                        <textarea name="t_list_id"></textarea>
                        <input type="text" name="actions_for" value="" />
                        <input type="text" name="actions_id_for" value="0" />
                        <input type="text" name="by_post_type" value="<?php echo $by_post_type; ?>" />
                        <input type="text" name="by_cat_id" value="<?php echo $by_cat_id; ?>" />
                        <input type="text" name="current_page" value="<?php echo $trang; ?>" />
                        <input type="submit" value="SB" />
                    </div>
                    <div class="titleCSS bold bborder">Chỉnh sửa nhiều sản phẩm</div>
                    <br>
                    <div class="bborder">
                        <div class="cf">
                            <div class="lf f20 bold">Giảm giá (%)</div>
                            <div class="lf f60 thread-multi-input">
                                <input type="text" name="t_giamgia" value="" placeholder="Phần trăm giảm giá" data-for="giamgia" maxlength="2" class="focus-set-actions-for s" />
                                <p class="description">Nhập phần trăm muốn giảm cho các sản phẩm đã được chọn, hệ thống sẽ cập nhật Giá mới theo Giá cũ của chính sản phẩm đó. Ví dụ: <strong>Giá cũ</strong>: 10,000, nhập vào 30, <strong>Giá mới</strong> sẽ được thiết lập là: 7,000.</p>
                            </div>
                            <div class="lf f20">
                                <button type="button" data-for="giamgia" class="click-set-actions-for">Cập nhật</button>
                            </div>
                        </div>
                        <br>
                    </div>
                    <br>
                    <!--
					<div class="bborder">
						<div class="cf">
							<div class="lf f20 bold">Trạng thái</div>
							<div class="lf f60">{tmp.trv_str_trangthai}</div>
							<div class="lf f20">
								<button type="button" data-for="status" class="click-set-actions-for">Cập nhật</button>
							</div>
						</div>
						<br>
					</div>
					<br>
					<div class="bborder">
						<div class="cf">
							<div class="lf f20 bold">Phân nhóm</div>
							<div class="lf f60 cf">
								<div class="lf f50">
									<div id="oiAnt"></div>
								</div>
							</div>
							<div class="lf f20">
								<button type="button" data-for="category" class="click-set-actions-for">Cập nhật</button>
							</div>
						</div>
						<br>
					</div>
					<br>
					-->
                    <div class="bborder">
                        <div class="cf">
                            <div class="lf f20 bold">Ngày hết hạn</div>
                            <div class="lf f60 thread-multi-input">
                                <input type="text" name="t_ngayhethan" value="" placeholder="Năm/Tháng/Ngày" data-for="enddate" maxlength="10" autocomplete="off" class="focus-set-actions-for thread-list-ngayhethan" />
                            </div>
                            <div class="lf f20">
                                <button type="button" data-for="enddate" class="click-set-actions-for">Cập nhật</button>
                            </div>
                        </div>
                        <br>
                    </div>
                    <br>
                    <div class="bborder">
                        <div class="cf">
                            <div class="lf f20 bold">Số thứ tự</div>
                            <div class="lf f60 thread-multi-input">
                                <input type="text" name="t_stt" value="0" placeholder="Số thứ tự" data-for="stt" maxlength="5" class="focus-set-actions-for" />
                            </div>
                            <div class="lf f20">
                                <button type="button" data-for="stt" class="click-set-actions-for">Cập nhật</button>
                            </div>
                        </div>
                        <br>
                    </div>
                    <br>
                    <div class="thread_list_edit_options"></div>
                </form>
            </div>
        </div>
    </div>
    <!-- chức năng tìm kiếm qua ajax -->
    <div class="w99 quick-show-if-postblog">
        <form name="frm_products_post_search" method="get" action="javascript:;" onSubmit="return auto_submit_products_post_search();">
            <input type="hidden" name="page" value="eb-products">
            <input type="hidden" name="by_post_type" value="<?php echo $by_post_type; ?>">
            <div class="products-post-search">
                <input type="search" name="q" value="<?php echo $by_search_key; ?>" placeholder="Tìm kiếm theo tên sản phẩm" aria-required="true" required />
                <button type="submit" class="cur"><i class="fas fa-search"></i></button>
            </div>
        </form>
        <br>
    </div>
</div>
<div id="frm_quick_edit_price" class="hide-if-press-esc">
    <div class="edit-price-padding">
        <div class="text-right"><i class="fa fa-close cur" onClick="$('#frm_quick_edit_price').fadeOut();"></i></div>
        <form name="frm_quick_edit_price" method="get" action="javascript:;" onSubmit="return WGR_check_quick_edit_price();">
            <input type="hidden" name="t_product_id" value="0">
            <input type="hidden" name="data_ajax" value="">
            <div class="cf">
                <div class="lf f30">Giá cũ</div>
                <div class="lf f70">
                    <input type="text" name="t_old_price" id="quick_edit_old_price" value="">
                </div>
            </div>
            <div class="cf">
                <div class="lf f30">Giá mới</div>
                <div class="lf f70">
                    <input type="text" name="t_new_price" id="quick_edit_new_price" value="">
                </div>
            </div>
            <div class="small">* Mẹo:<br>
                Ví dụ giá cũ đang là 1,000,000đ, trong ô giá mới ta có các cách nhập như sau:<br>
                [<strong>70%</strong>] -&gt; hệ thống tự tính toán giá mới bằng 70% giá cũ -&gt; 700,000đ.<br>
                [<strong>-70%</strong>] -&gt; hệ thống tự tính toán giá mới bằng giá cũ trừ đi 70% -&gt; 300,000đ.<br>
                [<strong>600k</strong>] -&gt; hệ thống tự tính toán giá mới bằng 600 nhân với 1000 -&gt; 600,000đ.<br>
                * Giá cũ nhỏ hơn hoặc bằng Giá mới -&gt; Giá cũ sẽ được set bằng 0.<br>
                * Giá mới nhỏ hơn hoặc bằng 0 -&gt; Giá mới sẽ được set bằng Giá cũ, Giá cũ sẽ được set về 0.</div>
            <br>
            <div class="cf">
                <div class="lf f30">&nbsp;</div>
                <div class="lf f70">
                    <button type="submit" class="button button-primary">Cập nhật</button>
                </div>
            </div>
        </form>
    </div>
</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="table-list class-for-post-type class-for-<?php echo $by_post_type; ?>">
    <tr class="table-list-title">
        <td width="5%">&nbsp;</td>
        <td width="10%">ID</td>
        <td width="8%">Ảnh</td>
        <td>Sản phẩm/ Giá cũ/ Giá mới</td>
        <td title="Dữ liệu được sắp xếp theo STT giảm dần" width="8%">STT <i class="fa fa-caret-down"></i></td>
        <td width="10%">Công cụ</td>
        <td width="14%">Ngày Đăng/ Cập nhật</td>
    </tr>
    <?php

    if ( $totalThread > 0 ) {
        //	echo $strFilter . ' aaaaaaaaaaaaaaaa<br>' . "\n"; echo $offset . '<br>' . "\n"; echo $threadInPage . '<br>' . "\n";

        //
        $sql = "SELECT *
        FROM
            `" . wp_posts . "`
            " . $joinFilter . "
        WHERE
            " . $strFilter . "
        ORDER BY
            `" . wp_posts . "`.menu_order DESC
        LIMIT " . $offset . ", " . $threadInPage;
        //	echo $sql; 
        $sql = _eb_q( $sql );
        //	print_r( $sql ); exit();

        //
        $arr_all_stick = get_option( 'sticky_posts' );

        //
        foreach ( $sql as $o ) {

            //		print_r( $o ); exit();

            $trv_id = $o->ID;
            $trv_link = web_link . '?p=' . $trv_id;
            $trv_tieude = $o->post_title;

            $trv_img = _eb_get_post_img( $o->ID, 'thumbnail' );
            $view_by_group = '';
            $trv_stt = $o->menu_order;
            $trv_trangthai = $o->post_status == 'publish' ? 1 : 0;
            //		$strLinkAjaxl = '&post_id=' . $trv_id . '&by_post_type=' . $by_post_type;
            $strLinkAjaxl = '&post_id=' . $trv_id . $strAjaxLink;

            //
            $current_sticky = 0;
            $comment_status = $o->comment_status;
            $ping_status = $o->ping_status;

            //
            $set_noindex = 0;
            $chinh_hang = 0;

            //
            $trv_giaban = 0;
            $trv_giamoi = 0;

            //
            $str_edit_custom_meta = '';

            // các tính năng chỉ có ở post
            if ( $o->post_type == 'post' ) {
                if ( in_array( $o->ID, $arr_all_stick ) ) {
                    //			if ( is_sticky( $o->ID ) ) {
                    $current_sticky = 1;
                }
                $chinh_hang = _eb_get_post_object( $o->ID, '_eb_product_chinhhang', 0 );

                // với phần giá cả -> sẽ lấy giá của woo nếu có
                $trv_giaban = _eb_float_only( _eb_get_post_object( $o->ID, '_eb_product_oldprice' ) );
                /*
                if ( $trv_giaban == 0 ) {
                	$trv_giaban = _eb_float_only( _eb_get_post_object( $o->ID, '_regular_price', 0 ) );
                }
                */
                $trv_giamoi = _eb_float_only( _eb_get_post_object( $o->ID, '_eb_product_price' ) );
                /*
                if ( $trv_giamoi == 0 ) {
                	$trv_giamoi = _eb_float_only( _eb_get_post_object( $o->ID, '_price', 0 ) );
                	
                	// cập nhật giá mới từ giá của woo
                	WGR_update_meta_post( $o->ID, '_eb_product_price', $trv_giamoi );
                }
                */

                //
                if ( $trv_giaban > 0 && $trv_giaban == $trv_giamoi ) {
                    delete_post_meta( $o->ID, '_eb_product_oldprice' );
                }

                //
                global $arr_eb_product_status;
                global $arr_eb_product_gender;

                $arr_edit_custom_meta = array(
                    '_eb_product_status' => 'status',
                    '_eb_product_gender' => 'gender',
                    '_eb_product_color' => 'text',
                    '_eb_product_sku' => 'text',
                    '_eb_product_leech_sku' => 'text',
                    '_eb_product_buyer' => 'number',
                    '_eb_product_quantity' => 'number'
                    //				'_eb_product_ngayhethan' => 'text'
                );

                foreach ( $arr_edit_custom_meta as $k => $v ) {
                    //
                    $data_attr = 'data-ajax="' . $strLinkAjaxl . '&custom_meta=' . $k . '" id="' . $trv_id . $k . '" class="' . $k . ' n change-update-custom-meta"';

                    //
                    $str_edit_custom_meta .= '
				<div class="cf">
					<div class="lf f30">' . WGR_admin_lang( $k ) . '</div>
					<div class="lf f70">';

                    // select
                    if ( $v == 'status' ) {
                        $str_edit_custom_meta .= '<select data-value="' . _eb_get_post_object( $trv_id, $k ) . '" ' . $data_attr . '>';

                        foreach ( $arr_eb_product_status as $k2 => $v2 ) {
                            $str_edit_custom_meta .= '<option value="' . $k2 . '">' . $v2 . '</option>';
                        }

                        $str_edit_custom_meta .= '</select>';
                    } else if ( $v == 'gender' ) {
                        $str_edit_custom_meta .= '<select data-value="' . _eb_get_post_object( $trv_id, $k ) . '" ' . $data_attr . '>';

                        foreach ( $arr_eb_product_gender as $k2 => $v2 ) {
                            $str_edit_custom_meta .= '<option value="' . $k2 . '">' . $v2 . '</option>';
                        }

                        $str_edit_custom_meta .= '</select>';
                    }
                    // input
                    else if ( $v != '' ) {
                        $str_edit_custom_meta .= '<input type="' . $v . '" value="' . _eb_get_post_object( $trv_id, $k ) . '" ' . $data_attr . ' />';
                    } else {}

                    //
                    $str_edit_custom_meta .= '</div>
				</div>';
                }
            }


            // tính điểm SEO nếu đang dùng công cụ SEO của EchBay
            $seo_color = '';

            // các tính năng chỉ có ở post hoặc blog
            if ( $o->post_type == 'post' || $o->post_type == 'blog' ) {
                $set_noindex = _eb_get_post_object( $o->ID, '_eb_product_noindex', 0 );

                //			echo cf_on_off_echbay_seo;
                if ( cf_on_off_echbay_seo == 1 ) {
                    $seo_score = 0;
                    $seo_class_score = '';

                    //
                    //				echo _eb_get_post_object( $o->ID, '_eb_product_title', $o->post_title ) . '<br>';

                    // check title
                    $a = strlen( _eb_get_post_object( $o->ID, '_eb_product_title', $o->post_title ) );
                    if ( $a > 10 && $a < 70 ) {
                        $seo_score++;
                        $seo_class_score .= '1';
                    } else {
                        $seo_class_score .= '0';
                    }

                    // check description
                    $a = strlen( strip_tags( _eb_get_post_object( $o->ID, '_eb_product_description', $o->post_excerpt ) ) );
                    if ( $a > 160 && $a < 300 ) {
                        $seo_score++;
                        $seo_class_score .= '1';
                    } else {
                        $seo_class_score .= '0';
                    }

                    // check content
                    $a = strlen( strip_tags( $o->post_content ) );
                    if ( $a > 500 ) {
                        $seo_score++;
                        $seo_class_score .= '1';
                    } else {
                        $seo_class_score .= '0';
                    }

                    // mặc định thì báo đỏ
                    $seo_color = 'redcolor';
                    //
                    if ( $seo_score > 2 ) {
                        $seo_color = 'greencolor';
                    }
                    //
                    else if ( $seo_score > 1 ) {
                        $seo_color = 'bluecolor';
                    }
                    //
                    else if ( $seo_score > 0 ) {
                        $seo_color = 'orgcolor';
                    }
                    $seo_color = '<i data-id="' . $o->ID . '" class="fa fa-dot-circle wgr-icons cur click-open-quick-edit-seo _' . $seo_class_score . ' ' . $seo_color . '"></i>';

                    //				echo $seo_color . '<br>';
                }
            }

            //
            echo '
<tr>
	<td class="text-center"><input type="checkbox" name="thread-checkbox" value="' . $trv_id . '" class="eb-uix-thread-checkbox thread-multi-checkbox" /></td>
	<td><a href="' . $trv_link . '" target="_blank">' . $trv_id . ' <i class="fa fa-eye"></i></a></td>
	<td><a href="' . $trv_link . '" target="_blank" class="d-block admin-thread-avt" style="background-image:url(\'' . $trv_img . '\');">&nbsp;</a></td>
	<td>
		<div><a title="' . $trv_tieude . '" href="' . admin_link . 'post.php?post=' . $trv_id . '&action=edit" target="_blank"><strong>' . $trv_tieude . '</strong> <i title="Sửa" class="fa fa-edit greencolor"></i></a></div>
		
		<div class="quick-show-if-post">Mã sản phẩm: <strong class="upper">' . _eb_get_post_object( $trv_id, '_eb_product_sku' ) . '</strong> | <span data-id="' . $trv_id . '" data-old-price="' . $trv_giaban . '"  data-new-price="' . $trv_giamoi . '" data-ajax="' . $strLinkAjaxl . '" class="click-quick-edit-price cur">Giá: <span class="graycolor ebe-currency ebe-currency-format">' . $trv_giaban . '</span>/ <strong class="ebe-currency ebe-currency-format">' . $trv_giamoi . '</strong> <i title="Sửa" class="fa fa-edit greencolor"></i></span></div>
		
		<div>' . $view_by_group . '</div>
		
		<div class="show-if-quick-edit d-none">
			' . $str_edit_custom_meta . '
		</div>
	</td>
	<td><input type="number" value="' . $trv_stt . '" data-ajax="' . $strLinkAjaxl . '&t=up&stt=" id="' . $trv_id . 'menu_order" class="s change-update-new-stt" /></td>
	<td>
		<div class="text-center">
			<i title="Up to TOP" data-ajax="' . $strLinkAjaxl . '&t=auto&stt=' . $trv_stt . '" class="fa fa-refresh wgr-icons cur click-order-thread"></i>
			
			<i title="Up" data-ajax="' . $strLinkAjaxl . '&t=up&stt=' . $trv_stt . '" class="fa fa-arrow-circle-up wgr-icons cur click-order-thread"></i>
			
			<i title="Down" data-ajax="' . $strLinkAjaxl . '&t=down&stt=' . $trv_stt . '" class="fa fa-arrow-circle-down wgr-icons cur click-order-thread"></i>
			
			<i title="Set sticky" data-val="' . $current_sticky . '" data-ajax="' . $strLinkAjaxl . '&t=sticky&current_sticky=' . $current_sticky . '" class="fa fa-star wgr-icons cur click-order-thread"></i>
			
			<i title="Toggle comment status" data-val="' . $comment_status . '" data-ajax="' . $strLinkAjaxl . '&t=comment_status&comment_status=' . $comment_status . '" class="fa fa-comments wgr-icons cur click-order-thread"></i>
			
			<i title="Toggle ping status" data-val="' . $ping_status . '" data-ajax="' . $strLinkAjaxl . '&t=ping_status&ping_status=' . $ping_status . '" class="fa fa-link wgr-icons cur click-order-thread"></i>
			
			<i title="Toggle status" data-ajax="' . $strLinkAjaxl . '&t=status&toggle_status=' . $trv_trangthai . '" class="wgr-icons cur click-order-thread fa ' . ( ( $trv_trangthai > 0 ) ? 'fa-unlock' : 'fa-lock blackcolor' ) . '"></i>
			
			<i title="Set noindex" data-val="' . $set_noindex . '" data-ajax="' . $strLinkAjaxl . '&t=set_noindex&set_noindex=' . $set_noindex . '" class="fa fa-paw wgr-icons cur click-order-thread"></i>
			
			<i title="Hàng chính hãng" data-val="' . $chinh_hang . '" data-ajax="' . $strLinkAjaxl . '&t=chinh_hang&chinh_hang=' . $chinh_hang . '" class="fa fa-diamond wgr-icons cur click-order-thread"></i>
			
			' . $seo_color . '
		</div>
	</td>
	<td class="text-center">' . date( $__cf_row[ 'cf_date_format' ] . ' ' . $__cf_row[ 'cf_time_format' ], strtotime( $o->post_date ) ) . '<br>' . date( $__cf_row[ 'cf_date_format' ] . ' ' . $__cf_row[ 'cf_time_format' ], strtotime( $o->post_modified ) ) . '</td>
</tr>';

        }

    }

    ?>
</table>
<?php


echo '<script type="text/javascript" src="' . web_link . EB_DIR_CONTENT . '/echbaydotcom/javascript/products_post.js?v=' . filemtime( EB_THEME_PLUGIN_INDEX . 'javascript/products_post.js' ) . '"></script>' . "\n";
