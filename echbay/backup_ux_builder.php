<p class="redcolor medium">* Chức năng này sẽ tiến hành backup các bài viết dạng page, blocks để đề phòng trường hợp bị xóa dữ liệu thì vẫn còn bản backup mà restore.</p>
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
        $day_backup = date( 'Ymd-H' );

        //
        $sql = _eb_q( "SELECT ID, post_name, post_content, post_status, post_type
        FROM
            `" . wp_posts . "`
        WHERE
            post_status = 'publish'
            AND ( post_type = 'page' OR post_type = 'blocks' )" );
        //print_r( $sql );

        foreach ( $sql as $v ) {
            if ( trim( $v->post_content ) == '' ) {
                continue;
            }

            // file tồn tại rồi thì thôi, backup tầm 1 tiếng 1 lần
            $file_backup = $ux_builder_dir . '/' . $v->post_name . '-' . $day_backup . '-' . $v->post_type . '-' . $v->ID . '.txt';
            if ( file_exists( $file_backup ) ) {
                echo 'Backup exist: <em>' . basename( $file_backup ) . '</em><br>' . "\n";
                continue;
            }

            //
            _eb_create_file( $file_backup, $v->post_content );
            echo 'Create new: <strong>' . basename( $file_backup ) . '</strong><br>' . "\n";
        }
    } else {
        echo 'dir backup ux-builder not found! <br>' . "\n";
    }

}
