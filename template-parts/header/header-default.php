<?php

/**
 * Template part for displaying Main Header
 *
 * Fallback header used when no Elementor header template is assigned.
 * Mirrors the Header widget's Layout One markup so the site keeps the
 * same look without the template builder.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Grozomart
 */

use GrozomartTheme\Classes\Grozomart_Helper as Helper;
use GrozomartTheme\Classes\Grozomart_Nav_Walker;

$site_logo_type  = Helper::get_option('site_logo_type', 'image');
$site_text_logo  = Helper::get_option('site_text_logo', get_bloginfo('name'));
$site_image_logo = Helper::get_option('site_image_logo', ['url' => GROZOMART_ASSETS . '/img/logo.png']);

$cart_count = (function_exists('WC') && WC()->cart) ? WC()->cart->get_cart_contents_count() : 0;

/**
 * Storzen owns the wishlist/compare counters and live-updates any element
 * carrying its data attributes — reuse those rather than deriving counts.
 */
$wishlist_count = shortcode_exists('storzen_wishlist_count') ? (int) wp_strip_all_tags(do_shortcode('[storzen_wishlist_count]')) : 0;
$wishlist_url   = function_exists('storzen_module_setting') && storzen_module_setting('wishlist', 'wishlist_page')
    ? get_permalink((int) storzen_module_setting('wishlist', 'wishlist_page'))
    : '#';

$compare_count = 0;
if (! empty($_COOKIE['storzen_compare_list'])) {
    $compare_count = count(array_filter(explode(',', wp_unslash($_COOKIE['storzen_compare_list']))));
}
$compare_url = function_exists('storzen_module_setting') && storzen_module_setting('compare', 'compare_page')
    ? get_permalink((int) storzen_module_setting('compare', 'compare_page'))
    : '#';

$product_categories = [];
if (function_exists('WC')) {
    $terms = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'number'     => 4,
    ]);

    if (! empty($terms) && ! is_wp_error($terms)) {
        $product_categories = $terms;
    }
}

/**
 * Renders the shop icon list. Used in both the offcanvas panel and the
 * header itself, so keep it in one place.
 */
$grozomart_shop_icons = function () use ($wishlist_url, $wishlist_count, $compare_url, $compare_count, $cart_count) {
?>
    <div class="shop-icon-list">
        <a href="<?php echo esc_url($wishlist_url); ?>" class="cart-icon">
            <i class="fa-regular fa-heart"></i>
            <span class="cart-number" data-sz-wishlist-count><?php echo esc_html($wishlist_count); ?></span>
        </a>
        <a href="<?php echo esc_url($compare_url); ?>" class="cart-icon">
            <i class="fa-solid fa-arrows-rotate"></i>
            <span class="cart-number" data-sz-compare-count><?php echo esc_html($compare_count); ?></span>
        </a>
        <a href="<?php echo esc_url(function_exists('wc_get_cart_url') ? wc_get_cart_url() : '#'); ?>" class="cart-icon">
            <i class="fa-sharp fa-regular fa-cart-shopping"></i>
            <span class="cart-number"><?php echo esc_html($cart_count); ?></span>
        </a>
    </div>
<?php
};

/**
 * Renders the site logo (image or text, per theme options).
 */
$grozomart_site_logo = function () use ($site_logo_type, $site_text_logo, $site_image_logo) {
    if ('text' === $site_logo_type && ! empty($site_text_logo)) {
        echo esc_html($site_text_logo);
    } elseif (! empty($site_image_logo['url'])) {
        printf(
            '<img src="%1$s" alt="%2$s">',
            esc_url($site_image_logo['url']),
            esc_attr(get_bloginfo('name'))
        );
    } else {
        echo esc_html(get_bloginfo('name'));
    }
};
?>
<!-- Offcanvas Area Start -->
<div class="fix-area">
    <div class="offcanvas__info">
        <div class="offcanvas__wrapper">
            <div class="offcanvas__content">
                <div class="offcanvas__top d-flex justify-content-between align-items-center">
                    <div class="offcanvas__logo">
                        <a href="<?php echo esc_url(home_url('/')); ?>"><?php $grozomart_site_logo(); ?></a>
                    </div>
                    <div class="offcanvas__close">
                        <button>
                            <i class="fa-thin fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="mobile-menu fix"></div>
                <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                    <input type="text" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="<?php esc_attr_e('Search for products....', 'grozomart'); ?>">
                    <?php if (function_exists('WC')) : ?>
                        <input type="hidden" name="post_type" value="product">
                    <?php endif; ?>
                    <button type="submit"><i class="fa-regular fa-magnifying-glass"></i></button>
                </form>
                <?php if (function_exists('WC')) {
                    $grozomart_shop_icons();
                } ?>
            </div>
        </div>
    </div>
</div>
<div class="offcanvas__overlay"></div>

<!-- Header Section Start -->
<header class="header-section">
    <div class="container">
        <div class="middle-wrap-items">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="logo"><?php $grozomart_site_logo(); ?></a>
            <form class="search-box" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                <?php if (! empty($product_categories)) : ?>
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

                <button class="search-btn" type="submit">
                    <i class="fa-regular fa-magnifying-glass"></i>
                </button>
            </form>
            <div class="icon-right-wrap">
                <?php if (function_exists('WC')) {
                    $grozomart_shop_icons();
                } ?>
                <div class="header__hamburger my-auto d-xl-block">
                    <div class="sidebar__toggle">
                        <i class="fa-sharp fa-light fa-grid-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="header-sticky" class="header-1">
        <div class="container">
            <div class="mega-menu-wrapper">
                <div class="header-main">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="logo"><?php $grozomart_site_logo(); ?></a>
                    <div class="mean__menu-wrapper">
                        <div class="main-menu">
                            <nav id="mobile-menu">
                                <?php
                                /**
                                 * Grozomart_Nav_Walker inherits core's start_lvl(), which
                                 * emits `.sub-menu`; this design's CSS targets `.submenu`.
                                 * It also has no chevron indicator. Patch both for just
                                 * this menu rather than editing the shared walker.
                                 */
                                $grozomart_submenu_class_filter = function ($classes) {
                                    return array_merge(array_values(array_diff($classes, ['sub-menu'])), ['submenu']);
                                };
                                add_filter('nav_menu_submenu_css_class', $grozomart_submenu_class_filter);

                                $grozomart_menu_chevron_filter = function ($title, $item) {
                                    if (in_array('menu-item-has-children', $item->classes, true)) {
                                        $title .= ' <i class="fa-solid fa-chevron-down"></i>';
                                    }
                                    return $title;
                                };
                                add_filter('nav_menu_item_title', $grozomart_menu_chevron_filter, 10, 2);

                                wp_nav_menu(
                                    [
                                        'theme_location' => 'primary_menu',
                                        'container'      => false,
                                        'items_wrap'     => '<ul>%3$s</ul>',
                                        'fallback_cb'    => false,
                                        'walker'         => new Grozomart_Nav_Walker(),
                                    ]
                                );

                                remove_filter('nav_menu_submenu_css_class', $grozomart_submenu_class_filter);
                                remove_filter('nav_menu_item_title', $grozomart_menu_chevron_filter, 10);
                                ?>
                            </nav>
                        </div>
                    </div>
                    <div class="header-right">
                        <?php get_template_part('template-parts/header/header', 'button'); ?>
                        <div class="header__hamburger my-auto d-xl-none">
                            <div class="sidebar__toggle">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
