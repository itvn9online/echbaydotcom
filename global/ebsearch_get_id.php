<?php


/*
 * file này sẽ tìm và lấy ra danh sách ID các bài viết theo điều kiện tìm kiếm -> để sau đó select dữ liệu bằng wp_query của wp
 */

//
$arrFilter = array();
if ( isset( $_GET[ 'q' ] ) ) {
    $current_search_key = trim( $_GET[ 'q' ] );
    $current_search_key = urldecode( $current_search_key );

    //
    if ( $current_search_key != '' ) {
        // bắt đầu tìm kiếm riêng
        //	echo $current_search_key;

        //
        $search_key = _eb_non_mark_seo( $current_search_key );
        $search_key = str_replace( '-', ' ', $search_key );

        // key tìm kiếm kiểu mới
        $trv_key = str_replace( ' ', '', $search_key );

        //
        $explode = explode( ' ', $search_key );
        $strSearch = array();
        $filterSearch = "";

        // tìm tương đối
        if ( count( $explode ) == 1 ) {
            $strSearch[] = " post_id LIKE '%{$trv_key}%' ";
            $strSearch[] = " ( meta_key = '_eb_product_searchkey' AND meta_value LIKE '%{$trv_key}%' ) ";
            $strSearch[] = " ( meta_key = '_eb_product_sku' AND meta_value LIKE '%{$trv_key}%' ) ";
            $filterSearch = implode( ' OR ', $strSearch );
        } else {
            if ( count( $explode ) > 4 ) {
                $i = 1;
                $str_node_Search = '';

                //
                foreach ( $explode as $v ) {
                    $str_node_Search .= $v;

                    //
                    if ( $i % 3 == 0 ) {
                        $str_node_Search = trim( $str_node_Search );
                        if ( $str_node_Search != '' ) {
                            $strSearch[] = " meta_value LIKE '%{$str_node_Search}%' ";
                            $str_node_Search = '';
                        } else {
                            $i--;
                        }
                    }
                    $i++;
                }

                //
                if ( $str_node_Search != '' && strlen( $str_node_Search ) > 5 ) {
                    $str_node_Search = trim( $str_node_Search );
                    if ( $str_node_Search != '' ) {
                        $strSearch[] = " meta_value LIKE '%{$str_node_Search}%' ";
                    }
                }

                //
                $filterSearch = " meta_key = '_eb_product_searchkey' AND (" . implode( ' OR ', $strSearch ) . ")";
            } else {
                $filterSearch = " meta_key = '_eb_product_searchkey' AND meta_value LIKE '%{$trv_key}%' ";
            }
        }

        //
        //	$strSearch = " AND (" . $strSearch . ")";
        //	echo $strSearch;

        //
        //	global $wpdb;

        //
        $sql = "SELECT post_id, meta_value
		FROM
			`" . wp_postmeta . "`
		WHERE
			" . $filterSearch . "
			AND post_id IN (SELECT ID
							FROM
								`" . wp_posts . "`
							WHERE
								post_type = 'post'
								AND post_status = 'publish')
        GROUP BY
            post_id";
        //echo $sql . '<br>' . "\n";
        $sql = _eb_q( $sql );
        //print_r( $sql );
        if ( !empty( $sql ) ) {
            foreach ( $sql as $v ) {
                $arrFilter[] = $v->post_id;
            }
        }

        //
        $sql = "SELECT ID, post_title, post_excerpt
        FROM
            `" . wp_posts . "`
        WHERE
            post_type = 'post'
            AND post_status = 'publish'
            AND (post_title LIKE '%{$current_search_key}%' OR post_excerpt LIKE '%{$current_search_key}%')
        GROUP BY
            ID";
        //echo $sql . '<br>' . "\n";
        $sql = _eb_q( $sql );
        //print_r( $sql );
        if ( !empty( $sql ) ) {
            foreach ( $sql as $v ) {
                $arrFilter[] = $v->ID;
            }
        }

        //
        //print_r( $arrFilter );
        $arrFilter = array_unique( $arrFilter );
    }
}