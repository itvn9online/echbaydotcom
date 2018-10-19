

//
WGR_admin_quick_edit_select_menu();
change_update_new_stt();



// thay đổi số lượng bài viết sẽ hiển thị
(function () {
	var arr = [
		3,
		10,
		20,
		30,
		50,
		100,
		200,
		500,
		800
	];
	
	var str = '',
		sl = '';
	
	for ( var i = 0; i < arr.length; i++ ) {
		sl = '';
		if ( arr[i] == threadInPage ) {
			sl = ' selected="selected"';
		}
		
		//
		str += '<option value="' +arr[i]+ '"' + sl + '>' +arr[i]+ '</option>';
	}
	
	jQuery('#change_set_thread_show_in_page').html( '<option value="">---</option>' + str ).off('change').change(function () {
		var a = jQuery(this).val() || '';
		if ( a == '' ) {
			a = 68;
		}
		
		//
		g_func.setc('quick_edit_per_page', a, 0, 30);
		
		//
		jQuery('body').css({
			opacity: .2
		});
		
		//
		setTimeout(function () {
			window.location = strLinkPager;
		}, 600);
	});
})();




// bấm vào để chỉnh sửa giá nhanh
jQuery('.click-quick-edit-price').off('click').click(function  () {
	var id = jQuery(this).attr('data-id') || '',
		old_price = jQuery(this).attr('data-old-price') || '',
		new_price = jQuery(this).attr('data-new-price') || '';
	
	if ( old_price == '' ) {
		old_price = 0;
	}
	
	if ( new_price == '' ) {
		new_price = 0;
	}
	
	if ( old_price == 0 && new_price > 0 ) {
		old_price = new_price;
	}
	
	//
//	console.log( id );
//	console.log( old_price );
//	console.log( new_price );
	
	//
	var f = document.frm_quick_edit_price;
	f.t_product_id.value = id;
	f.t_old_price.value = g_func.money_format( old_price );
	f.t_new_price.value = g_func.money_format( new_price );
	
	//
	jQuery('#frm_quick_edit_price').show();
	
	//
	f.t_new_price.focus();
});


//
jQuery('#quick_edit_new_price').off('change').change(function () {
	var a = jQuery(this).val() || '',
		b = jQuery('#quick_edit_old_price').val() || '';
	
	//
	if ( a == '' ) {
		return false;
	}
	a = a.toLowerCase();
	b = b.toLowerCase();
	
	// tính theo % của giá cũ
	if ( a.split('%').length > 1 ) {
		// nếu giá cũ không có giá trị gì -> lấy theo giá mới, sau đó mới gán lại giá trị cho giá mới
		if ( b == '' || b == 0 ) {
			b = jQuery('.click-quick-edit-price[data-id="' + document.frm_quick_edit_price.t_product_id.value + '"]').attr('data-new-price') || '';
			jQuery('#quick_edit_old_price').val( b );
		}
		
		// Kiểm tra lại, vẫn thế -> hủy luôn
		if ( b == '' || b == 0 ) {
			jQuery('#quick_edit_old_price').val( 0 );
			a = 0;
		}
		else {
			b = g_func.only_number( b );
			
			// nếu là 0% -> gán bằng giá cũ luôn
			if ( g_func.only_number( a ) == 0 ) {
				a = b;
			}
			else {
				// giảm theo số %
				if ( a.split('-').length > 1 ) {
					a = b/ 100 * g_func.only_number( a );
					a = b + a;
				}
				// bằng số %
				else {
					a = b/ 100 * g_func.only_number( a );
				}
			}
		}
		
		//
		jQuery(this).val( g_func.money_format( a ) );
	}
	// đơn vị k -> nhân thêm 1000
	else if ( a.split('k').length > 1 ) {
		a = g_func.only_number( a );
		jQuery(this).val( g_func.money_format( a * 1000 ) );
	}
	// lấy giá trực tiếp theo số liệu nhập vào
	else {
		jQuery(this).val( g_func.money_format( a ) );
	}
	
	//
	return false;
});

//
jQuery('#quick_edit_old_price').off('change').change(function () {
	var a = jQuery(this).val() || '';
	
	// đơn vị k -> nhân thêm 1000
	if ( a.split('k').length > 1 ) {
		a = g_func.only_number( a );
		jQuery(this).val( g_func.money_format( a * 1000 ) );
	}
	// lấy giá trực tiếp theo số liệu nhập vào
	else {
		jQuery(this).val( g_func.money_format( a ) );
	}
	
	//
	return false;
});

//
function WGR_check_quick_edit_price () {
	var f = document.frm_quick_edit_price;
	var a = g_func.only_number( f.t_old_price.value ),
		b = g_func.only_number( f.t_new_price.value );
	
	//
//	if ( a <= b ) {
	if ( a == b ) {
		a = 0;
	}
	
	//
	var trang = jQuery('.admin-part-page strong').html() || 1,
		uri = '&post_id=' + f.t_product_id.value + '&by_post_type=post&trang=' + trang + '&t=update_price&old_price=' + a + '&new_price=' + b;
//	console.log( uri );
	
	//
	WGR_admin_quick_edit_products( 'products', uri );
	
	//
	return false;
}



// tìm kiếm sản phẩm
function auto_submit_products_post_search () {
	var f = document.frm_products_post_search;
	
	WGR_admin_quick_edit_products( 'products', '&by_post_type=' + f.by_post_type.value + '&q=' + encodeURIComponent( f.q.value ) );
	
	return false;
}




// Chỉnh sửa SEO nhanh
WGR_click_open_quick_edit_seo();






// quick multi edit
function load_list_thread_check () {
	var str = '';
	jQuery('.eb-uix-thread-checkbox:checkbox:checked').each(function() {
//		console.log( jQuery(this).val() );
		str += ',' + jQuery(this).val();
	});
	
	//
	document.frm_multi_edit_post.t_list_id.value = str;
}


function WGR_after_update_multi_post () {
	f = document.frm_multi_edit_post;
	
	//
	ajaxl( 'products&by_post_type=' + f.by_post_type.value + '&by_cat_id=' + f.by_cat_id.value + '&trang=' + f.current_page.value, 'rAdminME', 9, function () {
		jQuery('#rAdminME').css({
			opacity: 1
		});
	});
}


jQuery('.click-show-tools').off('click').click(function () {
	jQuery('.show-if-click-tools, .show-if-quick-edit').toggle('slow');
	
	//
	var a = g_func.getc('eb_admin_quick_edit_post_taxonomy');
	
	// nếu -> đang mở -> đóng lại
	if ( a != null || a == 1 ) {
		g_func.delck( 'eb_admin_quick_edit_post_taxonomy' );
	}
	// chưa có -> gán lệnh đang mở
	else {
		g_func.setc( 'eb_admin_quick_edit_post_taxonomy', 1, 6 * 3600 );
	}
});


//
jQuery('#thread-all-checkbox').off('click').click(function () {
	jQuery('input[name=\'thread-checkbox\']').prop( 'checked', dog('thread-all-checkbox').checked );
	
	//
	load_list_thread_check();
});


//
jQuery('.eb-uix-thread-checkbox').off('click').click(function () {
	load_list_thread_check();
});


//
jQuery('.click-set-actions-for').off('click').click(function () {
	var a = jQuery(this).attr('data-for') || '',
		b = jQuery(this).attr('data-id-for') || 0,
		f = document.frm_multi_edit_post;
	
	if ( f.t_list_id.value == '' ) {
		a_lert('Không xác định được sản phẩm cần sửa');
	}
	else if ( a == '' ) {
		a_lert('Không xác định được thao tác');
	}
	else {
		jQuery('#rAdminME').css({
			opacity: 0.2
		});
		
		//
		f.actions_for.value = a;
		f.actions_id_for.value = b;
		f.submit();
		
		//
		jQuery('#target_eb_iframe').on('load', function () {
			jQuery('#rAdminME').css({
				opacity: 1
			});
		});
	}
});




_global_js_eb.select_date('.thread-list-ngayhethan');



