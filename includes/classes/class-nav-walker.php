<?php

namespace GrozomartTheme\Classes;

use Walker_Nav_Menu;

defined('ABSPATH') || exit;

class Grozomart_Nav_Walker extends Walker_Nav_Menu
{

	/**
	 * Starts the element output.
	 */
	public function start_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0)
	{
		$menu_item = $data_object;
		$item_meta = get_post_meta($menu_item->ID, 'grozomart_nav_menu_meta', true);

		$t      = isset($args->item_spacing) && 'discard' === $args->item_spacing ? '' : "\t";
		$indent = ($depth) ? str_repeat($t, $depth) : '';

		$classes   = empty($menu_item->classes) ? [] : (array) $menu_item->classes;
		$classes[] = 'menu-item-' . $menu_item->ID;

		// Check if the item has children and add the 'dropdown' class
		if (in_array('menu-item-has-children', $classes)) {
			$classes[] = 'dropdown';
		}

		$classes[] = $this->get_additional_classes($menu_item, $item_meta);

		/** This filter is documented in wp-includes/class-walker-nav-menu.php */
		$args = apply_filters('nav_menu_item_args', $args, $menu_item, $depth);

		/** This filter is documented in wp-includes/class-walker-nav-menu.php */
		$class_names = implode(' ', apply_filters('nav_menu_css_class', array_filter($classes), $menu_item, $args, $depth));

		/** This filter is documented in wp-includes/class-walker-nav-menu.php */
		$id = apply_filters('nav_menu_item_id', 'menu-item-' . $menu_item->ID, $menu_item, $args, $depth);

		$li_atts = [
			'id'    => ! empty($id) ? $id : '',
			'class' => ! empty($class_names) ? $class_names : ''
		];

		/** This filter is documented in wp-includes/class-walker-nav-menu.php */
		$li_atts       = apply_filters('nav_menu_item_attributes', $li_atts, $menu_item, $args, $depth);
		$li_attributes = $this->build_atts($li_atts);

		$output .= $indent . '<li' . $li_attributes . '>';

		$atts       = $this->build_link_attributes($menu_item, $args, $depth);
		$attributes = $this->build_atts($atts);

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters('the_title', $menu_item->title, $menu_item->ID);

		/** This filter is documented in wp-includes/class-walker-nav-menu.php */
		$title = apply_filters('nav_menu_item_title', $title, $menu_item, $args, $depth);

		$item_output = $args->before;
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $this->get_nav_icon_html($item_meta);
		$item_output .= $args->link_before . $title . $args->link_after;
		$item_output .= $this->get_nav_badge_html($item_meta);
		$item_output .= $this->get_submenu_indicator_html($menu_item, $classes);
		$item_output .= '</a>';
		$item_output .= $this->get_builder_mega_menu_content($menu_item);
		$item_output .= $args->after;

		/** This filter is documented in wp-includes/class-walker-nav-menu.php */
		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $menu_item, $depth, $args);
	}

	protected function get_additional_classes($menu_item, $item_meta)
	{
		$additional_classes     = '';
		$simple_mega_menu       = $item_meta['simple_mega_menu'] ?? false;
		$simple_mega_menu_width = $item_meta['simple_mega_menu_width'] ?? 'auto';

		$mega_menu_settings = get_post_meta($menu_item->object_id, 'grozomart_tb_settings', true);
		$menu_width         = $mega_menu_settings['mega_menu_width'] ?? 'full';

		if ($menu_item->object === 'grozomart_template') {
			$additional_classes .= 'menu-item-has-mega-menu mega-width-' . $menu_width;
		} elseif ($simple_mega_menu) {
			$additional_classes .= 'simple-mega-menu mega-width-' . $simple_mega_menu_width;
		}

		return $additional_classes;
	}

	protected function build_link_attributes($menu_item, $args, $depth)
	{
		$atts = [
			'title'  => ! empty($menu_item->attr_title) ? $menu_item->attr_title : '',
			'target' => ! empty($menu_item->target) ? $menu_item->target : '',
			'rel'    => ! empty($menu_item->xfn) ? $menu_item->xfn : '',
			'href'   => ! empty($menu_item->url) ? $menu_item->url : '',
			'class'  => 'menu-item-link'
		];

		// Set Builder mega-menu URL
		if ($menu_item->object === 'grozomart_template') {
			$mega_menu_url  = get_post_meta($menu_item->ID, '_grozomart_mega_menu_url', true);
			$atts['href'] = $mega_menu_url ?: '#';
		}

		/** This filter is documented in wp-includes/class-walker-nav-menu.php */
		return apply_filters('nav_menu_link_attributes', $atts, $menu_item, $args, $depth);
	}

	protected function get_nav_icon_html($item_meta)
	{
		$nav_icon_type = $item_meta['nav_icon_type'] ?? 'none';
		$font_icon     = $item_meta['nav_font_icon'] ?? '';
		$image_icon    = $item_meta['nav_image_icon'] ?? '';
		$icon_position = $item_meta['nav_icon_position'] ?? 'left';
		$icon_class    = 'nav-icon position-' . $icon_position;
		$icon_color    = $item_meta['nav_icon_color'] ?? '';
		$icon_html     = '';

		if ('font_icon' === $nav_icon_type && ! empty($font_icon)) {
			$icon_html .= '<span class="' . esc_attr($icon_class) . '"';
			if ($icon_color) {
				$icon_html .= 'style="color: ' . esc_attr($icon_color) . '"';
			}
			$icon_html .= '>';
			$icon_html .= '<i class="' . esc_attr($font_icon) . '"></i></span>';
		} elseif ('image_icon' === $nav_icon_type && ! empty($image_icon['id'])) {
			return '<span class="' . esc_attr($icon_class) . '">' . wp_get_attachment_image($image_icon['id'], 'thumbnail') . '</span>';
		}

		return $icon_html;
	}

	protected function get_nav_badge_html($item_meta)
	{
		$badge       = $item_meta['nav_menu_badge'] ?? '';
		$badge_color = $item_meta['nav_badge_color'] ?? '';
		$badge_html  = '';

		if ($badge) {
			$badge_html .= '<span class="nav-badge"';
			if ($badge_color) {
				$badge_html .= 'style="--badge-color: ' . esc_attr($badge_color) . '"';
			}
			$badge_html .= '>';
			$badge_html .= esc_html($badge);
			$badge_html .= '</span>';
		}

		return $badge_html;
	}

	protected function get_submenu_indicator_html($menu_item, $classes)
	{
		if (in_array('menu-item-has-children', $classes) || $menu_item->object === 'grozomart_template') {
			return '<span class="submenu-toggler"></span>';
		}

		return '';
	}

	protected function get_builder_mega_menu_content($menu_item)
	{
		if ($menu_item->object !== 'grozomart_template') {
			return '';
		}

		$settings     = get_post_meta($menu_item->object_id, 'grozomart_tb_settings', true);
		$menu_width   = $settings['mega_menu_width'] ?? 'full';
		$custom_width = $settings['set_mega_menu_width'] ?? ['width' => ''];

		$content = '<div class="sub-menu mega-menu-wrapper menu-width-' . esc_attr($menu_width) . '"';
		if ('custom' === $menu_width && ! empty($custom_width['width'])) {
			$content .= ' style="width: ' . esc_attr($custom_width['width']) . 'px"';
		}
		$content .= '>';
		$content .= Grozomart_Helper::get_elementor_content($menu_item->object_id);
		$content .= '</div>';

		return $content;
	}
}
