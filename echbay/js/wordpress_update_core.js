

if ( document.domain == 'localhost' ) {
	jQuery('.click-connect-to-echbay-update-wp-core, .click-connect-to-echbay-update-el-core').attr({
		href : 'javascript:;'
	}).off('click').click(function () {
		a_lert('Quá trình update chưa được hỗ trợ trên localhost!');
		return false;
	});
}
else {
	jQuery('.click-connect-to-echbay-update-wp-core').attr({
		href : window.location.href.split('&confirm_wp_process=')[0] + '&confirm_wp_process=1'
	});
	
	jQuery('.click-connect-to-echbay-update-el-core').attr({
		href : window.location.href.split('&confirm_el_process=')[0] + '&confirm_el_process=1'
	});
}

//
if ( window.location.href.split('&confirm_wp_process=').length > 1 ) {
	_global_js_eb.change_url_tab( 'confirm_wp_process' );
//	window.history.pushState("", '', window.location.href.split('&confirm_wp_process=')[0]);
}
else if ( window.location.href.split('&confirm_el_process=').length > 1 ) {
	_global_js_eb.change_url_tab( 'confirm_el_process' );
}

