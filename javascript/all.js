


/*
* Toàn bộ function dành cho admin sẽ được viết vào đây
*/





//console.log( typeof $ );
//console.log( typeof jQuery );
if ( typeof $ == 'undefined' ) {
	$ = jQuery;
}



// lưu URL cuối cùng mà người dùng đã xem, để lần sau truy cập luôn vào URL này cho tiện
// -> ưu tiên chạy đầu tiên luôn
(function () {
	var l = g_func.getc('wgr_last_url_user_visit');
	
	//
	if ( l == null ) {
		l = '';
	}
//	console.log(l);
	
	//
	if ( g_func.getc('wgr_check_last_user_visit') == null ) {
		
		// khoảng thời gian để chuyển URL cuối cùng
		g_func.setc( 'wgr_check_last_user_visit', 'webgiare.org', 2 * 3600 );
//		return false;
		
		// nếu có URL cũ, và URL mới không phải là edit post
		if ( window.location.href.split('/post.php?post=').length == 1 && l != '' && l != window.location.href ) {
			jQuery('body').css({
				opacity: .1
			});
			
			setTimeout(function () {
				window.location = unescape( l );
			}, 200);
			
			return false;
		}
		
	}
	
	// sau 5 phút thì lưu URL hiện tại lại
	setTimeout(function () {
		g_func.setc( 'wgr_check_last_user_visit', 'webgiare.org', 2 * 3600 );
//		g_func.setc( 'wgr_last_url_user_visit', escape( window.location.href ), 0, 7 );
	}, 5 * 60 * 1000);
})();



function eb_drop_menu(fix_id, select_id) {
//	console.log( select_id );
	if (typeof select_id == 'undefined') {
		select_id = 0;
	}
	else {
		jQuery('#' + fix_id + ' option[value=\'' + select_id + '\']').attr({
			selected: 'selected'
		});
	}
	
	var str = '',
		sl = jQuery('#' + fix_id + ' select').val(),
		sl_name = jQuery('#' + fix_id + ' select').attr('name'),
		search_id = sl_name + Math.random().toString(32) + Math.random().toString(32),
		//
//		sl_html = jQuery('#' + fix_id).html(),
		sl_html = '<input type="hidden" name="' +sl_name+ '" value="' +select_id+ '" />',
		list = '';
	
	search_id = search_id.replace(/\./g, '_');
	str += '<i class="fa fa-caret-down search-select-down"></i>';
	str += '<input type="text" title="T\u00ecm ki\u1ebfm nhanh" id="' + search_id + '" autocomplete="off" />';
	
	jQuery('#' + fix_id + ' option').each(function() {
		var a = jQuery(this).val() || '',
			b = jQuery(this).text(),
			lnk = jQuery(this).attr('data-href') || '',
			level = jQuery(this).attr('data-level') || '0',
			al_show = jQuery(this).attr('data-show') || '0',
			c = g_func.non_mark_seo(a + b),
			slug = jQuery(this).attr('data-slug') || g_func.non_mark_seo(b);
		
		if (lnk == '') {
			lnk = b;
		} else {
			lnk = '<a href="' + lnk + '">' + b + '</a>';
		}
		
		list += '<li title="' + b + '" data-show="' + al_show + '" data-level="' + level + '" data-value="' + a + '" data-key="' + c.replace(/-/g, '') + '" data-slug="' + slug.replace(/-/g, '') + '">' + lnk + '</li>';
	});
	
	list = '<div><ul>' + list + '</ul></div>';
	jQuery('#' + fix_id).html(str + list + sl_html).addClass('search-select-option');
	
	/*
	jQuery('#' + fix_id + ' option[value=\'' + select_id + '\']').attr({
		selected: 'selected'
	});
	*/
	
	var z_index = jQuery('.search-select-option').length + 1;
	jQuery('.search-select-option').each(function() {
		jQuery(this).css({
			'z-index': z_index
		});
		
		z_index--;
	});
	
	jQuery('#' + fix_id + ' li').off('click').click(function() {
		jQuery('#' + fix_id + ' li').removeClass('selected');
		jQuery(this).addClass('selected');
		
		var tit = jQuery(this).attr('title') || '';
		tit = tit.replace(/\s+\s/g, ' ');
		
		jQuery('#' + search_id).attr({
			placeholder: tit
		}).val('');
		
		
		// sử dụng text type thay vì selext box
		jQuery('#' + fix_id + ' input[name=\'' +sl_name+ '\']').val( jQuery(this).attr('data-value') || '' );
		
		//
		/*
		jQuery('#' + fix_id + ' option').removeAttr('selected');
		
		jQuery('#' + fix_id + ' option[value=\'' + (jQuery(this).attr('data-value') || '') + '\']').attr({
			selected: 'selected'
		});
		*/
	});
	
	jQuery('#' + fix_id + ' li[data-value=\'' + sl + '\']').click();
	jQuery('#' + fix_id + ' div').addClass('search-select-scroll');
	
	jQuery(document).mouseup(function(e) {
		var container = jQuery("#" + fix_id + " div");
		if (!container.is(e.target) && container.has(e.target).length === 0) {
			container.hide();
		}
	});
	
	jQuery('#' + search_id).off('click').click(function() {
		jQuery('#' + fix_id + ' div').show()
	}).off('keyup').keyup(function(e) {
		if (e.keyCode == 13) {
			return false;
		} else if (e.keyCode == 27) {
			jQuery("#" + fix_id + " div").hide();
			return false;
		} else if (e.keyCode == 32) {
			jQuery('#' + fix_id + ' div').show();
		}
		
		var key = jQuery(this).val() || '';
		if (key != '') {
			key = g_func.non_mark_seo(key);
			key = key.replace(/[^0-9a-zA-Z]/g, '');
		}
		
		if (key != '') {
			jQuery('#' + fix_id + ' li').hide().each(function() {
				if (a != '') {
					var a = jQuery(this).attr('data-key') || '';
					if (a != '' && a.split(key).length > 1) {
						jQuery(this).show();
					}
				}
			});
			
			jQuery('#' + fix_id + ' li[data-show="1"]').show();
		} else {
			jQuery('#' + fix_id + ' li').show();
		}
		
		if (jQuery('#' + fix_id + ' ul').height() > 250) {
			jQuery('#' + fix_id + ' div').addClass('search-select-scroll');
		} else {
			jQuery('#' + fix_id + ' div').removeClass('search-select-scroll');
		}
	});
}

// chức năng đồng bộ nội dung website theo chuẩn chung của EchBay
/*
function click_remove_style_of_content () {
	
	//
	jQuery('.click_remove_content_style').click(function () {
		
		// hủy check ngay và luôn
		jQuery(this).prop({
			checked : false
		});
		
		//
		var content_id = jQuery(this).attr('data-editer') || 't_noidung';
		
		// tên đầy đủ của text editter
		content_id += 'wysiwyg';
		
		//
		if ( dog( content_id ) == null ) {
			alert('Text editer #' +content_id+ ' not found');
			return false;
		}
		
		// ID cho jQuery
		content_id = '#' + content_id;
		
		//
		if ( confirm('Confirm remove all style in this content!') == false ) {
			return false;
		}
		
		// Các thẻ sẽ loại bỏ các attr gây ảnh hưởng đến style
		var arr = [
			'article',
			'font',
			'span',
			'ul',
			'ol',
			'li',
			'br',
			
			'strong',
			'blockquote',
			'b',
			'u',
			'i',
			'em',
			
			'pre',
			'code',
			'section',
			
			'table',
			'tr',
			'td',
			
			'h1',
			'h2',
			'h3',
			'h4',
			'h5',
			'h6',
			
			'a',
			'p',
			'div'
		];
		
		//
		for ( var i = 0; i < arr.length; i++ ) {
			jQuery( content_id ).contents().find( arr[i] ).removeAttr('style').removeAttr('id').removeAttr('face').removeAttr('dir').removeAttr('size');
		}
		
		
		// Các thẻ sẽ bị loại bỏ khỏi html
		var arr = [
			'figure',
			'figcaption'
		];
		
		//
		for ( var i = 0; i < arr.length; i++ ) {
			jQuery( content_id ).contents().find( arr[i] ).each(function() {
				jQuery(this).before( jQuery(this).html() ).remove();
			});
		}
		
		
		// xử lý riêng với IM
		jQuery( content_id ).contents().find( 'img' ).removeAttr('style');
		
		
		// xử lý riêng với TABLE
		jQuery( content_id ).contents().find( 'table' ).removeAttr('width').attr({
			border : 0,
//			width : '100%',
			cellpadding : 6,
			cellspacing : 0
		}).addClass('table-list');
		
		//
		jQuery( content_id ).contents().find( 'table p' ).each(function () {
			jQuery(this).before( jQuery(this).html() ).remove();
		});
		
		//
		jQuery( content_id ).contents().find( 'td' ).removeAttr('width');
		
		
		// loại bỏ thẻ style nếu có
//		console.log( 1 );
//		console.log( jQuery( content_id ).contents().find( 'style' ).length );
		jQuery( content_id ).contents().find( 'style' ).remove();
	});
}
*/



// chức năng đồng bộ hình ảnh trong nội dung website theo chuẩn chung của EchBay
/*
function click_remove_style_of_img_content () {
	
	//
	jQuery('.click_remove_content_img_style').click(function () {
		
		// hủy check ngay và luôn
		jQuery(this).prop({
			checked : false
		});
		
		//
		var content_id = jQuery(this).attr('data-editer') || 't_noidung';
		
		// tên đầy đủ của text editter
		content_id += 'wysiwyg';
		
		//
		if ( dog( content_id ) == null ) {
			alert('Text editer #' +content_id+ ' not found');
			return false;
		}
		
		// ID cho jQuery
		content_id = '#' + content_id;
		
		//
		if ( confirm('Confirm remove image style in this content!') == false ) {
			return false;
		}
		
		//
		jQuery( content_id ).contents().find( 'img' ).each(function() {
			var a = jQuery(this).attr('src') || '';
			
			if ( a != '' ) {
				jQuery(this).before( '<img src="' +a+ '" />' );
			}
			
			jQuery(this).remove();
		});
	});
}
*/




function create_content_editer(id) {
	var frame_id = id + 'wysiwyg';
	
	// tự hủy nếu không có ID
	if ( dog ( frame_id ) == null ) {
		return false;
	}
	
	// thi thoảng bị lỗi mất scoll hiện tại -> thêm lệnh này để trỏ về đúng vị trí
	var current_scroll_top = window.scrollY || jQuery(window).scrollTop();
	
	// đưa về kích thước chuẩn để tính toán lại
	jQuery('#' + frame_id).height( 300 );
	
	//
	var famre_height = jQuery('#' + frame_id).contents().find('body').height();
	famre_height = $w.number_only(famre_height);
//	console.log(famre_height);
	
	if (isNaN(famre_height) || famre_height < 300) {
		famre_height = 300
	}
//	console.log(famre_height);
	
	jQuery('#' + frame_id).height( famre_height - -80 );
	console.log('Fix editer height #' + frame_id);
	
	click_edit_img_in_content();
	
	//
	window.scroll( 0, current_scroll_top );
}





function click_download_img_in_content() {
	// lấy URL của frm upload hiện tại -> dùng để copy ảnh
	var a = document.frm_multi_upload.action || '',
		b = jQuery('#img_edit_source').val() || '';
//			alert(a);
	
	if ( a == '' ) {
		alert('Không xác định được nguồn xử lý');
		return false;
	}
	
	if ( b == '' ) {
		alert('Không xác định được file nguồn');
		return false;
	}
	
	if ( b.split('//').length == 1 ) {
		alert('Tính năng chỉ áp dụng cho URL tuyệt đối');
		return false;
	}
	
	// lấy nguồn hiện tại để so sánh
	var c = web_link.split('//')[1].split('/')[0];
	if ( c.substr(0, 4) == 'www.' ) {
		c = c.substr(4);
	}
	//alert(c);
	
	// ảnh cần copy phải khác nguồn với site này
	if ( b.split( c ).length > 1 ) {
		alert('Tính năng chỉ hỗ trợ download ảnh từ website khác về');
		return false;
	}
	
	// kiểm tra xem có phải là ảnh không
	var fname = b.split('/').pop().split('?')[0].split('&')[0].split('#')[0].toLowerCase(),
		ftype = fname.split('.').pop();
//				alert(ftype);
	
	// định dạng được phép upload
	switch ( ftype ) {
		case "gif":
		case "jpg":
		case "jpeg":
		case "png":
//					case "swf":
			break;
		
		default:
			alert('Định dạng chưa được hỗ trợ');
			return false;
			break;
	}
	
//				return false;
	
	// tạo link download
	if ( a.split('?').length == 1 ) {
		a += '?';
	} else {
		a += '&';
	}
	
	window.open( a + 'download_img=' + encodeURIComponent( b ) + '&fname=' + fname, 'target_eb_iframe' );
}




function click_edit_img_in_content() {
	if ( dog('t_noidungwysiwyg') != null ) {
		
		
		var rand_img_a_id = function ( t ) {
			return t + '-for-contant-' + pid + '_' + Math.random().toString(32).substr(3, 4);
		};
		
		// chỉnh sửa URL
		jQuery("#t_noidungwysiwyg").contents().find('a').off('click').click(function() {
			var x = jQuery(this).offset().left + jQuery("#t_noidungwysiwyg").offset().left,
				y = jQuery(this).offset().top + jQuery("#t_noidungwysiwyg").offset().top + 20,
				jd = jQuery(this).attr('id') || '';
//			console.log(x);
//			console.log(y);
			
			//
			if (jd == '') {
				jd = rand_img_a_id('url');
				jQuery(this).attr({
					id : jd
				});
			}
			
			//
			jQuery('.img-click-edit-img').show().css({
				left : x + 'px',
				top : y + 'px',
			}).attr({
				'data-process' : jd
			}).off('click').click(function () {
				jQuery(this).fadeOut();
//				console.log(jQuery(this).attr('data-process'));
				
//				jQuery('img#' + jQuery(this).attr('data-process') ).dblclick();
				jQuery("#t_noidungwysiwyg").contents().find( 'a#' + jQuery(this).attr('data-process') ).dblclick();
			}).html('Chỉnh sửa URL <i class="fa fa-link"></i>');
		}).off('dblclick').dblclick(function() {
			// Nếu trong thẻ a này có thẻ IMG -> tắt chức năng sửa URL đi
			if ( jQuery('img', this).length > 0 ) {
//				jQuery(this).off('dblclick');
//				return false;
			}
			
			//
			var a = jQuery(this).attr('title') || '',
				tex = jQuery(this).html() || '',
				jd = jQuery(this).attr('id') || '',
				lnk = jQuery(this).attr('href') || '',
				tar = jQuery(this).attr('target') || '',
				ren = jQuery(this).attr('rel') || '',
				s = window.scrollY || jQuery(window).scrollTop(),
				edit_frm = jQuery('.admin-edit-url');
			
			edit_frm.show();
			
			var l = jQuery(window).width() - edit_frm.width(),
				t = jQuery(window).height() - edit_frm.height();
			l = l / 2;
			t = t / 5;
			
			edit_frm.show().css({
				top: (t + s) + 'px',
				left: l + 'px'
			});
			
			if (jd == '') {
				jd = rand_img_a_id('url');
				jQuery(this).attr({
					id: jd
				});
			}
			
			//
			jQuery('#editer_url_edit_url').val(lnk);
			jQuery('#editer_url_edit_title').val(a);
			jQuery('#editer_url_edit_text').val(tex);
			
			//
			jQuery('#editer_url_edit_target option:first, #editer_url_edit_rel option:first').prop({
				selected : true
			});
			if ( tar != '' ) {
				jQuery('#editer_url_edit_target option[value="' +tar+ '"]').prop({
					selected : true
				});
			}
			if ( ren != '' ) {
				jQuery('#editer_url_edit_rel option[value="' +ren+ '"]').prop({
					selected : true
				});
			}
			
			//
			edit_frm.find('.img_edit_ok').off('click').click(function() {
				var arr_attr = {
					href: jQuery('#editer_url_edit_url').val()
				};
				
				//
				var tit = jQuery('#editer_url_edit_title').val() || '',
					tar = jQuery('#editer_url_edit_target').val() || '',
					rel = jQuery('#editer_url_edit_rel').val() || '';
				
				//
				if ( tit != '' ) {
					arr_attr['title'] = tit;
				}
				
				//
				if ( tar != '' ) {
					arr_attr['target'] = tar;
				}
				
				//
				if ( rel != '' ) {
					arr_attr['rel'] = rel;
				}
				
				jQuery("#t_noidungwysiwyg").contents().find('a#' + jd).removeAttr('title').removeAttr('target').removeAttr('rel').attr(arr_attr).html( jQuery('#editer_url_edit_text').val() || '' );
				
				jQuery('.admin-edit-url .img_edit_cancel').click();
				
				click_edit_img_in_content();
			});
		});
		
		// chỉnh sửa ảnh
		jQuery("#t_noidungwysiwyg").contents().find('img').off('click').click(function() {
			var x = jQuery(this).offset().left + jQuery("#t_noidungwysiwyg").offset().left,
				y = jQuery(this).offset().top + jQuery("#t_noidungwysiwyg").offset().top + jQuery(this).height()/ 2,
				jd = jQuery(this).attr('id') || '';
//			console.log(x);
//			console.log(y);
			
			//
			if (jd == '') {
				jd = rand_img_a_id('img');
				jQuery(this).attr({
					id : jd
				});
			}
			
			//
			jQuery('.img-click-edit-img').show().css({
				left : x + 'px',
				top : y + 'px',
			}).attr({
				'data-process' : jd
			}).off('click').click(function () {
				jQuery(this).fadeOut();
//				console.log(jQuery(this).attr('data-process'));
				
//				jQuery('img#' + jQuery(this).attr('data-process') ).dblclick();
				jQuery("#t_noidungwysiwyg").contents().find( 'img#' + jQuery(this).attr('data-process') ).dblclick();
			}).html('Chỉnh sửa ảnh <i class="fa fa-image"></i>');
		}).off('dblclick').dblclick(function() {
			var a = jQuery(this).attr('alt') || '',
				jd = jQuery(this).attr('id') || '',
				img = jQuery(this).attr('src') || '',
				wit = jQuery(this).width(),
				hai = jQuery(this).height(),
				s = window.scrollY || jQuery(window).scrollTop(),
				edit_frm = jQuery('.img-edit-img');
			
			edit_frm.show();
			
			var l = jQuery(window).width() - edit_frm.width(),
				t = jQuery(window).height() - edit_frm.height();
			l = l / 2;
			t = t / 5;
			
			edit_frm.show().css({
				top: (t + s) + 'px',
				left: l + 'px'
			});
			
			if (a == '') {
				if ( typeof document.frm_admin_edit_content != 'undefined' ) {
					a = document.frm_admin_edit_content.t_tieude.value;
				}
				else if ( typeof document.frm_thread_add != 'undefined' ) {
					a = document.frm_thread_add.t_tieude.value;
				}
			}
			
			if (jd == '') {
				jd = rand_img_a_id('img');
				jQuery(this).attr({
					id: jd
				});
			}
			
			// v2
			edit_frm.find('input[name="t_source"]').val( img );
			edit_frm.find('input[name="t_description"]').val( a ).focus();
			edit_frm.find('input[name="t_with"]').val( wit );
			edit_frm.find('input[name="t_height"]').val( hai );
			
			// v1
			/*
			jQuery('#img_edit_source').val(img);
			jQuery('#img_edit_description').val(a).focus();
			jQuery('#img_edit_width').val(wit);
			jQuery('#img_edit_height').val(hai);
			*/
			
			//
			edit_frm.find('.img_edit_ok').off('click').click(function() {
				var arr_attr = {
					src: jQuery('#img_edit_source').val(),
					alt: jQuery('#img_edit_description').val()
				};
				
				//
				var a = jQuery("#t_noidungwysiwyg").contents().find('img#' + jd);
				
				//
				a.removeAttr('width').removeAttr('height').attr(arr_attr).width('auto').height('auto');
				
				var wit = jQuery('#img_edit_width').val() || '',
					hai = jQuery('#img_edit_height').val() || '';
				
				if (wit != '') {
					wit = $w.number_only(wit);
					if (wit > 0) {
						a.width(wit);
//						arr_attr['width'] = wit;
					}
				}
				
				if (hai != '') {
					hai = $w.number_only(hai);
					if (hai > 0) {
						a.height(hai);
//						arr_attr['height'] = hai;
					}
				}
				
				jQuery('.img-edit-img .img_edit_cancel').click();
				
				click_edit_img_in_content();
			});
		});
		
		//
		jQuery('#img_edit_width, #img_edit_height').off('click').click(function() {
			jQuery(this).select();
		});
		
		//
		jQuery('.img-edit-img .img_edit_cancel').off('click').click(function() {
			jQuery('.img-edit-img').hide();
		});
		
		//
		jQuery('.admin-edit-url .img_edit_cancel').off('click').click(function() {
			jQuery('.admin-edit-url').hide();
		});
		
		//
		jQuery('.img_edit_downoad').off('click').click(function() {
			// lấy URL của frm upload hiện tại -> dùng để copy ảnh
			var a = document.frm_multi_upload.action || '',
				b = jQuery('#img_edit_source').val() || '';
//			alert(a);
			
			if ( a != '' && b != '' ) {
				// lấy nguồn hiện tại để so sánh
				var c = web_link.split('//')[1].split('/')[0];
				if ( c.substr(0, 4) == 'www.' ) {
					c = c.substr(4);
				}
				//alert(c);
				
				// ảnh cần copy phải khác nguồn với site này
				if ( b.split( c ).length > 1 ) {
					alert('Tính năng chỉ hỗ trợ download ảnh từ website khác về');
					return false;
				}
				
				// kiểm tra xem có phải là ảnh không
				var fname = b.split('/').pop().split('?')[0].split('&')[0].split('#')[0].toLowerCase(),
					ftype = fname.split('.').pop();
//				alert(ftype);
				
				// định dạng được phép upload
				switch ( ftype ) {
					case "gif":
					case "jpg":
					case "jpeg":
					case "png":
//					case "swf":
						break;
					
					default:
						alert('Định dạng chưa được hỗ trợ');
						return false;
						break;
				}
				
//				return false;
				
				// tạo link download
				if ( a.split('?').length == 1 ) {
					a += '?';
				} else {
					a += '&';
				}
				
				window.open( a + 'download_img=' + encodeURIComponent( b ) + '&fname=' + fname, 'target_eb_iframe' );
			}
		});
		
		//
		jQuery(document).mouseup(function(e) {
			var container = jQuery(".img-edit-img");
			if (!container.is(e.target) && container.has(e.target).length === 0) {
				container.hide();
			}
		});
	}
}




function fix_textarea_height() {
	jQuery('.fix-textarea-height textarea, textarea.fix-textarea-height').each(function() {
		var a = jQuery(this).attr('data-resize') || '',
			min_height = jQuery(this).attr('data-min-height') || 60,
			add_height = jQuery(this).attr('data-add-height') || 20;
//		console.log(min_height);
		
		if (a == '') {
			jQuery(this).height(20);
			
			//
			var new_height = jQuery(this).get(0).scrollHeight || 0;
			new_height -= 0 - add_height;
			if (new_height < min_height) {
				new_height = min_height;
			}
			
			//
			jQuery(this).height(new_height);
			
			//
			console.log('Fix textarea height #' + ( jQuery(this).attr('name') || jQuery(this).attr('id') || 'NULL' ) );
		}
	}).off('click').click(function() {
		fix_textarea_height()
	}).off('change').change(function() {
		fix_textarea_height()
	});
}





/*
* Định vị vị trí trụ sở chính của website
*/
function create_img_gg_map ( latlon, inner_now ) {
	if ( typeof latlon == 'undefined' || latlon == '' ) {
		return '';
	}
	
	var wit = jQuery('#mapholder').width() || 400;
	if ( wit > 640 ) {
		wit = 640;
	}
	latlon = latlon.replace( ';', ',' );
	
	// Bản đồ trực tuyến
	var site = 'https://www.google.com/maps/@' + latlon + ',15z';
//	var site = 'https://maps.google.com/maps?sspn=' + latlon + '&t=h&hnear=London,+United+Kingdom&z=15&output=embed';
//	console.log(site);
	
	// URL only
	var str = '<a title="' +site+ '" href="' +site+ '" rel="nofollow" target="_blank">' +site+ '</a>';
	
//	str += '<br><img src="https://maps.googleapis.com/maps/api/staticmap?center=' + latlon + '&zoom=14&size=' + wit + 'x300" />';
	
	//
	if ( typeof inner_now != 'undefined' && inner_now == 1 && dog('mapholder') != null ) {
		dog( 'mapholder', str );
	}
	
	//
	return str;
	
	//
	/*
	var img = '//maps.googleapis.com/maps/api/staticmap?center=' + latlon + '&zoom=14&size=' + wit + 'x300&sensor=true';
	
	// iframe img
//	return '<iframe src="' +img+ '" width="' +wit+ '" height="300"></iframe>';
	
	// url and img
	return '<a title="' +site+ '" href="' +site+ '" rel="nofollow" target="_blank" class="d-block"><img src="' +img+ '" /></a>';
	*/
}


//
function WGR_after_load_user_location ( data ) {
	if ( cf_tester_mode == 1 ) console.log(data);
	
	//
	var f = document.frm_config;
	
	//
	if ( f.cf_region.value == '' && typeof data.country != 'undefined' ) {
		f.cf_region.value = data.country;
	}
	
	if ( f.cf_placename.value == '' && typeof data.region != 'undefined' ) {
		f.cf_placename.value = data.region;
	}
	
	if ( f.cf_position.value == '' && typeof data.loc != 'undefined' ) {
		f.cf_position.value = data.loc;
		
		//
		create_img_gg_map ( f.cf_position.value, 1 );
	}
}

function auto_get_user_position ( current_position ) {
	if ( typeof document.frm_config == 'undefined' ) {
		console.log('frm_config not found');
		return false;
	}
	
	if ( dog('mapholder') == null ) {
		console.log('mapholder not found');
		return false;
	}
	
	
	// V2
	_global_js_eb.user_loc( ( typeof current_position == 'number' ) ? current_position : 0, function ( data ) {
		WGR_after_load_user_location( data );
	});
	
	// TEST
//	_global_js_eb.user_auto_loc();
	
	//
//	return false;
}


// V1
function auto_get_user_position_v1 ( current_position ) {
	
	//
	var f = document.frm_config;
	
	/*
	if ( f.cf_content_language.value == '' ) {
		f.cf_content_language.value = navigator.userLanguage || navigator.language || '';
	}
	*/
	
	
	// lấy vị trí chính xác
	if ( typeof current_position != 'undefined' ) {
		navigator.geolocation.getCurrentPosition( function ( position ) {
			var lat = position.coords.latitude,
				lon = position.coords.longitude;
//			console.log( lat );
//			console.log( lon );
			
			//
			jQuery('input[name=cf_position]').val( lat+ ';' +lon );
			
			//
			create_img_gg_map ( lat+ ',' +lon, 1 );
		}, function () {
			console.log( 'Not get user Position' );
		}, {
			timeout : 10000
		});
		
		return false;
	}
	
	
	// lấy vị trí gần đúng
	if ( f.cf_region.value == '' || f.cf_placename.value == '' || f.cf_position.value == '' ) {
//		console.log( window.location.protocol );
		var url_get_ip_info = window.location.protocol + '//ipinfo.io';
		if ( typeof client_ip != 'undefined' && client_ip != '' ) {
			url_get_ip_info += '/' + client_ip;
		}
		console.log( url_get_ip_info );
		
		//
		jQuery.getJSON( url_get_ip_info, function(data) {
//			console.log( data ); return;
			
			if ( f.cf_region.value == '' && typeof data['country'] != 'undefined' ) {
				f.cf_region.value = data['country'];
			}
			
			if ( f.cf_placename.value == '' && typeof data['region'] != 'undefined' ) {
				f.cf_placename.value = data['region'];
			}
			
			if ( f.cf_position.value == '' && typeof data['loc'] != 'undefined' ) {
				f.cf_position.value = data['loc'];
				
				//
				create_img_gg_map ( f.cf_position.value, 1 );
			}
		});
	}
}

function checkFlashT(img) {
	img = img.split('.');
	img = img[img.length - 1];
	
	//
	if (img == 'swf') {
		return true;
	}
	
	//
	return false;
}


function insertPictureContent(image) {
	if (checkFlashT(image) == true) {
		alert('Không hỗ trợ Flash cho mục này');
		return;
	}
	
	//
	if (image.split('//').length == 1) {
		image = web_link.replace('https://', 'http://') + image;
	}
	
	// thi thoảng bị lỗi mất scoll hiện tại -> thêm lệnh này để trỏ về đúng vị trí
	var current_scroll_top = window.scrollY || jQuery(window).scrollTop();
	
	//
	$w.fm('t_noidung', 'img', image);
	
	//
	create_content_editer('t_noidung');
	
	//
	window.scroll( 0, current_scroll_top );
}




function EBA_add_img_to ( img, id ) {
	dog(id).value = img;
}

function EBA_preview_img_logo ( img, id ) {
//	if ( typeof img == 'undefined' || img == '' ) {
	if ( typeof img == 'undefined' ) {
		return false;
	}
	
	//
	if ( jQuery('.' + id).length == 0 ) {
		console.log('.' + id + ' not found');
		return false;
	}
	
	//
	if ( img != '' && img.split('//').length == 1 ) {
		if ( img.substr( 0, 1 ) == '/' ) {
			img = img.substr(1);
		}
		img = web_link + img;
	}
	
	jQuery('.' + id).css({
		'background-image' : 'url(\'' + img + '\')'
	});
	
	// cuộn đến cuối của ô thêm ảnh
	window.scroll( 0, jQuery('.' + id).offset().top - jQuery(window).height() + 90 );
}

function EBA_add_img_logo ( img, id, set_full_link ) {
	console.log(img);
	if ( typeof set_full_link == 'undefined' || set_full_link == false || set_full_link == 0 ) {
		img = img.replace( web_link, '' );
	}
	EBA_add_img_to( img, id );
	EBA_preview_img_logo( img, id );
}


// phần thiết lập thông số của size -> chỉnh về 1 định dạng
function convert_size_to_one_format () {
	jQuery('.fixed-size-for-config').off('change').change(function () {
		var a = jQuery(this).val() || '';
		if ( a != '' ) {
			a = a.replace( /\s/g, '' );
			
			// kích thước tự động thì cũng bỏ qua luôn
			if ( a == 'auto' || a == 'full' ) {
			}
			else {
				// nếu có dấu x -> chuyển về định dạng của Cao/ Rộng
				if ( a.split('x').length > 1 ) {
					a = a.split( 'x' );
					
					if ( a[0] == a[1] ) {
						a = 1;
					}
					else {
						a = a[1] + '/' + a[0];
					}
				}
				a = a.toString().replace(/[^0-9\/]/g, '');
			}
			
			jQuery(this).val( a );
		}
	}).off('blur').blur(function () {
		jQuery(this).change();
	});
	
	
	jQuery('.fixed-width-for-config').off('change').change(function () {
		var a = jQuery(this).val() || '';
		if ( a != '' ) {
			a = a.replace( /\s/g, '' );
			
			if ( a != '' ) {
				a = a * 1;
				
				// nếu giá trị nhập vào nhỏ hơn 10 -> tính toán tự động số sản phẩm trên hàng theo kích thước tiêu chuẩn
				if ( a < 10 ) {
					// lấy kích thước tiêu chuẩn
					var b = jQuery(this).attr('data-width') || '';
					if ( b != '' ) {
						// tính toán
						jQuery(this).val( Math.ceil( b/ a ) - 5 );
					}
				}
			}
		}
	}).off('blur').blur(function () {
		jQuery(this).change();
	});
}

function WGR_widget_show_option_by_post_type ( select_id ) {
	
//	console.log(select_id);
	
	var a = jQuery('.' + select_id + ' .ebe-post-type').val() || '';
//	console.log( a );
	
	jQuery('.' + select_id).attr({
		'data-type': a
	});
	
	//
	jQuery('.' + select_id + ' .ebe-post-type').change(function () {
		var a = jQuery(this).val() || '';
		jQuery('.' + select_id).attr({
			'data-type': a
		});
	});
	
	
	// thêm BBCode cho title
	jQuery('.click-set-bbcode-to-title').attr({
		href: 'javascript:;'
	}).click(function() {
		var set = jQuery(this).attr('data-set') || '',
			tag = jQuery(this).attr('data-tag') || '',
			a = jQuery('.' + set + ' input');
		
		//
		a.val( '[' + tag + ']' + a.val().replace( '[' + tag + ']', '' ).replace( '[/' + tag + ']', '' ) + '[/' + tag + ']' );
	});
	
}



// chức năng đồng bộ nội dung website theo chuẩn chung của EchBay
function click_remove_style_of_content () {
	
	//
	if ( jQuery('#click_remove_content_style').length == 0 ) {
		console.log('click_remove_content_style not found');
		return false;
	}
	
	//
	jQuery('#click_remove_content_style').click(function () {
		
		// hủy check ngay và luôn
		/*
		jQuery(this).prop({
			checked : false
		});
		*/
		dog('click_remove_content_style').checked = false;
		
		//
		var content_id = jQuery(this).attr('data-editer') || 'content_ifr';
		
		// tên đầy đủ của text editter
//		content_id += 'wysiwyg';
		
		//
		if ( dog( content_id ) == null ) {
			alert('Text editer #' +content_id+ ' not found');
			return false;
		}
		
		// ID cho jQuery
		content_id = '#' + content_id;
		
		//
		if ( confirm('Confirm remove all style in this content!') == false ) {
			return false;
		}
		
		// Các thẻ sẽ loại bỏ các attr gây ảnh hưởng đến style
		var arr = [
			'article',
			'font',
			'span',
			'ul',
			'ol',
			'li',
			'br',
			
			'strong',
			'blockquote',
			'b',
			'u',
			'i',
			'em',
			
			'pre',
			'code',
			'section',
			
			'table',
			'tr',
			'td',
			
			'h1',
			'h2',
			'h3',
			'h4',
			'h5',
			'h6',
			
			'a',
			'p',
			'div'
		];
		console.log('Remove style, id... for tags:');
		console.log(arr);
		
		//
		for ( var i = 0; i < arr.length; i++ ) {
			jQuery( content_id ).contents().find( arr[i] ).removeAttr('style').removeAttr('id').removeAttr('face').removeAttr('dir').removeAttr('size');
		}
		
		
		// Các thẻ sẽ bị loại bỏ khỏi html
		var arr = [
			'font',
			'figure',
			'figcaption'
		];
		
		//
		for ( var i = 0; i < arr.length; i++ ) {
			jQuery( content_id ).contents().find( arr[i] ).each(function() {
				jQuery(this).before( jQuery(this).html() ).remove();
			});
		}
		
		
		// xử lý riêng với IM
		jQuery( content_id ).contents().find( 'img' ).each(function() {
			var a = jQuery(this).attr('alt') || '';
			
			if ( a != '' ) {
				jQuery(this).attr({
					alt : jQuery('#title').val() || ''
				});
			}
		}).removeAttr('style').removeAttr('longdesc');
		
		
		// xử lý riêng với TABLE
		jQuery( content_id ).contents().find( 'table' ).removeAttr('width').attr({
			border : 0,
//			width : '100%',
			cellpadding : 6,
			cellspacing : 0
		}).addClass('table-list');
		
		//
		jQuery( content_id ).contents().find( 'table p' ).each(function () {
			jQuery(this).before( jQuery(this).html() ).remove();
		});
		
		//
		jQuery( content_id ).contents().find( 'td' ).removeAttr('width');
		
		
		// loại bỏ thẻ style nếu có
//		console.log( 1 );
//		console.log( jQuery( content_id ).contents().find( 'style' ).length );
		jQuery( content_id ).contents().find( 'style' ).remove();
		
	});
}



// chức năng đồng bộ hình ảnh trong nội dung website theo chuẩn chung của EchBay
function click_remove_style_of_img_content () {
	
	//
	jQuery('.click_remove_content_img_style').click(function () {
		
		// hủy check ngay và luôn
		jQuery(this).prop({
			checked : false
		});
		
		//
		var content_id = jQuery(this).attr('data-editer') || 'content_ifr';
		
		// tên đầy đủ của text editter
//		content_id += 'wysiwyg';
		
		//
		if ( dog( content_id ) == null ) {
			alert('Text editer #' +content_id+ ' not found');
			return false;
		}
		
		// ID cho jQuery
		content_id = '#' + content_id;
		
		//
		if ( confirm('Confirm remove image style in this content!') == false ) {
			return false;
		}
		
		//
		jQuery( content_id ).contents().find( 'img' ).each(function() {
			var a = jQuery(this).attr('src') || '';
			
			if ( a != '' ) {
				jQuery(this).before( '<img src="' +a+ '" />' );
			}
			
			jQuery(this).remove();
		});
	});
}



// xóa thẻ TABLE trong nội dung bài viết
function click_convert_table_tags_of_content () {
	
	//
	if ( jQuery('#click_convert_table_tags').length == 0 ) {
		console.log('click_convert_table_tags not found');
		return false;
	}
	
	//
	jQuery('#click_convert_table_tags').click(function () {
		
		// hủy check ngay và luôn
		dog('click_convert_table_tags').checked = false;
		
		//
		var content_id = jQuery(this).attr('data-editer') || 'content_ifr';
		
		// tên đầy đủ của text editter
//		content_id += 'wysiwyg';
		
		//
		if ( dog( content_id ) == null ) {
			alert('Text editer #' +content_id+ ' not found');
			return false;
		}
		
		// ID cho jQuery
		content_id = '#' + content_id;
		
		//
		if ( confirm('Confirm convert all TABLE tags to DIV tags in this content!') == false ) {
			return false;
		}
		
		
		// Các thẻ sẽ bị loại bỏ khỏi html
		var max_for = 0;
		var func_for_runc_remove_table = function () {
			// số lần lặp tối đa
			if ( max_for > 50 ) {
				return false;
			}
			max_for++;
			
			//
			console.log('Convert tags:');
			
			//
			var arr = [
				'table'
			];
			console.log(arr);
			for ( var i = 0; i < arr.length; i++ ) {
				jQuery( content_id ).contents().find( arr[i] ).each(function() {
					jQuery(this).before( '<div class="cf wgr-convert-table">' + jQuery(this).html() + '</div>' ).remove();
				});
			}
			
			//
			var arr = [
				'thead',
				'tbody',
				'tfoot'
			];
			console.log(arr);
			for ( var i = 0; i < arr.length; i++ ) {
				jQuery( content_id ).contents().find( arr[i] ).each(function() {
					jQuery(this).before( jQuery(this).html() ).remove();
				});
			}
			
			//
			var arr = [
				'tr'
			];
			console.log(arr);
			for ( var i = 0; i < arr.length; i++ ) {
				jQuery( content_id ).contents().find( arr[i] ).each(function() {
					jQuery(this).before( '<div class="cf wgr-convert-tr">' + jQuery(this).html() + '</div>' ).remove();
				});
			}
			
			//
			var arr = [
				'th',
				'td'
			];
			console.log(arr);
			for ( var i = 0; i < arr.length; i++ ) {
				jQuery( content_id ).contents().find( arr[i] ).each(function() {
					jQuery(this).before( '<div class="lf wgr-convert-td">' + jQuery(this).html() + '</div>' ).remove();
				});
			}
			
			//
			if ( jQuery( content_id ).contents().find( 'table' ).length > 0 ) {
				func_for_runc_remove_table();
			}
		};
		func_for_runc_remove_table();
		
	});
}



// xóa thẻ TABLE trong nội dung bài viết
function click_remove_custom_tags_of_content () {
	
	//
	if ( jQuery('#click_remove_custom_tags').length == 0 ) {
		console.log('click_remove_custom_tags not found');
		return false;
	}
	
	//
	jQuery('#click_remove_custom_tags').click(function () {
		
		// hủy check ngay và luôn
		dog('click_remove_custom_tags').checked = false;
		
		//
		var content_id = jQuery(this).attr('data-editer') || 'content_ifr';
		
		// tên đầy đủ của text editter
//		content_id += 'wysiwyg';
		
		//
		if ( dog( content_id ) == null ) {
			alert('Text editer #' +content_id+ ' not found');
			return false;
		}
		
		// ID cho jQuery
		content_id = '#' + content_id;
		
		//
		var remove_tag = prompt('Enter tag for remove', '');
		if ( remove_tag == null || remove_tag == '' ) {
			return false;
		}
		
		//
		/*
		if ( confirm('Confirm remove all ' + remove_tag + ' tags in this content!') == false ) {
			return false;
		}
		*/
		console.log('Remove tags:');
		console.log(remove_tag);
		
		//
		jQuery( content_id ).contents().find( remove_tag ).each(function() {
			jQuery(this).before( jQuery(this).html() ).remove();
		});
		
	});
	
}

// xóa thẻ TABLE trong nội dung bài viết
function click_remove_table_tags_of_content () {
	
	//
	if ( jQuery('#click_remove_table_tags').length == 0 ) {
		console.log('click_remove_table_tags not found');
		return false;
	}
	
	//
	jQuery('#click_remove_table_tags').click(function () {
		
		// hủy check ngay và luôn
		dog('click_remove_table_tags').checked = false;
		
		//
		var content_id = jQuery(this).attr('data-editer') || 'content_ifr';
		
		// tên đầy đủ của text editter
//		content_id += 'wysiwyg';
		
		//
		if ( dog( content_id ) == null ) {
			alert('Text editer #' +content_id+ ' not found');
			return false;
		}
		
		// ID cho jQuery
		content_id = '#' + content_id;
		
		//
		if ( confirm('Confirm remove all TABLE tags in this content!') == false ) {
			return false;
		}
		
		
		// Các thẻ sẽ bị loại bỏ khỏi html
		var max_for = 0;
		var func_for_runc_remove_table = function () {
			// số lần lặp tối đa
			if ( max_for > 50 ) {
				return false;
			}
			max_for++;
			
			//
			var arr = [
				'table',
				'thead',
				'tbody',
				'tfoot',
				'tr',
				'th',
				'td'
			];
			console.log('Remove tags:');
			console.log(arr);
			
			//
			for ( var i = 0; i < arr.length; i++ ) {
				jQuery( content_id ).contents().find( arr[i] ).each(function() {
					jQuery(this).before( jQuery(this).html() ).remove();
				});
			}
			
			//
			if ( jQuery( content_id ).contents().find( arr[0] ).length > 0 ) {
				func_for_runc_remove_table();
			}
		};
		func_for_runc_remove_table();
		
	});
}



// xóa URL dạng redirect
function click_fixed_a_tags_redirect_of_content () {
	
	//
	if ( jQuery('#click_fixed_a_tags_redirect').length == 0 ) {
		console.log('click_fixed_a_tags_redirect not found');
		return false;
	}
	
	//
	jQuery('#click_fixed_a_tags_redirect').click(function () {
		
		// hủy check ngay và luôn
		dog('click_fixed_a_tags_redirect').checked = false;
		
		//
		var content_id = jQuery(this).attr('data-editer') || 'content_ifr';
		
		// tên đầy đủ của text editter
//		content_id += 'wysiwyg';
		
		//
		if ( dog( content_id ) == null ) {
			alert('Text editer #' +content_id+ ' not found');
			return false;
		}
		
		// ID cho jQuery
		content_id = '#' + content_id;
		
		//
		if ( confirm('Confirm fixed A tags redirect in this content!') == false ) {
			return false;
		}
		
		
		//
		console.log('Remove redirect URL:');
		jQuery( content_id ).contents().find( 'a' ).each(function() {
			var a = jQuery(this).attr('href') || jQuery(this).attr('data-mce-href') || '';
			if ( a != '' ) {
				console.log(a);
				
				//
				a = decodeURIComponent(a).split('://');
//				console.log(a);
				
				// nếu có từ 3 phần từ trở lên -> có xuất hiện URL redirect
				if ( a.length > 2 ) {
					a = 'http://' + a[ a.length - 1 ];
					console.log('New URL: ' + a);
					
					jQuery(this).removeAttr('data-mce-href').removeAttr('href').attr({
						'data-mce-href': a,
						href: a
					}).removeAttr('target').removeAttr('rel');
				}
			}
		});
		/*
		var new_html = jQuery( content_id ).contents().find( 'body' ).html() || jQuery( content_id ).contents().html() || '';
		console.log( new_html );
		*/
		
	});
}



// download IMG
var class_for_download_img_to_site = 'class_for_download_img_to_site';
function run_download_img_other_domain_of_content ( content_id ) {
	
	//
	if ( typeof content_id == 'undefined' || content_id == '' ) {
		content_id = '#content_ifr';
	}
	
	//
	var current_domain = web_link.split('//')[1].split('/')[0] || document.domain;
	console.log(current_domain);
	
	//
	var download_url = '';
	
	// tạo hiệu ứng ẩn cho BODY
	jQuery('body').css({
		opacity: .2
	});
	
	//
	console.log('Download all IMG:');
	jQuery( content_id ).contents().find( 'img' ).removeClass( class_for_download_img_to_site ).each(function() {
		var a = jQuery(this).attr('src') || jQuery(this).attr('data-mce-src') || '';
		if ( a != '' ) {
			console.log(a);
			
			var b = a.split('//')[1].split('/')[0];
			
			// nếu ảnh chưa được download -> kích hoạt chế độ download
			if ( current_domain != b ) {
				console.log(b);
				
				download_url = web_link + 'download_img_to_site/?img=' + encodeURIComponent( a ) + '&for_id_content=' + encodeURIComponent( content_id );
				
				// tìm vào tạo tên file mới
				var file_name = decodeURIComponent(a).split('://');
//				console.log(file_name);
				if ( file_name.length == 3 ) {
					file_name = file_name[2];
				} else {
					file_name = file_name[1];
				}
				file_name = file_name.replace( /\/|\s/g, '-' );
//				console.log(file_name);
				
				download_url += '&file_name=' + file_name;
				
				//
				console.log(download_url);
				
				//
				window.open(download_url, 'target_eb_iframe');
//				jQuery('#target_eb_iframe').on('load', function () {
//					console.log(Math.random());
					
					// xóa ảnh đại diện phụ trợ đi
					var avt = jQuery('#_eb_product_avatar').val() || '';
					if ( avt != '' ) {
						avt = avt.split('//')[1].split('/')[0];
						console.log(avt);
						if ( current_domain != avt ) {
							jQuery('#_eb_product_avatar').val('');
						}
					}
//				});
				
				// add class để chút xong việc sẽ thay src cho ảnh
				jQuery(this).addClass( class_for_download_img_to_site );
				
				// chỉ download mỗi cái 1 lần
				return false;
			}
		}
	});
	/*
	var new_html = jQuery( content_id ).contents().find( 'body' ).html() || jQuery( content_id ).contents().html() || '';
	console.log( new_html );
	*/
	
	// đến đây mà không bị return -> hết ảnh để download -> thông báo thành công
	if ( download_url == '' ) {
		// tạo hiệu ứng ẩn cho BODY
		jQuery('body').css({
			opacity: 1
		});
		
		//
		alert('All done!');
	}
	
}

function finish_download_img_other_domain_of_content ( img, content_id ) {
	console.log(img);
	
	//
	if ( typeof content_id == 'undefined' || content_id == '' ) {
		content_id = '#content_ifr';
	}
	
	jQuery( content_id ).contents().find( 'img.' + class_for_download_img_to_site ).attr({
		'data-mce-src' : img,
		src : img
	}).removeClass( class_for_download_img_to_site );
	
	// tìm và load ảnh tiếp theo
	run_download_img_other_domain_of_content();
	
}

function click_download_img_other_domain_of_content () {
	
	//
	if ( jQuery('#click_download_img_other_domain').length == 0 ) {
		console.log('click_download_img_other_domain not found');
		return false;
	}
	
	//
	jQuery('#click_download_img_other_domain').click(function () {
		
		// hủy check ngay và luôn
		dog('click_download_img_other_domain').checked = false;
		
		//
		var content_id = jQuery(this).attr('data-editer') || 'content_ifr';
		
		// tên đầy đủ của text editter
//		content_id += 'wysiwyg';
		
		//
		if ( dog( content_id ) == null ) {
			alert('Text editer #' +content_id+ ' not found');
			return false;
		}
		
		// ID cho jQuery
		content_id = '#' + content_id;
		
		//
		if ( confirm('Confirm download all IMG to this site!') == false ) {
			return false;
		}
		
		//
		run_download_img_other_domain_of_content( content_id );
		
	});
}



// Tạo ảnh đại diện mặc định nếu chưa có
function EBE_set_default_img_avt () {
	if ( dog('_eb_product_avatar') != null && dog('_eb_product_avatar').value == '' ) {
		// lấy trong nội dung trước
		var a = jQuery('#content_ifr' ).contents().find( 'img:first' ).attr('src') || '';
//		console.log(a);
		
		// nếu không có -> lấy trong gallery
		if ( a == '' ) {
			var a = jQuery('#_eb_product_gallery_ifr' ).contents().find( 'img:first' ).attr('src') || '';
			console.log(a);
		}
		
		if ( a != '' ) {
			dog('_eb_product_avatar').value = a;
			console.log('Set default avt for #_eb_product_avatar');
		}
	}
}



//
function EBE_set_default_title_for_seo () {
	if ( dog('postexcerpt-hide').checked == false ) {
		jQuery('#postexcerpt-hide').click();
		if ( dog('postexcerpt-hide').checked == false ) {
			dog('postexcerpt-hide').checked = true;
		}
	}
	
	//
	var str_title = jQuery.trim( jQuery('#title').val() || '' ),
		tit = '',
		str_excerpt = jQuery.trim( jQuery('#excerpt').val() || '' ),
		des = '',
		des_default = '%%excerpt%%';
	
	//
	if ( str_excerpt == des_default ) {
		str_excerpt = '';
		jQuery('#excerpt').val( '' );
	}
	
	/*
	* Yoast SEO default value
	*/
	if ( jQuery('#yoast_wpseo_title').length > 0 ) {
		tit = jQuery.trim( jQuery('#yoast_wpseo_title').val() || '' );
		
		if ( tit == '' || tit == str_title ) {
			jQuery('#yoast_wpseo_title').val( '%%title%%' );
		}
	}
	else if ( jQuery('#snippet-editor-title').length > 0 ) {
		tit = jQuery('#snippet-editor-title').val() || '';
		
		if ( tit == '' || tit == str_title ) {
			jQuery('#snippet-editor-title').val( '%%title%%' );
		}
	}
	
	if ( jQuery('#yoast_wpseo_metadesc').length > 0 ) {
		des = jQuery.trim( jQuery('#yoast_wpseo_metadesc').val() || '' );
		
		// nếu không có tóm tắt mặc định mà có tóm tắt của yoast -> chuyển sang mặc định
		if ( str_excerpt == '' && des != '' && des != des_default ) {
			jQuery('#excerpt').val( des );
			des = '';
		}
		
		// gán lại dữ liệu
		if ( des == '' || des == str_excerpt ) {
			jQuery('#yoast_wpseo_metadesc').val( des_default );
		}
	}
	else if ( jQuery('#snippet-editor-meta-description').length > 0 ) {
		des = jQuery('#snippet-editor-meta-description').val() || '';
		
//		if ( des == '' && str_excerpt != '' ) {
//			jQuery('#snippet-editor-meta-description').val( str_excerpt );
//		}
		if ( des == '' || des == str_excerpt ) {
			jQuery('#snippet-editor-meta-description').val( des_default );
		}
	}
	console.log(str_excerpt);
	console.log(des);
	
	//
	if ( str_excerpt == '' && des != '' && des != des_default ) {
		jQuery('#excerpt').val( des );
	}
	
	// chuyển đổi các slug sang dạng không dấu, tạo URL thân thiện
	var new_post_name = jQuery('#post_name').val() || '';
	if ( new_post_name != '' ) {
		new_post_name = g_func.non_mark_seo( new_post_name );
		console.log( new_post_name );
		jQuery('#post_name').val( new_post_name );
	}
	
	
	
	// tạo key tìm kiếm dạng tiêu chuẩn riêng của EchBay
	if ( typeof pagenow != 'undefined' && pagenow == 'post'
	&& typeof typenow != 'undefined' && typenow == 'post'
	&& jQuery('#_eb_product_searchkey').length > 0 ) {
		var new_post_title = jQuery('#title').val() || jQuery('#post-title-0').val() || document.post.post_title.value || '';
		if ( new_post_title != '' ) {
			new_post_title = g_func.non_mark_seo( new_post_title );
			if ( new_post_title != '' ) {
				new_post_title = new_post_title.replace( /\-/gi, '' );
				
				//
				console.log( new_post_title );
				jQuery('#_eb_product_searchkey').val( new_post_title );
			}
		}
	}
	
}



function EBE_get_current_wp_module ( s ) {
	var a = '';
	
	// chi tiết bài viết, sửa bài viết
	if ( s.split('/post.php').length > 1 ) {
		a = 'post';
	}
	// thêm bài viết mới
	else if ( s.split('/post-new.php').length > 1 ) {
		a = 'post-new';
	}
	// danh sách post, page, custom post type
	else if ( s.split('/edit.php').length > 1 ) {
		a = 'list';
	}
	// danh sách catgory, tag...
	else if ( s.split('/edit-tags.php').length > 1 ) {
		a = 'cat_list';
	}
	// chi tiết catgory, tag...
	else if ( s.split('/term.php').length > 1 ) {
		a = 'cat_details';
	}
	// thêm tài khoản thành viên
	else if ( s.split('/user-new.php').length > 1 ) {
		a = 'user-new';
	}
	// sửa tài khoản thành viên
	else if ( s.split('/user-edit.php').length > 1 ) {
		a = 'user-edit';
	}
	// không cho người dùng chỉnh sửa kích thước ảnh thumb -> để các câu lệnh dùng thumb sẽ chính xác hơn
	else if ( s.split('/options-media.php').length > 1 ) {
		a = 'media';
	}
	// chuyển rule wordpress sang nginx cho nó mượt
	else if ( s.split('/options-permalink.php').length > 1 ) {
		a = 'permalink';
	}
	// chuyển rule wordpress sang nginx cho nó mượt
	else if ( s.split('/nav-menus.php').length > 1 ) {
		a = 'menu';
	}
//	admin_act == 'menu'
	console.log(a);
	
	return a;
}



function WGR_load_GA_for_admin_tracking () {
	if ( cf_ga_id == '' ) {
		console.log('Admin tracking disable by cf_ga_id is NULL');
		return false;
	}
	
	//
	if ( typeof adminpage == 'undefined' || adminpage == '' ) {
		console.log('adminpage not found!');
		return false;
	}
	
	// các trang được phép tracking
	if ( adminpage == 'toplevel_page_eb-order' ) {
		/*
		var i = g_func.number_only( _global_js_eb._get( 'id' ) );
		
		//
		if ( i <= 0 ) {
			console.log('ID is zero!');
			return false;
		}
		*/
	}
	else {
		console.log('adminpage not support for GA tracking!');
		return false;
	}
	
	//
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	ga('create', cf_ga_id, 'auto');
	ga('require', 'displayfeatures');
	ga('send', 'pageview');
}




//
var time_out_for_set_new_tile = null;
function WGR_show_widget_name_by_title () {
	clearTimeout(time_out_for_set_new_tile);
	
	time_out_for_set_new_tile = setTimeout(function () {
		jQuery('#widgets-right .widget').each(function() {
//			console.log( 'eb-get-widget-title: ' + jQuery('input.eb-get-widget-title', this).length );
			
			if ( jQuery('input.eb-get-widget-title', this).length > 0
			|| jQuery('select.eb-get-widget-category', this).length > 0 ) {
				
				// lấy text để hiển thị ra
				var a = jQuery('input.eb-get-widget-title', this).val() || '';
				
				// nếu không có -> thử lấy theo tên phân nhóm
				if ( a == '' ) {
					a = jQuery('select.eb-get-widget-category', this).val() || 0;
					
					if ( a > 0 ) {
						a = jQuery('select.eb-get-widget-category option[value="' + a + '"]', this).text() || '';
					}
					else {
						a = '';
					}
				}
				
				//
				if ( a != '' ) {
//					console.log( a );
					
//					console.log( 'H3: ' + jQuery('h3 .in-widget-title', this).length );
					jQuery('h3 .in-widget-title', this).html(': ' + a).attr({
						title: a
					});
				}
			}
		});
	}, 800);
}




function WGR_check_if_value_this_is_one ( a ) {
	if ( dog(a) != null && dog(a).value == 1 ) {
		dog(a).checked = true;
		return true;
	}
	return false;
}


function WGR_get_between_12_thang ( t, time_select ) {
	//
	if (typeof time_select == 'undefined') {
		time_select = '';
	}
//	console.log( time_select );
	
	//
	var g = 24 * 3600,
		j = 1,
		a = '',
		str = '',
		sl = '';
	
	// lấy ngày đầu tiên của tháng trước
	for ( var i = 0; i < 366; i++ ) {
//		console.log( _date( 'd', t ) );
		if ( _date( 'd', t ) == '01' ) {
			// chỉ lấy dữ liệu từ 2 tháng trước
			if ( j > 1 ) {
				// trừ thêm 1 ngày để lấy ngày cuối tháng của tháng trước đó
				t -= g;
				
				//
				a = _date( 'Y/m', t ) + '/01&d2=' + _date( 'Y/m/d', t );
//				console.log( a );
				
				//
				sl = '';
				if ( 'between&d1=' + a == time_select ) {
					sl = ' selected="selected"';
				}
				
				//
				str += '<option value="between&d1=' + a + '"' + sl + '>Tháng ' + _date( lang_date_format.replace( /d\/|\/d/, '' ), t ) + '</option>';
			}
			
			//
//			console.log( j );
			j++;
		}
		t -= g;
	}
	
	//
	return str;
}

function WGR_view_by_time_line ( time_lnk, time_select, private_cookie ) {
	
	//
	console.log(time_lnk);
	console.log(time_select);
	console.log(private_cookie);
//	return false;
	
	//
	if ( dog('oi_quick_connect') == null ) {
		console.log('oi_quick_connect not found');
		return false;
	}
	
	//
	if (typeof time_lnk == 'undefined' || time_lnk == '') {
		console.log('time_lnk not found');
		return false;
	}
//	time_lnk += '&d=';
	
	//
	if (typeof private_cookie == 'undefined' || private_cookie == '') {
		private_cookie = 'default_cookie_name_for_time_line';
	}
	
	//
	var arr_quick_connect = {
			all: 'To\u00e0n b\u1ed9 th\u1eddi gian',
			hrs24: '24 gi\u1edd qua',
			today: 'H\u00f4m nay',
			yesterday: 'H\u00f4m qua',
			last7days: '7 ng\u00e0y qua',
			last30days: '30 ng\u00e0y qua',
			thismonth: 'Th\u00e1ng n\u00e0y (' + _date( lang_date_format.replace( /d\/|\/d/, '' ), date_time ) + ')',
			lastmonth: 'Th\u00e1ng tr\u01b0\u1edbc (' + _date( lang_date_format.replace( /d\/|\/d/, '' ), date_time - 24 * 3600 * 30 ) + ')',
			custom_time: 'Tùy chỉnh'
		},
		str = '',
//		click_click_lick_lick = false,
		_get = function(p) {
			return _global_js_eb._get( p );
		},
		/*
		__hide_popup_day_select = function() {
			setTimeout(function() {
				click_click_lick_lick = false;
			}, 200);
			jQuery('#oi_quick_connect .connect-padding').hide();
		},
		*/
		betwwen1 = _get('d1'),
		betwwen2 = _get('d2'),
		sl = '';
	
	//
	if (typeof time_select == 'undefined' || time_select == '') {
		time_select = _get('d');
		
		if (time_select == '') {
			time_select = g_func.getc(private_cookie);
			console.log(time_select);
			if ( time_select == null ) {
				time_select = '';
			}
		}
	}
//	console.log(time_select);
	
	//
	for (var x in arr_quick_connect) {
		/*
		if (x == time_select && dog('oi_time_line_name') != null) {
			dog('oi_time_line_name').value = arr_quick_connect[x];
		}
		*/
		
		//
		sl = '';
		if ( x == time_select ) {
			sl = ' selected="selected"';
		}
		
		//
//		str += '<li><a href="' + time_lnk + x + '">' + arr_quick_connect[x] + '</a></li>';
		str += '<option value="' + x + '"' + sl + '>' + arr_quick_connect[x] + '</option>';
		
		// thêm tùy chỉnh theo 12 thàng gần đây
		if ( x == 'lastmonth' ) {
			str += WGR_get_between_12_thang( date_time, time_select );
		}
	}
	
	//
	jQuery('#oi_quick_connect').html( '<select>' + str + '</select>' );
	
	//
	jQuery('#oi_quick_connect select').off('change').change(function () {
		var a = jQuery(this).val() || '';
		console.log(a);
		
		// nếu là custom time -> hiển thị khung chọn thời gian
		if ( a == 'custom_time' ) {
		}
		// nếu không
		else {
			// nếu là all time -> xóa cookie đi cho đỡ lằng nhằng
			if ( a == 'all' ) {
				g_func.delck(private_cookie);
			}
			// lưu cookie cho phiên này
			else {
				g_func.setc(private_cookie, encodeURIComponent( a ), 0, 7 );
			}
			
			// chuyển đến link cần đến
			setTimeout(function () {
				window.location = time_lnk + '&d=' + a;
			}, 600);
		}
	});
	
	//
	/*
	return false;
	
	
	
	
	//
	if ( betwwen1 != '' && betwwen2 != '' ) {
		dog('oi_time_line_name').value = betwwen1 + ' - ' + betwwen2;
	}
	dog('oi_quick_connect').innerHTML += str;
	if (run_function && typeof run_function == 'function') run_function(arr_quick_connect);
	jQuery('.hode-hide-popup-show-day').hover(function() {
		__hide_popup_day_select()
	});
	jQuery('.click-how-to-hide-day-selected').click(function() {
		__hide_popup_day_select()
	});
	jQuery('#oi_quick_connect').hover(function() {
		if (click_click_lick_lick == false) {
			click_click_lick_lick = true;
			jQuery('#oi_quick_connect .connect-padding').show()
		}
	});
	_global_js_eb.select_date('#oi_input_value_tu_ngay', {
		numberOfMonths: 3,
		defaultDate: '-2m'
	});
	_global_js_eb.select_date('#oi_input_value_den_ngay');
	jQuery('#oi_click_get_show_by_day').click(function() {
		var a = jQuery('#oi_input_value_tu_ngay').val(),
			b = jQuery('#oi_input_value_den_ngay').val();
		if (a != '') {
			if (b == '') {
				b = a
			}
			window.location = web_link + time_lnk + 'between&d1=' + a + '&d2=' + b
		} else {
			alert('Ch\u1ecdn ng\u00e0y th\u00e1ng c\u1ea7n xem')
		}
	});
	*/
}



// công cụ search và add menu riêng, do công cụ của wp tìm không ra
function WGR_load_post_page_for_add_menu ( arr, type, post_name, item_type ) {
	if ( arr.length == 0 ) {
		return false;
	}
	
	//
	if ( typeof item_type == 'undefined' || item_type == '' ) {
		item_type = 'post_type';
	}
	
	//
	jQuery('#show_all_list_post_page_menu ul').append('<li data-show="1"><h4>' + post_name + '</h4></li>');
	
	//
	WGR_load_post_page_for_append_menu ( arr, type, item_type );
}

function WGR_load_post_page_for_append_menu ( arr, type, item_type, child_of ) {
	// Thêm dấu - ở trước tên để phân biệt cha con
	if ( typeof child_of == 'undefined' ) {
		child_of = '';
	}
	
	//
	for ( var i = 0; i < arr.length; i++ ) {
		var a = g_func.non_mark_seo( arr[i].ten );
		a = a.replace(/[^0-9a-zA-Z]/g, '');
		
		//
		jQuery('#show_all_list_post_page_menu ul').append('<li data-key="' + a + '" onclick="WGR_custom_search_and_add_menu(' + arr[i].id + ', \'' + type + '\', \'' + item_type + '\');this.style.display=\'none\';" class="cur">' + child_of + arr[i].ten + '</li>');
		
		//
		if ( typeof arr[i].arr == 'object' && arr[i].arr.length > 0 ) {
			WGR_load_post_page_for_append_menu ( arr[i].arr, type, item_type, child_of + '- ' );
		}
	}
}

function WGR_custom_search_and_add_menu ( post_id, post_type, item_type ) {
	if ( typeof item_type == 'undefined' || item_type == '' ) {
		console.log('item_type not found');
		return false;
	}
	
	//
	var a = web_link + 'get_post_id_for_menu/?by_id=' + post_id + '&by_post_type=' + post_type + '&by_item_type=' + item_type;
	console.log(a);
	
	window.open(a, 'target_eb_iframe');
	
	jQuery('#show_all_list_post_page_menu').css({
		opacity: .1
	});
}

function WGR_finish_search_and_add_menu ( post_id, post_type, post_url, item_type ) {
	console.log(post_id);
	console.log(item_type);
	console.log(post_type);
	console.log(post_url);
	
	// xóa trước khi làm việc
	jQuery('.remove-after-add-menu').remove();
	
	//
	var node_max = jQuery('.menu-item-title input[type="checkbox"]').length || 9999999999;
	
	// lấy vị trí hiện tại của HTML để chuyển hết thành 1 vị trí mới
	var menu_item_checkbox = jQuery('#pagechecklist-most-recent li:last .menu-item-checkbox').attr('name')
								|| jQuery('#postchecklist-most-recent li:last .menu-item-checkbox').attr('name')
								|| jQuery('#blogchecklist-most-recent li:last .menu-item-checkbox').attr('name')
								|| '';
//		console.log(menu_item_checkbox);
	if ( menu_item_checkbox != '' ) {
		menu_item_checkbox = menu_item_checkbox.split(']')[0].split('[')[1];
//		console.log(menu_item_checkbox);
		
		// lấy HTML mẫu của wp để tạo ID
		var accordion_section_content = jQuery('#pagechecklist-most-recent li:last').html()
									|| jQuery('#postchecklist-most-recent li:last').html()
									|| jQuery('#blogchecklist-most-recent li:last').html()
									|| '';
//		console.log(accordion_section_content);
		
		// thay thế toàn bộ vị trí của mảng lên cấp cao nhất có thể
		for ( var i = 0; i < 50; i++ ) {
			accordion_section_content = accordion_section_content.replace( '[' + menu_item_checkbox + ']', '[-' + node_max + ']' );
		}
//		console.log(accordion_section_content);
		
		// TEST
		var post_title = '';
		// cho taxonomy
		if ( item_type == 'taxonomy' ) {
			var button_process = 'submit-taxonomy-' + post_type;
		}
		// cho post
		else {
			var button_process = 'submit-posttype-' + post_type;
		}
		if ( dog(button_process) == null ) {
			console.log('BUTTON [' + button_process + '] for add menu not found');
			return false;
		}
		button_process = jQuery('#' + button_process);
		
		// post
		var for_process = null;
		if ( post_type == 'page' ) {
//			for_process = jQuery('#pagechecklist-most-recent li:last');
			for_process = 'pagechecklist-most-recent';
			
			post_title = WGR_js_get_post_title_by_id ( eb_pages_list, post_id );
		}
		else if ( post_type == 'post' ) {
//			for_process = jQuery('#postchecklist-most-recent li:last');
			for_process = 'postchecklist-most-recent';
			
			post_title = WGR_js_get_post_title_by_id ( eb_posts_list, post_id );
		}
		else if ( post_type == 'blog' ) {
//			for_process = jQuery('#blogchecklist-most-recent li:last');
			for_process = 'blogchecklist-most-recent';
			
			post_title = WGR_js_get_post_title_by_id ( eb_blogs_list, post_id );
		}
		// taxonomy
		else if ( post_type == 'category' ) {
//			for_process = jQuery('#categorychecklist-pop li:last');
			for_process = 'categorychecklist-pop';
			
			post_title = WGR_js_get_post_title_by_id ( eb_site_group, post_id );
		}
		else if ( post_type == 'post_tag' ) {
//			for_process = jQuery('#blogschecklist-pop li:last');
			for_process = 'post_tagchecklist-pop';
			
			post_title = WGR_js_get_post_title_by_id ( eb_tags_group, post_id );
		}
		else if ( post_type == 'post_options' ) {
//			for_process = jQuery('#blogschecklist-pop li:last');
			for_process = 'post_optionschecklist-pop';
			
			post_title = WGR_js_get_post_title_by_id ( eb_options_group, post_id );
		}
		else if ( post_type == 'blogs' ) {
//			for_process = jQuery('#blogschecklist-pop li:last');
			for_process = 'blogschecklist-pop';
			
			post_title = WGR_js_get_post_title_by_id ( eb_blog_group, post_id );
		}
		
		// nếu không tồn tại ID để add -> thoát luôn
		if ( for_process == '' || dog(for_process) == null ) {
			/*
			if ( jQuery('#pagechecklist-most-recent li').length > 0 ) {
				for_process = jQuery('#pagechecklist-most-recent li:last');
			}
			else if ( jQuery('#postchecklist-most-recent li').length > 0 ) {
				for_process = jQuery('#postchecklist-most-recent li:last');
			}
			else if ( jQuery('#blogchecklist-most-recent li').length > 0 ) {
				for_process = jQuery('#blogchecklist-most-recent li:last');
			}
			else {
				*/
				console.log('for_process not found');
				return false;
//			}
		}
		for_process = jQuery('#' + for_process);
		
		//
//		for_process.after('<li class="remove-after-add-menu">' + accordion_section_content + '</li>');
		for_process.append('<li class="remove-after-add-menu">' + accordion_section_content + '</li>');
		
		//
		var add_process = jQuery('.remove-after-add-menu');
		
		//
		add_process.find('input').each(function() {
			var a = jQuery(this).attr('name') || '';
			
			if ( a.split('[menu-item-object]').length > 1 ) {
				jQuery(this).val( post_type );
			}
			/*
			else if ( a.split('[menu-item-object-id]').length > 1 ) {
				jQuery(this).val( post_id ).prop({
					checked : true
				});
			}
			*/
			else if ( a.split('[menu-item-type]').length > 1 ) {
				jQuery(this).val( item_type );
			}
			else if ( a.split('[menu-item-title]').length > 1 ) {
				jQuery(this).val( post_title );
			}
			else if ( a.split('[menu-item-url]').length > 1 ) {
				jQuery(this).val( post_url );
			}
		});

		add_process.find('input[type="checkbox"]').val( post_id ).prop({
			checked : true
		});
				
		// kích hoạt chức năng add menu
		setTimeout(function () {
			button_process.click();
			
			// xong việc thì loại bỏ checkbox này luôn
			/*
			setTimeout(function () {
				jQuery('.remove-after-add-menu').find('input[type="checkbox"]').prop({
					checked : false
				});
				jQuery('.remove-after-add-menu').remove();
			}, 200);
			*/
			
			//
			jQuery('#show_all_list_post_page_menu').css({
				opacity: 1
			});
		}, 200);
		
	}
	else {
		console.log('menu_item_checkbox not found');
	}
	
}

// lấy tiêu đề dựa theo ID
function WGR_js_get_post_title_by_id ( arr, id ) {
	for ( var i = 0; i < arr.length; i++ ) {
		if ( arr[i].id == id ) {
			return arr[i].ten;
			break;
		}
	}
}

// tạo chức năng gõ phím để tìm kiếm bài viết trong menu
function WGR_press_for_search_post_page () {
	
	jQuery('#wgr_search_product_in_menu').off('keyup').keyup(function(e) {
		if (e.keyCode == 13) {
			return false;
		}
		
		var fix_id = 'show_all_list_post_page_menu';
		
		var key = jQuery(this).val() || '';
		if (key != '') {
			key = g_func.non_mark_seo(key);
			key = key.replace(/[^0-9a-zA-Z]/g, '');
		}
		
		if (key != '') {
			jQuery('#' + fix_id + ' li').hide().each(function() {
				if (a != '') {
					var a = jQuery(this).attr('data-key') || '';
					if (a != '' && a.split(key).length > 1) {
						jQuery(this).show();
					}
				}
			});
			
			jQuery('#' + fix_id + ' li[data-show="1"]').show()
		} else {
			jQuery('#' + fix_id + ' li').show()
		}
	});
	
}



// lấy danh sách các nhóm sẽ được hiển thị trong widget home list
function WGR_category_for_home_list ( for_id, set_check ) {
	// tạo check cho các checkbox tương ứng -> load lần đầu
	if ( typeof set_check != 'undefined' && set_check == 1 ) {
		console.log( for_id );
		
		var a = jQuery('#' + for_id + ' input[data-name="' + for_id + '"]').val() || '';
		if ( a != '' ) {
			a = a.split(',');
			
			for ( var i = 0; i < a.length; i++ ) {
				jQuery('#' + for_id + ' input[data-id="' + a[i] + '"]').prop( 'checked', true );
			}
		}
	}
	
	// hiệu ứng cho các lần thay đổi checkbox sau đó
	jQuery('.click-get-category-id-home_list').off('click').click(function () {
		var a = jQuery(this).attr('data-class') || '';
		
		var str = '';
		jQuery('#' + a + ' .click-get-category-id-home_list').each(function() {
			if ( jQuery(this).prop('checked') == true ) {
				str += ',' + jQuery(this).attr('data-id');
			}
		});
		if ( str != '' && str.substr(0, 1) == ',' ) {
			str = str.substr(1);
		}
		
		jQuery('#' + a + ' input[data-name="' + a + '"]').val( str );
	});
}


function WGR_widget_change_class_if_change_category ( animate_id ) {
	jQuery(".eb-get-widget-category").off("change").change(function () {
	});
}

function WGR_widget_change_taxonomy_if_change_category ( animate_id ) {
//	jQuery('#' + animate_id).off("change").change(function () {
	jQuery(".eb-get-widget-category").off("change").change(function () {
//		var a = jQuery('#' + animate_id + ' option:selected').attr("data-taxonomy") || "";
		var a = jQuery("option:selected", this).attr("data-taxonomy") || "",
			b = jQuery(this).attr("id") || "";
		if ( b == "" ) {
			console.log("ID for set taxonomy not found!");
			return false;
		}
		if ( a == "" ) a = "category";
		console.log("Auto set taxonomy #" + a + " for: " + b);
//		jQuery('.' + animate_id).val( a );
		jQuery("." + b).val( a );
		jQuery("." + b + '_span').html( '(' + a + ')' );
	});
}


// thêm custom menu cho code của echbay
var WGR_done_add_class_for_custom_link_menu = false,
	WGR_done_add_rel_for_custom_link_menu = false,
	WGR_done_add_target_for_custom_link_menu = false;

function WGR_add_class_for_custom_link_menu ( lnk, nem, a, i ) {
//	console.log( lnk );
//	console.log( nem );
//	console.log( a );
	
	//
	if ( a == '' ) {
		return false;
	}
	
	//
	if ( typeof i != 'number' ) {
		i = 10;
		
		// ẩn tạm body đi, xong mới hiển thị lại
		jQuery('body').css({
			opacity: .1
		});
	}
	else if ( i < 0 ) {
		jQuery('body').css({
			opacity: 1
		});
	}
	
	// v2
	jQuery('#menu-to-edit li').each(function() {
		var check_lnk = jQuery('.edit-menu-item-url', this).val() || '',
			check_nem = jQuery('.edit-menu-item-title', this).val() || '',
			check_a = jQuery('.edit-menu-item-classes', this).val() || '';
		
		// Kiểm tra xem có đúng với dữ liệu định gán không
		if ( check_a == '' && check_lnk == lnk && check_nem == nem ) {
			// thêm class
			jQuery('.edit-menu-item-classes', this).val( a );
			
			// hiển thị luôn cái LI này ra
			jQuery(this).removeClass('menu-item-edit-inactive').addClass('menu-item-edit-active');
			
			//
			jQuery('body').css({
				opacity: 1
			});
			
			// xác nhận add thành công
			WGR_done_add_class_for_custom_link_menu = true;
		}
	});
	
	//
	if ( WGR_done_add_class_for_custom_link_menu == false ) {
		setTimeout(function () {
			WGR_add_class_for_custom_link_menu( lnk, nem, a, i - 1 );
		}, 600);
	}
	
	//
	/*
	return false;
	
	
	// v1
	// lấy các dữ liệu của thẻ LI cuối cùng
	var check_lnk = jQuery('#menu-to-edit li:last .edit-menu-item-url').val() || '',
		check_nem = jQuery('#menu-to-edit li:last .edit-menu-item-title').val() || '',
		check_a = jQuery('#menu-to-edit li:last .edit-menu-item-classes').val() || '';
	
	// Kiểm tra xem có đúng với dữ liệu định gán không
	if ( check_lnk == lnk && check_nem == nem ) {
		jQuery('#menu-to-edit li:last .edit-menu-item-classes').val( a );
		jQuery('body').css({
			opacity: 1
		});
	}
	// đợi và chạy tiếp
	else {
		setTimeout(function () {
			WGR_add_class_for_custom_link_menu( lnk, nem, a, i - 1 );
		}, 600);
	}
	*/
}


function WGR_add_rel_for_custom_link_menu ( lnk, nem, a, i ) {
//	console.log( lnk );
//	console.log( nem );
//	console.log( a );
	
	//
	if ( a == '' ) {
		return false;
	}
	
	//
	if ( typeof i != 'number' ) {
		i = 10;
		
		// ẩn tạm body đi, xong mới hiển thị lại
		jQuery('body').css({
			opacity: .1
		});
	}
	else if ( i < 0 ) {
		jQuery('body').css({
			opacity: 1
		});
	}
	
	//
	jQuery('#menu-to-edit li').each(function() {
		var check_lnk = jQuery('.edit-menu-item-url', this).val() || '',
			check_nem = jQuery('.edit-menu-item-title', this).val() || '',
			check_a = jQuery('.edit-menu-item-xfn', this).val() || '';
		
		// Kiểm tra xem có đúng với dữ liệu định gán không
		if ( check_a == '' && check_lnk == lnk && check_nem == nem ) {
			// thêm rel
			jQuery('.edit-menu-item-xfn', this).val( a );
			
			// hiển thị luôn cái LI này ra
			jQuery(this).removeClass('menu-item-edit-inactive').addClass('menu-item-edit-active');
			
			//
			jQuery('body').css({
				opacity: 1
			});
			
			// xác nhận add thành công
			WGR_done_add_rel_for_custom_link_menu = true;
		}
	});
	
	//
	if ( WGR_done_add_rel_for_custom_link_menu == false ) {
		setTimeout(function () {
			WGR_add_rel_for_custom_link_menu( lnk, nem, a, i - 1 );
		}, 600);
	}
}


function WGR_add_target_for_custom_link_menu ( lnk, nem, a, i ) {
//	console.log( lnk );
//	console.log( nem );
//	console.log( a );
	
	//
	if ( a == '' ) {
		return false;
	}
	
	//
	if ( typeof i != 'number' ) {
		i = 10;
		
		// ẩn tạm body đi, xong mới hiển thị lại
		jQuery('body').css({
			opacity: .1
		});
	}
	else if ( i < 0 ) {
		jQuery('body').css({
			opacity: 1
		});
	}
	
	//
	jQuery('#menu-to-edit li').each(function() {
		var check_lnk = jQuery('.edit-menu-item-url', this).val() || '',
			check_nem = jQuery('.edit-menu-item-title', this).val() || '',
			check_a = jQuery('.field-link-target input', this).attr('id') || '';
//		console.log( check_a );
		
		// Kiểm tra xem có đúng với dữ liệu định gán không
		if ( check_a != '' && dog(check_a).checked == false && check_lnk == lnk && check_nem == nem ) {
			// thêm target
//			jQuery('#' + check_a, this).val( a );
			dog(check_a).checked = true;
			
			// hiển thị luôn cái LI này ra
			jQuery(this).removeClass('menu-item-edit-inactive').addClass('menu-item-edit-active');
			
			//
			jQuery('body').css({
				opacity: 1
			});
			
			// xác nhận add thành công
			WGR_done_add_target_for_custom_link_menu = true;
		}
	});
	
	//
	if ( WGR_done_add_target_for_custom_link_menu == false ) {
		setTimeout(function () {
			WGR_add_target_for_custom_link_menu( lnk, nem, a, i - 1 );
		}, 600);
	}
}


