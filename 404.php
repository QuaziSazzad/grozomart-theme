<?php

/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Grozomart
 */

use GrozomartTheme\Classes\Grozomart_Helper as Helper;

get_header();

/**
 * The title keeps a little inline markup so the design's two-weight heading
 * survives: <span> is the light-weight run and <br> controls the line break.
 * Anything else entered in the option is stripped.
 */
$grozomart_error_title = Helper::get_option(
    'error_title',
    sprintf(
        /* translators: %s: the lighter-weight opening line of the 404 heading. */
        __('%s looking <br> for isn\'t here.', 'grozomart'),
        '<span>' . esc_html__('Sorry, the page you\'re', 'grozomart') . '</span>'
    )
);

$grozomart_error_message = Helper::get_option(
    'error_bottom_message',
    esc_html__('Don\'t worry—use the navigation menu or return to the homepage to continue exploring. We\'re here to help you get back on track quickly and easily.', 'grozomart')
);

$grozomart_error_button = Helper::get_option('error_button_text', esc_html__('Back to Home', 'grozomart'));

$grozomart_error_image = Helper::get_option('error_page_image', ['url' => GROZOMART_ASSETS . '/img/404.png']);
?>
<!--404 Section Start -->
<section class="error-section fix section-padding">
    <div class="container">
        <div class="error-items">
            <?php if (! empty($grozomart_error_image['url'])) : ?>
                <div class="thumb wow fadeInUp" data-wow-delay=".3s">
                    <img src="<?php echo esc_url($grozomart_error_image['url']); ?>" alt="<?php esc_attr_e('Page not found', 'grozomart'); ?>">
                </div>
            <?php endif; ?>
            <div class="content">
                <?php if ($grozomart_error_title) : ?>
                    <h1 class="title wow fadeInUp">
                        <?php
                        echo wp_kses(
                            $grozomart_error_title,
                            [
                                'span' => [],
                                'br'   => [],
                            ]
                        );
                        ?>
                    </h1>
                <?php endif; ?>
                <?php if ($grozomart_error_message) : ?>
                    <p class="wow fadeInUp" data-wow-delay=".2s">
                        <?php echo esc_html($grozomart_error_message); ?>
                    </p>
                <?php endif; ?>
                <?php if ($grozomart_error_button) : ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="theme-btn wow fadeInUp" data-wow-delay=".4s">
                        <?php echo esc_html($grozomart_error_button); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<!--404 Section End -->
<?php
get_footer();
