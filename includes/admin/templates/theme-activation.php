<?php

/**
 * Template Theme Activation
 *
 * Required plugins Template for admin panel
 *
 * @package Grozomart
 */

use GrozomartTheme\Classes\Grozomart_Helper;

$allowed_html = [
    'b',
    'a' => [
        'href'   => true,
        'target' => true,
    ],
];

$theme_data = Grozomart_Helper::is_theme_active();
?>
<div class="tekprof-dashboard-pages tekprof-theme-activation-page">
    <div class="tekprof-activation-wrapper">
        <div class="activation-form-wrap">
            <?php if (! $theme_data['theme_active']): ?>
                <div class="activation-form">
                    <h4><?php esc_html_e('Activate your license', 'grozomart'); ?></h4>
                    <p><?php esc_html_e('Please activate your license to get access to pre-build design', 'grozomart'); ?></p>
                    <form class="tekprof-activation" action="<?php echo esc_url(admin_url('admin.php?page=grozomart_theme_activation')); ?>" method="post">
                        <div class="from-fields">
                            <input type="text" placeholder="<?php esc_attr_e('Enter Your Purchase Code', 'grozomart'); ?>" name="purchase_code" required />
                            <input type="text" placeholder="<?php esc_attr_e('Your Envato Username', 'grozomart'); ?>" name="username" required />
                            <input type="email" placeholder="<?php esc_attr_e('Your Envato User Email', 'grozomart'); ?>" name="user_email" required />
                        </div>
                        <?php wp_nonce_field('theme-activation', 'activation_nonce'); ?>
                        <button type="submit" class="activate-license" value="submit">
                            <span class="button-text"><?php esc_html_e('Activate Theme', 'grozomart'); ?></span>
                            <span class="loading-text"><?php esc_html_e('Please wait..', 'grozomart'); ?></span>
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="deactivation-form">
                    <div class="activation-theme-msg">
                        <h3>
                            <span><?php esc_html_e('Thank you!', 'grozomart'); ?></span>
                            <?php esc_html_e('Your theme\'s license is activated successfully.', 'grozomart'); ?>
                        </h3>
                    </div>
                    <form class="tekprof-deactivation" action="<?php echo esc_url(admin_url('admin.php?page=grozomart_theme_activation')); ?>" method="post">
                        <input type="hidden" name="token" value="<?php echo esc_attr($theme_data['token']) ?>">
                        <?php wp_nonce_field('theme-deactivation', 'deactivation_nonce'); ?>
                        <button type="submit" class="deactivate-license" value="submit">
                            <span class="button-text"><?php esc_html_e('Deactivate Theme', 'grozomart'); ?></span>
                            <span class="loading-text"><?php esc_html_e('Please wait..', 'grozomart'); ?></span>
                        </button>
                    </form>
                </div>
            <?php endif; ?>
            <p class="license-note">
                <?php echo sprintf(
                    wp_kses(__('<b>Important Notice!</b> Only one standard license is considered valid for one website. Running more than 1 website on a single license is a gross infringement or violation of license.', 'grozomart'), $allowed_html)
                ); ?>
            </p>
        </div>
        <div class="activation-help-wrap">
            <h4><?php esc_html_e('Where Can I Find My Purchase Code?', 'grozomart') ?></h4>
            <ul>
                <li>
                    <?php echo sprintf(
                        wp_kses(__('Please go to Visit http : <a href="%s" target="_blank">https://themeforest.net/downloads</a>', 'grozomart'), $allowed_html),
                        esc_url('https://themeforest.net/downloads')
                    ); ?>
                </li>
                <li>
                    <?php echo sprintf(__('Click the Download button in "%s" row.', 'grozomart'), GROZOMART_NAME); ?>
                </li>
                <li><?php esc_html_e('Select License  Certificate & Purchase code.', 'grozomart') ?></li>
                <li><?php esc_html_e('Select & Copy item Purchase code.', 'grozomart') ?></li>
                <li>
                    <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-"
                        target="_blank">
                        <?php esc_html_e('Read How to find purchase code?', 'grozomart'); ?>
                    </a>
                </li>
                <li>
                    <a href="https://webtend-support.gitbook.io/docs/getting-started/theme-activation" target="_blank">
                        <?php esc_html_e('Read More About Theme Activation?', 'grozomart'); ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>