/**
 * Quick View Product for WooCommerce - Frontend Scripts
 *
 * @package Quick_View_Product_For_WooCommerce
 */

(function($) {
    'use strict';

    /**
     * Quick View functionality
     */
    var QVPWC_QuickView = {
        /**
         * Initialize the quick view functionality
         */
        init: function() {
            // Event listeners
            $(document).on('click', '.qvpwc-quick-view-button', this.openQuickView);
            $(document).on('click', '.qvpwc-modal-close, .qvpwc-modal-overlay', this.closeQuickView);
            $(document).on('keyup', this.handleEscKey);

            // Handle variation form events
            $(document).on('found_variation', 'form.variations_form', this.foundVariation);
            $(document).on('reset_data', 'form.variations_form', this.resetVariation);

            // Handle add to cart in modal
            $(document).on('submit', '.qvpwc-modal form.cart', this.handleAddToCart);
        },

        /**
         * Open quick view modal
         * 
         * @param {Event} e Click event
         */
        openQuickView: function(e) {
            e.preventDefault();

            var $button = $(this);
            var productId = $button.data('product-id');
            var nonce = $button.data('nonce');
            var $modal = $('#qvpwc-modal');

            // Show loading spinner
            $modal.find('.qvpwc-modal-loading').show();
            $modal.find('.qvpwc-modal-content').remove();

            // Show modal
            $modal.fadeIn(300);
            $('body').addClass('qvpwc-modal-open');

            // Trap focus in modal
            QVPWC_QuickView.trapFocus($modal);

            // AJAX request to get product data
            $.ajax({
                url: qvpwc_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'qvpwc_quick_view',
                    product_id: productId,
                    nonce: nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Hide loading spinner
                        $modal.find('.qvpwc-modal-loading').hide();
                        
                        // Append modal content
                        $modal.find('.qvpwc-modal-container').append(response.data.modal_html);
                        
                        // Initialize WooCommerce scripts
                        if (typeof $.fn.wc_variation_form !== 'undefined') {
                            $modal.find('.variations_form').wc_variation_form();
                        }
                        
                        // Set focus to close button
                        setTimeout(function() {
                            $modal.find('.qvpwc-modal-close').focus();
                        }, 100);
                    } else {
                        // Error handling
                        QVPWC_QuickView.closeQuickView();
                        console.error('Error loading product data:', response.data.message);
                    }
                },
                error: function(xhr, status, error) {
                    // Error handling
                    QVPWC_QuickView.closeQuickView();
                    console.error('AJAX error:', error);
                }
            });
        },

        /**
         * Close quick view modal
         * 
         * @param {Event} e Click event (optional)
         */
        closeQuickView: function(e) {
            if (e) {
                e.preventDefault();
            }

            var $modal = $('#qvpwc-modal');
            $modal.fadeOut(200);
            $('body').removeClass('qvpwc-modal-open');

            // Return focus to the last clicked button
            setTimeout(function() {
                $('.qvpwc-quick-view-button[data-product-id="' + $modal.attr('data-product-id') + '"]').focus();
            }, 200);
        },

        /**
         * Handle Escape key press
         * 
         * @param {Event} e Keyup event
         */
        handleEscKey: function(e) {
            if (e.keyCode === 27) { // ESC key
                QVPWC_QuickView.closeQuickView();
            }
        },

        /**
         * Trap focus within modal for accessibility
         * 
         * @param {jQuery} $modal Modal element
         */
        trapFocus: function($modal) {
            // Save current product ID to return focus later
            var productId = $modal.find('.qvpwc-quick-view-button').data('product-id');
            $modal.attr('data-product-id', productId);

            // Find all focusable elements
            var focusableElements = $modal.find('a[href], button, input, select, textarea, [tabindex]:not([tabindex="-1"])');
            var firstFocusable = focusableElements.first();
            var lastFocusable = focusableElements.last();

            // Trap focus
            $modal.on('keydown', function(e) {
                if (e.keyCode !== 9) { // TAB key
                    return;
                }

                if (e.shiftKey) { // Shift + Tab
                    if (document.activeElement === firstFocusable[0]) {
                        e.preventDefault();
                        lastFocusable.focus();
                    }
                } else { // Tab
                    if (document.activeElement === lastFocusable[0]) {
                        e.preventDefault();
                        firstFocusable.focus();
                    }
                }
            });
        },

        /**
         * Handle found variation event
         * 
         * @param {Event} e Event
         * @param {Object} variation Variation data
         */
        foundVariation: function(e, variation) {
            if (variation.image && variation.image.src) {
                var $modalImage = $(this).closest('.qvpwc-modal-content').find('.qvpwc-product-image img');
                var srcset = variation.image.srcset ? variation.image.srcset : '';
                
                $modalImage.attr('src', variation.image.src);
                $modalImage.attr('srcset', srcset);
                $modalImage.attr('alt', variation.image.alt);
            }
        },

        /**
         * Handle reset variation event
         * 
         * @param {Event} e Event
         */
        resetVariation: function(e) {
            var $form = $(this);
            var $modalContent = $form.closest('.qvpwc-modal-content');
            var $modalImage = $modalContent.find('.qvpwc-product-image img');
            var originalImage = $modalImage.attr('data-original-src');
            
            if (!originalImage) {
                // Store original image on first reset
                $modalImage.attr('data-original-src', $modalImage.attr('src'));
            } else {
                // Restore original image
                $modalImage.attr('src', originalImage);
            }
        },

        /**
         * Handle add to cart in modal
         * 
         * @param {Event} e Submit event
         */
        handleAddToCart: function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $modal = $('#qvpwc-modal');
            var $productActions = $form.closest('.qvpwc-product-actions');
            
            // Show loading state
            $form.find('button[type="submit"]').addClass('loading');
            
            // AJAX add to cart
            $.ajax({
                type: 'POST',
                url: woocommerce_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'),
                data: $form.serialize(),
                success: function(response) {
                    // Update cart fragments
                    if (response.fragments) {
                        $.each(response.fragments, function(key, value) {
                            $(key).replaceWith(value);
                        });
                    }
                    
                    // Trigger event so themes can refresh other areas
                    $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash]);
                    
                    // Show success message
                    if ($productActions.find('.woocommerce-message').length === 0) {
                        $productActions.prepend('<div class="woocommerce-message" role="alert">' + qvpwc_params.i18n_added_to_cart + '</div>');
                    }
                    
                    // Add checkout button if it doesn't exist
                    if ($productActions.find('.qvpwc-checkout-button').length === 0) {
                        $productActions.append('<a href="' + qvpwc_params.checkout_url + '" class="qvpwc-checkout-button button alt">Proceed to Checkout</a>');
                    }
                },
                error: function() {
                    // Remove loading state
                    $form.find('button[type="submit"]').removeClass('loading');
                    
                    // Show error message
                    if ($form.find('.woocommerce-error').length === 0) {
                        $form.prepend('<div class="woocommerce-error">' + qvpwc_params.i18n_add_to_cart_error + '</div>');
                    }
                },
                complete: function() {
                    // Remove loading state
                    $form.find('button[type="submit"]').removeClass('loading');
                }
            });
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        QVPWC_QuickView.init();
    });

})(jQuery);