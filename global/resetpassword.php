<?php



//
//print_r( $_GET );


//
$group_go_to[] = ' <li>Khởi tạo lại mật khẩu</li>';



if (isset ( $_GET ['t'], $_GET ['block'], $_GET ['u'], $_GET ['code'] )) {
	// time
	$t = (int) $_GET ['t'];
	$block = $_GET ['block'];
	
	// mail
	$u = _eb_decode( trim( $_GET ['u'] ) );
	$code = $_GET ['code'];
	
	
	// kiểm tra dữ liệu đầu vào
	if (
		date_time - $t < 3600 && _eb_mdnam ( $t ) == $block
		&& $u != '' && strpos( $u, '@' ) && _eb_mdnam ( $u ) == $code
	) {
		
		//
		$user_id = email_exists( $u );
//		echo $user_id . '<br>' . "\n";
		$arr_user_meta = get_user_meta($user_id);
//		print_r( $arr_user_meta );
		
		// Nếu trước đó người dùng đã reset pass bằng link này -> bỏ qua luôn
		if ( isset( $arr_user_meta['eb_reset_pass'] ) && $arr_user_meta['eb_reset_pass'][0] == $t ) {
			$main_content = '<strong class="orgcolor">Đường dẫn đổi mật khẩu này đã được sử dụng! Bạn vui lòng kiểm tra email để lấy mật khẩu mới. Nếu vẫn không thấy, có thể chờ thêm hoặc lặp lại thao tác lấy lại mật khẩu.</strong>';
		}
		// chưa thì tiền hành reset pass thôi
		else {
			$rand_pass = md5 ( date_time );
			$rand_pass = substr ( $rand_pass, 0, 12 );
//			echo $rand_pass . '<br>' . "\n";
			
			//
			wp_set_password( $rand_pass, $user_id );
			
			// cập nhật thời gian reset pass để chặn không cho reset liên tục
			update_user_meta( $user_id, 'eb_reset_pass', $t );
			
			// xóa lệnh chặn gửi reset pass
			delete_user_meta( $user_id, 'eb_active_reset_pass' );
			
			//
			$main_content = 'Mật khẩu đã được thay đổi';
			
			
			//
			$message = EBE_str_template( 'resetpassword.html', array(
				'tmp.ngaygui' => date ( 'r', date_time ),
				'tmp.email' => $u,
				'tmp.web_name' => $web_name,
				'tmp.web_link' => web_link,
				'tmp.matkhau' => $rand_pass 
			), EB_THEME_PLUGIN_INDEX . 'html/mail/' );
			
			
			//
			if ( _eb_send_email ( $u, 'Khoi tao lai mat khau dang nhap', $message ) == true ) {
				$main_content .= ' và gửi về email: <span class="bluecolor">' . $u . '</span>. Vui lòng kiểm tra email để lấy mật khẩu mới.';
			}
			
		}
	} else {
		$main_content = 'Dữ liệu không chính xác...';
	}
} else {
	$main_content = 'Dữ liệu không đầy đủ';
}

//
$main_content = '<h3 class="text-center w99">' . $main_content . '</h3>';




