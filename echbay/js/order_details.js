


var arr_global_js_order_details = [],
	arr_global_js_order_customter = {},
	auto_add_slug_if_not_exist = false;

function ___eb_admin_update_order_details () {
	
	//
	console.log( arr_global_js_order_details );
	$('#order_products').val( escape( JSON.stringify( arr_global_js_order_details ) ) );
	
	//
//	console.log( arr_global_js_order_customter );
	arr_global_js_order_customter['hd_ten'] = $('#oi_hd_ten').val() || '';
	arr_global_js_order_customter['hd_dienthoai'] = $('#oi_hd_dienthoai').val() || '';
	arr_global_js_order_customter['hd_diachi'] = $('#oi_hd_diachi').val() || '';
	arr_global_js_order_customter['hd_admin_ghichu'] = $('#hd_admin_ghichu').val() || '';
	
	// tạo key để sau này tìm kiếm đơn hàng cho tiện
	arr_global_js_order_customter['hd_key'] = g_func.non_mark_seo( arr_global_js_order_customter['hd_ten'] + arr_global_js_order_customter['hd_dienthoai'] );
	arr_global_js_order_customter['hd_key'] = arr_global_js_order_customter['hd_key'].replace( /\-/g, '' );
	
	//
	console.log( arr_global_js_order_customter );
	$('#order_customer').val( escape( JSON.stringify( arr_global_js_order_customter ) ) );
	
	return true;
}


function WGR_hide_html_alert_auto_order_submit () {
	
	// ẩn html alert tự động
	$('#WGR_html_alert').css({
		opacity: .01
	});
	
	$('#target_eb_iframe').on('load', function () {
		$('#WGR_html_alert').css({
			opacity: 1
		}).hide();
	});
	
}


//
(function () {
	
	// đồng bộ hóa v1 với v2
	if ( $('#order_old_type').val() > 0
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
			if ( $('#order_products').val().length > 100 && $('#order_customer').val().length > 100 ) {
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
		arr = $.parseJSON( unescape( arr ) );
		
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
	for ( var i = 0; i < arr.length; i++ ) {
//		console.log(arr[i]);
		
		//
		if ( arr[i].color != '' ) {
			arr[i].color = $.trim( arr[i].color );
			if ( arr[i].color.substr( 0, 1 ) != '-' ) {
				arr[i].color = '- ' + arr[i].color;
			}
			
			arr[i].color = ' ' + arr[i].color;
		}
		
		if ( arr[i].size != '' ) {
			arr[i].size = $.trim( arr[i].size );
			if ( arr[i].size.substr( 0, 1 ) != '(' ) {
				arr[i].size = '(Size: ' + arr[i].size + ')';
			}
			
			arr[i].size = ' ' + arr[i].size;
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
		if ( arr[i].child_price != '' ) {
			product_price = arr[i].child_price;
		}
		
		//
		$('.order-danhsach-sanpham').append('\
		<tr>\
			<td>' + arr[i].id + '</td>\
			<td><a href="' + web_link + '?p=' + arr[i].id + '" target="_blank">' + arr[i].name + '</a>' + arr[i].color + arr[i].size + '</td>\
			<td>' + g_func.money_format( product_price ) + '</td>\
			<td><input type="number" value="' + arr[i].quan + '" data-id="' + arr[i].id + '" class="change-update-cart-quantity s" size="5" maxlength="10" /></td>\
			<td>' + g_func.money_format( product_price * arr[i].quan ) + '</td>\
		</tr>');
		
	}
	
	//
	$('.change-update-cart-quantity').change(function() {
		
		var oi = $(this).attr('data-id') || '',
			a = $(this).val();
		
		//
		for ( var i = 0; i < arr_global_js_order_details.length; i++ ) {
			if ( typeof arr_global_js_order_details[i].id != 'undefined'
			&& arr_global_js_order_details[i].id == oi ) {
				arr_global_js_order_details[i].quan = a;
			}
		}
		
		//
		___eb_admin_update_order_details();
		
	});
	
	
	
	
	//
//	console.log(cus);
	if ( typeof cus != 'object' ) {
		cus = $.parseJSON( unescape( cus ) );
		
		// kiểm tra lại mà vẫn thế -> lỗi
		if ( typeof cus != "object" ) {
			cus = {};
		}
	}
	console.log(cus);
	
	//
	arr_global_js_order_customter = cus;
	
	//
	$('#oi_hd_ten').val( cus['hd_ten'] );
	$('#oi_hd_dienthoai').val( cus['hd_dienthoai'] );
	$('#oi_hd_diachi').val( cus['hd_diachi'] );
	$('#oi_ghi_chu_cua_khach').html( cus['hd_ghichu'] );
	
	// Hiển thị ghi chú của admin nếu có
	if ( typeof cus['hd_admin_ghichu'] != 'undefined' ) {
		$('#hd_admin_ghichu').val( cus['hd_admin_ghichu'] );
	}
	
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
					cus[x] = $('.order-time-server').attr('data-time') || 0;
				}
				
				a = _date( 'd-m-Y H:i', cus[x] );
			}
			else if ( ( x == 'hd_url' || x == 'hd_referrer' ) && a != '' ) {
				a = '<a href="' + a + '" target="_blank" rel="nofollow">' + a + '</a>';
			}
			
			//
			$('.dulieu-thamkhao').append( '\
			<tr>\
				<td class="t">' + arr_dich[x] + '</td>\
				<td class="i">' + a + '</td>\
			</tr>' );
		}
	}
	
})();




//
var arr_user_blacklist = [],
	da_load_danh_sach_bao_xau = false;
setTimeout(function () {
	
	// đánh dấu tab cùng trạng thái với dơn hiện tại
	$('.eb-order-filter-tab li a').removeClass('selected');
	$('.eb-order-filter-tab li[data-tab="' + document.frm_invoice_details.t_trangthai.value + '"] a').addClass('selected');
	
	
	// tìm các đơn hàng thuộc tiện báo xấu cùng thông tin với đơn hàng này để báo lại khách
	var dt = $('#oi_hd_dienthoai').val() || '',
		e = $('#get-order-email').html() || '',
		uri = '';
//	console.log( dt );
//	console.log( e );
	
	// điện thoại chỉ lấy 9 ký tự sau cùng -> bỏ qua phần số 0 hoặc +84 nếu có
	if ( dt != '' ) {
		dt = g_func.non_mark_seo( dt );
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
						$('#open_list_bao_xau strong').html( arr_user_blacklist.length );
						$('#open_list_bao_xau').fadeIn().on('click', function () {
							
							//
							$('#open_list_bao_xau').fadeOut();
							$('#order_show_bao_xau').fadeIn();
							
							//
							if ( da_load_danh_sach_bao_xau == true ) {
								return false;
							}
							da_load_danh_sach_bao_xau = true;
							
							//
							var str = '';
							for ( var i = 0; i < arr_user_blacklist.length; i++ ) {
								console.log( admin_link + 'admin.php?page=eb-order&id=' + arr_user_blacklist[i].order_id );
								
								
								//
	//							console.log( arr_user_blacklist[i].order_customer );
								try {
									var custom_info = $.parseJSON( unescape( arr_user_blacklist[i].order_customer ) );
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
											<li>Thời gian: ' + custom_info.hd_usertime + '</li>\
											<li class="d-none">Thiết bị: ' + custom_info.hd_agent + '</li>\
										</ul>\
									</li>';
								} catch ( e ) {
									console.log( WGR_show_try_catch_err( e ) );
								}
							}
							
							//
							$('#order_show_bao_xau ul').html( str );
							
						});
						
						//
						$('#close_list_bao_xau').on('click', function () {
							$('#open_list_bao_xau').fadeIn();
							$('#order_show_bao_xau').fadeOut();
						});
					}
				}
			}, 600);
			
			//
//			var msg = $('#oi_customer_blacklist').html();
//			console.log( msg );
//		}, {
//			dataType: "json"
		});
	}
	else {
		console.log( 'Phone and email is NULL!' );
	}
	
}, 600);




//
$('.show-if-js-enable').show();
//dog('eb_cart_submit').disabled = false;

//dog('eb_cart_print').disabled = false;
$('#eb_cart_print').click(function () {
	window.open( web_link + 'billing_print?order_id=' + order_id, '_blank' );
});

//dog('eb_vandon_print').disabled = false;
$('#eb_vandon_print').click(function () {
	window.open( web_link + 'billing_print?order_id=' + order_id + '&f=print_van_don', '_blank' );
});



