<?php
/**
 * Settings class for Quick View Product for WooCommerce
 *
 * @package Quick_View_Product_For_WooCommerce
 */

namespace QVPWC;

/**
 * Class to manage plugin settings
 */
class Settings implements Settings_Interface {
	/**
	 * Settings option key
	 *
	 * @var string
	 */
	private $option_key = 'qvpwc_settings';

	/**
	 * Default settings
	 *
	 * @var array
	 */
	private $defaults = array(
		'enabled'        => 'yes',
		'button_text'    => 'Quick View',
		'button_position' => 'after_add_to_cart',
	);

	/**
	 * Constructor
	 */
	public function __construct() {
		// Add settings tab to WooCommerce settings
		add_filter( 'woocommerce_get_sections_products', array( $this, 'add_section' ) );
		add_filter( 'woocommerce_get_settings_products', array( $this, 'add_settings' ), 10, 2 );
	}

	/**
	 * Add new section to WooCommerce settings
	 *
	 * @param array $sections WooCommerce sections.
	 * @return array Modified sections.
	 */
	public function add_section( $sections ) {
		$sections['qvpwc'] = __( 'Quick View', 'quick-view-product-for-woocommerce' );
		return $sections;
	}

	/**
	 * Add settings to the Quick View section
	 *
	 * @param array  $settings WooCommerce settings.
	 * @param string $current_section Current section being displayed.
	 * @return array Modified settings.
	 */
	public function add_settings( $settings, $current_section ) {
		// Check if we're in our section
		if ( 'qvpwc' !== $current_section ) {
			return $settings;
		}

		$quick_view_settings = array(
			array(
				'title' => __( 'Quick View Settings', 'quick-view-product-for-woocommerce' ),
				'type'  => 'title',
				'desc'  => __( 'Configure the Quick View button and modal display.', 'quick-view-product-for-woocommerce' ),
				'id'    => 'qvpwc_options',
			),
			array(
				'title'   => __( 'Enable/Disable', 'quick-view-product-for-woocommerce' ),
				'desc'    => __( 'Enable Quick View functionality', 'quick-view-product-for-woocommerce' ),
				'id'      => 'qvpwc_enabled',
				'default' => 'yes',
				'type'    => 'checkbox',
			),
			array(
				'title'    => __( 'Button Text', 'quick-view-product-for-woocommerce' ),
				'desc'     => __( 'Text displayed on the Quick View button', 'quick-view-product-for-woocommerce' ),
				'id'       => 'qvpwc_button_text',
				'default'  => 'Quick View',
				'type'     => 'text',
				'desc_tip' => true,
			),
			array(
				'title'    => __( 'Button Position', 'quick-view-product-for-woocommerce' ),
				'desc'     => __( 'Where to display the Quick View button', 'quick-view-product-for-woocommerce' ),
				'id'       => 'qvpwc_button_position',
				'default'  => 'after_add_to_cart',
				'type'     => 'select',
				'options'  => array(
					'after_title'       => __( 'After product title', 'quick-view-product-for-woocommerce' ),
					'after_price'       => __( 'After product price', 'quick-view-product-for-woocommerce' ),
					'after_add_to_cart' => __( 'After add to cart button', 'quick-view-product-for-woocommerce' ),
					'before_add_to_cart' => __( 'Before add to cart button', 'quick-view-product-for-woocommerce' ),
				),
				'desc_tip' => true,
			),
			array(
				'type' => 'sectionend',
				'id'   => 'qvpwc_options',
			),
		);

		return $quick_view_settings;
	}

	/**
	 * Register settings with WooCommerce
	 *
	 * @return void
	 */
	public function register_settings() {
		// Settings are registered via WooCommerce settings API
		// This method is required by the interface but implementation is handled in add_settings()
	}

	/**
	 * Get a specific setting value
	 *
	 * @param string $key     The setting key to retrieve.
	 * @param mixed  $default Default value if setting doesn't exist.
	 * @return mixed The setting value
	 */
	public function get_setting( $key, $default = null ) {
		$option_key = 'qvpwc_' . $key;
		$value = get_option( $option_key, null );

		// If the option doesn't exist, use default
		if ( null === $value ) {
			// Check if we have a default in our defaults array
			if ( isset( $this->defaults[ $key ] ) ) {
				$default = $this->defaults[ $key ];
			}

			// Allow filtering of default values
			return apply_filters( 'qvpwc_default_' . $key, $default );
		}

		return $value;
	}

	/**
	 * Get all plugin settings
	 *
	 * @return array All settings
	 */
	public function get_all_settings() {
		$settings = array();

		// Get each setting
		foreach ( array_keys( $this->defaults ) as $key ) {
			$settings[ $key ] = $this->get_setting( $key );
		}

		return $settings;
	}

	/**
	 * Check if quick view is enabled
	 *
	 * @return bool True if enabled, false otherwise
	 */
	public function is_enabled() {
		return 'yes' === $this->get_setting( 'enabled' );
	}

	/**
	 * Get button text setting
	 *
	 * @return string Button text
	 */
	public function get_button_text() {
		return $this->get_setting( 'button_text' );
	}

	/**
	 * Get button position setting
	 *
	 * @return string Button position
	 */
	public function get_button_position() {
		return $this->get_setting( 'button_position' );
	}
}