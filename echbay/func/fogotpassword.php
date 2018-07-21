<?php


//print_r($_POST);
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

// có thì trả về luôn
if ( $user_id > 0 ) {
	// gán dữ liệu để hàm của wp hoạt động
	$_POST['user_login'] = $user_email;
	
	$errors = retrieve_password();
	
	if ( is_wp_error($errors) ) {
		print_r( $errors );
		_eb_alert( 'Lỗi!' );
	}
} else {
	_eb_alert( 'Email không tồn tại, vui lòng nhập lại' );
}


//
die('<script type="text/javascript">

if ( top != self ) {
	parent.document.frm_dangnhap.reset();
}
else {
	window.opener.document.frm_dangnhap.reset();
}

</script>');

_eb_alert( 'Vui lòng kiểm tra email để tiếp tục' );




