<?php



//print_r($_POST);


$list_id = trim( $_POST['t_list_id'] );
$list_id = substr( $list_id, 1 );

if ( $list_id == '' ) {
	_eb_alert('Không xác định được ID cần xử lý');
}
$list_id = explode(",", $list_id);



// Múc đích của việc cập nhật
$actions_for = trim( $_POST['actions_for'] );

// Đối với 1 số chức năng (Thông số khác) -> cần xác định người dùng đang muốn cập nhật cho thông số nào
$actions_id_for = (int) $_POST['actions_id_for'];
//$func->alert($actions_id_for);


//
$strFilter = "";



//
echo '<script>
function WHR_show_console_in_multi ( m ) {
	console.log(m);
}
</script>';



//
if ( $actions_for == 'status' ) {
	if ( isset( $_POST['t_trangthai'] ) ) {
		$trv_trangthai = ( int ) $_POST ['t_trangthai'];
		
		//
		foreach ( $list_id as $v ) {
			$v = (int) trim( $v );
			if ( $v > 0 ) {
				WGR_update_meta_post( $v, '_eb_product_status', $gia );
			}
		}
	}
	else {
		_eb_alert('Chưa chọn [Trạng thái] mới');
	}
}
else if ( $actions_for == 'enddate' ) {
	$trv_ngayhethan = trim($_POST['t_ngayhethan']);
	
	if ( strlen( $trv_ngayhethan ) == 10 ) {
		foreach ( $list_id as $v ) {
			$v = (int) trim( $v );
			if ( $v > 0 ) {
				WGR_update_meta_post( $v, '_eb_product_ngayhethan', $trv_ngayhethan );
			}
		}
	}
	else {
		foreach ( $list_id as $v ) {
			$v = (int) trim( $v );
			if ( $v > 0 ) {
				delete_post_meta( $v, '_eb_product_ngayhethan' );
			}
		}
	}
}
else if ( $actions_for == 'giamgia' ) {
	$phantram_giamgia = (int)$_POST['t_giamgia'];
	echo '<script>WHR_show_console_in_multi("' . $phantram_giamgia . '");</script>';
	
	//
	foreach ( $list_id as $v ) {
		$v = (int) trim( $v );
		if ( $v > 0 ) {
			$sql = _eb_q("SELECT *
			FROM
				`" . wp_postmeta . "`
			WHERE
				meta_key = '_eb_product_oldprice'
				AND post_id = " . $v . "
			ORDER BY
				meta_id DESC
			LIMIT 0, 1");
//			print_r( $sql );
			
			//
			if ( ! empty( $sql ) ) {
				$gia = $sql[0]->meta_value;
				
				// nếu không có giá cu -> tìm theo giá mới
				if ( $sql[0]->meta_value == 0 ) {
					$sql = _eb_q("SELECT *
					FROM
						`" . wp_postmeta . "`
					WHERE
						meta_key = '_eb_product_price'
						AND post_id = " . $v . "
					ORDER BY
						meta_id DESC
					LIMIT 0, 1");
//					print_r( $sql );
					if ( ! empty( $sql ) ) {
						$gia = $sql[0]->meta_value;
					}
					else {
						echo '<script>WHR_show_console_in_multi("Product not found (2) #' . $v . '");</script>';
					}
				}
				
				// tìm được giá thì mới xử lý
				if ( $gia == 0 ) {
					echo '<script>WHR_show_console_in_multi("Product price is zero #' . $v . '");</script>';
				}
				else {
					$gia = $gia - ceil( $gia/ 100 * $phantram_giamgia );
//					echo $gia . '<br>';
					
					// cập nhật giá
					WGR_update_meta_post( $v, '_eb_product_price', $gia );
				}
			}
			else {
				echo '<script>WHR_show_console_in_multi("Product not found #' . $v . '");</script>';
			}
		}
	}
}
else if ( $actions_for == 'stt' ) {
	$trv_stt = _eb_number_only( $_POST ['t_stt'] );
	
	if ( $trv_stt < 0 ) {
		$trv_stt = 0;
	}
	
	//
	$arr_for_update = array();
	$arr_for_update['menu_order'] = $trv_stt;
	
	//
	foreach ( $list_id as $v ) {
		$v = (int) trim( $v );
		if ( $v > 0 ) {
			$arr_for_update['ID'] = $v;
			
			$post_id = WGR_update_post( $arr_for_update, 'ERROR!' );
		}
	}
}
else {
	_eb_alert('Không xác định được chức năng cần thiết!');
}


//
die('<script>parent.WGR_after_update_multi_post();</script>');




exit();


