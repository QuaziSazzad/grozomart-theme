<?php

/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Grozomart
 */

use GrozomartTheme\Classes\Grozomart_Helper as Helper;
use GrozomartTheme\Classes\Grozomart_Post_Helper;

get_header();
?>
<!--News Section Start -->
<section class="news-section-in section-padding fix">
	<div class="container">
		<div class="news-wrapper-in">
			<div class="row g-4">
				<div class="<?php Helper::col_size(); ?>">
					<div class="news-content">
						<?php
						if (have_posts()):
							/* Start the Loop */
							while (have_posts()): the_post();
								/*
								* Include the Post-Type-specific template for the content.
								* If you want to override this in a child theme, then include a file
								* called content-___.php (where ___ is the Post Type name) and that will be used instead.
								*/
								get_template_part('template-parts/contents/content');

							endwhile;

							Grozomart_Post_Helper::pagination();
						else:
							get_template_part('template-parts/contents/content', 'none');
						endif;
						?>
					</div>
				</div>
				<?php get_sidebar(); ?>
			</div>
		</div>
	</div>
</section>
<!--News Section End -->
<?php
get_footer();
