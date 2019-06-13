<?php
/*
Description: Breadcrumb cho website, thiết kế bo gọn theo kích thước của TOP trong mục Cài đặt giao diện, có kèm màu nền mặc định có thể điều chỉnh trong cấu hình website.
*/
?>

<div id="breadcrumb2-top1">
	<div class="<?php echo $__cf_row['cf_blog_class_style']; ?>">
		<div class="thread-details-tohome">
			<ul class="cf">
				<li><a href="./"><i class="fa fa-home"></i> <?php echo EBE_get_lang('home'); ?></a></li>
				<?php echo $group_go_to; ?>
			</ul>
		</div>
	</div>
</div>
