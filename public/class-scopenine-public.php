<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/abdullahsajjad
 * @since      1.0.0
 *
 * @package    Scope_Nine_Alert_Bar
 * @subpackage Scope_Nine_Alert_Bar/public
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Scope_Nine_Alert_Bar
 * @subpackage Scope_Nine_Alert_Bar/public
 * @author     Abdullah Sajjad <abdullahsajjad33@gmail.com>
 */
class SCOPENINE_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $plugin_name    The name of the plugin.
	 * @param    string    $version        The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Settings for the current request when the bar should display, or false.
	 *
	 * Cached so the checks only run once per request.
	 *
	 * @since 1.0.0
	 * @var array|false|null
	 */
	private $display_settings = null;

	/**
	 * Return the plugin settings if the bar should display on this request.
	 *
	 * @since 1.0.0
	 * @return array|false
	 */
	protected function get_display_settings() {
		if ( null !== $this->display_settings ) {
			return $this->display_settings;
		}

		$this->display_settings = false;
		$scopenine_settings           = get_option( 'scopenine_settings' );

		if ( empty( $scopenine_settings ) || ! is_array( $scopenine_settings ) ) {
			return false;
		}

		// Respect Enable/Disable setting (default to enabled when not set, for backward compatibility).
		if ( isset( $scopenine_settings['enabled'] ) && '1' !== $scopenine_settings['enabled'] ) {
			return false;
		}

		// The bar needs at least WYSIWYG content (news_title) to display.
		$news_title = $scopenine_settings['news_title'] ?? '';
		if ( empty( trim( wp_strip_all_tags( $news_title ) ) ) ) {
			return false;
		}

		// If a post is linked, check its expiry.
		if ( ! empty( $scopenine_settings['post_id'] ) && true === $this->check_alert_bar_expiry( $scopenine_settings['post_id'] ) ) {
			return false;
		}

		// Check display mode visibility.
		if ( ! $this->should_display( $scopenine_settings ) ) {
			return false;
		}

		$this->display_settings = $scopenine_settings;
		return $scopenine_settings;
	}

	/**
	 * Enqueue the front-end script.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_assets() {
		$scopenine_settings = $this->get_display_settings();
		if ( false === $scopenine_settings ) {
			return;
		}

		wp_enqueue_script(
			$this->plugin_name,
			SCOPENINE_PLUGIN_URL . 'public/assets/js/scopenine-public.js',
			array(),
			$this->version,
			array( 'in_footer' => true )
		);

		if ( 'off' !== ( $scopenine_settings['scroll_text'] ?? 'off' ) ) {
			wp_enqueue_style(
				$this->plugin_name,
				SCOPENINE_PLUGIN_URL . 'public/assets/css/scopenine-public.css',
				array(),
				$this->version
			);
		}

	}

	/**
	 * Display Alert Bar as direct HTML output.
	 *
	 * Hooked into wp_body_open so the bar is output directly in the page
	 * markup. Works reliably with both classic and block/Gutenberg themes.
	 *
	 * @since 1.0.0
	 */
	public function display_alert_bar() {
		$scopenine_settings = $this->get_display_settings();
		if ( false === $scopenine_settings ) {
			return;
		}

		$news_title = $scopenine_settings['news_title'] ?? '';
		// Only link published posts, so drafts and private posts never leak.
		$has_post = ! empty( $scopenine_settings['post_id'] ) && 'publish' === get_post_status( $scopenine_settings['post_id'] );

		// Build optional post link.
		$post_link_html = '';
		if ( $has_post ) {
			$title = get_post_meta( $scopenine_settings['post_id'], 'scopenine-custom-title', true );
			if ( '' === $title || false === $title ) {
				$title = get_the_title( $scopenine_settings['post_id'] );
			}
			$permalink      = esc_url( get_the_permalink( $scopenine_settings['post_id'] ) );
			$txt_color_attr = esc_attr( $scopenine_settings['text_color'] ?? '#ffffff' );
			$post_link_html = '&nbsp;<a style="color:' . $txt_color_attr . ';" href="' . $permalink . '">' . esc_html( $title ) . '</a>';
		}

		$bg_color       = esc_attr( $scopenine_settings['background_color'] ?? '#ff0000' );
		$txt_color      = esc_attr( $scopenine_settings['text_color'] ?? '#ffffff' );
		$scopenine_position   = $scopenine_settings['scopenine_position'] ?? 'after-header';
		$font_size      = absint( $scopenine_settings['font_size'] ?? 14 );
		$padding_top    = absint( $scopenine_settings['padding_top'] ?? 10 );
		$padding_right  = absint( $scopenine_settings['padding_right'] ?? 40 );
		$padding_bottom = absint( $scopenine_settings['padding_bottom'] ?? 10 );
		$padding_left   = absint( $scopenine_settings['padding_left'] ?? 40 );
		$close_icon_url = $scopenine_settings['close_icon_url'] ?? '';
		$scroll_mode    = $scopenine_settings['scroll_text'] ?? 'off';
		$bar_classes    = 'scopenine-alert-bar';
		if ( in_array( $scroll_mode, array( 'mobile', 'always' ), true ) ) {
			$bar_classes .= ' scopenine-scroll-' . $scroll_mode;
			if ( 'ltr' === ( $scopenine_settings['scroll_direction'] ?? 'rtl' ) ) {
				$bar_classes .= ' scopenine-scroll-ltr';
			}
		}

		// Build the close icon: custom upload or inline SVG default.
		if ( ! empty( $close_icon_url ) ) {
			$close_icon = '<img src="' . esc_url( $close_icon_url ) . '" alt="close" style="width:14px;height:14px;display:block;" />';
		} else {
			$close_icon = '<svg width="14" height="14" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19.6746 0.3254C19.2408-0.1085 18.5373-0.1085 18.1035 0.3254L10 8.4289 1.8965 0.3254C1.4626-0.1085 0.7592-0.1085 0.3254 0.3254-0.1085 0.7592-0.1085 1.4626 0.3254 1.8965L8.4289 10 0.3254 18.1035C-0.1085 18.5374-0.1085 19.2407 0.3254 19.6746 0.7593 20.1085 1.4627 20.1085 1.8965 19.6746L10 11.5711 18.1035 19.6746C18.5373 20.1085 19.2408 20.1085 19.6746 19.6746 20.1084 19.2407 20.1084 18.5374 19.6746 18.1036L11.571 10 19.6746 1.8965C20.1084 1.4626 20.1084 0.7592 19.6746 0.3254Z" fill="' . esc_attr( $txt_color ) . '"/></svg>';
		}

		// Mark the bar with a data attribute for position so the script can move it if needed.
		?>
		<div id="scopenine-alert-bar"
		     class="<?php echo esc_attr( $bar_classes ); ?>"
		     data-scopenine-position="<?php echo esc_attr( $scopenine_position ); ?>"
		     style="background-color:<?php echo esc_attr( $bg_color ); ?>;color:<?php echo esc_attr( $txt_color ); ?>;font-size:<?php echo esc_attr( $font_size ); ?>px;display:flex;align-items:center;justify-content:center;text-align:center;position:relative;z-index:9999;padding:<?php echo esc_attr( $padding_top ); ?>px <?php echo esc_attr( $padding_right ); ?>px <?php echo esc_attr( $padding_bottom ); ?>px <?php echo esc_attr( $padding_left ); ?>px;">
			<span class="scopenine-alert-bar-content"><span class="scopenine-alert-bar-track"><?php echo wp_kses_post( $news_title ); ?><?php echo wp_kses_post( $post_link_html ); ?></span></span>
			<button type="button" class="scopenine-alert-bar-close" aria-label="<?php esc_attr_e( 'Dismiss alert', 'scope-nine-alert-bar' ); ?>" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;padding:4px;opacity:0.85;line-height:0;display:flex;align-items:center;justify-content:center;"><?php echo wp_kses( $close_icon, array( 'svg' => array( 'width' => true, 'height' => true, 'viewbox' => true, 'fill' => true, 'xmlns' => true ), 'path' => array( 'd' => true, 'fill' => true ), 'img' => array( 'src' => true, 'alt' => true, 'style' => true ) ) ); ?></button>
		</div>
		<?php
	}

	/**
	 * Determines whether the alert bar should display
	 * on the current page based on display mode settings.
	 *
	 * @param array $scopenine_settings The plugin settings.
	 * @return bool
	 * @since 1.0.0
	 */
	protected function should_display( array $scopenine_settings ): bool {
		$display_mode = $scopenine_settings['display_mode'] ?? 'site_wide';

		switch ( $display_mode ) {
			case 'site_wide':
				return true;

			case 'homepage_only':
				return is_front_page() || is_home();

			case 'specific_pages':
				$selected_pages = isset( $scopenine_settings['selected_pages'] ) && is_array( $scopenine_settings['selected_pages'] )
					? $scopenine_settings['selected_pages']
					: array();

				if ( empty( $selected_pages ) ) {
					return false;
				}

				$current_id = get_queried_object_id();
				return in_array( (string) $current_id, $selected_pages, true );

			default:
				return true;
		}
	}

	/**
	 * Checks the alert bar expiry and unsets
	 * the alert from options
	 *
	 * @param int $post_id Post ID.
	 * @return bool
	 * @since 1.0.0
	 */
	protected function check_alert_bar_expiry( $post_id ): bool {
		$is_expiry = get_post_meta( $post_id, 'scopenine-is-expiry', true );
		if ( empty( $is_expiry ) ) {
			return false;
		}

		$expiry_date_time = get_post_meta( $post_id, 'scopenine-expiry-date-time', true );
		$current_time     = strtotime( current_time( 'mysql' ) );

		if ( $current_time < $expiry_date_time ) {
			return false;
		}

		// Unset alert bar post.
		$scopenine_settings = get_option( 'scopenine_settings' );
		unset( $scopenine_settings['post_id'] );
		update_option( 'scopenine_settings', $scopenine_settings );

		return true;
	}
}
