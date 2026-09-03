<?php

/**
 * The template for displaying all single Elementor Template
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Grozomart
 */

get_header();
?>
<div class="elementor-template">
	<div class="elementor-template-inner">
		<?php
		if (have_posts()) : while (have_posts()) : the_post();

				the_content();

			endwhile;
		endif; // End of the loop.
		?>
	</div>
</div>
<?php
get_footer();
