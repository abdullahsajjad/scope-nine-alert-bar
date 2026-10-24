<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * @link       https://github.com/abdullahsajjad
 * @since      1.0.0
 *
 * @package    Scope_Nine_Alert_Bar
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if ( ! function_exists( 'scopenine_delete_all_options' ) ) {

	/**
	 * Deletes plugin data in options table
	 *
	 * @since 1.0.0
	 */
	function scopenine_delete_all_options() {
		$options_arr = array(
			'scopenine_settings',
		);
		foreach ( $options_arr as $option ) {
			delete_option( $option );
		}
	}
}

if ( ! function_exists( 'scopenine_is_data_removal_allowed' ) ) {

	/**
	 * Checks if allowed removing data on plugin uninstall
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	function scopenine_is_data_removal_allowed() {
		$general_options = get_option( 'scopenine_settings' );
		if ( ! empty( $general_options['remove_data'] ) ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'scopenine_uninstall_driver' ) ) {

	/**
	 * Driver Function
	 *
	 * @since 1.0.0
	 */
	function scopenine_uninstall_driver() {
		if ( false === scopenine_is_data_removal_allowed() ) {
			return;
		}

		scopenine_delete_all_options();
	}
}

scopenine_uninstall_driver();
