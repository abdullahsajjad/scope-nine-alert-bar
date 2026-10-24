<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/abdullahsajjad
 * @since             1.0.0
 * @package           Scope_Nine_Alert_Bar
 *
 * @wordpress-plugin
 * Plugin Name:       Scope Nine Alert Bar
 * Plugin URI:        https://github.com/abdullahsajjad/scope-nine-alert-bar
 * Description:       A lightweight, fast, and beautifully designed alert bar plugin for WordPress. Display customizable notification banners with live preview, custom colors, and responsive design.
 * Version:           1.0.1
 * Requires at least: 5.0
 * Requires PHP:      7.1
 * Author:            Abdullah Sajjad
 * Author URI:        https://github.com/abdullahsajjad
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       scope-nine-alert-bar
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
defined( 'ABSPATH' ) || die();

/**
 * Currently plugin version.
 */
define( 'SCOPENINE_VERSION', '1.0.1' );

/**
 * Plugin base path.
 */
define( 'SCOPENINE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Plugin base URL.
 */
define( 'SCOPENINE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-scopenine-activator.php
 */
function scopenine_activate() {
	require_once SCOPENINE_PLUGIN_PATH . 'includes/class-scopenine-activator.php';
	SCOPENINE_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-scopenine-deactivator.php
 */
function scopenine_deactivate() {
	require_once SCOPENINE_PLUGIN_PATH . 'includes/class-scopenine-deactivator.php';
	SCOPENINE_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'scopenine_activate' );
register_deactivation_hook( __FILE__, 'scopenine_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require SCOPENINE_PLUGIN_PATH . 'includes/class-scopenine-core.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function scopenine_run() {
	$plugin = new SCOPENINE_Core();
	$plugin->run();
}
scopenine_run();
