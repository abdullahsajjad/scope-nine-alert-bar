<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/abdullahsajjad
 * @since      1.0.0
 *
 * @package    Scope_Nine_Alert_Bar
 * @subpackage Scope_Nine_Alert_Bar/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Scope_Nine_Alert_Bar
 * @subpackage Scope_Nine_Alert_Bar/includes
 * @author     Abdullah Sajjad <abdullahsajjad33@gmail.com>
 */
class SCOPENINE_Core {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      SCOPENINE_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SCOPENINE_VERSION' ) ) {
			$this->version = SCOPENINE_VERSION;
		} else {
			$this->version = '1.0.1';
		}
		$this->plugin_name = 'scope-nine-alert-bar';

		$this->load_dependencies();
		$this->define_admin();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - SCOPENINE_Loader. Orchestrates the hooks of the plugin.
	 * - SCOPENINE_Admin. Defines all hooks for the admin area.
	 * - SCOPENINE_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once SCOPENINE_PLUGIN_PATH . 'includes/class-scopenine-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once SCOPENINE_PLUGIN_PATH . 'admin/class-scopenine-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once SCOPENINE_PLUGIN_PATH . 'public/class-scopenine-public.php';

		/**
		 * The class responsible for defining all actions that occur in the settings page.
		 */
		require_once SCOPENINE_PLUGIN_PATH . 'admin/includes/class-scopenine-settings.php';

		$this->loader = new SCOPENINE_Loader();
	}


	/**
	 * Define and register all the functionality related to admin area
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 */
	protected function define_admin() {
		$this->define_admin_hooks();
		$this->define_settings_hooks();
	}

	/**
	 * Register all the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 */
	protected function define_admin_hooks() {
		$plugin_admin = new SCOPENINE_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_page' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_custom_meta_box' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_meta_data' );
		$this->loader->add_filter( 'plugin_action_links_' . plugin_basename( SCOPENINE_PLUGIN_PATH . 'scope-nine-alert-bar.php' ), $plugin_admin, 'add_plugin_action_links' );
	}

	/**
	 * Register all the hooks related to the settings page
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 */
	protected function define_settings_hooks() {
		$plugin_settings = new SCOPENINE_Settings();

		$this->loader->add_action( 'admin_init', $plugin_settings, 'scopenine_register_settings' );
		$this->loader->add_action( 'admin_init', $plugin_settings, 'scopenine_register_advanced_settings' );
		$this->loader->add_action( 'scopenine_settings_content', $plugin_settings, 'do_settings_fields' );
		$this->loader->add_action( 'scopenine_advanced_remove_data_field', $plugin_settings, 'scopenine_remove_data_callback' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new SCOPENINE_Public( $this->get_plugin_name(), $this->get_version() );

		// Use wp_body_open for direct HTML output (works with all themes including block themes)
		$this->loader->add_action( 'wp_body_open', $plugin_public, 'display_alert_bar', 1 );

		// Front-end script (dismiss + "after-header" repositioning).
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_assets' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    SCOPENINE_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
