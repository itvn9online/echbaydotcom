


//
var show_update_status = '',
	timeout_remove_update_cache_file = null,
	show_done1 = '',
	show_done2 = '';
function update_wgr_plugin_theme_done ( h, done_or_error ) {
	h = h.replace( /uploads\/ebcache\/echbaydotcom-master\//gi, 'echbaydotcom/' );
	h = h.replace( /uploads\/ebcache\/echbaydotcom-main\//gi, 'echbaydotcom/' );
    
	h = h.replace( /uploads\/ebcache\/echbaytwo-master\//gi, 'echbaytwo/' );
	h = h.replace( /uploads\/ebcache\/echbaytwo-main\//gi, 'echbaytwo/' );
    
	h = h.replace( /uploads\/ebcache\/hostingviet-main\//gi, 'hostingviet/' );
    
	jQuery('#' + show_update_status).html( h );
	
	jQuery('body').addClass('wgr-process-update-complete');
	
	jQuery('body,html').animate({
		scrollTop: jQuery(document).height()
	}, 6000, function () {
		setTimeout(function () {
			a_lert('Đang nạp lại bộ nhớ đệm...');
		}, 1000);
		console.log('All done! show page after 15 secondes...');
	});
	
	// kiểm tra xem quá trình cập nhật có thành công không
	if ( typeof done_or_error == 'undefined' ) {
		done_or_error = 0;
	}
	if ( done_or_error > 0 ) {
		show_done1 = 'Xin chúc mừng! quá trình cập nhật mã nguồn thành công...';
		show_done2 = 'Hoàn tất quá trình cập nhật Plugin....';
	}
	else {
		show_done1 = 'Lỗi trong quá trình cập nhật, vui lòng thử lại sau ít phút...';
		show_done2 = show_done1;
	}
	a_lert(show_done1);
	
	jQuery('#target_eb_iframe').removeClass('show-target-echbay');
	
	// xóa file bảo trì sau khi update xong
	clearTimeout( timeout_remove_update_cache_file );
	timeout_remove_update_cache_file = setTimeout(function () {
		// ẩn khung thông báo đang update đi
		jQuery('body').removeClass('wgr-waiting-update-complete').removeClass('ebdesign-no-scroll');
		
		a_lert(show_done2);
		
		window.open( window.location.href.split('&confirm_eb_process=')[0].split('&remove_update_running_file=')[0] + '&remove_update_running_file=1', 'target_eb_iframe' );
		
		console.log('Remove update running file');
	}, 10 * 1000);
}

function update_wgr_plugin_theme_begin ( i ) {
	show_update_status = i;
	jQuery('body').addClass('wgr-waiting-update-complete').addClass('ebdesign-no-scroll');
	
	// hiển thị phần nội dung trong target
	setTimeout(function () {
		jQuery('.click-show-eb-target').click();
	}, 600);
}

function update_wgr_plugin_theme_alert () {
	a_lert('Quá trình Cập nhật này vừa được thực thi, không thể thực hiện lại ngay được!');
}

// cập nhật plugin từ server của EchBay.com
jQuery('.click-connect-to-echbay-update-eb-core').attr({
	target : 'target_eb_iframe',
	href : window.location.href.split('&confirm_eb_process=')[0] + '&confirm_eb_process=1'
}).click(function () {
	
	//
//	alert( document.domain );
	if ( document.domain == 'localhost' ) {
		a_lert('Quá trình update chưa được hỗ trợ trên localhost!');
		return false;
	}
	
	//
	jQuery(this)
	.hide()
	/*
	.attr({
		href : 'javascript:;'
	})
	.off('click').click(function () {
		update_wgr_plugin_theme_alert();
	})
	*/
	;
	
	update_wgr_plugin_theme_begin( 'show_list_wgr_plugin_update' );
});

// cập nhật plugin từ github
jQuery('.click-connect-to-github-update-eb-core').attr({
	target : 'target_eb_iframe',
	href : window.location.href.split('&confirm_eb_process=')[0] + '&confirm_eb_process=1&connect_to=github'
}).click(function () {
	
	//
//	alert( document.domain );
	if ( document.domain == 'localhost' ) {
		a_lert('Quá trình update chưa được hỗ trợ trên localhost!');
		return false;
	}
	
	//
	jQuery(this)
	.hide()
	/*
	.attr({
		href : 'javascript:;'
	})
	.off('click').click(function () {
		update_wgr_plugin_theme_alert();
	})
	*/
	;
	
	update_wgr_plugin_theme_begin( 'show_list_wgr_plugin_update' );
});

// cập nhật theme
jQuery('.click-connect-to-echbay-update-eb-theme').attr({
	target : 'target_eb_iframe',
	href : window.location.href.split('&confirm_eb_process=')[0] + '&confirm_eb_process=1&connect_to=theme'
}).click(function () {
	
	//
//	alert( document.domain );
	if ( document.domain == 'localhost' ) {
		a_lert('Quá trình update chưa được hỗ trợ trên localhost!');
		return false;
	}
	
	//
	jQuery(this)
	.hide()
	/*
	.attr({
		href : 'javascript:;'
	})
	.off('click').click(function () {
		update_wgr_plugin_theme_alert();
	})
	*/
	;
	
	update_wgr_plugin_theme_begin( 'show_list_wgr_theme_update' );
});

//
if ( window.location.href.split('&confirm_eb_process=').length > 1 ) {
	
	// nếu lệnh đang chạy trong iframe -> lệnh update đang chạy -> hiển thị thông báo đang update
	if ( top != self ) {
		parent.update_wgr_plugin_theme_done( jQuery('#get_list_wgr_process_update').html(), jQuery('#eb_core_update_all_done').length || 0 );
	}
	
	//
	_global_js_eb.change_url_tab( 'confirm_eb_process' );
//	window.history.pushState("", '', window.location.href.split('&confirm_eb_process=')[0]);
	
}
// tự chạy chức năng update khi click từ link sang
else if ( window.location.href.split('&click_to_update_core=').length > 1 ) {
	// chức năng này chỉ hoạt động trong cửa sổ cha
	if ( top == self ) {
		setTimeout(function () {
			var a = '',
				b = '',
				c = '';
			
			// update theme
			if ( window.location.href.split('&connect_to=theme').length > 1 ) {
				a = jQuery('.click-connect-to-echbay-update-eb-theme').attr('href') || '';
				c = '.click-connect-to-echbay-update-eb-theme';
				b = 'show_list_wgr_theme_update';
			}
			// update plugin
			else {
				a = jQuery('.click-connect-to-github-update-eb-core').attr('href') || '';
				c = '.click-connect-to-github-update-eb-core';
				b = 'show_list_wgr_plugin_update';
			}
			
			//
			if ( a != '' && a != 'javascript:;' ) {
				window.open( a, 'target_eb_iframe' );
				update_wgr_plugin_theme_begin( b );
				jQuery(c).hide();
			}
			
			//
			_global_js_eb.change_url_tab( 'click_to_update_core' );
		}, 800);
	}
}




