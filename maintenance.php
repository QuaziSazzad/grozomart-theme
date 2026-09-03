<?php

/**
 * The template for maintenance mode
 *
 * @package Grozomart
 */

use GrozomartTheme\Classes\Grozomart_Helper as Helper;

wp_head();

$maintenance_img      = Helper::get_option('maintenance_img', ['url' => GROZOMART_ASSETS . '/img/maintenance.png']);
$maintenance_title    = Helper::get_option('maintenance_title', __('The site is currently down for maintenance', 'grozomart'));
$maintenance_subtitle = Helper::get_option('maintenance_subtitle', __('We apologize for any inconvenience caused', 'grozomart'));
$maintenance_page     = Helper::get_option('maintenance_page');
?>
<div class="tekprof-maintenance-page">
    <div class="container">
        <div class="maintenance-content">
            <div class="maintenance-img">
                <img src="<?php echo esc_url($maintenance_img['url']) ?>" alt="<?php esc_attr_e('Maintenance Mode', 'grozomart') ?>">
            </div>
            <h2 class="maintenance-title">
                <?php echo esc_html($maintenance_title); ?>
            </h2>
            <p><?php echo esc_html($maintenance_subtitle); ?></p>
        </div>
    </div>
</div>
<?php
wp_footer();
