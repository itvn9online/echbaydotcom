




//
function ___eb_add_conver_string_cart_to_arr_cart ( arr ) {
	
	console.log( arr );
	
	if ( typeof arr != "object" ) {
		try {
			arr = jQuery.parseJSON( unescape( arr ) );
		} catch (e) {
			console.log( WGR_show_try_catch_err( e ) );
			arr = '';
		}
		
		// kiểm tra lại mà vẫn thế -> lỗi
		if ( typeof arr != "object" ) {
			console.log( "cart value not found" );
			return false;
		}
		console.log( arr );
		
		//
		current_hd_object = arr;
	}
	
	return arr;
	
}

function ___eb_add_convertsion_gg_fb ( hd_id, arr, max_for ) {
	
	//
	if ( typeof hd_id != "number" ) {
		console.log( "order ID not found" );
		return false;
	}
	
	// kiểm tra xem GA với FB đã load được chưa, chờ tụi nó 1 lúc, nếu không thể load được thì đành chạy tiếp
//	if ( typeof ga == 'undefined' || typeof fbq == 'undefined' ) {
	if ( typeof ga == 'undefined' ) {
		if ( typeof max_for == "undefined" ) {
			max_for = 20;
		}
//		console.log( 'Max for: ' + max_for );
		
		//
		if ( max_for > 0 ) {
			setTimeout(function () {
				___eb_add_convertsion_gg_fb ( hd_id, arr, max_for - 1 );
			}, 500);
			
			console.log( 'Re-load tranking (' + max_for + ')...' );
			
			return false;
		}
		
		//
		console.log( 'Max for: ' + max_for );
	}
	
	// nếu giá trị tuyền vào không phải là mảng
	arr = ___eb_add_conver_string_cart_to_arr_cart( arr );
	if ( arr == false ) {
		return false;
	}
	
	//
//	current_hd_id = hd_id;
//	current_hd_object = arr;
	
	// Set tracker currency to Euros.
//	ga('set', 'currencyCode', 'VND');
	
	//
	var tong_tien = 0,
		arr_ids = [];
	for ( var i = 0; i < arr.length; i++ ) {
//		if ( typeof arr[i].__eb_hd_customer_info == 'undefined' ) {
			arr_ids.push( arr[i].id );
			tong_tien -= ( 0 - arr[i].price );
			
			//
			if ( typeof ga != 'undefined' ) {
				var ga_add_product = {
					'id': 'p' + arr[i].id,
					'name': arr[i].name,
					'category': 'Echbay category',
					'brand': 'Echbay',
					'variant': 'red',
					'price': arr[i].price,
					'quantity': arr[i].quan
				};
				ga('ec:addProduct', ga_add_product);
				
				//
				console.log( 'ec addProduct:' );
				console.log( ga_add_product );
			}
//		}
	}
	
	// fb track -> by products
	_global_js_eb.fb_track( "Purchase", {
		content_ids: arr_ids,
		content_type: "product",
//		value: arr[i].price,
		value: tong_tien,
//		currency: "VND"
		currency: cf_current_sd_price
	});
	
	// google analytics track -> by order
	if ( typeof ga != 'undefined' ) {
		var ga_set_action = {
//			"id": arr[0].id,
			"id": hd_id,
			"affiliation": window.location.href.split('//')[1].split('/')[0].replace('www.', ''),
//			"revenue": arr[0].price,
			"revenue": tong_tien,
			"tax": "0",
			"shipping": "0",
			"coupon": ""
		};
		ga("ec:setAction", "purchase", ga_set_action);
		
		//
		console.log( 'ec setAction:' );
		console.log( ga_set_action );
	}
	
}


function WGR_show_try_catch_err ( e ) {
	return 'name: ' + e.name + '; line: ' + (e.lineNumber || e.line) + '; script: ' + (e.fileName || e.sourceURL || e.script) + '; stack: ' + (e.stackTrace || e.stack) + '; message: ' + e.message;
}



