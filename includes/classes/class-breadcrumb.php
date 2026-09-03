<?php

namespace GrozomartTheme\Classes;

defined('ABSPATH') || exit;

/**
 * Initial Breadcrumb
 */
class Grozomart_Breadcrumb
{

	protected static $instance = null;

	public static function instance()
	{
		if (null === self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public static function breadcrumb_init()
	{
		global $post;
		global $wp_query;
		global $author;

		$html_markup = '';

		$home_title = Grozomart_Helper::get_option('breadcrumb_home_title', esc_html__('Home', 'grozomart'));
		$home       = home_url();

		$separator_markup = '<span class="separator"> <i class="fa fa-angle-right" aria-hidden="true"></i> </span>';
		$currentBefore    = '<span class="current">';
		$currentAfter     = '</span>';

		$post_page_title = get_option('page_for_posts');

		// Fixed condition to always show breadcrumb
		$html_markup .= '<div class="tekprof-breadcrumb wt-breadcrumb-body wt-breadcrumb-body details-body">';

		// Check if we're on home/front page
		if (is_home() && !is_paged() || is_front_page() && !is_paged()) {
			// On home/front page, just show the current page marker
			$html_markup .= $currentBefore . esc_html($home_title) . $currentAfter;
		} else {
			// For all other pages, show the home link followed by proper breadcrumb
			$html_markup .= '<a href="' . esc_url($home) . '">' . esc_html($home_title) . '</a> ' . $separator_markup . ' ';

			if (is_archive()) {
				if (is_category()) {
					$cat_obj   = $wp_query->get_queried_object();
					$thisCat   = $cat_obj->term_id;
					$thisCat   = get_category($thisCat);
					$parentCat = get_category($thisCat->parent);

					if ($thisCat->parent != 0) {
						$html_markup .= (get_category_parents($parentCat, true, ' ' . $separator_markup . ' '));
					}

					$html_markup .= $currentBefore . esc_html__('Archive by category &#39;', 'grozomart');
					$html_markup .= single_cat_title('', false);
					$html_markup .= esc_html__('&#39;', 'grozomart') . $currentAfter;
				} elseif (is_tag()) {
					$html_markup .= $currentBefore . esc_html__('Posts tagged &#39;', 'grozomart');
					$html_markup .= single_tag_title('', false);
					$html_markup .= esc_html__('&#39;', 'grozomart') . $currentAfter;
				} elseif (is_tax()) {
					$html_markup .= $currentBefore . esc_html__('Archive by &#39;', 'grozomart');
					$html_markup .= single_cat_title('', false);
					$html_markup .= esc_html__('&#39;', 'grozomart') . $currentAfter;
				} elseif (is_day()) {
					$html_markup .= '<a href="' . esc_url(get_year_link(get_the_time('Y'))) . '">' . esc_html(get_the_time('Y')) . '</a> ' . $separator_markup . ' ';
					$html_markup .= '<a href="' . esc_url(get_month_link(get_the_time('Y'), get_the_time('m'))) . '">' . esc_html(get_the_time('F')) . '</a> ' . $separator_markup . ' ';
					$html_markup .= $currentBefore . esc_html(get_the_time('d')) . $currentAfter;
				} elseif (is_month()) {
					$html_markup .= '<a href="' . esc_url(get_year_link(get_the_time('Y'))) . '">' . esc_html(get_the_time('Y')) . '</a> ' . $separator_markup . ' ';
					$html_markup .= $currentBefore . esc_html(get_the_time('F')) . $currentAfter;
				} elseif (is_year()) {
					$html_markup .= $currentBefore . esc_html(get_the_time('Y')) . $currentAfter;
				} elseif (is_author()) {
					$userdata = get_userdata($author);

					$html_markup .= $currentBefore . esc_html__('Articles by ', 'grozomart') . $userdata->display_name . $currentAfter;
				} elseif (is_post_type_archive()) {
					$post_type     = get_query_var('post_type');
					$post_type_obj = get_post_type_object($post_type);

					if (is_array($post_type)) {
						$post_type = reset($post_type);
					}

					$html_markup .= $currentBefore;
					$html_markup .= $post_type_obj->label;
					$html_markup .= $currentAfter;
				}
			} elseif (is_singular() && ! is_page()) {
				$post_type        = get_post_type();
				$post_type_object = get_post_type_object($post_type);

				if ('grozomart_portfolio' == get_post_type()) {
					$parent_page = Grozomart_Helper::get_option('portfolio_parent_page', 'archive_page');
					$custom_page = Grozomart_Helper::get_option('portfolio_custom_page', '');

					if ('custom_page' == $parent_page && $custom_page) {
						$custom_parent_page = get_post($custom_page);
						$html_markup        .= '<a href="' . esc_url(get_permalink($custom_parent_page->ID)) . '">' . esc_html(get_the_title($custom_parent_page->ID)) . '</a>';
						$html_markup        .= $separator_markup;
					} elseif ('none' !== $parent_page) {
						$html_markup .= '<a href="' . get_post_type_archive_link($post_type) . '">' . $post_type_object->label . '</a>';
						$html_markup .= $separator_markup;
					}
				} else {
					$html_markup .= '<a href="' . get_post_type_archive_link($post_type) . '">' . $post_type_object->label . '</a>';
					$html_markup .= $separator_markup;
				}

				$html_markup .= $currentBefore;
				$html_markup .= get_the_title();
				$html_markup .= $currentAfter;
			} elseif (is_page() && ! $post->post_parent) {
				$html_markup .= $currentBefore;
				$html_markup .= get_the_title();
				$html_markup .= $currentAfter;
			} elseif (is_page() && $post->post_parent) {
				$parent_id        = $post->post_parent;
				$page_breadcrumbs = [];

				while ($parent_id) {
					$page               = get_post($parent_id);
					$page_breadcrumbs[] = '<a href="' . esc_url(get_permalink($page->ID)) . '">' . esc_html(get_the_title($page->ID)) . '</a>';
					$parent_id          = $page->post_parent;
				}

				$page_breadcrumbs = array_reverse($page_breadcrumbs);

				foreach ($page_breadcrumbs as $crumb) {
					$html_markup .= $crumb . ' ' . $separator_markup . ' ';
				}

				$html_markup .= $currentBefore;
				$html_markup .= get_the_title();
				$html_markup .= $currentAfter;
			} elseif (is_search()) {
				$html_markup .= $currentBefore . esc_html__('Search results for &#39;', 'grozomart') . get_search_query() . esc_html__('&#39;', 'grozomart') . $currentAfter;
			} elseif (is_404()) {
				$html_markup .= $currentBefore . esc_html__('Error 404', 'grozomart') . $currentAfter;
			} elseif (is_home() && $post_page_title) {
				$html_markup .= $currentBefore;
				$html_markup .= get_the_title(get_option('page_for_posts'));
				$html_markup .= $currentAfter;
			}

			if (get_query_var('paged')) {
				if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
					$html_markup .= '(';
				}
				$html_markup .= esc_html__('Page', 'grozomart') . ' ' . get_query_var('paged');
				if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
					$html_markup .= ')';
				}
			}
		}

		$html_markup .= '</div>';

		echo wp_kses_post($html_markup);
	}
}
