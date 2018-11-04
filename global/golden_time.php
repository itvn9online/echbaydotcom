<?php


//
$arr_get_by_status = array(
	'products_hot' => 1,
	'products_new' => 2,
	'products_selling' => 3,
	'products_sales_off' => 4,
//	'all' => 5,
	'golden_time' => 6
);


//
if ( $act == 'products_all' ) {
	$products_list = _eb_load_post( EBE_get_lang('limit_products_list') );
}
else {
	$products_list = _eb_load_post( EBE_get_lang('limit_products_list'), array(
		'meta_key' => '_eb_product_status',
		'meta_value' => $arr_get_by_status[ $act ]
	) );
}




