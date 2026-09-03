<?php

/**
 * Template Help center
 *
 * Help Center Template for admin panel
 *
 * @package Grozomart
 */

$allowed_html = [
    'a' => [
        'href'   => true,
        'target' => true,
    ],
];

?>
<div class="grozomart-dashboard-pages grozomart-helper-center-page">
    <div class="grozomart-help-boxes">
        <div class="help-box doc-box" style="background-image: url( <?php echo GROZOMART_ASSETS . '/img/doc-bg.jpg' ?> );">
            <div class="img">
                <img src="<?php echo esc_url(GROZOMART_ASSETS . '/img/doc-img.png') ?>" alt="<?php esc_attr_e('Documentation', 'grozomart') ?>">
            </div>
            <a href="<?php echo esc_url('https://webtend-support.gitbook.io/docs/') ?>" target="_blank" class="help-center-btn"><?php esc_html_e('Documentation', 'grozomart') ?></a>
        </div>
        <div class="help-box support-box" style="background-image: url( <?php echo GROZOMART_ASSETS . '/img/support-bg.jpg' ?> );">
            <div class="img">
                <img src="<?php echo esc_url(GROZOMART_ASSETS . '/img/support-img.png') ?>" alt="<?php esc_attr_e('Documentation', 'grozomart') ?>">
            </div>
            <a href="<?php echo esc_url('https://themeforest.net/user/webtend#contact') ?>" target="_blank" class="help-center-btn"><?php esc_html_e('Get Support', 'grozomart') ?></a>
        </div>
    </div>
</div>