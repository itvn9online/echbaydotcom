<?php
/**
 * The template for displaying the footer.
 *
 * @package flatsome
 * @daidq - 0984533228 - itvn9online@gmail.com
 * Chỉnh sửa và phát triển theo hướng chuyên cho thị trường Việt Nam
 */

global $flatsome_opt;
?>
</main>
<footer id="footer" class="footer-wrapper">
    <?php do_action('flatsome_footer'); ?>
</footer>
</div>
<div id="fb-root"></div>
<?php


global $__cf_row;
echo $__cf_row[ 'cf_js_allpage' ];


wp_footer();


require __DIR__ . '/footer_cache_quick_cart.php';

?>
<div id="oi_popup"></div>
<script type="text/javascript" src="wp-content/echbaydotcom/javascript/analytics.js" defer></script>
</body>
</html>
<?php

/*
 * Bên trên là footer của flatsome
 */

// kết thúc website -> in ra cache nếu có
require __DIR__ . '/footer_cache.php';