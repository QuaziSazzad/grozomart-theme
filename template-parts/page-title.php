<?php

/**
 * Template part for displaying page Title
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Grozomart
 */

use GrozomartTheme\Classes\Grozomart_Helper as Helper;


$active_title = Helper::get_option('site_page_title', 'enabled');
$breadcrumb   = Helper::get_option('site_breadcrumb', 'enabled');
$title        = '';
$custom_title = '';
$title_output = [];

if (is_page() && ! is_home()) {
	$page_title        = Helper::get_meta('grozomart_page_meta', 'page_title', 'default');
	$page_breadcrumb   = Helper::get_meta('grozomart_page_meta', 'page_breadcrumb', 'default');
	$page_title_type   = Helper::get_meta('grozomart_page_meta', 'page_title_type', 'default');
	$page_custom_title = Helper::get_meta('grozomart_page_meta', 'page_custom_title', '');

	if ('default' !== $page_title) {
		$active_title = $page_title;
	}

	if ('custom' === $page_title_type && ! empty($page_custom_title)) {
		$custom_title = $page_custom_title;
	}

	if ('default' !== $page_breadcrumb) {
		$breadcrumb = $page_breadcrumb;
	}
} elseif (is_single() && 'post' === get_post_type()) {
	$post_page_title   = Helper::get_meta('grozomart_post_meta', 'post_page_title', 'default');
	$post_breadcrumb   = Helper::get_meta('grozomart_post_meta', 'post_breadcrumb', 'default');
	$post_title_type   = Helper::get_meta('grozomart_post_meta', 'post_title_type', 'default');
	$post_custom_title = Helper::get_meta('grozomart_post_meta', 'post_custom_title', __('News Details', 'grozomart'));

	if ('default' !== $post_page_title) {
		$active_title = $post_page_title;
	}

	if ('custom' === $post_title_type && ! empty($post_custom_title)) {
		$custom_title = $post_custom_title;
	}

	if ('default' !== $post_breadcrumb) {
		$breadcrumb = $post_breadcrumb;
	}
} elseif (is_single() && 'grozomart_portfolio' === get_post_type()) {

	$portfolio_page_title   = Helper::get_meta('grozomart_portfolio_meta', 'portfolio_page_title', 'default');
	$portfolio_breadcrumb   = Helper::get_meta('grozomart_portfolio_meta', 'portfolio_breadcrumb', 'default');
	$portfolio_title_type   = Helper::get_meta('grozomart_portfolio_meta', 'portfolio_page_title_type', 'default');
	$portfolio_custom_title = Helper::get_meta('grozomart_portfolio_meta', 'portfolio_custom_title', __('Project Details', 'grozomart'));

	if ('default' !== $portfolio_page_title) {
		$active_title = $portfolio_page_title;
	}

	if ('custom' === $portfolio_title_type && ! empty($portfolio_custom_title)) {
		$custom_title = $portfolio_custom_title;
	}

	if ('default' !== $portfolio_breadcrumb) {
		$breadcrumb = $portfolio_breadcrumb;
	}
} elseif (is_single() && 'product' === get_post_type()) {
	$product_page_title   = Helper::get_meta('grozomart_product_meta', 'product_page_title', 'default');
	$product_breadcrumb   = Helper::get_meta('grozomart_product_meta', 'product_breadcrumb', 'default');
	$product_title_type   = Helper::get_meta('grozomart_product_meta', 'product_page_title_type', 'default');
	$product_custom_title = Helper::get_meta('grozomart_product_meta', 'product_custom_title', '');

	if ('default' !== $product_page_title) {
		$active_title = $product_page_title;
	}

	if ('custom' === $product_title_type && ! empty($product_custom_title)) {
		$custom_title = $product_custom_title;
	}

	if ('default' !== $product_breadcrumb) {
		$breadcrumb = $product_breadcrumb;
	}
}

if (is_home()) {
	$title = Helper::get_option('blog_archive_title', __('Latest News', 'grozomart'));
} elseif (is_search()) {
	$title = esc_html__('Search Results for: ', 'grozomart') . get_search_query();
} elseif (is_archive()) {
	if (class_exists('WooCommerce') && is_shop()) {
		$shop_id = get_option('woocommerce_shop_page_id', '');
		$title   = get_the_title($shop_id);
	} elseif (is_post_type_archive('grozomart_portfolio')) {
		$portfolio_title = Helper::get_option('archive_page_title', __('Our Portfolio', 'grozomart'));

		if (! empty($portfolio_title)) {
			$title = $portfolio_title;
		}
	} else {
		$title = strip_tags(get_the_archive_title());
	}
} elseif (! empty($custom_title)) {
	$title = esc_html($custom_title);
} else {
	$title = wp_kses_post(get_the_title());
}

if (is_404()) {
	$title = esc_html__('404', 'grozomart');
}

if ($title) {
	$title_output[] = $title;
}

if ('enabled' !== $active_title) {
	return;
}

$show_post_meta = Helper::get_option('blog_details_meta', 'yes');

if (is_404()) {
	return;
}

/**
 * The shared Grozomart_Breadcrumb class emits flat <a> + <span class="separator">
 * markup, but this design needs a plain <li> trail. Build the crumb list here so
 * both breadcrumb variants below share one source.
 */
$breadcrumb_items = [];

$breadcrumb_items[] = [
	'label' => esc_html__('Home', 'grozomart'),
	'url'   => home_url('/'),
];

/**
 * Only walk page ancestors on a real singular page. On archives the global
 * $post still points at the first queued post, so touching post data here
 * would read the wrong object — and is_page() is false there anyway.
 */
if (is_page() && ! is_home()) {
	$page_ancestors = array_reverse(get_post_ancestors(get_queried_object_id()));

	foreach ($page_ancestors as $ancestor_id) {
		$breadcrumb_items[] = [
			'label' => get_the_title($ancestor_id),
			'url'   => get_permalink($ancestor_id),
		];
	}
}

$breadcrumb_items[] = ['label' => implode('', $title_output)];

/**
 * Renders the crumb trail into the given <ul> class.
 */
$grozomart_render_breadcrumb = function ($list_class, $wow_delay = '') use ($breadcrumb_items) {
?>
	<ul class="<?php echo esc_attr($list_class); ?> wow fadeInUp" <?php if ($wow_delay) : ?>data-wow-delay="<?php echo esc_attr($wow_delay); ?>"<?php endif; ?>>
		<?php foreach ($breadcrumb_items as $index => $breadcrumb_item) : ?>
			<?php if ($index > 0) : ?>
				<li>/</li>
			<?php endif; ?>
			<li>
				<?php if (! empty($breadcrumb_item['url'])) : ?>
					<a href="<?php echo esc_url($breadcrumb_item['url']); ?>"><?php echo esc_html($breadcrumb_item['label']); ?></a>
				<?php else : ?>
					<?php echo esc_html($breadcrumb_item['label']); ?>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
<?php
};

/**
 * On single posts the heading block lives inside the news-details section
 * (its CSS is scoped to `.news-details-section-in .news-details-top-area`),
 * so single.php renders it there instead — nothing to output here.
 */
if (is_singular('post')) {
	return;
}
?>
<!-- Breadcrumb Section Start -->
<section class="breadcrumb-wrapper fix">
	<div class="container">
		<div class="page-heading">
			<h1 class="breadcrumb-title wow fadeInUp"><?php echo implode('', $title_output); ?></h1>
			<?php if ('enabled' === $breadcrumb) {
				$grozomart_render_breadcrumb('breadcrumb-list', '.2s');
			} ?>
		</div>
	</div>
</section>
<!-- Breadcrumb Section End -->
