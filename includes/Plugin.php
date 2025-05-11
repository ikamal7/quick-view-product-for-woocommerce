<?php
/**
 * Main Plugin class for Quick View Product for WooCommerce
 *
 * @package QuickLook
 */

namespace QuickLook;

/**
 * Main plugin class to bootstrap the plugin
 */
class Plugin {
	/**
	 * Settings instance
	 *
	 * @var Settings_Interface
	 */
	private $settings;

	/**
	 * Frontend instance
	 *
	 * @var Frontend
	 */
	private $frontend;

	/**
	 * Ajax handler instance
	 *
	 * @var Ajax_Interface
	 */
	private $ajax_handler;

	/**
	 * Assets instance
	 *
	 * @var Assets
	 */
	private $assets;

	/**
	 * Constructor
	 *
	 * @param Settings_Interface $settings     Settings instance.
	 * @param Frontend          $frontend     Frontend instance.
	 * @param Ajax_Interface    $ajax_handler Ajax handler instance.
	 * @param Assets            $assets       Assets instance.
	 */
	public function __construct( Settings_Interface $settings, Frontend $frontend, Ajax_Interface $ajax_handler, Assets $assets ) {
		$this->settings     = $settings;
		$this->frontend    = $frontend;
		$this->ajax_handler = $ajax_handler;
		$this->assets      = $assets;
	}

	/**
	 * Initialize the plugin
	 *
	 * @return void
	 */
	public function init() {
		// Register settings.
		add_action( 'admin_init', array( $this->settings, 'register_settings' ) );

		// Initialize frontend if quick view is enabled.
		if ( $this->settings->is_enabled() ) {
			// Initialize frontend components.
			$this->frontend->init();

			// Register AJAX hooks.
			$this->ajax_handler->register_ajax_hooks();

			// Enqueue assets.
			$this->assets->init();
		}

		// Add settings link to plugins page.
		add_filter( 'plugin_action_links_' . QL_PLUGIN_BASENAME, array( $this, 'add_settings_link' ) );
	}

	/**
	 * Add settings link to plugin actions
	 *
	 * @param array $links Plugin action links.
	 * @return array Modified plugin action links.
	 */
	public function add_settings_link( $links ) {
		$settings_link = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=products&section=qvpwc' ) . '">' . __( 'Settings', 'quicklook' ) . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}
}