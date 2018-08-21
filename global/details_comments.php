<?php



// If comments are open or we have at least one comment, load up the comment template.
/*
if ( comments_open() || get_comments_number() ) :
	comments_template();
endif;
*/

ob_start();

?>

<div id="comments" class="comments-area">
	<h3><?php echo EBE_get_lang('home') . ' ' . $trv_h1_tieude; ?></h3>
	<?php if ( have_comments() ) { ?>
	<ol class="comment-list">
		<?php
		wp_list_comments( array(
			'style'       => 'ol',
			'short_ping'  => true,
			'avatar_size' => 56,
		) );
		?>
	</ol>
	<br>
	<?php
	}
	else {
		echo 'aaaaa';
	} // have_comments()
	?>
	<h3><?php echo EBE_get_lang('home') . ' ' . $trv_h1_tieude; ?></h3>
	<form name="frm_cart" method="post" action="process/?set_module=comments" target="target_eb_iframe" onsubmit="return _global_js_eb.add_comments();">
	</form>
</div>
<?php


//
//comment_form();


$wgr_comment_list = ob_get_contents();

//ob_clean();
//ob_end_flush();
ob_end_clean();








