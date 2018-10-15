

function WGR_order_export_exist ( a, k ) {
	if ( typeof a[k] != 'undefined' && a[k] != '' ) {
		return a[k];
	}
	return '&nbsp;';
}

function WGR_order_export_null ( s ) {
	return s == '' ? '&nbsp;' : s;
}

function WGR_order_export___products ( arr ) {
	return '<td>' + arr.name + '</td>' +
		'<td class="text-center">' + arr.quan + '</td>' +
		'<td class="text-center remove-size-color-tag">' + WGR_order_export_null( arr.color ) + '</td>' +
		'<td class="text-center remove-size-color-tag">' + WGR_order_export_null( arr.size ) + '</td>';
		
		/*
	return '<div class="cf">' +
			'<div class="lf f80"><a href="' + web_link + '?p=' + arr.id + '" target="_blank" rel="nofollow">- ' + arr.name + '</a></div>' +
			'<div class="lf f5 text-center">' + arr.quan + '</div>' +
			'<div class="lf f10 text-center remove-size-color-tag">' + arr.color + '</div>' +
			'<div class="lf f5 text-center remove-size-color-tag">' + arr.size + '</div>' +
		'</div>';
		*/
}

function WGR_order_export__products ( arr ) {
	var str = '';
	
	//
	for ( var i = 0; i < arr.length; i++ ) {
		if ( i > 0 ) {
			str += '<tr><td colspan="3">&nbsp;</td>' + WGR_order_export___products( arr[i] ) + '<td colspan="6">&nbsp;</td></tr>';
		}
	}
	
	return str;
}

function WGR_order_export_products ( arr ) {
	return WGR_order_export___products( arr[0] );
}


//
function WGR_order_export_run ( arr, arr_status ) {
	
	//
//	console.log( arr );
	
	//
	var order_lnk = admin_link + 'admin.php?page=eb-order&id=';
	
	//
	for ( var i = 0; i < arr.length; i++ ) {
		//
		var prod = jQuery.parseJSON( unescape( arr[i].order_products ) );
//		console.log( prod );
		//
		var cus = jQuery.parseJSON( unescape( arr[i].order_customer ) );
//		console.log( cus );
		
		//
		$('#headerTable').append(
		'<tr>' +
			'<td>' + arr[i].order_id + '</td>' +
			'<td>' + arr[i].order_sku + '</td>' +
			'<td>' + ( typeof arr_status[ arr[i].order_status ] != 'undefined' ? arr_status[ arr[i].order_status ] : 'Unknown' ) + '</td>' +
			
//			'<td>' + WGR_order_export_products( prod ) + '</td>' +
			WGR_order_export_products( prod ) +
			
			'<td class="upper">' + ( cus['hd_ten'] == '' ? 'No-name' : cus['hd_ten'] ) + '</td>' +
			'<td>' + cus['hd_dienthoai'] + '</td>' +
			'<td>' + cus['hd_diachi'] + '</td>' +
			'<td>' + WGR_order_export_exist( cus, 'hd_utm_source' ) + '</td>' +
			'<td>' + WGR_order_export_exist( cus, 'hd_utm_medium' ) + '</td>' +
			'<td>[' + WGR_order_export_exist( cus, 'hd_utm_campaign' ) + ']</td>' +
		'</tr>' +
		WGR_order_export__products( prod )
		);
		
	}
	
	//
	$('.remove-size-color-tag strong').remove();
	
}


// xác định chiều rộng cho table hiển thị cho đẹp
$(document).ready(function() {
	(function () {
		// tính tổng số thẻ tr
		var c = $('#headerTable tr').length;
		if ( c <= 1 ) {
			return false;
		}
		
//		console.log( c );
		// -> chiều cao của body -> thêm 10 đơn vị để dự phòng
		c = c * 16 + 10;
//		console.log( c );
//		console.log( $('#headerTable').height() );
		
		//
		for ( var i = 0; i < 150; i++ ) {
			if ( $('#headerTable').height() > c ) {
//				console.log( $('#headerTable').height() );
				$('#headerTable').width( ( 100 + i * 1 ) + '%' );
			}
			else {
				
				// thêm lần dữ phòng nữa
				$('#headerTable').width( ( 110 + i * 1 ) + '%' ).addClass('pd');
//				console.log( i );
//				console.log( $('#headerTable').height() );
				
				//
				$('body').addClass('done');
				
				break;
			}
		}
		
		//
		$('#headerTable tr').click(function () {
			$('#headerTable tr').removeClass('selected');
			$(this).addClass('selected');
		});
	})();
});


