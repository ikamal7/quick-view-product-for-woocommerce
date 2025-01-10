<?php
/**
 * Plugin Name: Quick View Product for WooCommerce
 * Plugin URI: https://kamalhosen.com/plugins/quick-view-product-for-woocommerce
 * Description: A lightweight plugin adding a Quick View button to WooCommerce product listings, showing a popup with product details.
 * Version: 1.0.0
 * Author: Kamal Hosen
 * Author URI: https://kamalhosen.com
 * Text Domain: quick-view-product-for-woocommerce
 * Domain Path: /languages
 * Requires at least: 6.7
 * Requires PHP: 7.4
 * WC requires at least: 5.0
 * WC tested up to: 9.8.3
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package Quick_View_Product_For_WooCommerce
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if WooCommerce is active
 */
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
	return;
}

// Define plugin constants.
define( 'QVPWC_VERSION', '1.0.0' );
define( 'QVPWC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'QVPWC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'QVPWC_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Include the autoloader
 */
require_once QVPWC_PLUGIN_DIR . 'autoloader.php';

/**
 * Initialize the plugin
 */
function qvpwc_init() {
	// Load text domain for translations.
	load_plugin_textdomain( 'quick-view-product-for-woocommerce', false, dirname( QVPWC_PLUGIN_BASENAME ) . '/languages' );

	// Initialize plugin components with dependency injection.
	$settings = new QVPWC\Settings();
	$ajax_handler = new QVPWC\Ajax_Handler( $settings );
	$assets = new QVPWC\Assets( $settings );
	$frontend = new QVPWC\Frontend( $settings, $ajax_handler );

	// Initialize the main plugin class with all dependencies.
	$plugin = new QVPWC\Plugin( $settings, $frontend, $ajax_handler, $assets );
	$plugin->init();
}

// Hook into WordPress init to start the plugin.
add_action( 'plugins_loaded', 'qvpwc_init' );