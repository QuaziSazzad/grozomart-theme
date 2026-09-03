<?php

namespace GrozomartTheme\Classes;

defined('ABSPATH') || exit;

/**
 * Nav walker for the default header's sticky menu.
 *
 * Grozomart_Nav_Walker (shared with the Elementor header widgets) emits
 * core's default `.sub-menu` class and has no chevron indicator for items
 * with children — both are needed by this design's CSS. Rather than
 * add/remove a `nav_menu_submenu_css_class`/`nav_menu_item_title` filter
 * pair around one `wp_nav_menu()` call, this subclass overrides the two
 * relevant methods directly, so the behavior is scoped to the call site by
 * construction instead of by cleanup.
 */
class Grozomart_Nav_Walker_Submenu extends Grozomart_Nav_Walker
{
	/**
	 * Starts the list before the elements are added, using `submenu`
	 * instead of core's default `sub-menu` class.
	 */
	public function start_lvl(&$output, $depth = 0, $args = null)
	{
		if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat($t, $depth);

		$output .= "{$n}{$indent}<ul class=\"submenu\">{$n}";
	}

	/**
	 * Appends a chevron icon to items with children.
	 */
	public function start_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0)
	{
		$before = strlen($output);

		parent::start_el($output, $data_object, $depth, $args, $current_object_id);

		$classes = empty($data_object->classes) ? [] : (array) $data_object->classes;

		if (in_array('menu-item-has-children', $classes, true)) {
			$chevron = ' <i class="fa-solid fa-chevron-down"></i>';
			$added   = substr($output, $before);
			$output  = substr($output, 0, $before) . str_replace('</a>', $chevron . '</a>', $added);
		}
	}
}
