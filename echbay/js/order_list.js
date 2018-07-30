



//
var no_reload_current_order_details = '',
	order_details_waiting_load = false,
	auto_reset_if_open_new_tab = null;


// thay đổi trạng thái đơn ở phần danh mục sau khi cập nhật đơn hàng trong popup
function WGR_order_list_after_update ( change_id, new_status, new_text ) {
	// chạy vòng lặp để xóa các class thể hiện trạng thái đi
	for ( var i = 0; i < 25; i++ ) {
		$('.poup-change-status[data-id="' + change_id + '"]').removeClass('hd_status' + i);
	}
	
	// xong thì add class mới
	$('.poup-change-status[data-id="' + change_id + '"]').addClass('hd_status' + new_status);
	$('.poup-change-status[data-id="' + change_id + '"] .order-list-icon').html(new_text);
}

function WGR_after_open_order_details_popup () {
	order_details_waiting_load = false;
	
	//
	$('#target_order_iframe').height( $(window).height() ).attr({
		height: $(window).height()
	});
	
	//
	$('body').css({
		opacity: 1
	});
}




// với màn PC đủ rộng -> xử lý đơn hàng trong popup
if ( $(window).width() > 768 ) {
	$('.open-order-in-popup').attr({
		target: 'target_order_iframe'
	}).on('click', function () {
		
		// không cho tải liên tục
		if ( order_details_waiting_load == true ) {
			console.log('Order details waiting load...');
			return false;
		}
		order_details_waiting_load = true;
		
		//
		$('#oi_order_iframe').show();
//		console.log( Math.random() );
//		return false;
		
		//
		$('body').css({
			opacity: 0.1
		}).addClass('body-no-scroll');
		
		// nếu vẫn là mở đơn hàng cũ -> không load lại nữa
		var a = $(this).attr('data-id') || '';
		if ( a == no_reload_current_order_details ) {
			console.log('Order details has been load!');
			
			WGR_after_open_order_details_popup();
			
			return false;
		}
		no_reload_current_order_details = a;
		
		// chỉnh lại chiều cao của iframe
//		$('#target_order_iframe').on('load', function () {
//		});
		
		//
		clearTimeout( auto_reset_if_open_new_tab );
		auto_reset_if_open_new_tab = setTimeout(function () {
//			console.log( $('#target_order_iframe').attr('src') || '' );
			WGR_after_open_order_details_popup();
		}, 5000);
		
	});
}

//
$(window).on('resize', function () {
	$('#target_order_iframe').height( $(window).height() ).attr({
		height: $(window).height()
	});
});


