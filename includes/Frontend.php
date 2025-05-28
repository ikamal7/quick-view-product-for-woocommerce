<?php
/**
 * Frontend class for QuickLook for WooCommerce
 *
 * @package QuickLookForWooCommerce
 */

namespace QuickLookForWooCommerce;

/**
 * Class to handle frontend display
 */
class Frontend {
	/**
	 * Settings instance
	 *
	 * @var Settings_Interface
	 */
	private $settings;

	/**
	 * Ajax handler instance
	 *
	 * @var Ajax_Interface
	 */
	private $ajax_handler;

	/**
	 * Constructor
	 *
	 * @param Settings_Interface $settings     Settings instance.
	 * @param Ajax_Interface     $ajax_handler Ajax handler instance.
	 */
	public function __construct( Settings_Interface $settings, Ajax_Interface $ajax_handler ) {
		$this->settings     = $settings;
		$this->ajax_handler = $ajax_handler;
	}

	/**
	 * Initialize frontend hooks
	 *
	 * @return void
	 */
	public function init() {
		// Add quick view button based on position setting
		$position = $this->settings->get_button_position();
		
		switch ( $position ) {
			case 'after_title':
				add_action( 'woocommerce_shop_loop_item_title', array( $this, 'add_quick_view_button' ), 15 );
				break;
			case 'after_price':
				add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'add_quick_view_button' ), 15 );
				break;
			case 'before_add_to_cart':
				add_action( 'woocommerce_after_shop_loop_item', array( $this, 'add_quick_view_button' ), 5 );
				break;
			case 'after_add_to_cart':
			default:
				add_action( 'woocommerce_after_shop_loop_item', array( $this, 'add_quick_view_button' ), 15 );
				break;
		}

		// Add modal container to footer
		add_action( 'wp_footer', array( $this, 'add_modal_container' ) );
	}

	/**
	 * Add quick view button to product
	 *
	 * @return void
	 */
	public function add_quick_view_button() {
		global $product;

		// Get button text from settings
		$button_text = $this->settings->get_button_text();
		
		// Get product ID
		$product_id = $product->get_id();
		
		// Create nonce for security
		$nonce = wp_create_nonce( 'quicklook_wc_nonce' );
		
		// Output button HTML with accessibility attributes
		/* translators: %s: Product name */
		echo '<a href="#" class="quicklook-wc-quick-view-button button" data-product-id="' . esc_attr( $product_id ) . '" data-nonce="' . esc_attr( $nonce ) . '" aria-label="' . sprintf( esc_attr__( 'Quick view for %s', 'quicklook-for-woocommerce' ), esc_attr( $product->get_name() ) ) . '">' . esc_html( $button_text ) . '</a>';
	}

	/**
	 * Add modal container to footer
	 *
	 * @return void
	 */
	public function add_modal_container() {
		// Add on shop/archive pages and any page that might have the [products] shortcode
		global $post;
		$has_shortcode = false;
		
		if ( is_a( $post, 'WP_Post' ) ) {
			$has_shortcode = has_shortcode( $post->post_content, 'products' );
		}
		
		if ( ! is_shop() && ! is_product_category() && ! is_product_tag() && ! $has_shortcode ) {
			return;
		}
		
		// Modal container HTML with accessibility attributes
		?>
		<div id="quicklook-wc-modal" class="quicklook-wc-modal" aria-hidden="true" role="dialog" aria-labelledby="quicklook-wc-modal-title" tabindex="-1">
			<div class="quicklook-wc-modal-overlay"></div>
			<div class="quicklook-wc-modal-wrapper">
				<div class="quicklook-wc-modal-container">
					<!-- Content will be loaded via AJAX -->
					<div class="quicklook-wc-modal-loading">
						<div class="quicklook-wc-spinner"></div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}