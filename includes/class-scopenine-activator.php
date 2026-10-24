<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/abdullahsajjad
 * @since      1.0.0
 *
 * @package    Scope_Nine_Alert_Bar
 * @subpackage Scope_Nine_Alert_Bar/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Scope_Nine_Alert_Bar
 * @subpackage Scope_Nine_Alert_Bar/includes
 * @author     Abdullah Sajjad <abdullahsajjad33@gmail.com>
 */
class SCOPENINE_Activator {

	/**
	 * Runs When Plugin is activated
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		self::set_default_options();
	}

	/**
	 * Sets Default options for the plugin
	 *
	 * @since 1.0.0
	 */
	protected static function set_default_options() {
		$scopenine_settings = get_option( 'scopenine_settings' );

		$scopenine_defaults = [
			'enabled'          => '1',
			'news_title'       => '',
			'background_color' => '#fb0404',
			'text_color'       => '#ffffff',
			'scopenine_position'     => 'after-header',
			'font_size'        => '14',
			'padding_top'      => '10',
			'padding_right'    => '40',
			'padding_bottom'   => '10',
			'padding_left'     => '40',
			'close_icon_url'   => '',
			'display_mode'     => 'site_wide',
		];

		self::scopenine_save_default_options( $scopenine_settings, $scopenine_defaults, 'scopenine_settings' );
	}

	/**
	 * Save default options in options
	 *
	 * checks for the existing options if not set then
	 * add the default value's in options table
	 *
	 * @param array  $scopenine_settings      db options array.
	 * @param array  $default_settings  default options array.
	 * @param string $option            option name.
	 *
	 * @since 1.0.0
	 */
	protected static function scopenine_save_default_options( $scopenine_settings, $default_settings, $option ) {
		if ( false === $scopenine_settings ) {
			$scopenine_settings = $default_settings;
		} else {
			foreach ( $default_settings as $key => $value ) {
				if ( ! isset( $scopenine_settings[ $key ] ) ) {
					$scopenine_settings[ $key ] = $value;
				}
			}
		}

		update_option( $option, $scopenine_settings );
	}
}
