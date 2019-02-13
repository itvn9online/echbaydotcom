


(function () {
	
	// thông tin khách hàng
	order_details_arr_cart_customer_info = jQuery.parseJSON( unescape( order_details_arr_cart_customer_info ) );
	console.log( order_details_arr_cart_customer_info );
	
	jQuery('.hd_ten').html( order_details_arr_cart_customer_info.hd_ten );
	
	jQuery('.hd_diachi').html( order_details_arr_cart_customer_info.hd_diachi );
	if ( typeof order_details_arr_cart_customer_info['hd_quanhuyen'] != 'undefined' && order_details_arr_cart_customer_info['hd_quanhuyen'] != '' ) {
		jQuery('.hd_diachi').append( ', ' + order_details_arr_cart_customer_info['hd_quanhuyen'] );
	}
	if ( typeof order_details_arr_cart_customer_info['hd_tinhthanh'] != 'undefined' && order_details_arr_cart_customer_info['hd_tinhthanh'] != '' ) {
		jQuery('.hd_diachi').append( ', ' + order_details_arr_cart_customer_info['hd_tinhthanh'] );
	}
	
	jQuery('.hd_dienthoai').html( order_details_arr_cart_customer_info.hd_dienthoai );
	
	// phí vận chuyển
	/*
	if ( typeof order_details_arr_cart_customer_info.hd_phivanchuyen == 'number' && order_details_arr_cart_customer_info.hd_phivanchuyen > 0 ) {
		jQuery('#phi_van_chuyen').addClass('ebe-currency').html( g_func.money_format( order_details_arr_cart_customer_info.hd_phivanchuyen ) );
	}
	*/
	
	
	
	// thông tin hóa đơn
	order_details_arr_cart_product_list = jQuery.parseJSON( unescape( order_details_arr_cart_product_list ) );
	console.log( order_details_arr_cart_product_list );
	
	//
	var str = '',
		str2 = '',
		tong_tien1 = 0,
		product_price = 0,
		j = 1;
	for ( var i = 0; i < order_details_arr_cart_product_list.length; i++ ) {
		
		if ( order_details_arr_cart_product_list[i].quan > 0 ) {
			product_price = order_details_arr_cart_product_list[i].price;
			// hiển thị giá theo size, color nếu có
			if ( typeof order_details_arr_cart_product_list[i].child_price != 'undefined' && order_details_arr_cart_product_list[i].child_price != '' && order_details_arr_cart_product_list[i].child_price * 1 > 0 ) {
				product_price = order_details_arr_cart_product_list[i].child_price;
			}
			
			//
			var total_line = product_price * order_details_arr_cart_product_list[i].quan;
			tong_tien1 += total_line;
			
			//
			if ( order_details_arr_cart_product_list[i].color != '' ) {
				order_details_arr_cart_product_list[i].color = jQuery.trim( order_details_arr_cart_product_list[i].color );
				if ( order_details_arr_cart_product_list[i].color.substr( 0, 1 ) != '-' ) {
					order_details_arr_cart_product_list[i].color = '- ' + order_details_arr_cart_product_list[i].color;
				}
				
				order_details_arr_cart_product_list[i].color = ' ' + order_details_arr_cart_product_list[i].color;
			}
			
			if ( order_details_arr_cart_product_list[i].size != '' ) {
				order_details_arr_cart_product_list[i].size = jQuery.trim( order_details_arr_cart_product_list[i].size );
				if ( order_details_arr_cart_product_list[i].size.substr( 0, 1 ) != '(' ) {
					order_details_arr_cart_product_list[i].size = '(Size: ' + order_details_arr_cart_product_list[i].size + ')';
				}
				
				order_details_arr_cart_product_list[i].size = ' ' + order_details_arr_cart_product_list[i].size;
			}
			
			//
			str += '\
<tr>\
	<td>' + j + '</td>\
	<td>' + order_details_arr_cart_product_list[i].name + order_details_arr_cart_product_list[i].color + order_details_arr_cart_product_list[i].size + '</td>\
	<td>&nbsp;</td>\
	<td><span class="ebe-currency">' + g_func.money_format( product_price ) + '</span></td>\
	<td>' + order_details_arr_cart_product_list[i].quan + '</td>\
	<td><span class="ebe-currency">' + g_func.money_format( total_line ) + '</span></td>\
</tr>';
			
			// for vận đơn
			str2 += '<div>' + j + '. ' + order_details_arr_cart_product_list[i].name + order_details_arr_cart_product_list[i].color + order_details_arr_cart_product_list[i].size + '</div>';
			
			//
			j++;
			
		}
		
		//
		if ( print_type == 'print_van_don' ) {
			jQuery('.product_vandon_list').html( str2 );
		}
		else {
			jQuery('.public-table-print .tr-title').after( str );
			jQuery('#tong_tien1').html( g_func.money_format( tong_tien1 ) );
		}
		
		// tính giảm giá nếu có
		if ( typeof order_details_arr_cart_customer_info['hd_chietkhau'] != 'undefined' && order_details_arr_cart_customer_info['hd_chietkhau'] != '' ) {
			
			// tính theo %
			var ck = 0;
			if ( order_details_arr_cart_customer_info['hd_chietkhau'].toString().split('%').length > 1 ) {
				if ( jQuery('#show_chiet_khau').length > 0 ) {
					jQuery('#show_chiet_khau').html( '-<span>' + order_details_arr_cart_customer_info['hd_chietkhau'] + '</span>' );
				}
				
				//
				ck = g_func.float_only( order_details_arr_cart_customer_info['hd_chietkhau'] );
				ck = tong_tien1/ 100 * ck;
			}
			// chiết khấu trực tiếp
			else {
				ck = g_func.float_only( order_details_arr_cart_customer_info['hd_chietkhau'] );
				
				if ( jQuery('#show_chiet_khau').length > 0 ) {
					jQuery('#show_chiet_khau').html( '-<span class="ebe-currency">' + g_func.money_format( ck ) + '</span>' );
				}
			}
			
			//
			if ( ck > 0 ) {
				tong_tien1 = tong_tien1 - ( ck * 1 );
				jQuery('.show-if-chietkhau').show();
			}
		}
		
		// tính phí vận chuyển
		// phí vận chuyển tính sau cùng
		if ( typeof order_details_arr_cart_customer_info['hd_phivanchuyen'] != 'undefined' && order_details_arr_cart_customer_info['hd_phivanchuyen'] != '' && order_details_arr_cart_customer_info['hd_phivanchuyen'] > 0 ) {
			if ( jQuery('#phi_van_chuyen').length > 0 ) {
				jQuery('#phi_van_chuyen').html( '<span class="ebe-currency">' + g_func.money_format( order_details_arr_cart_customer_info['hd_phivanchuyen'] ) + '</span>' );
			}
			
			//
			tong_tien1 = tong_tien1 + ( order_details_arr_cart_customer_info['hd_phivanchuyen'] * 1 );
		}
		
		//
		jQuery('#tinh_tong_tien').html( g_func.money_format( tong_tien1 ) );
	}
	
})();



