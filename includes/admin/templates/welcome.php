<?php

/**
 * Template Welcome
 *
 * Welcome Template for admin panel
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

<div class="tekprof-dashboard-pages tekprof-welcome-page">
    <div class="tekprof-welcome-wrapper">
        <div class="wrapper-left">
            <div class="theme-screenshot">
                <img src="<?php echo esc_url(get_template_directory_uri() . "/screenshot.png"); ?>" alt="<?php esc_attr_e('Screenshot', 'grozomart'); ?>">
            </div>
        </div>
        <div class="wrapper-right">
            <div class="tekprof-welcome-title">
                <h3>
                    <?php esc_html_e('Welcome to', 'grozomart'); ?>
                    <?php echo esc_html(wp_get_theme()->get('Name')); ?>

                    <span class="version-theme">
                        <?php esc_html_e('Version - ', 'grozomart'); ?>
                        <?php echo esc_html(wp_get_theme()->get('Version')); ?>
                    </span>
                    <?php if (is_child_theme()) : ?>
                        <span class="version-theme">
                            <?php esc_html_e('Parent Theme Version - ', 'grozomart'); ?>
                            <?php echo GROZOMART_VERSION; ?>
                        </span>
                    <?php endif; ?>
                </h3>
                <p>
                    <?php echo sprintf(__('%s is already installed and ready to use! Let\'s build something impressive.', 'grozomart'), GROZOMART_NAME); ?>
                </p>
            </div>
            <h6 class="tekprof-welcome-step-title"><?php echo __('Just complete the steps below:', 'grozomart'); ?></h6>
            <ul>
                <li>
                    <span class="step-title">
                        <?php esc_html_e('Step 1', 'grozomart'); ?>
                    </span>
                    <?php echo sprintf(
                        wp_kses(__('Check <a href="%s">Server status</a> to avoid errors with your WordPress.', 'grozomart'), $allowed_html),
                        esc_url(admin_url('admin.php?page=grozomart_server_status'))
                    ); ?>
                </li>
                <li>
                    <span class="step-title">
                        <?php esc_html_e('Step 2', 'grozomart'); ?>
                    </span>
                    <?php esc_html_e('Install Required and recommended plugins.', 'grozomart'); ?>
                </li>
                <li>
                    <span class="step-title">
                        <?php esc_html_e('Step 3', 'grozomart'); ?>
                    </span>
                    <?php esc_html_e('Import demo content', 'grozomart'); ?>
                </li>
            </ul>
        </div>
    </div>
</div>