<?php
/**
 * Setting page.
 *
 * @file
 * @package		WooCommerce Product Request
 * @author		Andrew Skochelias
 */

defined( 'ABSPATH' ) || die();

/**
 * Class WBL_Call_Me_Setting_Page.
 */
class Wbl_WC_Product_Request_Setting_Page extends Wbl_WC_Product_Request {

	/**
	 * Constructor
	 */
	function __construct() {

		add_action( 'admin_menu', 				array( &$this, 'register_setting_page' 	) );
		add_action( 'admin_enqueue_scripts', 	array( &$this, 'register_scripts' 		) );
	}

	/**
	 * Register setting page
	 *
	 * @return void.
	 */
	public function register_setting_page() {

		add_submenu_page(
			'woocommerce',
			'WooCommerce Product Request',
			'Product Request',
			'manage_options',
			'product-request',
			array( &$this, 'show_setting_page' )
		);
	}

	/**
	 * Show setting page.
	 *
	 * @return void.
	 */
	public function show_setting_page() {

		if ( isset( $_POST['wc-call-me-save-settings'] ) && isset( $_POST['wc-call-me-save-settings'] ) && wp_verify_nonce( $_POST['WBL_WC_PRODUCT_REQUEST_save_settings_nonce_field'], 'WBL_WC_PRODUCT_REQUEST_save_settings' ) ) {

			// Sanitize form data
			$settings = array(
				'enable_module'		=> absint( $_POST['wc-call-me']['enable_module'] ),
				'button_text'		=> sanitize_text_field( $_POST['wc-call-me']['button_text'] ),
				'button_position'	=> sanitize_text_field( $_POST['wc-call-me']['button_position'] ),
				'unistall_module'	=> array(
					'remove_module_settings'	=> absint( $_POST['wc-call-me']['unistall_module']['remove_module_settings'] ),
					'remove_product_requests'	=> absint( $_POST['wc-call-me']['unistall_module']['remove_product_requests'] ),
				),
			);

			// Save settings
			$settings = self::set_settings( $settings );
		} else {

			// Get settings
			$settings = self::get_settings();
		}
		?>

		<div class="wbl_container">
			<div class="wbl_main">
				<div class="wbl_main_header">
					<div class="wbl_left">
						<div class="wbl_logo">
							<a href="http://webolatory.com/" rel="nofollow" target="blank">
								<img src="<?php echo esc_url( WBL_WC_PRODUCT_REQUEST_URL ); ?>/dist/media/webolatory_logo.svg" class="wbl_hide_mobile" alt="logo">
								<img src="<?php echo esc_url( WBL_WC_PRODUCT_REQUEST_URL ); ?>/dist/media/mobile_logo.svg" class="wbl_hide_desc" alt="logo">
							</a>
						</div>
					</div>

					<div class="wbl_right">
						<h1>WooCommerce Product Request</h1>
					</h1></div>

				</div>

				<div class="wbl_main_section">

					<div id="changelog-list" class="wbl_subsection" style="display:block;">

						<form method="POST">

							<?php wp_nonce_field( 'WBL_WC_PRODUCT_REQUEST_save_settings' , 'WBL_WC_PRODUCT_REQUEST_save_settings_nonce_field' ); ?>

							<h1><?php esc_html_e( 'General settings', WBL_WC_PRODUCT_REQUEST_DOMAIN ); ?></h1>

							<div class="wbl_subsection">

								<div class="wbl_left">
									<h3><?php esc_html_e( 'Enable module', WBL_WC_PRODUCT_REQUEST_DOMAIN ); ?></h3>
								</div>

								<div class="wbl_right">
									<ul class="wbl_switch">
										<li class="switch_value"><input type="text" name="wc-call-me[enable_module]" value="<?php echo absint( $settings['enable_module'] ); ?>" hidden=""></li>
										<li data-val="0" class="">Off</li>
										<li data-val="1" class="">On</li>
									</ul>
								</div>

							</div>

							<div class="wbl_subsection">

								<div class="wbl_left">
									<h3><?php esc_html_e( 'Button text', WBL_WC_PRODUCT_REQUEST_DOMAIN ); ?></h3>
								</div>

								<div class="wbl_right">
									<input type="text" name="wc-call-me[button_text]" placeholder="<?php esc_html_e( 'Call me', WBL_WC_PRODUCT_REQUEST_DOMAIN ); ?>" value="<?php echo esc_html( $settings['button_text'] ); ?>">
								</div>

							</div>

							<div class="wbl_subsection">

								<div class="wbl_left">
									<h3><?php esc_html_e( 'Button position', WBL_WC_PRODUCT_REQUEST_DOMAIN ); ?></h3>
								</div>

								<div class="wbl_right">
									<select name="wc-call-me[button_position]">
										<option value="woocommerce_before_single_product" 			<?php selected( $settings['button_position'], 'woocommerce_before_single_product' ); 			?> >Before single product</option>
										<option value="woocommerce_after_single_product" 			<?php selected( $settings['button_position'], 'woocommerce_after_single_product' ); 			?> >After single product</option>
										<option value="woocommerce_before_single_product_summary" 	<?php selected( $settings['button_position'], 'woocommerce_before_single_product_summary' ); 	?> >Before single product summary</option>
										<option value="woocommerce_single_product_summary" 			<?php selected( $settings['button_position'], 'woocommerce_single_product_summary' ); 			?> >Single product summary</option>
										<option value="woocommerce_after_single_product_summary" 	<?php selected( $settings['button_position'], 'woocommerce_after_single_product_summary' ); 	?> >After single product summary</option>
										<option value="woocommerce_before_add_to_cart_form" 		<?php selected( $settings['button_position'], 'woocommerce_before_add_to_cart_form' ); 			?> >Before add to cart form</option>
										<option value="woocommerce_after_add_to_cart_form" 			<?php selected( $settings['button_position'], 'woocommerce_after_add_to_cart_form' ); 			?> >After add to cart form</option>
										<option value="woocommerce_before_add_to_cart_button" 		<?php selected( $settings['button_position'], 'woocommerce_before_add_to_cart_button' ); 		?> >Before add to cart button</option>
										<option value="woocommerce_after_add_to_cart_button" 		<?php selected( $settings['button_position'], 'woocommerce_after_add_to_cart_button' ); 		?> >After add to cart button</option>
									</select>
								</div>

							</div>

							<h1><?php esc_html_e( 'Uninstall settings', WBL_WC_PRODUCT_REQUEST_DOMAIN ); ?></h1>

							<div class="wbl_subsection">

								<div class="wbl_left">
									<h3><?php esc_html_e( 'Remove module settings', WBL_WC_PRODUCT_REQUEST_DOMAIN ); ?></h3>
								</div>

								<div class="wbl_right">
									<ul class="wbl_switch">
										<li class="switch_value"><input type="text" name="wc-call-me[unistall_module][remove_module_settings]" value="<?php echo absint( $settings['unistall_module']['remove_module_settings'] ); ?>" hidden=""></li>
										<li data-val="0" class="">Off</li>
										<li data-val="1" class="">On</li>
									</ul>
								</div>

							</div>

							<div class="wbl_subsection">

								<div class="wbl_left">
									<h3><?php esc_html_e( 'Remove all product requests', WBL_WC_PRODUCT_REQUEST_DOMAIN ); ?></h3>
								</div>

								<div class="wbl_right">
									<ul class="wbl_switch">
										<li class="switch_value"><input type="text" name="wc-call-me[unistall_module][remove_product_requests]" value="<?php echo absint( $settings['unistall_module']['remove_product_requests'] ); ?>" hidden=""></li>
										<li data-val="0" class="">Off</li>
										<li data-val="1" class="">On</li>
									</ul>
								</div>

							</div>

							<p>
								<input type="submit" value="<?php esc_html_e( 'Save', WBL_WC_PRODUCT_REQUEST_DOMAIN ); ?>" name="wc-call-me-save-settings" class="wbl_button small green">
							</p>
						</form>

					</div>

					<h3 class="wbl_copyright">
						© WooCommerce Product Request — Made with ♥ by <a href="http://webolatory.com/" rel="nofollow" target="blank">Webolatory</a>
					</h3>

				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Register scripts
	 *
	 * @return void.
	 */
	public static function register_scripts( $show = null ) {

		if ( ! self::is_product_request_save_setting_page() && true !== $show ) {

			return false;
		}

		// Register style
		wp_register_style( 'woocommerce-call-me-style', plugins_url( WBL_WC_PRODUCT_REQUEST_DOMAIN . '/dist/css/style.css' ), array(), null, 'all' );

		// Enqueue style
		wp_enqueue_style( 'woocommerce-call-me-style' );

		// Enqueue JS
		wp_enqueue_script( 'woocommerce-call-me-js', plugins_url( WBL_WC_PRODUCT_REQUEST_DOMAIN . '/dist/js/script.js' ), array(), null, true );
	}

	/**
	 * Cheack webolatory pack setting page.
	 *
	 * @return void.
	 */
	public static function is_product_request_save_setting_page() {

		global $pagenow;

		// Check page.
		if ( 'admin.php' === $pagenow && isset( $_GET['page'] ) && 'product-request' === sanitize_text_field( $_GET['page'] ) ) {
			return true;
		}

		return false;
	}
}

$setting_page = new Wbl_WC_Product_Request_Setting_Page();
