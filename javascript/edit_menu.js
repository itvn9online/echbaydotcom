// thêm nút để add img, icon vào menu
var add_media_for_menu = '';

function WGR_add_img_to_menu(img) {
    if (img.split('wp-content/uploads/').length > 1) {
        img = 'wp-content/uploads/' + img.split('wp-content/uploads/')[1];
    }

    WGR_event_add_img_edit_menu(add_media_for_menu, 'img', img.replace(web_link, ''));
}

function WGR_auto_check_dispay_option_edit_menu(a) {
    if (dog(a).checked == false) {
        dog(a).checked = true;
    }
}

function WGR_event_add_img_edit_menu(jd, tai, add_lnk) {
    add_media_for_menu = jd;
    //	console.log( add_media_for_menu );

    var a = jQuery('#' + jd).val() || '',
        str = '',
        lnk = '';

    //
    if (typeof add_lnk != 'undefined' && add_lnk != '') {
        lnk = add_lnk;
    } else {
        // v2 -> mở media
        if (tai == 'img') {
            jQuery('#oi_admin_popup').show();

            if (gallery_has_been_load == false) {
                gallery_has_been_load = true;

                ajaxl('gallery', 'oi_admin_popup', 9, function () {
                    // chỉ hiển thị option theo chỉ định
                    jQuery('#oi_admin_popup .eb-newgallery-option .gallery-add-to-menu-edit').show();

                    gallery_has_been_load = false;
                });
            }
        }
        // v1
        else {
            lnk = prompt('Liên kết/ Mã font:', '');

            // kiểm tra nếu ở phần add icon mà người dùng add link -> chuyển sang dạng img luôn
            if (lnk != null) {
                if (lnk.split('//').length > 1) {
                    tai = 'img';
                }
            }
        }

        //
        if (lnk == '' || lnk == null) {
            return false;
        }
    }

    // add
    if (tai == 'icon') {
        if (lnk.split('fa-').length == 1) {
            lnk = 'fa-' + lnk;
        }
        if (lnk.split('fa fa-').length == 1) {
            lnk = 'fa fa-' + lnk;
        }

        str = '<i class="' + lnk.replace('fa-fa-', 'fa-') + '"></i>';
    } else if (tai == 'img') {
        str = '<img src="' + lnk + '" />';
    }
    // edit
    else {
        // nếu không có dữ liệu trước đó -> chuyển sang add
        if (a == '') {
            return WGR_event_add_img_edit_menu(jd, tai.replace('_edit', ''), lnk);
        }

        //
        if ($('#wgr-div-for-edit-html').length == 0) {
            $('body').append('<div id="wgr-div-for-edit-html">' + a + '</div>');
        }

        //
        if (tai == 'icon_edit') {
            str = '<i class="fa fa-' + lnk + '"></i>';
        } else {
            str = '<i class="fa fa-' + lnk + '"></i>';
        }
    }

    //
    if (a != '') {
        var cursorPos = $('#' + jd).attr('data-cursor-pos') || -1;
        cursorPos *= 1;

        // thêm vào cuối
        if (cursorPos < 0) {
            str = a + ' ' + str;
        }
        // thêm vào đầu
        else if (cursorPos == 0) {
            str += ' ' + a;
        }
        // thêm vào giữa
        else {
            str = a.substr(0, cursorPos) + ' ' + str + ' ' + a.substr(cursorPos);
            str = str.replace(/\s\s/g, ' ');
        }
    }
    jQuery('#' + jd).val(str);
}

function WGR_add_img_edit_menu() {
    jQuery('.menu-item-edit-active').each(function () {
        if (jQuery('.wgr-button-menu', this).length == 0) {
            var a = jQuery('.edit-menu-item-title', this).attr('id') || '';
            if (a == '') {
                console.log('edit-menu-item-title ID not found!');
                return false;
            }

            //
            jQuery('.edit-menu-item-title', this).after('<div class="wgr-button-menu">\
				<button type="button" onclick="WGR_event_add_img_edit_menu(\'' + a + '\', \'img\')" data-add="img" data-id="' + a + '" class="button wgr-button-add-img">Add image</button>\
				<button type="button" onclick="WGR_event_add_img_edit_menu(\'' + a + '\', \'icon\')" class="button wgr-button-add-icon">Add icon (font awesome)</button>\
			</div>\
			<div class="wgr-button-menu"><a href="https://fontawesome.com/v4.7.0/icons/" target="_blank" rel="nofollow">List Font Awesome v4</a></div>');


            // xác định vị trí add dữ liệu theo phát bấm chuột của người dùng
            jQuery('.edit-menu-item-title').each(function () {
                var a = $(this).attr('data-wgr-click') || '';

                if (a == '') {
                    $(this).click(function () {
                        var cursorPos = $(this).prop('selectionStart');

                        $(this).attr({
                            'data-cursor-pos': cursorPos
                        });
                    }).attr({
                        'data-wgr-click': 'done'
                    });
                }
            });
        }
    });
}


function WGR_main_edit_menu() {

    // thêm khoảng trống cho chỗ tìm kiếm menu nâng cao
    if ($(window).width() > 1100) {
        $('#wpbody').addClass('pading-for-edit-menu');

        // hiển thị tool dưới dạng fixed cho dễ làm việc
        jQuery('body').addClass('fixed-tool-admin-menu');
    }

    //
    jQuery('#side-sortables ul.outer-border').after(jQuery('#content-for-quick-add-menu').html() || '');

    // khi người dùng bấm thêm vào menu
    jQuery('.click-to-add-custom-link').click(function () {
        var lnk = jQuery(this).attr('data-link') || '#',
            nem = jQuery(this).attr('data-text') || '*',
            tit = jQuery(this).attr('data-title') || '*',
            rel = jQuery(this).attr('data-rel') || '',
            tar = jQuery(this).attr('data-target') || '';
        nem = nem.replace(/\&lt\;/g, '<').replace(/\&gt\;/g, '>');
        jQuery('#custom-menu-item-url').val(lnk);
        jQuery('#custom-menu-item-name').val(nem);
        jQuery('#submit-customlinkdiv').click();
        //		jQuery('#menu-to-edit li:last').click();

        // nếu có class CSS riêng
        var a = jQuery(this).attr('data-css') || '';
        if (a != '') {
            //			console.log( a );
            WGR_auto_check_dispay_option_edit_menu('css-classes-hide');
            WGR_done_add_class_for_custom_link_menu = false;
            WGR_add_class_for_custom_link_menu(lnk, nem, a);
        }

        // rel nofollow
        if (rel != '') {
            WGR_auto_check_dispay_option_edit_menu('xfn-hide');
            WGR_done_add_rel_for_custom_link_menu = false;
            WGR_add_rel_for_custom_link_menu(lnk, nem, rel);
        }

        // nếu là mở trong tab mới
        if (tar != '') {
            WGR_auto_check_dispay_option_edit_menu('link-target-hide');
            WGR_done_add_target_for_custom_link_menu = false;
            WGR_add_target_for_custom_link_menu(lnk, nem, tar);
        }

        // nếu có title -> gắn title
        if (tit != '') {
            WGR_auto_check_dispay_option_edit_menu('title-attribute-hide');
            WGR_done_add_title_for_custom_link_menu = false;
            WGR_add_title_for_custom_link_menu(lnk, nem, tit);
        }
    });


    // tạo menu tìm kiếm bài viết cho phần menu, do tìm kiếm của wp tìm không chính xác
    jQuery('#nav-menus-frame').before('<br><div class="quick-add-menu-to-admin-menu"><input type="text" id="wgr_search_product_in_menu" placeholder="Tìm kiếm Sản phẩm/ Bài viết... để thêm vào menu" class="wgr-search-post-menu" /></div>');

    //		WGR_custom_search_and_add_menu( 1, 'post' );

    // nạp danh sách sản phẩm, tin tức... khi người dùng nhấn vào ô tìm kiếm
    jQuery('#wgr_search_product_in_menu').click(function () {
        if (dog('show_all_list_post_page_menu') == null) {

            // nút đóng
            //			jQuery('#wgr_search_product_in_menu').before('<div><i class="fa fa-close cur click-close-tool-admin-menu d-none"></i></div>');

            // hiệu ứng cho nút đóng
            /*
            jQuery('.click-close-tool-admin-menu').off('click').click(function () {
            	jQuery('body').removeClass('fixed-tool-admin-menu');
            });
            */


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
        //		jQuery('body').addClass('fixed-tool-admin-menu');

        // chỉnh chiều cao cho phần hiển thị menu
        if ($(window).width() > 1100) {
            $('#show_all_list_post_page_menu').height($(window).height() - $('#show_all_list_post_page_menu').offset().top - 25);
        }
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
