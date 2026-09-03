<?php

/**
 * Template part for site preloader
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Convis
 */


if (defined('ELEMENTOR_VERSION') && \Elementor\Plugin::$instance->preview->is_preview_mode()) {
    echo '';
} else {
?>
    <div class="preloader">
        <div class="custom-loader"></div>
    </div>
<?php
}
