<?php

/**
 * Template part for displaying Main Header Button
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Grozomart
 */

use GrozomartTheme\Classes\Grozomart_Helper as Helper;

$header_button     = Helper::get_option('header_button', 'disabled');
$button_text       = Helper::get_option('button_text', esc_html__('Get In Touch', 'grozomart'));
$button_url        = Helper::get_option('button_url', '#');

if ('enabled' === $header_button) : ?>
    <a href="<?php echo esc_url($button_url); ?>" class="theme-btn small-btn"><?php echo esc_html($button_text); ?></a>
<?php endif;
