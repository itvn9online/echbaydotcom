<div id="oi_admin_popup"></div>
<div class="d-none"> 
	<!-- template cho phần xem theo khoảng thời gian -->
	<div id="template_for_time_line">
		<input title="Xem theo khoảng thời gian" type="button" id="oi_time_line_name" value="7 ngày qua" class="red-button small cur" />
		<div class="connect-padding">
			<div class="cf">
				<div class="lf" style="width:20%;">
					<div class="hode-hide-popup-show-day" style="height:150px;margin-right:30px;">&nbsp;</div>
				</div>
				<div class="lf" style="width:80%">
					<div class="cf">
						<div class="bold lf f50">Ph\u1ea1m vi ng\u00e0y</div>
						<div title="\u0110\u00f3ng" align="right" class="lf f50 cur click-how-to-hide-day-selected">\u0110\u00f3ng [x]</div>
					</div>
					<div class="ad-pham-vi-ngay">' + '
						<input type="text" value="' + betwwen1 + '" id="oi_input_value_tu_ngay" maxlength="10" />
						' + '
						<input type="text" value="' + betwwen2 + '" id="oi_input_value_den_ngay" maxlength="10" />
						' + '
						<input type="button" value="Xem" id="oi_click_get_show_by_day" />
						' + '</div>
					<ul class="clearfix">
						{jmp.str}
					</ul>
				</div>
			</div>
			<div class="hode-hide-popup-show-day" style="height:30px;margin-top:20px;">&nbsp;</div>
		</div>
	</div>
	<!-- link nhanh cho phần tạo menu -->
	<div id="content-for-quick-add-menu">
		<ul class="buttom-for-quick-add-menu">
			<li>
				<h4>Hỗ trợ thêm menu nhanh</h4>
				<div>* <em>Bấm dấu <strong>[ + ]</strong> để thêm menu!</em></div>
			</li>
			<li class="cf">
				<div class="lf f80"><?php echo EBE_get_lang('home'); ?> <em>(/)</em></div>
				<div class="lf f20 text-center">
					<button data-link="./" data-text="<?php echo EBE_get_lang('home'); ?>" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			<li class="cf">
				<div class="lf f80"><i class="fas fa-home"></i> <?php echo EBE_get_lang('home'); ?> <em>(/)</em></div>
				<div class="lf f20 text-center">
					<button data-link="./" data-text="<i class='fas fa-home'></i> <?php echo EBE_get_lang('home'); ?>" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			<li class="cf">
				<div class="lf f80"><i class="fas fa-home"></i> <em>(/)</em></div>
				<div class="lf f20 text-center">
					<button data-link="./" data-text="<i class='fas fa-home'></i>" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			<li class="cf">
				<div class="lf f80"><i class="fa fa-circle-o-notch"></i> Chuyên mục <em class="small">(Tạo menu Danh mục sản phẩm bằng Javascript)</em></div>
				<div class="lf f20 text-center">
					<button data-link="#" data-text="..." data-css="wgr-load-js-category" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			<li class="cf">
				<div class="lf f80"><i class="fa fa-circle-o-notch"></i> JS Sub Category <em class="small">(Tạo menu Danh mục sản phẩm bằng Javascript <strong>dùng để làm sub-menu</strong>)</em></div>
				<div class="lf f20 text-center">
					<button data-link="#" data-text="Sản phẩm" data-css="wgr-load-js-sub-category" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			<li class="cf">
				<div class="lf f80"><i class="fa fa-circle-o-notch"></i> Danh mục <em class="small">(Tạo menu Danh mục Tin tức bằng Javascript)</em></div>
				<div class="lf f20 text-center">
					<button data-link="#" data-text="..." data-css="wgr-load-js-blogs" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			<li class="cf">
				<div class="lf f80"><i class="fa fa-circle-o-notch"></i> JS Sub Blogs <em class="small">(Tạo menu Danh mục Tin tức bằng Javascript <strong>dùng để làm sub-menu</strong>)</em></div>
				<div class="lf f20 text-center">
					<button data-link="#" data-text="Tin tức" data-css="wgr-load-js-sub-blogs" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			<li class="cf">
				<div class="lf f80"><i class="fa fa-circle-o-notch"></i> JS Dynamic taxonomy <em class="small">(Tạo menu taxonomy tự động tức bằng Javascript. Code sẽ nhận diện taxonomy và ID taxonomy cần lấy dựa theo cấu trúc trong title: taxonomy{category|blog|post_option}|id{0|1|2})</em></div>
				<div class="lf f20 text-center">
					<button data-link="#" data-text="..." data-title="post_options|0" data-css="wgr-load-js-sub-taxonomy" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			<li class="cf">
				<div class="lf f80"><i class="fa fa-support"></i> <?php echo EBE_get_lang('lienhe'); ?> <em>(/contact)</em></div>
				<div class="lf f20 text-center">
					<button data-link="./contact" data-text="<?php echo EBE_get_lang('lienhe'); ?>" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			<li class="cf">
				<div class="lf f80"><i class="fa fa-support"></i> <?php echo EBE_get_lang('lienhe'); ?> <em>(/lienhe)</em></div>
				<div class="lf f20 text-center">
					<button data-link="./lienhe" data-text="<?php echo EBE_get_lang('lienhe'); ?>" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			<li class="cf">
				<div class="lf f80"><i class="fa fa-support"></i> <?php echo EBE_get_lang('lienhe'); ?> <em>(/lien-he)</em></div>
				<div class="lf f20 text-center">
					<button data-link="./lien-he" data-text="<?php echo EBE_get_lang('lienhe'); ?>" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			<li class="cf">
				<div class="lf f80"><i class="fa fa-shopping-cart"></i> <?php echo EBE_get_lang('cart'); ?> <em>(/cart)</em></div>
				<div class="lf f20 text-center">
					<button data-link="./cart" data-text="<?php echo EBE_get_lang('cart'); ?>" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			<!--
			<li class="cf">
				<div class="lf f80"><i class="fa fa-user"></i> <?php echo EBE_get_lang('taikhoan'); ?> <em>(/profile)</em></div>
				<div class="lf f20 text-center">
					<button data-link="./profile" data-text="<?php echo EBE_get_lang('taikhoan'); ?>" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			-->
			<li class="cf">
				<div class="lf f80"><i class="fa fa-user"></i> <?php echo EBE_get_lang('taikhoan'); ?> <em>(oi_member_func)</em></div>
				<div class="lf f20 text-center">
					<button data-link="#" data-text="."  data-css="oi_member_func" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			<li class="cf">
				<div class="lf f80"><i class="fa fa-link"></i> <?php echo EBE_get_lang('products_all'); ?> <em>(/products_all)</em></div>
				<div class="lf f20 text-center">
					<button data-link="./products_all" data-text="<?php echo EBE_get_lang('products_all'); ?>" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			<li class="cf">
				<div class="lf f80"><i class="fa fa-link"></i> <?php echo EBE_get_lang('products_hot'); ?> <em>(/products_hot)</em></div>
				<div class="lf f20 text-center">
					<button data-link="./products_hot" data-text="<?php echo EBE_get_lang('products_hot'); ?>" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			<li class="cf">
				<div class="lf f80"><i class="fa fa-link"></i> <?php echo EBE_get_lang('products_new'); ?> <em>(/products_new)</em></div>
				<div class="lf f20 text-center">
					<button data-link="./products_new" data-text="<?php echo EBE_get_lang('products_new'); ?>" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			<li class="cf">
				<div class="lf f80"><i class="fa fa-link"></i> <?php echo EBE_get_lang('products_selling'); ?> <em>(/products_selling)</em></div>
				<div class="lf f20 text-center">
					<button data-link="./products_selling" data-text="<?php echo EBE_get_lang('products_selling'); ?>" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			<li class="cf">
				<div class="lf f80"><i class="fa fa-link"></i> <?php echo EBE_get_lang('products_sales_off'); ?> <em>(/products_sales_off)</em></div>
				<div class="lf f20 text-center">
					<button data-link="./products_sales_off" data-text="<?php echo EBE_get_lang('products_sales_off'); ?>" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			<li class="cf">
				<div class="lf f80"><i class="fa fa-diamond"></i> <?php echo EBE_get_lang('golden_time'); ?> <em>(/golden_time)</em></div>
				<div class="lf f20 text-center">
					<button data-link="./golden_time" data-text="<?php echo EBE_get_lang('golden_time'); ?>" data-css="nav-about-giovang" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			<li class="cf">
				<div class="lf f80"><i class="fa fa-heart"></i> <?php echo EBE_get_lang('favorite'); ?> <em>(/favorite)</em></div>
				<div class="lf f20 text-center">
					<button data-link="./favorite" data-text="<?php echo EBE_get_lang('favorite'); ?>" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			<li class="cf">
				<div class="lf f80"><i class="fa fa-map-marker"></i> %cf_diachi%</div>
				<div class="lf f20 text-center">
					<button data-link="#" data-text="<i class='fa fa-map-marker'></i> %cf_diachi%" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			<li class="cf">
				<div class="lf f80"><i class="fa fa-envelope"></i> %cf_email% <em>(mailto)</em></div>
				<div class="lf f20 text-center">
					<button data-link="mailto:%cf_email%" data-text="<i class='fa fa-envelope'></i> %cf_email%" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			<li class="cf">
				<div class="lf f80"><i class="fas fa-phone-alt"></i> %cf_dienthoai% <em>(tel)</em></div>
				<div class="lf f20 text-center">
					<button data-link="tel:%cf_dienthoai%" data-text="<i class='fas fa-phone-alt'></i> %cf_dienthoai%" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			<li class="cf">
				<div class="lf f80"><i class="fas fa-phone-alt"></i> %cf_hotline% <em>(tel)</em></div>
				<div class="lf f20 text-center">
					<button data-link="tel:%cf_hotline%" data-text="<i class='fas fa-phone-alt'></i> %cf_hotline%" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			<li class="cf">
				<div class="lf f80"><i class="fa fa-list"></i> Tất cả danh mục <em class="small">(lấy toàn bộ chuyên mục đang có trên hệ thống)</em></div>
				<div class="lf f20 text-center">
					<button data-link="/auto.get_all_category/" data-text="Toàn bộ danh mục" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			<li class="cf">
				<div class="lf f80"><i class="fas fa-home"></i> Trang chủ + Danh mục <em class="small">(lấy toàn bộ chuyên mục đang có trên hệ thống, đính kèm Home menu vào đầu tiên)</em></div>
				<div class="lf f20 text-center">
					<button data-link="/auto.get_all_category/home/" data-text="<i class='fas fa-home'></i> Danh mục" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			<li class="cf">
				<div class="lf f80"><i class="fas fa-home"></i> + Danh mục <em class="small">(lấy toàn bộ chuyên mục đang có trên hệ thống, đính kèm Home menu vào đầu tiên)</em></div>
				<div class="lf f20 text-center">
					<button data-link="/auto.get_all_category/home_icon/" data-text="<i class='fas fa-home'></i> Danh mục" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			<li class="cf">
				<div class="lf f80"><i class="fas fa-bars"></i> Danh mục <em class="small">(lấy toàn bộ chuyên mục đang có trên hệ thống, đính kèm cả icon, dùng cho việc tạo dropdown menu)</em></div>
				<div class="lf f20 text-center">
					<button data-link="/auto.get_all_category/bars/" data-text="<i class='fas fa-bars'></i> Danh mục" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			<li class="cf">
				<div class="lf f80"><i class="fas fa-bars"></i> Danh mục <i class="fa fa-caret-down"></i> <em class="small">(lấy toàn bộ chuyên mục đang có trên hệ thống, đính kèm cả icon, dùng cho việc tạo dropdown menu - mẫu 2)</em></div>
				<div class="lf f20 text-center">
					<button data-link="/auto.get_all_category/caret/" data-text="<i class='fas fa-bars'></i> Danh mục <i class='fa fa-caret-down'></i>" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			<li class="cf">
				<div class="lf f80">Đã thông báo với BCT (xanh)</div>
				<div class="lf f20 text-center">
					<button data-link="#" data-text="&lt;img src='<?php echo basename( WP_CONTENT_DIR ); ?>/echbaydotcom/images-global/dathongbao.png' width='200' height='76'&gt;" data-css="dathongbao-voi-bct img-max-width" data-target="_blank" data-rel="nofollow" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
			<li class="cf">
				<div class="lf f80">Đã đăng ký với BCT (đỏ)</div>
				<div class="lf f20 text-center">
					<button data-link="#" data-text="&lt;img src='<?php echo basename( WP_CONTENT_DIR ); ?>/echbaydotcom/images-global/dadangky.png' width='200' height='75'&gt;" data-css="dadangky-voi-bct img-max-width" data-target="_blank" data-rel="nofollow" type="button" class="cur click-to-add-custom-link"><i class="fas fa-plus"></i></button>
				</div>
			</li>
		</ul>
	</div>
</div>
<!-- Nút nhân bản bài viết, trang -> kết hợp với plugin Pót duplicator -->
<div id="wgr-for-duplicator" class="d-none">
	<button type="button" title="Hệ thống sẽ copy một sản phẩm tương tự sản phẩm này" class="button button-primary button-orgprimary button-large click-set-nhanban cur"><i class="fa fa-copy"></i> Nhân bản</button>
	<div class="show-if-duplicator-null d-none">
		<div>Plugin <strong>Post Duplicator</strong> chưa được cài đặt hoặc định dạng bài viết này chưa được hỗ trợ Nhân bản! Hãy <a href="<?php echo admin_link; ?>plugin-install.php?s=Post+Duplicator&tab=search&type=term">bấm vào đây</a> tìm rồi cài đặt plugin có tên là: <strong class="redcolor">Post Duplicator</strong>.</div>
	</div>
</div>
