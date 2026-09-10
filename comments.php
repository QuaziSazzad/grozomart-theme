<?php

/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Grozomart
 */

use GrozomartTheme\Classes\Grozomart_Comment_Walker;

if (post_password_required()) {
	return;
}
?>

<div id="comments" class="comment-area wow fadeInUp" data-wow-delay=".6s">
	<?php if (have_comments()): ?>
		<h3 class="comment-title">
			<?php
			comments_number(
				esc_html__('0 Customer Comments', 'grozomart'),
				esc_html__('1 Customer Comment', 'grozomart'),
				esc_html__('% Customer Comments', 'grozomart')
			);
			?>
		</h3>

		<ul class="comment-list">
			<?php
			wp_list_comments([
				'walker'      => new Grozomart_Comment_Walker(),
				'avatar_size' => 100,
				'short_ping'  => true,
			]);
			?>
		</ul>

		<?php
		the_comments_navigation();

		if (! comments_open()) : ?>
			<p class="no-comments"><?php esc_html_e('Comments are closed.', 'grozomart'); ?></p>
	<?php endif;

	endif;

	$grozomart_commenter = wp_get_current_commenter();

	$grozomart_comment_fields = array(
		'author' => '<div class="col-lg-6"><div class="form-clt">'
			. '<span>' . esc_html__('Full name*', 'grozomart') . '</span>'
			. '<input type="text" id="name" name="author" placeholder="' . esc_attr__('Your name', 'grozomart') . '" value="' . esc_attr($grozomart_commenter['comment_author']) . '" required>'
			. '</div></div>',
		'email'  => '<div class="col-lg-6"><div class="form-clt">'
			. '<span>' . esc_html__('Email Address*', 'grozomart') . '</span>'
			. '<input type="email" id="email" name="email" placeholder="' . esc_attr__('Your email', 'grozomart') . '" value="' . esc_attr($grozomart_commenter['comment_author_email']) . '" required>'
			. '</div></div>',
	);

	$grozomart_comments_args = array(
		'fields'               => apply_filters('comment_form_default_fields', $grozomart_comment_fields),
		// Keep core's comment-respond so default #respond styling still applies.
		'class_container'      => 'comment-respond comment-form-wrap wow fadeInUp',
		'class_form'           => 'row g-4',
		'title_reply_before'   => '<h3>',
		'title_reply'          => esc_html__('Leave a Comment', 'grozomart'),
		'title_reply_after'    => '</h3>',
		'comment_notes_before' => '<p>' . esc_html__('Your email address will not be published. Required fields are marked *', 'grozomart') . '</p>',
		'comment_field'        => '<div class="col-lg-12"><div class="form-clt">'
			. '<span>' . esc_html__('Your Comment*', 'grozomart') . '</span>'
			. '<textarea name="comment" id="message" placeholder="' . esc_attr__('Write comment', 'grozomart') . '" required></textarea>'
			. '</div></div>',
		'comment_notes_after'  => '',
		'submit_field'         => '<div class="col-lg-6">%1$s %2$s</div>',
		'submit_button'        => '<button type="submit" class="theme-btn">' . esc_html__('Send Comment', 'grozomart') . '</button>',
		// This renders inside the form's grid row, so give it a column of its own.
		'logged_in_as'         => sprintf(
			'<div class="col-lg-12"><p class="logged-in-as">%s</p></div>',
			sprintf(
				/* translators: 1: user profile URL, 2: user name, 3: logout URL. */
				wp_kses(__('Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s">Log out?</a>', 'grozomart'), ['a' => ['href' => []]]),
				esc_url(get_edit_user_link()),
				esc_html(wp_get_current_user()->display_name),
				esc_url(wp_logout_url(apply_filters('the_permalink', get_permalink())))
			)
		),
	);

	/**
	 * Core renders the comment textarea before the author/email fields, and
	 * emits the cookie-consent checkbox without a grid column. Reorder the
	 * textarea to the end and give the checkbox a column of its own.
	 */
	add_filter('comment_form_fields', function ($fields) {
		if (isset($fields['cookies'])) {
			$fields['cookies'] = '<div class="col-lg-12"><div class="form-check">' . $fields['cookies'] . '</div></div>';
		}

		// Desired order: name, email, comment, then the consent checkbox.
		$order    = ['author', 'email', 'url', 'comment', 'cookies'];
		$ordered  = [];

		foreach ($order as $key) {
			if (isset($fields[$key])) {
				$ordered[$key] = $fields[$key];
				unset($fields[$key]);
			}
		}

		// Anything a plugin added that we didn't account for keeps its place at the end.
		return array_merge($ordered, $fields);
	});

	comment_form($grozomart_comments_args);

	?>
</div>