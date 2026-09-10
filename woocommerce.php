<?php

/**
 * WooCommerce wrapper template.
 *
 * WordPress hands every WooCommerce archive to this file when the theme
 * provides it. Only product SEARCH is handled here — with the same sidebar,
 * toolbar and grid the Shop widget's Layout Two uses, so a search from the
 * header lands on a results page that matches the shop rather than
 * WooCommerce's unstyled default archive.
 *
 * Everything else (shop page, category and tag archives) is passed straight
 * through to woocommerce_content() untouched.
 *
 * @package Grozomart
 */

defined('ABSPATH') || exit;

get_header();

$grozomart_is_product_search = is_search() && 'product' === get_query_var('post_type');
$grozomart_can_use_layout    = $grozomart_is_product_search
    && class_exists('\GrozomartToolkit\Helper\Grozomart_Shop_Filter')
    && function_exists('grozomart_get_elementor_template');

if (! $grozomart_can_use_layout) :
    /**
     * Not a product search, or the toolkit that owns the shop layout is
     * inactive — fall back to WooCommerce's own output so the page still
     * works with the plugin disabled.
     */
?>
    <div class="py-120">
        <div class="container">
            <?php woocommerce_content(); ?>
        </div>
    </div>
<?php
else :

    $filter = '\GrozomartToolkit\Helper\Grozomart_Shop_Filter';

    $shop_two_search = get_search_query();

    /**
     * The results partial is shared with the Shop widget, so it reads widget
     * settings. Supply the same keys with sensible defaults for this page.
     */
    $settings = [
        'layout_two_limit'             => 12,
        'layout_two_default_orderby'   => 'menu_order',
        'layout_two_default_view'      => 'grid',
        'layout_two_show_result_count' => 'yes',
        'layout_two_show_view_tabs'    => 'yes',
        'layout_two_show_ordering'     => 'yes',
        'layout_two_show_pagination'   => 'yes',
    ];

    // ── Sidebar filter data ─────────────────────────────────────────────
    $shop_two_categories = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
    ]);
    if (is_wp_error($shop_two_categories)) {
        $shop_two_categories = [];
    }

    $shop_two_bounds      = $filter::price_bounds();
    $shop_two_price_floor = $shop_two_bounds['floor'];
    $shop_two_price_ceil  = $shop_two_bounds['ceil'];

    /**
     * The header search can carry a category (its dropdown posts product_cat
     * as a single slug), so accept both that and the sidebar's checkbox array.
     */
    $shop_two_selected_cats = [];
    if (isset($_GET['product_cat'])) {
        $shop_two_selected_cats = array_filter(array_map('sanitize_title', (array) wp_unslash($_GET['product_cat'])));
    }

    $shop_two_selected_stock = isset($_GET['stock_status']) ? array_filter(array_map('sanitize_key', (array) wp_unslash($_GET['stock_status']))) : [];
    $shop_two_on_sale_only   = isset($_GET['on_sale']) && '1' === $_GET['on_sale'];
    $shop_two_selected_min   = isset($_GET['min_price']) ? max($shop_two_price_floor, (int) $_GET['min_price']) : $shop_two_price_floor;
    $shop_two_selected_max   = isset($_GET['max_price']) ? min($shop_two_price_ceil, (int) $_GET['max_price']) : $shop_two_price_ceil;

    $shop_two_stock_statuses = [
        'instock'    => esc_html__('In Stock', 'grozomart'),
        'outofstock' => esc_html__('Out of Stock', 'grozomart'),
    ];

    // ── Query ───────────────────────────────────────────────────────────
    $shop_two_paged = max(1, (int) (get_query_var('paged') ? get_query_var('paged') : (isset($_GET['product_page']) ? absint($_GET['product_page']) : 1)));

    $shop_two_orderby_choice = isset($_GET['orderby']) ? sanitize_text_field(wp_unslash($_GET['orderby'])) : $settings['layout_two_default_orderby'];

    $shop_two_query = $filter::query([
        'per_page'          => (int) $settings['layout_two_limit'],
        'paged'             => $shop_two_paged,
        'orderby_choice'    => $shop_two_orderby_choice,
        'search'            => $shop_two_search,
        'selected_cats'     => $shop_two_selected_cats,
        'widget_categories' => [],
        'selected_stock'    => $shop_two_selected_stock,
        'on_sale_only'      => $shop_two_on_sale_only,
        'selected_min'      => $shop_two_selected_min,
        'selected_max'      => $shop_two_selected_max,
        'bounds'            => $shop_two_bounds,
    ]);

    $shop_two_products = $filter::collect_products($shop_two_query);

    $shop_two_total    = (int) $shop_two_query->found_posts;
    $shop_two_per_page = (int) $settings['layout_two_limit'];
    $shop_two_first    = $shop_two_total ? (($shop_two_paged - 1) * $shop_two_per_page) + 1 : 0;
    $shop_two_last     = min($shop_two_total, $shop_two_paged * $shop_two_per_page);

    $shop_two_views         = $filter::views();
    $shop_two_sort_labels   = $filter::sort_labels();
    $shop_two_active_view   = $filter::active_view($_GET, $settings);
    $shop_two_uid           = 'search';

    $shop_two_renderers        = $filter::renderers();
    $shop_two_render_card      = $shop_two_renderers['card'];
    $shop_two_render_list_item = $shop_two_renderers['list_item'];

    /**
     * Keeps the active filters on the sort form and pagination links. The
     * search term has to ride along too, or sorting a result set would drop
     * the keyword and show the whole catalogue.
     */
    $shop_two_persist_query_args = function () {
        foreach ($_GET as $key => $value) {
            if (in_array($key, ['orderby', 'product_page', 'paged'], true)) {
                continue;
            }
            $value   = wp_unslash($value);
            $is_list = is_array($value);
            foreach ((array) $value as $single) {
                echo '<input type="hidden" name="' . esc_attr($key) . ($is_list ? '[]' : '') . '" value="' . esc_attr($single) . '">';
            }
        }
    };
?>
    <!-- Product Search Results Start -->
    <section class="shop-list-section section-padding fix grozomart-shop-filter"
        data-element-id="<?php echo esc_attr($shop_two_uid); ?>"
        data-post-id="0"
        data-nonce="<?php echo esc_attr(wp_create_nonce($filter::NONCE)); ?>"
        data-settings="<?php echo esc_attr(wp_json_encode($settings)); ?>">
        <div class="container">
            <div class="row g-4">
                <div class="col-xl-3 col-lg-3 order-2 order-lg-1">
                    <div class="shop-sidebar-area">
                        <form method="get" class="shop-filter-form" id="shop-filter-form-<?php echo esc_attr($shop_two_uid); ?>">
                            <?php
                            /**
                             * Carry the keyword through every sidebar filter
                             * submit, otherwise filtering would silently turn
                             * the search into a full catalogue listing.
                             */
                            ?>
                            <input type="hidden" name="s" value="<?php echo esc_attr($shop_two_search); ?>">
                            <input type="hidden" name="post_type" value="product">

                            <div class="shop-sidebar-item active">
                                <div class="sidebar-header">
                                    <div class="head-title"><?php esc_html_e('Product Categories', 'grozomart'); ?></div>
                                    <i class="fas fa-chevron-down toggle-icon"></i>
                                </div>
                                <div class="sidebar-content">
                                    <div class="sidebar-inner">
                                        <div class="checkbox-group">
                                            <?php foreach ($shop_two_categories as $shop_two_category) : ?>
                                                <label class="custom-checkbox">
                                                    <input type="checkbox" name="product_cat[]" value="<?php echo esc_attr($shop_two_category->slug); ?>" <?php checked(in_array($shop_two_category->slug, $shop_two_selected_cats, true)); ?>>
                                                    <span class="checkmark"></span>
                                                    <?php echo esc_html($shop_two_category->name); ?>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="shop-sidebar-item active">
                                <div class="sidebar-header">
                                    <div class="head-title"><?php esc_html_e('Widget price filter', 'grozomart'); ?></div>
                                    <i class="fas fa-chevron-down toggle-icon"></i>
                                </div>
                                <div class="sidebar-content">
                                    <div class="sidebar-inner">
                                        <div class="price-filter-widget">
                                            <div class="slider-wrapper">
                                                <div class="slider-track"></div>
                                                <input type="range" min="<?php echo esc_attr($shop_two_price_floor); ?>" max="<?php echo esc_attr($shop_two_price_ceil); ?>" value="<?php echo esc_attr($shop_two_selected_min); ?>" name="min_price" class="range-min">
                                                <input type="range" min="<?php echo esc_attr($shop_two_price_floor); ?>" max="<?php echo esc_attr($shop_two_price_ceil); ?>" value="<?php echo esc_attr($shop_two_selected_max); ?>" name="max_price" class="range-max">
                                            </div>
                                            <div class="filter-bottom">
                                                <div class="price-value">
                                                    <?php esc_html_e('Price:', 'grozomart'); ?> <span>$<?php echo esc_html($shop_two_selected_min); ?> – $<?php echo esc_html($shop_two_selected_max); ?></span>
                                                </div>
                                                <button type="submit" class="filter-btn"><?php esc_html_e('Filter', 'grozomart'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="shop-sidebar-item active">
                                <div class="sidebar-header">
                                    <div class="head-title"><?php esc_html_e('Product Status', 'grozomart'); ?></div>
                                    <i class="fas fa-chevron-down toggle-icon"></i>
                                </div>
                                <div class="sidebar-content">
                                    <div class="sidebar-inner">
                                        <div class="checkbox-group">
                                            <?php foreach ($shop_two_stock_statuses as $shop_two_status_key => $shop_two_status_label) : ?>
                                                <label class="custom-checkbox">
                                                    <input type="checkbox" name="stock_status[]" value="<?php echo esc_attr($shop_two_status_key); ?>" <?php checked(in_array($shop_two_status_key, $shop_two_selected_stock, true)); ?>>
                                                    <span class="checkmark"></span>
                                                    <?php echo esc_html($shop_two_status_label); ?>
                                                </label>
                                            <?php endforeach; ?>
                                            <label class="custom-checkbox">
                                                <input type="checkbox" name="on_sale" value="1" <?php checked($shop_two_on_sale_only); ?>>
                                                <span class="checkmark"></span>
                                                <?php esc_html_e('On Sale', 'grozomart'); ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-9 order-1 order-lg-2">
                    <div class="shop-filter-results">
                        <?php include grozomart_get_elementor_template('shop-two-results.php'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Search Results End -->
<?php
endif;

get_footer();
