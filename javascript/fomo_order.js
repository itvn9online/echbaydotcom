

(function () {
	if ( typeof arr_fomo_order == 'undefined' || arr_fomo_order.length == 0 ) {
		return false;
	}
	cf_delay_order_fomo *= 1;
	cf_delay_order_fomo = Math.ceil(cf_delay_order_fomo);
	if ( cf_delay_order_fomo < 1 ) {
		console.log('cf_delay_order_fomo zero!');
		return false;
	}
	
	cf_time_order_fomo *= 1;
	cf_time_order_fomo = Math.ceil(cf_time_order_fomo);
	if ( cf_time_order_fomo < 1 ) {
		console.log('cf_time_order_fomo zero!');
		return false;
	}
	
	//
	var i = g_func.rand( 0, arr_fomo_order.length - 1 );
	var a = arr_fomo_order[i];
//	console.log(a);
	
	var phut = date_time - a.fomo_time;
	if ( phut < 3600 ) {
//		phut = Math.ceil( phut/ 60 ) - 1;
//		phut += ' phút trước';
		phut = 'Vài phút trước';
	}
//	else if ( phut < 24 * 3600 ) {
//		phut = '1 ngày trước';
//	}
	else {
		phut = 'Vài giờ trước';
	}
	
	$('body').append('<ul class="fomo-order"><li class="thread-list thread-list-re"><span onclick="$(\'.fomo-order\').removeClass(\'active\')" class="cur"><i class="fas fa-times"></i></span><a href="' + web_link + '?p=' + a.fomo_id + '" target="_blank">&nbsp;</a><div class="cf"><div class="lf f20"><div data-size="1" class="ti-le-global fomo-avt" style="background-image: url(\'' + unescape(a.fomo_img) + '\')">&nbsp;</div></div><div class="lf f80 l19"><div class="fomo-padding"><span class="bold">' + unescape(a.fomo_name) + ' (******' + a.fomo_phone + ')</span> vừa mua<div class="bold bluecolor">' + unescape(a.fomo_product) + '</div><div class="fomo-time">' + phut + '</div></div></div></div></li></ul>');
	
	setTimeout(function () {
		/*
		$('.fomo-order').animate({
			opacity: 1
		});
		*/
		$('.fomo-order').addClass('active');
		
		//
		setTimeout(function () {
			$('.fomo-order').removeClass('active');
		}, cf_time_order_fomo * 1000);
	}, cf_delay_order_fomo * 1000);
	
})();


