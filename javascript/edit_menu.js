


// thêm nút để add img, icon vào menu
function WGR_event_add_img_edit_menu ( jd, tai, add_lnk ) {
	var a = jQuery('#' + jd).val() || '',
		str = '',
		lnk = '';
	
	//
	if ( typeof add_lnk != 'undefined' && add_lnk != '' ) {
		lnk = add_lnk;
	}
	else {
		lnk = prompt('Liên kết/ Mã font:', '');
		
		//
		if ( lnk == '' ) {
			return false;
		}
	}
	
	// add
	if ( tai == 'icon' ) {
		str = '<i class="fa fa-' + lnk + '"></i>';
	}
	else if ( tai == 'img' ) {
		str = '<img src="' + lnk + '" />';
	}
	// edit
	else {
		// nếu không có dữ liệu trước đó -> chuyển sang add
		if ( a == '' ) {
			return WGR_event_add_img_edit_menu ( jd, tai.replace( '_edit', '' ), lnk );
		}
		
		//
		if ( $('#wgr-div-for-edit-html').length == 0 ) {
			$('body').append('<div id="wgr-div-for-edit-html">' + a + '</div>');
		}
		
		//
		if ( tai == 'icon_edit' ) {
			str = '<i class="fa fa-' + lnk + '"></i>';
		}
		else {
			str = '<i class="fa fa-' + lnk + '"></i>';
		}
	}
	
	//
	if ( a != '' ) {
		str = a + ' ' + str;
	}
	jQuery('#' + jd).val( str );
}

function WGR_add_img_edit_menu () {
	jQuery('.menu-item-edit-active').each(function() {
		if ( jQuery('.wgr-button-menu', this).length == 0 ) {
			var a = jQuery('.edit-menu-item-title', this).attr('id') || '';
			if ( a == '' ) {
				console.log('edit-menu-item-title ID not found!');
				return false;
			}
			
			//
			jQuery('.edit-menu-item-title', this).after('<div class="wgr-button-menu">\
				<button type="button" onclick="WGR_event_add_img_edit_menu(\'' + a + '\', \'img\')" data-add="img" data-id="' + a + '" class="button wgr-button-add-img">Add image</button>\
				<button type="button" onclick="WGR_event_add_img_edit_menu(\'' + a + '\', \'icon\')" class="button wgr-button-add-icon">Add icon (font awesome)</button>\
			</div>\
			<div class="wgr-button-menu"><a href="https://fontawesome.com/v4.7.0/icons/" target="_blank" rel="nofollow">List Font Awesome v4</a></div>');
		}
	});
}




function WGR_main_edit_menu () {

	jQuery('#side-sortables ul.outer-border').after(jQuery('#content-for-quick-add-menu').html() || '');

	// khi người dùng bấm thêm vào menu
	jQuery('.click-to-add-custom-link').click(function () {
		var lnk = jQuery(this).attr('data-link') || '#',
				nem = jQuery(this).attr('data-text') || 'Home',
				rel = jQuery(this).attr('data-rel') || '',
				tar = jQuery(this).attr('data-target') || '';
		jQuery('#custom-menu-item-url').val(lnk);
		jQuery('#custom-menu-item-name').val(nem);
		jQuery('#submit-customlinkdiv').click();
//			jQuery('#menu-to-edit li:last').click();

		// nếu có class CSS riêng
		var a = jQuery(this).attr('data-css') || '';
		if (a != '') {
//				console.log( a );
			WGR_done_add_class_for_custom_link_menu = false;
			WGR_add_class_for_custom_link_menu(lnk, nem, a);
		}

		// rel nofollow
		if (rel != '') {
			WGR_done_add_rel_for_custom_link_menu = false;
			WGR_add_rel_for_custom_link_menu(lnk, nem, rel);
		}

		// nếu là mở trong tab mới
		if (tar != '') {
			WGR_done_add_target_for_custom_link_menu = false;
			WGR_add_target_for_custom_link_menu(lnk, nem, tar);
		}
	});


	// tạo menu tìm kiếm bài viết cho phần menu, do tìm kiếm của wp tìm không chính xác
	jQuery('#nav-menus-frame').before('<br><div class="quick-add-menu-to-admin-menu"><input type="text" id="wgr_search_product_in_menu" placeholder="Tìm kiếm Sản phẩm/ Bài viết... để thêm vào menu" class="wgr-search-post-menu" /></div>');

//		WGR_custom_search_and_add_menu( 1, 'post' );

	// nạp danh sách sản phẩm, tin tức... khi người dùng nhấn vào ô tìm kiếm
	jQuery('#wgr_search_product_in_menu').click(function () {
		if (dog('show_all_list_post_page_menu') == null) {

			// nút đóng
			jQuery('#wgr_search_product_in_menu').before('<div><i class="fa fa-close cur click-close-tool-admin-menu d-none"></i></div>');

			// hiệu ứng cho nút đóng
			jQuery('.click-close-tool-admin-menu').off('click').click(function () {
				jQuery('body').removeClass('fixed-tool-admin-menu');
			});


			// nội dung tìm kiếm
			jQuery('#wgr_search_product_in_menu').after('<p class="orgcolor">* Nhập từ khóa vào ô tìm kiếm để tìm kiếm Sản phẩm, bài viết tin tức, trang tĩnh, chuyên mục, danh mục... sau đó bấm chọn trong danh sách vừa tim được để thêm vào menu.</p><div id="show_all_list_post_page_menu"><ul></ul></div>');

			//
			WGR_load_post_page_for_add_menu(eb_site_group, 'category', 'Chuyên mục sản phẩm', 'taxonomy');
			WGR_load_post_page_for_add_menu(eb_tags_group, 'post_tag', 'Từ khóa sản phẩm', 'taxonomy');
			WGR_load_post_page_for_add_menu(eb_options_group, 'post_options', 'Thông số khác của sản phẩm', 'taxonomy');
			WGR_load_post_page_for_add_menu(eb_blog_group, 'blogs', 'Danh mục tin tức', 'taxonomy');
			//
			WGR_load_post_page_for_add_menu(eb_posts_list, 'post', 'Sản phẩm');
			WGR_load_post_page_for_add_menu(eb_blogs_list, 'blog', 'Tin tức/ Blog');
			WGR_load_post_page_for_add_menu(eb_pages_list, 'page', 'Trang tĩnh');

			//
			WGR_press_for_search_post_page();

		}

		// hiển thị tool dưới dạng fixed cho dễ làm việc
		jQuery('body').addClass('fixed-tool-admin-menu');

	});


	// hiển thị các menu hay dùng
	// hiển thị phần option để người dùng chọn các menu hay dùng
	jQuery(window).on('load', function () {
		setTimeout(function () {
			if (dog('add-blogs-hide').checked == false) {
				jQuery('#add-blogs-hide').click();
			}

			if (dog('add-post-type-blog-hide').checked == false) {
				jQuery('#add-post-type-blog-hide').click();
			}

			/*
			 if ( dog('add-blogs-hide').checked == false || dog('add-post-type-blog-hide').checked == false ) {
			 jQuery('#show-settings-link').click();
			 }
			 */
		}, 800);
	});
	
	
	
	//
	WGR_add_img_edit_menu();
	setInterval(function () {
		WGR_add_img_edit_menu();
	}, 2000);
	
}



