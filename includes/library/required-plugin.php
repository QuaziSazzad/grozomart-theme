<?php

/**
 * Required Plugin for Grozomart theme
 *
 * @package Grozomart
 */

if (! defined('ABSPATH')) {
	exit('No direct script access allowed');
}

add_action('tgmpa_register', 'grozomart_register_required_plugins');
function grozomart_register_required_plugins()
{
	$plugins = [
		[
			'name'     => esc_html__('Elementor Website Builder', 'grozomart'),
			'slug'     => 'elementor',
			'required' => true,
			'version'  => '3.12',
		],
		[
			'name'     => esc_html__('Grozomart Toolkit', 'grozomart'),
			'slug'     => 'grozomart-toolkit',
			'source'   => 'https://wp.webtend.net/grozomart/tf-data/grozomart-toolkit.zip',
			'required' => true,
			'version'  => '1.0.0',
		],
		[
			'name'     => esc_html__('Contact Form 7', 'grozomart'),
			'slug'     => 'contact-form-7',
			'required' => false,
		],
		[
			'name'     => esc_html__('Breadcrumb NavXT', 'grozomart'),
			'slug'     => 'breadcrumb-navxt',
			'required' => false,
		],
		[
			'name'     => esc_html__('WooCommerce', 'grozomart'),
			'slug'     => 'woocommerce',
			'required' => false,
		],
		[
			'name'     => esc_html__('One Click Demo Import', 'grozomart'),
			'slug'     => 'one-click-demo-import',
			'required' => false,
		],
	];

	$config = [
		'id'           => 'grozomart_theme_plugins',
		'default_path' => '',
		'menu'         => 'grozomart_required_plugins',
		'parent_slug'  => 'grozomart_dashboard',
		'capability'   => 'manage_options',
		'has_notices'  => true,
		'dismissable'  => true,
		'dismiss_msg'  => '',
		'is_automatic' => false,
		'message'      => '',
		'strings'      => [
			'menu_title' => esc_html__('Required Plugins', 'grozomart'),
			'page_title' => esc_html__('Required Plugins', 'grozomart'),
		],
	];

	tgmpa($plugins, $config);
}
