<?php


//
$custom_product_flex_css = '';
$custom_blog_node_flex_css = '';


//
include EB_THEME_PLUGIN_INDEX . 'global/post.php';


//
//echo '<!-- POST NOT IN: ' . $___eb_post__not_in . ' -->' . "\n";
$___eb_post__not_in .= ',' . $pid;
//echo '<!-- POST NOT IN: ' . $___eb_post__not_in . ' -->' . "\n";


//
/*
if ( $__cf_row['cf_set_news_version'] != 1 ) {
	$web_og_type = 'product';
}
else {
*/
$web_og_type = 'article';
//}


// nếu bài viết được đánh dấu để set noindex -> set thuộc tính noindex
if ( _eb_get_post_object( $pid, '_eb_product_noindex', 0 ) == 1 ) {
    $__cf_row[ "cf_blog_public" ] = 0;
}
/*
if ( mtv_id == 1 ) {
	print_r($__cf_row);
}
*/

//echo __FILE__ . '<br>' . "\n";


// xác định các post type sản phẩm
$bai_san_pham = false;

//
$trv_giaban = 0;
$trv_giamoi = 0;
$pt = 0;
if ( $__post->post_type == 'post' || $__post->post_type == 'product' ) {
    $web_og_type = EBE_get_lang( 'schema_product_type' );
    $bai_san_pham = true;

    //
    if ( $__post->post_type == 'product' ) {
        $trv_giaban = _eb_float_only( _eb_get_post_object( $pid, '_regular_price' ) );
        $trv_giamoi = _eb_float_only( _eb_get_post_object( $pid, '_sale_price' ) );
    } else {
        $trv_giaban = _eb_float_only( _eb_get_post_object( $pid, '_eb_product_oldprice' ) );
        //		echo $trv_giaban . '<br>';
        $trv_giamoi = _eb_float_only( _eb_get_post_object( $pid, '_eb_product_price' ) );
        //		echo $trv_giamoi . '<br>';
        //		echo _eb_get_post_object( $pid, '_eb_product_price' ) . '<br>';
    }

    //
    if ( $trv_giamoi == 0 && $trv_giaban > 0 ) {
        $trv_giamoi = $trv_giaban;
        $trv_giaban = 0;
    }

    //
    $eb_product_price = $trv_giamoi;

    if ( $trv_giaban > $trv_giamoi ) {
        $pt = 100 - _eb_float_only( $trv_giamoi * 100 / $trv_giaban, 1 );
        //		echo $pt;
    } else {
        $trv_giaban = 0;
    }

    //
    $arr_for_add_js[] = EB_THEME_PLUGIN_INDEX . 'javascript/dp.js';
} else {
    $arr_for_add_js[] = EB_THEME_PLUGIN_INDEX . 'javascript/db.js';
}
$trv_luotxem = 0;
$trv_luotthich = 0;
/*
if ( $trv_giamoi == 0 ) {
	$trv_giamoi = _eb_float_only( _eb_get_post_object( $pid, '_price', 0 ) );
}
*/


$eb_blog_2content = '';


/*
 * bổ sung 1 số kiểu dữ liệu mới (nếu chưa có)
 */

// chuyển kiểu dữ liệu sang array để kiểm tra cho dễ
/*
$arr_post_for_check_mysql = (array) $__post;

// nếu chưa có cột giá mới -> add thêm cột giá mới
if ( ! isset( $arr_post_for_check_mysql['_eb_product_price'] ) ) {
	$eb_install_sql = "ALTER TABLE `" . wp_posts . "`
	ADD
		`_eb_product_price` INT(11) NOT NULL
	AFTER
		`menu_order`";
//	echo $eb_install_sql . '<br>' . "\n";
	_eb_q($eb_install_sql, 0);
}
// nếu có giá mới, mà cột giá mới chưa có update -> update cột giá
else if ( $arr_post_for_check_mysql['_eb_product_price'] != $trv_giamoi ) {
	_eb_q("UPDATE `" . wp_posts . "`
	SET
		_eb_product_price = " . $trv_giamoi . "
	WHERE
		ID = " . $__post->ID, 0);
}
*/
/*
else {
	echo 'no update<br>';
}
*/


//
$id_for_get_sidebar = '';


// blog
if ( $__post->post_type == EB_BLOG_POST_TYPE ) {

    //	$link_for_fb_comment = web_link . '?post_type=' . EB_BLOG_POST_TYPE . '&p=' . $pid;

    // bài báo
    //	$web_og_type = 'article';

    $post_categories = get_the_terms( $pid, EB_BLOG_POST_LINK );

    //
    if ( $__cf_row[ 'cf_on_off_amp_blog' ] == 1 ) {
        $global_dymanic_meta .= '<link rel="amphtml" href="' . $url_og_url . '?amp" />';
    }

    // load sidebar -> nếu có
    //	if ( $__cf_row['cf_blog_column_style'] == '' ) {
    if ( $__cf_row[ 'cf_blog_column_style' ] != '' ) {
        //		$id_for_get_sidebar = '';
        //	} else {
        $id_for_get_sidebar = 'blog_details_sidebar';
    }
}
// post
//else if ( $__post->post_type == 'post' || $__post->post_type == 'product' ) {
else if ( $bai_san_pham == true ) {
    if ( $__post->post_type == 'product' ) {
        $post_categories = get_the_terms( $pid, 'product_cat' );
    } else {
        $post_categories = wp_get_post_categories( $pid );
    }

    if ( $__cf_row[ 'cf_on_off_amp_product' ] == 1 ) {
        $global_dymanic_meta .= '<link rel="amphtml" href="' . $url_og_url . '?amp" /><link rel="amphtmldevelopment" href="' . $url_og_url . '?amp#development=1" />';
    }

    // load sidebar -> nếu có
    if ( $__cf_row[ 'cf_post_column_style' ] != '' ) {
        $id_for_get_sidebar = 'post_sidebar';
    }
}
// page
else if ( $__post->post_type == 'page' ) {
    // load sidebar -> nếu có
    if ( $__cf_row[ 'cf_page_column_style' ] != '' ) {
        $id_for_get_sidebar = 'page_sidebar';
    }
}
//if ( mtv_id == 1 ) print_r( $post_categories );


//
$cats = array();
$cats_child = array();
$cats2_child = array();
$ant_link = '';
$ant_ten = '';
$ant_id = 0;
$bnt_id = 0;
$other_option_list = '';

//
//if ( isset( $post_categories[0] ) ) {
if ( !empty( $post_categories ) ) {
    //	print_r( $post_categories );

    // parent -> lấy các nhóm cấp 1 trước
    foreach ( $post_categories as $c ) {
        $cat = get_term( $c );
        //		print_r( $cat );
        //		$cat = get_category( $c );
        //		print_r( $cat );

        // chỉ lấy các nhóm không bị khóa bởi EchBay
        if ( _eb_get_cat_object( $cat->term_id, '_eb_category_hidden', 0 ) != 1 ) {
            // parent
            if ( $cat->parent == 0 ) {
                $cats[] = $cat;

                //
                $ant_link = _eb_c_link( $cat->term_id );
                //				echo $ant_link . '<br>' . "\n";

                //
                $schema_BreadcrumbList[ $ant_link ] = _eb_create_breadcrumb( $ant_link, $cat->name, $cat->term_id );
            }
            // child -> nhóm cấp 2 để sau
            else {
                if ( $bnt_id == 0 ) {
                    $bnt_id = $cat->term_id;
                }

                $cats_child[] = $cat;
            }
        }

        // ưu tiên lấy tên nhóm sản phẩm theo nhóm chính
        if ( $ant_ten == '' && _eb_get_cat_object( $cat->term_id, '_eb_category_primary', 0 ) == 1 ) {
            $ant_ten = $cat->name;
            $ant_id = $cat->term_id;
            $cid = $ant_id;
        }
    }
    //	print_r( $cats_child );

    // kiểm tra nhóm cấp 2 với cấp 3
    if ( !empty( $cats_child ) ) {
        $check_arr_exits = array();

        // lắp vào 1 mảng phụ để kiểm tra
        foreach ( $cats_child as $cat ) {
            $check_arr_exits[] = $cat->term_id;
        }

        // kiểm tra nhóm cấp 2 mà thuộc cấp 3 -> cho vào nhóm cấp 3
        foreach ( $cats_child as $k => $cat ) {
            if ( in_array( $cat->parent, $check_arr_exits ) ) {
                $cats2_child[] = $cat;
                $cats_child[ $k ] = NULL;
            }
        }
        //		print_r( $cats_child );
        //		print_r( $cats2_child );

        // child -> giờ mới lấy nhóm cấp 2
        foreach ( $cats_child as $cat ) {
            if ( $cat != NULL ) {
                $ant_link = _eb_c_link( $cat->term_id );
                //				echo $ant_link . '<br>' . "\n";

                //
                $schema_BreadcrumbList[ $ant_link ] = _eb_create_breadcrumb( $ant_link, $cat->name, $cat->term_id );
            }
        }

        // -> tiếp tục nhóm cấp 3 nếu có
        if ( !empty( $cats2_child ) ) {
            foreach ( $cats2_child as $cat ) {
                $ant_link = _eb_c_link( $cat->term_id );
                //				echo $ant_link . '<br>' . "\n";

                //
                $schema_BreadcrumbList[ $ant_link ] = _eb_create_breadcrumb( $ant_link, $cat->name, $cat->term_id );
            }
        }

        // nhóm cấp 4 thì chịu chết -> cấp 3 là đủ rồi

    }

}
//if ( mtv_id == 1 ) print_r( $cats );

//
//if ( isset( $cats[0] ) ) {
if ( !empty( $cats ) ) {
    if ( $ant_ten == '' ) {
        $ant_ten = $cats[ 0 ]->name;
        $ant_id = $cats[ 0 ]->term_id;
        $cid = $ant_id;
    }

    // tìm nhóm cha (nếu có)
    $parent_cid = _eb_create_html_breadcrumb( $cats[ 0 ] );
} else if ( $bnt_id > 0 ) {
    // tìm nhóm cấp 1 nếu chưa có
    //	print_r( $cats_child );
    if ( $parent_cid == 0 && !empty( $cats_child ) ) {
        foreach ( $cats_child as $cat ) {
            if ( $cat != NULL ) {
                //				print_r( $cat );
                $get_parent_cid = get_term( $cat->parent );
                //				print_r( $get_parent_cid );

                // tồn tại mảng thì thêm nhóm này vào đầu Breadcrumb
                if ( !empty( $get_parent_cid ) ) {
                    $parent_cid = $get_parent_cid->term_id;
                    $ant_link = _eb_c_link( $parent_cid );
                    //					echo $ant_link . '<br>' . "\n";

                    //
                    $schema_BreadcrumbList = array_merge( array(
                        $ant_link => _eb_create_breadcrumb( $ant_link, $get_parent_cid->name, $parent_cid, '', true )
                    ), $schema_BreadcrumbList );
                }
            }
        }
    }

    //
    $ant_id = $bnt_id;
    $cid = $bnt_id;
}
//echo $parent_cid . '<br>';


// Nếu chế độ tải banner cho trang chi tiết bị tắt thì thôi
if ( $__cf_row[ 'cf_post_big_banner' ] != 1 ) {
    $str_big_banner = '<!-- Big banner disable by cf_post_big_banner in Theme Config - Post -->';
}
// Chỉ lấy banner riêng khi chế độ global không được kích hoạt
else if ( $__cf_row[ 'cf_global_big_banner' ] != 1 ) {
    // đây là page -> chưa làm
    if ( $__post->post_type == 'page' ) {}
    // Mặc định chỉ lấy cho phần post/ blog
    else {
        if ( $cid > 0 ) {
            if ( $__post->post_type == EB_BLOG_POST_TYPE ) {
                $arr_get_big_banner = array(
                    'tax_query' => array(
                        array(
                            'taxonomy' => EB_BLOG_POST_LINK,
                            'field' => 'term_id',
                            'terms' => $cid,
                            'operator' => 'IN'
                        )
                    )
                );
            } else {
                $arr_get_big_banner = array(
                    'category__in' => array( $cid )
                );
            }
        } else {
            $arr_get_big_banner = array(
                'category__in' => ''
            );
        }

        //
        $str_big_banner = EBE_get_big_banner( EBE_get_lang( 'bigbanner_num' ), $arr_get_big_banner );
        //		echo $str_big_banner;
    }
}


// Hết hoặc Còn hàng
$trv_mua = 0;
$trv_max_mua = 0;
$str_tinh_trang = '<span class="greencolor">' . EBE_get_lang( 'post_instock' ) . '</span>';
$con_hay_het = 1;
$trv_trangthai = 0;
$schema_availability = 'http://schema.org/InStock';

//$arr_product_color = '';

$post_modified = strtotime( $__post->post_modified );
$schema_priceValidUntil = $post_modified + 24 * 3600 * 365;

$trv_rating_value = 0;
$trv_rating_count = 0;
$rating_value_img = '5.0';

$product_js = '';

$product_size = '';

$product_color_name = '';
$_eb_product_chinhhang = 0;
$_eb_product_video_url = '';
$trv_masanpham = '';


//
//if ( $__post->post_type == 'post' ) {
if ( $bai_san_pham == true ) {

    //
    if ( $__post->post_type == 'product' ) {

        //
        $trv_masanpham = _eb_get_post_object( $pid, '_sku' );

        //
        $trv_mua = _eb_number_only( _eb_get_post_object( $pid, '_stock_status', '' ) );
        $trv_max_mua = _eb_number_only( _eb_get_post_object( $pid, '_stock', 0 ) );

        //
        if ( $trv_mua == 'instock' ) {
            $trv_mua = 0;
            $trv_trangthai = 7;
        } else {
            $trv_mua = $trv_max_mua;
            $trv_trangthai = 1;
        }

    } else {

        //
        $trv_masanpham = _eb_get_post_object( $pid, '_eb_product_sku' );

        //
        $product_color_name = _eb_str_block_fix_content( _eb_get_post_object( $pid, '_eb_product_color' ) );
        $_eb_product_chinhhang = _eb_get_post_object( $pid, '_eb_product_chinhhang', 0 );

        // product size
        $product_size = _eb_get_post_object( $pid, '_eb_product_size' );

        //
        $trv_mua = _eb_number_only( _eb_get_post_object( $pid, '_eb_product_buyer', 0 ) );
        $trv_max_mua = _eb_number_only( _eb_get_post_object( $pid, '_eb_product_quantity', 0 ) );

        //
        $trv_trangthai = _eb_get_post_object( $pid, '_eb_product_status', 0 );
        $trv_trangthai *= 1;

        //
        $trv_rating_value = _eb_get_post_object( $pid, '_eb_product_rating_value', 0 );
        $trv_rating_count = _eb_get_post_object( $pid, '_eb_product_rating_count', 0 );

        // Tạo rate ngẫu nhiên
        if ( $trv_rating_value < 6 || $trv_rating_count == 0 ) {
            $trv_rating_value = rand( 6, 10 );
            $trv_rating_count = rand( 1, 5 );

            // dùng update_post_meta thay cho add_post_meta
            WGR_update_meta_post( $pid, '_eb_product_rating_value', $trv_rating_value );
            WGR_update_meta_post( $pid, '_eb_product_rating_count', $trv_rating_count );

            //
            /*
            $arr_object_post_meta['_eb_product_rating_value'] = $trv_rating_value;
            $arr_object_post_meta['_eb_product_rating_count'] = $trv_rating_count;
			
            WGR_update_meta_post( $pid, eb_post_obj_data, $arr_object_post_meta );
            */
        }

    }


    //
    if ( $trv_masanpham == '' ) {
        $trv_masanpham = $pid;
        $d_none_sku = ' d-none';
    } else {
        $d_none_sku = '';
    }

    // thêm mã sản phẩm
    $other_option_list .= '
<tr class="post-details-sku' . $d_none_sku . '">
	<td><div>' . EBE_get_lang( 'post_sku' ) . '</div></td>
	<td><div>' . $trv_masanpham . '</div></td>
</tr>';


    //
    if ( $product_size != '' ) {
        if ( substr( $product_size, 0, 1 ) == ',' ) {
            $product_size = substr( $product_size, 1 );
        }
        $product_size = str_replace( '"', '\"', $product_size );
    }


    //
    $arr_product_js = array(
        'tieude' => '\'' . _eb_str_block_fix_content( $__post->post_title ) . '\'',
        'sku' => '\'' . $trv_masanpham . '\'',
        'gia' => $trv_giaban,
        'gm' => $trv_giamoi
    );
    foreach ( $arr_product_js as $k => $v ) {
        $product_js .= ',' . $k . ':' . $v;
    }

    //
    if ( $trv_trangthai === 7 || ( $trv_mua > 0 && $trv_mua >= $trv_max_mua ) ) {
        $schema_availability = 'http://schema.org/SoldOut';

        $str_tinh_trang = '<span class="redcolor">' . EBE_get_lang( 'post_outstock' ) . '</span>';

        $con_hay_het = 0;

        // thêm class ẩn nút mua hàng
        $css_m_css[] = 'details-hideif-hethang';
    }

    // Thêm phần tình trạng hàng hóa nếu người dùng có thiết lập số lượng
    if ( $trv_max_mua > 0 && $trv_mua < $trv_max_mua ) {
        $d_none_stock = '';
    } else {
        $d_none_stock = ' d-none';
    }

    //
    $other_option_list .= '
<tr class="post-details-stock' . $d_none_stock . '">
	<td><div>' . EBE_get_lang( 'post_stock' ) . '</div></td>
	<td><div>' . $str_tinh_trang . '</div></td>
</tr>';


    //
    /*
    if ( $trv_rating_value == '' ) {
    	$trv_rating_value = 0;
    }
    //echo $trv_rating_value . "\n";
	
    if ( $trv_rating_count == '' ) {
    	$trv_rating_count = 0;
    }
    */
    //echo $trv_rating_count . "\n";

    $rating_value_img = $trv_rating_value / 2;
    if ( strlen( $rating_value_img ) == 1 ) {
        $rating_value_img = $rating_value_img . '.0';
    }

}


// dữ liệu có cấu trúc
// https://developers.google.com/search/docs/data-types/product
// https://developers.google.com/search/docs/data-types/article
$structured_data_detail = '';
$structured_data_post_title = str_replace( '"', '&quot;', $__post->post_title );

//
$trv_width_img = 400;
$trv_height_img = 400;
//$get_img_size = str_replace( web_link, ABSPATH, $trv_img );
//$get_img_size = ABSPATH . strstr( $trv_img, EB_DIR_CONTENT );
$get_img_size = explode( EB_DIR_CONTENT, $trv_img );
//print_r( $get_img_size );

//
if ( count( $get_img_size ) > 1 ) {
    $get_img_size[ 0 ] = ABSPATH;
    $get_img_size = implode( EB_DIR_CONTENT, $get_img_size );
} else {
    $get_img_size = '';
}


//
$this_post_author = get_the_author();
if ( $this_post_author == '' ) {
    $this_post_author = get_the_author_meta( 'first_name', $__post->post_author );
    if ( $this_post_author == '' ) {
        $this_post_author = get_the_author_meta( 'nickname', $__post->post_author );
    }
}
//echo $this_post_author . '<br>' . "\n";

//
//echo $trv_img . '<br>';
//echo $get_img_size . '<br>';
if ( $get_img_size != '' && file_exists( $get_img_size ) ) {
    $get_img_size = getimagesize( $get_img_size );
    //	print_r( $get_img_size );
    $trv_width_img = $get_img_size[ 0 ];
    $trv_height_img = $get_img_size[ 1 ];
}

if ( $trv_giamoi > 0 ) {

    // giá cho coc coc đỡ hiển thị phần so sánh giá khi người dùng truy cập bằng coc coc
    $trv_coccoc_giamoi = $trv_giamoi;
    if ( $__cf_row[ 'cf_coccoc_discount_price' ] > 0 && $__cf_row[ 'cf_coccoc_discount_price' ] < 100 ) {
        $trv_coccoc_giamoi = $trv_coccoc_giamoi / 100 * $__cf_row[ 'cf_coccoc_discount_price' ];
    }

    // loại bỏ phần mã dư thừa để không bị lỗi trên fb
    $structured_data_detail = _eb_del_line( WGR_remove_js_comment( WGR_remove_js_multi_comment( '
<script type="application/ld+json" class="echbaydotcom-schema-graph">
{
	"@context": "http:\/\/schema.org\/",
	"@type": "' . EBE_get_lang( 'schema_post_type' ) . '",
	"name": "' . $structured_data_post_title . '",
//	"image": "' . str_replace( '/', '\/', $trv_img ) . '",
	"image": {
		"@type": "ImageObject",
		"width": "' . $trv_width_img . '",
		"height": "' . $trv_height_img . '",
		"url": "' . str_replace( '/', '\/', $trv_img ) . '"
	},
	"description": "' . $__cf_row[ 'cf_description' ] . '",
//	"mpn": "' . $pid . '"
	"url": "' . str_replace( '/', '\/', $url_og_url ) . '",
    "mpn": "",
	//
    "review": {
        "@type": "Review",
        "reviewRating": {
            "@type": "Rating",
            "ratingValue": "' . $rating_value_img . '",
            "bestRating": "5"
        },
        "author": {
            "@type": "Person",
            "name": "' . $this_post_author . '"
        }
    },
	"aggregateRating": {
		"@type": "AggregateRating",
		"ratingValue": "' . $rating_value_img . '",
		"reviewCount": "' . $trv_rating_count . '"
	},
	"offers": {
		"@type": "Offer",
//		"priceCurrency": "VND",
		"priceCurrency": "' . $__cf_row[ 'cf_current_sd_price' ] . '",
//		"price": "' . $trv_giamoi . '",
		"price": "' . $trv_coccoc_giamoi . '",
		"priceValidUntil": "' . date( 'Y-m-d', $schema_priceValidUntil ) . '",
        "url": "' . str_replace( '/', '\/', $url_og_url ) . '",
		/*
		"seller": {
			"@type": "Organization",
			"name": "Executive Objects"
		},
		*/
//		"itemCondition": "http:\/\/schema.org\/UsedCondition",
		"availability": "' . $schema_availability . '"
	},
	
	"brand": {
		"@type": "Thing",
		"name": "' . str_replace( '"', '&quot;', $ant_ten ) . '"
	},
    ' . ( $trv_masanpham != '' ? '"sku":"' . $trv_masanpham . '",' : '' ) . '
	
	//
	"productID": "' . $pid . '"
}
</script>' ), true ) );

}
// v2 -> đang ngâm cứu
//if ( $bai_san_pham == false || EBE_get_lang('schema_post_type') != 'Product' ) {
else {

    //
    $blog_img_logo = $__cf_row[ 'cf_logo' ];
    if ( strstr( $blog_img_logo, '//' ) == false ) {
        if ( substr( $blog_img_logo, 0, 1 ) == '/' ) {
            $blog_img_logo = substr( $blog_img_logo, 1 );
        }

        //
        $blog_img_logo = web_link . $blog_img_logo;
    }

    //
    $structured_data_detail = _eb_del_line( WGR_remove_js_comment( WGR_remove_js_multi_comment( '
<script type="application/ld+json" class="echbaydotcom-schema-graph">
{
	"@context": "http:\/\/schema.org",
	"@type": "' . EBE_get_lang( 'schema_blog_type' ) . '",
	"publisher": {
		"@type": "Organization",
		"name": "' . str_replace( '"', '&quot;', web_name ) . '",
		"logo": {
			"@type": "ImageObject",
			"url": "' . str_replace( '/', '\/', $blog_img_logo ) . '"
		}
	},
	"mainEntityOfPage": "' . str_replace( '/', '\/', $url_og_url ) . '",
	"headline": "' . $structured_data_post_title . '",
	"datePublished": "' . $__post->post_date . '",
	"dateModified": "' . $__post->post_modified . '",
	"author": {
		"@type": "Person",
		"name": "' . $this_post_author . '"
	},
	"description": "' . str_replace( '"', '&quot;', $__cf_row[ 'cf_description' ] ) . '",
	"image": {
		"@type": "ImageObject",
		"width": "' . $trv_width_img . '",
		"height": "' . $trv_height_img . '",
		"url": "' . str_replace( '/', '\/', $trv_img ) . '"
	}
}
</script>' ), true ) );


    // v2 -> đang ngâm cứu
    /*
    $structured_data_detail = file_get_contents( EB_THEME_PLUGIN_INDEX . 'html/structured-data/Article.js', 1 );
	//$structured_data_detail = _eb_del_line( WGR_remove_js_comment ( WGR_remove_js_multi_comment ( $structured_data_detail ) ) );
    $structured_data_detail = EBE_html_template( $structured_data_detail, [
        'tmp.web_link' => web_link,
        'tmp.structured_data_post_title' => $structured_data_post_title,
    ] );
    $structured_data_detail = '<script type="application/ld+json" class="echbaydotcom-schema-graph">' . $structured_data_detail . '</script>';
	*/
}

//echo EB_THEME_PLUGIN_INDEX . '<br>' . "\n";

//
if ( $structured_data_detail != '' ) {
    $structured_data_detail = preg_replace( "/\t/", "", trim( $structured_data_detail ) );

    $dynamic_meta .= $structured_data_detail;
}


//
//$group_go_to[] = ' <li>' . $__post->post_title . '</li>';
//echo $group_go_to;
//print_r($group_go_to);

//
$schema_BreadcrumbList[ $url_og_url ] = _eb_create_breadcrumb( $url_og_url, $__post->post_title );


// tự làm amp cho khách hàng
if ( isset( $_GET[ 'amp' ] ) ) {
    if ( ( $__post->post_type == EB_BLOG_POST_TYPE && $__cf_row[ 'cf_on_off_amp_blog' ] == 1 )
        //	|| ( $__post->post_type == 'post' && $__cf_row['cf_on_off_amp_product'] == 1 )
        ||
        ( $bai_san_pham == true && $__cf_row[ 'cf_on_off_amp_product' ] == 1 ) ) {
        include EB_THEME_PLUGIN_INDEX . 'amp.php';
    }
}


/*
 * cho những thứ không cần real vào cache
 */
/*
$strCacheFilter = 'details/' . $pid;
$main_content = _eb_get_static_html ( $strCacheFilter );
if ($main_content == false) {
	*/


//
//$thu_muc_for_html = EB_THEME_HTML;


// Mặc định là cho vào sản phẩm
$html_v2_file = 'thread_details';
//$html_file = 'thread_details.html';

$arr_list_tag = array();
$blog_list_medium = '';
$product_list_medium = '';
$other_post_right = '<!-- Chi tiết Sản phẩm -->';
//$other_post_right = '';
$other_post_2right = '<!-- Chi tiết Sản phẩm (2) -->';
$other_post_3right = '<!-- Chi tiết Sản phẩm (3) -->';
$str_for_details_sidebar = '';
$str_for_details_top_sidebar = '';

//
$product_gallery = '';
$product_list_color = '';
$wgr_comment_list = '';
$each_to_share_social = '';
if ( $__cf_row[ 'cf_details_show_share_button' ] == 1 ) {
    $each_to_share_social = WGR_get_html_template_lang( 'share_to_social' );

    $arr_for_add_css[ EB_THEME_PLUGIN_INDEX . 'css/template/share_to_social.css' ] = 1;
}

//
$post_buy_size_color = '';
$post_buy_bottom = '';


//
$trv_h1_tieude = str_replace( '<', '&lt;', str_replace( '>', '&gt;', $__post->post_title ) );


// post
if ( $__post->post_type == 'post' ) {

    //
    $product_gallery = _eb_get_post_object( $pid, '_eb_product_gallery' );
    $product_gallery = str_replace( ' src=', ' data-src=', $product_gallery );
    $product_gallery = str_replace( ' data-src=', ' src="' . EB_URL_OF_PLUGIN . 'images-global/_blank.png" data-src=', $product_gallery );

    $product_list_color = _eb_get_post_object( $pid, '_eb_product_list_color' );
    $product_list_color = str_replace( ' src=', ' data-src=', $product_list_color );
    $product_list_color = str_replace( ' data-src=', ' src="' . EB_URL_OF_PLUGIN . 'images-global/_blank.png" data-src=', $product_list_color );

    //
    include EB_THEME_PLUGIN_INDEX . 'global/details_post.php';
    //	include EB_THEME_PLUGIN_INDEX . 'global/details_comments.php';
    $wgr_comment_list = '<div data-id="' . $pid . '" id="comments" class="comments-area"></div>';


    //
    if ( $__cf_row[ 'cf_details_buttonmua' ] != 'none' ) {
        // menu nút mua ở botton trước, để nó laod css dùng chung
        $post_buy_bottom = '<div class="detail-muangay-padding detail-muangay-bottom-padding">' . WGR_get_html_template_lang( 'post_buy_bottom', '', EB_THEME_PLUGIN_INDEX . 'html/details/' ) . '</div>';


        // nếu người dùng không tự tạo html
        if ( EBE_get_lang( 'post_size_color' ) == 'post_size_color' ) {
            // lấy module tạo size, color
            $post_buy_size_color = WGR_get_html_template_lang( 'post_size_color', '', EB_THEME_PLUGIN_INDEX . 'html/details/' );

            // thêm nút mua
            $post_buy_size_color .= EBE_html_template(
                EBE_get_custom_template( $__cf_row[ 'cf_details_buttonmua' ], 'details/pcmua' ),
                array()
            );
        }

        /*
        $post_buy_size_color = EBE_html_template( $post_buy_size_color, array(
        	'tmp.post-buy-button' => WGR_get_html_template_lang( 'post_buy' )
        ) );
        */
    }

}
// với blog -> sử dụng giao diện khác post
// cơ bản là các hàm khác với post mặc định
else {
    include EB_THEME_PLUGIN_INDEX . 'global/details_count_view.php';
    if ( file_exists( EB_THEME_PLUGIN_INDEX . 'global/details_' . $__post->post_type . '.php' ) ) {
        include EB_THEME_PLUGIN_INDEX . 'global/details_' . $__post->post_type . '.php';
    } else {
        include EB_THEME_PLUGIN_INDEX . 'global/details_blog.php';
    }

    //
    /*
    if ( $__post->post_type == 'product' || $__post->post_type == EB_BLOG_POST_TYPE ) {
    	include EB_THEME_PLUGIN_INDEX . 'global/details_comments.php';
    }
    */
    $wgr_comment_list = '<div data-id="' . $pid . '" id="comments" class="comments-area"></div>';
}

//
$str_tags = '';
if ( !empty( $arr_list_tag ) ) {
    //	print_r( $arr_list_tag );
    foreach ( $arr_list_tag as $v ) {
        //		$str_tags .= '<a href="' . get_tag_link( $v->term_id ) . '">' . $v->name . '</a> ';
        $str_tags .= '<a href="' . _eb_c_link( $v->term_id, $v->taxonomy ) . '">' . $v->name . '</a> ';
    }
}


// the_content -> chỉ chạy khi gọi the_post -> dùng hàm the_content để tạo nội dung theo chuẩn wp
the_post();

// dùng ob để lấy nội dung đã được echo thay vì echo trực tiếp
ob_start();

the_content();
$trv_noidung = ob_get_contents();

//ob_clean();
//ob_end_flush();
ob_end_clean();

//echo $trv_noidung;


//
/*
if ( $__post->post_type == 'post' ) {
}
*/


// số Hotline -> luôn lấy số đầu tiên nếu có nhiều số
$cf_hotline = explode( "\n", trim( $__cf_row[ 'cf_hotline' ] ) );
$cf_hotline = trim( $cf_hotline[ 0 ] );


//
if ( $__cf_row[ 'cf_set_link_for_h1' ] == 1 ) {
    $h1_rel_nofollow = ' rel="nofollow"';
    if ( $__cf_row[ 'cf_set_nofollow_for_h1' ] != 1 ) {
        $h1_rel_nofollow = '';
    }
    $set_link_for_h1 = '<a href="' . $url_og_url . '"' . $h1_rel_nofollow . '>' . $trv_h1_tieude . '</a>';
} else {
    $set_link_for_h1 = $trv_h1_tieude;
}


//
$str_show_quick_cart = '';
if ( $trv_trangthai !== 7 && $__post->post_status === 'publish' ) {
    $str_show_quick_cart = $__cf_row[ 'cf_details_show_quick_cart' ] == 1 ? '<div class="clone-show-quick-cart"></div>' : '<div class="clone-show-mobile-quick-cart"></div>';
}


// tạo mảng để khởi tạo nội dung
$arr_main_content = array(
    'tmp.trv_id' => $pid,
    'tmp.trv_masanpham' => $trv_masanpham,
    //	'tmp.trv_masanpham' => $trv_masanpham == '' ? '#' . $pid : $trv_masanpham,

    'tmp.link_for_fb_comment' => $link_for_fb_comment,
    //	'tmp.html_for_fb_comment' => '<div class="fb-comments" data-href="' . $link_for_fb_comment . '" data-width="100%" data-numposts="{tmp.fb_num_comments}"></div>' . $wgr_comment_list,

    'tmp.trv_tieude' => $trv_h1_tieude,
    'tmp.trv_h1_tieude' => $set_link_for_h1,

    'tmp.trv_luotxem' => $trv_luotxem,
    'tmp.trv_luotthich' => $trv_luotthich,

    'tmp.cf_hotline' => $cf_hotline,

    'tmp.trv_goithieu' => $__post->post_excerpt != '' ? '<div class="blog-details-excerpt">' . $__post->post_excerpt . '</div>' : '',
    'tmp.trv_noidung' => $trv_noidung,
    'tmp.trv_dieukien' => _eb_get_post_object( $pid, '_eb_product_dieukien' ),
    'tmp.trv_tomtat' => _eb_get_post_object( $pid, '_eb_product_noibat' ),

    'tmp.trv_img' => $trv_img,

    'tmp.ant_link' => _eb_c_link( $ant_id ),
    'tmp.ant_ten' => $ant_ten,

    'tmp.trv_galerry' => $product_gallery,
    'tmp.trv_list_color' => $product_list_color,

    'tmp.trv_mua' => $trv_mua,
    'tmp.trv_max_mua' => $trv_max_mua,
    'tmp.str_tinh_trang' => $str_tinh_trang,

    'tmp.blog_list_medium' => $blog_list_medium,
    'tmp.product_list_medium' => $product_list_medium,

    'tmp.other_post_right' => $other_post_right,
    'tmp.other_post_2right' => $other_post_2right,
    'tmp.other_post_3right' => $other_post_3right,

    'tmp.other_option_list' => $other_option_list,

    'tmp.rating_value_img' => $rating_value_img,
    //	'tmp.str_tags' => substr( $str_tags, 1 ),
    'tmp.str_tags' => $str_tags,

    //	'tmp.bl_ngaygui' => date( 'd/m/Y H:i T', $post_modified ),
    'tmp.bl_ngaygui' => date( 'd/m/Y H:i T', strtotime( $__post->post_date ) ),

    'tmp.trv_giaban' => EBE_add_ebe_currency_class( $trv_giaban, 1 ),
    'tmp.trv_giamoi' => EBE_add_ebe_currency_class( $trv_giamoi ),
    'tmp.trv_num_giamoi' => $trv_giamoi,
    'tmp.pt' => $pt,
    'tmp.trv_tietkiem' => ( $trv_giamoi > 0 ) ? EBE_add_ebe_currency_class( $trv_giaban - $trv_giamoi ) : '',

    'tmp.cf_product_details_size' => $__cf_row[ 'cf_product_details_size' ],
    //	'tmp.cf_product_details_mobile_size' => $__cf_row['cf_product_details_mobile_size'],
    'tmp.cf_diachi' => nl2br( $__cf_row[ 'cf_diachi' ] ),

    'tmp.p_link' => $url_og_url,

    // chèn class tính chiều rộng cho khung
    //	'tmp.custom_blog_css' => $__cf_row['cf_blog_class_style'],
    'tmp.cf_blogd_class_style' => $__cf_row[ 'cf_blogd_class_style' ],
    'tmp.cf_blog_num_line' => $__cf_row[ 'cf_blog_num_line' ],
    'tmp.custom_blog_node_flex_css' => $custom_blog_node_flex_css,

    'tmp.custom_page_css' => $__cf_row[ 'cf_page_class_style' ],

    'tmp.cf_post_class_style' => $__cf_row[ 'cf_post_class_style' ],

    'tmp.custom_product_flex_css' => $custom_product_flex_css,

    'tmp.str_for_details_sidebar' => $str_for_details_sidebar,
    'tmp.str_for_details_top_sidebar' => $str_for_details_top_sidebar,

    // phom mua ngay
    'tmp.clone-show-quick-cart' => $str_show_quick_cart,

    // mặt nạ cho nội dung
    'tmp.thread_content_mask' => $__cf_row[ 'cf_set_mask_for_details' ] == 1 ? ' active-content-mask' : '',

    // tìm và tạo sidebar luôn
    //	'tmp.str_sidebar' => _eb_echbay_sidebar( $id_for_get_sidebar ),

    'tmp.cf_img_details_maxwidth' => $__cf_row[ 'cf_img_details_maxwidth' ],
    'tmp.cf_num_details_list' => $__cf_row[ 'cf_num_details_list' ],
    'tmp.cf_num_details_blog_list' => $__cf_row[ 'cf_num_details_blog_list' ],

    //
    'tmp.eb_blog_2content' => $eb_blog_2content,

    // lang
    'tmp.lang_btn_giacu' => EBE_get_lang( 'post_giacu' ),
    'tmp.lang_btn_giamgia' => EBE_get_lang( 'post_giamgia' ),
    'tmp.lang_btn_giamoi' => EBE_get_lang( 'post_giamoi' ),

    'tmp.post-buy-size-color' => $post_buy_size_color,
    'tmp.post-buy-bottom' => $post_buy_bottom,

    'tmp.each-to-share-social' => $each_to_share_social,
    'tmp.lang_btn_kichco' => EBE_get_lang( 'cart_kichco' ),
    'tmp.lang_btn_mausac' => EBE_get_lang( 'cart_mausac' ),
    'tmp.lang_btn_muangay' => EBE_get_lang( 'muangay' ),
    'tmp.add_to_cart' => EBE_get_lang( 'add_to_cart' ),
    'tmp.lang_details_tu_van' => EBE_get_lang( 'details_tu_van' ),
    'tmp.lang_details2_tu_van' => EBE_get_lang( 'details2_tu_van' ),
    'tmp.lang_details_share' => EBE_get_lang( 'details_share' ),

    'tmp.lang_btn_comment' => EBE_get_lang( 'post_comment' ),
    'tmp.lang_btn_content' => EBE_get_lang( 'post_content' ),
    'tmp.lang_btn_other' => EBE_get_lang( 'post_other' ),
    'tmp.post_css_other' => EBE_get_lang( 'post_css_other' ),
    'tmp.post_column_other' => EBE_get_lang( 'post_column_other' ),
    'tmp.lang_chitiet_sanpham' => EBE_get_lang( 'chitietsp' ),
    'tmp.lang_sanpham_tuongtu' => EBE_get_lang( 'tuongtu' ),

    // slogan
    'tmp.lang_ebslogan1' => EBE_get_lang( 'ebslogan1' ),
    'tmp.lang_ebslogan2' => EBE_get_lang( 'ebslogan2' ),
    'tmp.lang_ebslogan3' => EBE_get_lang( 'ebslogan3' ),
    'tmp.lang_ebslogan4' => EBE_get_lang( 'ebslogan4' ),

    //
    'tmp.lang_post_custom_text' => EBE_get_lang( 'post_custom_text' ),
    'tmp.lang_post_custom_text1' => EBE_get_lang( 'post_custom_text1' ),
    'tmp.lang_post_custom_text2' => EBE_get_lang( 'post_custom_text2' ),
    'tmp.lang_post_custom_text3' => EBE_get_lang( 'post_custom_text3' ),
    'tmp.lang_post_custom_text4' => EBE_get_lang( 'post_custom_text4' ),
    'tmp.lang_post_custom_text5' => EBE_get_lang( 'post_custom_text5' ),
    'tmp.lang_post_custom_text6' => EBE_get_lang( 'post_custom_text6' ),
    'tmp.lang_post_custom_text7' => EBE_get_lang( 'post_custom_text7' ),
    'tmp.lang_post_custom_text8' => EBE_get_lang( 'post_custom_text8' ),
    'tmp.lang_post_custom_text9' => EBE_get_lang( 'post_custom_text9' ),
    'tmp.lang_post_custom_text10' => EBE_get_lang( 'post_custom_text10' ),

    'tmp.lang_blog_views' => EBE_get_lang( 'blog_views' ),
    'tmp.lang_blog_other' => EBE_get_lang( 'blog_other' ),
    'tmp.lang_blog_custom_text' => EBE_get_lang( 'blog_custom_text' ),
    'tmp.lang_blog_custom_text1' => EBE_get_lang( 'blog_custom_text1' ),
    'tmp.lang_blog_custom_text2' => EBE_get_lang( 'blog_custom_text2' ),
    'tmp.lang_blog_custom_text3' => EBE_get_lang( 'blog_custom_text3' ),
    'tmp.lang_blog_custom_text4' => EBE_get_lang( 'blog_custom_text4' ),
    'tmp.lang_blog_custom_text5' => EBE_get_lang( 'blog_custom_text5' )
);

//
$arr_main_content[ 'tmp.html_for_fb_comment' ] = '';

if ( $__post->post_type == 'post' ) {
    // cmt fb
    if ( $__cf_row[ 'cf_show_fb_cmt_post' ] == 1 ) {
        $arr_main_content[ 'tmp.html_for_fb_comment' ] = '<div class="fb-comments" data-href="' . $link_for_fb_comment . '" data-numposts="{tmp.fb_num_comments}" data-mobile=true></div>';
    }

    // cmt wp
    if ( $__cf_row[ 'cf_show_cmt_post' ] == 1 ) {
        $arr_main_content[ 'tmp.html_for_fb_comment' ] .= $wgr_comment_list;
    }
} else if ( $__post->post_type == EB_BLOG_POST_TYPE ) {
    // cmt fb
    if ( $__cf_row[ 'cf_show_fb_cmt_blog' ] == 1 ) {
        $arr_main_content[ 'tmp.html_for_fb_comment' ] = '<div class="fb-comments" data-href="' . $link_for_fb_comment . '" data-numposts="{tmp.fb_num_comments}" data-mobile=true></div>';
    }

    // cmt wp
    if ( $__cf_row[ 'cf_show_cmt_blog' ] == 1 ) {
        $arr_main_content[ 'tmp.html_for_fb_comment' ] .= $wgr_comment_list;
    }
}
/*
else {
}
*/


// gọi đến function riêng của từng site
if ( function_exists( 'eb_details_for_current_domain' ) ) {
    $arr_main_new_content = eb_details_for_current_domain();

    // -> chạy vòng lặp, ghi đè lên mảng cũ
    foreach ( $arr_main_new_content as $k => $v ) {
        $arr_main_content[ $k ] = $v;
    }
}


// định dạng file
$post_format = get_post_format( $pid );

// TEST
//echo $post_format . '<br>' . "\n";
//echo __FILE__ . ':' . __LINE__ . ':' . $html_v2_file . '<br>' . "\n";


// tạo nội dung - v1
//$main_content = EBE_str_template( $html_file, $arr_main_content, $thu_muc_for_html );

// v2
$load_config_temp = $__cf_row[ 'cf_threaddetails_include_file' ];
//echo $load_config_temp . '<br>' . "\n";

// nếu có post format mới -> sử dụng format này
if ( $post_format != '' ) {
    $main_content = EBE_get_page_template( $html_v2_file . '-' . $post_format );
}
// với sản phẩm -> có thể tạo nhiều design khác nhau
//else if ( $__post->post_type == 'post' && $load_config_temp != '' ) {
else if ( $bai_san_pham == true && $load_config_temp != '' ) {
    $main_content = WGR_check_and_load_tmp_theme( $load_config_temp, 'threaddetails' );
}
// mặc định thì kiểm tra theo theme và plugin
else {
    $main_content = EBE_get_page_template( $html_v2_file );
}
$main_content = EBE_html_template( $main_content, $arr_main_content );


// If comments are open or we have at least one comment, load up the comment template.
// load comment bằng ajax -> vì theme mình viết toàn có cache
$eb_site_comment_open = 0;
/*
if ( comments_open() || get_comments_number() ) {
//	comments_template();
	
	$eb_site_comment_open = 1;
}
*/


//
$trv_ngayhethan = 0;
$_eb_product_ngayhethan = '';
$_eb_product_giohethan = '';
//$_eb_product_leech_source = '';
$_eb_product_leech_source = _eb_get_post_object( $pid, '_eb_product_leech_source' );
$_eb_product_supper_shop = '';

// nếu website có mã giảm giá -> kích hoạt chức năng nhập mã giảm giá
$co_ma_giam_gia = 0;

//if ( $__post->post_type == 'post' ) {
if ( $bai_san_pham == true ) {

    //
    if ( $__post->post_type == 'post' ) {
        $_eb_product_ngayhethan = _eb_get_post_object( $pid, '_eb_product_ngayhethan' );
        //		echo $_eb_product_ngayhethan . '<br>';
        if ( $_eb_product_ngayhethan != '' ) {
            $_eb_product_giohethan = _eb_get_post_object( $pid, '_eb_product_giohethan' );
            if ( $_eb_product_giohethan == '' ) {
                $_eb_product_giohethan = '23:59';
            }

            // kiểm tra định dạng này tháng
            $check_dinh_dang_ngay = explode( '/', $_eb_product_ngayhethan );

            // định dạng chuẩn là: YYYY/MM/DD
            if ( count( $check_dinh_dang_ngay ) == 3 && strlen( $check_dinh_dang_ngay[ 0 ] ) == 4 ) {
                $trv_ngayhethan = $_eb_product_ngayhethan . ' ' . $_eb_product_giohethan;
                //				echo $trv_ngayhethan;
                $trv_ngayhethan = strtotime( $trv_ngayhethan );
                //				echo $trv_ngayhethan . '<br>';

                // nếu sản phẩm hết hạn
                if ( $__cf_row[ 'cf_update_price_if_hethan' ] == 1 && $trv_ngayhethan > 0 && $trv_ngayhethan < date_time ) {
                    $gia_sau_km = _eb_float_only( _eb_get_post_object( $pid, '_eb_product_baseprice' ) );

                    if ( $gia_sau_km > 0 && $gia_sau_km > $trv_giamoi && $trv_giamoi > 0 ) {
                        // cập nhật lại giá phẩm
                        // đặt lại giá sau khuyến mại về 0, nếu không web sẽ update liên tục -> lỗi hệ thống ngay
                        WGR_update_meta_post( $pid, '_eb_product_baseprice', 0 );
                        WGR_update_meta_post( $pid, '_eb_product_price', $trv_giaban );

                        WGR_update_meta_post( $pid, '_eb_product_ngayhethan', '' );
                        WGR_update_meta_post( $pid, '_eb_product_giohethan', '' );
                    }
                    $trv_ngayhethan = 0;
                }
            }
        }

        //
        //		if ( $_eb_product_leech_source != '' ) {
        //			$_eb_product_leech_source = str_replace( '/', '\/', $_eb_product_leech_source );
        //		}

        //
        $_eb_product_supper_shop = _eb_get_post_object( $pid, '_eb_product_supper_shop' );
        if ( $_eb_product_supper_shop != '' ) {
            $_eb_product_supper_shop = str_replace( '/', '\/', $_eb_product_supper_shop );
        }
    }


    // chuyển thumb về bên phải
    if ( $__cf_row[ 'cf_details_right_thumbnail' ] == 1 ) {

        // v1
        /*
        $arr_for_add_css[ EB_THEME_PLUGIN_INDEX . 'css/template/thumb-col.css' ] = 1;
        $arr_for_add_css[ EB_THEME_PLUGIN_INDEX . 'css/template/thumb-col-mobile.css' ] = 1;
        */

        // v2 -> thêm vào dưới dạng LINK để còn remove được trên mobile
        $__cf_row[ 'cf_js_head' ] .= '<link rel="stylesheet" id="thumb-col" href="' . EB_DIR_CONTENT . '/echbaydotcom/css/template/thumb-col.css?v=1" type="text/css" media="all" />';

    }
    // chuyển thumb về bên trái
    else if ( $__cf_row[ 'cf_details_left_thumbnail' ] == 1 ) {

        // v1
        /*
        $arr_for_add_css[ EB_THEME_PLUGIN_INDEX . 'css/template/thumb-col.css' ] = 1;
        $arr_for_add_css[ EB_THEME_PLUGIN_INDEX . 'css/template/thumb-col-left.css' ] = 1;
        $arr_for_add_css[ EB_THEME_PLUGIN_INDEX . 'css/template/thumb-col-mobile.css' ] = 1;
        */

        // v2 -> thêm vào dưới dạng LINK để còn remove được trên mobile
        $__cf_row[ 'cf_js_head' ] .= '<link rel="stylesheet" id="thumb-col" href="' . EB_DIR_CONTENT . '/echbaydotcom/css/template/thumb-col.css" type="text/css" media="all" />
<link rel="stylesheet" id="thumb-col-left" href="' . EB_DIR_CONTENT . '/echbaydotcom/css/template/thumb-col-left.css" type="text/css" media="all" />';

    }

    //
    if ( $__cf_row[ 'cf_details_ul_options' ] == 1 ) {
        $arr_for_add_css[ EB_THEME_PLUGIN_INDEX . 'css/template/thread-details2-options.css' ] = 1;
    }

    // nếu có hiển thị phom mua ngay và đặt chế độ rút gọn phom -> rút luôn
    if ( $__cf_row[ 'cf_details_show_quick_cart' ] == 1 && $__cf_row[ 'cf_details_short_quick_cart' ] == 1 ) {
        $arr_for_add_css[ EB_THEME_PLUGIN_INDEX . 'css/template/clone-show-quick-cart.css' ] = 1;
    }


    // kiểm tra xem có mã giảm giá không
    $co_ma_giam_gia = WGR_check_discount_code_exist();

}


//
$_eb_product_video_url = _eb_get_post_object( $pid, '_eb_product_video_url' );
$_eb_product_source_author = _eb_get_post_object( $pid, '_eb_product_source_author' );


// hiển thị danh mục trên phần danh sách sản phẩm
$get_category_in_list = 0;
if ( $__cf_row[ 'cf_category_in_list' ] === 1 ) {
    $get_category_in_list = 1;
} else if ( defined( 'eb_find_category_in_list' ) && eb_find_category_in_list === true ) {
    $get_category_in_list = 1;
}

//echo $get_category_in_list . '<br>';
if ( $get_category_in_list === 1 ) {
    $main_content = WGR_replace_category_slug( $pid, $__post->post_type, $main_content );
}


// -> thêm đoạn JS dùng để xác định xem khách đang ở đâu trên web
$main_content .= '<script type="text/javascript">
var switch_taxonomy="' . $__post->post_type . '",
//	pid=' . $pid . ',
	product_post_status="' . $__post->post_status . '",
	product_trv_trangthai="' . $trv_trangthai . '",
	eb_site_comment_open=' . $eb_site_comment_open . ',
	con_hay_het=' . $con_hay_het . ',
	post_canonical_url="' . $url_og_url . '",
	product_js={' . substr( $product_js, 1 ) . '},
	arr_product_size="' . $product_size . '",
	arr_product_color=[],
	product_color_name="' . $product_color_name . '",
	_eb_product_chinhhang="' . $_eb_product_chinhhang . '",
	_eb_product_video_url="' . $_eb_product_video_url . '",
	_eb_product_ngayhethan="' . $_eb_product_ngayhethan . '",
	_eb_product_giohethan="' . $_eb_product_giohethan . '",
	_eb_product_leech_source="' . str_replace( '/', '\/', $_eb_product_leech_source ) . '",
	_eb_product_supper_shop="' . $_eb_product_supper_shop . '",
	cf_details_excerpt="' . $__cf_row[ 'cf_details_excerpt' ] . '",
	cf_details_bold_excerpt="' . $__cf_row[ 'cf_details_bold_excerpt' ] . '",
	cf_options_excerpt="' . $__cf_row[ 'cf_options_excerpt' ] . '",
	cf_details_ul_options="' . $__cf_row[ 'cf_details_ul_options' ] . '",
	cf_post_rm_img_width="' . $__cf_row[ 'cf_post_rm_img_width' ] . '",
	cf_blog_rm_img_width="' . $__cf_row[ 'cf_blog_rm_img_width' ] . '",
	cf_donot_remove_quick_cart="' . $__cf_row[ 'cf_donot_remove_quick_cart' ] . '",
	cf_product_details_viewmore=' . $__cf_row[ 'cf_product_details_viewmore' ] . ',
	cf_slider_details_play=' . $__cf_row[ 'cf_slider_details_play' ] . ',
	cf_img_details_maxwidth=' . $__cf_row[ 'cf_img_details_maxwidth' ] . ',
    // chế độ chuyển slider cho trang chi tiết
	cf_show_img_color="' . $__cf_row[ 'cf_show_img_color' ] . '",
	cf_on_details_slider="' . $__cf_row[ 'cf_on_details_slider' ] . '",
	// do thumbnail left với right chung 1 lệnh -> nên đặt chung luôn
	cf_details_right_thumbnail="' . ( ( $__cf_row[ 'cf_details_right_thumbnail' ] == 1 || $__cf_row[ 'cf_details_left_thumbnail' ] == 1 ) ? 1 : 0 ) . '",
	cf_details_content_slider="' . $__cf_row[ 'cf_details_content_slider' ] . '",
	cf_details_bottom_quick_cart="' . $__cf_row[ 'cf_details_bottom_quick_cart' ] . '",
	cf_details_show_list_next="' . $__cf_row[ 'cf_details_show_list_next' ] . '",
	cf_details_show_list_thumb="' . $__cf_row[ 'cf_details_show_list_thumb' ] . '",
	cf_swipe_details_banner="' . $__cf_row[ 'cf_swipe_details_banner' ] . '",
	trv_ngayhethan=' . $trv_ngayhethan . ',
	cart_shipping_content="' . _eb_str_block_fix_content( EBE_get_lang( 'cart_shipping_content' ) ) . '",
	co_ma_giam_gia=' . $co_ma_giam_gia . ';
</script>';
//	arr_product_color=[' . substr( $arr_product_color, 1 ) . '],


//
/*
_eb_get_static_html ( $strCacheFilter, $main_content );

} // end cache
*/


// một số file gắn riêng cho post
/*
if ( $post->post_type == 'post' ) {
	// gọi file js dùng chung trước
	$arr_for_add_js[] = 'javascript/details_wp.js';
	
	// sau đó gọi file js riêng của từng domain
	$arr_for_add_js[] = 'details.js';
}
*/
//$arr_for_add_js[] = EB_THEME_PLUGIN_INDEX . 'javascript/d_post.js';


// thêm link sửa bài cho admin
/*
$admin_edit = get_edit_post_link( $__post->ID );
if ( $admin_edit != '' ) {
	$admin_edit = '<a title="Edit" href="' . $admin_edit . '"><i class="fa fa-edit"></i></a>';
}
*/

$admin_edit = '';
//if ( current_user_can('editor') || current_user_can('administrator') ) {
if ( current_user_can( 'delete_posts' ) ) {
    $admin_edit = '<a title="Edit" href="' . admin_link . 'post.php?post=' . $pid . '&action=edit" class="breadcrumb-clone-edit-post"><i class="fa fa-edit"></i></a>';
}
$main_content = str_replace( '{tmp.admin_edit}', $admin_edit, $main_content );
$main_content = str_replace( '{tmp._eb_product_source_author}', $_eb_product_source_author, $main_content );
$main_content = str_replace( '{tmp._eb_product_leech_source}', $_eb_product_leech_source, $main_content );


// thêm các dữ liệu từ meta post
if ( array_key_exists( 'id' . $pid, $arr_object_post_meta ) ) {
    //	print_r( $arr_object_post_meta['id' . $pid] );
    $main_content = EBE_arr_tmp( $arr_object_post_meta[ 'id' . $pid ], $main_content );
}


// thêm thanh công cụ mua trên mobile
//if ( $__post->post_type == 'post' && $__cf_row['cf_details_mobilemua'] != 'none' ) {
if ( $bai_san_pham == true && $__cf_row[ 'cf_details_mobilemua' ] != 'none' ) {
    // v1
    //	$main_content .= EBE_html_template( EBE_get_page_template( 'details_mobilemua' ) );

    // v2
    //	$details_mobilemua = EBE_get_page_template( 'details_mobilemua' );

    // v3
    $main_content .= EBE_html_template(
        EBE_get_custom_template( $__cf_row[ 'cf_details_mobilemua' ], 'details/mobilemua' ),
        /*
        EBE_get_page_template(
        	$__cf_row['cf_details_mobilemua'],
        	EB_THEME_PLUGIN_INDEX . 'html/details/',
        	EB_THEME_PLUGIN_INDEX . 'html/details/' . $__cf_row['cf_details_mobilemua'] . '.css'
        ),
        */
        array(
            'tmp.id' => $pid,
            'tmp.pid' => $pid,
            'tmp.details_mobilemua_mua' => EBE_get_lang( 'details_mobilemua_mua' ),
            'tmp.details_mobilemua_top' => EBE_get_lang( 'details_mobilemua_top' )
        )
    );
}


//
//print_r( $_COOKIE );


// loại bỏ chức năng bỏ qua sản phẩm đã lấy, để custom code còn hoạt động được
$___eb_post__not_in = '';


// thêm CSS theo slug để tiện sử dụng cho việc custom CSS
$_eb_product_css = _eb_get_post_object( $pid, '_eb_product_css' );
if ( $_eb_product_css != '' ) {
    //	$class_css_of_post = ' ' . $_eb_product_css;
    $css_m_css[] = $_eb_product_css;
}
//$css_m_css[] = $__post->post_type . '-' . $__post->post_name;
//echo $class_css_of_post;