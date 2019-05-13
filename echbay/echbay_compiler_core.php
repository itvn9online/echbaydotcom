<?php


function WGR_update_core_remove_html_comment ( $a ) {
	$a = WGR_remove_js_multi_comment( $a, '<!--', '-->' );
	
    $a = explode("\n", $a);

    $str = '';
    foreach ($a as $v) {
        $v = trim($v);

        if ( $v != '' ) {
			// loại bỏ các comment html đơn giản
			/*
			if ( substr( $v, 0, 4 ) == '<!--' && substr( $v, -3 ) == '-->' ) {
			}
			else {
	            $str .= $v . "\n";
			}
			*/
			
			$str .= $v;
			if ( strstr( $v, '//' ) == true ) {
	            $str .= "\n";
			}
        }
    }
	
	return $str;
}

function WGR_update_core_remove_php_comment ( $a ) {
    $a = explode("\n", $a);

    $str = '';
    foreach ($a as $v) {
        $v = trim($v);
		
		// loại bỏ các dòng comment đơn
        if ( $v == '' || substr($v, 0, 2) == '//' || substr($v, 0, 2) == '# ' ) {
        } else {
			// loại bỏ comment php nếu nó nằm trên 1 dòng
//			if ( substr( $v, 0, 2 ) == '/*' && substr( $v, -2 ) == '*/' ) {
//			}
			// trong code php có sẽ code html -> loại bỏ như html luôn
			/*
			else if ( substr( $v, 0, 4 ) == '<!--' && substr( $v, -3 ) == '-->' ) {
			}
			else {
				*/
	            $str .= $v . "\n";
//			}
        }
    }
	
	return $str;
}

function WGR_compiler_update_echbay_css_js ( $v ) {
	// xác định các file sẽ compiler cho nhẹ code
	$ext = pathinfo($v, PATHINFO_EXTENSION);
	
	// các file đạt yêu cầu
	if ( $ext == 'js' ) {
//		file_put_contents( $v, WGR_remove_js_comment( file_get_contents( $v, 1 ) ) );
		file_put_contents( $v, _eb_str_text_fix_js_content( WGR_remove_js_comment( file_get_contents( $v, 1 ) ) ) );
//		file_put_contents( $v, WGR_remove_js_multi_comment( WGR_remove_js_comment( file_get_contents( $v, 1 ) ) ) );
	}
	else if ( $ext == 'css' ) {
		file_put_contents( $v, WGR_remove_css_multi_comment( file_get_contents( $v, 1 ) ) );
	}
	else if ( $ext == 'php' ) {
		file_put_contents( $v, WGR_update_core_remove_php_comment( file_get_contents( $v, 1 ) ) );
	}
	else if ( $ext == 'html' || $ext == 'htm' ) {
		file_put_contents( $v, WGR_update_core_remove_html_comment( file_get_contents( $v, 1 ) ) );
	}
	
}




