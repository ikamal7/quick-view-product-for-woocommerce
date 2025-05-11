<?php
/**
 * Plugin Name: QuickLook
 * Plugin URI: https://kamalhosen.com/plugins/quicklook
 * Description: A lightweight plugin adding a Quick View button to WooCommerce product listings, showing a popup with product details.
 * Version: 1.0.0
 * Author: Kamal Hosen
 * Author URI: https://kamalhosen.com
 * Text Domain: quicklook
 * Domain Path: /languages
 * Requires at least: 6.7
 * Requires PHP: 7.4
 * WC requires at least: 5.0
 * WC tested up to: 9.8.3
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Require Plugins: woocommerce
 *
 * @package QuickLook
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// Define plugin constants.
define( 'QL_VERSION', '1.0.0' );
define( 'QL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'QL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'QL_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Include the autoloader
 */
require_once QL_PLUGIN_DIR . 'autoloader.php';

/**
 * Initialize the plugin
 */
function ql_init() {
	// Load text domain for translations.
	load_plugin_textdomain( 'quicklook', false, dirname( QL_PLUGIN_BASENAME ) . '/languages' );

	// Initialize plugin components with dependency injection.
	$settings = new QuickLook\Settings();
	$ajax_handler = new QuickLook\Ajax_Handler( $settings );
	$assets = new QuickLook\Assets( $settings );
	$frontend = new QuickLook\Frontend( $settings, $ajax_handler );

	// Initialize the main plugin class with all dependencies.
	$plugin = new QuickLook\Plugin( $settings, $frontend, $ajax_handler, $assets );
	$plugin->init();
}

// Hook into WordPress init to start the plugin.
add_action( 'plugins_loaded', 'ql_init' );