<?php

/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Grozomart
 */

use GrozomartTheme\Classes\Grozomart_Helper as Helper;
use GrozomartTheme\Classes\Grozomart_Post_Helper;

$show_meta     = Helper::get_option('archive_post_meta', 'yes');
$show_excerpt  = Helper::get_option('archive_post_excerpt', 'yes');
$excerpt_count = Helper::get_option('archive_excerpt_count', 40);
$show_button   = Helper::get_option('archive_post_button', 'yes');
$button_text   = Helper::get_option('post_button_text', __('Read More', 'grozomart'));

$post_class = ['news-items'];

if (! has_post_thumbnail()) {
	$post_class[] = 'no-thumbnail';
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class($post_class); ?>>
	<?php if (has_post_thumbnail()) : ?>
		<div class="news-thumb">
			<?php
			// The design layers the same image twice for its hover reveal.
			Grozomart_Post_Helper::render_media();
			Grozomart_Post_Helper::render_media();
			?>
		</div>
	<?php endif; ?>
	<div class="news-content">
		<?php
		if ('yes' === $show_meta) {
			Grozomart_Post_Helper::render_news_meta();
		}
		?>
		<?php the_title('<h3 class="title-2"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>'); ?>
		<?php
		if ('yes' === $show_excerpt) {
			if (has_excerpt()) {
				echo wpautop(wp_trim_words(get_the_excerpt(), $excerpt_count, '...'));
			} else {
				echo wpautop(wp_trim_words(get_the_content(), $excerpt_count, '...'));
			}
		}
		?>
		<?php if ('yes' === $show_button && ! empty($button_text)) : ?>
			<a href="<?php the_permalink(); ?>" class="theme-btn"><?php echo esc_html($button_text); ?></a>
		<?php endif; ?>
	</div>
</article>
