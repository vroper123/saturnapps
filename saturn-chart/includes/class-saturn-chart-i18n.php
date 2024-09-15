<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       bsquadt.com
 * @since      1.0.0
 *
 * @package    Saturn_Chart
 * @subpackage Saturn_Chart/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Saturn_Chart
 * @subpackage Saturn_Chart/includes
 * @author     Vincent Roper <vincent_roper@yahoo.com>
 */
class Saturn_Chart_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'saturn-chart',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
