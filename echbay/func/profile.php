<?php


/*
print_r( $_POST );
print_r( $_GET );
exit();
*/
$_POST = EBE_stripPostServerClient();

// kiểm tra dữ liệu đầu vào phải đầy đủ
//WGR_check_ebnonce();


// nếu là yêu cầu xác thực tài khoản
if ( isset( $_POST[ 'verification_code' ] ) ) {
    $arr_user_profile = get_userdata( mtv_id );
    //print_r( $arr_user_profile );

    //
    if ( isset( $arr_user_profile->data ) && isset( $arr_user_profile->data->user_activation_key ) && $arr_user_profile->data->user_activation_key == trim( $_POST[ 'verification_code' ] ) ) {
        // hủy bỏ phần xác thực
        wp_update_user(
            array(
                'user_activation_key' => '',
                'ID' => mtv_id
            )
        );

        // thay đổi role của khách
        global $wp_roles;
        $check_roles_exist = [];
        //print_r( $wp_roles );
        if ( isset( $wp_roles->roles ) ) {
            foreach ( $wp_roles->roles as $k => $v ) {
                $check_roles_exist[] = $k;
            }
            //print_r( $check_roles_exist );

            //
            $user = new WP_User( $mtv_id );

            // chuyển quyền thành khách hàng -> woocomerce
            if ( in_array( 'customer', $check_roles_exist ) ) {
                $user->set_role( 'customer' );
                /*
            } else {
                $user->set_role( 'subscriber' );
                */
            }
        }

        //
        _eb_alert( 'Xác thực tài khoản thành công! Cảm ơn quý khách.', web_link . 'profile' );
    }

    //
    _eb_alert( 'Mã xác thực không chính xác! Vui lòng kiểm tra lại...' );
}


//
$t_hoten = trim( $_POST[ 't_hoten' ] );
$t_dienthoai = trim( $_POST[ 't_dienthoai' ] );
$t_diachi = trim( $_POST[ 't_diachi' ] );


//
if ( mtv_id <= 0 ) {
    _eb_alert( EBE_get_lang( 'pr_no_id' ) );
}


//
wp_update_user(
    array(
        'first_name' => $t_hoten,
        'ID' => mtv_id
    )
);
update_user_meta( mtv_id, 'address', $t_diachi );
update_user_meta( mtv_id, 'phone', $t_dienthoai );


//
_eb_alert( EBE_get_lang( 'pr_done' ) );