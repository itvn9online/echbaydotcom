<?php


$_POST = EBE_stripPostServerClient ();

// kiểm tra dữ liệu đầu vào phải đầy đủ
//WGR_check_ebnonce();

//print_r($_POST);
//die('fgjd dfhfjg ');
if ( !isset($_POST['t_email']) ) {
	_eb_alert('Dữ liệu đầu vào không chính xác');
}


//
$user_email = _eb_non_mark( strtolower( trim( $_POST['t_email'] ) ) );


//
if ( _eb_check_email_type ( $user_email ) != 1 ) {
	_eb_alert( 'Email không đúng định dạng' );
}


// tìm theo email
$user_id = email_exists( $user_email );
$m = 'Gửi yêu cầu lấy lại mật khẩu thành công!';

// có thì trả về luôn
if ( $user_id > 0 ) {
	
	/*
	* Tự viết chức năng quên pass
	*/
	$arr_user_meta = get_user_meta($user_id);
//	print_r( $arr_user_meta );
	
	// nếu lần gửi reset pass trước đó vẫn còn hạn và chưa được kích hoạt
	if ( isset( $arr_user_meta['eb_active_reset_pass'] ) && $arr_user_meta['eb_active_reset_pass'][0] * 1 > date_time ) {
	}
	else {
		$link = web_link . 'resetpassword?t=' . date_time . '&u=' . _eb_encode( $user_email ) . '&code=' . _eb_mdnam ( $user_email ) . '&block=' . _eb_mdnam ( date_time );
		
		//
		$message = EBE_str_template( 'fogotpassword.html', array(
			'tmp.link' => $link,
			'tmp.web_link' => web_link,
			'tmp.web_name' => $web_name,
//			'tmp.tv_email' => $user_email,
			'tmp.ngaygui' => date ( 'r', date_time ) 
		), EB_THEME_PLUGIN_INDEX . 'html/mail/' );
		
		//
		if ( _eb_send_email ( $user_email, 'Lay lai mat khau dang nhap', $message ) == true ) {
			$m .= ' Vui lòng kiểm tra email và làm theo hướng dẫn để tiếp tục.';
			
			// cập nhật thời gian reset pass để chặn không cho reset liên tục
			update_user_meta( $user_id, 'eb_active_reset_pass', date_time + 3600 );
		}
		
		
		
		
		/*
		* Sử dụng thông qua hàm của WP -> thấy có lõi -> quay sang sử dụng hàm riêng vậy
		*/
		if ( 1 == 2 ) {
			// gán dữ liệu để hàm của wp hoạt động
			$_POST['user_login'] = $user_email;
			
			// cái này là cho plugin echbay-admin-security
			$_POST[ 'eas_token_by_echbaydotcom' ] = md5( date_time );
			
			// gọi đến trang wp-login để nó xử lý việc còn lại
			ob_start();
			include ABSPATH . 'wp-login.php';
			ob_end_clean();
			
			//
//			exit();
			
			// gọi đến hàm xử lý phần quên pass của wordpress
			if ( function_exists('retrieve_password') ) {
				$errors = retrieve_password();
				
				if ( is_wp_error($errors) ) {
					print_r( $errors );
					_eb_alert( 'Lỗi!' );
				}
			}
			else {
				_eb_alert( 'Function retrieve_password not found!' );
			}
		}
	}
} else {
	_eb_alert( 'Email không tồn tại, vui lòng nhập lại' );
}


//
die('<script type="text/javascript">

if ( top != self ) {
	parent.document.frm_quenpass.reset();
}
else {
	window.opener.document.frm_quenpass.reset();
}

alert("' . $m . '");

</script>');

_eb_alert( 'Vui lòng kiểm tra email để tiếp tục' );




