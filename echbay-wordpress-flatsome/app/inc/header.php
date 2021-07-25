<style type="text/css">
/* EchBay custom CSS for replace default CSS by plugin or theme */
<?php /* do phần css chứa các url ảnh nên cần thay thế lại luôn nếu có */
if ( $__cf_row['cf_replace_content'] != '' ) {
$__cf_row['cf_default_css'] = WGR_replace_for_all_content( $__cf_row['cf_replace_content'], $__cf_row['cf_default_css'] );
}
echo $__cf_row['cf_default_css'] . $__cf_row['cf_default_themes_css'];
?>
</style>
<script type="text/javascript">
<?php


include EB_THEME_PLUGIN_INDEX . 'data_id.php';


?>
var web_link = '<?php echo str_replace( '/', '\/', web_link ); ?>';
</script> 
<!-- HEAD by <?php echo $arr_private_info_setting['author']; ?> --> 
<?php echo $__cf_row['cf_js_head']; ?> 
<!-- // Global site format by <?php echo $arr_private_info_setting['author']; ?> -->
<?php


//
EBE_print_product_img_css_class( $eb_background_for_post );

// reset lại mục này, để còn insert CSS xuống footer nếu có
$eb_background_for_post = array();
