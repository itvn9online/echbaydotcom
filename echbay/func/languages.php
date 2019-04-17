<?php




//
//print_r( $_GET ); exit();

//
$text = isset( $_POST['languages_content_edit'] ) ? trim($_POST['languages_content_edit']) : '';
$key = isset( $_POST['languages_key_edit'] ) ? trim($_POST['languages_key_edit']) : '';

if ( $key == '' ) {
	_eb_alert('KEY is null');
}

//
EBE_set_lang( $key, $text );

//
//echo $text;



//
$text = WGR_stripslashes ( trim( $text ) );
$text = str_replace( '\\\"', '\"', $text );


// chỉnh sửa lại nội dung cho file lang -> chỉ cần thêm vào cuối file là được
$__eb_cache_only_lang = EB_THEME_CACHE . '___lang.php';
$__eb_txt_only_lang = EB_THEME_CACHE . '___lang.txt';

file_put_contents( $__eb_cache_only_lang, '$___eb_lang[\'' . $key . '\']="' . str_replace ( '"', '\"', str_replace ( '$', '\$', $text ) ) . '";' . "\n", FILE_APPEND ) or die('ERROR: append main cache file');

// kiểm tra lại sau khi tạo file
$error_admin_log_cache = WGR_check_syntax( $__eb_cache_only_lang, $__eb_txt_only_lang, false, true );
if ( $error_admin_log_cache != '' ) {
	_eb_log_admin( $error_admin_log_cache );
}



echo '<script>
if ( top != self ) {
	parent.done_update_languages();
}
</script>';


exit();



