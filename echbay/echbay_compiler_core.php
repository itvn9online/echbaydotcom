<?php


function WGR_update_core_remove_html_comment ( $a ) {
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
			
			$str .= $v . ' ';
			if ( strstr( $v, '//' ) == true ) {
	            $str .= "\n";
			}
        }
    }
	
	//
	return trim( WGR_remove_js_multi_comment( $str, '<!--', '-->' ) );
//	return trim( $str );
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
	            $str .= $v . ' ' . "\n";
//			}
        }
    }
	
//	return trim( WGR_remove_js_multi_comment( $str ) );
	return trim( $str );
}

function WGR_update_core_remove_php_multi_comment ( $fileStr ) {
	// https://stackoverflow.com/questions/503871/best-way-to-automatically-remove-comments-from-php-code
	$str = '';
	
	//
	$commentTokens = array(T_COMMENT);
	if (defined('T_DOC_COMMENT')) {
		$commentTokens[] = T_DOC_COMMENT; // PHP 5
	}
	if (defined('T_ML_COMMENT')) {
		$commentTokens[] = T_ML_COMMENT;  // PHP 4
	}
	
	//
	$tokens = token_get_all($fileStr);
	
	//
	foreach ($tokens as $token) {    
		if (is_array($token)) {
			if (in_array($token[0], $commentTokens)) {
				continue;
			}
	
			$token = $token[1];
		}
	
		$str .= $token;
	}
	
	return trim( $str );
}

function WGR_update_core_remove_js_comment ( $a ) {
	$a = WGR_remove_js_comment( $a );
	$a = _eb_str_text_fix_js_content( $a );
//	$a = WGR_remove_js_multi_comment( $a );
	
	return trim( $a );
}

function WGR_compiler_update_echbay_css_js ( $v ) {
	// xác định các file sẽ compiler cho nhẹ code
	$ext = pathinfo($v, PATHINFO_EXTENSION);
	
	// các file đạt yêu cầu
	if ( $ext == 'css' ) {
		file_put_contents( $v, trim( WGR_remove_css_multi_comment( file_get_contents( $v, 1 ) ) ) );
	}
	else if ( $ext == 'js' ) {
		file_put_contents( $v, WGR_update_core_remove_js_comment( file_get_contents( $v, 1 ) ) );
	}
	else if ( $ext == 'php' ) {
		// với file template thì không remove multi comment -> nó là định danh cho file
		if ( strstr( $v, '/templates/' ) == true ) {
			file_put_contents( $v, WGR_update_core_remove_php_comment( file_get_contents( $v, 1 ) ) );
		}
		// còn lại thì remove hết
		else {
			file_put_contents( $v, WGR_update_core_remove_php_multi_comment( WGR_update_core_remove_php_comment( file_get_contents( $v, 1 ) ) ) );
		}
	}
	else if ( $ext == 'html' || $ext == 'htm' ) {
		file_put_contents( $v, WGR_update_core_remove_html_comment( file_get_contents( $v, 1 ) ) );
	}
	
}




