

//
WGR_admin_quick_edit_select_menu();
change_update_new_stt();



// chuyển nhóm cha cho 1 nhóm con
jQuery('.click-change-parent-category').off('click').click(function () {
	jQuery('#edit_parent_for_category').show();
	
	//
	var uri = jQuery(this).attr('data-ajax') || '',
		name = jQuery(this).attr('data-name') || '',
		parent = jQuery(this).attr('data-val') || 0,
		f = document.frm_quick_edit_parent;
	
	//
	f.t_uri.value = uri;
	
	jQuery('.edit_parent_for').html(name);
	
	//
	jQuery('.edit_parent_by select').val(parent).off('change').change(function () {
//		a_lert( jQuery(this).val() );
		setTimeout(function () {
//			document.frm_quick_edit_parent.submit();
			WGR_check_quick_edit_parent ();
		}, 200);
	});
	/*
	jQuery('.edit_parent_by select option').removeAttr('selected');
	jQuery('.edit_parent_by select option[value="' + parent + '"]').attr({
		'selected' : 'selected'
	});
	*/
	
	//
	f.t_ant.focus();
});

function WGR_check_quick_edit_parent () {
	var f = document.frm_quick_edit_parent;
	
	WGR_admin_quick_edit_products( 'products', f.t_uri.value, '&new_parent=' + f.t_ant.value );
}




// Chỉnh sửa SEO nhanh
WGR_click_open_quick_edit_seo();




/*
* Thêm nhiều nhóm 1 lúc
*/
eb_drop_menu('oiAnt');

function WGR_check_create_taxonomy () {
	if ( jQuery.trim( jQuery('#t_multi_taxonomy').val() ) == '' ) {
		jQuery('#t_multi_taxonomy').focus();
		alert('Nhập danh sách nhóm cần thêm');
		return false;
	}
	
	return true;
}

function WGR_after_create_taxonomy ( m ) {
	jQuery('#create_taxonomy_result').append( m + '<br>' );
}

jQuery('.click-show-quick-add-taxonomy').off('click').click(function () {
	jQuery('.show-quick-add-taxonomy').toggle('slow');
	jQuery('#t_multi_taxonomy').focus();
});




