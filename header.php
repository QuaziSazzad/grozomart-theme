<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Grozomart
 */

use GrozomartTheme\Classes\Grozomart_Helper as Helper;
use GrozomartTheme\Classes\Grozomart_Woocommerce as Grozomart_Woocommerce;

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div id="tekprof-page" class="tekprof-body-content">
		<?php
		if ('enabled' === Helper::get_option('site_preloader', 'enabled')) {
			get_template_part('template-parts/preloader');
		}

		if (class_exists('Grozomart_Toolkit')) {
			do_action("grozomart_builder_before_main");
		}

		if ('enabled' === Helper::check_default_header()) {
			get_template_part('template-parts/header/header', 'default');
		}

		if (class_exists('Woocommerce')) {
			Grozomart_Woocommerce::mini_cart_sidebar();
		}
		?>
		<main id="tekprof-content" class="tekprof-content-area">
			<?php get_template_part('template-parts/page-title'); ?>