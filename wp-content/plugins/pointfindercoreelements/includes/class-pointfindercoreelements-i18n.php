<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://themeforest.net/user/webbu
 * @since      1.0.0
 *
 * @package    Pointfindercoreelements
 * @subpackage Pointfindercoreelements/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Pointfindercoreelements
 * @subpackage Pointfindercoreelements/includes
 * @author     Webbu <info@webbudesign.com>
 */
class Pointfindercoreelements_i18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'pointfindercoreelements',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages'
		);

	}

}
