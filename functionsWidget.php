<?php


function WGR_widget_arr_default_home_hot( $new_arr = array() ) {
    // Giá trị mặc định
    $arr = array(
        'title' => 'EchBay Widget for product',
        'hide_widget_title' => 0,
        'custom_cat_link' => '',
        'dynamic_tag' => 'div',
        'dynamic_post_tag' => '',
        'description' => '',
        'get_full_content' => 0,
        'content_only' => 0,
        'off_img_max_width' => 0,
        'js_ptags' => 0,
        'show_content_excerpt' => 0,
        'sortby' => 'menu_order ID',
        'num_line' => '',
        'html_template' => 'home_hot.html',
        'html_node' => '',
        'max_width' => '',
        'post_number' => 5,
        'cat_ids' => 0,
        'cat_type' => 'category',
        'get_childs' => 0,
        'post_cloumn' => '',
        'hide_title' => 0,
        'hide_description' => 0,
        'show_post_content' => 0,
        'hide_info' => 0,
        // khi thuộc tính này kích hoạt -> ảnh sẽ được đặt làm slider
        'run_slider' => 0,
        'post_type' => 'post',
        // dành cho mục quảng cáo -> mở dưới dạng video youtube
        'open_youtube' => 0,
        // lấy các bài viết cùng nhóm
        'same_cat' => 0,
        // tự động lấy post type mới khi chức năng same_cat được kích hoạt
        'get_post_type' => 0,

        // Quan hệ liên kết (XFN) -> rel="nofollow"
        'rel_xfn' => '',
        // Mở liên kết trong 1 thẻ mới
        'open_target' => 0,

        'ads_eb_status' => 0,
        'post_eb_status' => 0,
        'custom_style' => '',
        'custom_id' => '',
        'custom_size' => '',
        // bỏ qua chế độ lọc trùng sản phẩm nếu được kích hoạt
        'post__not_in' => '',
        'page_id' => 0
    );

    // thay thế các giá trị mặc định
    foreach ( $new_arr as $k => $v ) {
        $arr[ $k ] = $v;
    }

    // Trả về kết quả
    return $arr;
}

function WGR_widget_home_hot( $instance ) {
    //	global $func;
    global $echbay_widget_i_set_home_product_bg;


    //
    //	$title = apply_filters ( 'widget_title', $instance ['title'] );
    $title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : '';
    $dynamic_tag = isset( $instance[ 'dynamic_tag' ] ) ? $instance[ 'dynamic_tag' ] : 'div';
    $dynamic_post_tag = isset( $instance[ 'dynamic_post_tag' ] ) ? $instance[ 'dynamic_post_tag' ] : '';
    $description = isset( $instance[ 'description' ] ) ? $instance[ 'description' ] : '';
    $post_number = isset( $instance[ 'post_number' ] ) ? $instance[ 'post_number' ] : 0;
    if ( $post_number == 0 )$post_number = 5;

    $sortby = isset( $instance[ 'sortby' ] ) ? $instance[ 'sortby' ] : '';
    $num_line = isset( $instance[ 'num_line' ] ) ? $instance[ 'num_line' ] : '';
    $cat_ids = isset( $instance[ 'cat_ids' ] ) ? $instance[ 'cat_ids' ] : 0;
    $cat_type = isset( $instance[ 'cat_type' ] ) ? $instance[ 'cat_type' ] : 'category';
    $post_eb_status = isset( $instance[ 'post_eb_status' ] ) ? $instance[ 'post_eb_status' ] : 0;

    $html_template = isset( $instance[ 'html_template' ] ) ? $instance[ 'html_template' ] : '';

    $max_width = isset( $instance[ 'max_width' ] ) ? $instance[ 'max_width' ] : '';
    $post_cloumn = isset( $instance[ 'post_cloumn' ] ) ? $instance[ 'post_cloumn' ] : '';
    $post_type = isset( $instance[ 'post_type' ] ) ? $instance[ 'post_type' ] : '';

    //	$html_node = isset( $instance ['html_node'] ) ? $instance ['html_node'] : '';
    //	$html_node = str_replace( '.html', '', $html_node );

    $custom_style = isset( $instance[ 'custom_style' ] ) ? $instance[ 'custom_style' ] : '';
    $custom_size = isset( $instance[ 'custom_size' ] ) ? $instance[ 'custom_size' ] : '';

    // ẩn các thuộc tính theo option
    $custom_style .= WGR_add_option_class_for_post_widget( $instance );


    //
    $___order = 'DESC';
    if ( $sortby == '' || $sortby == 'rand' ) {
        $___order = '';
    }

    //
    $args = array(
        'orderby' => $sortby,
        'order' => $___order,
    );


    //
    $str_home_hot = '';
    $home_hot_lnk = '';
    $home_hot_more = '';
    //	echo $cat_ids . '<br>';
    //	echo 'aaaaaaaaaaaaaaaaa<br>';

    // nếu có phân nhóm -> lấy theo phân nhóm
    if ( $cat_ids > 0 ) {
        $cat_type = WGR_get_taxonomy_name( $cat_ids, $cat_type );
        //		echo $cat_type . '<br>';

        // các sản phẩm trong nhóm con
        /*
		$arr_in = array();
		
		$sub_cat = get_categories( array(
			'parent' => $cat_ids
		) );
//		print_r( $sub_cat );
		if ( ! empty( $sub_cat ) ) {
			foreach ( $sub_cat as $k => $v ) {
				$arr_in[] = $v->term_id;
			}
		}
		
		if ( ! empty( $arr_in ) ) {
			$arr_in[] = $cat_ids;
			
			$args['category__in'] = $arr_in;
		}
		else {
			*/
        if ( $cat_type == 'category' ) {
            $args[ 'cat' ] = $cat_ids;
        } else {
            $args[ 'tax_query' ] = array(
                array(
                    'taxonomy' => $cat_type,
                    'field' => 'term_id',
                    'operator' => 'IN',
                    'terms' => array( $cat_ids )
                    //						'terms' => $terms_categories,
                )
            );
        }
        //		}

        //
        $home_hot_lnk = _eb_c_link( $cat_ids, $cat_type );

        // lấy thông tin phân nhóm luôn
        if ( $title == '' ) {
            $categories = get_term_by( 'id', $cat_ids, $cat_type );
            $title = $categories->name;
        }

        //
        $title = '<a href="' . $home_hot_lnk . '">' . $title . '</a>';
        //		$home_hot_more = '<' . $dynamic_tag . ' class="home-hot-more"><a href="' . $home_hot_lnk . '">Xem thêm <span>&raquo;</span></a></' . $dynamic_tag . '>';
        $home_hot_more = '<div class="home-hot-more"><a href="' . $home_hot_lnk . '">Xem thêm <span>&raquo;</span></a></div>';
    } else if ( $title == '' ) {
        $title = EBE_get_lang( 'home_hot' );
    } else {
        global $___eb_lang;

        //
        if ( isset( $___eb_lang[ eb_key_for_site_lang . $title ] ) ) {
            $title = $___eb_lang[ eb_key_for_site_lang . $title ];
        }
    }

    // tìm theo trạng thái
    if ( $post_eb_status > 0 ) {
        $args[ 'meta_key' ] = '_eb_product_status';
        $args[ 'meta_value' ] = $post_eb_status;
        $args[ 'compare' ] = '=';
        $args[ 'type' ] = 'NUMERIC';
    }
    $args[ 'ignore_sticky_posts' ] = 1;

    //
    $html_content = __eb_thread_template;
    // nếu có size riêng -> sử dụng size này
    if ( $custom_size != '' ) {
        $html_content = str_replace( '{tmp.cf_product_size}', $custom_size, $html_content );
    }
    if ( $dynamic_post_tag != '' ) {
        $html_content = EBE_dynamic_title_tag( $html_content, $dynamic_post_tag );
    }

    $str_home_hot = _eb_load_post( $post_number, $args, $html_content );


    //
    if ( $str_home_hot == '' ) {
        echo '<!-- ';
        print_r( $args );
        echo ' -->';

        $str_home_hot = '<li class="text-center"><em>post not found</em></li>';
    }


    //
    $html_template = _eb_widget_create_html_template( $html_template, 'home_hot' );


    //
    echo '<div class="' . $custom_style . '">';

    $arr_for_template = array(
        'tmp.dynamic_widget_tag' => $dynamic_tag,
        'tmp.max_width' => $max_width,
        'tmp.num_post_line' => $num_line,
        'tmp.home_hot_title' => $title,
        'tmp.home_hot_more' => $home_hot_more,
        'tmp.description' => $description,
        'tmp.home_hot' => $str_home_hot,
        //	) );
    );

    /*
    if ( $max_width != '' ) {
    	$arr_for_template['custom_blog_css'] = $max_width;
    }
    */
    //	print_r($arr_for_template);


    //	echo EBE_html_template( EBE_get_page_template( $html_template ), array(
    $show_content = WGR_show_home_hot( $arr_for_template, $html_template );

    /*
    if ( $dynamic_post_tag != '' ) {
    	$show_content = EBE_dynamic_title_tag( $show_content, $dynamic_post_tag );
    }
    */

    echo $show_content;

    echo '</div>';

}


// hiển thị phần home hot theo chuẩn nhất định
function WGR_show_home_hot( $arr, $tmp = 'home_hot' ) {
    //	global $__cf_row;

    //
    //	print_r($arr);

    // nạp html được truyền vào
    $html = EBE_html_template( EBE_get_page_template( $tmp ), $arr );

    //
    if ( !isset( $arr[ 'tmp.dynamic_widget_tag' ] ) ) {
        $arr[ 'tmp.dynamic_widget_tag' ] = 'div';
    }

    // tạo thẻ đóng
    $html = str_replace( 'dynamic_widget_tag>', $arr[ 'tmp.dynamic_widget_tag' ] . '>', $html );
    // tạo thẻ mở
    $html = str_replace( '<dynamic_widget_tag', '<' . $arr[ 'tmp.dynamic_widget_tag' ], $html );

    // các đoạn HTML mặc định cho về trống nếu chưa có
    $html = EBE_html_template( $html, array(
        'tmp.max_width' => '',
        'tmp.num_post_line' => '',
        'tmp.home_hot_title' => '',
        'tmp.home_hot_more' => '',
        'tmp.description' => '',
        'tmp.home_hot' => ''
    ) );

    //
    /*
    if ( $__cf_row['cf_replace_content'] != '' ) {
    	$html = WGR_replace_for_all_content( $__cf_row['cf_replace_content'], $html );
    }
    */

    //
    return $html;
}


// hiển thị phần home node theo chuẩn nhất định
function WGR_show_home_node( $arr, $custom_tag = '', $tmp = 'home_node' ) {

    // kiểm tra xem có custom tag không -> do bản cũ với mới khác nhau nên phải có đoạn này
    $custom_end_tag = '';
    if ( $custom_tag != '' ) {
        $custom_end_tag = '</' . $custom_tag . '>';
        $custom_tag = '<' . $custom_tag . '>';
    }

    // nạp html được truyền vào
    $html = EBE_html_template( EBE_get_page_template( $tmp ), $arr );

    // các đoạn HTML mặc định cho về trống nếu chưa có
    return EBE_html_template( $html, array(
        // thẻ H2, H3... cho phần tên danh mục
        'tmp.custom_tag' => $custom_tag,
        'tmp.custom_end_tag' => $custom_end_tag,

        // các thông số khác
        'tmp.num_post_line' => '',
    ) );
}

// lấy danh sách nhóm con
function WGR_get_home_node_sub_cat( $cat_ids, $custom_tag = '' ) {
    // danh sách nhóm cấp 2
    $arr_sub_cat = array(
        'parent' => $cat_ids,
    );
    $sub_cat = get_categories( $arr_sub_cat );
    //	print_r( $sub_cat );

    // kiểm tra xem có custom tag không -> do bản cũ với mới khác nhau nên phải có đoạn này
    $custom_end_tag = '';
    if ( $custom_tag != '' ) {
        $custom_end_tag = '</' . $custom_tag . '>';
        $custom_tag = '<' . $custom_tag . '>';
    }

    //
    $str_sub_cat = '';
    foreach ( $sub_cat as $sub_v ) {
        $str_sub_cat .= $custom_tag . '<a href="' . _eb_c_link( $sub_v->term_id ) . '">' . $sub_v->name . ' <span class="home-count-subcat">(' . $sub_v->count . ')</span></a>' . $custom_end_tag;
    }

    return $str_sub_cat;
}

// lấy danh sách các quảng cáo đi kèm cho từng nhóm
function WGR_get_home_node_ads( $cat_ids, $tmp = 'ads_node' ) {
    return _eb_load_ads( 9, _eb_number_only( EBE_get_lang( 'homelist_num' ) ), EBE_get_lang( 'homelist_size' ), array(
        'cat' => $cat_ids,
    ), 0, EBE_get_page_template( $tmp ) );
    //	), 0, str_replace( 'ti-le-global', '', EBE_get_page_template( 'ads_node' ) ) );
}

//
function WGR_add_option_class_for_post_widget( $a ) {
    $s = '';

    //
    if ( isset( $a[ 'hide_widget_title' ] ) && $a[ 'hide_widget_title' ] == 'on' ) {
        $s .= ' hide-widget-title';
    }

    if ( isset( $a[ 'hide_title' ] ) && $a[ 'hide_title' ] == 'on' ) {
        $s .= ' hide-blogs-title';
    }

    if ( isset( $a[ 'hide_description' ] ) && $a[ 'hide_description' ] == 'on' ) {
        $s .= ' hide-blogs-description';
    }

    if ( isset( $a[ 'hide_info' ] ) && $a[ 'hide_info' ] == 'on' ) {
        $s .= ' hide-blogs-info';
    }

    if ( isset( $a[ 'run_slider' ] ) && $a[ 'run_slider' ] == 'on' ) {
        $s .= ' ebwidget-run-slider';
    }

    if ( isset( $a[ 'open_youtube' ] ) && $a[ 'open_youtube' ] == 'on' ) {
        $s .= ' youtube-quick-view';
    }

    //
    return $s;
}


function WGR_show_widget_blog( $args, $instance, $options = array() ) {
    //	global $__cf_row;

    //	print_r( $instance );

    extract( $args );

    //
    $page_id = isset( $instance[ 'page_id' ] ) ? $instance[ 'page_id' ] : 0;
    if ( $page_id > 0 ) {
        global $post;
        //		print_r( $post );

        // 		nếu không phải là page -> thông báo lỗi luôn -> thông báo ẩn thôi, để các page khác không thấy là được
        //		if ( ! is_page() || ! isset( $post->post_type ) || $post->post_type != 'page' || $page_id != $post->ID ) {
        if ( !isset( $post->post_type ) || $post->post_type != 'page' || $page_id != $post->ID ) {
            echo '<!-- WARNING!</strong>: widget show only page id: <strong>' . $page_id . '. Current post type: ' . $post->post_type . ', current post ID: ' . $post->ID . ' -->';
            return false;
        }
    }


    //	$title = apply_filters ( 'widget_title', $instance ['title'] );
    $title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : '';
    $custom_cat_link = isset( $instance[ 'custom_cat_link' ] ) ? $instance[ 'custom_cat_link' ] : '';
    if ( trim( $custom_cat_link ) == '#' ) {
        $custom_cat_link = 'javascript:;';
    }
    $dynamic_tag = isset( $instance[ 'dynamic_tag' ] ) ? $instance[ 'dynamic_tag' ] : '';
    $dynamic_post_tag = isset( $instance[ 'dynamic_post_tag' ] ) ? $instance[ 'dynamic_post_tag' ] : '';
    $description = isset( $instance[ 'description' ] ) ? $instance[ 'description' ] : '';
    $post_number = isset( $instance[ 'post_number' ] ) ? $instance[ 'post_number' ] : 0;

    $sortby = isset( $instance[ 'sortby' ] ) ? $instance[ 'sortby' ] : '';

    $cat_ids = isset( $instance[ 'cat_ids' ] ) ? $instance[ 'cat_ids' ] : 0;
    $cat_type = isset( $instance[ 'cat_type' ] ) ? $instance[ 'cat_type' ] : EB_BLOG_POST_LINK;
    $get_childs = isset( $instance[ 'get_childs' ] ) ? $instance[ 'get_childs' ] : 'off';

    $get_full_content = isset( $instance[ 'get_full_content' ] ) ? $instance[ 'get_full_content' ] : 'off';
    //	echo $get_full_content . '<br>' . "\n";
    $content_only = isset( $instance[ 'content_only' ] ) ? $instance[ 'content_only' ] : 'off';
    $off_img_max_width = isset( $instance[ 'off_img_max_width' ] ) ? $instance[ 'off_img_max_width' ] : 'off';
    $js_ptags = isset( $instance[ 'js_ptags' ] ) ? $instance[ 'js_ptags' ] : 'off';
    //echo 'js_ptags: ' . $js_ptags . '<br>' . "\n";
    $show_content_excerpt = isset( $instance[ 'show_content_excerpt' ] ) ? $instance[ 'show_content_excerpt' ] : 'off';

    $num_line = isset( $instance[ 'num_line' ] ) ? $instance[ 'num_line' ] : '';
    $max_width = isset( $instance[ 'max_width' ] ) ? $instance[ 'max_width' ] : '';
    $post_cloumn = isset( $instance[ 'post_cloumn' ] ) ? $instance[ 'post_cloumn' ] : '';

    $html_template = isset( $instance[ 'html_template' ] ) ? $instance[ 'html_template' ] : '';

    $post_type = isset( $instance[ 'post_type' ] ) ? $instance[ 'post_type' ] : '';
    $same_cat = isset( $instance[ 'same_cat' ] ) ? $instance[ 'same_cat' ] : 'off';
    $get_post_type = isset( $instance[ 'get_post_type' ] ) ? $instance[ 'get_post_type' ] : 'off';
    // xác định lại post type cho trường hợp đặc biệt
    if ( $post_type == 'for_other_post_type' ) {
        $get_post_type = 'on';
    }
    //echo $post_type . '<br>' . "\n";
    //echo $get_post_type . '<br>' . "\n";

    $html_node = isset( $instance[ 'html_node' ] ) ? $instance[ 'html_node' ] : '';
    $html_node = _eb_widget_create_html_template( $html_node, 'blogs_node' );
    //echo $html_node . '<br>' . "\n";

    $ads_eb_status = isset( $instance[ 'ads_eb_status' ] ) ? $instance[ 'ads_eb_status' ] : 0;
    $post_eb_status = isset( $instance[ 'post_eb_status' ] ) ? $instance[ 'post_eb_status' ] : 0;
    $custom_style = isset( $instance[ 'custom_style' ] ) ? $instance[ 'custom_style' ] : '';

    $custom_id = isset( $instance[ 'custom_id' ] ) ? $instance[ 'custom_id' ] : '';
    if ( $custom_id != '' ) {
        $custom_id = ' id="' . $custom_id . '"';
    }

    $custom_size = isset( $instance[ 'custom_size' ] ) ? $instance[ 'custom_size' ] : '';

    $rel_xfn = isset( $instance[ 'rel_xfn' ] ) ? $instance[ 'rel_xfn' ] : '';
    $open_target = isset( $instance[ 'open_target' ] ) ? $instance[ 'open_target' ] : 'off';
    $open_youtube = isset( $instance[ 'open_youtube' ] ) ? $instance[ 'open_youtube' ] : 'off';

    // ẩn các thuộc tính theo option
    $custom_style .= WGR_add_option_class_for_post_widget( $instance );

    // nếu chỉ có một bài -> thêm class đánh dấu chỉ một bài viết
    //	echo $post_number;
    if ( $post_number == 0 ) {
        $post_number = 5;
    }
    /*
    else if ( $post_number == 1 ) {
    	$custom_style .= ' echbay-blog-onerow';
    }
    */
    //	echo $post_number . '<br>';
    $custom_style .= ' echbay-blog-number' . $post_number;
    //	echo $custom_style . '<br>';


    //
    //	$cat_link = '';
    $cat_link = $custom_cat_link;

    //
    //	_eb_echo_widget_title( $title, 'echbay-widget-blogs-title', $before_title );

    //
    $terms_categories = array();
    // lấy lại nhóm của bài viết
    $re_post_categories = array();
    $cat_name = '';
    $more_link = '';
    $str_sub_cat = '';


    // chỉ lấy các bài viết cùng nhóm (tự động)
    if ( $same_cat == 'on' ) {
        global $cid;
        global $parent_cid;
        global $pid;

        // xử lý thêm khi trong trang chi tiết
        if ( $pid > 0 ) {
            // xác định lại post type
            if ( $get_post_type == 'on' ) {
                global $__post;
                //				print_r( $__post );

                if ( isset( $__post->post_type ) ) {
                    $post_type = $__post->post_type;
                } else {
                    $post_type = WGR_get_post_type_name( $pid );
                }

                // nếu post type là page -> hủy luôn
                if ( $post_type == 'page' ) {
                    echo '<!-- STOP! auto get same cat not runing in page post type -->';
                    return false;
                }
            }

            // lấy toàn bộ danh sách nhóm của bài viết này
            if ( $post_type == EB_BLOG_POST_TYPE ) {
                $re_post_categories = get_the_terms( $pid, EB_BLOG_POST_LINK );
            } else {
                $re_post_categories = get_the_terms( $pid, 'category' );
            }
        }
        /*
        else if ( $cat_type != EB_BLOG_POST_LINK ) {
        	$post_type = 'post';
        }
        echo $cat_type . '<br>' . "\n";
        */

        //
        /*
        if ( $parent_cid > 0 ) {
        	$cat_ids = $parent_cid;
        }
        else if ( $cid > 0 ) {
        	$cat_ids = $cid;
        }
        */
        if ( $cid > 0 ) {
            $cat_ids = $cid;
        } else if ( $parent_cid > 0 ) {
            $cat_ids = $parent_cid;
        }

        //
        echo '<!-- auto get same cat -->';
    }
    //	echo 'aaaaaaaaaaaaa<br>';


    //
    _eb_echo_widget_name( $options[ 'this_name' ], $before_widget );


    // lấy theo nhóm tin đã được chỉ định
    //echo $cat_ids . '<br>' . "\n";
    if ( $cat_ids > 0 ) {
        //echo $cat_type . '<br>' . "\n";
        //echo $pid . '<br>' . "\n";

        // lấy lại taxonomy dựa theo ID cho nó chuẩn xác
        $cat_type = WGR_get_taxonomy_name( $cat_ids, $cat_type );
        //echo $cat_type . '<br>' . "\n";
        if ( $cat_type == '' ) {
            echo '<!-- taxonomy for #' . $cat_ids . ' not found! -->';
        }
        //
        else {

            //
            //			echo $cat_type . '<br>' . "\n";

            // xác định lại post type nếu đang là lấy tin tự động, và nghi ngờ post type với taxonomy không hợp lệ
            if ( $get_post_type == 'on' && $same_cat == 'on' && $cat_type != EB_BLOG_POST_LINK && $post_type == EB_BLOG_POST_TYPE ) {
                $post_type = 'post';
            }

            //
            //print_r( $re_post_categories );
            if ( !empty( $re_post_categories ) ) {
                foreach ( $re_post_categories as $v ) {
                    $terms_categories[] = $v->term_id;
                }
            } else {
                $terms_categories[] = $cat_ids;
            }

            //
            if ( $post_type == 'for_other_post_type' && $get_post_type == 'on' ) {
                //echo 'for_other_post_type aaaaaaa';
                $arr_select_data = array();
                $arr_select_data[ 'tax_query' ] = array(
                    array(
                        'taxonomy' => $cat_type,
                        'field' => 'term_id',
                        //					'terms' => array( $cat_ids ),
                        'terms' => $terms_categories,
                        'operator' => 'IN'
                    )
                );
                //print_r( $arr_select_data );
                $sql = _eb_load_post_obj( 1, $arr_select_data );
                //print_r( $sql );
            }
            //echo $post_type . '<br>' . "\n";

            //
            if ( $cat_link == '' ) {
                $cat_link = _eb_c_link( $cat_ids, $cat_type );
            }
            $more_link = '<div class="widget-blog-more"><a href="' . $cat_link . '">Xem thêm <span>&raquo;</span></a></div>';

            if ( $title == '' ) {
                //				echo $cat_ids;
                //				$categories = get_term_by('id', $cat_ids, $cat_type);
                $categories = get_term( $cat_ids, $cat_type );
                //				print_r($categories);
                if ( !empty( $categories ) ) {
                    $title = $categories->name;
                }
                //				print_r($categories);
            }


            // danh sách nhóm cấp 2
            $arr_sub_cat = array(
                'parent' => $cat_ids,
                'taxonomy' => $cat_type,
            );
            $sub_cat = get_categories( $arr_sub_cat );
            //			print_r( $sub_cat );

            if ( !empty( $sub_cat ) ) {
                // lấy các các bài viết trong nhóm con (nếu có)
                foreach ( $sub_cat as $sub_v ) {
                    $terms_categories[] = $sub_v->term_id;
                }

                //
                if ( $post_type != 'ads' && $get_childs == 'on' ) {
                    foreach ( $sub_cat as $sub_v ) {
                        $str_sub_cat .= ' <a href="' . _eb_c_link( $sub_v->term_id, $cat_type ) . '">' . $sub_v->name . ' <span class="blog-count-subcat">(' . $sub_v->count . ')</span></a>';
                    }
                    $str_sub_cat = '<div class="widget-blog-subcat">' . $str_sub_cat . '</div>';
                }
            }

        }
    }
    // lấy tất cả
    else {
        // tự xác định lại taxonomy
        if ( $post_type == EB_BLOG_POST_TYPE ) {
            $cat_type = EB_BLOG_POST_LINK;
        } else {
            $cat_type = 'category';
        }

        //
        $args = array(
            'taxonomy' => $cat_type,
        );
        $categories = get_categories( $args );
        //		print_r( $categories );

        //
        if ( !empty( $categories ) ) {
            foreach ( $categories as $v ) {
                $terms_categories[] = $v->term_id;
            }
        }
    }
    //	print_r( $terms_categories );
    //echo $post_type . '<br>' . "\n";

    // https://codex.wordpress.org/Template_Tags/get_posts#Random_posts
    $___order = 'DESC';
    if ( $sortby == '' || $sortby == 'rand' ) {
        $sortby = 'rand';
        $___order = '';
    }


    // https://codex.wordpress.org/Template_Tags/get_posts
    $arr_select_data = array(
        'orderby' => $sortby,
        'order' => $___order,
        'post_type' => $post_type,
    );

    // đối với ads
    if ( $post_type == 'ads' ) {
        // lấy theo trạng thái
        if ( $ads_eb_status > 0 ) {
            // v2
            /*
            $arr_select_data['tax_query'] = array(
            	array(
            		'key' => '_eb_ads_status',
            		'value' => $ads_eb_status,
            		'operator' => '=',
            		'type' => 'NUMERIC'
            	)
            );
            */

            // v1
            $arr_select_data[ 'meta_key' ] = '_eb_ads_status';
            $arr_select_data[ 'meta_value' ] = $ads_eb_status;
            $arr_select_data[ 'compare' ] = '=';
            $arr_select_data[ 'type' ] = 'NUMERIC';

            // hiển thị trạng thái ads ra để check cho dễ
            global $arr_eb_ads_status;

            echo '<!-- ADS status: ' . $ads_eb_status . ' - ' . $arr_eb_ads_status[ $ads_eb_status ] . ' -->';
        }

        // lấy theo taxonomy
        if ( $cat_ids > 0 ) {
            //		if ( ! empty( $terms_categories ) ) {
            $arr_select_data[ 'tax_query' ] = array(
                array(
                    'taxonomy' => $cat_type,
                    'field' => 'term_id',
                    //					'terms' => array( $cat_ids ),
                    'terms' => $terms_categories,
                    'operator' => 'IN'
                )
            );
        }
    }
    // các post type khác
    else {

        //
        /*
		global $cid;
		
		// chỉ lấy các bài viết cùng nhóm
		if ( $cat_ids == 0 && $cid > 0 && $same_cat == 'on' ) {
			$arr_select_data['tax_query'] = array(
				array(
					'taxonomy' => $cat_type,
					'field' => 'term_id',
					'terms' => array( $cid ),
					'operator' => 'IN'
				)
			);
			
			if ( $title == '' ) {
				$categories = get_term( $cid, $cat_type );
//				print_r($categories);
				if ( ! empty( $categories ) ) {
					$title = $categories->name;
				}
			}
		}
		else {
			*/
        // post -> có thêm phần trạng thái
        if ( $post_type == 'post' && $post_eb_status > 0 ) {
            $arr_select_data[ 'meta_key' ] = '_eb_product_status';
            $arr_select_data[ 'meta_value' ] = $post_eb_status;
            $arr_select_data[ 'compare' ] = '=';
            $arr_select_data[ 'type' ] = 'NUMERIC';
        }

        // với blog, lấy đặc biệt hơn chút
        //			else if ( count( $terms_categories ) > 0 ) {
        // -> lấy theo danh mục hoặc post option -> dùng để phân loại widget
        //			if ( count( $terms_categories ) > 0 ) {
        if ( !empty( $terms_categories ) ) {
            $arr_select_data[ 'tax_query' ] = array(
                array(
                    'taxonomy' => $cat_type,
                    'field' => 'term_id',
                    'terms' => $terms_categories,
                    'operator' => 'IN'
                )
            );
        }
        //			}
    }

    //
    //		print_r( $arr_select_data );
    
    $auto_del_line = 'yes';


    // nếu là node của sản phẩm -> dùng bản mặc định luôn
    if ( $html_node == 'thread_node' ) {
        $html_node = __eb_thread_template;
        $html_template = 'widget_echbay_thread';
    } else {
        // cố định file HTML để tối ưu với SEO
        if ( $post_cloumn == 'chi_anh' ) {
            $html_node = 'blogs_node_chi_anh';
        } else if ( $post_cloumn == 'chi_chu' ) {
            $html_node = 'blogs_node_chi_chu';
        } else if ( $post_cloumn == 'text_only' ) {
            $html_node = 'blogs_node_text_only';
        } else if ( isset( $instance[ 'hide_description' ] ) && $instance[ 'hide_description' ] == 'on' &&
            isset( $instance[ 'hide_info' ] ) && $instance[ 'hide_info' ] == 'on' ) {
            $html_node = 'blogs_node_avt_title';
        }

        //
        $html_node = EBE_get_page_template( $html_node );
        $html_node = WGR_add_li_to_thread_node( $html_node );

        // chỉnh lại kích thước nếu có
        if ( $custom_size != '' ) {
            $html_node = str_replace( '{tmp.cf_blog_size}', $custom_size, $html_node );
            $html_node = str_replace( '{tmp.cf_product_size}', $custom_size, $html_node );
        }

        // xóa bỏ các HTML thừa theo option
        if ( isset( $instance[ 'hide_description' ] ) && $instance[ 'hide_description' ] == 'on' ) {
            $html_node = str_replace( '{tmp.trv_gioithieu}', '', $html_node );
        }

        if ( isset( $instance[ 'hide_info' ] ) && $instance[ 'hide_info' ] == 'on' ) {
            $html_node = str_replace( '{tmp.ngaycapnhat}', '', $html_node );
            $html_node = str_replace( '{tmp.ant_ten}', '', $html_node );
        }

        if ( isset( $instance[ 'show_post_content' ] ) && $instance[ 'show_post_content' ] == 'on' ) {
            //echo 'js_ptags: ' . $js_ptags . '<br>' . "\n";
            //print_r( $instance ); echo 'aaaaaaaaaaa';
            
            //
            $fix_ptags = '';
            if ( $js_ptags == 'on' ) {
                $fix_ptags = ' each-to-fix-ptags';
                //echo 'fix_ptags: ' . $fix_ptags . '<br>' . "\n";
                $auto_del_line = 'no';
            }
            
            //
            $html_node = str_replace( '{tmp.trv_gioithieu}', '<div class="echbay-blog-excerpt">{tmp.trv_gioithieu}</div><div class="echbay-blog-content' . $fix_ptags . '">{tmp.post_content}</div>', $html_node );
        }
    }

    //
    if ( $dynamic_post_tag != '' ) {
        $html_node = EBE_dynamic_title_tag( $html_node, $dynamic_post_tag );
    }


    //
    if ( $post_cloumn != '' ) {
        $post_cloumn = 'blogs_node_' . $post_cloumn;
    }
    //	echo $cat_link;


    //
    $html_template = _eb_widget_create_html_template( $html_template, 'widget_echbay_blog' );
    echo '<!-- HTML widget file: ' . $html_template . ' - Widget title: ' . $title . ' -->';


    //
    $widget_title = '';
    if ( !isset( $instance[ 'hide_widget_title' ] ) || $instance[ 'hide_widget_title' ] == 'off' ) {
        $widget_title = _eb_get_echo_widget_title(
            $cat_link == '' ? $title : '<a href="' . $cat_link . '">' . $title . '</a>',
            'echbay-widget-blogs-title',
            //			$before_title,
            '',
            $dynamic_tag
        );

        if ( $description != '' ) {
            $widget_title .= '<div class="echbay-widget-blogs-desc">' . WGR_widget_title_with_bbcode( $description ) . '</div>';
        }
        //		echo $description;
    }


    //
    echo '<div' . $custom_id . ' class="' . trim( $custom_style ) . '">';

    // chỉ lấy nội dung bài viết
    if ( $content_only == 'on' ) {

        //
        //		print_r( $arr_select_data );

        // bắt buộc là sắp xếp theo menu_order DESC
        $arr_select_data[ 'orderby' ] = 'menu_order';
        $arr_select_data[ 'order' ] = 'DESC';
        //		print_r( $arr_select_data ); exit();

        // chỉ lấy 1 bài duy nhất
        $sql = _eb_load_post_obj( 1, $arr_select_data );

        if ( isset( $sql->post ) ) {
            $cl = '';
            // nếu có lệnh bỏ img maxwidth
            if ( $off_img_max_width != 'on' ) {
                $cl .= ' img-max-width';
            }
            // thêm lệnh tự tạo ptag
            if ( $js_ptags == 'on' && $show_content_excerpt == 'on' ) {
                $cl .= ' each-to-fix-ptags';
            }

            // lấy và in ra nội dung tìm được
            echo '<div class="echbay-blog-content_only' . $cl . '">';
            echo '<div data-id="' . $sql->post->ID . '" data-type="' . $sql->post->post_type . '" class="quick-edit-content_only"></div>';

            // in thẳng
            if ( isset( $sql->post ) && isset( $sql->post->post_content ) ) {
                echo '<!-- ' . _eb_p_link( $sql->post->ID ) . ' -->';
                if ( $sql->post->post_content == '' ) {
                    echo $sql->post->post_excerpt;
                }
                // hiển thị trực tiếp phần content
                else if ( $show_content_excerpt == 'on' ) {
                    echo $sql->post->post_content;
                }
                // sử dụng hàm the_content để định hình
                else {
                    $content = apply_filters( 'the_content', $sql->post->post_content );
                    echo str_replace( ']]>', ']]&gt;', $content );
                }
            }

            // sử dụng hàm content của wp -> nặng hơn -> đầy đủ chức năng hơn
            /*
            while ( $sql->have_posts() ) {
            	$sql->the_post();
            	the_content();
            }
            */
            echo '</div>';
        } else {
            echo '<!-- ';
            print_r( $sql );
            echo ' -->';
        }

    }
    // mặc định
    else {
        $arr_select_data[ 'ignore_sticky_posts' ] = 1;

        // load riêng 1 kiểu đối với ads
        /*
        if ( $post_type == 'ads' ) {
        	$content = _eb_load_post( $post_number, $arr_select_data,
        	$html_node );
        }
        // mặc định thì load theo post
        else {
        	*/
        /*
        if ( $get_full_content == 0 ) {
        	$get_full_content = 'off';
        }
        echo $get_full_content . '<br>' . "\n";
        */
        /*
        print_r( array(
        	'pot_tai' => $cat_type,
        	'get_full_content' => ( $get_full_content === 'on' ) ? 1 : 0
        ) );
        */

        $content = _eb_load_post( $post_number, $arr_select_data,
            //			), file_get_contents( EB_THEME_PLUGIN_INDEX . 'html/blog_node.html', 1 ) );
            //			), EBE_get_page_template( 'blog_node' ) );
            //			), EBE_get_page_template( $html_node ) );
            $html_node, 0, array(
                'pot_tai' => $cat_type,
                'auto_del_line' => $auto_del_line,
                'get_full_content' => $get_full_content === 'on' ? 1 : 0
            ) );
        //echo $content;

        // nếu không có dữ liệu -> in ra dữ liệu để test
        if ( $content == '' ) {
            echo '<!-- ';

            global $___eb_post__not_in;

            echo $___eb_post__not_in . '<br>' . "\n";

            print_r( $arr_select_data );

            echo ' -->';
        }
        //	}


        // thiết lập rel cho link
        $blog_link_option = '';
        $widget_title_option = '';
        if ( $rel_xfn != '' ) {
            $blog_link_option .= ' rel="' . $rel_xfn . '"';
            $widget_title_option .= ' rel="' . $rel_xfn . '"';
        }
        // mở link trong tab mới
        if ( $open_target == 'on' ) {
            $blog_link_option .= ' target="_blank"';
            $widget_title_option .= ' target="_blank"';
        }
        // thêm class để mở dưới dạng video youtube
        if ( $open_youtube == 'on' ) {
            $blog_link_option .= ' class="click-quick-view-video"';
        }
        //		echo $blog_link_option . 'aaaaaaaaaaaaaaa';

        //
        $show_content = EBE_html_template( EBE_get_page_template( $html_template ), array(
            'tmp.cat_link' => $cat_link == '' ? 'javascript:;' : $cat_link,
            //			'tmp.blog_link_option' => $blog_link_option,
            'tmp.more_link' => $more_link,
            'tmp.num_line' => $num_line,
            'tmp.max_width' => trim( 'margin-random-blog ' . $max_width ),
            'tmp.blog_title' => $title,
            'tmp.post_cloumn' => $post_cloumn,
            'tmp.widget_title' => $widget_title,
            'tmp.str_sub_cat' => $str_sub_cat,
            'tmp.content' => $content
        ) );

        // tạo tag động cho tiêu đề
        /*
        if ( $dynamic_post_tag != '' ) {
        	$show_content = EBE_dynamic_title_tag( $show_content, $dynamic_post_tag );
        }
        */

        // thay thế link
        $show_content = str_replace( '{tmp.blog_link_option}', $blog_link_option, $show_content );
        $show_content = str_replace( '{tmp.widget_title_option}', $widget_title_option, $show_content );
        $show_content = str_replace( '{tmp.post_zero}', EBE_get_lang( 'post_zero' ), $show_content );

        // hiển thị nội dung
        echo $show_content;
    }

    echo '</div>';

    //
    echo $after_widget;
}


function WGR_widget_list_checkbox_taxonomy( $instance, $default, $this_value, $taxonomy = 'category' ) {

    //
    WGR_add_css_js_for_elementor_editer();

    //
    //	print_r( $instance );
    //	print_r( $default );
    $instance = wp_parse_args( ( array )$instance, $default );
    foreach ( $instance as $k => $v ) {
        $$k = esc_attr( $v );
    }


    echo '<p>Mặc định sẽ hiển thị toàn bộ danh sách sản phẩm theo nhóm cấp 1. Nếu muốn chủ động hiển thị nhóm theo ý muốn, hãy chọn ở dưới:</p>';

    $categories = get_categories( array(
        'hide_empty' => 0,
        'taxonomy' => $taxonomy,
        'parent' => 0
    ) );
    //	print_r( $categories );

    $id_for = '_label_for_widget_home_list_' . rand( 0, 1000000 );

    echo '<div id="' . $id_for . '" class="div-widget-home_list">';


    echo '<p class="d-none"><input type="text" class="widefat" data-name="' . $id_for . '" name="' . $this_value[ 'cat_ids' ] . '" value="' . $cat_ids . '" /></p>';


    //
    echo '<ul class="ul-widget-home_list">';

    foreach ( $categories as $v ) {
        echo '<li style="order:' . ( int )_eb_get_cat_object( $v->term_id, '_eb_category_order', 0 ) . '">';

        echo '<label for="' . $id_for . $v->term_id . '" class="category-for-home_list"><input type="checkbox" id="' . $id_for . $v->term_id . '" data-id="' . $v->term_id . '" data-class="' . $id_for . '" class="click-get-category-id-home_list" /> <strong>' . $v->name . ' (' . $v->count . ')</strong></label>';

        // lấy nhóm con (nếu có)
        $sub_cat = get_categories( array(
            'hide_empty' => 0,
            'taxonomy' => $v->taxonomy,
            'parent' => $v->term_id
        ) );
        //		print_r( $sub_cat );
        foreach ( $sub_cat as $v2 ) {
            echo '<label for="' . $id_for . $v2->term_id . '" class="category-for-home_list"><input type="checkbox" id="' . $id_for . $v2->term_id . '" data-id="' . $v2->term_id . '" data-class="' . $id_for . '" class="click-get-category-id-home_list" /> <span>' . $v2->name . ' (' . $v2->count . ')</span></label>';

            //
            $sub3_cat = get_categories( array(
                'hide_empty' => 0,
                'taxonomy' => $v2->taxonomy,
                'parent' => $v2->term_id
            ) );
            //			print_r( $sub3_cat );
            foreach ( $sub3_cat as $v3 ) {
                echo '<label for="' . $id_for . $v3->term_id . '" class="category-for-home_list"><input type="checkbox" id="' . $id_for . $v3->term_id . '" data-id="' . $v3->term_id . '" data-class="' . $id_for . '" class="click-get-category-id-home_list" /> <em>' . $v3->name . ' (' . $v3->count . ')</em></label>';
            }
        }

        echo '</li>';
    }

    echo '</ul>';

    echo '</div>';

    echo '<script>WGR_category_for_home_list("' . $id_for . '", 1);</script>';
    //	print_r( $_GET );
    
}


function WGR_phom_for_home_list_and_blog( $instance, $default, $this_value, $taxonomy = 'category' ) {
    foreach ($instance as $k=> $v) {
        $default[$k]=$v;
    }
    
    //
    WGR_widget_list_checkbox_taxonomy($instance, $default, $this_value, $taxonomy);

    //
    _eb_widget_echo_number_of_posts_to_show( $this_value[ 'post_number' ], $default['post_number'] );


    //
    _eb_widget_number_of_posts_inline( $this_value[ 'num_line' ], $default['num_line'] );


    //
    _eb_widget_style_for_post_cloumn( $this_value[ 'post_cloumn' ], $default['post_cloumn'] );


    //
    _eb_widget_echo_widget_input_checkbox( $this_value[ 'hide_title' ], $default['hide_title'], 'Ẩn tiêu đề của bài viết.' );

    //
    _eb_widget_echo_widget_input_checkbox( $this_value[ 'hide_description' ], $default['hide_description'], 'Ẩn tóm tắt của bài viết.' );

    //
    _eb_widget_echo_widget_input_checkbox( $this_value[ 'hide_info' ], $default['hide_info'], 'Ẩn ngày tháng, danh mục của bài viết.' );

    //
    _eb_widget_echo_widget_input_checkbox( $this_value[ 'run_slider' ], $default['run_slider'], 'Chạy slider.' );


    //
    $arr_click_add_style = array(
        'r99' => 'remove class w90, w99',
        'nth-child-even' => 'Đổi nền vị trí chẵn',
        'nth-child-odd' => 'Đổi nền vị trí lẻ',
        'show-view-more' => 'hiển thị nút xem thêm (nếu có)',
        'hide-cat' => 'Ẩn tên danh mục (nếu có)',
        'hide-child-cat' => 'Ẩn các nhóm cấp 2, 3... (nếu có)'
    );
    $str_click_add_style = '';
    //	$i_class = 0;
    $id_click_add_style = str_replace( '[', '_', str_replace( ']', '_', $this_value[ 'custom_style' ] ) );
    foreach ( $arr_click_add_style as $k_class => $v_class ) {
        $str_click_add_style .= '<span class="d-block"><strong data-value="' . $k_class . '" data-add="' . $this_value[ 'custom_style' ] . '" class="cur click_add_widget_class"><i class="fa fa-minus-square"></i> ' . $k_class . '</strong>: ' . $v_class . '</span>';
        //		$i_class = 1;
    }

    //
    echo '<p><strong>Tùy chỉnh CSS</strong>: <input type="text" class="widefat" name="' . $this_value[ 'custom_style' ] . '" value="' . $default['custom_style'] . '" /> * Tạo class CSS để custom riêng:<br>
	' . $str_click_add_style . '</p>';
    /*
    - <strong>r99</strong>: remove class w90, w99<br>
    - <strong>nth-child-even</strong>: Đổi nền vị trí chẵn<br>
    - <strong>nth-child-odd</strong>: Đổi nền vị trí lẻ<br>
    - <strong>show-view-more</strong>: hiển thị nút xem thêm (nếu có)<br>
    - <strong>hide-cat</strong>: Ẩn tên danh mục (nếu có)<br>
    - <strong>hide-child-cat</strong>: Ẩn các nhóm cấp 2, 3... (nếu có).
    */

    //
    echo '<script type="text/javascript">
	WGR_widget_add_custom_style_to_field("' . $this_value[ 'custom_style' ] . '");
	</script>';

}

function WGR_default_for_home_list_and_blog() {
    return array(
        'num_line' => '',
        'post_number' => 5,
        'cat_ids' => '',
        'post_cloumn' => '',
        'hide_title' => '',
        'hide_description' => '',
        'hide_info' => '',
        'run_slider' => '',
        'custom_style' => ''
    );
}


// add css, js for elementor
// do trong trình chỉnh sửa của elemento không có sẵn các mã JS, CSS của EchBay theme, nên widget trong này hoạt động cũng không chuẩn -> add thêm vào cho nó chuẩn
function WGR_add_css_js_for_elementor_editer() {
    $f = EB_THEME_PLUGIN_INDEX . 'javascript/elementor.js';

    /*
    echo EB_THEME_PLUGIN_INDEX . '<br>';
    echo ABSPATH . '<br>';
    echo web_link . '<br>';
    echo admin_link . '<br>';
	
    //
    echo $f . '<br>';
    echo str_replace( ABSPATH, web_link, $f ) . '<br>';
    */
    echo '<script>var url_for_add_elementor="' . str_replace( ABSPATH, web_link, EB_THEME_PLUGIN_INDEX ) . '";</script>
	<script src="' . str_replace( ABSPATH, web_link, $f ) . '?v=' . filemtime( $f ) . '"></script>';
}


// category function
function EBE_widget_categories_get_child( $term_id, $cat_type, $show_count, $widget_id ) {
    $arrs_child_cats = get_categories( array(
        'taxonomy' => $cat_type,
        /*
        'orderby' => 'meta_value_num',
        'meta_query' => array(
        	'key' => '_eb_category_order',
        	'type' => 'NUMERIC'
        ),
        */
        'orderby' => 'slug',
        //		'order'   => 'ASC',
        'parent' => $term_id
    ) );
    //	print_r($arrs_child_cats);

    //	if ( count( $arrs_child_cats ) > 0 ) {
    if ( !empty( $arrs_child_cats ) ) {

        echo '<ul class="sub-menu">';

        //
        $arrs_child_cats = WGR_order_and_hidden_taxonomy( $arrs_child_cats );

        foreach ( $arrs_child_cats as $v2 ) {
            //			if ( _eb_get_cat_object( $v2->term_id, '_eb_category_hidden', 0 ) != 1 ) {
            $hien_thi_sl = '';
            if ( $show_count == 'on' ) {
                $hien_thi_sl = ' (' . $v->count . ')';
            }

            echo '<li class="cat-item cat-item-' . $v2->term_id . '"><a data-taxonomy="' . $cat_type . '" data-id="' . $v2->term_id . '" data-parent="' . $term_id . '" data-node-id="' . $widget_id . '" title="' . $v2->name . '" href="' . _eb_c_link( $v2->term_id ) . '" >' . $v2->name . '</a></li>';
            //			}
        }

        echo '</ul>';
    }
}


function EBE_widget_get_parent_cat( $id, $cat_type = 'category' ) {
    if ( $id > 0 ) {
        //		$a = get_term_by( 'id', $id, $cat_type );
        $a = get_term( $id, $cat_type );
        //		print_r( $a );

        // nếu không có nhóm cha -> nhóm này nhất rồi
        if ( !empty( $a ) ) {
            if ( $a->parent == 0 ) {
                return $a;
            } else {
                return EBE_widget_get_parent_cat( $a->parent, $cat_type );
            }
        }
    }

    return array();
}


// tạo câu lệnh select thủ công để kiểm tra post có tồn tại không
function WGR_custom_check_post_in_multi_taxonomy_v1( $cat1, $cat2 ) {
    global $wpdb;

    //
    //	$strFilter = " AND `" . $wpdb->term_taxonomy . "`.term_id IN (" . $cat1 . ',' . $cat2 . ") ";
    //	$strFilter = " AND `" . $wpdb->term_relationships . "`.term_id IN (" . $cat1 . ',' . $cat2 . ") ";
    $strFilter = " AND `" . wp_posts . "`.ID IN ( select object_id from `" . $wpdb->term_relationships . "` " . $cat1 . ',' . $cat2 . ") ";

    //
    $joinFilter = " LEFT JOIN `" . $wpdb->term_relationships . "` ON ( `" . wp_posts . "`.ID = `" . $wpdb->term_relationships . "`.object_id) ";
    //	$joinFilter .= " LEFT JOIN `" . $wpdb->term_taxonomy . "` ON ( `" . $wpdb->term_relationships . "`.term_taxonomy_id = `" . $wpdb->term_taxonomy . "`.term_taxonomy_id ) ";
    $joinFilter = "";

    //
    $sql = "SELECT ID
	FROM
		`" . wp_posts . "`
		" . $joinFilter . "
	WHERE
		`" . wp_posts . "`.post_type = 'post'
		AND `" . wp_posts . "`.post_status = 'publish'
		" . $strFilter . "
	ORDER BY
		`" . wp_posts . "`.ID DESC
	LIMIT 0, 1";
    echo $sql . '<br>' . "\n";
    $sql = _eb_q( $sql );
    print_r( $sql );

    //
    if ( empty( $sql ) ) {
        return 0;
    }
    echo _eb_p_link( $sql[ 0 ]->ID ) . '<br>' . "\n";

    //
    return 1;
}

function WGR_custom_check_post_in_multi_taxonomy_v2( $cat1, $cat2 ) {
    global $wpdb;

    //
    //	$strFilter = " AND `" . $wpdb->term_taxonomy . "`.term_id IN (" . $cat1 . ',' . $cat2 . ") ";
    //	$strFilter = " AND `" . $wpdb->term_relationships . "`.term_id IN (" . $cat1 . ',' . $cat2 . ") ";
    $strFilter = " AND ID IN ( select object_id from `" . $wpdb->term_relationships . "` where term_taxonomy_id in (" . $cat1 . ',' . $cat2 . ") ) ";

    //
    //	$joinFilter = " LEFT JOIN `" . $wpdb->term_relationships . "` ON ( `" . wp_posts . "`.ID = `" . $wpdb->term_relationships . "`.object_id) ";
    //	$joinFilter .= " LEFT JOIN `" . $wpdb->term_taxonomy . "` ON ( `" . $wpdb->term_relationships . "`.term_taxonomy_id = `" . $wpdb->term_taxonomy . "`.term_taxonomy_id ) ";
    $joinFilter = "";

    //
    $sql = "SELECT ID
	FROM
		`" . wp_posts . "`
		" . $joinFilter . "
	WHERE
		post_type = 'post'
		AND post_status = 'publish'
		" . $strFilter . "
	ORDER BY
		ID DESC
	LIMIT 0, 1";
    echo $sql . '<br>' . "\n";
    $sql = _eb_q( $sql );
    print_r( $sql );

    //
    if ( empty( $sql ) ) {
        return 0;
    }
    echo _eb_p_link( $sql[ 0 ]->ID ) . '<br>' . "\n";

    //
    return 1;
}

function WGR_custom_check_post_in_multi_taxonomy( $cat1, $cat2 ) {
    global $wpdb;

    // lấy và chạy vòng lặp để so dữ liệu
    $sql = "SELECT object_id
	FROM
		`" . $wpdb->term_relationships . "`
	WHERE
		term_taxonomy_id = " . $cat1 . " OR term_taxonomy_id = " . $cat2 . "
	ORDER BY
		object_id DESC
	LIMIT 0, 500";
    //	echo $sql . '<br>' . "\n";
    $sql = _eb_q( $sql );
    //	print_r( $sql );

    $num = 0;
    foreach ( $sql as $v ) {
        // dùng chính object_id để order -> chạy vòng lặp sẽ phát hiện ra sản phẩm trùng nhau luôn
        if ( $v->object_id == $num ) {
            //			echo _eb_p_link( $v->object_id ) . '<br>' . "\n";
            return 1;
        }
        $num = $v->object_id;
    }

    //
    return 0;

}

function WGR_check_post_in_multi_taxonomy( $a ) {

    print_r( $a );

    $sql_check = _eb_load_post_obj( 1, $a );
    print_r( $sql_check );
    echo count( $sql_check->posts ) . '<br>' . "\n";

    return count( $sql_check->posts );
    //	wp_reset_postdata();

}


function WGR_widget_categories_get_by_option( $v, $op ) {
    $hien_thi_sl = '';
    if ( $op[ 'show_count' ] == 'on' ) {
        $hien_thi_sl = ' (' . $v->count . ')';
    }

    //
    //	echo '<li class="cat-item cat-item-' . $v->term_id . '" style="order:' . _eb_number_only( _eb_get_cat_object( $v->term_id, '_eb_category_order', 0 ) ) . ';">' . $op['dynamic_tag_begin'] . '<a data-taxonomy="' . $v->taxonomy . '" data-id="' . $v->term_id . '" data-parent="' . $op['cat_ids'] . '" data-node-id="' . $op['widget_id'] . '" title="' . $v->name . '" href="' . _eb_c_link( $v->term_id ) . '" >' . $v->name . $hien_thi_sl . '</a>' . $op['dynamic_tag_end'];
    echo '<li class="cat-item cat-item-' . $v->term_id . '">' . $op[ 'dynamic_tag_begin' ] . '<a data-taxonomy="' . $v->taxonomy . '" data-id="' . $v->term_id . '" data-parent="' . $op[ 'cat_ids' ] . '" data-node-id="' . $op[ 'widget_id' ] . '" title="' . $v->name . '" href="' . _eb_c_link( $v->term_id ) . '" >' . $v->name . $hien_thi_sl . '</a>' . $op[ 'dynamic_tag_end' ];

    //
    if ( $op[ 'get_child' ] == true ) {
        EBE_widget_categories_get_child( $v->term_id, $op[ 'cat_type' ], $op[ 'show_count' ], $op[ 'widget_id' ] );
    }

    echo '</li>';
}