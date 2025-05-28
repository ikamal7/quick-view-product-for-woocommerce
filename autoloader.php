<?php
/**
 * Autoloader for QuickLook for WooCommerce
 *
 * @package QuickLookForWooCommerce
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Autoload classes
 *
 * @param string $class The fully-qualified class name.
 * @return void
 */
spl_autoload_register( function( $class ) {
	// Project-specific namespace prefix.
	$prefix = 'QuickLookForWooCommerce\\';

	// Does the class use the namespace prefix?
	$len = strlen( $prefix );
	if ( strncmp( $prefix, $class, $len ) !== 0 ) {
		return;
	}

	// Get the relative class name.
	$relative_class = substr( $class, $len );

	// Replace namespace separators with directory separators.
	$file = str_replace( '\\', '/', $relative_class ) . '.php';

	// Get the full path to the file.
	$path = QUICKLOOK_WC_PLUGIN_DIR . 'includes/' . $file;

	// If the file exists, require it.
	if ( file_exists( $path ) ) {
		require_once $path;
	}
} );