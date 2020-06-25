


//
var eb_global_product_size = '',
	eb_inner_html_product_size = '',
	// size theo color
	admin_global_size_color = [];


function WGR_post_edit_change_url_avt ( a ) {
	jQuery('tr[data-row="_eb_product_avatar"]').after( '\
	<tr data-row="_eb_show_product_avatar">\
		<td class="t">&nbsp;</td>\
		<td class="i"><div class="show_product_avatar" style="background-image:url(\'' + a + '\')">&nbsp;</div></td>\
	</tr>' );
}

function WGR_check_and_load_submit_v5 ( i ) {
	// chờ load tầm 25 lần, tương đương với 5s
	if ( typeof i == 'undefined' ) {
		i = 25;
	}
	
	// sau 25 lần load thì bỏ đi
	if ( i < 0 ) {
		console.log('Maximun load for wp v5');
		
		// xóa luôn textarea liên quan
		jQuery('.get-html-for-v5-submit').remove();
		
		// thoát
		return false;
	}
	
	// nếu không có -> chờ load lại
	if ( jQuery('.editor-post-publish-button').length == 0 ) {
		setTimeout(function () {
			WGR_check_and_load_submit_v5( i - 1 );
		}, 200);
		return false;
	}
	
	//
	jQuery('.editor-post-publish-button').click(function () {
//		console.log('Code for submit v5');
		
		// textarea đang lỗi -> chế cách khắc phục kiểu này
		jQuery('.get-html-for-v5-submit').each(function() {
			var t = jQuery(this).attr('name') || null;
			
			//
			if ( t != null ) {
				// lấy nội dung trong iframe
				var a = jQuery('#' + t.replace( '_forv5', '' ) + '_ifr').contents().find('body').html() || '';
//				console.log(a);
				
				//
				if ( a != '' ) {
					jQuery(this).val( a );
				}
			}
		});
	});
	
	//
	console.log('Set submit for v5');
	return true;
}


// xóa các mảng bị null
function WGR_remove_add_null_value ( arr ) {
	var new_arr = [];
	
	for ( var i = 0; i < arr.length; i++ ) {
		if ( arr[i] != null ) {
			new_arr.push( arr[i] );
		}
	}
	
	return new_arr;
}


function WGR_add_value_form_edit_size ( arr ) {
	jQuery('#eb_input_edit_product_size_name').val( arr.name );
	jQuery('#eb_input_edit_product_size_sku').val( arr.sku );
	jQuery('#eb_input_edit_product_size_quan').val( arr.val );
	jQuery('#eb_input_edit_product_size_price').val( g_func.money_format( arr.price ) );
	
	//
	jQuery('.eb-input-edit-product-size').css({
//		top : jQuery(current_select).offset().top + jQuery(current_select).height(),
//		top : jQuery(current_select).offset().top + 30,
//		left : jQuery(current_select).offset().left
	}).show();
	
	//
	jQuery('.eb-input-edit-product-size input[name="eb_input_edit_product_size_name"]').focus();
	
	//
	jQuery('.eb-input-edit-product-size button').off('click').click(function () {
		var a = jQuery(this).attr('data-action') || '';
		console.log(a);
		
		// size theo màu
		if ( jQuery('#eb_input_edit_color_size_node').val() != '' ) {
			console.log('Edit size for color');
			
			//
			var a_node = jQuery('#eb_input_edit_color_size_node').val();
			
			//
			if ( a == 'save' ) {
				admin_global_size_color[ a_node ] = {
					name : jQuery('#eb_input_edit_product_size_name').val(),
					sku : jQuery('#eb_input_edit_product_size_sku').val(),
					val : jQuery('#eb_input_edit_product_size_quan').val(),
					price : jQuery('#eb_input_edit_product_size_price').val().replace( /\,/g, '' )
				};
			}
			else if ( a == 'remove' ) {
				admin_global_size_color[ a_node ] = null;
				admin_global_size_color = WGR_remove_add_null_value( admin_global_size_color );
			}
			
			//
			WGR_show_list_size_in_color();
		}
		// size chính
		else {
			if ( a == 'save' ) {
				check_eb_input_edit_product_size();
			}
			else if ( a == 'remove' ) {
//				eb_global_product_size[ a_parent ][ a_node ] = null;
//				eb_global_product_size[ a_node ] = null;
				eb_global_product_size[ jQuery('#eb_input_edit_product_size_node').val() ] = null;
				eb_global_product_size = WGR_remove_add_null_value( eb_global_product_size );
				
				//
				eb_func_global_product_size();
			}
		}
		
		//
		jQuery('.eb-input-edit-product-size').hide();
	});
}


function WGR_show_list_size_in_color () {
	var str = '';
	for ( var i = 0; i < admin_global_size_color.length; i++ ) {
		str += WGR_create_note_for_product_size( admin_global_size_color[i], i );
	}
	str += '<li data-add="1" title="Thêm size (color) mới"><i class="fa fa-plus"></i></li>';
	
	jQuery('.eb-admin-product-color-size').html( str );
	
	// hiệu ứng chỉnh size
	jQuery('.eb-admin-product-color-size li').off('click').click(function () {
		console.log( admin_global_size_color );
		
		// Bỏ phần sửa size chính
		jQuery('#eb_input_edit_product_size_node').val('');
		
		var a = jQuery(this).attr('data-add') || '',
			a_node = 0;
//		console.log(a);
		
		// nếu không có thông số add -> sửa size
		if ( a == '' ) {
			a_node = jQuery(this).attr('data-node') || 0;
		}
		else {
			// thêm size cho màu
			admin_global_size_color.push( {
				name : "",
				val : ""
			});
//			console.log( admin_global_size_color );
			
			//
			WGR_show_list_size_in_color();
			
			//
			a_node = admin_global_size_color.length - 1;
		}
		
		// kích hoạt chế độ lưu size theo màu
		jQuery('#eb_input_edit_color_size_node').val(a_node);
		
		//
		WGR_add_value_form_edit_size( admin_global_size_color[ a_node ] );
	});
	
	//
	if ( admin_global_size_color.length > 0 ) {
		jQuery('#eb_input_edit_product_size_bycolor_id').val( escape( JSON.stringify( admin_global_size_color ) ) );
	} else {
		jQuery('#eb_input_edit_product_size_bycolor_id').val('');
	}
}


function WGR_create_note_for_product_size ( arr, j ) {
//	console.log( arr );
	
	var str = '',
		show_text = '',
		show_title = '';
	
	if ( typeof arr == 'object' ) {
		// khi chuyển size sang còn 1 mảng duy nhất -> khai báo j để đỡ phải viết lại nhiều code
//		var j = 0;
//		arr[j] = arr;
		
		//
//		for ( var j = 0; j < arr.length; j++ ) {
			/*
			if ( arr == null ) {
			}
			else {
				*/
				// conver từ bản code cũ sang
				if ( typeof arr.name == 'undefined' ) {
					if ( typeof arr.ten != 'undefined' ) {
						arr.name = arr.ten;
					}
					else {
						arr.name = '';
					}
				}
				
				if ( typeof arr.val == 'undefined' ) {
					if ( typeof arr.soluong != 'undefined' ) {
						arr.val = arr.soluong;
					}
					else {
						arr.val = 0;
					}
				}
				
				if ( typeof arr.sku == 'undefined' ) {
					arr.sku = '';
				}
				
				if ( typeof arr.price == 'undefined' ) {
					arr.price = '';
				}
				
				//
				show_text = arr.name + '/ ' + arr.val;
				show_title = 'Size: ' + arr.name + '/ Số lượng: ' + arr.val;
				if ( arr.sku != '' ) {
					show_text += '/ ' + arr.sku;
					show_title += '/ Mã sản phẩm: ' + arr.sku;
				}
				if ( arr.price != '' ) {
					show_text += '/ ' + g_func.money_format( arr.price );
					show_title += '/ Giá bán: ' + g_func.money_format( arr.price );
				}
				
				//
				str += '<li data-parent="0" data-node="' + j + '" data-size="' + arr.name + '" data-sku="' + arr.sku + '" data-quan="' + arr.val + '" data-price="' + arr.price + '" title="' + show_title + '">' + show_text + '</li>';
//			}
//		}
	}
	
	return str;
};


// kích hoạt chức năng hiển thị tên nhóm hoặc bài viết được chọn
function WGR_ads_get_current_select_category_or_post_name ( action_id ) {
	if ( typeof action_id == 'undefined' ) {
		WGR_ads_get_current_select_category_or_post_name( '_eb_ads_for_post' );
		WGR_ads_get_current_select_category_or_post_name( '_eb_ads_for_category' );
		return false;
	}
	
	//
	var a = jQuery('#' + action_id).val() || '';
//	console.log(a);
	if ( a != '' ) {
		a = g_func.number_only(a);
		
		//
		if ( action_id == '_eb_ads_for_post' ) {
			if ( WGR_ads_show_current_select_category_or_post_name( a, eb_posts_list, action_id ) == false ) {
				if ( WGR_ads_show_current_select_category_or_post_name( a, eb_blogs_list, action_id ) == false ) {
					WGR_ads_show_current_select_category_or_post_name( a, eb_pages_list, action_id );
				}
			}
		}
		else {
			if ( WGR_ads_show_current_select_category_or_post_name( a, eb_site_group, action_id ) == false ) {
				if ( WGR_ads_show_current_select_category_or_post_name( a, eb_blog_group, action_id ) == false ) {
					if ( WGR_ads_show_current_select_category_or_post_name( a, eb_tags_group, action_id ) == false ) {
						WGR_ads_show_current_select_category_or_post_name( a, eb_options_group, action_id );
					}
				}
			}
		}
	}
}

// Tìm và hiển thị tên nhóm hoặc bài viết đang được chọn
function WGR_ads_show_current_select_category_or_post_name ( a, arr, id ) {
//	console.log(a);
	
	//
	var r = false;
	
	//
	for ( var i = 0; i < arr.length; i++ ) {
		if ( a == arr[i].id ) {
			jQuery('.show-for-' + id).html( arr[i].ten );
			r = true;
			break;
		}
		else if ( typeof arr[i].arr == 'object' && arr[i].arr.length > 0 ) {
			if ( WGR_ads_show_current_select_category_or_post_name ( a, arr[i].arr, id ) == true ) {
				break;
			}
		}
	}
	
	//
	return r;
}

function WGR_run_for_admin_edit_ads_post ( action_id ) {
	if ( dog(action_id) == null ) {
		return false;
	}
	
	//
	WGR_check_if_value_this_is_one('_eb_ads_target');
	WGR_check_if_value_this_is_one('_eb_ads_name');
	
	//
//	console.log(action_id);
	jQuery('#' + action_id).after('<div><em class="small bluecolor show-for-' + action_id + '"></em></div>');
	
	//
	WGR_ads_get_current_select_category_or_post_name( action_id );
	
	// nhập ID blog, product, page mà q.cáo alias tới
	var jd_for_quick_search_post = 'quick_sreach_for' + action_id,
		action_for_quick_search_post = '';
	jQuery('#' + action_id).attr({
		autocomplete: 'off'
	}).click(function () {
//		console.log(eb_site_group);
		
		//
		action_for_quick_search_post = jd_for_quick_search_post;
		
		//
		if ( dog(jd_for_quick_search_post) == null ) {
			jQuery(this).after('<div class="admin-show-quick-search"><div id="' + jd_for_quick_search_post + '" class="ads-show-quick-search-post"></div></div>');
			
			//
			if ( action_id == '_eb_ads_for_post' ) {
				if ( eb_posts_list.length > 0 ) {
					jQuery('#' + jd_for_quick_search_post).append( edit_post_load_list_post_for_quick_search( eb_posts_list, 'Sản phẩm (Post)' ) );
				}
				
				if ( eb_blogs_list.length > 0 ) {
					jQuery('#' + jd_for_quick_search_post).append( edit_post_load_list_post_for_quick_search( eb_blogs_list, 'Blog/ Tin tức' ) );
				}
				
				if ( eb_pages_list.length > 0 ) {
					jQuery('#' + jd_for_quick_search_post).append( edit_post_load_list_post_for_quick_search( eb_pages_list, 'Trang tĩnh (Page)' ) );
				}
			}
			else {
				if ( eb_site_group.length > 0 ) {
					jQuery('#' + jd_for_quick_search_post).append( edit_post_load_list_taxonomy_for_quick_search( eb_site_group, 'Chuyên mục sản phẩm' ) );
				}
				
				if ( eb_blog_group.length > 0 ) {
					jQuery('#' + jd_for_quick_search_post).append( edit_post_load_list_taxonomy_for_quick_search( eb_blog_group, 'Blog/ Tin tức', 'blogs' ) );
				}
				
				if ( eb_tags_group.length > 0 ) {
					jQuery('#' + jd_for_quick_search_post).append( edit_post_load_list_taxonomy_for_quick_search( eb_tags_group, 'Thẻ', 'post_tag' ) );
				}
				
				if ( eb_options_group.length > 0 ) {
					jQuery('#' + jd_for_quick_search_post).append( edit_post_load_list_taxonomy_for_quick_search( eb_options_group, 'Thông số khác', 'post_options' ) );
				}
			}
			
			//
			jQuery('#' + jd_for_quick_search_post + ' li').click(function () {
				jQuery('#' + action_id).val( jQuery(this).attr('data-id') || '' );
				jQuery('body').addClass('hide-module-advanced-ads');
				window.scroll( 0, 0 );
				WGR_ads_get_current_select_category_or_post_name(action_id);
			});
		}
		
		//
		jQuery('#' + jd_for_quick_search_post).show();
	}).focus(function () {
		if ( dog(jd_for_quick_search_post) == null ) {
			jQuery(this).click();
		}
		
		action_for_quick_search_post = jd_for_quick_search_post;
		jQuery('#' + jd_for_quick_search_post).show();
	}).blur(function () {
		jQuery('#' + jd_for_quick_search_post).fadeOut();
		
		//
		if ( jQuery('#_eb_ads_for_post').val() == '' && jQuery('#_eb_ads_for_category').val() == '' ) {
			jQuery('body').removeClass('hide-module-advanced-ads');
		}
	});
	
	// thêm class để ẩn các chức năng không còn cần thiết khi q.cáo có alias
	if ( jQuery('#_eb_ads_for_post').val() != '' || jQuery('#_eb_ads_for_category').val() != '' ) {
		jQuery('body').addClass('hide-module-advanced-ads');
	}
	
	
	// kích hoạt chức năng tìm kiếm nhanh
	jQuery('#' + action_id).keyup(function (e) {
//		console.log(e.keyCode);
		
		//
		var fix_id = '#' + action_for_quick_search_post + ' li';
		
		// enter
		if (e.keyCode == 13) {
			return false;
		}
		// ESC
		else if (e.keyCode == 27) {
			jQuery('#' + action_for_quick_search_post).fadeOut();
			return false;
		}
		
		//
		var key = jQuery(this).val() || '';
		if (key != '') {
			key = g_func.non_mark_seo(key);
			key = key.replace(/[^0-9a-zA-Z]/g, '');
		}
		
		//
		if (key != '') {
			jQuery(fix_id).hide().each(function() {
				var a = jQuery(this).attr('data-key') || '';
				
				//
				if ( a != '' ) {
					if ( key.length == 1 && a.substr(0, 1) == key) {
						jQuery(this).show();
					} else if ( a.split(key).length > 1) {
						jQuery(this).show();
					}
				}
			});
		} else {
			jQuery(fix_id).show();
		}
	});
}

function edit_post_load_list_post_for_quick_search ( arr, arr_name, permarklink, taxonomy ) {
	// post
	if ( typeof permarklink == 'undefined' ) {
		permarklink = 0;
	}
	// taxonomy
	else {
		permarklink = 1;
	}
	
	//
//	if ( typeof taxonomy == 'undefined' ) {
//		taxonomy = 'category';
//	}
	
	//
	return '<h4>' + arr_name + '</h4><ul>' + edit_post_load_list_details_post_for_quick_search ( arr, permarklink, taxonomy ) + '</ul>';
}

function edit_post_load_list_details_post_for_quick_search ( arr, permarklink, taxonomy, child_of ) {
	// Thêm dấu - ở trước tên để phân biệt cha con
	if ( typeof child_of == 'undefined' ) {
		child_of = '';
	}
	
	//
	var str = '',
		key = '',
		lnk = '';
	for ( var i = 0; i < arr.length; i++ ) {
		key = g_func.non_mark_seo( arr[i].ten ) + arr[i].seo;
		
		// taxonomy
		if ( permarklink == 1 ) {
			lnk = '';
			if ( taxonomy != 'category' ) {
				lnk = 'taxonomy=' + taxonomy + '&';
			}
			
			lnk += 'cat=' + arr[i].id;
		}
		// post
		else {
			lnk = 'p=' + arr[i].id;
		}
		
		//
		str += '<li data-id="' + arr[i].id + '" data-key="' + key.replace(/\-/g, '') + '">' + child_of + arr[i].ten + ' <a href="' + web_link + '?' + lnk + '" target="_blank"><i class="fa fa-external-link"></i></a></li>';
		
		//
		if ( typeof arr[i].arr == 'object' && arr[i].arr.length > 0 ) {
			str += edit_post_load_list_details_post_for_quick_search ( arr[i].arr, permarklink, taxonomy, child_of + '- ' );
		}
	}
	
	return str;
}

function edit_post_load_list_taxonomy_for_quick_search ( arr, arr_name, taxonomy ) {
	//
	if ( typeof taxonomy == 'undefined' ) {
		taxonomy = 'category';
	}
	
	//
	return edit_post_load_list_post_for_quick_search ( arr, arr_name, 1, taxonomy );
}








function check_eb_input_edit_product_color () {
	var f = document.frm_eb_input_edit_product_color;
	
	jd = f.eb_input_edit_product_color_id.value;
	ten = f.eb_input_edit_product_color_name.value;
	sku = f.eb_input_edit_product_color_sku.value;
	color = f.eb_input_edit_product_color_color.value;
	quan = f.eb_input_edit_product_color_quan.value;
	price = f.eb_input_edit_product_color_price.value;
	size_color = f.eb_input_edit_product_size_bycolor_id.value;
	
	//
	var iff_id = '_eb_product_list_color_ifr';
	
	jQuery('#' + iff_id).contents().find( 'img#' + jd ).attr({
		'alt' : ten,
		'data-sku' : sku,
		'data-color' : color,
		'data-quan' : quan,
		'data-price' : price.replace( /\,/g, '' ),
		'data-size' : size_color
	});
	
	return false;
}









function eb_func_add_nut_product_size ( str, i ) {
	if ( typeof str == 'undefined' ) {
		str = '';
	}
	if ( typeof i == 'undefined' ) {
		i = 0;
	}
	
	//
	return '\
	<div class="eb-admin-product-size">\
		<ul class="cf">\
			' + str + '\
			<li data-parent="' + i + '" data-add="1" title="Thêm size mới"><i class="fa fa-plus"></i></li>\
		</ul>\
	</div>';
}

function eb_func_show_product_size () {
	
	// nếu mảng size chưa được tạo -> tìm và tạo từ string
	if ( typeof eb_global_product_size != 'object' ) {
		
		// TEST
		/*
		console.log('TEST');
		if ( jQuery('#_eb_product_size').val() == '' ) {
			jQuery('#_eb_product_size').val(',{name:"800x1200",val:"1554000"},{name:"1000x1200",val:"1849000"},{name:"1200x1500",val:"2432000"},{name:"1200x1600",val:"2566000"},{name:"1200x1800",val:"2814000"},{name:"1200x2000",val:"2981000"},{name:"1200x2200",val:"3239000"},{name:"1200x2400",val:"3496000"}');
		}
		*/
		
		//
		eb_global_product_size = jQuery('#_eb_product_size').val() || '';
//		console.log( eb_global_product_size ); return false;
		if ( eb_global_product_size != '' ) {
//			eb_global_product_size = unescape( eb_global_product_size );
			
			// xử lý với các dữ liệu cũ đang bị lệch sóng
			if ( eb_global_product_size.substr(0, 1) == ',' ) {
				eb_global_product_size = eb_global_product_size.substr(1);
			}
			
			if ( eb_global_product_size.substr(0, 1) != '[' ) {
				eb_global_product_size = "[" + eb_global_product_size + "]";
			}
//			console.log( eb_global_product_size );
			eb_global_product_size = eb_global_product_size.replace( /\[\[/g, '[' ).replace( /\]\]/g, ']' );
//			console.log( eb_global_product_size );
			
			// chuyển từ string sang object
			try {
				eb_global_product_size = eval( eb_global_product_size );
			} catch ( e ) {
				eb_global_product_size = [];
				console.log( WGR_show_try_catch_err( e ) );
			}
			
		} else {
			eb_global_product_size = [];
		}
//		console.log( JSON.stringify( eb_global_product_size ) );
		console.log(eb_global_product_size);
		
		// nếu mảng số 0 tồn tại tham số name -> kiểu dữ liệu cũ -> convert sang dữ liệu mới
		if ( eb_global_product_size.length > 0
		&& typeof eb_global_product_size[0].name == 'undefined' ) {
			
			//
			for ( var i = 0; i < eb_global_product_size.length; i++ ) {
				// chuyển ten -> name
				if ( typeof eb_global_product_size[i].ten != 'undefined'
				&& typeof eb_global_product_size[i].name == 'undefined' ) {
					eb_global_product_size[i].name = eb_global_product_size[i].ten;
				}
				
				// chuyển số lượng sang val
				if ( typeof eb_global_product_size[i].soluong != 'undefined'
				&& typeof eb_global_product_size[i].val == 'undefined' ) {
					eb_global_product_size[i].val = eb_global_product_size[i].soluong;
				}
			}
			console.log(eb_global_product_size);
			
			// kiểm tra lại lần nữa
			if ( eb_global_product_size.length > 0
			&& typeof eb_global_product_size[0].name == 'undefined' ) {
				if ( typeof eb_global_product_size[0][0].name != 'undefined' ) {
					eb_global_product_size = eb_global_product_size[0];
				}
				else {
					console.log('ERROR fixed arr product size!');
					eb_global_product_size = [];
				}
			}
			
		}
		/*
		if ( typeof eb_global_product_size[0] != 'undefined' ) {
			if ( typeof eb_global_product_size[0][0] == 'undefined' ) {
				var eb_global_product_size_v2 = eb_global_product_size.slice();
//				console.log( eb_global_product_size );
//				console.log( eb_global_product_size_v2 );
				
				eb_global_product_size = [];
				eb_global_product_size[0] = eb_global_product_size_v2;
//				console.log( JSON.stringify( eb_global_product_size ) );
			}
		}
		*/
		
	}
//	console.log( eb_global_product_size );
//	console.log( eb_global_product_size.length );
//	return false;
	
	var str_size = '';
	if ( eb_global_product_size.length > 0 ) {
		for ( var i = 0; i < eb_global_product_size.length; i++ ) {
//			console.log( i );
			
			//
//			var arr = eb_global_product_size[i];
//			var str_node_size = (function ( arr ) {
//			str_size += (function ( arr ) {
//			})( eb_global_product_size[i] );
			str_size += WGR_create_note_for_product_size( eb_global_product_size[i], i );
			
			//
			/*
			if ( str_node_size != '' ) {
				str_size += eb_func_add_nut_product_size( str_node_size, i );
			}
			*/
			
			//
			/*
			if ( eb_global_product_size.length > 1 ) {
				console.log('Chỉ sử dụng một mảng để tạo size!');
			}
			break;
			*/
		}
		/*
	} else {
		str_size += eb_func_add_nut_product_size();
		*/
	}
	str_size = eb_func_add_nut_product_size( str_size );
	
	//
//	console.log(eb_inner_html_product_size);
//	console.log(str_size);
	jQuery('#' + eb_inner_html_product_size).html( str_size + '<div class="small">Chức năng dùng để tạo danh sách Kích thước, dung tích... cho sản phẩm và mức giá riêng (nếu có). Nếu phần giá riêng này được thiết lập, nó sẽ được sử dụng để thay thế giá mặc định của sản phẩm.</div>' );
//	jQuery('#' + eb_inner_html_product_size + ' ul:last li:last').after('<li data-add="group" title="Thêm nhóm size mới (một số theme mới hỗ trợ tính năng này)"><i class="fa fa-plus"></i> <i class="fa fa-plus"></i></li>');
	
	// chuyển từ object sang string
	/*
	eb_global_product_size = JSON.parse(eb_global_product_size);
	eb_global_product_size = jQuery.parseJSON(eb_global_product_size);
	*/
//	console.log( eb_global_product_size );
//	console.log( eb_global_product_size.length );
	
	// gán trở lại để còn lưu dữ liệu
	if ( eb_global_product_size.length > 0 ) {
//		console.log( escape( JSON.stringify( eb_global_product_size ) ) );
//		jQuery('#_eb_product_size').val( escape( JSON.stringify( eb_global_product_size ) ) );
		jQuery('#_eb_product_size').val( JSON.stringify( eb_global_product_size ) );
	} else {
		jQuery('#_eb_product_size').val('');
	}
	
}

function check_eb_input_edit_product_size () {
	
	// nếu là thay đổi size riêng của màu -> chơi function riêng và mới cho nó ổn định
	if ( jQuery('#eb_input_edit_color_size_node').val() != '' ) {
		console.log('size for color!');
		return false;
	}
	
	
	
	//
	/*
	var current_select = '#' + eb_inner_html_product_size + ' li.selected';
	console.log(current_select);
	if ( jQuery( current_select ).length == 0 ) {
		console.log('current_select(2) not found!');
		return false;
	}
	*/
	
	//
//	var a_parent = jQuery(current_select).attr('data-parent') || 0,
//		a_node = jQuery(current_select).attr('data-node') || 0;
	var a_parent = 0,
		a_node = jQuery('#eb_input_edit_product_size_node').val() || 0;
//	console.log( a_parent );
	console.log( a_node );
	
	//
//	eb_global_product_size[ a_parent ][ a_node ] = {
	eb_global_product_size[ a_node ] = {
		name : jQuery('#eb_input_edit_product_size_name').val(),
		sku : jQuery('#eb_input_edit_product_size_sku').val(),
		val : jQuery('#eb_input_edit_product_size_quan').val(),
		price : jQuery('#eb_input_edit_product_size_price').val().replace( /\,/g, '' )
	};
	console.log( eb_global_product_size );
	
	//
	eb_func_global_product_size();
	
	return false;
	
	
	
	
	// v1
	if ( typeof eb_global_product_size[ a_parent ][ a_node ] == 'undefined' ) {
		alert('Object value (node) not found');
		return false;
	}
	
	jQuery('.eb-input-edit-product-size input[name="eb_input_edit_product_size_sku"]').val( eb_global_product_size[ a_parent ][ a_node ].sku );
	jQuery('.eb-input-edit-product-size input[name="eb_input_edit_product_size_price"]').val( eb_global_product_size[ a_parent ][ a_node ].price );
	jQuery('.eb-input-edit-product-size input[name="eb_input_edit_product_size_quan"]').val( eb_global_product_size[ a_parent ][ a_node ].val );
	jQuery('.eb-input-edit-product-size input[name="eb_input_edit_product_size_name"]').val( eb_global_product_size[ a_parent ][ a_node ].name );
	
	
	
	//
	var ten = jQuery('.eb-input-edit-product-size input[name="eb_input_edit_product_size_name"]').val() || '',
		sku = jQuery('.eb-input-edit-product-size input[name="eb_input_edit_product_size_sku"]').val() || '',
		sai = jQuery('.eb-input-edit-product-size input[name="eb_input_edit_product_size_quan"]').val() || '',
		price = jQuery('.eb-input-edit-product-size input[name="eb_input_edit_product_size_price"]').val() || '';
	
	/*
	if ( ten == '' || sai == '' ) {
		eb_global_product_size[ a_parent ][ a_node ] = null;
	}
	else {
		*/
		eb_global_product_size[ a_parent ][ a_node ] = {
			name : ten,
			sku : sku,
			val : sai,
			price : price
		};
//		console.log(eb_global_product_size);
//	}
	
	//
	eb_func_global_product_size();
	
	return false;
}

// các hiệu ứng khi click ào thẻ LI
function eb_func_click_modife_product_size () {
	
	jQuery('#' + eb_inner_html_product_size + ' li').off('click').click(function () {
//		console.log(1);
		
		// Bỏ phần lưu theo màu
		jQuery('#eb_input_edit_color_size_node').val('');
		
		jQuery('#' + eb_inner_html_product_size + ' li').removeClass('redcolor').removeClass('selected');
		
		//
		var a = jQuery(this).attr('data-add') || '';
//		console.log(a);
		
		// nếu không có thông số add -> sửa size
		if ( a == '' ) {
			
			//
			jQuery(this).addClass('redcolor').addClass('selected');
			
			//
			var a_node = jQuery(this).attr('data-node') || 0;
//			console.log('Edit size #' + a_node);
			
			// lưu vị trí cấn chỉnh sửa dữ liệu
			jQuery('#eb_input_edit_product_size_node').val( a_node );
			
			//
			/*
			var current_select = '#' + eb_inner_html_product_size + ' li.selected';
			console.log(eb_inner_html_product_size);
			if ( jQuery( current_select ).length == 0 ) {
				console.log('current_select not found!');
			}
			*/
			
			//
//			WGR_add_value_form_edit_size( eb_global_product_size[ 0 ][ a_node ] );
			WGR_add_value_form_edit_size( eb_global_product_size[ a_node ] );
		}
		else {
			// thêm nhóm size
			if ( a == 'group' ) {
				if ( typeof eb_global_product_size[0] != 'undefined' ) {
					var add_new_group_size = eb_global_product_size[0].slice();
					
//					eb_global_product_size[ eb_global_product_size.length ] = [];
					eb_global_product_size.push( add_new_group_size );
				} else {
					alert('First object not found');
					return false;
				}
			}
			// thêm size
			else {
				
				//
				/*
				var size_parent = jQuery(this).attr('data-parent') || 0;
				
				if ( typeof eb_global_product_size[size_parent] == 'undefined' ) {
					eb_global_product_size[size_parent] = [];
				}
				*/
				
//				eb_global_product_size[ size_parent ].push( {
				eb_global_product_size.push( {
					name : "",
					val : ""
				});
//				console.log( eb_global_product_size[ size_parent ] );
			}
			
			//
			eb_func_global_product_size();
			
			// tạo select cho LI để tiếp tục
			if ( a != 'group' ) {
				/*
//				jQuery(this).prev().addClass('redcolor').addClass('selected');
				setTimeout(function () {
//					jQuery('.eb-admin-product-size li[data-add="1"]').prev().addClass('redcolor').addClass('selected').click();
					jQuery('.eb-admin-product-size li[data-add="1"]').prev().click();
				}, 200);
				*/
				jQuery('.eb-admin-product-size li[data-node="' + ( eb_global_product_size.length - 1 ) + '"]').click();
			}
		}
		
		//
		/*
		if ( a == '' ) {
		}
		*/
	});
}


// kiểm tra và tạo size
function eb_func_global_product_size () {
	
	// nếu có module size -> chỉ sản phẩm mới có
	var kk = '_eb_product_size';
	
	if ( dog(kk) == null ) {
		return false;
	}
//	alert(1);
	
	//
	/*
	console.log('TEST');
	jQuery('#' + kk).attr({
		type : 'text'
	});
	*/
	
	// tạo khung để sử dụng chức năng add size
	eb_inner_html_product_size = 'oi' + kk;
	
	// nếu chưa có HTML để tạo hiệu ứng -> tạo
//	if ( dog(eb_inner_html_product_size) == null ) {
	if ( jQuery('#' + eb_inner_html_product_size).length == 0 ) {
		
		jQuery('tr[data-row="_eb_product_color"]').after('\
		<tr data-row="' + kk + '">\
			<td class="t bold">Kích thước</div></td>\
			<td id="' + eb_inner_html_product_size + '" class="i"></td>\
		</tr>');
		
		// thêm chức năng sửa size
//		jQuery('body').append('\');
		
	}
	
	//
	eb_func_show_product_size();
	eb_func_click_modife_product_size();
	
}


function WGR_run_for_admin_edit_post () {
	
	//
	/*
	if ( win_href.split('www.webgiare.org').length > 1 ) {
		jQuery(document).ready(function() {
			setTimeout(function () {
				jQuery('#click_remove_content_style').click();
			}, 2000);
		});
		
		//
		jQuery('#excerpt').val( jQuery('#excerpt').val().replace( /\&nbsp\;/gi, ' ' ) );
	}
	*/
	
	
	
	eb_func_global_product_size();
	
	
	
	// gán ảnh đại diện
	(function () {
		
		
		
		// chuyển định dạng số cho phần giá
		jQuery('#_eb_product_oldprice, #_eb_product_price').change(function () {
			var a = jQuery(this).val() || 0;
			
//				a = g_func.number_only( a );
			a = g_func.money_format( a );
			
			if ( a == '' ) {
				a = 0;
			}
			
//			console.log(a);
			jQuery(this).val(a);
			
			// kiểm tra và phân bổ lại giá
			var giacu = jQuery('#_eb_product_oldprice').val(),
				giaban = jQuery('#_eb_product_price').val();
			
			if ( giaban == 0 && giacu > 0 ) {
				jQuery('#_eb_product_oldprice').val( 0 );
				jQuery('#_eb_product_price').val( giacu );
			}
		}).change();
		
		
		
		// tạo hiệu ứng cho textarea
		if ( jQuery('textarea[id="excerpt"]').length > 0 ) {
			jQuery('textarea[id="excerpt"]').addClass('fix-textarea-height');
		}
		
		
		//
		var a = jQuery('#_eb_product_avatar').val() || '',
			b = jQuery('tr[data-row="_eb_product_avatar"]').length;
		if ( a != '' && b > 0 ) {
			
			// xử lý hình ảnh lỗi cho xwatch cũ
//			a = a.replace( '/home/pictures/', '/Home/Pictures/' );
//			jQuery('#_eb_product_avatar').val( a );
			
			//
			WGR_post_edit_change_url_avt( a );
		}
		
		jQuery('#_eb_product_avatar').change(function () {
			var a = jQuery(this).val() || '';
			
			// không có ảnh thì xóa
			if ( a == '' ) {
				if ( jQuery('tr[data-row="_eb_show_product_avatar"]').length > 0 ) {
					jQuery('tr[data-row="_eb_show_product_avatar"]').remove();
				}
			}
			// có thì add thêm vào
			else {
				if ( jQuery('tr[data-row="_eb_show_product_avatar"]').length > 0 ) {
					jQuery('tr[data-row="_eb_show_product_avatar"] .show_product_avatar').css({
						'background-image': 'url(\'' + a + '\')'
					});
					/*
					jQuery('tr[data-row="_eb_show_product_avatar"] img').attr({
						'src': a
					});
					*/
				} else {
					WGR_post_edit_change_url_avt( a );
				}
			}
		});
		
		// gán hình ảnh cho video youtube
		$('#_eb_ads_url, #_eb_ads_video_url').change(function () {
			var ytb = $('#_eb_ads_url').val() || '';
			var img = $('#_eb_product_avatar').val() || '';
			
			if ( ytb == ''  ) {
				ytb = $('#_eb_ads_video_url').val() || '';
			}
			
			if ( img == '' ) {
				if ( ytb.split('youtube.com/').length > 1 || ytb.split('youtu.be/').length > 1 ) {
					console.log(ytb);
					var a = _global_js_eb.youtube_id(ytb);
					console.log(a);
					
					if ( a != '' ) {
						$('#_eb_product_avatar').val('https://img.youtube.com/vi/' + a + '/0.jpg').change();
					}
				}
			}
		});
		
		
		// xử lý hình ảnh lỗi cho xwatch cũ
		/*
		setTimeout(function () {
			console.log('for xwatch domain');
			if ( jQuery("#_eb_product_gallery_ifr").length > 0 ) {
				jQuery("#_eb_product_gallery_ifr").contents().find('img').each(function() {
					var a = jQuery(this).attr('src') || '',
						b = a;
					if (a != '') {
						a = a.replace( '/home/pictures/', '/Home/Pictures/' );
						
						jQuery(this).attr({
							src : a,
							'data-old-src' : b
						});
					}
				});
			}
		}, 2000);
		*/
		
		
		
		
		jQuery('#postdivrich').after('\
		<div class="ebe-fixed-content-style">\
			<input type="checkbox" id="click_replace_content" />\
			<label for="click_replace_content">Thay thế nội dung trong bài viết theo <a href="' + web_link + 'wp-admin/admin.php?page=eb-config&tab=cache&support_tab=cf_replace_content_full" target="_blank">cấu hình website</a> (nếu có).</label>\
		</div>\
		<div class="ebe-fixed-content-style">\
			<input type="checkbox" id="click_remove_content_style" />\
			<label for="click_remove_content_style">Loại bỏ toàn bộ các style tĩnh để chuẩn hóa style cho bài viết theo một thiết kế chung.</label>\
		</div>\
		<div class="ebe-fixed-content-style">\
			<input type="checkbox" id="click_convert_table_tags" />\
			<label for="click_convert_table_tags">Chuyển đổi thẻ TABLE sang thẻ DIV cho nội dung dễ style hơn, tương thích nhiều thiết bị hơn.</label>\
		</div>\
		<div class="ebe-fixed-content-style graycolor small">\
			<input type="checkbox" id="click_remove_table_tags" />\
			<label for="click_remove_table_tags">Loại bỏ toàn bộ các thẻ TABLE để nội dung có thể chạy trên nhiều thiết bị khác như mobile, table... (<em>điều này có thể làm vỡ khối nên không khuyên dùng</em>)</label>\
		</div>\
		<div class="ebe-fixed-content-style graycolor small">\
			<input type="checkbox" id="click_remove_custom_tags" />\
			<label for="click_remove_custom_tags">Loại bỏ toàn bộ các thẻ bất kỳ trong nội dung, phòng trường hợp thẻ nào đó gây lỗi HTML hoặc AMP.</label>\
		</div>\
		<div class="ebe-fixed-content-style">\
			<input type="checkbox" id="click_fixed_a_tags_redirect" />\
			<label for="click_fixed_a_tags_redirect">Xử lý các URL thuộc dạng redirect về non-redirect (thường áp dụng cho web chết, bị lưu trữ trên web.archive.org).</label>\
		</div>\
		<div class="ebe-fixed-content-style">\
			<input type="checkbox" id="click_remove_a_tags" />\
			<label for="click_remove_a_tags">Xóa toàn bộ các thẻ A có trong bài viết (dùng khi copy nội dung từ website khác về mà web đó không phải của mình).</label>\
		</div>\
		<div class="ebe-fixed-content-style">\
			<input type="checkbox" id="click_download_img_other_domain" />\
			<label for="click_download_img_other_domain">Download ảnh từ host khác về host hiện tại (giúp cho ảnh được xử lý với tốc độ tối ưu hơn).</label>\
		</div>');
		
		
		//
//		console.log('aaaaaaaaaaaaaaaaa');
		
		//
		click_replace_content();
		
		// tạo chức năng format nội dung, đưa hết về 1 định dạng chuẩn
		click_remove_style_of_content();
		
		// chuyển thẻ table thành thẻ DIV -> theo tiêu chuẩn riêng của EchBay
		click_convert_table_tags_of_content();
		
		// xóa thẻ table khỏi nội dung -> làm cho nội dung chuẩn hơn trên mobile
		click_remove_table_tags_of_content();
		click_remove_custom_tags_of_content();
		
		// xóa URL dạng redirect
		click_fixed_a_tags_redirect_of_content();
		click_remove_a_tags_of_content();
		
		// download IMG về
		click_download_img_other_domain_of_content();
		
		
		
	})();
	
	
	
	//
	var check_and_set_height_for_img_content = function ( iff_id, default_h, fix_height ) {
		
		// Khung nội dung chính của wordpress thì hạn chế can thiệp vào ảnh -> để người dùng có thể tự ý điều chỉnh size
		if ( typeof fix_height == 'undefined' ) {
			fix_height = 1;
		}
		
		//
		if ( jQuery('#' + iff_id).length > 0 ) {
//			console.log(iff_id);
			
			// xử lý hình ảnh trong nội dung
			jQuery('#' + iff_id).contents().find( 'img' ).each(function() {
				// current style
				var cs = jQuery(this).attr('style') || '',
					// height
					h = jQuery(this).attr('height') || '',
					// width
//					w = jQuery(this).attr('width') || default_h,
					w = jQuery(this).attr('width') || '',
					// ID
					current_id = jQuery(this).attr('id') || '';
				
				if ( cs != '' ) {
					jQuery(this).removeAttr('style').removeAttr('data-mce-src');
				}
				
				// nếu không tìm thấy chiều cao
				if ( h == '' || w == '' ) {
					// reset lại toàn bộ size ảnh
					jQuery(this).removeAttr('width').removeAttr('height').width('auto').height('auto');
//					jQuery(this).removeAttr('width').removeAttr('height');
					
					// tìm chiều cao mặc định
					h = jQuery(this).height() || 0;
					w = jQuery(this).width() || 0;
					
					// khi nào tìm được mới thôi
					if ( h > 0 && w > 0 ) {
						jQuery(this).attr({
//							'width' : Math.ceil( w ) - 1,
							'width' : w.toString().split('.')[0],
//							'height' : Math.ceil( h ) - 1
							'height' : h.toString().split('.')[0]
						});
					}
				}
				// có chiều cao -> set thuộc tính mới luôn
//				else if ( h > default_h && fix_height == 1 ) {
				else if ( h != default_h && fix_height == 1 ) {
					var dh = jQuery(this).attr('data-height') || h,
						dw = jQuery(this).attr('data-width') || w;
					
					
					var nw = dh/ default_h;
//					console.log(nw);
					nw = dw/ nw;
//					console.log(nw);
					
					//
					jQuery(this).attr({
						'data-width' : dw,
						'data-height' : dh,
//						'width' : Math.ceil( nw ),
						'width' : nw.toString().split('.')[0],
						'height' : default_h
					});
				}
				// nếu có chiều cao -> set data mới
				else {
					jQuery(this).attr({
						'data-width' : w,
						'data-height' : h
					});
				}
				
				
				// với phần màu sắc -> nếu chưa có ID -> set ID để điều khiển cho tiện
				if ( iff_id == '_eb_product_list_color_ifr' ) {
					if ( current_id == '' ) {
						// tạo ID ngẫu nhiên để add cho IMG
						current_id = jQuery('#post_ID').val() || '';
						
						if ( current_id != '' ) {
							current_id = '_' + current_id + '_' + Math.random(32).toString().replace('.', '_');
							jQuery(this).attr({
								'id' : current_id
							});
						}
					}
				}
				
				// thêm ALT để hiển thị các ảnh lỗi URL
				var b_alt = jQuery(this).attr('alt') || '',
					b_color = jQuery(this).attr('data-color') || '',
					b_sku = jQuery(this).attr('data-sku') || '',
					b_name = jQuery(this).attr('title') || '',
					b_src = jQuery(this).attr('src') || '';
				
				if ( b_alt == '' ) {
					if ( b_color != '' ) {
						b_alt = b_color;
					}
					else if ( b_sku != '' ) {
						b_alt = b_sku;
					}
					else if ( b_name != '' ) {
						b_alt = b_name;
					}
					else {
						b_alt = b_src;
					}
					console.log('No ALT: ' + b_alt);
					
					$(this).attr({
						'alt' : b_alt
					});
				}
			});
			
			
			// riêng đối với phần list của màu sắc thì chuyển caption sang alt để lấy tên màu cho chuẩn
			if ( iff_id == '_eb_product_list_color_ifr' ) {
//				console.log(iff_id);
				jQuery('#' + iff_id).contents().find( 'dl' ).each(function() {
					var a = jQuery('.wp-caption-dd', this).html() || '',
						b = jQuery('img', this).attr('alt') || '';
//						console.log(a);
					if ( a != '' && a != b ) {
						jQuery('img', this).attr({
							alt: a
						});
						
						console.log('Convert caption to ALT');
					}
				});
				
				// hiệu ứng khi click vào
				jQuery('#' + iff_id).contents().find( 'img' ).off('click').click(function () {
					var jd = jQuery(this).attr('id') || '';
					
					if ( jd == '' ) {
						return false;
					}
					console.log('Edit color #' + jd);
					
					//
					jQuery('.eb-input-edit-product-color') /* .css({
						left : jQuery(this).offset().left + jQuery('#' + iff_id).offset().left
					}) */ .show();
					jQuery('#eb_input_edit_product_color_name').focus();
					
					//
					var f = document.frm_eb_input_edit_product_color;
					
					// các thông số của màu sắc đang chỉnh sửa
					var ten = jQuery(this).attr('alt') || '',
						sku = jQuery(this).attr('data-sku') || '',
						color = jQuery(this).attr('data-color') || '',
						quan = jQuery(this).attr('data-quan') || '',
						price = jQuery(this).attr('data-price') || '',
						size_color = jQuery(this).attr('data-size') || '';
					
					//
					f.eb_input_edit_product_color_id.value = jd;
					f.eb_input_edit_product_color_name.value = ten;
					f.eb_input_edit_product_color_sku.value = sku;
					f.eb_input_edit_product_color_color.value = color;
					f.eb_input_edit_product_color_quan.value = quan;
//					f.eb_input_edit_product_color_price.value = price;
					f.eb_input_edit_product_color_price.value = g_func.money_format( price );
//					jQuery('#oi_input_edit_product_color_price').html( g_func.money_format( price ) );
					f.eb_input_edit_product_size_bycolor_id.value = size_color;
					
					
					// 
					console.log('Add or edit size for color');
					
					// reset lại mảng size
					admin_global_size_color = [];
					
					//
					if ( size_color != '' ) {
						try {
							admin_global_size_color = eval( unescape( size_color ) );
						} catch ( e ) {
							admin_global_size_color = [];
							console.log( WGR_show_try_catch_err( e ) );
						}
					}
					WGR_show_list_size_in_color();
				});
			}
		}
	};
	
	
	
	// chỉnh lại ảnh của phần gallery để nhìn cho dễ
	setInterval(function () {
		check_and_set_height_for_img_content('content_ifr', 300, 0);
		check_and_set_height_for_img_content('_eb_product_gallery_ifr', 120);
		check_and_set_height_for_img_content('_eb_product_list_color_ifr', 90);
	}, 2000);
	
	
	
	
	//
	setTimeout(function () {
		jQuery('#_eb_product_list_color_ifr').height( 250 );
		
		setTimeout(function () {
			jQuery('#_eb_product_list_color_ifr').height( 250 );
			
			setTimeout(function () {
				jQuery('#_eb_product_list_color_ifr').height( 250 );
			}, 2000);
		}, 2000);
		
		
		//
		jQuery('.eb-input-edit-product-color button').off('click').click(function () {
			var a = jQuery(this).attr('data-action') || '';
			
			if ( a == 'save' ) {
				check_eb_input_edit_product_color();
			}
			
			jQuery('.eb-input-edit-product-color').hide();
		});
	}, 2000);
	
	
	
	
	
	// v4
	jQuery('#publish').addClass('publish-position-fixed')
	/*
	.css({
		'position' : 'fixed',
		'bottom': '20px',
		'right' : '20px',
		'z-index' : 99
	})
	*/
	.click(function () {
		EBE_set_default_img_avt();
		EBE_set_default_title_for_seo();
	});
	
	
	// v5
	WGR_check_and_load_submit_v5();
	
	
	
	// thêm nút nhân bản bài viết
//	jQuery('body').append('');
	jQuery('#wgr-for-duplicator').show();
	jQuery('.show-if-duplicator-null div').click(function () {
		jQuery('.show-if-duplicator-null').fadeOut();
	});
	
	//
	jQuery('.click-set-nhanban').click(function () {
		// sử dụng plugin Post duplicator
		if ( dog('duplicator') == null ) {
			jQuery('.show-if-duplicator-null').fadeIn();
			
			setTimeout(function () {
				jQuery('.show-if-duplicator-null').fadeOut();
			}, 5000);
			
			return false;
		}
		
		//
		if ( confirm('Xác nhận nhân bản bài viết này') == false ) {
			return false;
		}
		
		//
		jQuery('#duplicator a').click();
		
		//
		return true;
		
		
		
		
		
		//
		var a = jQuery('#post_ID').val() || 0;
		
		if ( a <= 0 ) {
			alert('Post ID not found!');
			return false;
		}
		
		//
		var uri = web_link + 'duplicate_post?post_id=' + a;
		console.log(uri);
		
		if ( confirm('Xác nhận nhân bản bài viết này') == false ) {
			return false;
		}
		
	});
	
	
	// sau khi nhân bản xong, chuyển sang bài đó luôn
	if ( window.location.href.split('&post-duplicated=').length > 1 ) {
		var a = jQuery('#wpbody-content .updated a').attr('href') || '';
		
		if ( a != '' ) {
			window.location = a;
			return false;
		}
	}
	
	
	
	//
	jQuery(window).on('load', function () {
		EBE_set_default_title_for_seo();
	});
	
	
	
	//
	WGR_check_if_value_this_is_one('_eb_product_chinhhang');
	WGR_check_if_value_this_is_one('_eb_product_noindex');
	
	
	
	//
	jQuery(document).ready(function() {
		if ( dog('_eb_product_ngayhethan') != null ) {
			_global_js_eb.select_date('#_eb_product_ngayhethan');
		}
	});
	
	
	
	
	// riêng cho ads
	WGR_run_for_admin_edit_ads_post( '_eb_ads_for_post' );
	WGR_run_for_admin_edit_ads_post( '_eb_ads_for_category' );
	
	
	
	
	// thêm CSS hiển thị nút add ảnh đại diện
	jQuery('body').append( '<style>div.gallery-add-to-post_avt { display: block; }</style>' );
	
	
	
	
	// chỉnh lại URL ảnh để tránh bị lỗi
	var content_id = '#content_ifr, #_eb_product_gallery_ifr, #_eb_product_list_color_ifr',
		dm = document.domain;
	
	//
	jQuery(window).on('load', function () {
		if ( cf_old_domain != '' ) {
			
			var arr_old_domain = cf_old_domain.replace(/\s/g, '').split(',');
			
			//
			jQuery( content_id ).contents().find( 'img' ).each(function() {
				
				var a = jQuery(this).attr('src') || jQuery(this).attr('data-mce-src') || '';
//				console.log(a);
				
				//
				if ( a != '' ) {
					for ( var i = 0; i < arr_old_domain.length; i++ ) {
						a = a.replace( '//' + arr_old_domain[i] + '/', '//' + dm + '/' );
					}
//					console.log(a);
					
					// nếu đang là URL tương đối -> chuyển sang tuyệt đối vì wp ko hỗ trợ
					if ( a.split('//').length == 1 ) {
						if ( a.substr( 0, 1 ) == '/' ) {
							a = a.substr( 1 );
						}
						a = web_link + a;
//						a = '//' + dm + '/' + a;
						console.log(a);
					}
					
					//
					jQuery(this).attr({
						'data-mce-src' : a,
						src : a
					});
				}
				
			});
			
			//
			var a = jQuery('#_eb_product_avatar').val() || '';
			if ( a != '' ) {
				for ( var i = 0; i < arr_old_domain.length; i++ ) {
					a = a.replace( '//' + arr_old_domain[i] + '/', '//' + dm + '/' );
				}
				
				jQuery('#_eb_product_avatar').val( a );
			}
			
		}
		// chuyển URL ảnh về url tuyệt đối
		else {
			jQuery( content_id ).contents().find( 'img' ).each(function() {
				var a = jQuery(this).attr('src') || jQuery(this).attr('data-mce-src') || '';
//				console.log(a);
				
				//
				if ( a != '' ) {
					
					// nếu đang là URL tương đối -> chuyển sang tuyệt đối ví wp ko hỗ trợ
					if ( a.split('//').length == 1 ) {
						if ( a.substr( 0, 1 ) == '/' ) {
							a = a.substr( 1 );
						}
						a = web_link + a;
//						a = '//' + dm + '/' + a;
						console.log(a);
					}
					
					//
					jQuery(this).attr({
						'data-mce-src' : a,
						src : a
					});
				}
			});
		}
		
		
		
		// tạo các quick menu cho phần edit post
		var str = '';
		jQuery('#normal-sortables .postbox, #advanced-sortables .postbox, #side-sortables .postbox').each(function() {
			var a = jQuery('h2 span', this).html() || '',
				jd = jQuery(this).attr('id') || '',
				cl = jQuery(this).attr('class') || '';
//			console.log(a);
			
			//
			if ( a != '' && jd != '' && jd != 'submitdiv' && cl.split('hide-if-js').length == 1 ) {
				str += '<li data-id="' + jd + '">' + a + '</li>';
			}
		});
		str += '<li>Về đầu trang <i class="fa fa-arrow-up"></i></li>';
//		console.log(str);
		
		//
		jQuery('body').append('<ul class="edit-post-wgr-tab">' + str + '</ul>');
		
		jQuery('.edit-post-wgr-tab li').click(function () {
			var jd = jQuery(this).attr('data-id') || '',
				to_id = 0;
			
			//
			if ( jQuery('.edit-post-layout__content').length > 0 ) {
				if ( jd != '' ) {
					to_id = jQuery('.edit-post-visual-editor').height();
				}
				
				//
				jQuery('.edit-post-layout__content').animate({
					scrollTop: to_id
				}, 600);
			}
			else {
				if ( jd != '' ) {
					to_id = jQuery('#' + jd).offset().top - 50;
					
					jQuery('.postbox h2').removeClass('orgcolor');
					jQuery('#' + jd + ' h2').addClass('orgcolor');
				}
				
				//
				jQuery('body,html').animate({
					scrollTop: to_id
				}, 600);
			}
		});
		
	});
	
	
}


