


function WGR_for_edit_widgets () {
	if ( window.location.href.split('/widgets.php').length == 0 ) {
		return false;
	}
	
	$('#main_sidebar .sidebar-name h2').click();
	
	$('div.widget-liquid-left').css({
		'overflow-y': 'scroll',
		'height': $('div.widget-liquid-right').height()
	});
	
	$('#main_sidebar .sidebar-name h2').click();
	
	
	// khi bấm vào mở xem 1 wdiget nào đó
	$('.widget-liquid-right .widget-title h3, .widget-liquid-right .widget-title-action').click(function () {
		// dò tìm element cha
		var a = $(this).parent().attr('id') || '';
		
		if ( a == '' ) {
			a = $(this).parent().parent().attr('id') || '';
			
			if ( a == '' ) {
				a = $(this).parent().parent().parent().attr('id') || '';
				
				if ( a == '' ) {
					a = $(this).parent().parent().parent().parent().attr('id') || '';
					
					if ( a == '' ) {
						a = $(this).parent().parent().parent().parent().parent().attr('id') || '';
					}
				}
			}
		}
		
		if ( a != '' ) {
//			$('.widget-liquid-right .widget-title h3, .widget-liquid-right .widget-title-action').removeClass('redcolor');
//			$(this).addClass('redcolor');
			
			$('.widget-liquid-right .widget-content, .widget-liquid-right .widget-inside').css({
				'z-index': '99999'
			});
			
			$('#' + a + ' .widget-content, #' + a + ' .widget-inside').css({
				'z-index': '999999'
			});
//			console.log(a);
		}
	});
}

$(document).ready(function() {
	WGR_for_edit_widgets();
});




