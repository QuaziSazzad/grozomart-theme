<?php

/**
 * The sidebar containing the Primary widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Grozomart
 */

use GrozomartTheme\Classes\Grozomart_Helper as Helper;

if ('no-sidebar' === Helper::content_sidebar() || ! is_active_sidebar('primary_sidebar')) {
  return;
}

$grozomart_sidebar_class = '';
if (Helper::content_sidebar() == 'left-sidebar') {
  $grozomart_sidebar_class = 'order-first';
}
?>
<div class="col-lg-4 <?php echo esc_attr($grozomart_sidebar_class); ?>">
  <div class="main-sideber">
    <?php dynamic_sidebar('primary_sidebar'); ?>
  </div>
</div>
