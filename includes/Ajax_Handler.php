<?php
/**
 * Ajax Handler class for Quick View Product for WooCommerce
 *
 * @package Quick_View_Product_For_WooCommerce
 */

namespace QVPWC;

/**
 * Class to handle AJAX requests for quick view
 */
class Ajax_Handler implements Ajax_Interface {
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
	 * Register AJAX hooks
	 *
	 * @return void
	 */
	public function register_ajax_hooks() {
		// Register AJAX actions for both logged in and non-logged in users
		add_action( 'wp_ajax_qvpwc_quick_view', array( $this, 'handle_quick_view_request' ) );
		add_action( 'wp_ajax_nopriv_qvpwc_quick_view', array( $this, 'handle_quick_view_request' ) );
	}

	/**
	 * Handle AJAX request for quick view data
	 *
	 * @return void
	 */
	public function handle_quick_view_request() {
		// Check nonce for security
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'qvpwc_nonce' ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed', 'quick-view-product-for-woocommerce' ) ) );
		}

		// Check if product ID is provided
		if ( ! isset( $_POST['product_id'] ) || empty( $_POST['product_id'] ) ) {
			wp_send_json_error( array( 'message' => __( 'Product ID is required', 'quick-view-product-for-woocommerce' ) ) );
		}

		// Get product ID
		$product_id = absint( $_POST['product_id'] );

		// Get product data
		$product_data = $this->get_product_data( $product_id );

		// Send response
		if ( ! empty( $product_data ) ) {
			wp_send_json_success( $product_data );
		} else {
			wp_send_json_error( array( 'message' => __( 'Product not found', 'quick-view-product-for-woocommerce' ) ) );
		}
	}

	/**
	 * Get product data for quick view
	 *
	 * @param int $product_id The product ID.
	 * @return array Product data for quick view
	 */
	public function get_product_data( $product_id ) {
		// Get product
		$product = wc_get_product( $product_id );

		// Check if product exists
		if ( ! $product ) {
			return array();
		}

		// Generate modal HTML
		$modal_html = $this->generate_modal_html( $product );

		// Return product data
		return array(
			'id'        => $product_id,
			'title'     => $product->get_title(),
			'modal_html' => $modal_html,
		);
	}

	/**
	 * Generate HTML for product modal
	 *
	 * @param \WC_Product $product The product object.
	 * @return string HTML content for modal
	 */
	public function generate_modal_html( $product ) {
		// Start output buffering
		ob_start();

		// Get product data
		$product_id    = $product->get_id();
		$product_title = $product->get_title();
		$product_image = $product->get_image_id() ? wp_get_attachment_image_url( $product->get_image_id(), 'woocommerce_single' ) : wc_placeholder_img_src( 'woocommerce_single' );
		$product_price = $product->get_price_html();
		$product_desc  = $product->get_short_description();
		$product_url   = $product->get_permalink();

		// Modal HTML structure
		?>
		<div class="qvpwc-modal-content">
			<div class="qvpwc-modal-close">&times;</div>
			<div class="qvpwc-modal-body">
				<div class="qvpwc-product-image">
					<img src="<?php echo esc_url( $product_image ); ?>" alt="<?php echo esc_attr( $product_title ); ?>" />
				</div>
				<div class="qvpwc-product-details">
					<h2 class="qvpwc-product-title"><?php echo esc_html( $product_title ); ?></h2>
					<div class="qvpwc-product-price"><?php echo wp_kses_post( $product_price ); ?></div>
					
					<?php if ( ! empty( $product_desc ) ) : ?>
					<div class="qvpwc-product-description">
						<?php echo wp_kses_post( $product_desc ); ?>
					</div>
					<?php endif; ?>
					
					<div class="qvpwc-product-actions">
						<?php 
						// Add to cart form
						if ( $product->is_in_stock() ) {
							// For variable products, we need to load the add to cart template
							if ( $product->is_type( 'variable' ) ) {
								// Load the variable product template
								wc_get_template( 'single-product/add-to-cart/variable.php', array(
									'available_variations' => $product->get_available_variations(),
									'attributes'           => $product->get_variation_attributes(),
									'selected_attributes'  => $product->get_default_attributes(),
								) );
							} else {
								// Simple product
								?>
								<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product_url ) ); ?>" method="post" enctype="multipart/form-data">
									<?php 
									// Allow other plugins to add content before quantity input
									do_action( 'woocommerce_before_add_to_cart_button' );
									
									// Quantity input
									woocommerce_quantity_input( array(
										'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
										'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
										'input_value' => $product->get_min_purchase_quantity(),
									) );
									
									// Hidden field for product ID
									?>
									<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product_id ); ?>" />
									<button type="submit" class="single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
									<?php 
									// Allow other plugins to add content after add to cart button
									do_action( 'woocommerce_after_add_to_cart_button' );
									?>
								</form>
								<?php
							}
						} else {
							// Out of stock message
							echo '<p class="qvpwc-out-of-stock">' . esc_html__( 'This product is currently out of stock and unavailable.', 'quick-view-product-for-woocommerce' ) . '</p>';
						}
						?>
						<a href="<?php echo esc_url( $product_url ); ?>" class="qvpwc-view-details button"><?php esc_html_e( 'View Details', 'quick-view-product-for-woocommerce' ); ?></a>
					</div>
				</div>
			</div>
		</div>
		<?php

		// Get buffer contents and clean buffer
		$html = ob_get_clean();

		// Allow filtering of modal HTML
		return apply_filters( 'qvpwc_modal_html', $html, $product );
	}
}