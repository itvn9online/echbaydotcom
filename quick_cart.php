<!-- quick cart -->
<?php

// chỉ hiển thị quick cart ở rong trang sản phẩm và trang phải không được đặt là tin tức
if ( $pid > 0 && $__post->post_type == 'post' && $__cf_row[ 'cf_set_news_version' ] != 1 ) {

    ?>
<div id="click_show_cpa">
    <div class="cart-quick">
        <div class="cart-quick-close">
            <div onClick="close_img_quick_video_details();" class="cur"><i class="fa fa-close"></i></div>
        </div>
        <div class="cart-quick-title bold upper"><?php echo EBE_get_lang('cart_muangay'); ?></div>
        <div class="cart-quick-padding">
            <form name="frm_cart" method="post" action="process/?set_module=booking" target="target_eb_iframe" onsubmit="return _global_js_eb.check_cart();" class="eb-global-frm-cart">
                <div id="cart_user_agent" class="d-none">
                    <input type="text" name="t_muangay[]" value="0" />
                    <input type="text" name="t_size[]" value="" />
                    <input type="text" name="t_color[]" value="" />
                    <input type="text" name="t_new_price[]" value="0" />
                    <input type="text" name="t_discount_value" value="" />
                </div>
                <div class="eb-quickcart-table">
                    <div class="eb-quickcart-node cf show-if-color-exist product-color d-none">
                        <div class="eb-quickcart-left"><?php echo EBE_get_lang('cart_mausac'); ?></div>
                        <div class="eb-quickcart-right">
                            <div class="oi_product_color">
                                <ul class="cf">
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="eb-quickcart-node cf show-if-size-exist d-none">
                        <div class="eb-quickcart-left"><?php echo EBE_get_lang('cart_kichco'); ?></div>
                        <div class="eb-quickcart-right">
                            <div class="eb-product-size oi_product_size">
                                <ul class="cf">
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="eb-quickcart-node eb-quickcart-quan cf">
                        <div class="eb-quickcart-left"><?php echo EBE_get_lang('cart_soluong'); ?></div>
                        <div id="oi_change_soluong" class="eb-quickcart-right"></div>
                    </div>
                    <div class="eb-quickcart-node eb-quickcart-price cf">
                        <div class="eb-quickcart-left"><?php echo EBE_get_lang('cart_thanhtien'); ?></div>
                        <div class="eb-quickcart-right redcolor"><span id="oi_change_tongtien" class="ebe-currency bold"></span> <span id="oi_details_zero_price" class="bold d-none"><?php echo EBE_get_lang('post_zero'); ?></span></div>
                    </div>
                    <div class="eb-quickcart-node eb-quickcart-fullname cf">
                        <div class="eb-quickcart-left"><?php echo EBE_get_lang('cart_hoten'); ?></div>
                        <div class="eb-quickcart-right">
                            <input type="text" name="t_ten" value="" placeholder="<?php echo EBE_get_lang('cart_hoten'); ?>"<?php if ( $__cf_row['cf_required_name_cart'] == 1 ) { echo ' aria-required="true" required'; } ; ?> />
                        </div>
                    </div>
                    <div class="eb-quickcart-node eb-quickcart-tel cf">
                        <div class="eb-quickcart-left"><?php echo EBE_get_lang('cart_dienthoai'); ?> <span class="redcolor d-none">*</span></div>
                        <div class="eb-quickcart-right">
                            <input type="text" name="t_dienthoai" value="" placeholder="<?php echo EBE_get_lang('cart_pla_dienthoai'); ?>"<?php if ( $__cf_row['cf_required_phone_cart'] == 1 ) { echo ' aria-required="true" required'; } ; ?> />
                        </div>
                    </div>
                    <div class="eb-quickcart-node eb-quickcart-email cf">
                        <div class="eb-quickcart-left">Email</div>
                        <div class="eb-quickcart-right">
                            <input type="email" name="t_email" value="" placeholder="Email"<?php if ( $__cf_row['cf_required_email_cart'] == 1 ) { echo ' aria-required="true" required'; } ; ?> />
                        </div>
                    </div>
                    <div class="eb-quickcart-node eb-quickcart-address cf">
                        <div class="eb-quickcart-left"><?php echo EBE_get_lang('cart_diachi'); ?></div>
                        <div class="eb-quickcart-right">
                            <textarea name="t_diachi" placeholder="<?php echo EBE_get_lang('cart_diachi2'); ?>"<?php if ( $__cf_row['cf_required_address_cart'] == 1 ) { echo ' aria-required="true" required'; } ; ?>></textarea>
                        </div>
                    </div>
                    <!--
					<div class="eb-quickcart-node eb-quickcart-districts cf">
						<div class="eb-quickcart-left"><?php echo EBE_get_lang('cart_tinhthanh'); ?></div>
						<div class="eb-quickcart-right">
							<textarea name="t_diachi" placeholder="<?php echo EBE_get_lang('cart_tinhthanh2'); ?>" class="auto-set-cart-districts"<?php if ( $__cf_row['cf_required_address_cart'] == 1 ) { echo ' aria-required="true" required'; } ; ?>></textarea>
						</div>
					</div>
					-->
                    <div class="eb-quickcart-node eb-quickcart-note cf">
                        <div class="eb-quickcart-left"><?php echo EBE_get_lang('cart_ghichu'); ?></div>
                        <div class="eb-quickcart-right">
                            <textarea name="t_ghichu" placeholder="<?php echo EBE_get_lang('cart_vidu'); ?>"></textarea>
                        </div>
                    </div>
                    <div class="eb-quickcart-node eb-quickcart-coupon d-none cf">
                        <div class="eb-quickcart-left"><?php echo EBE_get_lang('cart_discount_code'); ?></div>
                        <div class="eb-quickcart-right">
                            <input type="text" name="t_discount_code" value="" placeholder="<?php echo EBE_get_lang('cart_discount_code'); ?>" class="auto-check-discount-code" />
                        </div>
                    </div>
                    <div class="eb-quickcart-node eb-quickcart-shipping_fee d-none cf">
                        <div class="eb-quickcart-left"><?php echo EBE_get_lang('cart_shipping_fee'); ?></div>
                        <div class="eb-quickcart-right"><?php echo EBE_get_lang('cart_shipping_content'); ?></div>
                    </div>
                    <div class="eb-quickcart-node eb-quickcart-payment-method">
                        <div class="eb-quickcart-left">&nbsp;</div>
                        <div class="eb-quickcart-right">
                            <div class="cart-pay-method">
                                <div class="cart-paymethod-cod">
                                    <label>
                                        <input type="radio" name="t_thanhtoan" value="cod" checked>
                                        <?php echo EBE_get_lang('cart_payment_cod'); ?></label>
                                </div>
                                <div class="cart-paymethod-tt">
                                    <label>
                                        <input type="radio" name="t_thanhtoan" value="tructiep">
                                        <?php echo EBE_get_lang('cart_payment_tt'); ?></label>
                                </div>
                                <div class="cart-paymethod-ib">
                                    <label>
                                        <input type="radio" name="t_thanhtoan" value="bank">
                                        <?php echo EBE_get_lang('cart_payment_bank'); ?></label>
                                </div>
                                <div class="cart-paymethod-paygate">
                                    <div class="show-if-pay-baokim d-none">
                                        <label>
                                            <input type="radio" name="t_thanhtoan" value="bk">
                                            <?php echo EBE_get_lang('cart_payment_bk'); ?></label>
                                    </div>
                                    <div class="show-if-pay-nganluong d-none">
                                        <label>
                                            <input type="radio" name="t_thanhtoan" value="nl">
                                            <?php echo EBE_get_lang('cart_payment_nl'); ?></label>
                                    </div>
                                    <div class="show-if-pay-paypal d-none">
                                        <label>
                                            <input type="radio" name="t_thanhtoan" value="pp">
                                            <?php echo EBE_get_lang('cart_payment_pp'); ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    if ( EBE_get_lang( 'url_chinhsach' ) != '#' ) {
                        ?>
                    <p class="l19 small">
                        <input type="checkbox" name="t_dongy" checked>
                        <?php echo str_replace( '{tmp.url_chinhsach}', EBE_get_lang('url_chinhsach'), EBE_get_lang('chinhsach') ); ?></p>
                    <?php
                    }
                    ?>
                    <div class="text-center eb-quickcart-submit">
                        <button type="button" class="btn-addto-cart default-2bg div-jquery-add-to-cart click-jquery-add-to-cart"><?php echo EBE_get_lang('cart_them'); ?></button>
                        <button type="submit" id="sb_submit_cart" class="default-bg cur"><?php echo EBE_get_lang('cart_gui'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php

// end if for quikc cart
}

?>
<!-- quick view -->
<div id="oi_ebe_quick_view" class="ebe-quick-view">
    <div class="quick-view-margin <?php echo $__cf_row['cf_post_class_style']; ?>">
        <div class="quick-view-close"><i onclick="close_ebe_quick_view();" class="fa fa-close cur"></i></div>
        <div class="quick-view-padding"></div>
    </div>
</div>
