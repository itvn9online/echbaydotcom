<?php



//print_r( $_GET );
if ( ! isset( $_GET['img'] ) ) {
	die('img not found!');
}

if ( ! isset( $_GET['file_name'] ) ) {
	die('file_name not found!');
}

//
$file_source = $_GET['img'];


// thư mục download
$save_to = ABSPATH . 'ebarchive/';
//echo $save_to . '<br>' . "\n";

// tạo thư mục để download ảnh về
EBE_create_dir( $save_to, 1, 0777 );


// thêm năm vào khu vực lưu trữ
$set_year = $year_curent;
if ( isset( $_GET['set_year'] ) ) {
	$set_year = (int) $_GET['set_year'];
	if ( $set_year < 1970 ) {
		$set_year = $year_curent;
	}
}
$save_to .= $set_year . '/';
EBE_create_dir( $save_to, 1, 0777 );


// thêm tháng luôn
$save_to .= $month_curent . '/';
EBE_create_dir( $save_to, 1, 0777 );


// file download
$file_name = $_GET['file_name'];
$file_name = explode( '?', $file_name );
$file_name = $file_name[0];
$save_to .= $file_name;
//echo $save_to . '<br>' . "\n";
if ( ! file_exists( $save_to ) ) {
	//
//	set_time_limit(0);
	
	if( copy( $file_source, $save_to ) ) {
		chmod($save_to, 0777) or die('ERROR chmod file: ' . $save_to);
	} else {
		// xử lý lỗi copy ảnh qua SSL
		// https://www.youtube.com/watch?v=5vjbOqdxtEM
		$re_copy_file = 0;
		if ( function_exists('stream_context_set_default') ) {
			stream_context_set_default([
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false
				)
			]);
			
			$re_copy_file = 1;
		}
		
		if( $re_copy_file === 1 && copy( $file_source, $save_to ) ) {
			chmod($save_to, 0777) or die('ERROR chmod file: ' . $save_to);
		}
		else {
			die('ERROR copy file');
		}
	}
}
else if ( ! isset( $_GET['show_url_img'] ) ) {
	echo 'file exist!';
}

//
$save_to = str_replace( ABSPATH, web_link, $save_to );
//echo $save_to . '<br>' . "\n";


//
if ( isset( $_GET['show_url_img'] ) ) {
	die( $save_to );
}


//
echo '<script>
if ( top != self ) {
	parent.finish_download_img_other_domain_of_content("' . $save_to . '");
}
</script>';




exit();



