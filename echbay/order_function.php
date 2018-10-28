<?php


//
//echo ECHBAY_PRI_CODE;



function WGR_create_order_filter($o) {
	global $date_server;
//	echo $date_server . '<br>' . "\n";
	
	global $year_curent;
//	echo $year_curent . '<br>' . "\n";
	
	global $month_curent;
//	echo $month_curent . '<br>' . "\n";
	
	global $day_curent;
//	echo $day_curent . '<br>' . "\n";
	
	
	//
	if ($o == 'all') {
		return '';
	}
	
	
	//
	$return_filter = '';
	switch ($o) {
		case "between" :
			if (isset ( $_GET ['d1'] ) && ($d1 = trim ( $_GET ['d1'] )) != '') {
				if (isset ( $_GET ['d2'] ) && ($d2 = trim ( $_GET ['d2'] )) != '') {
				} else {
					$d2 = $d1;
				}
				$thang_trc = strtotime ( urldecode( $d1 ) );
				$thang_sau = strtotime ( urldecode( $d2 ) ) + (24 * 3600);
				$return_filter = "order_time > " . $thang_trc . " AND order_time < " . $thang_sau;
			}
			break;
		case "thismonth" :
			$thang_nay = strtotime ( $year_curent . "-" . $month_curent . "-01" );
			$return_filter = " order_time > " . $thang_nay;
			break;
		case "yesterday" :
			$str_date = strtotime ( $date_server );
			$return_filter = " (order_time > " . ($str_date - (24 * 3600)) . " AND order_time < " . $str_date . ") ";
			break;
		case "lastmonth" :
			$_month_curent = $month_curent - 1;
			$_year_curent = $year_curent;
			if ($_month_curent == 0) {
				$_month_curent = 12;
				$_year_curent -= 1;
			}
			$thang_truoc = strtotime ( $_year_curent . "-" . $_month_curent . "-01" );
			$thang_nay = strtotime ( $year_curent . "-" . $month_curent . "-01" );
			$return_filter = "(order_time > " . $thang_truoc . " AND order_time < " . $thang_nay . ")";
			break;
		case "last7days" :
			$return_filter = "order_time > " . (date_time - (24 * 3600 * 7));
			break;
		case "last30days" :
			$return_filter = "order_time > " . (date_time - (24 * 3600 * 30));
			break;
		case "today" :
			$return_filter = "order_time > " . strtotime ( $date_server );
			break;
		case "hrs24" :
			$return_filter = " order_time > " . (date_time - (24 * 3600));
			break;
		default :
			$return_filter = "order_time > " . (date_time - (24 * 3600 * 7));
//			$return_filter = " order_time > " . (date_time - (24 * 3600));
	}
//	echo $return_filter . ' - aaaaaaaaaaaaaaaa<br>' . "\n";
	
	if ($return_filter != "") {
		return " AND " . $return_filter;
	}
	
	return '';
}





