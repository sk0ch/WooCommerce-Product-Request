<?php
/**
 * Meta box
 *
 * @file
 * @package		WooCommerce Product Request
 * @author		Andrew Skochelias
 */

defined( 'ABSPATH' ) || die();

/**
 * Class Wbl_WC_Product_Request_Box.
 */
class Wbl_WC_Product_Request_Box extends Wbl_WC_Product_Request {

	/**
	 * Constructor
	 */
	function __construct() {

		// Add meta box
		add_action( 'add_meta_boxes', array( &$this, 'register_meta_box' ) );
	}

	/**
	 * Register Post MetaBox
	 *
	 * @return void.
	 */
	public function register_meta_box() {

		if ( false === $this->is_call_me_page() ) {
			return false;
		}

		add_meta_box( 'call_me_meta_box', esc_html__( 'Form data', 'woocommerce-product-request' ), array( &$this, 'show_meta_box' ), '', 'normal', 'high' );

		return null;
	}

	/**
	 * Show Post MetaBox
	 *
	 * @return void.
	 */
	public function show_meta_box() {

		global $post;

		// Get form data
		$post_meta = get_post_meta( $post->ID, 'call_me_form', true );

		// Show form data
		if ( is_array( $post_meta ) && ! empty( $post_meta ) ) {

			echo '<div class="wbl-call-me-info">';

			foreach ( $post_meta as $key => $value ) {

				switch ( $key ) {

					case 'product':

						// Show product
						printf( '
							<div class="wbl-call-me-info-row">
								%s: <a href="%s">%s</a>
							</div>
							',
							esc_html__( 'Product', 'woocommerce-product-request' ),
							esc_url( get_permalink( $value ) ),
							esc_html( get_the_title( $value ) )
						);

						break;

					default:

						// Show other field
						printf( '
							<div class="wbl-call-me-info-row">
								<b style="text-transform:capitalize;">%s</b>: %s
							</div>
							',
							esc_html__( $key, 'woocommerce-product-request' ),
							esc_html( $value )
						);

						break;
				}
			}

			echo '</div>';
		}
		?>

		<style>
			.wbl-call-me-info{
				max-width: 800px;
				margin: 0 auto;
				border: 1px solid #ddd;
				padding: 10px 20px;
				box-shadow: 0 1px 2px rgba(0,0,0,0.1);
				transition: color .3s, border .3s, background .3s, opacity .3s;
				border-radius: 3px;
				background-color: #fdfdfd;
			}
			
			.wbl-call-me-info-row{
				border-bottom: 1px dashed #ddd;
				padding: 5px 0px;
			}
			.wbl-call-me-info-row:last-child {
				border:none;
			}
		</style>
		
		<?php
	}

	/**
	 * Check is product request page
	 *
	 * @return boolean
	 */
	public function is_call_me_page() {

		global $post;
		global $pagenow;

		if ( 'post.php' !== $pagenow && 'post-new.php' !== $pagenow && 'edit.php' === $pagenow ) {
			return false;
		}

		if ( 'wbl_product_request' !== $post->post_type ) {
			return false;
		}

		return true;
	}
}

$product_request_box = new Wbl_WC_Product_Request_Box();
