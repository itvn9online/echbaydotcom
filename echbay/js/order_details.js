


// nếu hóa đơn được mở trong popup -> ẩn 1 số thông tin đi cho gọn
if ( top != self ) {
	// thêm class để ẩn bớt dữ liệu
	jQuery('body').addClass('order_open_in_popup').addClass('folded');
	jQuery('html').addClass('order_open_in_popup');
	
	//
	parent.WGR_after_open_order_details_popup();
	
	//
	jQuery('a').attr({
		target: '_blank'
	});
}
/*
else if ( typeof window.opener == 'object' ) {
	window.opener.WGR_after_open_order_details_popup();
}
*/
//console.log( typeof window.opener );



//
var arr_global_js_order_details = [],
	arr_global_js_order_customter = {},
	auto_add_slug_if_not_exist = false,
	co_thay_doi_chua_duoc_luu = false,
	auto_submit_order_details = false,
	time_submit_order_details = null,
	khong_submit_khi_bam_enter = false;


function WGR_auto_submit_order_details () {
	
	//
	auto_submit_order_details = true;
	
	// hẹn giờ sau 1 chút, nếu vẫn true -> auto submit
	clearTimeout( time_submit_order_details );
	time_submit_order_details = setTimeout(function () {
		if ( auto_submit_order_details == true ) {
			document.frm_invoice_details.submit();
			console.log('Auto save order details!');
		}
	}, 2500);
}

function WGR_order_details_after_update () {
	// nếu đơn hàng được sửa trong popup -> xử lý phần trạng thái đơn
	if ( top != self ) {
		parent.WGR_order_list_after_update( order_id, document.frm_invoice_details.t_trangthai.value, jQuery('.bill-detail-status button.selected span').html() );
	}
}

function WGR_admin_tinh_tong_hoa_don ( tong ) {
	
	//
//	console.log( tong );
	jQuery('.show-total-order strong').html( g_func.money_format( tong ) );
	
	var ck = jQuery('#hd_chietkhau').val() || '',
		vc = g_func.float_only( jQuery('#hd_phivanchuyen').val() || '' );
//	console.log( ck );
//	console.log( vc );
	
	// Tính số tiền vận chuyển với khuyến mại nếu có
	if ( ck != '' ) {
		// tính theo %
		if ( ck.toString().split('%').length > 1 ) {
			ck = g_func.float_only( ck );
			ck = tong/ 100 * ck;
		}
		// chiết khấu trực tiếp
		else {
			ck = g_func.float_only( ck );
		}
		tong = tong - ( ck * 1 );
//		console.log( tong );
	}
	
	// phí vận chuyển tính sau cùng
	if ( vc != 0 ) {
		tong = tong + ( vc * 1 );
//		console.log( tong );
	}
	
	//
	jQuery('.show-totals-order strong').html( g_func.money_format( tong ) );
	
}

function WGR_admin_tinh_so_luong_hoa_don () {
	
	//
	___eb_admin_update_order_details();
	
	// chạy vòng lặp đếm số lượng và giá trước
	var tong = 0;
	jQuery('.change-update-cart-quantity').each(function() {
		
		var gia = jQuery(this).attr('data-price') || '',
			a = jQuery(this).val() || 0;
//		console.log( gia );
//		console.log( a );
		
		//
		if ( a < 0 ) {
			a = 0;
		}
		
		//
		tong = gia * a;
		
	});
	
	// sau đó mới tính theo phần chiết khấu
	WGR_admin_tinh_tong_hoa_don( tong );
	jQuery('#hd_total_price').val( tong );
	
}

function ___eb_admin_update_order_details () {
	//
	if ( khong_submit_khi_bam_enter == true ) {
		return false;
	}
	
	//
	console.log( arr_global_js_order_details );
	jQuery('#order_products').val( escape( JSON.stringify( arr_global_js_order_details ) ) );
	
	//
//	console.log( arr_global_js_order_customter );
	arr_global_js_order_customter['hd_ten'] = jQuery.trim( jQuery('#oi_hd_ten').val() || '' );
	arr_global_js_order_customter['hd_dienthoai'] = jQuery.trim( jQuery('#oi_hd_dienthoai').val() || '' );
	arr_global_js_order_customter['hd_diachi'] = jQuery.trim( jQuery('#oi_hd_diachi').val() || '' );
	
	// địa chỉ nâng cao
	arr_global_js_order_customter['hd_quanhuyen'] = jQuery.trim( jQuery('#oi_hd_quanhuyen').val() || '' );
	arr_global_js_order_customter['hd_id_quanhuyen'] = jQuery.trim( jQuery('#oi_hd_id_quanhuyen').val() || '' );
	arr_global_js_order_customter['hd_tinhthanh'] = jQuery.trim( jQuery('#oi_hd_tinhthanh').val() || '' );
	arr_global_js_order_customter['hd_id_tinhthanh'] = jQuery.trim( jQuery('#oi_hd_id_tinhthanh').val() || '' );
	
	//
	arr_global_js_order_customter['hd_chietkhau'] = jQuery.trim( jQuery('#hd_chietkhau').val() || '' );
	arr_global_js_order_customter['hd_phivanchuyen'] = jQuery.trim( jQuery('#hd_phivanchuyen').val() || '' );
	arr_global_js_order_customter['hd_admin_ghichu'] = jQuery.trim( jQuery('#hd_admin_ghichu').val() || '' );
	
	// tạo key để sau này tìm kiếm đơn hàng cho tiện
	arr_global_js_order_customter['hd_key'] = g_func.non_mark_seo( arr_global_js_order_customter['hd_ten'] + arr_global_js_order_customter['hd_dienthoai'] + arr_global_js_order_customter['hd_email'] );
	arr_global_js_order_customter['hd_key'] = arr_global_js_order_customter['hd_key'].replace( /\-/g, '' );
	
	//
	console.log( arr_global_js_order_customter );
	jQuery('#order_customer').val( escape( JSON.stringify( arr_global_js_order_customter ) ) );
	
	//
	co_thay_doi_chua_duoc_luu = false;
	/*
	console.log( co_thay_doi_chua_duoc_luu );
	setTimeout(function () {
		console.log( co_thay_doi_chua_duoc_luu );
	}, 600);
	*/
	
	//
	auto_submit_order_details = false;
	
	//
	return true;
}


function WGR_hide_html_alert_auto_order_submit () {
	
	// ẩn html alert tự động
	jQuery('#WGR_html_alert').css({
		opacity: .01
	});
	
	jQuery('#target_eb_iframe').on('load', function () {
		jQuery('#WGR_html_alert').css({
			opacity: 1
		}).hide();
	});
	
}


//
(function () {
	
	// đồng bộ hóa v1 với v2
	if ( jQuery('#order_old_type').val() > 0
	&& order_details_arr_cart_product_list == ''
	&& typeof order_details_arr_cart_product_list_v1 == 'object' ) {
		
		//
		console.log( 'conver oder v1 to v2' );
		
		//
//		console.log( order_details_arr_cart_product_list_v1 );
//		console.log( typeof order_details_arr_cart_product_list_v1 );
		
		//
		order_details_arr_cart_product_list = [];
		order_details_arr_cart_customer_info = {};
		
		//
		var arr = order_details_arr_cart_product_list_v1;
		for ( var i = 0; i < arr.length; i++ ) {
//			console.log(arr[i]);
			
			//
			if ( typeof arr[i].__eb_hd_customer_info != 'undefined' ) {
				var arr2 = arr[i];
				
				for ( var x in arr2 ) {
					order_details_arr_cart_customer_info[x.replace('__eb_', '')] = arr2[x];
				}
				
				// thông tin khách hàng (dùng để kiểm soát)
				var custom_info = arr[i].__eb_hd_customer_info;
//				console.log(custom_info);
				custom_info = custom_info.replace( /\&quot\;/g, '"' );
//				console.log(custom_info);
				custom_info = eval( '[' + custom_info + ']' );
				custom_info = custom_info[0];
//				console.log(custom_info);
				
				//
				for ( var x in custom_info ) {
					order_details_arr_cart_customer_info[x] = custom_info[x];
				}
			}
			else {
				order_details_arr_cart_product_list.push( arr[i] );
			}
		}
		console.log( order_details_arr_cart_product_list );
		console.log( order_details_arr_cart_customer_info );
		
		// tự động cập nhật lại
		setTimeout(function () {
			___eb_admin_update_order_details();
			
			//
			if ( jQuery('#order_products').val().length > 100 && jQuery('#order_customer').val().length > 100 ) {
				console.log( 'auto update old order' );
				document.frm_invoice_details.submit();
				WGR_hide_html_alert_auto_order_submit();
			}
			else {
				console.log( 'auto update STOP, because content too short!' );
			}
		}, 1200);
		
	}
	
	//
	var arr = order_details_arr_cart_product_list,
		cus = order_details_arr_cart_customer_info;
	
//	console.log(arr);
	if ( typeof arr != 'object' ) {
		arr = jQuery.parseJSON( unescape( arr ) );
		
		// kiểm tra lại mà vẫn thế -> lỗi
		if ( typeof arr != "object" ) {
			console.log( "cart value not found" );
			return false;
		}
	}
	console.log(arr);
//	console.log(arr.length);
	
	//
	arr_global_js_order_details = arr.slice();
	
	//
	var total_current_order = 0;
	for ( var i = 0; i < arr.length; i++ ) {
//		console.log(arr[i]);
		
		//
		var str_edit_size_color = '';
		
		//
		if ( arr[i].color != '' ) {
			arr[i].color = jQuery.trim( arr[i].color );
			if ( arr[i].color.substr( 0, 1 ) == '-' ) {
//				arr[i].color = '- ' + arr[i].color;
				arr[i].color = jQuery.trim( arr[i].color.substr( 1 ) );
			}
//			arr[i].color = ' ' + arr[i].color;
			
			str_edit_size_color += '<span title="Đổi màu mới" data-id="' + arr[i].id + '" class="cur click-edit-order-color bluecolor"><strong> - Color: </strong>' + arr[i].color + ' <i class="fa fa-edit"></i></span>';
		}
		
		if ( arr[i].size != '' ) {
			arr[i].size = jQuery.trim( arr[i].size );
			if ( arr[i].size.substr( 0, 1 ) != '(' ) {
				arr[i].size = '(<strong>Size: </strong>' + arr[i].size + ')';
			}
//			arr[i].size = ' ' + arr[i].size;
			
			str_edit_size_color += ' <span title="Đổi size mới" data-id="' + arr[i].id + '" class="cur click-edit-order-size bluecolor">' + arr[i].size + ' <i class="fa fa-edit"></i></span>';
		}
		
		// bổ sung thêm slug để sau tìm kiếm cho nó tiện
		if ( typeof arr[i].slug == 'undefined' || arr[i].slug == '' ) {
			arr[i].slug = g_func.non_mark_seo( arr[i].name );
			arr[i].slug = arr[i].slug.replace( /\-/g, '' );
			
			// gán cho mảng tổng -> tí mới submit được
			arr_global_js_order_details[i].slug = arr[i].slug;
			
			// tự động submit sau 1 thời gian
			if ( auto_add_slug_if_not_exist == false ) {
				auto_add_slug_if_not_exist = true;
				
				//
				setTimeout(function () {
					___eb_admin_update_order_details();
					console.log( 'auto update slug' );
					document.frm_invoice_details.submit();
					WGR_hide_html_alert_auto_order_submit();
				}, 600);
			}
		}
		
		//
		var product_price = arr[i].price;
		// hiển thị giá theo size, color nếu có
		if ( typeof arr[i].child_price != 'undefined' && arr[i].child_price != '' && arr[i].child_price * 1 > 0 ) {
			product_price = arr[i].child_price;
		}
		
		//
		var total_line = product_price * arr[i].quan;
		total_current_order += total_line * 1;
		
		//
		jQuery('.order-title-sanpham').after('\
		<tr>\
			<td data-quan="' + arr[i].quan + '" data-price="' + product_price + '" data-id="' + arr[i].id + '" class="each-for-set-cart-value">' + arr[i].id + '</td>\
			<td><a href="' + web_link + '?p=' + arr[i].id + '" target="_blank">' + arr[i].name + ' <i class="fa fa-eye"></i></a>' + str_edit_size_color + '</td>\
			<td><span class="ebe-currency">' + g_func.money_format( product_price ) + '</span></td>\
			<td><input type="number" value="' + arr[i].quan + '" data-price="' + product_price + '" data-id="' + arr[i].id + '" class="change-update-cart-quantity ss" size="5" maxlength="10" /></td>\
			<td><span class="ebe-currency">' + g_func.money_format( total_line ) + '</span></td>\
		</tr>');
		
	}
	
	//
	jQuery('.change-update-cart-quantity').change(function() {
		
		var oi = jQuery(this).attr('data-id') || '',
			a = jQuery(this).val() || '';
		
		//
		if ( a < 0 || a == '' ) {
			a = 0;
		}
		
		//
		for ( var i = 0; i < arr_global_js_order_details.length; i++ ) {
			if ( typeof arr_global_js_order_details[i].id != 'undefined'
			&& arr_global_js_order_details[i].id == oi ) {
				arr_global_js_order_details[i].quan = a;
				
				//
				WGR_admin_tinh_so_luong_hoa_don();
				co_thay_doi_chua_duoc_luu = true;
				
				//
				WGR_auto_submit_order_details();
				
				//
				break;
			}
		}
		
	});
	
	// đổi màu
	jQuery('.click-edit-order-color').click(function () {
		var oi = jQuery(this).attr('data-id') || '',
			cache = jQuery(this).html() || '';
		
		// Lấy tên màu, bỏ các mã lằng nhằng đi
		jQuery('span, i, strong', this).remove();
		var v = jQuery(this).html() || '';
		v = jQuery.trim( v.replace( /\-/g, ' ' ).replace( 'Color: ', '' ) );
		
		//
		var a = prompt( 'Nhập màu mới:', v );
		
		// Nếu có màu mới -> nhập luôn
		if ( a != null && a != v ) {
			jQuery(this).html( '<strong> - Color: </strong>' + a + ' <i class="fa fa-edit"></i>' );
			
			// gán vào dữ liệu
			for ( var i = 0; i < arr_global_js_order_details.length; i++ ) {
				if ( typeof arr_global_js_order_details[i].id != 'undefined'
				&& arr_global_js_order_details[i].id == oi ) {
					arr_global_js_order_details[i].color = a;
					
					//
					___eb_admin_update_order_details();
					co_thay_doi_chua_duoc_luu = true;
					
					//
					WGR_auto_submit_order_details();
					
					//
					break;
				}
			}
		}
		// Hiển thị lại tên màu cũ
		else {
			jQuery(this).html( cache );
		}
	});
	
	// thay đổi size
	jQuery('.click-edit-order-size').click(function () {
		var oi = jQuery(this).attr('data-id') || '',
			cache = jQuery(this).html() || '';
		
		// Lấy tên màu, bỏ các mã lằng nhằng đi
		jQuery('span, i, strong', this).remove();
		var v = jQuery(this).html() || '';
		v = jQuery.trim( v.replace( /\(|\)/g, ' ' ).replace( 'Size: ', '' ) );
		
		//
		var a = prompt('Nhập size mới:', v);
		
		// Nếu có size mới -> nhập luôn
		if ( a != null && a != v ) {
			jQuery(this).html( ' (<strong>Size: </strong>' + a + ') <i class="fa fa-edit"></i>' );
			
			// gán vào dữ liệu
			for ( var i = 0; i < arr_global_js_order_details.length; i++ ) {
				if ( typeof arr_global_js_order_details[i].id != 'undefined'
				&& arr_global_js_order_details[i].id == oi ) {
					arr_global_js_order_details[i].size = a;
					
					//
					___eb_admin_update_order_details();
					co_thay_doi_chua_duoc_luu = true;
					
					//
					WGR_auto_submit_order_details();
					
					//
					break;
				}
			}
		}
		// Hiển thị lại tên màu cũ
		else {
			jQuery(this).html( cache );
		}
	});
	
	//
	jQuery('#hd_chietkhau, #hd_phivanchuyen').change(function () {
		WGR_admin_tinh_so_luong_hoa_don();
		co_thay_doi_chua_duoc_luu = true;
		
		//
		WGR_auto_submit_order_details();
	}).click(function () {
		jQuery(this).select();
	});
	
	
	
	
	//
//	console.log(cus);
	if ( typeof cus != 'object' ) {
		cus = jQuery.parseJSON( unescape( cus ) );
		
		// kiểm tra lại mà vẫn thế -> lỗi
		if ( typeof cus != "object" ) {
//			cus = {};
			cus = null;
		}
	}
	console.log(cus);
	
	//
	if ( cus == null || cus == {} ) {
		console.log( 'ERROR load data in JSON!!!!!!!!!!!!!!!!!!!' );
		return false;
	}
	
	//
	arr_global_js_order_customter = cus;
	
	//
	try {
		jQuery('#oi_hd_ten').val( cus['hd_ten'] );
		jQuery('#oi_hd_dienthoai').val( cus['hd_dienthoai'] );
		
		//
		jQuery('#oi_hd_diachi').val( cus['hd_diachi'] );
		jQuery('#oi_full_diachi').val( cus['hd_diachi'] );
		if ( typeof cus['hd_quanhuyen'] != 'undefined' ) {
			jQuery('#oi_hd_quanhuyen').val( cus['hd_quanhuyen'] );
			if ( cus['hd_quanhuyen'] != '' ) {
				dog('oi_full_diachi').value += ', ' + cus['hd_quanhuyen'];
			}
		}
		if ( typeof cus['hd_id_quanhuyen'] != 'undefined' ) jQuery('#oi_hd_id_quanhuyen').val( cus['hd_id_quanhuyen'] );
		if ( typeof cus['hd_tinhthanh'] != 'undefined' ) {
			jQuery('#oi_hd_tinhthanh').val( cus['hd_tinhthanh'] );
			if ( cus['hd_tinhthanh'] != '' ) {
				dog('oi_full_diachi').value += ', ' + cus['hd_tinhthanh'];
			}
		}
		if ( typeof cus['hd_id_tinhthanh'] != 'undefined' ) jQuery('#oi_hd_id_tinhthanh').val( cus['hd_id_tinhthanh'] );
		
		//
		jQuery('#oi_ghi_chu_cua_khach').html( cus['hd_ghichu'] );
		
		// Hiển thị ghi chú của admin nếu có
		if ( typeof cus['hd_admin_ghichu'] != 'undefined' ) {
			jQuery('#hd_admin_ghichu').val( cus['hd_admin_ghichu'] );
		}
		
		// một số tính năng khác
		if ( typeof cus['hd_chietkhau'] != 'undefined' && cus['hd_chietkhau'] != '' ) {
			jQuery('#hd_chietkhau').val( cus['hd_chietkhau'] );
		}
		// nếu có mã giảm giá -> hiển thị mã này
		if ( typeof cus['hd_discount_code'] != 'undefined' && cus['hd_discount_code'] != '' ) {
			jQuery('#oi_ma_giam_gia').html( cus['hd_discount_code'] );
			
			// nếu chiết khấu chưa có mà có mã giảm giá -> tìm và nhập lại
//			if ( jQuery('#hd_chietkhau').val() == '' ) {
				jQuery.ajax({
//					type: 'POST',
					type: 'GET',
					url: ajaxl_url( 'check_discount_code&code=' + cus['hd_discount_code'] + '&no_echo=1' ),
					data: ''
				}).done(function(msg) {
					if ( typeof msg == 'object' ) {
						console.log( msg );
						
						// nếu có lỗi -> nhập luôn là 0 để sau nó đỡ check lại
						if ( typeof msg['error'] != 'undefined' ) {
//							jQuery('#hd_chietkhau').val( 0 ).change();
							jQuery('#hd_chietkhau').val( 0 );
						}
						else if ( typeof msg['code'] != 'undefined' && msg['code'] * 1 < 0 ) {
							jQuery('#hd_chietkhau').val( 0 );
							
							jQuery('#oi_ma_giam_gia').append(' <span class="redcolor">' + msg['msg'] + '</span>');
						}
						else {
							var m2 = '';
							
							// giảm theo sản phẩm cụ thể
							if ( typeof msg['coupon_product_name'] != 'undefined' && msg['coupon_product_name'] != '' ) {
								m2 += ' Mã giảm giá áp dụng cho sản phẩm: <a href="' + msg['coupon_product_link'] + '" target="_blank">' + msg['coupon_product_name'] + '</a>';
								
								var so_tien_duoc_giam = 0;
								jQuery('.each-for-set-cart-value').each(function () {
									var cart_pid = jQuery(this).attr('data-id') || 0,
										gia_moi = jQuery(this).attr('data-price') || 0,
										cart_quan = jQuery(this).attr('data-quan') || 0;
									
									if ( cart_pid > 0 && cart_pid * 1 == msg['coupon_product'] * 1 ) {
										so_tien_duoc_giam = gia_moi * cart_quan;
									}
								});
								jQuery('#hd_chietkhau').val( so_tien_duoc_giam );
							}
							if ( typeof msg['coupon__product_name'] != 'undefined' && msg['coupon__product_name'] != '' ) {
								m2 += ' <span class="redcolor">Mã giảm giá KHÔNG áp dụng cho sản phẩm</span>: <a href="' + msg['coupon__product_link'] + '" target="_blank">' + msg['coupon__product_name'] + '</a>';
							}
							
							if ( typeof msg['coupon_category_name'] != 'undefined' && msg['coupon_category_name'] != '' ) {
								m2 += ' Mã giảm giá áp dụng cho chuyên mục: <a href="' + msg['coupon_category_link'] + '" target="_blank">' + msg['coupon_category_name'] + '</a>';
							}
							if ( typeof msg['coupon__category_name'] != 'undefined' && msg['coupon__category_name'] != '' ) {
								m2 += ' <span class="redcolor">Mã giảm giá KHÔNG áp dụng cho chuyên mục</span>: <a href="' + msg['coupon__category_link'] + '" target="_blank">' + msg['coupon__category_name'] + '</a>';
							}
							if ( m2 != '' ) {
								$('#oi_ma_giam_gia').append(' - Lưu ý: <span class="small greencolor">' + m2 + '</span>');
							}
							
							//
							var current_discount = jQuery('#hd_chietkhau').val() || '';
							
							if ( current_discount != '' && current_discount * 1 <= 0 ) {
								console.log( '%c Mã giảm giá đã bị hủy', 'color: red;' );
							}
							// ưu tiên giảm theo giá tiền
							else if ( typeof msg['coupon_giagiam'] != 'undefined' && msg['coupon_giagiam'] != '' && msg['coupon_giagiam'].replace(/\,/g, '') * 1 > 0 ) {
//								jQuery('#hd_chietkhau').val( msg['coupon_giagiam'].replace(/\,/g, '') ).change();
								jQuery('#hd_chietkhau').val( msg['coupon_giagiam'].replace(/\,/g, '') );
							}
							//
							else if ( typeof msg['coupon_phantramgiam'] != 'undefined' && msg['coupon_phantramgiam'] != '' && msg['coupon_phantramgiam'] * 1 > 0 ) {
//								jQuery('#hd_chietkhau').val( msg['coupon_phantramgiam'] + '%' ).change();
								jQuery('#hd_chietkhau').val( msg['coupon_phantramgiam'] + '%' );
							}
						}
					}
					else {
						console.log( '%c Lỗi cú pháp! vui lòng báo cho kỹ thuật viên. Xin cảm ơn', 'color: red;' );
					}
				});
//			}
		}
		if ( typeof cus['hd_phivanchuyen'] != 'undefined' && cus['hd_phivanchuyen'] != '' ) {
			jQuery('#hd_phivanchuyen').val( cus['hd_phivanchuyen'] );
		}
	} catch (e) {
		console.log( WGR_show_try_catch_err( e ) );
	}
	
	// bắt đầu tính tổng tiền sau khi nạp các giá trị khác thành công
	WGR_admin_tinh_tong_hoa_don( total_current_order );
	
	
	// tự động cập nhật key tìm kiếm thông tin khách hàng
	if ( typeof arr_global_js_order_customter['hd_key'] == 'undefined' ) {
		
		// key tìm kiếm đơn hàng
		arr_global_js_order_customter['hd_key'] = g_func.non_mark_seo( arr_global_js_order_customter['hd_ten'] + arr_global_js_order_customter['hd_dienthoai'] );
		arr_global_js_order_customter['hd_key'] = arr_global_js_order_customter['hd_key'].replace( /\-/g, '' );
		
		// tự động submit sau 1 thời gian
		if ( auto_add_slug_if_not_exist == false ) {
			auto_add_slug_if_not_exist = true;
			
			//
			setTimeout(function () {
				___eb_admin_update_order_details();
				console.log( 'auto update key' );
				document.frm_invoice_details.submit();
				WGR_hide_html_alert_auto_order_submit();
			}, 600);
		}
		
	}
	
	
	
	//
	var arr_dich = {
		'hd_url' : 'URL',
		'hd_referrer' : 'Nguồn',
		'hd_utm_campaign' : 'Chiến dịch',
		'hd_utm_medium' : 'utm_medium',
		'hd_utm_source' : 'Nguồn (chiến dịch)',
		'hd_title' : 'Tiêu đề',
		'hd_timezone' : 'Múi giờ',
		'hd_lang' : 'Ngôn ngữ',
		'hd_usertime' : 'Giờ máy trạm',
		'hd_window' : 'Kích thước trình duyệt',
		'hd_document' : 'Kích thước văn bản',
		'hd_screen' : 'Màn hình/ Hệ điều hành',
		'hd_agent' : 'Trình duyệt',
	};
	
	for ( var x in arr_dich ) {
		if ( typeof cus[x] != 'undefined' ) {
			var a = cus[x];
			
			//
			if ( x == 'hd_usertime' ) {
//				if ( typeof cus[x] == 'string' ) {
				if ( cus[x].toString().split('1970').length > 1 ) {
					console.log( typeof cus[x] );
					cus[x] = jQuery('.order-time-server').attr('data-time') || 0;
				}
				
				a = _date( 'd-m-Y H:i', cus[x] );
			}
			// tạo link
			else if ( ( x == 'hd_url' || x == 'hd_referrer' ) && a != '' ) {
				a = '<a href="' + a + '" target="_blank" rel="nofollow">' + a + '</a>';
			}
			// tạo khoảng cách cho phần agent
			else if ( x == 'hd_agent' ) {
				a = a.replace( /\//g, '/ ' ).replace( /\;/g, '; ' );
			}
			
			//
			jQuery('.dulieu-thamkhao').append( '\
			<tr>\
				<td class="t">' + arr_dich[x] + '</td>\
				<td class="i">' + a + '</td>\
			</tr>' );
		}
	}
	
})();




// danh sách các đơn đã bị báo xấu của tài khoản này
var arr_user_blacklist = [],
	da_load_danh_sach_bao_xau = false,
	// danh sách các đơn khác cùng tài khoản, trừ đơn báo xấu
	arr_list_orther_order = [];
setTimeout(function () {
	
	// đánh dấu tab cùng trạng thái với dơn hiện tại
	jQuery('.eb-order-filter-tab li a').removeClass('selected');
	jQuery('.eb-order-filter-tab li[data-tab="' + document.frm_invoice_details.t_trangthai.value + '"] a').addClass('selected');
	
	
	// tìm các đơn hàng thuộc tiện báo xấu cùng thông tin với đơn hàng này để báo lại khách
	var dt = jQuery('#oi_hd_dienthoai').val() || '',
		e = jQuery('#get-order-email').html() || '',
		uri = '';
//	console.log( dt );
//	console.log( e );
	
	// điện thoại chỉ lấy 9 ký tự sau cùng -> bỏ qua phần số 0 hoặc +84 nếu có
	if ( dt != '' ) {
		dt = g_func.non_mark_seo( dt.split('-')[0].split('/')[0] );
//		console.log( dt );
		dt = g_func.number_only( dt );
		dt = dt.toString().replace(/\-/g, '').substr( dt.toString().length - 9 );
		
		uri += '&dt=' + dt;
	}
	
	// mail thì không lấy các email cùng với tên miền hiện tại -> vì lấy theo số đt là đủ rồi
	// kiểm tra quyền điều khiển của người đăng đơn, nếu không phải admin thì bỏ qua email
	if ( WGR_check_option_on ( order_user_can ) == false && e != '' && e.split( '@' ).length == 2 && e.split( document.domain ).length == 1 ) {
		uri += '&e=' + e;
	}
	console.log( 'Check blacklist by: ' + uri );
	
	//
	if ( uri != '' ) {
		ajaxl('get_customer_blacklist&no_echo=1&order_id=' + order_id + uri, 'oi_customer_blacklist', 1, function () {
			setTimeout(function () {
//				console.log( arr_user_blacklist );
				
				//
				if ( arr_user_blacklist.length > 0 ) {
					if ( arr_user_blacklist.length == 1 && typeof arr_user_blacklist[0].error != 'undefined' ) {
						console.log( arr_user_blacklist[0].error );
					}
					else {
						// hiển thị thông báo báo xấu
						jQuery('#open_list_bao_xau strong').html( arr_user_blacklist.length );
						
						// ẩn đi để lúc nữa còn dùng lệnh toggle
						jQuery('#order_show_bao_xau').hide();
						
						//
						jQuery('#open_list_bao_xau').fadeIn().on('click', function () {
							
							//
							jQuery('#open_list_bao_xau').fadeOut();
//							jQuery('#order_show_bao_xau').fadeIn();
							jQuery('#order_show_bao_xau').toggle('slow');
							
							//
							if ( da_load_danh_sach_bao_xau == true ) {
								return false;
							}
							da_load_danh_sach_bao_xau = true;
							
							//
							var str = '';
							for ( var i = 0; i < arr_user_blacklist.length; i++ ) {
//								console.log( admin_link + 'admin.php?page=eb-order&id=' + arr_user_blacklist[i].order_id );
								
								
								//
	//							console.log( arr_user_blacklist[i].order_customer );
								try {
									var custom_info = jQuery.parseJSON( unescape( arr_user_blacklist[i].order_customer ) );
									console.log( custom_info );
									
									//
									if ( custom_info.hd_ten == '' ) {
										custom_info.hd_ten = custom_info.hd_dienthoai;
									}
									
									//
									str += '<li>\
										<a href="' + admin_link + 'admin.php?page=eb-order&id=' + arr_user_blacklist[i].order_id + '" target="_blank" class="bold">' + custom_info.hd_ten + '</a>\
										<ul class="sub-menu">\
											<li>Ghi chú: <span class="orgcolor">' + custom_info.hd_admin_ghichu + '</span></li>\
											<li>Điện thoại: ' + custom_info.hd_dienthoai + '</li>\
											<li>Email: ' + custom_info.hd_email + '</li>\
											<li>Địa chỉ: ' + custom_info.hd_diachi + '</li>\
											<li>Ngày gửi: ' + _date( lang_date_time_format, custom_info.hd_usertime ) + '</li>\
											<li class="d-none">Thiết bị: ' + custom_info.hd_agent + '</li>\
										</ul>\
									</li>';
								} catch ( e ) {
									console.log( WGR_show_try_catch_err( e ) );
								}
							}
							
							//
							jQuery('#order_show_bao_xau ul').html( str );
							
						});
						
						//
						jQuery('#close_list_bao_xau').on('click', function () {
							jQuery('#open_list_bao_xau').fadeIn();
//							jQuery('#order_show_bao_xau').fadeOut();
							jQuery('#order_show_bao_xau').toggle('slow');
						});
					}
				}
				
				//
//				console.log( arr_list_orther_order );
				if ( arr_list_orther_order.length > 0 ) {
					var str = '';
					for ( var i = 0; i < arr_list_orther_order.length; i++ ) {
						try {
							var custom_info = jQuery.parseJSON( unescape( arr_list_orther_order[i].order_customer ) );
//							console.log( custom_info );
							
							//
							if ( custom_info.hd_ten == '' ) {
								custom_info.hd_ten = custom_info.hd_dienthoai;
							}
							
							//
							str += '\
							<tr class="hd_status' + arr_list_orther_order[i].order_status + '">\
								<td><a href="' + admin_link + 'admin.php?page=eb-order&id=' + arr_list_orther_order[i].order_id + '" target="_blank" class="bold">' + arr_list_orther_order[i].order_id + '</a></td>\
								<td><a href="' + admin_link + 'admin.php?page=eb-order&id=' + arr_list_orther_order[i].order_id + '" target="_blank" class="bold">' + custom_info.hd_ten + '</a></td>\
								<td>' + custom_info.hd_dienthoai + '</td>\
								<td>' + custom_info.hd_diachi + '</td>\
								<td>' + _date( lang_date_time_format, custom_info.hd_usertime ) + '</td>\
								<td class="orgcolor">' + custom_info.hd_admin_ghichu + '</li>\
							</tr>';
						} catch ( e ) {
							console.log( WGR_show_try_catch_err( e ) );
						}
					}
					
					//
					jQuery('#order_show_too_order table').append( str );
					jQuery('#order_show_too_order').toggle('slow');
				}
			}, 600);
			
			//
//			var msg = jQuery('#oi_customer_blacklist').html();
//			console.log( msg );
//		}, {
//			dataType: "json"
		});
	}
	else {
		console.log( 'Phone and email is NULL!' );
	}
	
}, 600);




// nếu hóa đơn đang được xác nhận -> ẩn đơn đi
(function () {
	if ( jQuery('.dang-xac-nhan').length > 0 ) {
		var a = jQuery('.dang-xac-nhan a').attr('data-id') || '';
		
		// đưa ra thông báo nếu người xác nhận khác với người đang xem
		if ( a != '' && a * 1 != mtv_id ) {
			a_lert( jQuery('.dang-xac-nhan').html() );
			
			jQuery('form[name="frm_invoice_details"]').css({
				opacity: .1
			}).animate({
				opacity: 1
			}, 15000);
		}
		// không thì ẩn nó đi thôi
		else {
			jQuery('.dang-xac-nhan').hide();
		}
	}
})();




//
jQuery('.show-if-js-enable').show();
//dog('eb_cart_submit').disabled = false;

//dog('eb_cart_print').disabled = false;
jQuery('#eb_cart_print').click(function () {
	window.open( web_link + 'billing_print?order_id=' + order_id, '_blank' );
});

//dog('eb_vandon_print').disabled = false;
jQuery('#eb_vandon_print').click(function () {
	window.open( web_link + 'billing_print?order_id=' + order_id + '&f=print_van_don', '_blank' );
});




// tổng số LI đang được hiển thị
var tong_so_li_quan_huyen = 0;

// khi người dùng gõ tìm địa chỉ -> dưa ra gợi ý luôn
jQuery('#oi_hd_quanhuyen').focus(function () {
	khong_submit_khi_bam_enter = true;
	
	// chưa có danh sách tỉnh thành -> load
	if ( dog('ui_hd_quanhuyen') == null ) {
		// nạp danh sách quận huyện
		(function () {
			var str = '',
				a = {},
				k = '',
				key = '';
			for ( var x in arr_vn_distics ) {
				a = arr_vn_distics[x].districts;
				
				//
				k = g_func.non_mark_seo(arr_vn_distics[x].name);
				k = k.replace(/[^0-9a-zA-Z]/g, '');
				
				//
				for ( var x2 in a ) {
					key = g_func.non_mark_seo(a[x2]);
					key = key.replace(/[^0-9a-zA-Z]/g, '');
					
					//
					str += '<li data-key="' + k + key + '" data-city="' + x + '" data-city-name="' + arr_vn_distics[x].name + '" data-dist="' + x2 + '" data-dist-name="' + a[x2] + '" class="d-none">' + a[x2] + ', ' + arr_vn_distics[x].name + '</li>';
				}
			}
			
			//
			jQuery('#oi_hd_quanhuyen').after('<div id="ui_hd_quanhuyen"><ul>' + str + '</ul></div>');
		})();
		
		//
		jQuery('#ui_hd_quanhuyen li').click(function() {
			// xóa hết orgcolor đi
			jQuery('#ui_hd_quanhuyen li.orgcolor').removeClass('orgcolor');
			// add cái hiện tại
			jQuery(this).addClass('orgcolor');
			// nạp dữ liệu
			WGR_order_details_add_city();
		});
	}
	
	//
	jQuery('#ui_hd_quanhuyen').show();
	
	//
	setTimeout(function () {
		if ( jQuery(this).val() != '' ) {
			jQuery(this).keyup();
		}
	}, 200);
}).blur(function () {
	khong_submit_khi_bam_enter = false;
	
	//
	setTimeout(function () {
		jQuery('#ui_hd_quanhuyen').hide();
	}, 200);
//}).off('keyup').keyup(function(e) {
}).keyup(function(e) {
//	console.log(e.keyCode);
	
	// khi bấm nút lên
	if (e.keyCode == 38) {
		if ( tong_so_li_quan_huyen == 0 ) {
			tong_so_li_quan_huyen = jQuery('#ui_hd_quanhuyen li.d-block').length;
		}
//		console.log('tong_so_li_quan_huyen: ' + tong_so_li_quan_huyen)
		
		// nếu có nhiều hơn 1 d-block
		if ( tong_so_li_quan_huyen > 1 ) {
			// nếu chưa có select nào -> add cái cuối cùng
			if ( jQuery('#ui_hd_quanhuyen li.orgcolor').length == 0 ) {
				jQuery('#ui_hd_quanhuyen li.d-block:last').addClass('orgcolor');
			}
			// nếu đã có -> kiểm tra LI trước đó
			else {
				tong_so_li_quan_huyen--;
				
				//
				var i = 1;
				jQuery('#ui_hd_quanhuyen li.d-block').each(function() {
//					console.log('i: ' + i)
					if ( i == tong_so_li_quan_huyen ) {
						// xóa hết orgcolor đi
						jQuery('#ui_hd_quanhuyen li.orgcolor').removeClass('orgcolor');
						// add cái hiện tại
						jQuery(this).addClass('orgcolor');
						// thoát
						return false;
					}
					i++;
				});
			}
		}
		// chỉ còn 1 d-block
		else if ( tong_so_li_quan_huyen == 1 ) {
			jQuery('#ui_hd_quanhuyen li.d-block').addClass('orgcolor');
			
			// nếu có nhiều hơn 1 orgcolor (hệ quả từ việc each ở trên)
			if ( jQuery('#ui_hd_quanhuyen li.orgcolor').length != 1 ) {
				// xóa hết đi
				jQuery('#ui_hd_quanhuyen li.orgcolor').removeClass('orgcolor');
				// và lấy cái cuối cùng
				jQuery('#ui_hd_quanhuyen li.d-block:last').addClass('orgcolor');
				// tính tổng li lại
				tong_so_li_quan_huyen = jQuery('#ui_hd_quanhuyen li.d-block').length;
			}
		}
		return false;
	}
	// khi bấm nút xuống
	else if (e.keyCode == 40) {
		// nếu có nhiều hơn 1 orgcolor
		if ( jQuery('#ui_hd_quanhuyen li.orgcolor').length != 1 ) {
			// xóa hết đi
			jQuery('#ui_hd_quanhuyen li.orgcolor').removeClass('orgcolor');
			// và lấy cái đầu tiên
			jQuery('#ui_hd_quanhuyen li.d-block:first').addClass('orgcolor');
		}
		else {
			var i = false,
				j = false;
			jQuery('#ui_hd_quanhuyen li.d-block').each(function() {
				if ( i == true ) {
					// xóa hết orgcolor đi
					jQuery('#ui_hd_quanhuyen li.orgcolor').removeClass('orgcolor');
					// add cái hiện tại
					jQuery(this).addClass('orgcolor');
					//
					j = true;
					// thoát
					return false;
				}
				else {
					var a = jQuery(this).attr('class') || '';
					
					if ( a != '' && a.split('orgcolor').length > 1 ) {
						i = true;
					}
				}
			});
			
			// xong việc mà i vẫn false
			if ( j == false ) {
				// xóa hết đi
				jQuery('#ui_hd_quanhuyen li.orgcolor').removeClass('orgcolor');
				// và lấy cái đầu tiên
				jQuery('#ui_hd_quanhuyen li.d-block:first').addClass('orgcolor');
			}
		}
		return false;
	}
	// khi bấm enter
	else if (e.keyCode == 13) {
		// nạp dữ liệu
		WGR_order_details_add_city();
		//
		return false;
	}
	
	//
	tong_so_li_quan_huyen = 0;
	
	//
	var key = jQuery(this).val() || '';
	if (key != '') {
		key = g_func.non_mark_seo(key);
		key = key.replace(/[^0-9a-zA-Z]/g, '');
	}
//	console.log(key);
	
	//
	if (key.length > 2) {
		jQuery('#ui_hd_quanhuyen li')
		.addClass('d-none')
		.removeClass('orgcolor')
		.removeClass('d-block')
		.each(function() {
			var a = jQuery(this).attr('data-key') || '';
			if (a != '' && a.split(key).length > 1) {
				jQuery(this)
				.removeClass('d-none')
				.addClass('d-block');
			}
		});
	}
	else {
		jQuery('#ui_hd_quanhuyen li')
		.addClass('d-none')
		.removeClass('orgcolor')
		.removeClass('d-block');
	}
});

//
function WGR_order_details_add_city () {
	jQuery('#oi_hd_quanhuyen').val( jQuery('#ui_hd_quanhuyen li.orgcolor').attr('data-dist-name') || '' );
	jQuery('#oi_hd_id_quanhuyen').val( jQuery('#ui_hd_quanhuyen li.orgcolor').attr('data-dist') || '' );
	
	jQuery('#oi_hd_tinhthanh').val( jQuery('#ui_hd_quanhuyen li.orgcolor').attr('data-city-name') || '' );
	jQuery('#oi_hd_id_tinhthanh').val( jQuery('#ui_hd_quanhuyen li.orgcolor').attr('data-city') || '' );
	
	//
	/*
	setTimeout(function () {
		document.frm_invoice_details.submit();
		WGR_hide_html_alert_auto_order_submit();
	}, 600);
	*/
}



// tự add tỉnh thành nếu chưa có
function WGR_auto_add_city_with_address () {
	// chỉ kiểm tra khi dữ liệu kia chưa có
	if ( jQuery.trim( jQuery('#oi_hd_quanhuyen').val() ) == '' && jQuery.trim( jQuery('#oi_hd_tinhthanh').val() ) == '' ) {
		var a = jQuery('#oi_hd_diachi').val();
		
		//
		a = a.split(',');
		
		// phải có từ 3 cụm địa chỉ trở lên
		if ( a.length > 2 ) {
			jQuery('#oi_hd_quanhuyen').focus().val( jQuery.trim( a[ a.length - 2 ] ) ).keyup();
			
			// và chỉ 1 địa chỉ được tìm thấy thì mới tự add
			if ( jQuery('#ui_hd_quanhuyen li.d-block').length == 1 ) {
				jQuery('#ui_hd_quanhuyen li.d-block').click();
				
				// xóa 2 dòng địa chỉ cuối đi
				a[ a.length - 1 ] = '';
				a[ a.length - 2 ] = '';
				/*
				try {
					delete a[ a.length - 1 ];
					delete a[ a.length - 2 ];
				} catch (e) {
					console.log( WGR_show_try_catch_err( e ) );
				}
				*/
				a = a.join( ',' ).replace( ',,', '' );
//				console.log( a );
				
				// backup lại địa chỉ mà khách đã ghi, cho vào phần ghi chú của admin
				if ( jQuery.trim( jQuery('#hd_admin_ghichu').val() ) == '' ) {
					jQuery('#hd_admin_ghichu').val( 'Địa chỉ: ' + jQuery('#oi_hd_diachi').val() );
				}
				else {
					jQuery('#hd_admin_ghichu').val(
						jQuery.trim( jQuery('#hd_admin_ghichu').val() )
						+ "\n"
						+ 'Địa chỉ: ' + jQuery('#oi_hd_diachi').val()
					);
				}
				
				// thiết lập địa chỉ mới
				jQuery('#oi_hd_diachi').val( a );
			}
			/*
			var s = g_func.non_mark_seo( a[ a.length - 2 ] );
			s = s.replace( /\-/g, '' );
			console.log( s );
			*/
		}
	}
}

//
WGR_auto_add_city_with_address();

//
jQuery('#oi_hd_diachi').change(function () {
	WGR_auto_add_city_with_address();
});



//
/*
setTimeout(function () {
	console.log( co_thay_doi_chua_duoc_luu );
}, 600);
window.onbeforeunload = function(e) {
	if ( co_thay_doi_chua_duoc_luu == true ) {
		return 'Các thay đổi của bạn sẽ không được lưu lại!';
	}
	return true;
};
*/




