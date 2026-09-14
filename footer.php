<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Grozomart
 */

use GrozomartTheme\Classes\Grozomart_Helper as Helper;

$grozomart_show_back_top = Helper::show_back_to_top();

$grozomart_back_top_class = 'back-to-top';

// Without this class the button is hidden on small screens by CSS.
if (Helper::get_option('back_to_top_mobile', true)) {
    $grozomart_back_top_class .= ' show-on-mobile';
}

$grozomart_back_top_icon = Helper::get_option('back_to_top_icon', 'fa-regular fa-arrow-up');
?>
</main>
<?php

if (class_exists('Grozomart_Toolkit')) {
    do_action("grozomart_builder_after_main");
}

if ('enabled' === Helper::check_default_footer()) {
    get_template_part('template-parts/footer/footer', 'default');
}
?>
</div>

<?php if ($grozomart_show_back_top) : ?>
    <!-- Back To Top Start -->
    <button id="back-top" class="<?php echo esc_attr($grozomart_back_top_class); ?>" aria-label="<?php esc_attr_e('Back to top', 'grozomart'); ?>">
        <?php if ($grozomart_back_top_icon) : ?>
            <i class="<?php echo esc_attr($grozomart_back_top_icon); ?>"></i>
        <?php endif; ?>
    </button>
    <!-- Back To Top End -->
<?php endif; ?>

<?php wp_footer(); ?>

</body>

</html>