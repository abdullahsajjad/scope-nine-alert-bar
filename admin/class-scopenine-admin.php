<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/abdullahsajjad
 * @since      1.0.0
 *
 * @package    Scope_Nine_Alert_Bar
 * @subpackage Scope_Nine_Alert_Bar/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Scope_Nine_Alert_Bar
 * @subpackage Scope_Nine_Alert_Bar/admin
 * @author     Abdullah Sajjad <abdullahsajjad33@gmail.com>
 */
class SCOPENINE_Admin {

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
	 * @param    string    $plugin_name    The name of this plugin.
	 * @param    string    $version        The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @param string $hook Current admin page hook suffix.
	 * @since    1.0.0
	 */
	public function enqueue_styles( $hook ) {
		wp_enqueue_style( $this->plugin_name, SCOPENINE_PLUGIN_URL . 'admin/assets/css/scopenine-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook ) {
		$plugin_pages = array( 'toplevel_page_scope-nine-alert-bar', 'alert-bar_page_scope-nine-alert-bar-advanced' );

		wp_enqueue_script( $this->plugin_name, SCOPENINE_PLUGIN_URL . 'admin/assets/js/scopenine-admin.js', array( 'jquery' ), $this->version, false );

		// Enqueue media library on the plugin Design page for close icon upload.
		if ( in_array( $hook, $plugin_pages, true ) ) {
			wp_enqueue_media();
		}
	}

	/**
	 * Add Settings link to the plugin row on the Plugins screen.
	 *
	 * @param array $links Existing plugin row links.
	 * @return array Modified links.
	 * @since 1.0.0
	 */
	public function add_plugin_action_links( $links ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return $links;
		}

		$settings_link = sprintf(
			'<a href="%s">%s</a>',
			esc_url( admin_url( 'admin.php?page=scope-nine-alert-bar' ) ),
			esc_html__( 'Settings', 'scope-nine-alert-bar' )
		);

		array_unshift( $links, $settings_link );

		return $links;
	}

	/**
	 * Adds the Settings Page and subpages in Admin Dashboard
	 *
	 * @since 1.0.0
	 */
	public function add_admin_page() {
		add_menu_page(
			__( 'Alert Bar', 'scope-nine-alert-bar' ),
			__( 'Alert Bar', 'scope-nine-alert-bar' ),
			'manage_options',
			'scope-nine-alert-bar',
			array( $this, 'settings_page_callback' ),
			'dashicons-megaphone',
			90
		);

		// Rename the auto-created first submenu to "Design".
		add_submenu_page(
			'scope-nine-alert-bar',
			__( 'Design — Alert Bar', 'scope-nine-alert-bar' ),
			__( 'Design', 'scope-nine-alert-bar' ),
			'manage_options',
			'scope-nine-alert-bar',
			array( $this, 'settings_page_callback' )
		);

		add_submenu_page(
			'scope-nine-alert-bar',
			__( 'Advanced — Alert Bar', 'scope-nine-alert-bar' ),
			__( 'Advanced', 'scope-nine-alert-bar' ),
			'manage_options',
			'scope-nine-alert-bar-advanced',
			array( $this, 'advanced_page_callback' )
		);
	}

	/**
	 * Main Settings Page Callback method
	 *
	 * @since 1.0.0
	 */
	public function settings_page_callback() {
		require_once SCOPENINE_PLUGIN_PATH . 'admin/templates/scopenine-admin-display.php';
	}

	/**
	 * Advanced Settings Page Callback method
	 *
	 * @since 1.0.0
	 */
	public function advanced_page_callback() {
		require_once SCOPENINE_PLUGIN_PATH . 'admin/templates/scopenine-advanced-display.php';
	}

	/**
	 * Adds Custom Meta boxes of the plugin
	 *
	 * @since 1.0.0
	 */
	public function add_custom_meta_box() {
		// Only site admins can choose the alert bar post.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		add_meta_box(
			'scopenine_fields',
			__( 'Alert Bar', 'scope-nine-alert-bar' ),
			array( $this, 'scopenine_meta_box_callback' ),
			$this->get_supported_post_types(),
			'advanced',
			'high'
		);
	}

	/**
	 * Return names of all registered post types
	 *
	 * @access protected
	 * @return mixed|void
	 * @since 1.0.0
	 */
	protected function get_supported_post_types() {
		$args = array(
			'public' => true,
		);

		$output   = 'names';
		$operator = 'and';

		return apply_filters( 'scopenine_supported_post_types', get_post_types( $args, $output, $operator ) );
	}

	/**
	 * Custom metabox Callback, displays all custom meta fields
	 *
	 * Meta Fields
	 * - Feature in Alert Bar (checkbox)  => scopenine_is_alert_bar
	 * - Custom title (text)              => scopenine_custom_title
	 * - Set Expiry Date (checkbox)       => scopenine_expiry
	 * - Expiry Date (Date)               => scopenine_expiry_date
	 * - Expiry Time (Time)               => scopenine_expiry_time
	 *
	 * @since 1.0.0
	 */
	public function scopenine_meta_box_callback( $post ) {

		wp_nonce_field( 'scopenine_meta_nonce', 'scopenine_meta_nonce' );

		$scopenine_settings = get_option( 'scopenine_settings' );

		$current_alert_bar = '';
		if ( isset( $scopenine_settings['post_id'] ) && ! empty( $scopenine_settings['post_id'] ) ) {
			$current_alert_bar = $scopenine_settings['post_id'];
		}

		$scopenine_is_expiry    = get_post_meta( $post->ID, 'scopenine-is-expiry', true );
		$scopenine_custom_title = get_post_meta( $post->ID, 'scopenine-custom-title', true );
		$scopenine_expiry       = $this->scopenine_date_time( $post->ID );
		?>
		<div class="scopenine-meta-main">
			<div class="scopenine-meta-control">
				<label class="scopenine-label" for="is-alert-bar"><?php esc_html_e( 'Feature in Alert Bar', 'scope-nine-alert-bar' ); ?></label>
				<label class="scopenine-checkbox">
					<input name="scopenine_is_alert_bar" id="is-alert-bar" type="checkbox" <?php checked( $current_alert_bar, $post->ID ); ?> />
					<span class="slider round"></span>
				</label>
				<small class="scopenine-small-description"><?php esc_html_e( 'Enable to display this post in the alert bar', 'scope-nine-alert-bar' ); ?></small>
			</div>
			<div class="scopenine-meta-control">
				<label class="scopenine-label" for="scopenine-custom-title"><?php esc_html_e( 'Custom Title', 'scope-nine-alert-bar' ); ?></label>
				<input class="scopenine-meta-field" name="scopenine_custom_title" id="scopenine-custom-title" type="text" value="<?php echo esc_attr( $scopenine_custom_title ?? '' ); ?>"/>
				<small class="scopenine-small-description"><?php esc_html_e( 'Add a custom title to display in the alert bar instead of the post title', 'scope-nine-alert-bar' ); ?></small>
			</div>
			<div class="scopenine-meta-control">
				<label class="scopenine-label" for="scopenine-expiry"><?php esc_html_e( 'Set Expiry Date', 'scope-nine-alert-bar' ); ?></label>
				<label class="scopenine-checkbox">
					<input name="scopenine_expiry" id="scopenine-expiry" type="checkbox" <?php checked( $scopenine_is_expiry, 'on' ); ?>/>
					<span class="slider round"></span>
				</label>
				<small class="scopenine-small-description"><?php esc_html_e( 'Enable to add an expiry date', 'scope-nine-alert-bar' ); ?></small>
			</div>
			<div class="scopenine-meta-control <?php echo 'on' !== $scopenine_is_expiry ? 'scopenine-hide' : ''; ?>">
				<label class="scopenine-label" for="scopenine-expiry-date"><?php esc_html_e( 'Expiry Date & Time', 'scope-nine-alert-bar' ); ?></label>
				<input class="scopenine-meta-field" name="scopenine_expiry_date" id="scopenine-expiry-date" type="date" value="<?php echo esc_attr( $scopenine_expiry['expiry_date'] ?? '' ); ?>"/>
				<input class="scopenine-meta-field" name="scopenine_expiry_time" id="scopenine-expiry-time" type="time" value="<?php echo esc_attr( $scopenine_expiry['expiry_time'] ?? '' ); ?>"/>
				<small class="scopenine-small-description"><?php esc_html_e( 'Set Expiry Date & Time', 'scope-nine-alert-bar' ); ?></small>
			</div>
		</div>
		<?php
	}

	/**
	 * Save Meta Fields data on post save
	 *
	 * Meta Keys
	 * - scopenine-custom-title
	 * - scopenine-is-expiry
	 * - scopenine-expiry-date-time
	 *
	 * @param int $post_id Post ID.
	 *
	 * @since 1.0.0
	 */
	public function save_meta_data( $post_id ) {

		// Return if we're doing an auto save.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Nonce Verification.
		if ( ! isset( $_POST['scopenine_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['scopenine_meta_nonce'] ) ), 'scopenine_meta_nonce' ) ) {
			return;
		}

		// Only site admins who can edit this post may change the alert bar post.
		if ( wp_is_post_revision( $post_id ) || ! current_user_can( 'manage_options' ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$is_alert_bar = isset( $_POST['scopenine_is_alert_bar'] ) ? sanitize_text_field( wp_unslash( $_POST['scopenine_is_alert_bar'] ) ) : '';

		if ( 'on' !== $is_alert_bar ) {
			// Remove this post from the alert bar, but only if it is the featured post.
			$scopenine_settings = get_option( 'scopenine_settings' );
			if ( is_array( $scopenine_settings ) && isset( $scopenine_settings['post_id'] ) && (int) $scopenine_settings['post_id'] === (int) $post_id ) {
				unset( $scopenine_settings['post_id'] );
				update_option( 'scopenine_settings', $scopenine_settings );
			}
			return;
		}

		$scopenine_custom_title     = isset( $_POST['scopenine_custom_title'] ) ? sanitize_text_field( wp_unslash( $_POST['scopenine_custom_title'] ) ) : '';
		$scopenine_expiry           = isset( $_POST['scopenine_expiry'] ) ? sanitize_text_field( wp_unslash( $_POST['scopenine_expiry'] ) ) : '';
		$scopenine_expiry_date_time = ( isset( $_POST['scopenine_expiry_date'] ) ? sanitize_text_field( wp_unslash( $_POST['scopenine_expiry_date'] ) ) : '' ) .
		                        ' ' .
		                        ( isset( $_POST['scopenine_expiry_time'] ) ? sanitize_text_field( wp_unslash( $_POST['scopenine_expiry_time'] ) ) : '' );

		// Set this post as the alert bar post.
		$scopenine_settings = get_option( 'scopenine_settings' );
		if ( ! is_array( $scopenine_settings ) ) {
			$scopenine_settings = array();
		}
		$scopenine_settings['post_id'] = $post_id;
		update_option( 'scopenine_settings', $scopenine_settings );

		update_post_meta( $post_id, 'scopenine-custom-title', $scopenine_custom_title );
		update_post_meta( $post_id, 'scopenine-is-expiry', $scopenine_expiry );
		update_post_meta( $post_id, 'scopenine-expiry-date-time', strtotime( $scopenine_expiry_date_time ) );
	}

	/**
	 * Returns Alert Bar Expiry Date and time
	 *
	 * Gets UNIX timestamp from post meta and returns
	 * the expiry date-time in an associative array
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function scopenine_date_time( $post_id ): array {
		$expiry_date_time = get_post_meta( $post_id, 'scopenine-expiry-date-time', true );
		$expiry           = array();
		if ( '' !== $expiry_date_time ) {
			$expiry = explode( ' ', gmdate( 'Y-m-d H:i', $expiry_date_time ) );
			$expiry = array_combine( array( 'expiry_date', 'expiry_time' ), $expiry );
		}

		if ( ! empty( $expiry ) ) {
			return $expiry;
		}

		return array();
	}
}
