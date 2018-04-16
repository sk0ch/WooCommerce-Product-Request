<?php
/**
 * Front-end Form.
 *
 * @file
 * @package		WooCommerce Product Request
 * @author		Andrew Skochelias
 */

defined( 'ABSPATH' ) || die();

/**
 * Class Wbl_WC_Product_Request_Form.
 */
class Wbl_WC_Product_Request_Form extends Wbl_WC_Product_Request {

	/**
	 * Constructor
	 */
	function __construct() {

		// Get plugin settings
		$settings = self::get_settings();

		// Check enable madolu
		if ( false === (bool) $settings['enable_module'] ) {

			return null;
		}

		// Set button position
		$button_position = isset( $settings['button_position'] ) ? $settings['button_position'] : 'woocommerce_single_product_summary';

		// Cnange button position
		$button_position = apply_filters( 'wbl_wc_product_request_button_position', $button_position );

		add_action( $button_position, 						array( &$this, 'show_form' ) );
		add_action( 'wp_footer', 							array( &$this, 'show_form_js' ) );
		add_action( 'wp_enqueue_scripts', 					array( &$this, 'show_form_css' ) );
		add_action( 'wp_ajax_call_me_send_form', 			array( &$this, 'form_ajax_action' ) );
		add_action( 'wp_ajax_nopriv_call_me_send_form', 	array( &$this, 'form_ajax_action' ) );
	}

	/**
	 * Show form
	 *
	 * @return void.
	 */
	public function show_form() {

		$settings = self::get_settings();

		// Set button text
		$button_text = ! empty( $settings['button_text'] ) ? $settings['button_text'] : esc_html__( 'Call me', WBL_WC_PRODUCT_REQUEST_DOMAIN );

		// Show button
		echo '<a href="#" class="wbl_cm_show">' . esc_html( $button_text ) . '</a>';

		// Show form
		?>
			<div id="wbl_cm">

				<div id="wbl_cm_close_area"></div>

				<div class="inner">

					<form id="wbl_cm_form">

						<?php wp_nonce_field( 'wbl_call_me_form' , 'wbl_call_me_form_nonce_field' ); ?>

						<h1><?php esc_html_e( 'Call me', WBL_WC_PRODUCT_REQUEST_DOMAIN ); ?><span class="wbl_cm_close dashicons dashicons-no"></span></h1>

						<label for="wbl_cm_name"><?php esc_html_e( 'Name', WBL_WC_PRODUCT_REQUEST_DOMAIN ); ?></label>
						<input type="text" id="wbl_cm_name" required/>

						<label for="wbl_cm_phone"><?php esc_html_e( 'Phone', WBL_WC_PRODUCT_REQUEST_DOMAIN ); ?></label>
						<input type="tel" id="wbl_cm_phone" required/>

						<a href="#" class="wbl_toggle"><?php esc_html_e( 'Add comment', WBL_WC_PRODUCT_REQUEST_DOMAIN ); ?></a>

						<div class="wbl_cm_comment_toggle">

							<label for="wbl_cm_comment"><?php esc_html_e( 'Comment', WBL_WC_PRODUCT_REQUEST_DOMAIN ); ?></label>

							<textarea id="wbl_cm_comment" rows="10"></textarea>

						</div>

						<input class="wbl_cm_submit" type="submit" value="<?php esc_html_e( 'Submit', WBL_WC_PRODUCT_REQUEST_DOMAIN ); ?>"/>
						<span class="wbl_cm_success hide"><?php esc_html_e( 'Your request has been sent', WBL_WC_PRODUCT_REQUEST_DOMAIN ); ?></span>
						<img class="wbl_cm_loader hide" src="<?php echo esc_url( WBL_WC_PRODUCT_REQUEST_URL ); ?>/dist/media/gear.svg"/>
						<?php wp_nonce_field( 'webolatory_save_settings', 'webolatory_save_settings_nonce_field' ); ?>

					</form>

				</div>

			</div>
		<?php
	}

	/**
	 * Show form css
	 *
	 * @return void.
	 */
	public function show_form_css() {

		if ( false === $this->is_single_product_page() ) {

			return null;
		}

		wp_enqueue_style( WBL_WC_PRODUCT_REQUEST_DOMAIN,  WBL_WC_PRODUCT_REQUEST_URL . '/dist/css/frontend.css' );
	}

	/**
	 * Show form js
	 *
	 * @return void.
	 */
	public function show_form_js() {

		if ( false === $this->is_single_product_page() ) {
			return null;
		}

		// Get post data
		global $post;

		if ( empty( $post ) ) {
			return null;
		}

		?>
		<script>
			jQuery( document ).ready( function ($) {

				$(".wbl_cm_comment_toggle").hide();

				$(".wbl_toggle").click(function(){
					$(".wbl_cm_comment_toggle").toggle();
					$(this).toggleClass("active");
				});

				// form open/close function
				function toggleForm(){
					$("#wbl_cm").toggleClass("active");
					if ( $("#wbl_cm").hasClass("active") ){
						$('html, body').css({
							'overflow': 'hidden'
						});
					} else {
						$("#wbl_cm_name, #wbl_cm_phone textarea, #wbl_cm_comment").val('');
						$(".wbl_cm_comment_toggle").hide();
						$('html, body').css({
							'overflow': 'auto'
						});
					}
				}

				$(".wbl_cm_show, #wbl_cm_close_area, .wbl_cm_close").click(function(e){
					toggleForm();
					e.preventDefault();
				});

				// escape key close function
				$(document).keyup(function(e) {
				    if (e.keyCode == 27) {
						if ( $("#wbl_cm").hasClass("active") ){
				        	toggleForm();
							e.preventDefault();
						}
				    }
				});
				// Ajax action
				jQuery( '.wbl_cm_submit' ).click( function(e) {
					if ( $("#wbl_cm_name").val() && $("#wbl_cm_phone").val() ){

						//Show loader and hide button
						$(".wbl_cm_loader,.wbl_cm_submit").toggleClass("hide");

						// Get ajax URL
						$ajaxurl = '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>';

						// Data array
						var $data = {
							action:		'call_me_send_form',
							product:	<?php echo absint( $post->ID ); ?>,
							name:		jQuery( '#wbl_cm_name' ).val(),
							phone:		jQuery( '#wbl_cm_phone' ).val(),
							comment:	jQuery( '#wbl_cm_comment' ).val(),
							nonce:		jQuery( '#wbl_call_me_form_nonce_field' ).val()
						};

						// Response
						jQuery.post( $ajaxurl, $data, function( $response ) {

							if ( 1 == $response){
								$(".wbl_cm_loader,.wbl_cm_success").toggleClass("hide");
								$(".wbl_cm_comment_toggle").hide();
								$("#wbl_cm_form input,#wbl_cm_form label,#wbl_cm_form .wbl_toggle").css("display","none");
							}
						});

					e.preventDefault();
					}
				});

			});
		</script>
		<?php
	}

	/**
	 * Form ajax action
	 *
	 * @return void.
	 */
	public function form_ajax_action() {

		// Security check
		if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'wbl_call_me_form' ) ) {

			return false;
			wp_die();
		}

		// Get product
		$product = get_post( absint( $_REQUEST['product'] ) );

		if ( ! empty( $product ) ) {

			// Sanitize in data
			$data = array(
				'name' 		=> isset( $_REQUEST['name'] )		? sanitize_text_field( $_REQUEST['name'] ) 		: '',
				'phone' 	=> isset( $_REQUEST['phone'] )		? sanitize_text_field( $_REQUEST['phone'] )		: '',
				'comment' 	=> isset( $_REQUEST['comment'] )	? sanitize_text_field( $_REQUEST['comment'] )	: '',
				'product' 	=> $product->ID,
			);

			$title = wp_strip_all_tags( $data['name'] . ' - ' . $data['phone'] );

			// Create post
			$post_data = array(
				'post_type'    		=> 'wbl_product_request',
				'post_title'    	=> $title,
				'post_status'   	=> 'publish',
			);

			// Insert post
			$post_id = wp_insert_post( $post_data );

			if ( $post_id ) {
				// Update post meta
				update_post_meta( $post_id, 'call_me_form', $data );
			}

			// Create mail
			$to 		= get_option( 'admin_email' );
			$title		= '[' . get_bloginfo( 'name' ) . '] ' .$title;
			$message	= sprintf( '
				<div>
					<p><b>%s</b> %s</p>
					<p><b>%s</b> %s</p>
					<p><b>%s</b> %s</p>
					<p><b>%s</b> <a href="%s">%s</a></p>
				</div>',
				esc_html__( 'Client name: ', WBL_WC_PRODUCT_REQUEST_DOMAIN ),
				esc_html( $data['name'] ),
				esc_html__( 'Client phone: ', WBL_WC_PRODUCT_REQUEST_DOMAIN ),
				esc_html( $data['phone'] ),
				esc_html__( 'Client comment: ', WBL_WC_PRODUCT_REQUEST_DOMAIN ),
				esc_html( $data['comment'] ),
				esc_html__( 'Product: ', WBL_WC_PRODUCT_REQUEST_DOMAIN ),
				esc_url( get_permalink( $product->ID ) ),
				esc_html( $product->post_title )
			);

			// Send mail
			$status = wp_mail(
				$to,		// To
				$title,		// Subject
				$message,	// Message
				'content-type: text/html'
			);

			wp_die( 1 );
		}

		wp_die();
	}

	/**
	 * Check is single product page
	 *
	 * @return boolean
	 */
	public function is_single_product_page() {

		// Single product
		if ( is_singular( 'product' ) ) {
			return true;
		}

		return false;
	}
}

$call_me_form = new Wbl_WC_Product_Request_Form();
