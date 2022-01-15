<?php

// lấy sản phẩm theo mẫu chung
function EBE_select_thread_list_all( $post, $html = __eb_thread_template, $pot_tai = 'category', $other_options = array() ) {
    global $__cf_row;
    global $wpdb;
    global $eb_background_for_post;
    //	global $eb_background_for_mobile_post;
    //	print_r( $other_options );
    //
    $ant_ten = '';
    $ant_option = '';
    $ant_tags = '';

    //
    //	print_r( $post );
    //	print_r( $__cf_row ); exit();
    // truyền các giá trị cho HTML cũ có thể chạy được
    // riêng với mục quảng cáo -> kiểm tra xem có alias tới post, page, blog nào không
    $anh_dai_dien_goc = '';
    $ads_id = $post->ID;
    if ( $post->post_type == 'ads' ) {
        // lấy ảnh đại diện gốc để dùng trong trường hợp q.cáo có ảnh riêng
        $anh_dai_dien_goc = _eb_get_post_img( $post->ID, $__cf_row[ 'cf_ads_thumbnail_size' ] );

        //
        $alias_post = _eb_number_only( _eb_get_post_object( $post->ID, '_eb_ads_for_post', 0 ) );
        $alias_taxonomy = _eb_number_only( _eb_get_post_object( $post->ID, '_eb_ads_for_category', 0 ) );

        // nếu có -> nạp thông tin post, page... mà nó alias tới
        if ( $alias_post > 0 ) {
            $sql = _eb_q( "SELECT *
			FROM
				`" . wp_posts . "`
			WHERE
				ID = " . $alias_post . "
				AND post_status = 'publish'" );
            //			print_r( $sql );
            // gán post mới nếu có
            if ( !empty( $sql ) ) {
                //
                $cache_ads_id = $post->ID;
                //				echo $cache_ads_id . '<br>' . "\n";
                $cache_ads_name = $post->post_title;
                //				echo $cache_ads_name . '<br>' . "\n";
                $cache_ads_excerpt = $post->post_excerpt;
                //				echo $cache_ads_excerpt . '<br>' . "\n";
                //
                $post = $sql[ 0 ];

                // lấy tên của Q.Cáo thay vì phân nhóm
                if ( _eb_get_post_meta( $cache_ads_id, '_eb_ads_name' ) == 1 ) {
                    $post->post_title = $cache_ads_name;
                    if ( $cache_ads_excerpt != '' ) {
                        $post->post_excerpt = $cache_ads_excerpt;
                    }
                } else if ( $post->post_excerpt == '' && $cache_ads_excerpt != '' ) {
                    $post->post_excerpt = $cache_ads_excerpt;
                }
            }
        }
    }


    // kiểm tra lại lần nữa, vì post bây giờ có thể đã thay đổi
    // với quảng cáo thì lấy link theo kiểu quảng cáo
    if ( $post->post_type == 'ads' ) {
        //		echo $alias_taxonomy;
        // Nếu có link trỏ tới 1 nhóm nào đó -> lấy link và tên nhóm để gán cho post này
        if ( $alias_taxonomy > 0 ) {
            $new_name = WGR_get_all_term( $alias_taxonomy );
            //			print_r( $new_name );
            //
            if ( !isset( $new_name->errors ) ) {
                //				$post->p_link = _eb_c_link( $alias_taxonomy, $new_name->taxonomy );
                $post->p_link = _eb_cs_link( $new_name );

                // lấy tên của Q.Cáo thay vì phân nhóm
                if ( _eb_get_post_meta( $post->ID, '_eb_ads_name' ) != 1 ) {
                    $post->post_title = $new_name->name;
                    if ( $new_name->description != '' ) {
                        $post->post_excerpt = $new_name->description;
                    }
                } else if ( $post->post_excerpt == '' && $new_name->description != '' ) {
                    $post->post_excerpt = $new_name->description;
                }
            }
        } else {
            $post->p_link = _eb_get_post_meta( $post->ID, '_eb_ads_url', true, 'javascript:;' );
        }

        // đặt ảnh đại diện cho phần q.cáo
        $post->trv_img = $anh_dai_dien_goc;


        // load ảnh đại diện cho phần quảng cáo
        if ( $__cf_row[ 'cf_ads_thumbnail_table_size' ] == $__cf_row[ 'cf_ads_thumbnail_size' ] ) {
            $post->trv_table_img = $post->trv_img;
        } else {
            $post->trv_table_img = _eb_get_post_img( $ads_id, $__cf_row[ 'cf_ads_thumbnail_table_size' ] );
        }

        if ( $__cf_row[ 'cf_ads_thumbnail_mobile_size' ] == $__cf_row[ 'cf_ads_thumbnail_table_size' ] ) {
            $post->trv_mobile_img = $post->trv_table_img;
        } else {
            $post->trv_mobile_img = _eb_get_post_img( $ads_id, $__cf_row[ 'cf_ads_thumbnail_mobile_size' ] );
        }


        //
        $youtube_uri = _eb_get_post_meta( $post->ID, '_eb_ads_video_url' );
        $youtube_id = _eb_get_youtube_id( $youtube_uri );
        //		$youtube_id = _eb_get_youtube_id( _eb_get_ads_object( $post->ID, '_eb_ads_video_url' ) );
        $youtube_url = 'about:blank';
        $youtube_avt = '';
        if ( $youtube_id != '' ) {
            //			$youtube_url = '//www.youtube.com/watch?v=' . $youtube_id;
            $youtube_url = '//www.youtube.com/embed/' . $youtube_id;
            $youtube_avt = '//i.ytimg.com/vi/' . $youtube_id . '/0.jpg';
        }
        $post->youtube_id = $youtube_id;
        $post->youtube_url = $youtube_url;
        $post->youtube_uri = $youtube_uri;
        $post->youtube_avt = $youtube_avt;
    }
    // các loại post khác
    else {
        // sử dụng ảnh riêng của q.cáo (nếu có) -> trường hợp q.cáo alias tới 1 post nào đó, nhưng phần ảnh vẫn sẽ dùng ảnh của q.cáo
        if ( $anh_dai_dien_goc != '' ) {
            $post->trv_img = $anh_dai_dien_goc;


            // load ảnh đại diện cho phần quảng cáo
            if ( $__cf_row[ 'cf_ads_thumbnail_table_size' ] == $__cf_row[ 'cf_ads_thumbnail_size' ] ) {
                $post->trv_table_img = $post->trv_img;
            } else {
                $post->trv_table_img = _eb_get_post_img( $ads_id, $__cf_row[ 'cf_ads_thumbnail_table_size' ] );
            }

            if ( $__cf_row[ 'cf_ads_thumbnail_mobile_size' ] == $__cf_row[ 'cf_ads_thumbnail_table_size' ] ) {
                $post->trv_mobile_img = $post->trv_table_img;
            } else {
                $post->trv_mobile_img = _eb_get_post_img( $ads_id, $__cf_row[ 'cf_ads_thumbnail_mobile_size' ] );
            }
        }
        // sử dụng ảnh mặc định của post
        else {
            $post->trv_img = _eb_get_post_img( $post->ID, $__cf_row[ 'cf_product_thumbnail_size' ] );
            $ads_id = $post->ID;


            // load ảnh đại diện cho phần sản phẩm
            if ( $__cf_row[ 'cf_product_thumbnail_table_size' ] == $__cf_row[ 'cf_product_thumbnail_size' ] ) {
                $post->trv_table_img = $post->trv_img;
            } else {
                $post->trv_table_img = _eb_get_post_img( $ads_id, $__cf_row[ 'cf_product_thumbnail_table_size' ] );
            }

            if ( $__cf_row[ 'cf_product_thumbnail_mobile_size' ] == $__cf_row[ 'cf_product_thumbnail_table_size' ] ) {
                $post->trv_mobile_img = $post->trv_table_img;
            } else if ( $__cf_row[ 'cf_product_thumbnail_mobile_size' ] == $__cf_row[ 'cf_product_thumbnail_size' ] ) {
                $post->trv_mobile_img = $post->trv_img;
            } else {
                $post->trv_mobile_img = _eb_get_post_img( $ads_id, $__cf_row[ 'cf_product_thumbnail_mobile_size' ] );
            }
        }


        // nếu có lệnh lấy full nội dung -> lấy luôn
        if ( isset( $other_options[ 'get_full_content' ] ) && $other_options[ 'get_full_content' ] == 1 ) {
            $post->post_excerpt = $post->post_content;
        }
        //		else if ( $post->post_type == 'blog' && $post->post_excerpt == '' ) {
        else if ( $post->post_excerpt == '' && $__cf_row[ 'cf_content_for_excerpt_null' ] > 69 ) {
            $post->post_excerpt = _eb_short_string( strip_tags( $post->post_content ), $__cf_row[ 'cf_content_for_excerpt_null' ] );
            //			$post->post_excerpt = 'bbbbbbbb';
        }
        /*
          else {
          $post->post_excerpt = nl2br( $post->post_excerpt );
          }
         */

        //
        $post->p_link = _eb_p_link( $post->ID );


        // blog
        if ( $post->post_type == EB_BLOG_POST_TYPE ) {
            $pot_tai = EB_BLOG_POST_LINK;

            // tags
            $arr_list_tag = wp_get_object_terms( $post->ID, 'blog_tag' );
        }
        // product
        else {

            // post option
            $arr_post_options = wp_get_object_terms( $post->ID, 'post_options' );
            if ( !empty( $arr_post_options ) ) {
                //				print_r( $arr_post_options );
                //
                foreach ( $arr_post_options as $v ) {
                    if ( $v->parent > 0 ) {
                        $parent_name = WGR_get_taxonomy_parent( $v );

                        //
                        $ant_option .= '<span>' . $parent_name->name . '</span>: <a href="' . _eb_cs_link( $v ) . '" target="_blank">' . $v->name . '</a> ';
                    }
                }
                $ant_option = '<span class="thread-list-options">' . $ant_option . '</span>';
            }


            //
            if ( $__cf_row[ 'cf_list_avt_hover' ] == 1 ) {
                $img_hover = _eb_get_post_object( $post->ID, '_eb_product_avatar' );
                if ( $img_hover != '' ) {
                    $__cf_row[ 'cf_default_css' ] .= '.thread-list li[data-id="' . $post->ID . '"]:hover .thread-list-avt{background-image:url("' . $img_hover . '") !important}';
                }
            }


            // tags
            $arr_list_tag = get_the_tags( $post->ID );


            // các thuộc tính chỉ ở sản phẩm mới có
            $post->trv_masanpham = _eb_get_post_object( $post->ID, '_eb_product_sku', $post->ID );

            $post->trv_mua = ( int )_eb_get_post_object( $post->ID, '_eb_product_buyer', 0 );
            $post->trv_luotmua = $post->trv_mua;

            $post->trv_soluong = ( int )_eb_get_post_object( $post->ID, '_eb_product_quantity', 0 );

            $post->trv_soluongconlai = $post->trv_soluong - $post->trv_mua;

            $post->trv_xem = ( int )_eb_get_post_object( $post->ID, '_eb_product_views', 0 );
            $post->trv_luotxem = number_format( $post->trv_xem );

            //
            if ( $post->trv_mua > 0 && $post->trv_xem < $post->trv_mua ) {
                WGR_update_meta_post( $post->ID, '_eb_product_views', $post->trv_mua * 12 );
            }

            //
            $post->giaban = _eb_float_only( _eb_get_post_object( $post->ID, '_eb_product_oldprice' ) );

            $post->giamoi = _eb_float_only( _eb_get_post_object( $post->ID, '_eb_product_price' ) );

            if ( $post->giaban <= $post->giamoi ) {
                $post->giaban = 0;
            }

            $post->trv_num_giacu = $post->giaban;
            $post->trv_num_giamoi = $post->giamoi;

            //
            $post->trv_color_count = 1;

            $post->trv_trangthai = 1;
            //			$post->trv_ngayhethan = date_time;
            $post->trv_ngayhethan = '';


            // chức năng cập nhật lại giá khi hết hạn
            if ( $__cf_row[ 'cf_update_price_if_hethan' ] == 1 ) {
                $_eb_product_ngayhethan = _eb_get_post_object( $post->ID, '_eb_product_ngayhethan' );
                //				echo $_eb_product_ngayhethan . '<br>';
                if ( $_eb_product_ngayhethan != '' ) {
                    $_eb_product_giohethan = _eb_get_post_object( $post->ID, '_eb_product_giohethan' );
                    if ( $_eb_product_giohethan == '' ) {
                        $_eb_product_giohethan = '23:59';
                    }

                    // kiểm tra định dạng này tháng
                    $check_dinh_dang_ngay = explode( '/', $_eb_product_ngayhethan );

                    // định dạng chuẩn là: YYYY/MM/DD
                    if ( count( $check_dinh_dang_ngay ) == 3 && strlen( $check_dinh_dang_ngay[ 0 ] ) == 4 ) {
                        $trv_ngayhethan = $_eb_product_ngayhethan . ' ' . $_eb_product_giohethan;
                        //						echo $trv_ngayhethan;
                        $trv_ngayhethan = strtotime( $trv_ngayhethan );
                        //						echo $trv_ngayhethan . '<br>';

                        // nếu sản phẩm hết hạn
                        if ( $trv_ngayhethan > 0 && $trv_ngayhethan < date_time ) {
                            $gia_sau_km = _eb_float_only( _eb_get_post_object( $post->ID, '_eb_product_baseprice' ) );

                            if ( $gia_sau_km > 0 && $gia_sau_km > $post->giamoi && $post->giamoi > 0 ) {
                                // cập nhật lại giá phẩm
                                //								echo '<!-- reset price -->';

                                // đặt lại giá sau khuyến mại về 0, nếu không web sẽ update liên tục -> lỗi hệ thống ngay
                                WGR_update_meta_post( $post->ID, '_eb_product_baseprice', 0 );
                                WGR_update_meta_post( $post->ID, '_eb_product_price', $gia_sau_km );

                                WGR_update_meta_post( $post->ID, '_eb_product_ngayhethan', '' );
                                WGR_update_meta_post( $post->ID, '_eb_product_giohethan', '' );
                            }
                            $trv_ngayhethan = 0;
                        }
                    }
                }
            }


            //
            $post->pt = 0;
            if ( $post->giaban > $post->giamoi ) {
                $post->pt = 100 - _eb_float_only( $post->giamoi * 100 / $post->giaban, 1 );
            }

            //
            $post->trv_giaban = EBE_add_ebe_currency_class( $post->giaban, 1, '&nbsp;' );

            $post->trv_giamoi = EBE_add_ebe_currency_class( $post->giamoi );

            $post->product_status = _eb_get_post_object( $post->ID, '_eb_product_status', $post->post_status );
        }

        // -> tag
        if ( !empty( $arr_list_tag ) ) {
            //			print_r( $arr_list_tag );
            //
            foreach ( $arr_list_tag as $v ) {
                $ant_tags .= '<a href="' . get_tag_link( $v->term_id ) . '">' . $v->name . '</a> ';
            }
            $ant_tags = '<span class="thread-list-tags">' . $ant_tags . '</span>';
        }


        // lấy danh mục sản phẩm
        //		$ant = get_the_category( $post->ID );
        $ant = get_the_terms( $post->ID, $pot_tai );
        //		print_r( $ant );
        if ( !empty( $ant ) ) {
            foreach ( $ant as $v ) {
                // ưu tiên lấy nhóm con trước
                if ( $ant_ten == '' && $v->parent > 0 ) {
                    $ant_ten = '<a href="' . _eb_cs_link( $v ) . '" class="thread-list-ant_ten">' . $v->name . '</a>';
                    break;
                }
            }

            // nếu ko tìm được -> lấy luôn nhóm cha đầu tiên
            if ( $ant_ten == '' ) {
                foreach ( $ant as $v ) {
                    $ant_ten = '<a href="' . _eb_cs_link( $v ) . '" class="thread-list-ant_ten">' . $v->name . '</a>';
                    break;
                }
            }
        }

        //		$post->ant_ten = isset ($ant[0]->name ) ? '<a href="' . _eb_cs_link( $ant[0] ) . '">' . $ant[0]->name . '</a>' : '';
    }
    $post->ant_ten = $ant_ten;
    $post->ant_option = $ant_option;
    $post->ant_tags = $ant_tags;


    //	$post->p_link = $post->guid;
    $post->trv_tieude = $post->post_title;
    $post->trv_title = str_replace( '"', '&quot;', trim( strip_tags( $post->post_title ) ) );
    $post->trv_id = $post->ID;
    $post->trv_gioithieu = nl2br( $post->post_excerpt );
    //	$post->trv_gioithieu = $post->post_excerpt;
    //
    //	$post_time = strtotime( $post->post_modified );
    $post_time = strtotime( $post->post_date );
    //	$post->ngaycapnhat = date( 'd/m/Y', $post_time );
    $post->ngaycapnhat = date( $__cf_row[ 'cf_date_format' ], $post_time );
    $post->ngaycapnhats = $post->ngaycapnhat . ' ' . date( $__cf_row[ 'cf_time_format' ], $post_time );

    $post->cf_product_size = $__cf_row[ 'cf_product_size' ];
    $post->cf_product_mobile2_size = $__cf_row[ 'cf_product_mobile2_size' ];
    $post->cf_blog_size = $__cf_row[ 'cf_blog_size' ];


    //
    $html = EBE_dynamic_title_tag( $html );

    //
    /*
      if ( $post->trv_mobile_img != '' ) {
      $post->trv_mobile_img = 'background-image:url(' . $post->trv_mobile_img . ')!important';
      }
      if ( $post->trv_img != '' ) {
      $post->trv_img = 'background-image:url(' . $post->trv_img . ')!important';
      }
      $eb_background_for_post['p' . $post->ID] = '.ebp' . $post->ID . 'm{' . $post->trv_mobile_img . '}.ebp' . $post->ID . '{' . $post->trv_img . '}';
      $post->trv_img = 'speed';
      $post->trv_mobile_img = 'ebp' . $post->ID;
     */

    // tiếp tục tìm và xử lý dữ liệu trong các trường tùy biến nếu có
    global $arr_object_post_meta;
    if ( array_key_exists( 'id' . $post->ID, $arr_object_post_meta ) ) {
        //		print_r( $arr_object_post_meta['id' . $post->ID] );
        $html = EBE_arr_tmp( $arr_object_post_meta[ 'id' . $post->ID ], $html );
    }

    // nếu có function xử lý thêm dữ liệu từ theme con -> gọi nó -> chủ yếu để ghi đè các trường dữ liệu thiếu -> đỡ xấu website
    if ( function_exists( 'WGR_cleanup_tmp_in_child_theme' ) ) {
        $html = WGR_cleanup_tmp_in_child_theme( $html );
    }

    // hiển thị danh mục trên phần danh sách sản phẩm
    $get_category_in_list = 0;
    if ( $__cf_row[ 'cf_category_in_list' ] === 1 ) {
        $get_category_in_list = 1;
    } else if ( defined( 'eb_find_category_in_list' ) && eb_find_category_in_list === true ) {
        $get_category_in_list = 1;
    }

    //echo $get_category_in_list . '<br>';
    if ( $get_category_in_list === 1 ) {
        $html = WGR_replace_category_slug( $post->ID, $post->post_type, $html );
    }
    //echo $html;
    //print_r($post);

    //
    return EBE_arr_tmp( $post, $html );
}

function WGR_replace_category_slug( $post_id, $post_post_type, $html ) {
    //echo 'aaaaaaaaa';
    $replace_post_categories = array();
    $post_categories = array();

    //
    if ( $post_post_type == 'post' ) {
        //$post_categories = wp_get_post_categories( $post_id );
        $post_categories = get_the_terms( $post_id, 'category' );
        $post_categories2 = array();
        $post_categories2 = get_the_terms( $post_id, 'post_options' );

        //
        if ( $post_categories != false && $post_categories2 != false ) {
            $post_categories = array_merge( get_the_terms( $post_id, 'category' ), get_the_terms( $post_id, 'post_options' ) );
        } else if ( $post_categories == false && $post_categories2 != false ) {
            $post_categories = $post_categories2;
        }
    } else if ( $post_post_type == EB_BLOG_POST_TYPE ) {
        $post_categories = get_the_terms( $post_id, EB_BLOG_POST_LINK );
    } else if ( $post_post_type == 'product' ) {
        $post_categories = get_the_terms( $post_id, 'product_cat' );
    }
    if ( $post_categories == false ) {
        $post_categories = array();
    }
    //print_r($post_categories);

    if ( !empty( $post_categories ) ) {
        global $arr_replace_cat_in_ids_list;
        //echo gettype($arr_replace_cat_in_ids_list) . '<br>';
        if ( gettype( $arr_replace_cat_in_ids_list ) == 'NULL' ) {
            //echo 'aaaaaaaaa<br>';
            $arr_replace_cat_in_ids_list = array();
        }
        //print_r($arr_replace_cat_in_ids_list);

        //
        //print_r($post_categories);
        foreach ( $arr_replace_cat_in_ids_list as $v ) {
            foreach ( $post_categories as $v2 ) {
                if ( $v->term_id == $v2->term_id ) {
                    // nếu là nhóm cha -> gán luôn -> vì key lấy theo nhóm cha
                    if ( $v->parent == 0 ) {
                        $replace_post_categories[ $v->slug ] = $v->name;
                    }
                    // còn nhóm con thì đi tìm key của nhóm cha
                    else {
                        foreach ( $arr_replace_cat_in_ids_list as $v_child ) {
                            if ( $v->parent == $v_child->term_id ) {
                                $replace_post_categories[ $v_child->slug ] = $v->name;
                            }
                        }
                    }
                }
            }
        }
    }
    /*
    if ($post_post_type == 'post') {
//		$post_categories = wp_get_post_categories( $post_id );
        $post_categories = get_the_terms( $post_id, 'category' );

        if ( ! empty( $post_categories ) ) {
            global $arr_replace_cat_in_ids_list;

            print_r($post_categories);
            foreach ( $arr_replace_cat_in_ids_list as $v ) {
                if ( in_array( $v->term_id, $post_categories ) ) {
                    $replace_post_categories[ $v->slug ] = $v->name;
                }
            }
        }
    }
    else {
    }
    */
    //print_r($replace_post_categories);
    return EBE_arr_tmp( $replace_post_categories, $html );
}

function WGR_get_taxonomy_parent( $arr ) {
    //	$a = get_term_by( 'id', $arr->parent, $arr->taxonomy );
    $a = get_term( $arr->parent, $arr->taxonomy );
    //	print_r( $a );
    // tìm đến khi lấy được nhóm cấp 1 thì thôi
    if ( $a->parent > 0 ) {
        $a = WGR_get_taxonomy_parent( $a );
    }

    return $a;
}

// tạo thẻ động cho phần tiêu đề sản phẩm, blog
function EBE_dynamic_title_tag( $html, $tag = '' ) {
    // lấy tag mặc định
    if ( $tag == '' ) {
        global $__cf_row;

        $tag = $__cf_row[ 'cf_threadnode_title_tag' ];
    }
    //	echo '<!-- =========================== ' . $tag . ' =========================== -->';
    // tạo thẻ đóng
    $html = str_replace( 'dynamic_title_tag>', $tag . '>', $html );
    // tạo thẻ mở
    $html = str_replace( '<dynamic_title_tag', '<' . $tag . ' dynamic-title-tag="1"', $html );

    return $html;
}

function WGR_money_format( $n ) {
    $n = explode( '.', $n );

    // định dạng tiền USD
    if ( isset( $n[ 1 ] ) ) {
        return number_format( $n[ 0 ] ) . '.' . $n[ 1 ];
    }

    // Tiền Việt
    return number_format( $n[ 0 ] );
}

function EBE_add_ebe_currency_class( $gia, $gia_cu = 0, $default_value = '' ) {

    //
    $str = $default_value;

    // giá mới
    if ( $gia_cu == 0 ) {
        if ( $gia > 0 ) {
            $str = '<strong data-num="' . $gia . '" class="global-details-giamoi ebe-currency ebe-currency-format">&nbsp;</strong>';
        } else {
            $str = '<strong class="global-details-giamoi global-details-gialienhe">{tmp.post_zero}</strong>';
        }
    }
    // giá cũ
    else if ( $gia > 0 ) {
        $str = '<span data-num="' . $gia . '" class="global-details-giacu old-price ebe-currency ebe-currency-format">&nbsp;</span>';
    }

    // Giá trị mặc định
    return $str;
}

function EBE_arr_tmp( $row = array(), $str = '', $tmp = 'tmp.' ) {
    //	print_r($row);
    foreach ( $row as $k => $v ) {
        $str = str_replace( '{' . $tmp . $k . '}', $v, $str );
    }
    return $str;
}

function EBE_str_template( $f, $arr = array(), $dir = EB_THEME_HTML ) {
    $f = $dir . $f;

    if ( file_exists( $f ) ) {
        $f = file_get_contents( $f, 1 );

        //
        return EBE_html_template( $f, $arr );
    }

    //
    return 'File "' . $f . '" not found.';
}

// thay thế các văn bản trong html tìm được
function EBE_html_template( $html, $arr = array() ) {
    foreach ( $arr as $k => $v ) {
        $html = str_replace( '{' . $k . '}', $v, $html );
    }

    return $html;
}

// tạo tên class riêng theo config
function EBE_get_html_file_addon( $page_name, $addon = '' ) {
    global $arr_for_show_html_file_load;

    if ( $addon != '' ) {
        $page_name .= '_' . $addon;
    }
    $arr_for_show_html_file_load[] = '<!-- addon: ' . $page_name . ' -->';

    return $page_name;
}

function EBE_get_page_custom_options( $page_name ) {

    // lấy page trong CSDL xem có không? chỉ lấy page dưới dạng Private
    $sql = _eb_q( "SELECT ID, post_title, post_excerpt
	FROM
		`" . wp_posts . "`
	WHERE
		post_name = '" . $page_name . "'
		AND post_type = 'eb_page'
		AND post_status = 'private'" );
    //		print_r($sql);
    // nếu tồn tại page này -> sử dụng page để làm html
    if ( isset( $sql[ 0 ] ) && isset( $sql[ 0 ]->ID ) ) {
        return trim( $sql[ 0 ]->post_excerpt );
    }
    return '';
}

function EBE_get_page_template( $page_name = '', $dir = EB_THEME_HTML, $f_css = '' ) {
    //	global $wpdb;
    //	global $__cf_row;
    global $arr_for_add_css;
    //	global $arr_for_add_theme_css;
    global $arr_for_show_html_file_load;

    //
    if ( $page_name == '' ) {
        $page_name = 'home';
        //		$page_name = 'test-page';
    }

    //
    $html = '';

    // thử lấy trong CSDL xem có không
    /*
      $html = EBE_get_page_custom_options( $__cf_row['cf_theme_dir'] . '-' . $page_name );
      if ( $html != '' ) {
      $arr_for_show_html_file_load[] = '<!-- custom HTML: ' . $page_name . ' -->';

      return $html;
      }
     */

    // kiểm tra trong child theme
    $tmp_child_theme = '';
    $tmp_child_ui_theme = '';
    if ( using_child_wgr_theme == 1 ) {
        $tmp_child_theme = EB_CHILD_THEME_URL . 'html/' . $page_name . '.html';
        $tmp_child_ui_theme = EB_CHILD_THEME_URL . 'ui/' . $page_name . '.html';
        //		echo $tmp_child_theme . '<br>' . "\n";
    }

    // không có HTML động -> lấy file tĩnh theo theme
    $f = $dir . $page_name . '.html';
    //	echo $f . '<br>';
    // tìm trong thư mục theme riêng (ưu tiên)
    if ( $tmp_child_theme != '' && file_exists( $tmp_child_theme ) ) {
        $f = $tmp_child_theme;

        $arr_for_show_html_file_load[] = '<!-- child theme HTML: ' . $page_name . ' -->';

        $html = file_get_contents( $f, 1 );

        // dùng chung thì gán CSS dùng chung luôn (nếu có)
        $css = EB_CHILD_THEME_URL . 'css/' . $page_name . '.css';
        //		echo $css;
        //		if ( file_exists( $css ) ) {
        //			$arr_for_add_theme_css[ $css ] = 1;
        $arr_for_add_css[ $css ] = 1;

        //			$arr_for_show_html_file_load[] = '<!-- child theme CSS: ' . $page_name . ' -->';
        //		}
    } else if ( $tmp_child_ui_theme != '' && file_exists( $tmp_child_ui_theme ) ) {
        $f = $tmp_child_ui_theme;

        $arr_for_show_html_file_load[] = '<!-- child theme ui HTML: ' . $page_name . ' -->';

        $html = file_get_contents( $f, 1 );

        // dùng chung thì gán CSS dùng chung luôn (nếu có)
        $css = EB_CHILD_THEME_URL . 'ui/' . $page_name . '.css';
        //		echo $css;
        //		if ( file_exists( $css ) ) {
        //			$arr_for_add_theme_css[ $css ] = 1;
        $arr_for_add_css[ $css ] = 1;

        //			$arr_for_show_html_file_load[] = '<!-- child theme CSS: ' . $page_name . ' -->';
        //		}
    } else if ( file_exists( $f ) ) {
        $arr_for_show_html_file_load[] = '<!-- theme HTML: ' . $page_name . ' -->';

        $html = file_get_contents( $f, 1 );

        // dùng chung thì gán CSS dùng chung luôn (nếu có)
        $css = EB_THEME_THEME . 'css/' . $page_name . '.css';
        //		echo $css;
        //		if ( file_exists( $css ) ) {
        //			$arr_for_add_theme_css[ $css ] = 1;
        $arr_for_add_css[ $css ] = 1;

        //			$arr_for_show_html_file_load[] = '<!-- theme CSS: ' . $page_name . ' -->';
        //		}
    }
    // tìm trong thư mục theme chung
    else {
        $f = EB_THEME_PLUGIN_INDEX . 'html/' . $page_name . '.html';

        // nếu không -> báo lỗi
        if ( !file_exists( $f ) ) {
            return 'File HTML "' . $page_name . '" not found.';
        }

        // nếu có -> dùng
        $arr_for_show_html_file_load[] = '<!-- global HTML: ' . $page_name . ' -->';

        $html = file_get_contents( $f, 1 );

        // nếu có css riêng -> add luôn vào
        /*
          if ( $f_css != '' ) {
          $arr_for_add_css[ $f_css ] = 1;
          }
          else {
         */
        // dùng chung thì gán CSS dùng chung luôn (nếu có)
        $css = EB_THEME_PLUGIN_INDEX . 'css/default/' . $page_name . '.css';
        //			if ( file_exists( $css ) ) {
        $arr_for_add_css[ $css ] = 1;

        //				$arr_for_show_html_file_load[] = '<!-- global CSS: ' . $page_name . ' -->';
        //			}
        //		}
    }

    //
    return $html;
}

// lấy template HTML cho NAV mobile hoặc nút mua trên mobile
function EBE_get_custom_template( $n, $plugin_dir ) {
    global $arr_for_add_css;
    global $arr_for_show_html_file_load;

    //
    //	echo 'aaaaaaaaaaaa';
    // file html
    $f = EB_THEME_THEME . 'html/' . $n . '.html';

    // kiểm tra trong child theme
    $tmp_child_theme = '';
    if ( using_child_wgr_theme == 1 ) {
        $tmp_child_theme = EB_CHILD_THEME_URL . 'html/' . $n . '.html';
        //		echo $tmp_child_theme . '<br>' . "\n";
    }

    // child theme
    if ( $tmp_child_theme != '' && file_exists( $tmp_child_theme ) ) {
        $h = file_get_contents( $tmp_child_theme, 1 );

        $arr_for_add_css[ EB_CHILD_THEME_URL . 'css/' . $n . '.css' ] = 1;
        $arr_for_add_css[ EB_CHILD_THEME_URL . 'html/' . $n . '.css' ] = 1;

        $arr_for_show_html_file_load[] = '<!-- chile theme HTML: ' . $n . ' -->';
    }
    // kiểm tra trong theme
    else if ( file_exists( $f ) ) {
        $h = file_get_contents( $f, 1 );
        //		echo $f . '<br>' . "\n";

        $arr_for_add_css[ EB_THEME_THEME . 'css/' . $n . '.css' ] = 1;
        $arr_for_add_css[ EB_THEME_THEME . 'html/' . $n . '.css' ] = 1;

        $arr_for_show_html_file_load[] = '<!-- theme HTML: ' . $n . ' -->';
    }
    // lấy mặc định trong plugin
    else {
        $f = EB_THEME_PLUGIN_INDEX . 'html/' . $plugin_dir . '/' . $n . '.html';
        //		echo $f . '<br>' . "\n";

        $h = file_get_contents( $f, 1 );

        $arr_for_add_css[ EB_THEME_PLUGIN_INDEX . 'html/' . $plugin_dir . '/' . $n . '.css' ] = 1;

        $arr_for_show_html_file_load[] = '<!-- global HTML: ' . $n . ' -->';
    }
    //	print_r( $arr_for_add_css );

    return $h;
}

// chức năng lấy template mặc định trong child-theme (nếu có)
function WGR_get_html_template_lang( $f, $file_name = '', $default_dir = '' ) {
    global $arr_for_add_css;

    //
    $c = EBE_get_lang( $f );

    //
    if ( trim( $c ) == $f ) {
        if ( $file_name == '' ) {
            $file_name = $f;
        }

        //
        if ( using_child_wgr_theme == 1 && file_exists( EB_CHILD_THEME_URL . 'html/' . $file_name . '.html' ) ) {
            $c = file_get_contents( EB_CHILD_THEME_URL . 'html/' . $file_name . '.html' );

            $arr_for_add_css[ EB_CHILD_THEME_URL . 'css/' . $file_name . '.css' ] = 1;
        }
        /*
          else if ( file_exists( EB_THEME_URL . 'html/' . $file_name . '.html' ) ) {
          $c = file_get_contents( EB_THEME_URL . 'html/' . $file_name . '.html' );
          }
         */
        else {
            if ( $default_dir == '' ) {
                $default_dir = EB_THEME_PLUGIN_INDEX . 'html/';
            }

            //
            $c = file_get_contents( $default_dir . $file_name . '.html' );

            $arr_for_add_css[ EB_THEME_PLUGIN_INDEX . 'css/' . $file_name . '.css' ] = 1;
            $arr_for_add_css[ $default_dir . 'css/' . $file_name . '.css' ] = 1;
            $arr_for_add_css[ $default_dir . $file_name . '.css' ] = 1;
            //			print_r( $arr_for_add_css );
        }
    }

    return $c;
}

/*
 * Chức năng tạo head riêng của Echbay
 * Các file tĩnh như css, js sẽ được cho vào vòng lặp để chạy 1 phát cho tiện
 */

function _eb_add_css( $arr = array(), $include_now = 0 ) {
    _eb_add_css_js_file( $arr, '.css', $include_now );
}

function _eb_add_full_css( $arr = array(), $type_add = 'import' ) {
    if ( $type_add == 'import' ) {
        foreach ( $arr as $v ) {
            echo '<style>@import url(' . $v . ');</style>';
        }
    } else {
        foreach ( $arr as $v ) {
            echo '<link rel="stylesheet" href="' . $v . '" type="text/css" media="all" />';
        }
    }
}

function _eb_add_js( $arr = array(), $include_now = 0 ) {
    _eb_add_css_js_file( $arr, '.js', $include_now );
}

function _eb_add_full_js( $arr = array(), $type_add = 'import' ) {
    /*
      if ( $type_add == 'import' ) {
      foreach ( $arr as $v ) {
      // v2
      echo _eb_import_js( $v . '?v=' . web_version );
      }
      }
      else {
     */
    foreach ( $arr as $v ) {
        echo '<script type="text/javascript" src="' . $v . '"></script>' . "\n";
    }
    //	}
    //
    /*
      echo '<script type="text/javascript" src="';
      echo implode( '?v=' . web_version . '"></script>' . "\n" . '<script type="text/javascript" src="', $arr );
      echo '"></script>';
     */
}

function EBE_add_js_compiler_in_cache(
    $arr_eb_add_full_js,
    $async = '',
    // có tối ưu nội dung file hay không
    $optimize = 0
) {

    global $__cf_row;

    //
    //	print_r( $arr_eb_add_full_js );
    //
    //	if ( eb_code_tester == true ) {
    if ( $__cf_row[ 'cf_js_optimize' ] != 1 ) {
        $content_dir = basename( WP_CONTENT_DIR );
        //		echo $content_dir . "\n";

        //		$ver = web_version;

        foreach ( $arr_eb_add_full_js as $v ) {
            if ( file_exists( $v ) ) {
                $ver = filemtime( $v );

                //				echo ABSPATH . "\n";
                //				$v = str_replace( ABSPATH, '', $v );
                $v = str_replace( '\\', '/', strstr( $v, $content_dir ) );

                echo '<script type="text/javascript" src="' . $v . '?ver=' . $ver . '" defer></script>' . "\n";
            }
        }
        return true;
    }


    // chức năng load nội dung file trực tiếp giống wordpress
    $file_name = array();
    foreach ( $arr_eb_add_full_js as $v ) {
        if ( file_exists( $v ) ) {
            //			echo $v . "\n";

            // nếu trong thư mục mặc định -> lấy tên file là đủ
            if ( strpos( $v, 'echbaydotcom/javascript' ) !== false ) {
                $file_name[] = basename( $v );
            } else {
                $file_name[] = urlencode( strstr( $v, EB_DIR_CONTENT ) );
            }
        }
    }
    //	echo WP_CONTENT_DIR . "\n";
    //	echo EB_DIR_CONTENT . "\n";
    //	echo EB_THEME_CONTENT . "\n";
    //	print_r( $file_name );

    // Nếu script của bạn là một module nào đó, chạy độc lập và không phụ thuộc vào bất kỳ script nào khác thì dùng thuộc tính async
    // Nếu script của bạn phụ thuộc vào một script khác hoặc được một script khác sử dụng lại (phụ thuộc vào) thì nên sử dụng thuộc tính defer
    if ( !empty( $file_name ) ) {
        echo '<script type="text/javascript" src="' . strstr( EB_THEME_PLUGIN_INDEX, EB_DIR_CONTENT ) . 'load-scripts.php?load=' . implode( ',', $file_name ) . '&ver=' . web_version . '" defer></script>' . "\n";
    }

    //
    return true;


    //
    $file_name_cache = '';
    $full_file_name = '';
    foreach ( $arr_eb_add_full_js as $v ) {
        if ( file_exists( $v ) ) {
            //			$file_name_cache .= basename( $v ) . filemtime( $v );
            // thời gian cập nhật file
            //			$file_time = filemtime ( $v );
            //			$file_name_cache .= basename( $v, '.js' ) . substr( $file_time, strlen($file_time) - 3 );
            // chỉ lấy mỗi tên file, thời gian cập nhật file thì cho định kỳ cho nhẹ
            $file_name = basename( $v, '.js' );

            $full_file_name .= '+' . $file_name;

            $file_name_cache .= $file_name;
        }
    }
    //	echo $file_name_cache . '<br>' . "\n";
    $file_name_cache = md5( $file_name_cache );
    $file_show = $file_name_cache;

    // thêm khoảng thời gian lưu file
    $current_server_minute = ( int )substr( date( 'i', date_time ), 0, 1 );
    $file_name_cache = 'zjs-' . $file_name_cache . '-' . $current_server_minute . '.js';

    // file hiển thị sẽ hiển thị sớm hơn chút
    /*
      if ( $current_server_minute == 0 ) {
      $current_server_minute = 5;
      }
      else {
     */
    $current_server_minute = $current_server_minute - 1;
    //	}
    $file_show = 'zjs-' . $file_show . '-' . $current_server_minute . '.js';


    // nếu file có rồi -> nhúng luôn file
    //	$file_in_cache = ABSPATH . EB_DIR_CONTENT . '/uploads/ebcache/' . $file_name_cache;
    $file_in_cache = EB_THEME_CACHE . 'noclean/' . $file_name_cache;
    // chỉ cập nhật file khi có sự thay đổi
    //	if ( file_exists( $file_in_cache ) ) {
    // cập nhật file định kỳ
    if ( !file_exists( $file_in_cache ) || date_time - filemtime( $file_in_cache ) + rand( 0, 30 ) > 500 ) {

        //
        $new_content = '';
        foreach ( $arr_eb_add_full_js as $v ) {
            // xem file có tồn tại không
            if ( file_exists( $v ) ) {
                //			echo $v . "\n";
                // xem trong cache có chưa
                //			$file_name_cache = basename( $v ) . filemtime( $v ) . '.js';
                //			$file_in_cache = ABSPATH . EB_DIR_CONTENT . '/uploads/ebcache/' . $file_name_cache;
                // nếu chưa có -> tạo cache
                //			if ( ! file_exists( $file_in_cache ) ) {
                //				echo $file_in_cache . "\n";
                //
                $file_content = file_get_contents( $v, 1 );

                // thu gọn nội dung
                if ( $optimize == 1 ) {
                    $file_content = WGR_remove_js_comment( $file_content );
                    //					$file_content = WGR_remove_js_multi_comment( $file_content );
                }

                // chỉ gộp nội dung thành 1 file
                //					else {
                $new_content .= $file_content . "\n";
                //					}
                //
                /*
                  $file_content = explode( "\n", $file_content );
                  foreach ( $file_content as $v ) {
                  $v = trim( $v );

                  // tối ưu sơ qua cho nội dung
                  if ( $v == '' || substr( $v, 0, 2 ) == '//' ) {
                  }
                  // tối ưu sâu hơn chút
                  else if ( $optimize == 1 ) {
                  if ( strpos( $v, '//' ) !== false ) {
                  $v .= "\n";
                  }
                  $new_content .= $v;
                  }
                  // gần như không làm gì cả
                  else {
                  $new_content .= $v . "\n";
                  }
                  }
                 */
                //			}
            }
        }
        //		echo $new_content;
        //
        _eb_create_file( $file_in_cache, create_cache_infor_by( $full_file_name ) . $new_content );

        // chưa có file phụ -> tạo luôn file phụ
        if ( !file_exists( EB_THEME_CACHE . 'noclean/' . $file_show ) ) {
            if ( copy( $file_in_cache, EB_THEME_CACHE . 'noclean/' . $file_show ) ) {
                chmod( EB_THEME_CACHE . 'noclean/' . $file_show, 0777 );
            }
        }

        // cập nhật lại version để css mới nhận nhanh hơn
        //		_eb_set_config( 'cf_web_version', date( 'md.Hi', date_time ), 0 );
    }

    //
    echo '<!-- ' . $file_name_cache . ' --><script type="text/javascript" src="' . str_replace( ABSPATH, '', EB_THEME_CACHE ) . 'noclean/' . $file_show . '?v=' . web_version . '" ' . $async . '></script>' . "\n";
}

// tách các phiên bản ra cho nhẹ người code
include EB_THEME_PLUGIN_INDEX . 'functionsResizeImg.php';
include EB_THEME_PLUGIN_INDEX . 'functions1.php';
include EB_THEME_PLUGIN_INDEX . 'functions1_2.php';
include EB_THEME_PLUGIN_INDEX . 'functions1_3.php';
include EB_THEME_PLUGIN_INDEX . 'functionsP2.php';
include EB_THEME_PLUGIN_INDEX . 'functionsP3.php';
include EB_THEME_PLUGIN_INDEX . 'functionsTemplate.php';
include EB_THEME_PLUGIN_INDEX . 'functionsWidget.php';