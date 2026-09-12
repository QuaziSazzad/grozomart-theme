<?php

/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Grozomart
 */

get_header();
?>
<!-- Blog List Area start -->
<section class="news-section-in section-padding fix">
	<div class="container">
		<div class="news-wrapper-in">
			<div class="row">
				<div class="col-lg-12">
					<?php
					// Mirrors single.php so the comment list inside the content
					// part matches the blog's comment styling.
					?>
					<div class="news-details-wrapper-in">
						<?php
						if (have_posts()):
							/* Start the Loop */
							while (have_posts()): the_post();
								/*
								* Include the Post-Type-specific template for the content.
								* If you want to override this in a child theme, then include a file
								* called content-___.php (where ___ is the Post Type name) and that will be used instead.
								*
								* Comments are rendered inside the content part so they stay
								* within .news-details-content, as the design expects.
								*/
								get_template_part('template-parts/contents/content', 'page');

							endwhile;
						endif;
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Blog List Area end -->
<?php
get_footer();
