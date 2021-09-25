<?php
/**
 * The template for displaying the footer.
 *
 * @package flatsome
 */

global $flatsome_opt;
?>

</main>

<footer id="footer" class="footer-wrapper">

	<?php do_action('flatsome_footer'); ?>

</footer>

</div>

<?php


global $__cf_row;
echo $__cf_row ['cf_js_allpage'];


wp_footer();

?>

</body>
</html><?php

/*
* Bên trên là footer của flatsome
*/

// kết thúc website -> in ra cache nếu có
require __DIR__ . '/footer_cache.php';
