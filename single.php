<?php

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Grozomart
 */

use GrozomartTheme\Classes\Grozomart_Helper as Helper;

get_header();
?>
<!-- News Details Section Start -->
<section class="news-details-section-in section-padding fix">
	<div class="container">
		<?php
		if (have_posts()) {
			the_post();
			get_template_part('template-parts/contents/post-header');
			rewind_posts();
		}
		?>
		<div class="news-details-wrapper-in">
			<div class="row g-4">
				<div class="<?php Helper::col_size(); ?>">
					<?php
					while (have_posts()) :
						the_post();

						// The comment area is rendered inside the content part so it
						// stays within .news-details-content, as the design expects.
						get_template_part('template-parts/contents/content', 'single');

					endwhile; // End of the loop.
					?>
				</div>
				<?php get_sidebar(); ?>
			</div>
		</div>
	</div>
</section>
<!-- News Details Section End -->
<?php
get_footer();
