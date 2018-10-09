<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}
/**
 *
 * @link              https://addonspress.com/
 * @since             1.0.0
 * @package           Wp_Demo_Export
 *
 * @wordpress-plugin
 * Plugin Name:       WP Demo Export
 * Plugin URI:        https://addonspress.com/item/wp-demo-export
 * Description:       Advance Export Demo Data.
 * Version:           1.0.0
 * Author:            AddonsPress
 * Author URI:        https://addonspress.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-demo-export
 * Domain Path:       /languages
 */

/*Define Constants for this plugin*/
define( 'WP_DEMO_EXPORT_VERSION', '1.0.0' );
define( 'WP_DEMO_EXPORT_PATH', plugin_dir_path( __FILE__ ) );
define( 'WP_DEMO_EXPORT_URL', plugin_dir_url( __FILE__ ) );

$upload_dir = wp_upload_dir();
$wp_demo_export_temp =  $upload_dir['basedir'] . '/wp-demo-export-temp/';
$wp_demo_export_temp_uploads =  $wp_demo_export_temp . '/uploads/';

define( 'WP_DEMO_EXPORT_TEMP', $wp_demo_export_temp );
define( 'WP_DEMO_EXPORT_TEMP_UPLOADS', $wp_demo_export_temp_uploads );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-demo-export-activator.php
 */
function activate_wp_demo_export() {
	require_once WP_DEMO_EXPORT_PATH . 'includes/class-wp-demo-export-activator.php';
	Wp_Demo_Export_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-demo-export-deactivator.php
 */
function deactivate_wp_demo_export() {
	require_once WP_DEMO_EXPORT_PATH . 'includes/class-wp-demo-export-deactivator.php';
	Wp_Demo_Export_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_demo_export' );
register_deactivation_hook( __FILE__, 'deactivate_wp_demo_export' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require WP_DEMO_EXPORT_PATH . 'includes/class-wp-demo-export.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function wp_demo_export() {
	return Wp_Demo_Export::instance();
}
wp_demo_export();