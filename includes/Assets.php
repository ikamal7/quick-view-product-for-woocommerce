<?php
/**
 * Assets class for Quick View Product for WooCommerce
 *
 * @package QuickLook
 */

namespace QuickLook;

/**
 * Class to manage CSS and JS assets
 */
class Assets {
	/**
	 * Settings instance
	 *
	 * @var Settings_Interface
	 */
	private $settings;

	/**
	 * Constructor
	 *
	 * @param Settings_Interface $settings Settings instance.
	 */
	public function __construct( Settings_Interface $settings ) {
		$this->settings = $settings;
	}

	/**
	 * Initialize assets
	 *
	 * @return void
	 */
	public function init() {
		// Enqueue frontend styles and scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
	}

	/**
	 * Enqueue frontend styles and scripts
	 *
	 * @return void
	 */
	public function enqueue_frontend_assets() {
		// Only enqueue on shop/archive pages or when products shortcode is used
		if ( ! is_shop() && ! is_product_category() && ! is_product_tag() && ! is_product() && ! has_shortcode( get_the_content(), 'products' ) ) {
			return;
		}

		// Enqueue CSS
		wp_enqueue_style(
			'qvpwc-styles',
			QuickLook_PLUGIN_URL . 'assets/css/quick-view.css',
			array(),
			QuickLook_VERSION
		);

		// Enqueue JS
		wp_enqueue_script(
			'qvpwc-scripts',
			QuickLook_PLUGIN_URL . 'assets/js/quick-view.js',
			array( 'jquery' ),
			QuickLook_VERSION,
			true
		);

		// Localize script with AJAX URL and nonce
		wp_localize_script(
			'qvpwc-scripts',
			'qvpwc_params',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'qvpwc_nonce' ),
				'checkout_url' => wc_get_checkout_url(),
				'i18n_added_to_cart' => __( 'Product added to cart successfully!', 'quicklook' ),
				'i18n_add_to_cart_error' => __( 'Error adding product to cart. Please try again.', 'quicklook' ),
			)
		);
	}
}