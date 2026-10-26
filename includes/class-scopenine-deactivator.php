<?php

/**
 * Fired during plugin deactivation
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
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Scope_Nine_Alert_Bar
 * @subpackage Scope_Nine_Alert_Bar/includes
 * @author     Abdullah Sajjad <abdullahsajjad33@gmail.com>
 */
class SCOPENINE_Deactivator {

	/**
	 * Plugin deactivation handler.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		// Nothing to do on deactivation currently.
	}
}
