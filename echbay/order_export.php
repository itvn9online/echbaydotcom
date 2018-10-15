<?php



include ECHBAY_PRI_CODE . 'order_function.php';

include ECHBAY_PRI_CODE . 'order_filter.php';




$sql = _eb_load_order( $threadInPage, array(
//	'status_by' => $status_by,
	'filter_by' => $strFilter,
	'offset' => $offset
) );

print_r( $sql );





