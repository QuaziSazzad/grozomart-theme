<?php

/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Grozomart
 */

?>
<div id="post-<?php the_ID(); ?>" <?php post_class('page-inner clearfix'); ?>>
	<div class="blog-details-content news-details-content">
		<?php
		the_content();

		wp_link_pages([
			'before' => '<div class="page-links">' . esc_html__('Pages:', 'grozomart'),
			'after'  => '</div>',
		]);

		// Comments live inside .news-details-content so they pick up the same
		// styling the single-post comment list uses.
		if (comments_open() || get_comments_number()) {
			comments_template();
		}
		?>
	</div>
</div>
