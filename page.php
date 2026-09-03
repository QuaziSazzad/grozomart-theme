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

use GrozomartTheme\Classes\Grozomart_Helper as Helper;

get_header();
?>
<!-- Blog List Area start -->
<section class="blog-details-page pt-130 rpy-100 pb-110 rel z-1">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<?php
				if (have_posts()):
					/* Start the Loop */
					while (have_posts()): the_post();
						/*
						* Include the Post-Type-specific template for the content.
						* If you want to override this in a child theme, then include a file
						* called content-___.php (where ___ is the Post Type name) and that will be used instead.
						*/
						get_template_part('template-parts/contents/content', 'page');

						// If comments are open or we have at least one comment, load up the comment template.
						if (comments_open() || get_comments_number()):
							comments_template();
						endif;

					endwhile;
				endif;
				?>
			</div>
		</div>
	</div>
</section>
<!-- Blog List Area end -->
<?php
get_footer();
