<?php

/**
 * Header search form.
 *
 * A dedicated, filterable template part rather than a form hardcoded
 * inline in header-default.php — matches the pattern get_search_form()
 * uses (locate_template('searchform.php')), but for this widget-style
 * form get_search_form() itself can't be used: it always resolves to the
 * theme's single searchform.php, which has no way to carry this form's
 * WooCommerce category dropdown and icon button. Exposing the markup here,
 * through get_template_part() and the grozomart_header_search_form filter
 * below, gives a child theme or plugin the same override points
 * get_search_form() would.
 *
 * @param array $args {
 *     @type bool  $with_category_select Whether to render the product category dropdown.
 *     @type array $product_categories   Category terms for the dropdown, when enabled.
 * }
 *
 * @package Grozomart
 */

$with_category_select = ! empty($args['with_category_select']);
$product_categories   = $args['product_categories'] ?? [];

ob_start();
?>
<form class="<?php echo esc_attr($with_category_select ? 'search-box' : ''); ?>" method="get" action="<?php echo esc_url(home_url('/')); ?>">
	<?php if ($with_category_select && ! empty($product_categories)) : ?>
		<div class="category-select">
			<select class="single-select price-list w-100" name="product_cat">
				<option value=""><?php esc_html_e('All type', 'grozomart'); ?></option>
				<?php foreach ($product_categories as $product_category) : ?>
					<option value="<?php echo esc_attr($product_category->slug); ?>"><?php echo esc_html($product_category->name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	<?php endif; ?>

	<input type="text" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="<?php esc_attr_e('Search for products....', 'grozomart'); ?>">
	<?php if (function_exists('WC')) : ?>
		<input type="hidden" name="post_type" value="product">
	<?php endif; ?>

	<button class="<?php echo esc_attr($with_category_select ? 'search-btn' : ''); ?>" type="submit">
		<i class="fa-regular fa-magnifying-glass"></i>
	</button>
</form>
<?php
$grozomart_search_form = ob_get_clean();

/**
 * Filters the header search form markup.
 *
 * @param string $grozomart_search_form HTML markup of the form.
 * @param bool   $with_category_select  Whether the category dropdown variant was requested.
 */
echo apply_filters('grozomart_header_search_form', $grozomart_search_form, $with_category_select);
