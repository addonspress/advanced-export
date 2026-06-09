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

 * @link       https://addonspress.com/
 * @since      1.0.0
 *
 * @package    Advanced_Export
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Clean up temp directories and files.
$advanced_export_upload_dir = wp_upload_dir();
$advanced_export_temp_dir   = $advanced_export_upload_dir['basedir'] . '/advanced-export-temp/';

WP_Filesystem();
global $wp_filesystem;

if ( $wp_filesystem && $wp_filesystem->exists( $advanced_export_temp_dir ) ) {
	$wp_filesystem->rmdir( $advanced_export_temp_dir, true );
}
