<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/DevWael
 * @since             1.0.0
 * @package           Wcme
 *
 * @wordpress-plugin
 * Plugin Name:       WC Marketing Emails
 * Plugin URI:        https://github.com/DevWael/WC-Marketing-Emails
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Ahmad Wael
 * Author URI:        https://github.com/DevWael
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wcme
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WCME_VERSION', '1.0.0' );
//define( 'WCME_ASSETS_URL', plugin_dir_url( __FILE__ ) . 'assets' );
define( 'WCME_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Classes autoloader
 */
spl_autoload_register( 'wcme_autoloader' );
function wcme_autoloader( $class_name ) {
	$classes_dir = WCME_DIR . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR;
	$class_file  = str_replace( 'WCME', '', $class_name ) . '.php';
	$class       = $classes_dir . str_replace( '\\', '/', $class_file );
	if ( file_exists( $class ) ) {
		require_once $class;
	}

	return false;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wcme-activator.php
 */
function activate_wcme() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wcme-activator.php';
	Wcme_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wcme-deactivator.php
 */
function deactivate_wcme() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wcme-deactivator.php';
	Wcme_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wcme' );
register_deactivation_hook( __FILE__, 'deactivate_wcme' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wcme.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wcme() {

	$plugin = new Wcme();
	$plugin->run();

}

run_wcme();

/**
 * Plugin Update Service
 */
require WCME_DIR . 'plugin-update-checker-4.9/plugin-update-checker.php';
add_action( 'plugins_loaded', function () {
	Puc_v4_Factory::buildUpdateChecker(
		'https://github.com/DevWael/WC-Marketing-Emails',
		__FILE__,
		'WC-Marketing-Emails',
		24
	);
} );
