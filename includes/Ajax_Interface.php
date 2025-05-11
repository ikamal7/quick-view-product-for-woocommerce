<?php
/**
 * Ajax Interface for Quick View Product for WooCommerce
 *
 * @package QuickLook
 */

namespace QuickLook;

/**
 * Interface for AJAX operations
 */
interface Ajax_Interface {
	/**
	 * Register AJAX hooks
	 *
	 * @return void
	 */
	public function register_ajax_hooks();

	/**
	 * Handle AJAX request for quick view data
	 *
	 * @return void
	 */
	public function handle_quick_view_request();

	/**
	 * Get product data for quick view
	 *
	 * @param int $product_id The product ID.
	 * @return array Product data for quick view
	 */
	public function get_product_data( $product_id );

	/**
	 * Generate HTML for product modal
	 *
	 * @param \WC_Product $product The product object.
	 * @return string HTML content for modal
	 */
	public function generate_modal_html( $product );
}