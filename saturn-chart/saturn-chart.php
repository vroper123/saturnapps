<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              bsquadt.com
 * @since             1.0.0
 * @package           Saturn_Chart
 *
 * @wordpress-plugin
 * Plugin Name:       Saturn Chart CSV Reader
 * Plugin URI:        bsquadt.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Vincent Roper
 * Author URI:        bsquadt.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       saturn-chart
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-saturn-chart-activator.php
 */
function activate_saturn_chart() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-saturn-chart-activator.php';
	Saturn_Chart_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-saturn-chart-deactivator.php
 */
function deactivate_saturn_chart() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-saturn-chart-deactivator.php';
	Saturn_Chart_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_saturn_chart' );
register_deactivation_hook( __FILE__, 'deactivate_saturn_chart' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-saturn-chart.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_saturn_chart() {

	$plugin = new Saturn_Chart();
	$plugin->run();

}
run_saturn_chart();
