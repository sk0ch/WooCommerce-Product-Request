<?php
/**
 * Plugin Name: WooCommerce Product Request
 * Plugin URI: http://skoch.com.ua/
 * Description: WooCommerce Product Request Plugin – a simple plugin to add a functionality to request a product quote using simple form
 * Author: Webolatory Team
 * Author URI: http://webolatory.com/
 * Text Domain: woocommerce-product-request
 * Version: 0.0.2
 * Domain Path: /languages/
 * License: GPL v3
 */

/**
 * WooCommerce Product Request
 * Copyright (C) 2017, Webolatory - a.skoch@webolatory.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

defined( 'ABSPATH' ) || die;

define( 'WBL_WC_PRODUCT_REQUEST_PLUGIN', __FILE__ );

define( 'WBL_WC_PRODUCT_REQUEST_BASENAME', plugin_basename( WBL_WC_PRODUCT_REQUEST_PLUGIN ) );

define( 'WBL_WC_PRODUCT_REQUEST_DOMAIN', trim( dirname( WBL_WC_PRODUCT_REQUEST_BASENAME ), '/' ) );

define( 'WBL_WC_PRODUCT_REQUEST_DIR', untrailingslashit( dirname( WBL_WC_PRODUCT_REQUEST_PLUGIN ) ) );

define( 'WBL_WC_PRODUCT_REQUEST_URL', plugins_url( WBL_WC_PRODUCT_REQUEST_DOMAIN ) );

/**
 * Init WooCommerce Product Request
 * */
class Wbl_WC_Product_Request {

	public static $settings = array();

	/**
	 * Constructor
	*/
	function __construct() {

		// Include setting page
		include_once( 'classes/class.settings-page.php' );
		include_once( 'classes/class.post-type.php' );
		include_once( 'classes/class.meta-box.php' );

		// Load translate
		add_action( 'plugins_loaded', array( &$this, 'load_translate' ) );

		include_once( 'classes/class.form.php' );

		// WooCommerce check
		if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
			add_action( 'admin_notices', function(){
					?>
					<div class="notice-error notice is-dismissible">
						<p><b>WooCommerce Product Request: </b><?php esc_html_e( 'please activate WooCommerce first to start working!', 'woocommerce-product-request' ); ?></p>
					</div>
					<?php
			} );
		}
	}

	/**
	 * Load translate.
	 *
	 * @return void.
	 */
	public function load_translate() {

		load_plugin_textdomain( 'woocommerce-product-request', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Get settings.
	 *
	 * @return array $settings.
	 */
	public static function get_settings( $reload = false ) {

		// Get settings if settings is empty
		if ( empty( self::$settings ) || true === $reload ) {

			$settings = unserialize( get_option( 'WBL_WC_PRODUCT_REQUEST', false ) );
			self::$settings = $settings;
		}

		return self::$settings;
	}

	/**
	 * Set settings.
	 *
	 * @return array $settings.
	 */
	public static function set_settings( $settings ) {

		if ( ! empty( $settings ) ) {

			// Update settings
			update_option( 'WBL_WC_PRODUCT_REQUEST', serialize( $settings ), false );

			return self::get_settings( true );
		}

		return self::get_settings();
	}

	/**
	 * Plugin Activation.
	 *
	 * @return void.
	 */
	public static function activation() {

		// Get settings
		$settings = self::get_settings();

		// Check settings
		if ( empty( $settings ) || ! is_array( $settings ) ) {

			// Base settings
			$settings = array(
				'enable_module'		=> true,
				'button_text'		=> '',
				'unistall_module'	=> array(
					'remove_product_requests'	=> false,
					'remove_module_settings'	=> false,
				),
			);

			// Set settings
			$settings = self::set_settings( $settings );
		}
	}

	/**
	 * Plugin uninstall.
	 *
	 * @return void.
	 */
	public static function uninstall() {

		// Get settings
		$settings = self::get_settings();

		// Remove plugin settings
		if ( true === (bool) $settings['unistall_module']['remove_module_settings'] ) {

			delete_option( 'WBL_WC_PRODUCT_REQUEST' );
		}

		// Remove product requests
		if ( true === (bool) $settings['unistall_module']['remove_product_requests'] ) {

			// Get all product requests
			$product_requests = get_posts(
				array(
					'post_type'			=> 'wbl_product_request',
					'post_status'		=> 'any',
					'posts_per_page'	=> -1,
				)
			);

			// Remove product requests
			if ( ! empty( $product_requests ) ) {

				foreach ( $product_requests as $product_request ) {

					wp_delete_post( $product_request->ID, true );
				}
			}
		}

	}
}

$wbl_wc_product_request = new Wbl_WC_Product_Request();

// Activation hook
register_activation_hook( __FILE__, array( 'Wbl_WC_Product_Request', 'activation' ) );

// Uninstall hook
register_uninstall_hook( __FILE__, array( 'Wbl_WC_Product_Request', 'uninstall' ) );
