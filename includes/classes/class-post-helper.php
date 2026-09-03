<?php

namespace GrozomartTheme\Classes;

defined('ABSPATH') || exit;

/**
 * Post Helper Function
 */
class Grozomart_Post_Helper
{

	/**
	 * Renders the blog meta line used by the news cards and post header:
	 * Category । Post by <Author> । Date
	 */
	public static function render_news_meta($list_class = '')
	{
		$categories = get_the_category();
	?>
		<ul<?php if ($list_class) : ?> class="<?php echo esc_attr($list_class); ?>"<?php endif; ?>>
			<?php if (! empty($categories)) : ?>
				<li><?php echo esc_html($categories[0]->name); ?></li>
				<li>।</li>
			<?php endif; ?>
			<li>
				<?php
				printf(
					/* translators: %s: post author name. */
					esc_html__('Post by %s', 'grozomart'),
					'<b>' . esc_html(get_the_author()) . '</b>'
				);
				?>
			</li>
			<li>।</li>
			<li><?php echo esc_html(get_the_date()); ?></li>
		</ul>
	<?php
	}

	/**
	 * Get Post Media
	 */
	public static function render_media()
	{
		if (! has_post_thumbnail()) {
			return;
		}

		if ('no-sidebar' === Grozomart_Helper::content_sidebar()) {
			$size = 'grozomart_1290x620';
		} else {
			$size = 'grozomart_850x470';
		}

		the_post_thumbnail($size, ['alt' => wp_kses_post(get_the_title())]);
	}

	/**
	 * Get Meta Markup
	 */
	public static function meta_item_markup($meta)
	{
		$author_id = get_post_field('post_author', get_the_ID());

		if ('author' === $meta) : ?>
			<li><i class="far fa-user"></i>
				<a href="<?php echo esc_url(get_author_posts_url($author_id)) ?>">
					<?php echo esc_html(get_the_author_meta('display_name', $author_id)) ?>
				</a>
			</li>
		<?php elseif ('date' === $meta) : ?>
			<li><i class="far fa-calendar-alt"></i>
				<a href="<?php echo esc_url(get_the_permalink(get_the_ID())) ?>">
					<?php echo esc_html(get_the_date()) ?>
				</a>
			</li>
		<?php elseif ('comments' === $meta && ! post_password_required() && comments_open()) : ?>
			<li><i class="far fa-comments"></i>
				<a href="<?php echo esc_url(esc_url(get_comments_link())) ?>" class="comments">
					<span class="comment-text"><?php echo esc_html__('Comments ', 'grozomart') ?></span>
					<?php echo '(' . esc_html(get_comments_number()) . ')' ?>
				</a>
			</li>
		<?php endif;
	}

	/**
	 * Get Post Meta
	 */
	public static function render_post_meta()
	{
		$default_item = [
			'enabled' => [
				'author'   => esc_html__('Author', 'grozomart'),
				'date'     => esc_html__('Date', 'grozomart'),
				'comments' => esc_html__('Date', 'grozomart'),
			]
		];
		if (is_single()) {
			$meta_items = Grozomart_Helper::get_option('single_meta_items', $default_item);
		} else {
			$meta_items = Grozomart_Helper::get_option('archive_meta_items', $default_item);
		}
		$enable_meta = $meta_items['enabled'] ? $meta_items['enabled'] : [];
		?>
		<ul class="blog-meta-two">
			<?php foreach ($enable_meta as $key => $item) {
				self::meta_item_markup($key);
			} ?>
		</ul>
	<?php
	}

	/**
	 * Post Navigation
	 */
	public static function post_navigation()
	{
		if ('post' !== get_post_type()) {
			return;
		}

		$prev = get_previous_post();
		$next = get_next_post();

		if (empty($prev) && empty($next)) {
			return;
		}
	?>
		<div class="soil-from wow fadeInUp" data-wow-delay=".7s">
			<?php if (! empty($prev)) : ?>
				<a class="first-from" href="<?php echo esc_url(get_permalink($prev->ID)); ?>">
					<div class="icon">
						<i class="fa-solid fa-arrow-left"></i>
					</div>
					<div class="content">
						<ul>
							<li>
								<i class="fa-regular fa-calendar"></i>
								<?php echo esc_html(get_the_date('', $prev->ID)); ?>
							</li>
						</ul>
						<h3 class="title-5">
							<?php echo esc_html(wp_trim_words(get_the_title($prev->ID), 8, '...')); ?>
						</h3>
					</div>
				</a>
			<?php endif; ?>
			<?php if (! empty($next)) : ?>
				<a class="sec-from" href="<?php echo esc_url(get_permalink($next->ID)); ?>">
					<div class="content">
						<ul>
							<li>
								<i class="fa-regular fa-calendar"></i>
								<?php echo esc_html(get_the_date('', $next->ID)); ?>
							</li>
						</ul>
						<h3 class="title-5">
							<?php echo esc_html(wp_trim_words(get_the_title($next->ID), 8, '...')); ?>
						</h3>
					</div>
					<div class="icon">
						<i class="fa-solid fa-arrow-right"></i>
					</div>
				</a>
			<?php endif; ?>
		</div>
	<?php
	}

	/**
	 * Social share links for the current post.
	 */
	public static function post_share_links()
	{
		$url   = rawurlencode(get_permalink());
		$title = rawurlencode(wp_strip_all_tags(get_the_title()));

		$networks = [
			'facebook'   => 'https://www.facebook.com/sharer/sharer.php?u=' . $url,
			'twitter'    => 'https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title,
			'linkedin-in' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $url,
			'youtube'    => '#',
		];
	?>
		<div class="social-share">
			<span class="me-3"><?php esc_html_e('Share:', 'grozomart'); ?></span>
			<?php foreach ($networks as $icon => $link) : ?>
				<a href="<?php echo esc_url($link); ?>" target="_blank" rel="noopener noreferrer">
					<i class="fab fa-<?php echo esc_attr($icon); ?>"></i>
				</a>
			<?php endforeach; ?>
		</div>
	<?php
	}

	/**
	 * Post Author Info
	 */
	public static function post_author_info()
	{
		global $post;
		$user_id = get_the_author_meta('ID');

		// Get author's display name - NB! changed display_name to first_name. Error in code.
		$display_name = get_the_author_meta('display_name', $post->post_author);

		// If display name is not available then use nickname as display name
		if (empty($display_name)) {
			$display_name = get_the_author_meta('nickname', $post->post_author);
		}

		$user_description = get_the_author_meta('user_description', $post->post_author);
		$user_avatar      = get_avatar($user_id, 130);
	?>
		<div class="client-info-area">
			<div class="client-img">
				<?php echo wp_kses_post($user_avatar); ?>
			</div>
			<div class="content">
				<span><?php esc_html_e('Post by', 'grozomart'); ?> </span>
				<h3 class="title-6">
					<?php echo esc_html($display_name); ?>
				</h3>
				<?php if ($user_description) : ?>
					<p><?php echo esc_html($user_description); ?></p>
				<?php endif; ?>
				<?php self::author_social_links($user_id); ?>
			</div>
		</div>
	<?php
	}

	/**
	 * Author social links, driven by the user's profile fields.
	 */
	public static function author_social_links($user_id)
	{
		$networks = [
			'facebook'    => get_the_author_meta('facebook', $user_id),
			'twitter'     => get_the_author_meta('twitter', $user_id),
			'linkedin-in' => get_the_author_meta('linkedin', $user_id),
			'youtube'     => get_the_author_meta('youtube', $user_id),
		];

		$networks = array_filter($networks);

		if (empty($networks)) {
			return;
		}
	?>
		<div class="social-share">
			<?php foreach ($networks as $icon => $link) : ?>
				<a href="<?php echo esc_url($link); ?>" target="_blank" rel="noopener noreferrer">
					<i class="fab fa-<?php echo esc_attr($icon); ?>"></i>
				</a>
			<?php endforeach; ?>
		</div>
	<?php
	}

	/**
	 * Pagination
	 */
	public static function pagination($query = false)
	{
		if ($query != false) {
			$wp_query = $query;
		} else {
			global $paged, $wp_query;
		}

		if (empty($paged)) {
			$query_vars = $wp_query->query_vars;
			$paged      = $query_vars['paged'] ?? 1;
		}

		$max_page = $wp_query->max_num_pages;

		// Exit if pagination not need
		if (! ($max_page > 1)) {
			return;
		}

		//return $output;
		$big = 999999999;

		$page_items = paginate_links([
			'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
			'type'      => 'array',
			'current'   => max(1, $paged),
			'end_size'  => 1,
			'mid_size'  => 1,
			'total'     => $max_page,
			'prev_text' => '<i class="fa-solid fa-chevron-left"></i>',
			'next_text' => '<i class="fa-solid fa-chevron-right"></i>',
		]);
	?>
		<div class="page-nav-wrap text-center">
			<ul>
				<?php foreach ($page_items as $key => $value) : ?>
					<li<?php echo false !== strpos($value, 'current') ? ' class="active"' : ''; ?>><?php echo wp_kses_post($value) ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
<?php
	}
}
