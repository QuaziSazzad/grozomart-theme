<?php

namespace GrozomartTheme\Classes;

defined('ABSPATH') || exit;

/**
 * Load Theme Assets
 */
class Grozomart_Assets
{

	protected static $instance = null;

	public static function instance()
	{
		if (null === self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct()
	{
		add_action('wp_enqueue_scripts', [$this, 'register_scripts']);
		add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);
		// Priority 20: run after the Grozomart Toolkit's own wp_enqueue_scripts
		// (default priority 10), so wp_script_is(..., 'registered') below can
		// see whichever optional libraries the plugin registered, when active.
		add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts'], 20);
		add_action('wp_enqueue_scripts', [$this, 'global_root_css']);

		add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);

		add_action('wp_head', [$this, 'custom_header_scripts']);
		add_action('wp_footer', [$this, 'custom_footer_scripts']);
	}

	public function google_font_url()
	{
		$fonts_url     = '';
		$font_families = [];

		$primary_font   = Grozomart_Helper::get_option('primary_font', ['font-family' => '']);
		$secondary_font = Grozomart_Helper::get_option('secondary_font', ['font-family' => '']);

		if ('' === $primary_font || (is_array($primary_font) && empty($primary_font['font-family']))) {
			if ('off' !== _x('on', 'Inter', 'grozomart')) {
				$font_families[] = 'Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900';
			}
		}

		if ('' === $secondary_font || (is_array($secondary_font) && empty($secondary_font['font-family']))) {
			if ('off' !== _x('on', 'Hanken Grotesk', 'grozomart')) {
				$font_families[] = 'Hanken Grotesk:ital,wght@0,100..900;1,100..900';
			}
		}

		if (!empty($font_families)) {
			// Build Google Fonts /css2 URL
			$query_args = array_map(function ($family) {
				return 'family=' . urlencode($family);
			}, $font_families);

			$query_args[] = 'display=swap';

			$fonts_url = 'https://fonts.googleapis.com/css2?' . implode('&', $query_args);
		}

		return esc_url_raw($fonts_url);
	}

	public function admin_google_font_url()
	{
		$font_families = [];
		$subsets       = 'latin';

		if ('off' !== _x('on', 'Inter', 'grozomart')) {
			$font_families[] = 'Inter:300,400,500,600';
		}

		$fonts_url = add_query_arg([
			'family' => urlencode(implode('|', $font_families)),
			'subset' => urlencode($subsets),
		], 'https://fonts.googleapis.com/css');

		return esc_url_raw($fonts_url);
	}

	public function register_scripts()
	{
		wp_register_style('bootstrap', GROZOMART_VENDOR . '/bootstrap/bootstrap.min.css', [], '1.1.0');
		wp_register_script('bootstrap', GROZOMART_VENDOR . '/bootstrap/bootstrap.bundle.min.js', ['jquery'], '1.1.0', true);

		wp_register_style('animate', GROZOMART_VENDOR . '/animate/animate.css', [], '4.1.1');

		wp_register_style('meanmenu', GROZOMART_VENDOR . '/meanmenu/meanmenu.css', [], '1.1.0');
		wp_register_script('meanmenu', GROZOMART_VENDOR . '/meanmenu/jquery.meanmenu.min.js', ['jquery'], '1.1.0', true);
	}

	public function enqueue_styles()
	{
		wp_enqueue_style('grozomart-fonts', $this->google_font_url(), [], null);
		wp_enqueue_style('bootstrap');
		wp_enqueue_style('fontawesome', GROZOMART_VENDOR . '/fontawesome/all.min.css', [], '6.7');
		wp_enqueue_style('animate');
		wp_enqueue_style('meanmenu');
		wp_enqueue_style('grozomart-theme', GROZOMART_ASSETS . '/css/style.css', [], GROZOMART_VERSION);
		wp_enqueue_style('grozomart-style', get_stylesheet_uri(), [], GROZOMART_VERSION);
	}

	/**
	 * Inline CSS
	 *
	 * @return void
	 */
	public function global_root_css()
	{
		$boxed_width     = Grozomart_Helper::get_option('boxed_width', []);
		$global_colors   = Grozomart_Helper::get_global_colors();
		$page_colors   = Grozomart_Helper::get_page_colors();
		$global_fonts    = Grozomart_Helper::get_global_fonts();
		$page_fonts    = Grozomart_Helper::get_page_fonts();
		$inline_css      = [];


		if (! empty($boxed_width)) {
			$inline_css[] = '--grozomart-boxed-width: ' . $boxed_width['width'] . 'px';
		} else {
			$inline_css[] = '--grozomart-boxed-width: 1530px';
		}



		foreach ($global_colors as $key => $color) {
			$inline_css[] = '--' . $color['slug'] . ':' . $color['value'];
		}

		foreach ($global_fonts as $font) {
			$inline_css[] = '--' . $font['slug'] . ':' . $font['font-family'] . ', ' . $font['backup-font-family'];
		}

		if (is_page()) :
			foreach ($page_colors as $key => $color) {
				$inline_css[] = '--' . $color['slug'] . ':' . $color['value'];
			}

			foreach ($page_fonts as $font) {
				$inline_css[] = '--' . $font['slug'] . ':' . $font['font-family'] . ', ' . $font['backup-font-family'];
			}
		endif;


		$output = '
        :root {
            ' . esc_attr(implode('; ', $inline_css)) . '
        }
        ';

		wp_add_inline_style('grozomart-style', $output);
	}

	/**
	 * Enqueue Theme Scripts
	 *
	 * @return void
	 */
	public function enqueue_scripts()
	{
		wp_enqueue_script('bootstrap');

		/**
		 * theme.js only hard-depends on jquery and meanmenu, both registered
		 * by the theme itself. nice-select/magnific-popup/swiper/counterup/
		 * wow/parallaxie are registered by the Grozomart Toolkit plugin and
		 * every use of them in theme.js is already guarded with
		 * `if ($.fn.xxx)`, so they're optional soft dependencies: theme.js
		 * loads after them when the plugin is active, but still loads (with
		 * those features simply skipped) when it's deactivated. Previously
		 * they were hard dependencies, so deactivating the plugin removed
		 * those handles and WordPress refused to enqueue theme.js at all —
		 * including the preloader fade-out, leaving a blank white page.
		 */
		$theme_js_deps = ['jquery', 'meanmenu'];
		foreach (['nice-select', 'magnific-popup', 'swiper', 'counterup', 'wow', 'parallaxie'] as $optional_handle) {
			if (wp_script_is($optional_handle, 'registered')) {
				$theme_js_deps[] = $optional_handle;
			}
		}

		wp_enqueue_script(
			'grozomart-theme',
			GROZOMART_ASSETS . '/js/theme.js',
			$theme_js_deps,
			GROZOMART_VERSION,
			true
		);


		if (is_singular() && comments_open() && get_option('thread_comments')) {
			wp_enqueue_script('comment-reply');
		}
	}

	/**
	 * Admin CSS
	 */
	public function enqueue_admin_scripts()
	{
		wp_enqueue_style('grozomart-admin-fonts', $this->admin_google_font_url(), [], null);
		wp_enqueue_style('grozomart-admin', GROZOMART_ASSETS . '/css/admin.min.css', [], time(), 'all');
		wp_enqueue_script('grozomart-admin', GROZOMART_ASSETS . '/js/admin.min.js', ['jquery'], time(), true);

		wp_localize_script(
			'grozomart-admin',
			'grozomartAdminLocalize',
			[
				'ajax_url' => admin_url('admin-ajax.php'),
			]
		);
	}

	/**
	 * Custom Header Scripts
	 */
	public function custom_header_scripts()
	{
		if ('' !== Grozomart_Helper::get_option('custom_header_scripts')): ?>
			<script>
				<?php echo Grozomart_Helper::get_option('custom_header_scripts'); ?>
			</script>
		<?php endif;
	}

	/**
	 * Custom Scripts
	 */
	public function custom_footer_scripts()
	{
		if ('' !== Grozomart_Helper::get_option('custom_footer_scripts')): ?>
			<script>
				<?php echo Grozomart_Helper::get_option('custom_footer_scripts'); ?>
			</script>
<?php endif;
	}
}

Grozomart_Assets::instance();
