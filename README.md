# QuickLook - Quick View Product For WooCommerce

[![WordPress Plugin Version](https://img.shields.io/wordpress/plugin/v/quick-view-product-for-woocommerce.svg)](https://wordpress.org/plugins/quicklook/)
[![WordPress Compatibility](https://img.shields.io/wordpress/v/quick-view-product-for-woocommerce.svg)](https://wordpress.org/plugins/quicklook/)
[![WooCommerce Compatibility](https://img.shields.io/badge/WooCommerce-9.0+-purple.svg)](https://wordpress.org/plugins/quicklook/)

A lightweight plugin adding a Quick View button to WooCommerce product listings, showing a popup with product details.

## Description

QuickLook enhances your online store by allowing customers to quickly preview product details without leaving the shop or category page. This improves the shopping experience and can lead to increased conversions.

### Features

* Adds a Quick View button to product listings
* Displays product image, title, price, description, and add-to-cart functionality in a modal popup
* Supports both simple and variable products
* Fully responsive design works on all devices
* Customizable button text and position
* Accessibility-ready with keyboard navigation and ARIA attributes
* Lightweight and optimized for performance
* Compatible with popular WordPress themes

## Installation

### From WordPress.org

1. Visit Plugins > Add New
2. Search for "QuickLook - Quick View Product For WooCommerce"
3. Install and activate the plugin
4. Go to WooCommerce > Settings > Products > Quick View to configure the plugin

### Manual Installation

1. Upload the `quick-view-product-for-woocommerce` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to WooCommerce > Settings > Products > Quick View to configure the plugin

## Frequently Asked Questions

### Does this work with variable products?

Yes, the plugin fully supports variable products, including the variation selection interface.

### Can I customize the button position?

Yes, you can choose to display the Quick View button after the product title, after the price, before the add-to-cart button, or after the add-to-cart button.

### Is the plugin responsive?

Yes, the Quick View modal is fully responsive and works well on mobile, tablet, and desktop devices.

### The modal is not showing, what should I check?

1. Make sure WooCommerce is active
2. Check if there are any JavaScript errors in your browser console
3. Verify that your theme is not removing WooCommerce default hooks
4. Try disabling other plugins to check for conflicts

### Can I customize the styling of the Quick View button and modal?

Yes, you can add custom CSS to your theme to override the default styles.

## Development

### Prerequisites

* WordPress 6.7+
* WooCommerce 9.0+
* PHP 7.4+

### Plugin Structure

```
quick-view-product-for-woocommerce/
├── assets/
│   ├── css/
│   │   └── quick-view.css
│   └── js/
│       └── quick-view.js
├── includes/
│   ├── Ajax_Handler.php
│   ├── Ajax_Interface.php
│   ├── Assets.php
│   ├── Frontend.php
│   ├── Plugin.php
│   ├── Settings.php
│   └── Settings_Interface.php
├── languages/
│   └── quicklook.pot
├── .distignore
├── .gitattributes
├── .gitignore
├── autoloader.php
├── quicklook.php
├── README.md
└── readme.txt
```

### Development Guidelines

1. **Coding Standards**: Follow WordPress Coding Standards for PHP, CSS, and JavaScript.

2. **Object-Oriented Approach**: The plugin is built using OOP principles with interfaces for better maintainability.

3. **Autoloading**: The plugin uses a custom autoloader for loading PHP classes.

4. **Hooks and Filters**: Use WordPress hooks and filters to make the plugin extensible.

5. **Localization**: All strings should be properly localized using the 'quicklook' text domain.

6. **Testing**: Test the plugin with different themes and WooCommerce versions.

7. **Performance**: Keep performance in mind, especially for frontend scripts and styles.

### Building and Deployment

1. Make your changes to the codebase
2. Update version numbers in:
   - quicklook.php
   - readme.txt
3. Update the changelog in readme.txt
4. Test thoroughly on a development site
5. Commit changes to the repository
6. Create a new release tag

## License

This plugin is licensed under the GPL v2 or later.

## Credits

* Developed by [Kamal Hosen](https://kamalhosen.com)

## Changelog

### 1.0.0
* Initial release