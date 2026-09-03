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

$back_top_class = 'back-to-top';

if (Helper::get_option('back_to_top_mobile', true)) {
    $back_top_class = 'back-to-top show-on-mobile';
}

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

<?php wp_footer(); ?>

</body>

</html>