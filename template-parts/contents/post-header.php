<?php

/**
 * Single post heading block, rendered inside the news details section so it
 * picks up `.news-details-section-in .news-details-top-area` styling.
 *
 * @package Grozomart
 */

use GrozomartTheme\Classes\Grozomart_Helper as Helper;
use GrozomartTheme\Classes\Grozomart_Post_Helper;

$show_breadcrumb = Helper::get_option('site_breadcrumb', 'enabled');
$show_post_meta  = Helper::get_option('blog_details_meta', 'yes');

$post_breadcrumb = Helper::get_meta('grozomart_post_meta', 'post_breadcrumb', 'default');
if ('default' !== $post_breadcrumb) {
	$show_breadcrumb = $post_breadcrumb;
}
?>
<div class="news-details-top-area">
	<?php if ('enabled' === $show_breadcrumb) : ?>
		<ul class="news-list wow fadeInUp">
			<li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'grozomart'); ?></a></li>
			<li>/</li>
			<li><?php echo wp_kses_post(get_the_title()); ?></li>
		</ul>
	<?php endif; ?>
	<h1 class="title">
		<?php echo wp_kses_post(get_the_title()); ?>
	</h1>
	<?php
	if ('yes' === $show_post_meta) {
		Grozomart_Post_Helper::render_news_meta('news-list-2');
	}
	?>
</div>
