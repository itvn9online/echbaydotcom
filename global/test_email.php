<?php

//echo mtv_id . ' - ' . __DIR__ . '<br>' . "\n";

//
$eb_user_info = wp_get_current_user();
//print_r( $current_user );
//echo 'aaaaaaaaaaaa<br>' . "\n";
//print_r( $eb_user_info );
//print_r( $_GET );

//
if ( mtv_id == 0 ) {
	die(_eb_full_url() . '<br>For user only! Please login to check this function...');
}


//
$email = '';
if ( isset ( $_GET ['email'] ) ) {
	$email = trim ( $_GET ['email'] );
	
	// nếu email không đúng định dạng -> hủy bỏ luôn
	if ( _eb_check_email_type( $email ) != 1 ) {
		$email = '';
	}
}

if ( $email == '' ) {
	$email = $__cf_row['cf_email'];
}


//
$mesage = nl2br( trim( '

Nội dung: test email
Host: ' . $_SERVER ['HTTP_HOST'] . '
Ngày thử: ' . date ( 'd-m-Y H:i:s', $date_time ) . '
IP thử: ' . $client_ip . '
User: #' . mtv_id . '

' ) );


// test thì bật chế độ test lên để còn xem lỗi
$__cf_row['cf_tester_mode'] = 1;

//
if ( _eb_send_email( $email, 'Test email via EchBay e-commerce plugin', $mesage ) == true ) {
	echo 'Send to ' . $email;
}
else {
	echo 'ERROR! ' . $email;
}




exit();




