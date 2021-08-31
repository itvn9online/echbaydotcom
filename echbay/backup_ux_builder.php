<p class="redcolor medium">* Chức năng này sẽ tiến hành backup các bài viết dạng page, blocks để đề phòng trường hợp bị xóa dữ liệu thì vẫn còn bản backup mà restore. Bấm chọn một file để xem nội dung bên trong, sau đó có thể copy nội dung đó để sửa dụng ch việc restore!</p>
<?php

//
if ( defined( 'EB_CHILD_THEME_URL' ) ) {
    //echo EB_CHILD_THEME_URL . '<br>' . "\n";
    $ux_builder_dir = EB_CHILD_THEME_URL . 'ux-builder';
    if ( !is_dir( $ux_builder_dir ) ) {
        EBE_create_dir( $ux_builder_dir );
    }

    //
    if ( is_dir( $ux_builder_dir ) ) {
        //$day_backup = date( 'Ymd-H' );

        //
        $show_file = isset( $_GET[ 'show_file' ] ) ? trim( $_GET[ 'show_file' ] ) : '';
        $backup_ext = '.tpl';

        //
        $file_content = '';
        if ( $show_file != '' ) {
            if ( file_exists( $ux_builder_dir . '/' . $show_file . $backup_ext ) ) {
                $file_content = file_get_contents( $ux_builder_dir . '/' . $show_file . $backup_ext, 1 );

                echo '<div><textarea style="height: 400px;width: 95%;">' . $file_content . '</textarea></div>';
            }
        }


        // danh sách toàn bộ file backup
        echo '<h3 class="top-menu-space">Danh sách toàn bộ file backup:</h3>';
        echo '<ol>';

        //
        foreach ( glob( $ux_builder_dir . '/*' . $backup_ext ) as $filename ) {
            $filename = basename( $filename, $backup_ext );

            //
            echo '<li><a href="' . admin_link . 'admin.php?page=eb-coder&tab=backup_ux_builder&show_file=' . $filename . '">' . $filename . '</a></li>';
        }

        echo '</ol>';


        //
        if ( $show_file == '' ) {
            $sql = _eb_q( "SELECT *
            FROM
                `" . wp_posts . "`
            WHERE
                post_status = 'publish'
                AND ( post_type = 'page' OR post_type = 'blocks' )" );
            //print_r( $sql );

            //
            foreach ( $sql as $v ) {
                //echo $v->post_date . '<br>' . "\n";
                //echo $v->post_date_gmt . '<br>' . "\n";
                //echo $v->post_modified . '<br>' . "\n";
                //echo $v->post_modified_gmt . '<br>' . "\n";

                // lấy ngày thay đổi cuối để tạo backup -> nếu không có thay đổi thì không tạo backup
                if ( !isset( $v->post_modified_gmt ) || empty( $v->post_modified_gmt ) ) {
                    echo '$post_modified_gmt not found!: ' . $v->post_type . '#' . $v->ID . '<br>' . "\n";
                    continue;
                }
                $post_modified_gmt = date( 'Ymd-H', strtotime( $v->post_modified_gmt ) );
                //echo 'post_modified_gmt: ' . $post_modified_gmt . '<br>' . "\n";

                //
                if ( trim( $v->post_content ) == '' ) {
                    continue;
                }

                // file tồn tại rồi thì thôi, backup tầm 1 tiếng 1 lần
                $file_backup = $ux_builder_dir . '/' . $v->post_name . '-' . $post_modified_gmt . '-' . $v->post_type . '-' . $v->ID . $backup_ext;
                if ( file_exists( $file_backup ) ) {
                    //echo 'Backup exist: <em>' . basename( $file_backup ) . '</em><br>' . "\n";
                    continue;
                }

                //
                _eb_create_file( $file_backup, $v->post_content );
                echo 'Create new: <strong>' . basename( $file_backup ) . '</strong><br>' . "\n";
            }
        }
    } else {
        echo 'dir backup ux-builder not found! <br>' . "\n";
    }

}
