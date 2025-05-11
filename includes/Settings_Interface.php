<?php
/**
 * Settings Interface for Quick View Product for WooCommerce
 *
 * @package QuickLook
 */

namespace QuickLook;

/**
 * Interface for settings management
 */
interface Settings_Interface {
	/**
	 * Register settings with WooCommerce
	 *
	 * @return void
	 */
	public function register_settings();

	/**
	 * Get a specific setting value
	 *
	 * @param string $key     The setting key to retrieve.
	 * @param mixed  $default Default value if setting doesn't exist.
	 * @return mixed The setting value
	 */
	public function get_setting( $key, $default = null );

	/**
	 * Get all plugin settings
	 *
	 * @return array All settings
	 */
	public function get_all_settings();

	/**
	 * Check if quick view is enabled
	 *
	 * @return bool True if enabled, false otherwise
	 */
	public function is_enabled();

	/**
	 * Get button text setting
	 *
	 * @return string Button text
	 */
	public function get_button_text();

	/**
	 * Get button position setting
	 *
	 * @return string Button position
	 */
	public function get_button_position();
}