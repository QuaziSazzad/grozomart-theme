<?php

namespace GrozomartTheme\Classes;

defined('ABSPATH') || exit;

/**
 * Initial Helper functions for this theme.
 */
class Grozomart_Helper
{

	/**
	 * Get Theme Options
	 */
	public static function get_option($option, $default = null)
	{
		$options = get_option('grozomart_options');

		return (isset($options[$option])) ? $options[$option] : $default;
	}

	/**
	 * Get a metaboxes
	 */
	public static function get_meta($prefix_key, $meta_key, $default = null, $id = '')
	{
		if (! $id) {
			$id = get_the_ID();
		}

		$meta_boxes = get_post_meta($id, $prefix_key, true);

		return (isset($meta_boxes[$meta_key])) ? $meta_boxes[$meta_key] : $default;
	}

	/**
	 * Check is theme active or Not
	 */
	public static function is_theme_active()
	{
		$theme_data = get_option('grozomart_theme_verify', []);

		$return_data = [
			'theme_active' => true,
			'token'        => '',
		];

		if (is_array($theme_data) && ! empty($theme_data['token'])) {
			$return_data = [
				'theme_active' => true,
				'token'        => $theme_data['token'],
			];
		}

		return $return_data;
	}

	/**
	 * Check if this Page created with elementor
	 */
	public static function is_elementor_page()
	{
		global $post;
		$is_elementor = false;

		if (\class_exists('\Elementor\Plugin')) {
			$is_elementor = \Elementor\Plugin::$instance->documents->get($post->ID)->is_built_with_elementor();
		}

		return $is_elementor;
	}

	/**
	 * Get content layout
	 */
	public static function site_layout()
	{
		$layout = self::get_option('site_layout', 'full-width');

		if (is_page()) {
			$page_layout = self::get_meta('grozomart_page_meta', 'site_layout', 'default');
			$layout      = ('default' !== $page_layout) ? $page_layout : $layout;
		} elseif (is_single()) {
			$post_type = get_post_type();

			switch ($post_type) {
				case 'grozomart_portfolio':
					$portfolio_layout = self::get_meta('grozomart_portfolio_meta', 'portfolio_details_layout', 'default');
					$layout           = ('default' !== $portfolio_layout) ? $portfolio_layout : $layout;
					break;
				case 'product':
					$product_layout = self::get_meta('grozomart_product_meta', 'product_details_layout', 'default');
					$layout         = ('default' !== $product_layout) ? $product_layout : $layout;
					break;
				case 'post':
					$post_layout = self::get_meta('grozomart_post_meta', 'post_details_layout', 'default');
					$layout      = ('default' !== $post_layout) ? $post_layout : $layout;
					break;
			}
		}

		return $layout;
	}

	/**
	 * Get Content Sidebar
	 */
	public static function content_sidebar()
	{
		$sidebar = 'right-sidebar';

		if (is_page()) {
			$sidebar = 'no-sidebar';
		} elseif (is_single() && 'post' === get_post_type()) {
			$sidebar      = self::get_option('blog_details_sidebar', 'right-sidebar');
			$post_sidebar = self::get_meta('grozomart_post_meta', 'post_details_sidebar', 'default');
			$sidebar      = ('default' !== $post_sidebar) ? $post_sidebar : $sidebar;
		} elseif (! is_page()) {
			$sidebar = self::get_option('blog_archive_sidebar', 'right-sidebar');
		}

		if (! is_active_sidebar('primary_sidebar')) {
			$sidebar = 'no-sidebar';
		}

		return $sidebar;
	}

	/**
	 * Container Classes
	 */
	public static function container()
	{
		$classes = ['container'];

		if (is_page() || (is_single() && 'grozomart_portfolio' == get_post_type())) {
			if (self::is_elementor_page()) {
				$classes[] = 'container-elementor';
			} else {
				$classes[] = 'container-gap';
			}
		} elseif (is_archive() && 'grozomart_portfolio' === get_post_type()) {
			$classes[] = 'container-gap no-sidebar';
		} elseif ((is_archive() || is_single()) && 'product' === get_post_type()) {
			$classes[] = 'container-gap no-sidebar';
		} else {
			$content_sidebar = self::content_sidebar();
			$classes[]       = 'container-gap';

			if ('left-sidebar' === $content_sidebar || 'right-sidebar' === $content_sidebar) {
				$classes[] = 'have-sidebar ' . $content_sidebar;
			} elseif ('no-sidebar' === $content_sidebar) {
				$classes[] = 'no-sidebar';
			}
		}

		echo esc_attr(implode(' ', $classes));
	}

	/**
	 * Col Size
	 */
	public static function col_size()
	{
		$class = 'col-lg-8';
		$content_sidebar = self::content_sidebar();
		if ($content_sidebar == 'no-sidebar') {
			$class = 'col-lg-12';
		}
		echo esc_attr($class);
	}

	/**
	 * Get Mysql Version
	 */
	public static function mysql_version()
	{
		global $wpdb;
		return ! empty($wpdb->is_mysql) ? $wpdb->db_version() : '';
	}

	/**
	 * Check Theme Default Header
	 */
	public static function check_default_header()
	{
		$default_header = self::get_option('default_header', 'enabled');

		if (is_page()) {
			$page_default_header = self::get_meta('grozomart_page_meta', 'page_default_header', 'default');
			$default_header      = ('default' !== $page_default_header) ? $page_default_header : $default_header;
		} elseif (is_single()) {
			$post_type = get_post_type();

			switch ($post_type) {
				case 'grozomart_portfolio':
					$portfolio_default_header = self::get_meta('grozomart_portfolio_meta', 'portfolio_default_header', 'default');
					$default_header           = ('default' !== $portfolio_default_header) ? $portfolio_default_header : $default_header;
					break;
				case 'product':
					$product_default_header = self::get_meta('grozomart_product_meta', 'product_default_header', 'default');
					$default_header         = ('default' !== $product_default_header) ? $product_default_header : $default_header;
					break;
				case 'post':
					$post_default_header = self::get_meta('grozomart_post_meta', 'post_default_header', 'default');
					$default_header      = ('default' !== $post_default_header) ? $post_default_header : $default_header;
					break;
			}
		}

		return $default_header;
	}

	/**
	 * Check Default Footer
	 */
	public static function check_default_footer()
	{
		$default_footer = self::get_option('default_footer', 'enabled');

		if (is_page()) {
			$page_default_footer = self::get_meta('grozomart_page_meta', 'page_default_footer', 'default');
			$default_footer      = ('default' !== $page_default_footer) ? $page_default_footer : $default_footer;
		} elseif (is_single()) {
			$post_type = get_post_type();

			switch ($post_type) {
				case 'grozomart_portfolio':
					$portfolio_default_footer = self::get_meta('grozomart_portfolio_meta', 'portfolio_default_footer', 'default');
					$default_footer           = ('default' !== $portfolio_default_footer) ? $portfolio_default_footer : $default_footer;
					break;
				case 'product':
					$product_default_footer = self::get_meta('grozomart_product_meta', 'product_default_footer', 'default');
					$default_footer         = ('default' !== $product_default_footer) ? $product_default_footer : $default_footer;
					break;
				case 'post':
					$post_default_footer = self::get_meta('grozomart_post_meta', 'post_default_footer', 'default');
					$default_footer      = ('default' !== $post_default_footer) ? $post_default_footer : $default_footer;
					break;
			}
		}

		return $default_footer;
	}

	/**
	 * Theme Global Colors
	 */
	public static function get_global_colors()
	{
		$colors = [];

		// Light
		$primary_color   = self::get_option('primary_color', '');
		$secondary_color = self::get_option('secondary_color', '');
		$blue_color = self::get_option('blue_color', '');
		$body_color      = self::get_option('body_color', '');
		$heading_color   = self::get_option('heading_color', '');
		$gray_color      = self::get_option('gray_color', '');
		$light_neutral   = self::get_option('light_neutral', '');

		$colors['_primary'] = [
			'slug'  => 'tekprof-primary-color',
			'title' => esc_html__('Primary', 'grozomart'),
			'value' => ! empty($primary_color) ? $primary_color : '#FC5546',
		];

		$colors['_secondary'] = [
			'slug'  => 'tekprof-secondary-color',
			'title' => esc_html__('Secondary', 'grozomart'),
			'value' => ! empty($secondary_color) ? $secondary_color : '#021433',
		];

		$colors['_blue'] = [
			'slug'  => 'tekprof-blue-color',
			'title' => esc_html__('Blue', 'grozomart'),
			'value' => ! empty($blue_color) ? $secondary_color : '#021433',
		];

		$colors['_body'] = [
			'slug'  => 'tekprof-body-color',
			'title' => esc_html__('Body', 'grozomart'),
			'value' => ! empty($body_color) ? $body_color : '#5B5B5B',
		];

		$colors['_heading'] = [
			'slug'  => 'tekprof-heading-color',
			'title' => esc_html__('Headline', 'grozomart'),
			'value' => ! empty($heading_color) ? $heading_color : '#0B0C0C',
		];

		$colors['_gray'] = [
			'slug'  => 'tekprof-gray-color',
			'title' => esc_html__('Gray', 'grozomart'),
			'value' => ! empty($gray_color) ? $gray_color : '#F3F6F9',
		];

		$colors['_light'] = [
			'slug'  => 'tekprof-light-neutral',
			'title' => esc_html__('Light', 'grozomart'),
			'value' => ! empty($light_neutral) ? $light_neutral : '#F3F6F9',
		];

		return $colors;
	}

	/**
	 * Theme Page Colors
	 */
	public static function get_page_colors()
	{
		$colors = [];

		$is_enabled_page_color = self::get_meta('grozomart_page_meta', 'page_custom_color_scheme', 'disabled');

		if ('enabled' !== $is_enabled_page_color) {
			return $colors;
		}

		$primary_color   = self::get_meta('grozomart_page_meta', 'primary_color', '');
		$secondary_color = self::get_meta('grozomart_page_meta', 'secondary_color', '');
		$blue_color = self::get_meta('grozomart_page_meta', 'blue_color', '');
		$body_color      = self::get_meta('grozomart_page_meta', 'body_color', '');
		$heading_color   = self::get_meta('grozomart_page_meta', 'heading_color', '');
		$gray_color      = self::get_meta('grozomart_page_meta', 'gray_color', '');
		$light_neutral   = self::get_meta('grozomart_page_meta', 'light_neutral', '');

		$colors['_primary'] = [
			'slug'  => 'tekprof-primary-color',
			'title' => esc_html__('Primary', 'grozomart'),
			'value' => ! empty($primary_color) ? $primary_color : '#FC5546',
		];

		$colors['_secondary'] = [
			'slug'  => 'tekprof-secondary-color',
			'title' => esc_html__('Secondary', 'grozomart'),
			'value' => ! empty($secondary_color) ? $secondary_color : '#021433',
		];

		$colors['_blue'] = [
			'slug'  => 'tekprof-blue-color',
			'title' => esc_html__('blue', 'grozomart'),
			'value' => ! empty($blue_color) ? $blue_color : '#021433',
		];

		$colors['_body'] = [
			'slug'  => 'tekprof-body-color',
			'title' => esc_html__('Body', 'grozomart'),
			'value' => ! empty($body_color) ? $body_color : '#5B5B5B',
		];

		$colors['_heading'] = [
			'slug'  => 'tekprof-heading-color',
			'title' => esc_html__('Headline', 'grozomart'),
			'value' => ! empty($heading_color) ? $heading_color : '#0B0C0C',
		];

		$colors['_gray'] = [
			'slug'  => 'tekprof-gray-color',
			'title' => esc_html__('Gray', 'grozomart'),
			'value' => ! empty($gray_color) ? $gray_color : '#F3F6F9',
		];

		$colors['_light'] = [
			'slug'  => 'tekprof-light-neutral',
			'title' => esc_html__('Light', 'grozomart'),
			'value' => ! empty($light_neutral) ? $light_neutral : '#F3F6F9',
		];

		return $colors;
	}

	/**
	 * Theme Global Font
	 */
	public static function get_global_fonts()
	{
		$fonts = [
			'_primary'   => [
				'slug'               => 'tekprof-base-font',
				'font-family'        => 'Inter',
				'backup-font-family' => 'sans-serif'
			],
			'_secondary' => [
				'slug'               => 'tekprof-heading-font',
				'font-family'        => 'Hanken Grotesk',
				'backup-font-family' => 'sans-serif'
			],
		];

		$primary_font   = self::get_option('primary_font', []);
		$secondary_font = self::get_option('secondary_font', []);

		// Update Primary Font
		if (! empty($primary_font['font-family'])) {
			$fonts['_primary']['font-family'] = $primary_font['font-family'];

			if (! empty($primary_font['backup-font-family'])) {
				$fonts['_primary']['backup-font-family'] = $primary_font['backup-font-family'];
			}
		}

		// Update Secondary Font
		if (! empty($secondary_font['font-family'])) {
			$fonts['_secondary']['font-family'] = $secondary_font['font-family'];

			if (! empty($secondary_font['backup-font-family'])) {
				$fonts['_secondary']['backup-font-family'] = $secondary_font['backup-font-family'];
			}
		}

		return $fonts;
	}

	/**
	 * Theme Global Font
	 */
	public static function get_page_fonts()
	{

		$fonts = [];

		$is_enabled_page_typo   = self::get_meta('grozomart_page_meta', 'custom_typo_type', 'default-font');

		if ('custom-font' !== $is_enabled_page_typo) {
			return $fonts;
		}

		$fonts = [
			'_primary'   => [
				'slug'               => 'tekprof-base-font',
				'font-family'        => 'Inter',
				'backup-font-family' => 'sans-serif'
			],
			'_secondary' => [
				'slug'               => 'tekprof-heading-font',
				'font-family'        => 'Hanken Grotesk',
				'backup-font-family' => 'sans-serif'
			],
		];

		$primary_font   = self::get_meta('grozomart_page_meta', 'primary_font', []);
		$secondary_font = self::get_meta('grozomart_page_meta', 'secondary_font', []);

		// Update Primary Font
		if (! empty($primary_font['font-family'])) {
			$fonts['_primary']['font-family'] = $primary_font['font-family'];

			if (! empty($primary_font['backup-font-family'])) {
				$fonts['_primary']['backup-font-family'] = $primary_font['backup-font-family'];
			}
		}

		// Update Secondary Font
		if (! empty($secondary_font['font-family'])) {
			$fonts['_secondary']['font-family'] = $secondary_font['font-family'];

			if (! empty($secondary_font['backup-font-family'])) {
				$fonts['_secondary']['backup-font-family'] = $secondary_font['backup-font-family'];
			}
		}

		return $fonts;
	}

	public static function get_container_max_width()
	{
		$maximum_width = [
			'desktop'      => 1320,
			'laptop'       => 1320,
			'tablet_extra' => 990,
			'tablet'       => 750,
			'mobile'       => 570,
		];

		$container_width = self::get_option('container_max_width', []);
		$keys_to_check   = ['desktop', 'laptop', 'tablet_extra', 'tablet', 'mobile'];

		foreach ($keys_to_check as $key) {
			if (! empty($container_width[$key]['width'])) {
				$maximum_width[$key] = (int) $container_width[$key]['width'];
			}
		}

		return $maximum_width;
	}

	public static function site_back_to_top()
	{
		$back_to_top = self::get_option('back_to_top', 'enabled');

		if (is_page()) {
			$back_to_top_page = self::get_meta('grozomart_page_meta', 'back_to_top_page', 'default');

			if ('default' !== $back_to_top_page) {
				$back_to_top = $back_to_top_page;
			}
		}

		return $back_to_top;
	}

	public static function get_elementor_content($content_id)
	{
		$content = '';
		if (\class_exists('\Elementor\Plugin')) {
			$elementor_instance = \Elementor\Plugin::instance();
			$content            = $elementor_instance->frontend->get_builder_content_for_display($content_id, true);
		}

		return $content;
	}
}
