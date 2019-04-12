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



// chỉnh sửa lại nội dung cho file lang -> chỉ cần thêm vào cuối file là được
$__eb_cache_only_lang = EB_THEME_CACHE . '___lang.php';
file_put_contents( $__eb_cache_only_lang, '$___eb_lang[\'' . $key . '\']="' . str_replace ( '"', '\"', str_replace ( '$', '\$', $text ) ) . '";' . "\n", FILE_APPEND ) or die('ERROR: append main cache file');



echo '<script>
if ( top != self ) {
	parent.done_update_languages();
}
</script>';


exit();



