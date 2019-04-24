/*
* Các function dùng chung cho phần danh sách bài viết sẽ được cho vào đây
* Sau đó các file js riêng của từng theme sẽ kế thừa và sử dụng các chức năng bằng cách gọi function
*/


// Sắp xếp sản phẩm theo ý muốn người dùng
(function () {
	if ( jQuery('#oi_order_by').length == 0 ) {
		return false;
	}
	
	
	//
	var arr = {
		time : lang_order_by,
//		time : 'Mới nhất',
		view : lang_order_view,
		price_down : lang_order_price_down,
		price_up : lang_order_price_up,
		az : lang_order_az,
		za : lang_order_za
	};
	
	//
	var str = '',
		sl = '';
	for (var x in arr) {
		sl = '';
		if ( x == current_order ) {
			sl = ' selected="selected"';
		}
		
		//
		str += '<option value="' + eb_this_current_url + '?orderby=' +x+ '" ' +sl+ '>' +arr[x]+ '</option>';
	}
	
	//
	dog( 'oi_order_by', '<select>' +str+ '</select>' );
	jQuery('#oi_order_by select').change(function () {
		var a = jQuery(this).val() || '';
		if ( a != '' ) {
//				alert(a);
			window.location = a;
		}
	});
})();

//
function ___eb_list_product_order () {
	console.log('WARNING! Function ___eb_list_product_order bas been remove, please clear in your code!');
}

//
function ___eb_list_post_run ( r ) {
	if ( khong_chay_function_o_theme_nua == 1 ) {
		console.log('WARNING!!!!!!!!!!!!!!!!!!! function ___eb_list_post_run has been runing...');
		return false;
	}
	khong_chay_function_o_theme_nua = 1;
	
	if ( typeof Child_eb_list_post_run == 'function' ) {
		if ( WGR_check_option_on ( cf_tester_mode ) ) console.log('Child_eb_list_post_run');
		
		Child_eb_list_post_run();
	}
	
	
	//
	(function ( a ) {
		
		if ( a != '' ) {
			
			// chỉ xử lý khi nội dung đủ lớn
			if ( cf_cats_description_viewmore < 0 ) {
				if ( WGR_check_option_on ( cf_tester_mode ) ) console.log('View more category disable!');
				
				jQuery('.global-cats-description').hide();
				
				return false;
			}
//			else if ( cf_cats_description_viewmore > 0 && jQuery('.global-cats-description').height() < cf_cats_description_viewmore * 1.5 ) {
			else if ( cf_cats_description_viewmore == 0 || jQuery('.global-cats-description').height() < cf_cats_description_viewmore * 1.5 ) {
//				console.log( jQuery('.global-cats-description').height() );
				jQuery('.global-cats-description').addClass('global-cats-description-active');
				return false;
			}
			
			// hiển thị nút bấm hiển thị thêm nội dung
			jQuery('.viewmore-cats-description').show();
			
			// thêm class tạo hiệu ứng thu gọn nội dung
			jQuery('.global-cats-description').addClass('global-cats-description-scroll').height( cf_cats_description_viewmore );
			
			//
			jQuery('.click-viewmore-cats-description').click(function () {
				jQuery('.global-cats-description').toggleClass('global-cats-description-active');
				
				window.scroll( 0, jQuery('.global-cats-description').offset().top - 90 );
			});
			
		} else {
			jQuery('.global-cats-description').hide();
		}
		
	})( jQuery('.global-cats-description').html() || '' );
}



function ___eb_global_blogs_runing ( r ) {
	if ( khong_chay_function_o_theme_nua == 1 ) {
		console.log('WARNING!!!!!!!!!!!!!!!!!!! function ___eb_global_blogs_runing has been runing...');
		return false;
	}
	khong_chay_function_o_theme_nua = 1;
	
	if ( typeof Child_eb_global_blogs_runing == 'function' ) {
		Child_eb_global_blogs_runing();
	}
}