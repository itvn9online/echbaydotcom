<?php

function WGR_get_user_email( $id ) {
    return _eb_lay_email_tu_cache( $id );
}

function _eb_lay_email_tu_cache( $id ) {
    if ( $id <= 0 ) {
        return 'NULL';
    }
    $strCacheFilter = 'tv_mail/' . $id;

    $tv_email = _eb_get_static_html( $strCacheFilter, '', '', 24 * 3600 );

    if ( $tv_email == false ) {
        $user = get_user_by( 'id', $id );
        //			print_r($user);

        //
        if ( !empty( $user ) ) {
            $tv_email = $user->user_email;
        } else {
            $tv_email = $id;
        }

        //
        _eb_get_static_html( $strCacheFilter, $tv_email, '', 60 );
    }

    return $tv_email;
}

function _eb_categories_list_list_v3( $taxx = 'category' ) {
    $arr = get_categories( array(
        'taxonomy' => $taxx,
        'hide_empty' => 0,
    ) );
    //	print_r($arr);

    //
    //		echo count( $arr ) . "\n";

    //
    $str = '';

    foreach ( $arr as $v ) {
        $str .= '<option data-slug="' . $v->slug . '" data-parent="' . $v->category_parent . '" value="' . $v->term_id . '">' . $v->name . '</option>';
    }

    return $str;
}

function _eb_categories_list_v3( $select_name = 't_ant', $taxx = 'category' ) {
    $str = '<option value="0">[ Lựa chọn phân nhóm ]</option>';

    $str .= _eb_categories_list_list_v3( $taxx );

    $str .= '<option data-show="1" data-href="' . admin_link . 'edit-tags.php?taxonomy=category">[+] Thêm phân nhóm mới</option>';

    return '<select name="' . $select_name . '">' . $str . '</select>';
}


$cache_thumbnail_id = array();
//$cache_attachment_image_src = array();
function _eb_get_post_img(
    // post ID
    $id,
    // size cần lấy
    $_size = 'full',
    // chỉnh lại thumbnail theo tiêu chuẩn của wordpress
    $fixed_thumb = false
) {

    //
    global $cache_thumbnail_id;
    //	global $cache_attachment_image_src;

    /*
    echo '<!-- ';
    print_r( $cache_thumbnail_id ) . "\n";
    echo 'Post ID: ' . $id . "\n";
    echo 'Post title: ' . get_the_title( $id ) . "\n";
    echo 'Size: ' . $_size . "\n";
    echo ' -->';
    */

    //
    //	if ( isset( $cache_attachment_image_src[ $id . $_size ] ) ) {
    //		return $cache_attachment_image_src[ $id . $_size ];
    //	}

    /*
	if ( $_size == '' ) {
		global $__cf_row;
		
		$_size =  $__cf_row['cf_product_thumbnail_size'];
//		$_size =  $__cf_row['cf_ads_thumbnail_size'];
	}
	*/

    /*
    $strCacheFilter = 'post_img/' . $id;
    $a = _eb_get_static_html ( $strCacheFilter );
    if ($a == false) {
    	global $__cf_row;
    	*/

    // thumbnail của wp
    if ( has_post_thumbnail( $id ) ) {

        // lưu ID thumbnail vào biến để sử dụng lại
        if ( !isset( $cache_thumbnail_id[ $id ] ) ) {
            $cache_thumbnail_id[ $id ] = get_post_thumbnail_id( $id );
        }

        // do safari chưa hỗ trợ webp nên đành tạm dừng lại đã
        if ( $_size == 'ebwebp' ) {
            $_size = 'ebmobile';
        }

        // size riêng cho bản EchBay mobile
        //if ( $_size == 'ebmobile' && function_exists('imagepalettetotruecolor') && function_exists('imagewebp') ) {
        if ( $_size == 'ebwebp' && function_exists( 'imagepalettetotruecolor' ) && function_exists( 'imagewebp' ) ) {
            return EBE_resize_mobile_table_webp( $cache_thumbnail_id[ $id ], $_size );
        }
        // nếu server có hỗ trợ Imagick
        else if ( $_size == 'ebmobile' && class_exists( 'Imagick' ) ) {
            return EBE_resize_mobile_table_img( $cache_thumbnail_id[ $id ], $_size );
        }
        // không thì lấy size medium
        else if ( $_size == 'ebwebp' || $_size == 'ebmobile' ) {
            $_size = 'medium';
            //$_size = 'thumbnail';
        }

        //
        $a = wp_get_attachment_image_src( $cache_thumbnail_id[ $id ], $_size );
        //echo $id . '<br>' . "\n";
        //echo $cache_thumbnail_id[ $id ] . '<br>' . "\n";
        //print_r( $a );
        //$a = esc_url( $a[0] );
        if ( empty( $a ) ) {
            return '';
        }
        $a = $a[ 0 ];
    }
    // thumbnail lúc chuyển dữ liệu qua
    else {
        $a = _eb_get_post_object( $id, '_eb_product_avatar' );

        // chỉnh sửa lại thumbnail nếu người dùng đang dùng thumbnail chuẩn của wp nhưng lại set thông qua tool của WGR
        if ( $fixed_thumb == true && $a != '' ) {
            $img = explode( EB_DIR_CONTENT, $a );

            // tìm ảnh theo tiêu chuẩn của wp
            if ( count( $img ) > 1 ) {
                $img[ 0 ] = '';
                $img = implode( EB_DIR_CONTENT, $img );
                //					echo $img . '<br>' . "\n";

                // tìm ID thumbnail
                $sql = _eb_q( "SELECT *
					FROM
						`" . wp_posts . "`
					WHERE
						post_type = 'attachment'
						AND guid LIKE '%{$img}'
					ORDER BY
						ID DESC
					LIMIT 0, 1" );
                //					print_r( $sql );

                // nếu có -> set luôn
                if ( !empty( $sql ) ) {
                    //						echo $sql[0]->ID . '<br>' . "\n";

                    //
                    set_post_thumbnail( $id, $sql[ 0 ]->ID );
                }
            }
        }
    }

    /*
    	
    	//
    	if ($a != '') {
    		_eb_get_static_html ( $strCacheFilter, $a );
    	}
    }
    */

    //
    //	$cache_attachment_image_src[ $id . $_size ] = $a;

    //
    return $a;
}

function EBE_resize_mobile_table_webp( $attachment_id, $_size, $new_size = 440 ) {
    // lấy ảnh full
    $attachment_file = wp_get_attachment_image_src( $attachment_id, 'full' );
    $attachment_file = explode( '?', $attachment_file[ 0 ] );
    $attachment_file = $attachment_file[ 0 ];
    $source_file = $attachment_file;
    //	return $source_file;

    $new_file = $source_file . '_' . $_size . '.webp';

    // xem file này có tồn tại không -> không thì tạo
    $check_file = ABSPATH . strstr( $new_file, EB_DIR_CONTENT . '/' );
    if ( !file_exists( $check_file ) ) {
        // Kiểm tra file nguồn
        $source_file = ABSPATH . strstr( $source_file, EB_DIR_CONTENT . '/' );
        if ( !file_exists( $source_file ) ) {
            return 'source not found!';
        }

        // -> ảnh cho bản mobile
        $file_type = explode( '.', $source_file );
        $file_type = strtolower( $file_type[ count( $file_type ) - 1 ] );

        // tạo file trung gian cho nhẹ bớt
        $tmp_file = ABSPATH . strstr( $source_file, EB_DIR_CONTENT . '/' ) . '-tmp-' . $_size . '.' . $file_type;
        if ( !file_exists( $tmp_file ) ) {
            $a = getimagesize( $source_file );
            $width = $a[ 0 ];
            $height = $a[ 1 ];

            // ưu tiên sử dụng Imagick
            if ( class_exists( 'Imagick' ) ) {
                $image = new Imagick();
                $image->readImage( $source_file );

                // copy và resize theo chiều rộng
                if ( $width > $new_size * 1.2 ) {
                    $image->resizeImage( $new_size, 0, Imagick::FILTER_CATROM, 1 );
                    $image->writeImages( $tmp_file, true );
                    $image->destroy();
                }
                // theo chiều cao
                else if ( $height > $new_size * 1.2 ) {
                    $image->resizeImage( 0, $new_size, Imagick::FILTER_CATROM, 1 );
                    $image->writeImages( $tmp_file, true );
                    $image->destroy();
                } else {
                    copy( $source_file, $tmp_file );
                }

                // test
                //				copy( $tmp_file, $tmp_file . '-Imagick-.' . $file_type );
            }
            // rồi mới đến sử dụng hàm mặc định của php
            else {
                $image = new WGR_SimpleImage();
                $image->load( $source_file );

                // chỉ resize nếu size nó đủ lớn
                if ( $width > $new_size * 1.2 ) {
                    $image->resizeToWidth( $new_size );
                    $image->save( $tmp_file );
                } else if ( $height > $new_size * 1.2 ) {
                    $image->resizeToHeight( $new_size );
                    $image->save( $tmp_file );
                } else {
                    copy( $source_file, $tmp_file );
                }
            }
            chmod( $tmp_file, 0766 );
        }

        // bắt đầu chuyển đổi sang webp
        if ( $file_type == 'png' ) {
            $img = imagecreatefrompng( $tmp_file );
        } else if ( $file_type == 'jpg' || $file_type == 'jpeg' ) {
            $img = imagecreatefromjpeg( $tmp_file );
        } else if ( $file_type == 'gif' ) {
            $img = imagecreatefromgif( $tmp_file );
        } else {
            unlink( $tmp_file );
            return $attachment_file;
        }
        // xóa file tạm
        unlink( $tmp_file );

        //
        imagepalettetotruecolor( $img );
        imagealphablending( $img, true );
        imagesavealpha( $img, true );
        //imagewebp( $img, $check_file, 100 );
        imagewebp( $img, $check_file );
        imagedestroy( $img );
        chmod( $check_file, 0766 );
    }

    return $new_file;
}

function EBE_resize_mobile_table_img( $attachment_id, $_size, $new_size = 160 ) {
    // lấy ảnh full
    $source_file = wp_get_attachment_image_src( $attachment_id, 'full' );
    $source_file = explode( '?', $source_file[ 0 ] );
    $source_file = $source_file[ 0 ];
    //	return $source_file;

    // -> ảnh cho bản mobile
    $file_type = explode( '.', $source_file );
    $file_type = $file_type[ count( $file_type ) - 1 ];

    $new_file = $source_file . '_' . $_size . '.' . $file_type;

    // xem file này có tồn tại không -> không thì tạo
    $check_file = ABSPATH . strstr( $new_file, EB_DIR_CONTENT . '/' );
    if ( !file_exists( $check_file ) ) {
        // Kiểm tra file nguồn
        $source_file = ABSPATH . strstr( $source_file, EB_DIR_CONTENT . '/' );
        if ( !file_exists( $source_file ) ) {
            return 'source not found!';
        }
        $arr_parent_size = getimagesize( $source_file );

        // resize sang ảnh mới
        $image = new Imagick();

        //
        try {
            $image->readImage( $source_file );

            // copy và resize theo chiều rộng
            if ( $arr_parent_size[ 0 ] > $arr_parent_size[ 1 ] ) {
                $image->resizeImage( $new_size, 0, Imagick::FILTER_CATROM, 1 );
            }
            // theo chiều cao
            else {
                $image->resizeImage( 0, $new_size, Imagick::FILTER_CATROM, 1 );
            }
            /*
            if ( $arr_parent_size['mime'] == 'image/jpeg' ) {
            	$image->setImageFormat( 'jpg' );
            	$image->setImageCompression(Imagick::COMPRESSION_JPEG);
            }
            else {
            	$image->setImageCompression(Imagick::COMPRESSION_UNDEFINED);
            }
            $image->setImageCompressionQuality( 75 );
            $image->optimizeImageLayers();
            */

            $image->writeImages( $check_file, true );
            $image->destroy();

            chmod( $check_file, 0666 );
        } catch ( Exception $e ) {
            echo '<!-- Caught exception: ';
            //			print_r( $e );
            echo 'File: ' . $e->getFile() . "\n";
            echo 'Line: ' . $e->getLine() . "\n";
            echo 'Message: ' . $e->getMessage() . "\n";
            echo ' -->' . "\n";
        }

        //		return $check_file;
    }

    return $new_file;
}


/*
 * Chức năng lấy post meta dưới dạng object
 */
function _eb_get_object_post_meta( $id, $key = eb_post_obj_data, $sing = true, $default_value = array() ) {
    $a = get_post_meta( $id, $key, $sing );
    if ( $a == '' ) {
        $a = $default_value;
    }
    // thêm ID của mảng để sau còn check lại dữ liệu cho chuẩn
    else {
        $a[ 'id' ] = $id;
    }

    return $a;
}

/*
 * Chức năng dùng để gộp các post metae vào 1 post meta duy nhất -> select sẽ nhanh gọn hơn -> giảm thiểu việc mysql sử dụng quá nhiều ram server
 */
//function _eb_convert_postmeta_to_v2 ( $id, $key, $meta_key ) {
function _eb_convert_postmeta_to_v2( $id, $key = '_eb_product_', $meta_key = eb_post_obj_data ) {

    //
    $strFilter = " meta_key LIKE '{$key}%' ";
    if ( $key != '_eb_category_' ) {
        $key1 = '_eb_product_';
        $key2 = '_eb_ads_';

        $strFilter = " ( meta_key LIKE '{$key1}%' OR meta_key LIKE '{$key2}%' ) ";
    }

    // lấy tất cả các post meta thuộc post tương ứng
    $row = _eb_q( "SELECT *
	FROM
		`" . wp_postmeta . "`
	WHERE
		post_id = " . $id . "
		AND " . $strFilter . "
	ORDER BY
		meta_id DESC" );
    //			print_r($old_post_meta);

    // lưu vào 1 mảng tạm để xuất dữ liệu ra cho chuẩn
    $arr = array();
    $arr_update = array();
    foreach ( $row as $v ) {

        // mảng hiển thị thì phải cắt bỏ hết các ký tự không liên quan
        $arr[ $v->meta_key ] = WGR_stripslashes( $v->meta_value );

        // còn mảng dùng để update thì bắt buộc phải lắp thêm vào -> nếu không sẽ gây lỗi chuỗi khi convert sang mảng
        $arr_update[ $v->meta_key ] = addslashes( $arr[ $v->meta_key ] );

    }
    //	print_r($arr);
    //	print_r($arr_update);

    // gán thêm ID để đỡ phải lấy lại lần sau
    $arr[ 'id' ] = $id;
    $arr_update[ 'id' ] = $id;
    //	print_r($arr);
    //	print_r($arr_update);

    // cập nhật theo chức năng mới luôn
    WGR_update_meta_post( $id, $meta_key, $arr_update );

    //	exit();

    //
    return $arr;

}

/*
 * Gán vào một tham số khác để phân định giữa category với post
 */
//$arr_object_cat_meta = array();
$arr_object_term_meta = array();

function _eb_get_cat_object( $id, $key, $default_value = '', $no_cache = 0 ) {
    /*
     * no_cache: khi muốn lấy dữ liệu trực tiếp từ CSDL thì đặt lệnh này
     */
    global $arr_object_term_meta;

    if ( $id <= 0 ) {
        return $default_value;
    }

    /*
    echo '============================<br>' . "\n";
    print_r( $id );
    echo '<br>' . "\n";
    echo $key . '<br>' . "\n";
    echo '============================<br>' . "\n";
    */

    // v3 -> sử dụng term meta
    $check_id = 'cid' . $id;

    if ( $no_cache === 1 || $no_cache === true || !isset( $arr_object_term_meta[ $check_id ] ) ) {
        //global $wpdb;

        $sql = _eb_q( "SELECT meta_key, meta_value
		FROM
			`" . wp_termmeta . "`
		WHERE
			term_id = " . $id );
        //		print_r($sql);

        // nếu chưa có -> thử tìm trong bảng post meta xem có không
        if ( empty( $sql ) ) {
            //			echo eb_cat_obj_data . '<br>' . "\n";

            // thử kiểm tra trong bảng post meta xem có không
            $sql = _eb_q( "SELECT meta_key, meta_value
			FROM
				`" . wp_postmeta . "`
			WHERE
				post_id = " . $id );
            //			print_r($sql);

            // nếu có -> chuyển sang bảng term meta
            if ( !empty( $sql ) ) {
                foreach ( $sql as $v ) {
                    // xác minh đúng là term cho category mới chuyển
                    if ( strpos( $v->meta_key, '_eb_category_' ) !== false ) {
                        //						print_r( $v );

                        // nếu dữ liệu trống -> cũng hủy luôn
                        //						if ( $v->meta_value == '' || $v->meta_value == 0 ) {
                        if ( $v->meta_value == '' ) {
                            delete_post_meta( $id, $v->meta_key );
                        }
                        // chuyển sang bảng term
                        else if ( update_term_meta( $id, $v->meta_key, $v->meta_value ) ) {
                            // xóa post meta
                            delete_post_meta( $id, $v->meta_key );
                        }
                    }
                }
            }
        }

        // gán dữ liệu để trả về
        $arr = array();

        foreach ( $sql as $v ) {
            $arr[ $v->meta_key ] = $v->meta_value;
        }

        // nếu không có kết quả trả về -> trả về dữ liệu mặc định
        if ( !isset( $arr[ $key ] ) || $arr[ $key ] == '' ) {
            $arr[ $key ] = $default_value;

            // chuyển về dạng số nếu dữ liệu mặc định cũng là số
            if ( is_numeric( $default_value ) ) {
                $arr[ $key ] = ( int )$arr[ $key ];
            }
        }
        $arr[ eb_cat_obj_data ] = '';

        // gán ID để lần sau còn dùng lại
        $arr_object_term_meta[ $check_id ] = $arr;

        //
        //		exit();
    } else {
        $arr = $arr_object_term_meta[ $check_id ];

        //
        if ( !isset( $arr[ $key ] ) || $arr[ $key ] == '' ) {
            $arr[ $key ] = $default_value;
        }
    }

    // xong thì trả về dữ liệu
    //	return isset( $arr[ $key ] ) ? $arr[ $key ] : $default_value;
    return $arr[ $key ];


    // v2 -> sử dụng post meta
    return _eb_get_post_object( $id, $key, $default_value, eb_cat_obj_data, '_eb_category_' );


    /*
    global $arr_object_cat_meta;
	
    //
    if ( ! isset( $arr_object_cat_meta['id'] ) || $arr_object_cat_meta['id'] != $id ) {
    	$arr_object_cat_meta = _eb_get_object_post_meta( $id, eb_cat_obj_data );
    	
    	// nếu không tồn tại mảng tiêu đề -> vẫn là kiểu dữ liệu cũ -> chuyển sang dữ liệu mới luôn và ngay
    	if ( ! isset( $arr_object_cat_meta['id'] ) ) {
    		$arr_object_cat_meta = _eb_convert_postmeta_to_v2( $id, '_eb_category_', eb_cat_obj_data );
    	}
    }
	
    if ( ! isset ( $arr_object_cat_meta[ $key ] ) ) {
    	return $default_value;
    }
	
    return $arr_object_cat_meta[ $key ];
    */

}

/*
 * Gán vào một tham số khác để phân định giữa ads với post
 */
function _eb_get_ads_object( $id, $key, $default_value = '' ) {
    return _eb_get_post_object( $id, $key, $default_value, eb_post_obj_data, '_eb_ads_' );

    /*
    global $arr_object_cat_meta;
	
    //
    if ( ! isset( $arr_object_cat_meta['id'] ) || $arr_object_cat_meta['id'] != $id ) {
    	$arr_object_cat_meta = _eb_get_object_post_meta( $id );
    	
    	// nếu không tồn tại mảng tiêu đề -> vẫn là kiểu dữ liệu cũ -> chuyển sang dữ liệu mới luôn và ngay
    	if ( ! isset( $arr_object_cat_meta['id'] ) ) {
    		$arr_object_cat_meta = _eb_convert_postmeta_to_v2( $id, '_eb_ads_' );
    	}
    }
	
    if ( ! isset ( $arr_object_cat_meta[ $key ] ) ) {
    	return $default_value;
    }
	
    return $arr_object_cat_meta[ $key ];
    */

}

/*
 * Hàm này dùng để lấy object của post, object này bao gồm các thông tin khác tương tự như post meta riêng lẻ. Ví dụ: giá bán, ảnh đại diện...
 */
$arr_object_post_meta = array();

function _eb_get_post_object( $id, $key, $default_value = '', $meta_key = eb_post_obj_data, $meta_convert = '_eb_product_' ) {

    if ( $id <= 0 ) {
        return $default_value;
    }

    //
    /*
    if ( $meta_convert == '_eb_category_' ) {
    	return _eb_get_cat_object( $id, $key, $default_value );
    }
    else {
    	$check_id = 'id' . $id;
    }
    */

    //
    global $arr_object_post_meta;

    //
    $check_id = 'id';
    if ( $meta_convert == '_eb_category_' ) {
        $check_id = 'cid';
    }
    $check_id .= $id;

    //
    //	echo $key . '<br>' . "\n";

    //
    //	echo $check_id . ' -------<br>' . "\n";
    //	echo $meta_convert . ' -------<br>' . "\n";


    /*
     * Đỡ phải select nhiều -> nhẹ server, host -> hàm sẽ kiểm tra mảng dữ liệu cũ. Nếu trùng ID thì sử dụng luôn, không cần lấy lại nữa.
     * Trường hợp không tìm thấy hoặc ID truyền vào khác ID trước đó -> sẽ tiền hành lấy mới trong CSDL
     */
    //	if ( ! isset( $arr_object_post_meta[$check_id] ) || $arr_object_post_meta[$check_id] != $id ) {
    //	if ( ! isset( $arr_object_post_meta[$check_id] ) ) {
    if ( !array_key_exists( $check_id, $arr_object_post_meta ) ) {

        //
        $arr = array();

        // v3 -> chỉ dành cho post -> ưu tiên lấy trong bảng posts trước
        if (
            cf_set_raovat_version == 1
            //			&& strpos( $key, '_eb_' ) !== false
            &&
            $meta_convert != '_eb_category_'
        ) {
            //			echo 'aaaaaaaaaaaaaaaa<br>';

            $sql = _eb_q( "SELECT *
			FROM
				`" . wp_posts . "`
			WHERE
				ID = " . $id );

            // nếu có giá trị trả về -> dùng luôn thôi
            if ( !empty( $sql ) ) {
                $sql = $sql[ 0 ];
                //				echo $key . '<br>' . "\n";

                // nếu mảng trả về -> có thể sẽ có dữ liệu -> dùng luôn
                if ( isset( $sql->$key ) ) {
                    //					echo $key . '<br>' . "\n";

                    //
                    foreach ( $sql as $k => $v ) {
                        // kiểm tra đúng key của EchBay thì mới tiếp tục
                        if ( strpos( $k, '_eb_' ) !== false ) {
                            // nếu không có giá trị -> thử lấy theo post meta mặc định
                            /*
                            if ( $v == '' ) {
                            	$v = get_post_meta( $id, $k, true );
                            	
                            	// nếu có -> cập nhật post meta sang bảng post luôn
                            	if ( $v != '' ) {
                            		WGR_update_meta_post( $id, $k, $v );
                            	}
                            }
                            */

                            // gán mảng
                            $arr[ $k ] = $v;
                        }
                    }

                    // chạy hết vòng lặp mà không có key -> chưa được tạo
                    //					if ( ! array_key_exists ( $key, $arr ) ) {
                    if ( !array_key_exists( $key, $arr ) || $arr[ $key ] == '' ) {
                        //						$arr[ $key ] = get_post_meta( $id, $key, true );
                        $arr[ $key ] = $default_value;

                        //						WGR_update_meta_post( $id, $key, $arr[ $key ] );
                        WGR_update_meta_post( $id, $key, '' );
                    }
                }
                // nếu không có -> tìm trong bảng postmeta rồi chuyển sang
                else {
                    //					$arr[ $key ] = get_post_meta( $id, $key, true );
                    $arr[ $key ] = $default_value;

                    //					WGR_update_meta_post( $id, $key, $arr[ $key ] );
                    WGR_update_meta_post( $id, $key, '' );
                }

                // gán giá trị để lần sau còn dùng lại
                $arr_object_post_meta[ $check_id ] = $arr;
            }

            //
            //			echo $id . '<br>' . "\n";
            //			print_r( $arr );
            //			print_r( $sql ); exit();

            // trả về dữ liệu tìm được
            //			return $arr[ $key ];
        }


        // v2 -> tiếp tục lấy các trường dữ liệu trong meta để sử dụng
        //		if ( empty( $arr ) ) {
        $sql = _eb_q( "SELECT meta_key, meta_value
			FROM
				`" . wp_postmeta . "`
			WHERE
				post_id = " . $id );
        //			print_r($sql);
        //			exit();

        //			if ( count($sql) > 0 ) {
        foreach ( $sql as $v ) {
            //					if ( ! isset( $arr[ $v->meta_key ] ) || $arr[ $v->meta_key ] == '' ) {
            $arr[ $v->meta_key ] = $v->meta_value;
            //					}
        }
        //			}
        //		}


        // v1
        /*
        $arr_object_post_meta = _eb_get_object_post_meta( $id, $meta_key );
		
        // nếu không tồn tại mảng ID -> vẫn là kiểu dữ liệu cũ -> chuyển sang dữ liệu mới luôn và ngay
        if ( ! isset( $arr_object_post_meta[check_id] ) ) {
        	$arr_object_post_meta = _eb_convert_postmeta_to_v2( $id, $meta_convert, $meta_key );
        }
        */

        // nếu không có kết quả trả về -> trả về dữ liệu mặc định
        if ( !array_key_exists( $key, $arr ) || $arr[ $key ] == '' ) {
            $arr[ $key ] = $default_value;

            // chuyển về dạng số nếu dữ liệu mặc định cũng là số
            /*
            if ( is_numeric( $default_value ) ) {
            	$arr[ $key ] = (int)$arr[ $key ];
            }
            */
        }
        $arr[ $meta_key ] = '';

        //
        //		echo $key . ' --------0<br>' . "\n";
        //		print_r( $arr );

        // gán ID để lần sau còn dùng lại
        $arr_object_post_meta[ $check_id ] = $arr;
    } else {
        //		echo $key . ' --------1<br>' . "\n";
        $arr = $arr_object_post_meta[ $check_id ];

        //
        if ( !array_key_exists( $key, $arr ) || $arr[ $key ] == '' ) {
            $arr[ $key ] = $default_value;
        }
    }
    //	echo '=====================<br>' . "\n";

    //
    //	print_r($arr_object_post_meta);

    // có kết quả thì trả về kết quả tìm được
    /*
	if ( isset( $arr[ $key ] ) ) {
		return $arr[ $key ];
	}
	else {
//		echo $key . '<br>' . "\n";
//		print_r( $arr );
		return '';
	}
	*/
    //	if ( isset( $arr[ $key ] ) && $arr[ $key ] != '' ) {
    return $arr[ $key ];
    //	}
    //	return $default_value;
}

function _eb_get_post_meta( $id, $key, $sing = true, $default_value = '' ) {

    // chuyển sang sử dụng phiên bản code mới
    //	if ( strpos( $key, '_eb_product_' ) !== false ) {
    return _eb_get_post_object( $id, $key, $default_value );
    /*
    }
    else if ( strpos( $key, '_eb_category_' ) !== false ) {
    	return _eb_get_cat_object( $id, $key, $default_value );
    }
    */


    // bản code cũ
    $strCacheFilter = 'post_meta/' . $key . $id;
    $a = _eb_get_static_html( $strCacheFilter );
    if ( $a == false ) {
        $a = get_post_meta( $id, $key, $sing );
        if ( $a == '' ) {
            $row = _eb_q( "SELECT meta_value
			FROM
				`" . wp_postmeta . "`
			WHERE
				post_id = " . $id . "
				AND meta_key = '" . $key . "'
			ORDER BY
				meta_id DESC
			LIMIT 0, 1" );
            //			print_r($row);
            //			echo $id . "\n";
            //			echo $key . "\n";

            //
            if ( isset( $row[ 0 ]->meta_value ) ) {
                $a = $row[ 0 ]->meta_value;
            }

            //
            if ( $a == '' ) {
                $a = $default_value;
            }
        }

        //
        if ( $a != '' ) {
            _eb_get_static_html( $strCacheFilter, $a );
        }
    }

    //
    return $a;
}


// kiểm tra nếu có file html riêng -> sử dụng html riêng
function _eb_get_html_for_module( $check_file ) {
    // kiểm tra ở thư mục code riêng
    if ( file_exists( EB_THEME_HTML . $check_file ) ) {
        $f = EB_THEME_HTML . $check_file;
    }
    // nếu không -> kiểm tra ở thư mục dùng chung
    else if ( file_exists( EB_THEME_PLUGIN_INDEX . 'html/' . $check_file ) ) {
        $f = EB_THEME_PLUGIN_INDEX . 'html/' . $check_file;
    }

    return file_get_contents( $f, 1 );
}

function _eb_get_private_html( $f, $f2 = '' ) {
    $check = EB_THEME_HTML . $f;
    $dir = EB_THEME_HTML;

    //
    if ( $f2 == '' ) {
        $f2 = $f;
    }

    // sử dụng html riêng (nếu có)
    if ( file_exists( $check ) ) {
        $html = EB_THEME_HTML . $f2;
    }
    // mặc định là html chung
    else {
        $dir = EB_THEME_PLUGIN_INDEX . 'html/';

        $html = EB_THEME_PLUGIN_INDEX . 'html/' . $f2;
    }

    //
    return array(
        'dir' => $dir,
        'html' => file_get_contents( $html, 1 ),
    );
}