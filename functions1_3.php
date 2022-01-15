<?php

function _eb_build_mail_header( $from_email, $bcc_email = '' ) {
    $headers = array();

    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-Type: text/html; charset=UTF-8';
    //	$headers[] = 'Date: ' . gmdate('d M Y H:i:s Z', NOW);
    $headers[] = 'From: ' . web_name . ' <' . $from_email . '>';
    $headers[] = 'Reply-To: <' . $from_email . '>';

    //
    $bcc_email = str_replace( ';', ',', str_replace( ' ', '', trim( $bcc_email ) ) );
    if ( $bcc_email != '' ) {
        $bcc_email = explode( ',', $bcc_email );
        foreach ( $bcc_email as $v ) {
            $v = trim( $v );

            if ( $v != '' && _eb_check_email_type( $v ) == 1 ) {
                $headers[] = 'BCC: ' . $v;
            }
        }
    }

    //
    $headers[] = 'Auto-Submitted: auto-generated';
    $headers[] = 'Return-Path: <' . $from_email . '>';
    $headers[] = 'X-Sender: <' . $from_email . '>';
    $headers[] = 'X-Priority: 3';
    $headers[] = 'X-MSMail-Priority: Normal';
    $headers[] = 'X-MimeOLE: Produced By xtreMedia';
    $headers[] = 'X-Mailer: PHP/ ' . phpversion();

    // trả về header
    return trim( implode( "\r\n", $headers ) );
}

function _eb_lnk_block_email( $em ) {
    return web_link . 'block_email&e=' . base64_encode( $em ) . '&c=' . _eb_mdnam( $em );
}

function _eb_send_email( $to_email, $title, $message, $headers = '', $bcc_email = '', $add_domain = 1 ) {
    global $year_curent;
    global $__cf_row;


    //
    //	echo $to_email . '<br>' . "\n";
    //	echo $bcc_email . '<br>' . "\n";

    //
    if ( $to_email == '' && $bcc_email != '' ) {
        $to_email = $bcc_email;
        $bcc_email = '';
    }

    if ( $to_email == '' ) {
        EBE_show_log( 'Send email to: NULL' );

        return false;
    }


    //
    $chost = str_replace( ':', '.', str_replace( 'www.', '', $_SERVER[ 'HTTP_HOST' ] ) );


    //
    if ( $add_domain == 1 ) {
        $title = '[' . $chost . '] ' . $title;
    }


    //
    if ( $headers == '' ) {
        /*
          if ( $__cf_row['cf_email_note'] != '' ) {
          $headers = _eb_build_mail_header ( $__cf_row['cf_email_note'] );
          } else if ( $__cf_row['cf_email'] != '' ) {
          $headers = _eb_build_mail_header ( $__cf_row['cf_email'] );
          } else {
         */
        $headers = _eb_build_mail_header( 'noreply@' . $chost, $bcc_email );
        /*
          }
         */
    }
    /*
      else {
      $bcc_email = str_replace( ';', ',', str_replace( ' ', '', trim($bcc_email) ) );
      if ($bcc_email != '') {
      $bcc_email = explode( ',', $bcc_email );
      foreach ( $bcc_email as $v ) {
      $v = trim( $v );

      if ( $v != '' && _eb_check_email_type( $v ) == 1 ) {
      $headers .= "\r\n" . 'BCC: ' . $v;
      }
      }
      }
      }
     */


    //
    //	$message = _eb_del_line( EBE_str_template ( 'html/mail/mail.html', array (
    $message = _eb_del_line( EBE_html_template( WGR_get_html_template_lang( 'mail_main', 'mail', EB_THEME_PLUGIN_INDEX . 'html/mail/' ), array(
        'tmp.message' => $message,
        'tmp.web_name' => web_name,
        'tmp.web_link' => web_link,
        'tmp.web_host' => $_SERVER[ 'HTTP_HOST' ],
        'tmp.block_email' => _eb_lnk_block_email( $to_email ),
        'tmp.year_curent' => $year_curent,
        'tmp.cf_ten_cty' => $__cf_row[ 'cf_ten_cty' ],
        'tmp.to_email' => $to_email,
        'tmp.captcha' => _eb_mdnam( $to_email )
        //	), EB_THEME_PLUGIN_INDEX ) );
    ) ) );
    //	echo $to_email.'<hr>'; echo $message; exit();
    // sử dụng hame mail mặc định
    /*
      if ( mail ( $to_email, $title, $message, $headers )) {
      if ( $bcc_email != '' ) {
      mail ( $bcc_email, $title, $message, $headers );
      }

      return true;
      }
     */


    //
    $ham_gui_mail = 'WP mail';

    // sử dụng hame mail mặc định
    if ( $__cf_row[ 'cf_sys_email' ] == '' ) {
        $mail = mail( $to_email, $title, $message, $headers );
        $ham_gui_mail = 'PHP mail';
    }
    // sử dụng wordpress mail
    //	else if ( $__cf_row ['cf_sys_email'] == 'wpmail' ) {
    else {
        $mail = wp_mail( $to_email, $title, $message, $headers );

        //
        if ( $__cf_row[ 'cf_sys_email' ] != 'wpmail' ) {
            $ham_gui_mail = 'SMTP';
        }
    }

    //
    if ( !$mail ) {
        //	if( ! wp_mail( $to_email, $title, $message, $headers ) ) {
        EBE_show_log( 'ERROR send mail: ' . $to_email );
        return false;
    }

    // Thông báo kết quả
    EBE_show_log( 'Send email to: ' . $to_email . ' (Using: ' . $ham_gui_mail . ')' );

    //
    return true;
}

function EBE_show_log( $m ) {
    echo '<script>console.log("' . $m . '");</script>';
}

// https://codex.wordpress.org/Plugin_API/Action_Reference/phpmailer_init
//function EBE_configure_smtp(PHPMailer $phpmailer) {
function EBE_configure_smtp( $phpmailer ) {

    global $__cf_row;

    if ( $__cf_row[ 'cf_smtp_host' ] == '' || $__cf_row[ 'cf_smtp_email' ] == '' || $__cf_row[ 'cf_smtp_pass' ] == '' ) {
        return false;
    }

    if ( $__cf_row[ 'cf_smtp_port' ] == '' || $__cf_row[ 'cf_smtp_port' ] == '0' ) {
        $__cf_row[ 'cf_smtp_port' ] == 25;
    }

    // switch to smtp
    $phpmailer->isSMTP();
    $phpmailer->CharSet = 'utf-8';

    // https://github.com/PHPMailer/PHPMailer/wiki/SMTP-Debugging
    if ( $__cf_row[ 'cf_tester_mode' ] == 1 ) {
        $phpmailer->SMTPDebug = 2;
    } else {
        $phpmailer->SMTPDebug = 0;
    }

    // Force it to use Username and Password to authenticate
    $phpmailer->SMTPAuth = true;

    // Set the Pepipost settings
    // default setting
    $phpmailer->Host = $__cf_row[ 'cf_smtp_host' ];
    $phpmailer->Port = $__cf_row[ 'cf_smtp_port' ];
    $phpmailer->Username = $__cf_row[ 'cf_smtp_email' ];
    $phpmailer->Password = $__cf_row[ 'cf_smtp_pass' ];

    // Additional settings...
    // Choose SSL or TLS, if necessary for your server
    if ( $__cf_row[ 'cf_smtp_encryption' ] == '' ) {
        $phpmailer->SMTPSecure = false;
    } else {
        $phpmailer->SMTPSecure = $__cf_row[ 'cf_smtp_encryption' ];
    }
    //	$phpmailer->SMTPSecure = 'tls';
    //	$phpmailer->SMTPSecure = 'ssl';

    if ( _eb_check_email_type( $__cf_row[ 'cf_smtp_email' ] ) != 1 ) {
        $phpmailer->From = $__cf_row[ 'cf_email' ];
    } else {
        $phpmailer->From = $__cf_row[ 'cf_smtp_email' ];
    }
    if ( $__cf_row[ 'cf_web_name' ] == '' ) {
        $phpmailer->FromName = '(' . strtoupper( $_SERVER[ 'HTTP_HOST' ] ) . ')';
    } else {
        $phpmailer->FromName = _eb_non_mark( $__cf_row[ 'cf_web_name' ] );
    }

    //
    $phpmailer->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    $phpmailer->WordWrap = 50;
    $phpmailer->IsHTML( true );

    //
    return true;
}

function _eb_send_mail_phpmailer( $to, $to_name = '', $subject, $message, $from_reply = '', $bcc_email = '' ) {
    //	global $dir_index;
    global $__cf_row;
    global $__cf_row_default;

    //
    //	set_time_limit( 10 );
    //
    //	$mail = new PHPMailer ();
    //
    $cf_email = $__cf_row[ 'cf_email' ];
    if ( $cf_email == '' ) {
        $cf_email = 'root@' . str_replace( 'www.', '', $_SERVER[ 'HTTP_HOST' ] );
    }

    if ( $to == '' || _eb_check_email_type( $to ) == false ) {
        return false;
    }
    if ( $to_name == '' ) {
        $to_name = explode( '@', $to );
        $to_name = $to_name[ 0 ];
    }

    /*
     * Send email version3
     */
    $mail_via_eb = new mailViaEchBay();

    //
    $a = $mail_via_eb->send( array(
        'to' => $to,
        'to_name' => $to_name,
        'bcc' => $bcc_email,
        'subject' => $subject,
        'content' => $message,
        'host' => $_SERVER[ 'HTTP_HOST' ],
        // gửi qua smtp riêng (nếu có)
        'smtp_host' => $__cf_row[ 'cf_smtp_host' ],
        'smtp_email' => $__cf_row[ 'cf_smtp_email' ],
        'smtp_pass' => $__cf_row[ 'cf_smtp_pass' ],
        'smtp_port' => $__cf_row[ 'cf_smtp_port' ],
        'from' => $cf_email,
        'from_name' => web_name,
        'from_reply' => $from_reply == '' ? $cf_email : $from_reply,
    ) );

    // nếu có lỗi
    if ( $a != 1 ) {
        // gửi lại bằng hàm mail thông thường
        $__cf_row[ 'cf_sys_email' ] = $__cf_row_default[ 'cf_sys_email' ];

        _eb_send_email( $to, $subject, $message, '', $bcc_email, 0 );

        // trả về lỗi
        return $a;
    }
    return true;
}

function _eb_ssl_template( $c ) {
    if ( eb_web_protocol != 'https' ) {
        return $c;
    }
    //	echo 'aaaaaaaaaaaaaaaaaaaaaaa';
    //
    $c = str_replace( 'http://' . $_SERVER[ 'HTTP_HOST' ], '//' . $_SERVER[ 'HTTP_HOST' ], $c );
    //	$c = str_replace ( web_link . EB_DIR_CONTENT . '/', EB_DIR_CONTENT . '/', $c );
    //
    return $c;


    // v1
    $host = str_replace( 'www.', '', $_SERVER[ 'HTTP_HOST' ] ) . '/';

    // Không replace thẻ A
    $c = str_replace( ' href="http://' . $host, ' href="//' . $host, $c );
    $c = str_replace( ' href="http://www.' . $host, ' href="//www.' . $host, $c );

    // -> chuyển các url khác về dạng tương đối
    $c = str_replace( 'http://' . $host, '', $c );
    $c = str_replace( 'http://www.' . $host, '', $c );

    //
    return $c;
}

/*
 * https://codex.wordpress.org/Class_Reference/WP_Query
 * https://gist.github.com/thachpham92/d57b18cf02e3550acdb5
 */

function _eb_load_ads_v2( $type = 0, $posts_per_page = 20, $_eb_query = array(), $op = array() ) {
    if ( !isset( $op[ 'offset' ] ) ) {
        $op[ 'offset' ] = 0;
    }
    if ( !isset( $op[ 'html' ] ) ) {
        $op[ 'html' ] = '';
    }
    if ( !isset( $op[ 'data_size' ] ) ) {
        $op[ 'data_size' ] = 1;
    }

    return _eb_load_ads( $type, $posts_per_page, $op[ 'data_size' ], $_eb_query, $op[ 'offset' ], $op[ 'html' ] );
}

function _eb_load_ads(
    // Trạng thái của banner quảng cáo
    $type = 0,
    // số lượng bản ghi cần lấy
    $posts_per_page = 20,
    // kích thước muốn hiển thị, nếu là auto -> tự lấy theo size ảnh
    $data_size = 1,
    // query phủ định
    $_eb_query = array(),
    // offset như mysql thông thương
    $offset = 0,
    // định dạng HTML cần xuất ra
    // default: EBE_get_page_template( 'ads_node' )
    // get title: EBE_get_page_template( 'ads_node_title' )
    // get title and excerpt EBE_get_page_template( 'ads_node_excerpt' )
    $html = '',
    $other_options = array()
) {
    global $__cf_row;
    global $arr_eb_ads_status;
    global $eb_background_for_post;
    global $cid;
    global $wpdb;
    //	global $___eb_ads__not_in;
    //		echo 'ADS NOT IN: ' . $___eb_ads__not_in . '<br>' . "\n";
    //
    //		print_r($_eb_query);
    //		$strCacheFilter = '';
    /* v1
      foreach ( $_eb_query as $k => $v ) {
      $strCacheFilter .= '-' . $k;

      if ( gettype($v) == 'array' ) {
      //				print_r($v);

      //
      foreach ( $v as $k2 => $v2 ) {
      if ( gettype($v2) == 'array' ) {
      $strCacheFilter .= implode ( '-', $v2 );
      } else {
      $strCacheFilter .= '-' . $k2;
      }
      }
      }
      }
     */
    // v2
    /*
      if ( $___eb_ads__not_in != '' ) {
      $strCacheFilter .= $___eb_ads__not_in;
      }
     */


    // nếu có thuộc tính in_cat, mà giá trị trống -> lấy các q.cáo trong nhóm hiện tại
    if ( isset( $_eb_query[ 'category__in' ] ) && $_eb_query[ 'category__in' ] == '' ) {
        $arr_in = array();

        // nếu đang có nhóm -> lấy luôn ID của nhóm này
        if ( $cid > 0 ) {
            $arr_in[] = $cid;
        }
        // hoặc lấy ID các category đang xuất hiện ở đây
        else {
            // chỉ lấy category hiện tại
            $categories = get_queried_object();
            //			print_r($categories);
            if ( isset( $categories->term_id ) ) {
                $arr_in[] = $categories->term_id;
            } else {
                // lấy các category theo sản phẩm
                $categories = get_the_category();
                //				print_r($categories);
                foreach ( $categories as $k => $v ) {
                    $arr_in[] = $v->term_id;
                    //				$strCacheFilter .= $v->term_id;
                }
            }
        }
        //		print_r($arr_in);
        $_eb_query[ 'category__in' ] = $arr_in;
    }
    // nếu có thuộc tính not_in_cat, mà giá trị trống -> chỉ lấy các q.cáo không có nhóm
    else if ( isset( $_eb_query[ 'category__not_in' ] ) && $_eb_query[ 'category__not_in' ] == '' ) {
        $arr_in = array();

        // nếu đang có nhóm -> lấy luôn ID của nhóm này
        if ( $cid > 0 ) {
            $arr_in[] = $cid;
        }
        // mặc định là lấy toàn bộ category
        else {
            //			$categories = get_the_category();
            //			print_r($categories);

            $categories = get_categories();
            /*
              $categories = get_categories( array(
              'hide_empty' => 0,
              ) );
             */
            /*
              echo '<!-- ';
              print_r($categories);
              echo ' -->';
             */

            foreach ( $categories as $k => $v ) {
                $arr_in[] = $v->term_id;
                //				$strCacheFilter .= $v->term_id;
            }


            /*
             * với custom taxonomy thì add vào phần not in kiểu khác
             */

            // tạo tax_query nếu chưa có
            if ( !isset( $_eb_query[ 'tax_query' ] ) ) {
                $_eb_query[ 'tax_query' ] = array();
            }

            //
            $categories = get_categories( array(
                'taxonomy' => 'post_options'
            ) );
            /*
              echo '<!-- ';
              print_r($categories);
              echo ' -->';
             */

            $arr_not_in = array();
            foreach ( $categories as $k => $v ) {
                $arr_not_in[] = $v->term_id;
            }
            // tạo list các banner not in phần post_options
            $_eb_query[ 'tax_query' ][] = array(
                'taxonomy' => 'post_options',
                'field' => 'term_id',
                'terms' => $arr_not_in,
                'operator' => 'NOT IN'
            );


            //
            $categories = get_categories( array(
                'taxonomy' => EB_BLOG_POST_LINK
            ) );
            /*
              echo '<!-- ';
              print_r($categories);
              echo ' -->';
             */

            $arr_not_in = array();
            foreach ( $categories as $k => $v ) {
                $arr_not_in[] = $v->term_id;
            }
            // tạo list các banner not in phần blog
            $_eb_query[ 'tax_query' ][] = array(
                'taxonomy' => EB_BLOG_POST_LINK,
                'field' => 'term_id',
                'terms' => $arr_not_in,
                'operator' => 'NOT IN'
            );

            //
            //			$_eb_query['tag__not_in'] = $arr_not_in;

            /*
              echo '<!-- ';
              print_r($_eb_query);
              echo ' -->';
             */
        }
        //		print_r($arr_in);
        //
        $_eb_query[ 'category__not_in' ] = $arr_in;
    }


    /*
      if ( $strCacheFilter != '' ) {
      $strCacheFilter = md5($strCacheFilter);
      }


      //	$strCacheFilter = 'ads' . $type . implode ( '-', $_eb_query );
      $strCacheFilter = 'ads' . $type . $strCacheFilter;
      //	echo $strCacheFilter . '<br>' . "\n";
      $str = _eb_get_static_html ( $strCacheFilter );
      if ($str == false) {
     */

    // lọc các sản phẩm trùng nhau
    /*
      if ( $___eb_ads__not_in != '' ) {
      $_eb_query['post__not_in'] = explode( ',', substr( $___eb_ads__not_in, 1 ) );
      }
     */

    //
    $arr[ 'post_type' ] = 'ads';

    //
    $arr[ 'meta_key' ] = '_eb_ads_status';
    $arr[ 'meta_value' ] = $type;
    $arr[ 'compare' ] = '=';
    $arr[ 'type' ] = 'NUMERIC';

    $arr[ 'offset' ] = $offset;
    //		$arr['offset'] = 0;
    $arr[ 'posts_per_page' ] = $posts_per_page;

    $arr[ 'orderby' ] = 'menu_order ID';
    $arr[ 'order' ] = 'DESC';

    $arr[ 'post_status' ] = 'publish';

    //
    foreach ( $_eb_query as $k => $v ) {
        $arr[ $k ] = $v;
    }
    /*
      echo '<!-- ';
      //		print_r( $_eb_query );
      print_r( $arr );
      echo ' -->';
     */

    //
    $sql = new WP_Query( $arr );

    //
    /*
      if ( $result_type == 'obj' ) {
      return $sql;
      }
     */

    //
    if ( $html == '' ) {
        $html = EBE_get_page_template( 'ads_node' );
    }
    $html = str_replace( '{tmp.other_attr}', '', $html );

    //
    $str = '';

    // lấy size theo dữ liệu truyền vào
    //		if ( $__cf_row['cf_auto_get_ads_size'] != 1 ) {
    $data_size = ( $data_size == '' ) ? 1 : $data_size;
    //		}
    // lấy size tự động theo ảnh đầu tiên
    $auto_get_size = '';
    if ( $__cf_row[ 'cf_auto_get_ads_size' ] == 1 || $data_size == 'auto' ) {
        $auto_get_size = 'auto_get_size';
    }

    //
    while ( $sql->have_posts() ) {

        $sql->the_post();
        //			print_r( $sql );

        $post = $sql->post;
        //			print_r( $post );
        //
        $p_link = '';


        //
        $anh_dai_dien_goc = _eb_get_post_img( $post->ID, $__cf_row[ 'cf_ads_thumbnail_size' ] );
        $ads_id = $post->ID;

        // kiểm tra xem q.cáo có alias tới post, page... nào không
        $alias_post = _eb_number_only( _eb_get_post_object( $post->ID, '_eb_ads_for_post', 0 ) );
        $alias_taxonomy = _eb_number_only( _eb_get_post_object( $post->ID, '_eb_ads_for_category', 0 ) );

        // nếu có -> nạp thông tin post, page... mà nó alias tới
        if ( $alias_post > 0 ) {
            $strsql = _eb_q( "SELECT *
				FROM
					`" . wp_posts . "`
				WHERE
					ID = " . $alias_post . "
					AND post_status = 'publish'" );
            //				print_r( $strsql );
            if ( !empty( $strsql ) ) {
                //
                $cache_ads_id = $post->ID;
                //					echo $cache_ads_id . '<br>' . "\n";
                $cache_ads_name = $post->post_title;
                $cache_ads_excerpt = $post->post_excerpt;

                //
                $post = $strsql[ 0 ];

                // lấy tên của Q.Cáo thay vì phân nhóm
                if ( _eb_get_post_meta( $cache_ads_id, '_eb_ads_name' ) == 1 ) {
                    $post->post_title = $cache_ads_name;
                    if ( $cache_ads_excerpt != '' ) {
                        $post->post_excerpt = $cache_ads_excerpt;
                    }
                } else if ( $post->post_excerpt == '' && $cache_ads_excerpt != '' ) {
                    $post->post_excerpt = $cache_ads_excerpt;
                }
                $p_link = _eb_p_link( $post->ID );
            }
        } else if ( $alias_taxonomy > 0 ) {
            $new_name = WGR_get_all_term( $alias_taxonomy );

            //
            if ( !isset( $new_name->errors ) ) {
                // lấy tên của Q.Cáo thay vì phân nhóm
                if ( _eb_get_post_meta( $post->ID, '_eb_ads_name' ) != 1 ) {
                    $post->post_title = $new_name->name;
                    if ( $new_name->description != '' ) {
                        $post->post_excerpt = $new_name->description;
                    }
                } else if ( $post->post_excerpt == '' && $new_name->description != '' ) {
                    $post->post_excerpt = $new_name->description;
                }

                //					$p_link = _eb_c_link( $alias_taxonomy, $new_name->taxonomy );
                $p_link = _eb_cs_link( $new_name );
            }
        }

        //
        if ( $p_link == '' ) {
            $p_link = _eb_get_post_meta( $post->ID, '_eb_ads_url', true, 'javascript:;' );
        }


        //
        //		$___eb_ads__not_in .= ',' . $post->ID;
        //
        //		$p_link = _eb_get_ads_object( $post->ID, '_eb_ads_url', 'javascript:;' );
        //		echo $p_link . '<br>';

        //		echo $__cf_row['cf_ads_thumbnail_size'] . '<br>' . "\n";

        // nếu q.cáo này không có ảnh
        $trv_img = '';
        if ( $anh_dai_dien_goc == '' ) {
            // -> gán lại ID nếu nó có alias
            if ( $alias_post > 0 ) {
                $ads_id = $post->ID;

                // lấy ảnh theo post alias
                $trv_img = _eb_get_post_img( $post->ID, $__cf_row[ 'cf_ads_thumbnail_size' ] );
            }
            // không thì bỏ qua phần lấy ảnh
            else {
                $ads_id = 0;
            }
        } else {
            $trv_img = $anh_dai_dien_goc;
        }
        //		echo $trv_img . '<br>' . "\n";
        $ftype = explode( '.', $trv_img );
        //		$ftype = $ftype[ count( $ftype ) - 1 ];
        //		echo '<!-- ' . $ftype . ' -->' . "\n";
        // lấy ảnh từ bài viết
        $trv_table_img = '';
        $trv_mobile_img = '';

        // với ảnh gif -> chỉ lấy ảnh gốc -> do ảnh resize có thể bị lỗi
        if ( strtolower( $ftype[ count( $ftype ) - 1 ] ) == 'gif' ) {
            $trv_table_img = $trv_img;
            $trv_mobile_img = $trv_img;
        } else if ( $ads_id > 0 ) {
            //			if ( $__cf_row['cf_product_thumbnail_table_size'] == $__cf_row['cf_product_thumbnail_size'] ) {
            //			if ( $__cf_row['cf_product_thumbnail_table_size'] == $__cf_row['cf_ads_thumbnail_size'] ) {
            if ( $__cf_row[ 'cf_ads_thumbnail_table_size' ] == $__cf_row[ 'cf_ads_thumbnail_size' ] ) {
                $trv_table_img = $trv_img;
            } else {
                //				$trv_table_img = _eb_get_post_img( $ads_id, $__cf_row['cf_product_thumbnail_table_size'] );
                $trv_table_img = _eb_get_post_img( $ads_id, $__cf_row[ 'cf_ads_thumbnail_table_size' ] );
            }

            //			if ( $__cf_row['cf_product_thumbnail_mobile_size'] == $__cf_row['cf_product_thumbnail_table_size'] ) {
            if ( $__cf_row[ 'cf_ads_thumbnail_mobile_size' ] == $__cf_row[ 'cf_ads_thumbnail_table_size' ] ) {
                $trv_mobile_img = $trv_table_img;
            } else {
                //				$trv_mobile_img = _eb_get_post_img( $ads_id, $__cf_row['cf_product_thumbnail_mobile_size'] );
                $trv_mobile_img = _eb_get_post_img( $ads_id, $__cf_row[ 'cf_ads_thumbnail_mobile_size' ] );
            }
        }

        //
        $youtube_avt = '';
        $youtube_url = _eb_get_post_meta( $post->ID, '_eb_ads_video_url' );
        $post->youtube_uri = $youtube_url;
        $youtube_id = '';
        if ( strpos( $youtube_url, '.mp4' ) === false ) {
            $youtube_id = _eb_get_youtube_id( $youtube_url );
            //				$youtube_id = _eb_get_youtube_id( _eb_get_ads_object( $post->ID, '_eb_ads_video_url' ) );
            if ( $youtube_id != '' ) {
                //					$youtube_url = '//www.youtube.com/watch?v=' . $youtube_id;
                $youtube_url = '//www.youtube.com/embed/' . $youtube_id;
                $youtube_avt = '//i.ytimg.com/vi/' . $youtube_id . '/0.jpg';
            } else {
                $youtube_url = 'about:blank';
            }
        }


        /*
          //
          $str .=  EBE_arr_tmp( array(
          'post_title' => $post->post_title,

          'youtube_id' => $youtube_id,
          'youtube_url' => $youtube_url,
          'youtube_avt' => $youtube_avt,

          'p_link' => $p_link,
          'data_size' => $data_size,
          'trv_img' => $trv_img,
          'post_content' => $post->post_content,
          'post_excerpt' => $post->post_excerpt,

          //
          'trv_tieude' => $post->post_title,
          'trv_gioithieu' => $post->post_excerpt,
          ), $html );
         */

        //
        $post->youtube_id = $youtube_id;
        $post->youtube_url = $youtube_url;
        $post->youtube_avt = $youtube_avt;
        $post->p_link = $p_link;

        // tạo size tự động theo ảnh (nếu chưa có)
        //			if ( $__cf_row['cf_auto_get_ads_size'] == 1 && $auto_get_size == '' ) {
        if ( $auto_get_size == 'auto_get_size' ) {
            // ảnh phải nằm trong thư mục wp-content
            if ( strpos( $trv_img, EB_DIR_CONTENT . '/' ) !== false ) {
                $auto_get_size = strstr( $trv_img, EB_DIR_CONTENT . '/' );
            }
            // hoặc cùng với domain cũng được
            else if ( strpos( $trv_img, web_link ) !== false ) {
                $auto_get_size = str_replace( web_link, '', $trv_img );
            }
            //				echo $auto_get_size . '<br>' . "\n";

            if ( $auto_get_size != '' ) {
                // ghép nối lại để bắt đầu xác định size
                $auto_get_size = ABSPATH . $auto_get_size;
                //					echo $auto_get_size . '<br>' . "\n";

                if ( file_exists( $auto_get_size ) ) {
                    $auto_get_size = getimagesize( $auto_get_size );
                    //						print_r( $auto_get_size );
                    // -> tạo size mới
                    $data_size = $auto_get_size[ 1 ] . '/' . $auto_get_size[ 0 ];
                }
            }

            // gán lại size auto để sau nó không lặp lại nữa
            $auto_get_size = $data_size;
        }
        $post->data_size = $data_size;

        //
        $post->trv_img = $trv_img;
        $post->trv_mobile_img = $trv_mobile_img;
        $post->trv_table_img = $trv_table_img;

        //
        $post->target_blank = ( _eb_get_post_meta( $post->ID, '_eb_ads_target' ) == 1 ) ? ' target="_blank"' : '';

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

        //
        $post->trv_tieude = $post->post_title;
        $post->trv_title = str_replace( '"', '&quot;', trim( strip_tags( $post->post_title ) ) );

        // mặc định là sử dụng post_excerpt, nếu không có -> sẽ sử dụng post_content
        //			$post->trv_gioithieu = ( $post->post_excerpt == '' ) ? '<div class="each-to-fix-ptags">' . trim( $post->post_content ) . '</div>' : nl2br( $post->post_excerpt );
        $post->trv_gioithieu = ( $post->post_excerpt == '' ) ? nl2br( trim( $post->post_content ) ) : nl2br( $post->post_excerpt );

        // với phần nội dung thì không có nl2br
        $post->post_content = $post->post_content;
        $post->trv_noidung = $post->post_content;

        //
        $str .= EBE_arr_tmp( $post, $html );
    }

    //
    wp_reset_postdata();

    // nếu có dữ liệu -> trả về dữ liệu theo cấu trúc định sẵn
    if ( $str != '' ) {
        $str = '<ul class="cf global-ul-load-ads' . ( isset( $other_options[ 'add_class' ] ) ? ' ' . $other_options[ 'add_class' ] : '' ) . '">' . $str . '</ul>';
    }
    // nếu không -> trả về giá trị mặc định (nếu có)
    else if ( isset( $other_options[ 'default_value' ] ) ) {
        return $other_options[ 'default_value' ];
    }
    // hoặc trả về câu thông báo cho người dùng add banner cần thiết để chạy
    else {
        $str = '<div class="show-if-site-demo global-ul-load-ads' . $type . '">Please add banner for "' . $arr_eb_ads_status[ $type ] . ' (' . $type . ')"</div>';
    }

    //
    /*
      _eb_get_static_html ( $strCacheFilter, $str );

      }
     */

    //
    /*
      if ( $__cf_row['cf_replace_content'] != '' ) {
      $str = WGR_replace_for_all_content( $__cf_row['cf_replace_content'], $str );
      }
     */
    if ( $__cf_row[ 'cf_old_domain' ] != '' ) {
        $str = WGR_sync_old_url_in_content( $__cf_row[ 'cf_old_domain' ], $str );
    }

    //
    return '<!-- ADS status: ' . $type . ' - ' . $arr_eb_ads_status[ $type ] . ' -->' . _eb_supper_del_line( $str );
}

// xác định lại post type của 1 post bất kỳ
function WGR_get_post_type_name( $id ) {
    global $wpdb;

    //
    $sql = _eb_q( "SELECT post_type
	FROM
		`" . wp_posts . "`
	WHERE
		ID = " . $id . "
	LIMIT 0, 1" );
    //	print_r( $sql );
    if ( !empty( $sql ) ) {
        return $sql[ 0 ]->post_type;
    }

    return '';
}

// Dùng để lấy thông tin các term chưa được xác định
function WGR_get_taxonomy_name( $id, $df = '' ) {
    global $wpdb;

    //
    $sql = _eb_q( "SELECT taxonomy
	FROM
		`" . $wpdb->term_taxonomy . "`
	WHERE
		term_id = " . $id . "
		OR term_taxonomy_id = " . $id . "
	LIMIT 0, 1" );
    //	print_r( $sql );
    if ( !empty( $sql ) ) {
        return $sql[ 0 ]->taxonomy;
    }

    return $df;
}

function WGR_get_all_term( $id ) {
    $taxonomy = WGR_get_taxonomy_name( $id );
    //	echo $id . '<br>';
    //	echo $taxonomy . '<br>';
    if ( $taxonomy == '' ) {
        return ( object )array( 'errors' => 'Taxonomy not found!' );
    }

    $t = get_term( $id, $taxonomy );
    //	print_r( $t );
    //	echo 'bbbbbbbbbbb<br>';
    //	echo gettype($t);
    //
    if ( gettype( $t ) == 'object' && !isset( $t->errors ) ) {
        return $t;
    }

    //
    $t = get_term( $id, 'category' );
    //	print_r( $t );

    if ( isset( $t->errors ) ) {
        $t = get_term( $id, EB_BLOG_POST_LINK );
        //		print_r( $t );

        if ( isset( $t->errors ) ) {
            $t = get_term( $id, 'post_tag' );
            //			print_r( $t );

            if ( isset( $t->errors ) ) {
                $t = get_term( $id, 'post_options' );
                //				print_r( $t );
            }
        }
    }

    //
    return $t;
}
