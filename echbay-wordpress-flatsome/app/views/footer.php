<?php

/**
 * The template for displaying the footer.
 *
 * @package flatsome
 * @daidq - 0984533228 - itvn9online@gmail.com
 * Chỉnh sửa và phát triển theo hướng chuyên cho thị trường Việt Nam
 */

// die(__FILE__ . ':' . __LINE__);

/**
 * Thằng elementor nó có quả update làm xung đột với code cache nên phải thêm đoạn này để xử lý lỗi
 * Nếu bắt buộc phải dùng thì chuyển sang dùng w3-cache
 */
// lấy nội dung trước đó, để phòng elementor nó clear
// $before_footers_content = ob_get_contents();
// ob_end_clean();

// lấy nội dung tiếp theo trong footer
// ob_start();

global $flatsome_opt;
?>
</main>
<footer id="footer" class="footer-wrapper">
    <?php do_action('flatsome_footer'); ?>
</footer>
</div>
<!-- <div id="fb-root"></div> -->
<?php


global $__cf_row;
echo $__cf_row['cf_js_allpage'];


wp_footer();


require __DIR__ . '/footer_cache_quick_cart.php';

?>
<div id="oi_popup"></div>
<!-- <script type="text/javascript" src="wp-content/echbaydotcom/javascript/analytics.js" defer></script> -->
</body>

</html>
<?php

//
// $footers_content = ob_get_contents();

// ob_end_clean();
// END ob footer

// in lại
// ob_start();
// echo $before_footers_content . $footers_content;

/**
 * Bên trên là footer của flatsome
 */

// kết thúc website -> in ra cache nếu có
require __DIR__ . '/footer_cache.php';
