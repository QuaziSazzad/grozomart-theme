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

$error_title    = Helper::get_option('error_title', esc_html__('"Oops! It looks like something went wrong."', 'grozomart'));
$error_message  = Helper::get_option('error_bottom_message', esc_html__('The page you are looking for does not exist or has been moved', 'grozomart'));
$button_text    = Helper::get_option('error_button_text', esc_html__('Go to Home', 'grozomart'));
$error_page_image = Helper::get_option('error_page_image', ['url' => GROZOMART_ASSETS . '/img/error-404.png']);

?>
<!-- 404 Error Area Start -->
<section class="404-error-area py-130 rpy-100 rel z-1">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-9">
                <div class="error-content text-center">
                    <?php if (!empty($error_page_image['url'])): ?>
                        <img src="<?php echo esc_url($error_page_image['url']); ?>" alt="<?php esc_attr_e('404 Error', 'grozomart'); ?>">
                    <?php endif; ?>
                    <?php if ($error_title): ?>
                        <h2><?php echo esc_html($error_title); ?></h2>
                    <?php endif; ?>
                    <?php if ($error_message): ?>
                        <p><?php echo esc_html($error_message); ?></p>
                    <?php endif; ?>

                    <a href="<?php echo esc_url(home_url('/')); ?>" class="theme-btn mt-15"><?php echo esc_html($button_text); ?></a>

                </div>
            </div>
        </div>
    </div>
</section>
<!-- 404 Error Area End -->
<?php
get_footer();
