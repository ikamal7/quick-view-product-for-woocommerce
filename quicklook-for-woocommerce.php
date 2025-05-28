<?php
/**
 * Plugin Name: QuickLook for WooCommerce
 * Plugin URI: https://kamalhosen.com/plugins/quicklook-for-woocommerce
 * Description: A lightweight plugin adding a Quick View button to WooCommerce product listings, showing a popup with product details.
 * Version: 1.0.1
 * Author: Kamal Hosen
 * Author URI: https://kamalhosen.com
 * Text Domain: quicklook-for-woocommerce
 * Domain Path: /languages
 * Requires at least: 5.4
 * Requires PHP: 7.4
 * WC requires at least: 5.0
 * WC tested up to: 9.8.3
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Require Plugins: woocommerce
 *
 * @package QuickLookForWooCommerce
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// Define plugin constants.
define( 'QUICKLOOK_WC_VERSION', '1.0.1' );
define( 'QUICKLOOK_WC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'QUICKLOOK_WC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'QUICKLOOK_WC_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Include the autoloader
 */
require_once QUICKLOOK_WC_PLUGIN_DIR . 'autoloader.php';

/**
 * Initialize the plugin
 */
function quicklook_wc_init() {
	// Initialize plugin components with dependency injection.
	$settings = new QuickLookForWooCommerce\Settings();
	$ajax_handler = new QuickLookForWooCommerce\Ajax_Handler( $settings );
	$assets = new QuickLookForWooCommerce\Assets( $settings );
	$frontend = new QuickLookForWooCommerce\Frontend( $settings, $ajax_handler );

	// Initialize the main plugin class with all dependencies.
	$plugin = new QuickLookForWooCommerce\Plugin( $settings, $frontend, $ajax_handler, $assets );
	$plugin->init();
}

// Hook into WordPress init to start the plugin.
add_action( 'plugins_loaded', 'quicklook_wc_init' );