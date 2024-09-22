<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.derethor.net
 * @since             1.0.0
 * @package           Mejorcluster
 *
 * @wordpress-plugin
 * Plugin Name:       El mejor Cluster
 * Plugin URI:        https://github.com/derethor/mejorcluster
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.1.14
 * Author:            Javier Loureiro
 * Author URI:        https://www.derethor.net
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mejorcluster
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'MEJORCLUSTER_VERSION', '1.1.14' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mejorcluster-activator.php
 */
function activate_mejorcluster() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mejorcluster-activator.php';
	Mejorcluster_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mejorcluster-deactivator.php
 */
function deactivate_mejorcluster() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mejorcluster-deactivator.php';
	Mejorcluster_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mejorcluster' );
register_deactivation_hook( __FILE__, 'deactivate_mejorcluster' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mejorcluster.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mejorcluster() {

	$plugin = new Mejorcluster();
	$plugin->run();

}
run_mejorcluster();
