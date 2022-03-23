function WGR_action_create_quick_link_edit_post() {
    if (isQuanly != 1 || top != self) {
        //console.log(Math.random());
        return false;
    }
    //console.log(Math.random());

    var icon_edit = 'fa fa-edit';
    if (echbay_for_flatsome === 1) {
        icon_edit = 'icon-pen-alt-fill';
    }

    //
    setTimeout(function () {

        // chỉnh sửa logo
        jQuery('.web-logo').each(function () {
            var edit_exist = jQuery(this).attr('data-add-edit') || '';

            //
            if (edit_exist == '') {
                jQuery(this).before('<div class="each-setup-goto-edit"><span data-href="' + web_link + 'wp-admin/admin.php?page=eb-config&tab=advanced&support_tab=cf_logo" title="Chỉnh sửa logo" class="click-goto-edit"><i class="' + icon_edit + '"></i></span></div>');
            }

            //
            jQuery(this).attr({
                'data-add-edit': 1
            });
        });

        // sửa địa chỉ
        jQuery('.footer-address .footer-address-company').each(function () {
            var edit_exist = jQuery(this).attr('data-add-edit') || '';

            //
            if (edit_exist == '') {
                jQuery(this).before('<div class="each-setup-goto-edit"><span data-href="' + web_link + 'wp-admin/admin.php?page=eb-config&tab=contact&support_tab=cf_diachi" title="Chỉnh sửa địa chỉ" class="click-goto-edit"><i class="' + icon_edit + '"></i></span></div>');
            }

            //
            jQuery(this).attr({
                'data-add-edit': 1
            });
        });

        // chỉnh sửa menu
        jQuery('.each-to-edit-menu').each(function () {
            var a = jQuery(this).attr('data-id') || 0,
                edit_exist = jQuery(this).attr('data-add-edit') || '';

            //
            if (edit_exist == '') {
                if (a * 1 > 0) {
                    jQuery(this).html('<span data-href="' + web_link + 'wp-admin/nav-menus.php?action=edit&menu=' + a + '" title="Chỉnh sửa menu" class="click-goto-edit"><i class="' + icon_edit + '"></i></span>');
                }
            }

            //
            jQuery(this).attr({
                'data-add-edit': 1
            });
        });

        // sửa phần icon mạng xã hội
        jQuery('#webgiare__top .footer-social, #webgiare__footer .footer-social').each(function () {
            var edit_exist = jQuery(this).attr('data-add-edit') || '';

            //
            if (edit_exist == '') {
                jQuery(this).prepend('<span data-href="' + web_link + 'wp-admin/admin.php?page=eb-config&tab=social&support_tab=cf_facebook_page" title="Chỉnh sửa social URL" class="click-goto-edit"><i class="' + icon_edit + '"></i></span>');
            }

            //
            jQuery(this).attr({
                'data-add-edit': 1
            });
        });

        // ép phần quảng cáo về định dạng q.cáo -> do khi q.cáo liên kết tới post khác thì định dạng này sẽ bị thay đổi -> cần reset lại
        jQuery('.global-ul-load-ads li').attr({
            'data-type': 'ads'
        });

        // hỗ trợ chỉnh sửa bài viết từ trang khách
        jQuery('.echbay-blog li, .global-ul-load-ads li, .quick-edit-content_only').each(function () {
            var a = jQuery(this).attr('data-id') || 0,
                edit_exist = jQuery(this).attr('data-add-edit') || '',
                t = jQuery(this).attr('data-type') || '',
                w = 0,
                h = 0,
                pading_top = 0;

            //
            if (edit_exist == '') {
                //				if (a * 1 > 0 && t == 'ads') {
                if (a * 1 > 0) {
                    //
                    w = jQuery('.ty-le-global', this).width() || jQuery('.ti-le-global', this).width() || 0;
                    if (w * 1 > 0) {
                        w = Math.ceil(w);
                    }

                    // tìm thêm padding top cho phiên bản mới
                    /*
                    pading_top = jQuery('.ty-le-global', this).css('padding-top') || 0;
                    console.log(pading_top);
                    if (pading_top * 1 > 0) {
                        h = Math.ceil(pading_top);
                    }
                    // không có thì mới tìm theo height
                    else {
                    */
                    //h = jQuery('.ty-le-global', this).height() || jQuery('.ti-le-global', this).height() || 0;
                    h = jQuery('.ty-le-global', this).attr('data-show-height') || jQuery('.ti-le-global', this).height() || 0;
                    if (h * 1 > 0) {
                        h = Math.ceil(h);
                    }
                    //}

                    // hiển thị nút sửa và size khung ảnh
                    jQuery(this).prepend('<div class="each-to-edit-ads"><span data-href="' + web_link + 'wp-admin/post.php?post=' + a + '&action=edit" title="Chỉnh sửa bài viết. Kích thước banner: ' + w.toString() + 'x' + h.toString() + '" class="click-goto-edit"><i class="' + icon_edit + '"></i></span></div>');
                }
            }

            //
            jQuery(this).attr({
                'data-add-edit': 1
            });
        });

        // chỉnh sửa taxonomy
        var edit_taxonomy = 'category',
            edit_taxonomy_title = 'Chỉnh sửa Chuyên mục Sản phẩm';
        edit_taxonomy_type = 'post';
        if (typeof switch_taxonomy != 'undefined') {
            edit_taxonomy = switch_taxonomy;

            //
            if (switch_taxonomy == 'blogs') {
                edit_taxonomy_title = 'Chỉnh sửa Danh mục Tin tức';
                edit_taxonomy_type = 'blog';
            } else if (switch_taxonomy == 'post_options') {
                edit_taxonomy_title = 'Chỉnh sửa Thông số Sản phẩm';
            } else if (switch_taxonomy == 'post_tag') {
                edit_taxonomy_title = 'Chỉnh sửa Thẻ Sản phẩm';
            }
        }

        jQuery('.thread-module-name, .blogs-module-name').each(function () {
            var edit_exist = jQuery(this).attr('data-add-edit') || '';

            //
            if (edit_exist == '') {
                jQuery(this).addClass('each-setup-goto-edit').append('<span data-href="' + web_link + 'wp-admin/term.php?taxonomy=' + edit_taxonomy + '&tag_ID=' + cid + '&post_type=' + edit_taxonomy_type + '" title="' + edit_taxonomy_title + '" class="click-goto-edit"><i class="' + icon_edit + '"></i></span>');
            }

            //
            jQuery(this).attr({
                'data-add-edit': 1
            });
        });

        //
        setTimeout(function () {
            jQuery('.click-goto-edit').off('click').click(function () {
                a = jQuery(this).attr('data-href') || '';

                if (a != '') {
                    window.open(a, '_blank');
                }
            });
        }, 600);

        //
        if ($('.breadcrumb-clone-edit-post').length > 0 && $('.btn-clone-edit-post').length == 0) {
            $('body').append('<a href="' + $('.breadcrumb-clone-edit-post:first').attr('href') + '" class="hide-if-mobile btn-clone-edit-post"><i class="' + icon_edit + '"></i></a>');
            $('.breadcrumb-clone-edit-post').removeClass('breadcrumb-clone-edit-post');
        }

    }, 3000);
}
WGR_action_create_quick_link_edit_post();
