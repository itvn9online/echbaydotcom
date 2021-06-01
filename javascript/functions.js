//
function ___eb_add_conver_string_cart_to_arr_cart(arr) {

    console.log(arr);

    if (typeof arr != "object") {
        try {
            arr = jQuery.parseJSON(unescape(arr));
        } catch (e) {
            console.log(WGR_show_try_catch_err(e));
            arr = '';
        }

        // kiểm tra lại mà vẫn thế -> lỗi
        if (typeof arr != "object") {
            console.log("cart value not found");
            return false;
        }
        console.log(arr);

        //
        //		current_hd_object = arr;
    }

    return arr;

}

// dành cho các chức năng tracking cần lấy email đơn hàng trước
function WGR_get_hoan_tat_user_email() {
    var a = uEmail;

    //
    try {
        var arr = ___eb_add_conver_string_cart_to_arr_cart(current_tv_object);

        //
        if (typeof arr['hd_email'] != 'undefined' && arr['hd_email'] != '') {
            a = arr['hd_email'];
        } else if (typeof arr['hd_dienthoai'] != 'undefined' && arr['hd_dienthoai'] != '') {
            a = g_func.number_only(arr['hd_dienthoai']) + '@' + document.doctype;
        }
    } catch (e) {
        console.log(WGR_show_try_catch_err(e));
    }

    //
    console.log(a);
    return a;
}

// dành cho các chức năng tracking cần lấy giá đơn hàng trước
function WGR_get_hoan_tat_total_price() {
    if (typeof order_total_price != 'undefined' && order_total_price * 1 > 0) {
        order_total_price *= 1;
        console.log('order_total_price:');
        console.log(order_total_price);
        return order_total_price;
    }

    //
    var a = 0;

    //
    try {
        var arr = ___eb_add_conver_string_cart_to_arr_cart(current_hd_object);

        //
        for (var i = 0; i < arr.length; i++) {

            //
            var product_price = arr[i].price;
            // hiển thị giá theo size, color nếu có
            if (typeof arr[i].child_price != 'undefined' && arr[i].child_price != '' && arr[i].child_price * 1 > 0) {
                product_price = arr[i].child_price;
            }

            //
            a += arr[i].quan * product_price;

        }
    } catch (e) {
        console.log(WGR_show_try_catch_err(e));
    }

    //
    console.log('hoan-tat price: ' + a);
    return a;
}

function WGR_format_discount_price(t) {
    if (typeof discount_price != 'undefined' && discount_price != '') {
        if (discount_price.toString().split('%').length > 1) {
            discount_price = discount_price.replace('%', '') * 1;

            if (discount_price > 0) {
                //				t = t - ( t/ 100 * discount_price );
                t = t / 100 * discount_price;
            }
        } else {
            //			discount_price = g_func.float_only(discount_price);
            discount_price *= 1;
            //console.log(discount_price);

            if (discount_price > 0) {
                //t -= discount_price;
                t = discount_price;
            }
        }
        console.log('Calculate discount price: ' + t);

        //return t;
    }

    return 0;
}

function WGR_show_hoan_tat_product_for_gg(arr, arr2) {
    current_hd_object = ___eb_add_conver_string_cart_to_arr_cart(arr);
    current_tv_object = ___eb_add_conver_string_cart_to_arr_cart(arr2);

    //
    if (current_hd_object == false || current_hd_object.length == 0) {
        return false;
    }

    //
    var str = '',
        str2 = '',
        total = 0;

    //
    str += '<br><h3 class="l35 text-center">' + arr_lang_hoan_tat['customer_info'] + '</h3>'
        + '<div class="hoantat-cus-padding l20 gray2bg">'
        + '<div>' + arr_lang_hoan_tat['cart_done_madon'] + ': <strong>' + current_hd_code + '</strong></div>'
        + '<div>' + arr_lang_hoan_tat['cart_done_khachhang'] + ': <strong>' + current_tv_object['hd_ten'] + '</strong></div>'
        + '<div>' + arr_lang_hoan_tat['cart_done_dienthoai'] + ': <strong>' + current_tv_object['hd_dienthoai'] + '</strong></div>'
        + '<div>' + arr_lang_hoan_tat['cart_done_diachi'] + ': <strong>' + current_tv_object['hd_diachi'] + '</strong></div>'
        + '<div>' + arr_lang_hoan_tat['cart_done_ghichu'] + ': <strong>' + current_tv_object['hd_ghichu'] + '</strong></div>'
        + '<br>'
        + '<div>' + arr_lang_hoan_tat['payment_method'] + ': <strong>' + (typeof arr_lang_hoan_tat[current_tv_object['hd_thanhtoan']] != 'undefined' ? arr_lang_hoan_tat[current_tv_object['hd_thanhtoan']] : '<em>NULL</em>') + '</strong></div>'
        + '<div>' + arr_lang_hoan_tat['cart_done_trangthai'] + '</div>'
        + '</div>';

    //
    for (var i = 0; i < current_hd_object.length; i++) {
        //
        if (typeof current_hd_object[i].color_img != 'undefined' && current_hd_object[i].color_img != '') {
            current_hd_object[i].color_img = 'background-image: url(' + current_hd_object[i].color_img + ');';
        } else {
            current_hd_object[i].color_img = '';
        }

        //
        var product_price = current_hd_object[i].price;
        // hiển thị giá theo size, color nếu có
        if (typeof current_hd_object[i].child_price != 'undefined' && current_hd_object[i].child_price != '' && current_hd_object[i].child_price * 1 > 0) {
            product_price = current_hd_object[i].child_price;
        }

        //
        str2 += ''
            + '<div class="cf">'
            + '<div class="lf f25 fullsize-if-mobile">'
            + '<div data-size="1" data-img="" class="ti-le-global hoan-tat-avt" style="' + current_hd_object[i].color_img + '">&nbsp;</div>'
            + '</div>'
            + '<div class="lf f75 fullsize-if-mobile">'
            + '<div class="left-menu-space">'
            + '<h4><a href="' + web_link + '?p=' + current_hd_object[i].id + '" target="_blank" class="bluecolor">' + current_hd_object[i].name + '</a></h4>'
            + '<div class="l30">'
            + '<div class="redcolor"><strong class="ebe-currency">' + g_func.money_format(product_price) + '</strong></div>'
            + '<div>' + arr_lang_hoan_tat['cart_done_color'] + ': ' + current_hd_object[i].color + '</div>'
            + '<div>' + arr_lang_hoan_tat['cart_done_size'] + ': ' + current_hd_object[i].size + '</div>'
            + '<div>' + arr_lang_hoan_tat['cart_done_quan'] + ': ' + current_hd_object[i].quan + '</div>'
            + '</div>'
            + '</div>'
            + '</div>'
            + '</div>';

        //
        total += product_price * current_hd_object[i].quan;
    }

    //
    str += '<h3 class="l35 text-center">' + arr_lang_hoan_tat['cart_done_list'] + '</h3>'
        + '<div class="hoantat-post-padding l20 tborder lborder rborder bborder">'
        + str2
        + '</div>'
        + '<div class="hoantat-post-padding l20 lborder rborder bborder cf center-if-mobile">'
        + '<div class="lf f25 fullsize-if-mobile text-right">' + arr_lang_hoan_tat['cart_done_tong'] + '</div>'
        + '<div class="lf f75 fullsize-if-mobile">'
        + '<div class="left-menu-space"><strong class="ebe-currency medium18 redcolor">' + g_func.money_format(total) + '</strong></div>'
        + '</div>'
        + '</div>';

    //
    var hoan_tat_discount = WGR_format_discount_price(total);
    //	console.log(hoan_tat_discount);
    if (hoan_tat_discount > 0) {
        str += '<div class="hoantat-post-padding l20 lborder rborder bborder cf center-if-mobile">'
            + '<div class="lf f25 fullsize-if-mobile text-right">Giảm giá</div>'
            + '<div class="lf f75 fullsize-if-mobile">'
            + '<div class="left-menu-space"><strong class="ebe-currency orgcolor">' + g_func.money_format(hoan_tat_discount) + '</strong> - Số tiền giảm giá (nếu có) hiện là số tạm tính, chúng tôi sẽ kiểm tra và xác nhận lại tính hợp lệ rồi mới thông báo chính thức tới khách hàng sau.</div>'
            + '</div>'
            + '</div>'
            + '<div class="hoantat-post-padding l20 lborder rborder bborder cf center-if-mobile">'
            + '<div class="lf f25 fullsize-if-mobile text-right">Giá sau khuyến mại</div>'
            + '<div class="lf f75 fullsize-if-mobile">'
            + '<div class="left-menu-space"><strong class="ebe-currency medium18 greencolor">' + g_func.money_format(total - hoan_tat_discount) + '</strong></div>'
            + '</div>'
            + '</div>';
    }

    //
    if (jQuery('#show-product-for-google').length > 0) {
        jQuery('#show-product-for-google').html(str);
    } else if (jQuery('.col-booking-done-padding').length > 0) {
        jQuery('.col-booking-done-padding').append(str);
    }
}

function WGR_hoan_tat_send_tracking(hd_id, current_hd_object, current_tv_object) {
    WGR_show_hoan_tat_product_for_gg(current_hd_object, current_tv_object);

    //
    if (g_func.getc('wgr_check_tracking_social' + hd_id) != null) {
        console.log('%c Order has been tracking!', 'color: red;');
        return false;
    }
    g_func.setc('wgr_check_tracking_social' + hd_id, 'wgr', 0, 7);

    //
    ___eb_add_convertsion_gg_fb(hd_id, current_hd_object);
    WGR_backup_order_to_google_sheet(current_hd_object, current_tv_object);

    //
    _global_js_eb.ga_event_track('Booking done', 'Dat hang thanh cong', '', {
        //		'category' : '',
        //		'label' : '',
        'action': 'purchase'
    });

    //
    //	setTimeout(function () {
    if (typeof current_hd_id != 'undefined' && current_hd_id != '') {
        ajaxl('hoan-tat-mail&id=' + current_hd_id, 'oi_hoan_tat_mail', 1);
    }
    //	}, 3000);
}

function ___eb_add_convertsion_gg_fb(hd_id, arr, max_for) {

    //
    if (typeof hd_id != "number") {
        console.log("order ID not found");
        return false;
    }

    // kiểm tra xem GA với FB đã load được chưa, chờ tụi nó 1 lúc, nếu không thể load được thì đành chạy tiếp
    //	if ( typeof ga == 'undefined' || typeof fbq == 'undefined' ) {
    if (1 == 2 && typeof ga == 'undefined' && typeof fbq == 'undefined') {
        if (typeof max_for == "undefined") {
            max_for = 60;
        }
        //		console.log( 'Max for: ' + max_for );

        //
        if (max_for > 0) {
            setTimeout(function () {
                ___eb_add_convertsion_gg_fb(hd_id, arr, max_for - 1);
            }, 500);

            console.log('Re-load tracking (' + max_for + ')...');

            return false;
        }

        //
        console.log('Max for: ' + max_for);
    }

    // nếu giá trị tuyền vào không phải là mảng
    arr = ___eb_add_conver_string_cart_to_arr_cart(arr);
    if (arr == false) {
        return false;
    }

    //
    //	current_hd_id = hd_id;
    //	current_hd_object = arr;

    // Set tracker currency to Euros.
    //	ga('set', 'currencyCode', 'VND');

    //
    var tong_tien = 0,
        arr_ids = [],
        ga_add_product = [];
    for (var i = 0; i < arr.length; i++) {
        //if ( typeof arr[i].__eb_hd_customer_info == 'undefined' ) {
        // bản cũ
        //arr_ids.push(arr[i].id);
        // bản mới: https://developers.facebook.com/docs/facebook-pixel/implementation/marketing-api
        arr_ids.push({
            'id': arr[i].id.toString(),
            'quantity': arr[i].quan,
            //'quantity': arr[i].quan * 1,
        });

        //
        var product_price = arr[i].price;
        // hiển thị giá theo size, color nếu có
        if (typeof arr[i].child_price != 'undefined' && arr[i].child_price != '' && arr[i].child_price * 1 > 0) {
            product_price = arr[i].child_price;
        }
        product_price *= 1;
        //product_price *= arr[i].quan;

        //
        //tong_tien -= (0 - product_price);
        tong_tien += product_price;

        //
        ga_add_product.push({
            'id': 'p' + arr[i].id,
            'name': arr[i].name,
            'category': 'Echbay category',
            'brand': 'Echbay',
            'variant': 'red',
            'price': product_price,
            'quantity': arr[i].quan
        });
        //}
    }

    //
    var hoan_tat_discount = WGR_format_discount_price(tong_tien);
    //	console.log(hoan_tat_discount);
    if (hoan_tat_discount > 0) {
        tong_tien -= hoan_tat_discount;
    }

    // fb track -> by products
    _global_js_eb.fb_track("Purchase", {
        content_type: 'product',
        //content_ids: arr_ids,
        contents: arr_ids,
        //value: arr[i].price,
        value: tong_tien,
        //currency: "VND"
        currency: cf_current_sd_price
    });

    // google analytics track -> by order
    ___eb_add_convertsion_gg_v2( ga_add_product, {
        //"id": arr[0].id,
        "id": hd_id,
        "affiliation": window.location.href.split('//')[1].split('/')[0].replace('www.', ''),
        //"revenue": arr[0].price,
        "revenue": tong_tien,
        "tax": "0",
        "shipping": "0",
        "coupon": ""
    } );

}

function ___eb_add_convertsion_gg_v2(ga_add_product, ga_set_action, max_for) {
    if (typeof max_for == "undefined") {
        max_for = 60;
    }
    
    //
    if (typeof ga == 'undefined') {
        if (typeof max_for == "undefined") {
            max_for = 60;
        }
        //console.log( 'Max for: ' + max_for );

        //
        if (max_for > 0) {
            setTimeout(function () {
                ___eb_add_convertsion_gg_v2(ga_add_product, ga_set_action, max_for - 1);
            }, 500);

            console.log('Re-load GG tracking (' + max_for + ')...');

            return false;
        }

        //
        console.log('Max for: ' + max_for);
        return false;
    }
    
    //
    for ( var i = 0; i < ga_add_product.length; i++ ) {
        ga('ec:addProduct', ga_add_product[i]);

        //
        console.log('ec addProduct:');
        console.log(ga_add_product[i]);
    }
    
    //
    ga("ec:setAction", "purchase", ga_set_action);

    //
    console.log('ec setAction:');
    console.log(ga_set_action);
}


// backup dữ liệu hóa đơn tới google sheet
function WGR_backup_order_to_google_sheet(arr, arr2) {
    if (typeof jQuery != 'function') {
        setTimeout(function () {
            WGR_backup_order_to_google_sheet(arr, arr2);
        }, 1000);

        //
        return false;
    }

    //
    if (typeof cf_google_sheet_backup == 'undefined' || cf_google_sheet_backup.split('/script.google.com/').length == 1) {
        // v2 -> backup default for user
        cf_google_sheet_backup = 'https://script.google.com/macros/s/AKfycbwaunyHsSCVpsEge4-kkIROr2eaDMmwh117n7_5Iw/exec';

        //
        //		console.log('cf_google_sheet_backup is NULL');
        //		return false;
    }

    //
    //	var check_order_backup = g_func.getc('wgr_backup_order_to_google'),
    //		same_same_order_id = 'id' + current_hd_id;
    //	if ( check_order_backup != null && check_order_backup == same_same_order_id ) {
    //		console.log('order has been backup');
    //		return false;
    //	}

    //
    current_hd_object = ___eb_add_conver_string_cart_to_arr_cart(arr);
    current_tv_object = ___eb_add_conver_string_cart_to_arr_cart(arr2);

    // tạo form
    if (jQuery('#backup-order-to-google-sheet').length == 0) {
        jQuery('body').append(''
            + '<div class="d-none">'
            + '<form id="backup-order-to-google-sheet" method="GET">'
            + '<input type="text" name="id" value="" />'
            + '<input type="text" name="ma-hoa-don" value="" />'
            + '<input type="text" name="ngay-gui" value="" />'
            + '<input type="text" name="trang-thai" value="" />'
            + '<input type="text" name="san-pham" value="" />'
            + '<input type="text" name="url" value="" />'
            + '<input type="text" name="sl" value="" />'
            + '<input type="text" name="msac" value="" />'
            + '<input type="text" name="kthuoc" value="" />'
            + '<input type="text" name="sku" value="" />'
            + '<input type="text" name="cnang" value="" />'
            + '<input type="text" name="cong" value="" />'
            + '<input type="text" name="giam" value="" />'
            + '<input type="text" name="cuoc" value="" />'
            + '<input type="text" name="tong" value="" />'
            + '<input type="text" name="khach-hang" value="" />'
            + '<input type="text" name="dien-thoai" value="" />'
            + '<input type="text" name="dia-chi" value="" />'
            + '<input type="text" name="quan-huyen" value="" />'
            + '<input type="text" name="tinh-thanh-pho" value="" />'
            + '<input type="text" name="nguon" value="" />'
            + '<input type="text" name="phuong-tien" value="" />'
            + '<input type="text" name="chien-dich" value="" />'
            + '<input type="text" name="domain" value="wgr" />'
            + '</form>'
            + '</div>');
    }
    console.log(arr_hd_trangthai);

    //
    var i = 0;
    jQuery('form#backup-order-to-google-sheet input[name="id"]').val('ID' + current_hd_id);
    jQuery('form#backup-order-to-google-sheet input[name="ma-hoa-don"]').val('SKU' + current_hd_code);
    jQuery('form#backup-order-to-google-sheet input[name="ngay-gui"]').val(current_hd_date);
    //	jQuery('form#backup-order-to-google-sheet input[name="trang-thai"]').val( typeof arr_hd_trangthai[ current_hd_status ] != 'undefined' ? arr_hd_trangthai[ current_hd_status ] : 'Unknown' );
    jQuery('form#backup-order-to-google-sheet input[name="trang-thai"]').val('-');
    jQuery('form#backup-order-to-google-sheet input[name="san-pham"]').val(current_hd_object[i].name);
    jQuery('form#backup-order-to-google-sheet input[name="url"]').val(web_link + '?p=' + current_hd_object[i].id);
    jQuery('form#backup-order-to-google-sheet input[name="sl"]').val(current_hd_object[i].quan);
    jQuery('form#backup-order-to-google-sheet input[name="msac"]').val(current_hd_object[i].color);
    jQuery('form#backup-order-to-google-sheet input[name="kthuoc"]').val(current_hd_object[i].size);
    jQuery('form#backup-order-to-google-sheet input[name="sku"]').val(current_hd_object[i].sku);
    //	jQuery('form#backup-order-to-google-sheet input[name="cnang"]').val();
    jQuery('form#backup-order-to-google-sheet input[name="cong"]').val(current_hd_object[i].price);
    //	jQuery('form#backup-order-to-google-sheet input[name="giam"]').val();
    //	jQuery('form#backup-order-to-google-sheet input[name="cuoc"]').val();
    jQuery('form#backup-order-to-google-sheet input[name="tong"]').val(current_hd_object[i].quan * current_hd_object[i].price);
    jQuery('form#backup-order-to-google-sheet input[name="khach-hang"]').val('N:' + current_tv_object['hd_ten']);
    jQuery('form#backup-order-to-google-sheet input[name="dien-thoai"]').val('P:' + current_tv_object['hd_dienthoai']);
    jQuery('form#backup-order-to-google-sheet input[name="dia-chi"]').val('A:' + current_tv_object['hd_diachi']);
    //	jQuery('form#backup-order-to-google-sheet input[name="quan-huyen"]').val();
    //	jQuery('form#backup-order-to-google-sheet input[name="tinh-thanh-pho"]').val();
    //	jQuery('form#backup-order-to-google-sheet input[name="nguon"]').val();
    //	jQuery('form#backup-order-to-google-sheet input[name="phuong-tien"]').val();
    //	jQuery('form#backup-order-to-google-sheet input[name="chien-dich"]').val();
    //	jQuery('form#backup-order-to-google-sheet input[name="domain"]').val( document.domain );
    //	jQuery('form#backup-order-to-google-sheet input[name="domain"]').val( '' );

    //
    var data = jQuery('form#backup-order-to-google-sheet').serialize();
    jQuery.ajax({
        type: 'GET',
        url: cf_google_sheet_backup,
        dataType: 'json',
        crossDomain: true,
        data: data,
        success: function (data) {
            if (data == 'false') {
                console.log('ERROR backup order to google sheet');
            } else {
                console.log('Backup order to google sheet');

                // lưu cookie để không gửi liên tục 1 đơn hàng
                //				g_func.setc('wgr_backup_order_to_google', same_same_order_id, 0, 7);
            }
        }
    });
}


function WGR_show_try_catch_err(e) {
    return 'name: ' + e.name + '; line: ' + (e.lineNumber || e.line) + '; script: ' + (e.fileName || e.sourceURL || e.script) + '; stack: ' + (e.stackTrace || e.stack) + '; message: ' + e.message;
}


// Thay cái alert mặc định bằng cái alert HTML này cho thân thiện
var time_for_hide_html_alert = null;

function WGR_html_alert(m) {
    if (dog('WGR_html_alert') == null) {
        jQuery('body').append('<div id="WGR_html_alert" class="d-none"></div>');
    }

    //
    jQuery('#WGR_html_alert').html('<div>' + m + '</div>').fadeIn().off('click').click(function () {
        jQuery('#WGR_html_alert').fadeOut();
    });

    //
    clearTimeout(time_for_hide_html_alert);
    time_for_hide_html_alert = setTimeout(function () {
        jQuery('#WGR_html_alert').fadeOut(2000);
    }, 5000);
}

function dog(o, s) {
    if (typeof o == 'undefined' || o == '' || document.getElementById(o) == null) {
        console.log('id: ' + o + ' NULL');
        return null;
    }
    if (typeof s != 'undefined') {
        document.getElementById(o).innerHTML = s;
    }
    return document.getElementById(o);
}


function WGR_check_option_on(a) {
    //	if ( a == 1 || a == "1" ) {
    if (a.toString() == "1") {
        return true;
    }
    return false;
}


function _date(phomat, t) {

    //
    if (typeof phomat != 'string' || phomat.replace(/\s/g, '') == '') {
        return _date('D, M d,Y H:i:s');
    }

    //
    var type = typeof t,
        js_date = function (d) {
            var arr_D = "Sunday Monday Tuesday Wednesday Thursday Friday Saturday".split(" "),
                arr_M = "January February March April May June July August September October November December".split(" ");
            for (var i = 0, str = ''; i < phomat.length; i++) {
                str += (function (a) {
                    if (typeof a == 'undefined') {
                        return '';
                    }
                    a = a.replace(/\s/g, ' ');
                    switch (a) {
                        case "d":
                            a = d.getDate();
                            break;
                        case "D":
                            a = arr_D[d.getDay()].substr(0, 3);
                            break;
                        case "l":
                            a = arr_D[d.getDay()];
                            break;
                        case "F":
                            a = arr_M[d.getMonth()];
                            break;
                        case "M":
                            a = arr_M[d.getMonth()].substr(0, 3);
                            break;
                        case "m":
                            a = d.getMonth() + 1;
                            break;
                        case "Y":
                            a = d.getFullYear();
                            break;
                        case "y":
                            a = d.getFullYear().toString().substr(2);
                            break;
                        case "a":
                            a = d.getHours();
                            if (a >= 12) {
                                a = 'am';
                            } else {
                                a = 'pm';
                            }
                            break;
                        case "A":
                            a = d.getHours();
                            if (a >= 12) {
                                a = 'AM';
                            } else {
                                a = 'PM';
                            }
                            break;
                        case "H":
                            a = d.getHours();
                            break;
                        case "h":
                            a = d.getHours();
                            if (a > 12) {
                                a -= 12;
                            }
                            break;
                        case "i":
                            a = d.getMinutes();
                            break;
                        case "s":
                            a = d.getSeconds();
                            break;
                    }
                    if (a != ' ' && !isNaN(a) && a < 10) {
                        a = '0' + a;
                    }
                    return a;
                }(phomat.substr(i, 1)));
            }
            return str;
        };

    //
    if (type == 'string') {
        t = t * 1;
        //		t = parseInt(t, 10);
    } else if (type == 'undefined' || isNaN(t)) {
        if (typeof date_time != 'undefined') {
            t = date_time * 1000;
        } else {
            t = new Date().getTime();
        }
    } else {
        t = t * 1000;
    }
    var nd = new Date(t);

    //
    return js_date(nd);
}


function _time_date() {
    var _1_ngay_truoc = date_time - (24 * 3600);
    jQuery('.number-to-time').each(function () {
        var a = jQuery(this).attr('title') || jQuery(this).html() || '',
            a_cache = a;
        if (a != '') {
            a = parseInt(a, 10);
            if (!isNaN(a)) {
                if (a > date_time) {
                    jQuery(this).html(_date(lang_date_time_format, a));
                } else if (a > _1_ngay_truoc) {
                    var str_truoc_sau = 'tr\u01b0\u1edbc';
                    if (a > date_time) {
                        a = a - date_time;
                        str_truoc_sau = 'sau';
                    } else {
                        a = date_time - a;
                    }
                    var str = '',
                        gio = 0,
                        sodu = 0,
                        phut = 0;
                    if (a < 60) {
                        str = a + ' gi\u00e2y ' + str_truoc_sau;
                    } else if (a < 3600) {
                        str = ((a - (a % 60)) / 60) + ' ph\u00fat ' + str_truoc_sau;
                    } else {
                        sodu = a % 3600;
                        gio = (a - sodu) / 3600;
                        phut = (sodu - (sodu % 60)) / 60;
                        str = gio + ((phut > 5) ? ',' + phut : '') + ' gi\u1edd ' + str_truoc_sau;
                        jQuery(this).attr({
                            title: _date(lang_date_time_format, a_cache)
                        });
                    }
                    jQuery(this).html(str);
                } else {
                    jQuery(this).html(_date(lang_date_time_format, a));
                }
            }
        }
    }).removeClass('number-to-time');
}


function ajaxl_url(url) {
    return web_link + 'eb-ajaxservice?set_module=' + url;
}

function ajaxl(url, id, bg, callBack, ajax_option) {
    if (typeof url == 'undefined' || url == '') {
        console.log('URL is NULL');
        return false;
    }

    if (typeof id == 'undefined' || id == '') {
        console.log('id is NULL.');
        return false;
    } else if (dog(id) == null) {
        if (typeof bg == 'number' && bg == 1) {
            jQuery('body').append('<div id="' + id + '" class="d-none"></div>');
        } else {
            console.log('"' + id + '" not found. Set bg = 1 for auto create div[id="' + id + '"].');
            return false;
        }
    }

    // URL phải theo 1 chuẩn nhất định
    //	if ( url.split( web_link ).length == 1 ) {
    if (url.split('//').length == 1) {
        url = ajaxl_url(url);
    }
    if (WGR_check_option_on(cf_tester_mode)) console.log(url);

    // các tham số để lấy dữ liệu
    if (typeof ajax_option != 'object') {
        ajax_option = {
            type: 'POST',
            url: url,
            data: ''
        };
    } else {
        // method
        if (typeof ajax_option.type == 'undefined') {
            ajax_option.type = 'POST';
        } else if (ajax_option.type == '') {
            ajax_option.type = 'GET';
        }

        //
        ajax_option.url = url;
        ajax_option.data = '';
    }

    //
    jQuery.ajax(ajax_option).done(function (msg) {
        jQuery('#' + id).html(msg);

        if (typeof callBack == 'function') {
            callBack();
        }
    });
}


function _alert(m) {
    WGR_html_alert(m);
}

function a_lert(m) {
    WGR_html_alert(m);
    /*
    clearTimeout(ctimeout);
    dog('o_load', '<div class="o-load">' + m + '</div>');
    ctimeout = setTimeout(function() {
    	g_func.jquery_null('o_load');
    }, 3000);
    */
}

var arr_discount_code_return = {};

function WGR_check_discount_code_return(jd) {
    //	var f = document.frm_cart;
    //	f.t_discount_value.value = '';
    $('input[name="t_discount_value"]').val('');

    // có mã giảm giá thì mới tiếp tục
    if (co_ma_giam_gia != 1) {
        console.log('%c Không tìm thấy Mã giảm giá nào!', 'color: red;');
        return false;
    }

    // kiểm tra xem có dữ liệu của phần giảm giá không
    if (jQuery.isEmptyObject(arr_discount_code_return) == true) {
        jQuery('#' + jd).html('<span class="redcolor">Không xác định được dữ liệu giảm giá!</span>');
        return false;
    }

    //
    var msg = arr_discount_code_return;
    //	console.log( msg );

    // hiển thị lỗi nếu có
    if (typeof msg['error'] != 'undefined') {
        jQuery('#' + jd).html('<span class="redcolor">' + msg['error'] + '</span>');
    } else if (typeof msg['code'] != 'undefined' && msg['code'] * 1 < 0) {
        jQuery('#' + jd).html('<span class="redcolor">' + msg['msg'] + '</span>');
    } else {
        // chuẩn hóa dữ liệu
        msg['coupon_max'] = msg['coupon_max'] * 1;
        msg['coupon_min'] = msg['coupon_min'] * 1;
        msg['coupon_toida'] = g_func.number_only(msg['coupon_toida']);
        msg['coupon_toithieu'] = g_func.number_only(msg['coupon_toithieu']);
        var m = msg['category_description'],
            m2 = '',
            cl = 'greencolor';

        // kiểm tra xem đã dùng hết hay chưa
        if (msg['coupon_max'] > 0 && msg['coupon_min'] >= msg['coupon_max']) {
            m = 'Số lượng mã giảm giá đã hết!';
            cl = 'orgcolor';
        } else {
            var gia_tri_don_hang = 0,
                so_tien_duoc_giam = 0;

            // trong trang chi tiết sản phẩm
            if (pid > 0) {
                gia_tri_don_hang = jQuery('#oi_change_soluong input').val() || jQuery('#oi_change_soluong select').val() || 0;
                gia_tri_don_hang = gia_tri_don_hang * product_js['gm'];
            }
            // giỏ hàng
            else {
                /*
                gia_tri_don_hang = jQuery('.cart-table-total .global-details-giamoi').html() || '0';
                gia_tri_don_hang = gia_tri_don_hang.replace( /\,|\s/g, '' );
                gia_tri_don_hang *= 1;
                */

                //
                jQuery('.each-for-set-cart-value').each(function () {
                    var cart_pid = jQuery(this).attr('data-id') || 0,
                        gia_moi = jQuery(this).attr('data-price') || 0,
                        cart_quan = jQuery('.change-select-quantity', this).val() || 1;

                    gia_tri_don_hang += gia_moi * cart_quan;
                });
            }
            so_tien_duoc_giam = gia_tri_don_hang;
            //			console.log( gia_tri_don_hang );


            // thêm dấu . cho thông điệp trả về
            if (m.substr(m.length - 1) != '.') {
                m += '.';
            }

            // giảm theo sản phẩm cụ thể
            if (typeof msg['coupon_product_name'] != 'undefined' && msg['coupon_product_name'] != '') {
                m2 += ' Mã giảm giá áp dụng cho sản phẩm: <a href="' + msg['coupon_product_link'] + '" target="_blank">' + msg['coupon_product_name'] + '</a>';

                // xác định lại giá trị đơn hàng
                so_tien_duoc_giam = 0;
                if (pid > 0) {
                    // nếu đúng ID rồi thì chiến thôi
                    if (pid == msg['coupon_product'] * 1) {
                        so_tien_duoc_giam = gia_tri_don_hang;
                    }
                } else {
                    jQuery('.each-for-set-cart-value').each(function () {
                        var cart_pid = jQuery(this).attr('data-id') || 0,
                            gia_moi = jQuery(this).attr('data-price') || 0,
                            cart_quan = jQuery('.change-select-quantity', this).val() || 1;

                        if (cart_pid > 0 && cart_pid * 1 == msg['coupon_product'] * 1) {
                            so_tien_duoc_giam = gia_moi * cart_quan;
                        }
                    });
                }
            }
            if (typeof msg['coupon__product_name'] != 'undefined' && msg['coupon__product_name'] != '') {
                m2 += ' <span class="redcolor">Mã giảm giá KHÔNG áp dụng cho sản phẩm</span>: <a href="' + msg['coupon__product_link'] + '" target="_blank">' + msg['coupon__product_name'] + '</a>';
                // xác định lại giá trị đơn hàng
                if (pid > 0) {
                    // nếu đúng ID thì loại bỏ ra
                    if (pid == msg['coupon__product'] * 1) {
                        so_tien_duoc_giam = 0;
                    }
                } else {
                    so_tien_duoc_giam = 0;
                    jQuery('.each-for-set-cart-value').each(function () {
                        var cart_pid = jQuery(this).attr('data-id') || 0,
                            gia_moi = jQuery(this).attr('data-price') || 0,
                            cart_quan = jQuery('.change-select-quantity', this).val() || 1;

                        if (cart_pid > 0 && cart_pid * 1 != msg['coupon__product'] * 1) {
                            so_tien_duoc_giam += gia_moi * cart_quan;
                        }
                    });
                }
            }

            if (typeof msg['coupon_category_name'] != 'undefined' && msg['coupon_category_name'] != '') {
                m2 += ' Mã giảm giá áp dụng cho chuyên mục: <a href="' + msg['coupon_category_link'] + '" target="_blank">' + msg['coupon_category_name'] + '</a>';
            }
            if (typeof msg['coupon__category_name'] != 'undefined' && msg['coupon__category_name'] != '') {
                m2 += ' <span class="redcolor">Mã giảm giá KHÔNG áp dụng cho chuyên mục</span>: <a href="' + msg['coupon__category_link'] + '" target="_blank">' + msg['coupon__category_name'] + '</a>';
            }


            // kiểm tra điều kiện khuyến mại
            if (msg['coupon_toithieu'] > 0 && gia_tri_don_hang > 0 && gia_tri_don_hang < msg['coupon_toithieu']) {
                m = '<span class="orgcolor">Mã giảm giá áp dụng cho đơn hàng có giá trị tối thiểu là <span class="ebe-currency">' + g_func.money_format(msg['coupon_toithieu']) + '</span></span>';
                cl = 'orgcolor';
            } else if (so_tien_duoc_giam > 0) {
                // ưu tiên giảm theo giá tiền
                if (typeof msg['coupon_giagiam'] != 'undefined' && msg['coupon_giagiam'] != '' && msg['coupon_giagiam'].replace(/\,/g, '') * 1 > 0) {
                    //					f.t_discount_value.value = msg['coupon_giagiam'];
                    $('input[name="t_discount_value"]').val(msg['coupon_giagiam'].replace(/\,/g, ''));
                }
                //
                else if (typeof msg['coupon_phantramgiam'] != 'undefined' && msg['coupon_phantramgiam'] != '' && msg['coupon_phantramgiam'] * 1 > 0) {
                    //					console.log( msg['coupon_phantramgiam'] );
                    //					f.t_discount_value.value = msg['coupon_phantramgiam'] + '%';
                    $('input[name="t_discount_value"]').val(msg['coupon_phantramgiam'] + '%');
                }

                //
                var gia_tri_giam_gia = $('input[name="t_discount_value"]').val() || '';
                //				console.log( gia_tri_giam_gia );
                if (gia_tri_giam_gia != '') {
                    if (gia_tri_giam_gia.split('%').length > 1) {
                        gia_tri_giam_gia = gia_tri_giam_gia.split('%')[0].replace(/\s|\,/g, '');
                        so_tien_duoc_giam = so_tien_duoc_giam / 100 * gia_tri_giam_gia;
                    } else {
                        so_tien_duoc_giam = gia_tri_giam_gia * 1;
                    }
                    console.log('New order price: ' + gia_tri_don_hang + ' - ' + so_tien_duoc_giam + ' = ' + g_func.money_format(gia_tri_don_hang - so_tien_duoc_giam));
                    //					jQuery('.cart-table-total .global-details-giamoi').html( g_func.money_format( so_tien_duoc_giam ) );
                }
            } else {
                m = '<span class="redcolor">Mã giảm giá không hợp lệ hoặc không áp dụng cho sản phẩm, danh mục đang có trong giỏ hàng.</span>';
            }
        }

        //
        jQuery('#' + jd).html('<span class="' + cl + '">' + m + m2 + '</span>');
    }
}

function click2Copy(element, textShow) {
    element.focus();
    element.select();
    document.execCommand('copy');

    if (typeof textShow != 'undefined' && textShow == true) {
        try {
            textShow = element.value;
            textShow = ' <strong>' + $.trim(textShow) + '</strong>';
        } catch (e) {
            textShow = ''
        }
    } else {
        textShow = ''
    }
    WGR_html_alert('Copied' + textShow);
}
