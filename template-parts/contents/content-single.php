<?php

/**
 * Template part for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Grozomart
 */

use GrozomartTheme\Classes\Grozomart_Helper as Helper;
use GrozomartTheme\Classes\Grozomart_Post_Helper;

$show_post_share = Helper::get_option('blog_details_share', 'yes');
$show_tag        = Helper::get_option('blog_details_tag', 'yes');
$show_nav        = Helper::get_option('blog_details_nav', 'yes');
$author_info     = Helper::get_option('blog_author_info', 'yes');
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('news-details-content'); ?>>
	<?php if (has_post_thumbnail()) : ?>
		<div class="news-details-img">
			<?php Grozomart_Post_Helper::render_media(); ?>
		</div>
	<?php endif; ?>

	<div class="entry-content clearfix">
		<?php
		the_content();

		wp_link_pages(array(
			'before' => '<div class="page-links">' . esc_html__('Pages:', 'grozomart'),
			'after'  => '</div>',
		));
		?>
	</div>

	<?php
	/**
	 * Tags are core post data, so they show whenever the post has them —
	 * independent of Grozomart Toolkit. Only the share links come from the
	 * plugin's option sorter, so they disappear with the plugin.
	 *
	 * When neither is available the whole row is skipped, so the page does not
	 * keep an empty bordered block.
	 */
	$has_tags       = ('yes' === $show_tag && has_tag());
	$share_networks = ('yes' === $show_post_share) ? Grozomart_Post_Helper::enabled_share_networks() : [];
	$has_share      = ! empty($share_networks);
	?>
	<?php if ($has_tags || $has_share) : ?>
		<?php
		// Side by side when both are present; otherwise the one that renders
		// takes the full row instead of leaving half of it empty.
		$grozomart_col = ($has_tags && $has_share) ? 'col-lg-6' : 'col-lg-12';
		?>
		<div class="row tag-share-wrap mt-5 mb-5">
			<?php if ($has_tags) : ?>
				<div class="<?php echo esc_attr($grozomart_col); ?> col-12 wow fadeInUp" data-wow-delay=".2s">
					<div class="tagcloud">
						<span><?php esc_html_e('Tags:', 'grozomart'); ?></span>
						<?php the_tags('', ''); ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if ($has_share) : ?>
				<div class="<?php echo esc_attr($grozomart_col); ?> col-12 mt-3 mt-lg-0 <?php echo $has_tags ? 'text-lg-end' : ''; ?> wow fadeInUp" data-wow-delay=".4s">
					<?php Grozomart_Post_Helper::post_share_links(); ?>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php
	if ('yes' === $show_nav) {
		Grozomart_Post_Helper::post_navigation();
	}

	if ('yes' === $author_info) {
		Grozomart_Post_Helper::post_author_info();
	}

	// Comments live inside the article so they sit within .news-details-content.
	if (comments_open() || get_comments_number()) {
		comments_template();
	}
	?>
</article>
