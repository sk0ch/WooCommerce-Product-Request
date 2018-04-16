<?php
/**
 * Post type
 *
 * @file
 * @package		WooCommerce Product Request
 * @author		Andrew Skochelias
 */

defined( 'ABSPATH' ) || die();

/**
 * Class Wbl_WC_Product_Request_Post_Type.
 */
class Wbl_WC_Product_Request_Post_Type extends Wbl_WC_Product_Request {

	/**
	 * Constructor
	 */
	function __construct() {

		// Register post type
		add_action( 'init', array( &$this, 'register_post_type' ) );
	}

	/**
	 * Create subject post type
	 */
	function register_post_type() {

		$labels = array(
			'name'               	=> __( 'Product Requests', 'post type general name', 	'woocommerce-product-request' ),
			'singular_name'      	=> __( 'Product Request', 'post type singular name', 	'woocommerce-product-request' ),
			'menu_name'          	=> __( 'Product Requests', 'admin menu', 				'woocommerce-product-request' ),
			'name_admin_bar'     	=> __( 'Product Request', 'add new on admin bar', 		'woocommerce-product-request' ),
			'add_new'            	=> __( 'Add New', 'Request', 							'woocommerce-product-request' ),
			'add_new_item'       	=> __( 'Add New Request',								'woocommerce-product-request' ),
			'new_item'           	=> __( 'New Request', 									'woocommerce-product-request' ),
			'edit_item'          	=> __( 'Edit Request', 									'woocommerce-product-request' ),
			'view_item'          	=> __( 'View Request', 									'woocommerce-product-request' ),
			'all_items'          	=> __( 'All Requests', 									'woocommerce-product-request' ),
			'search_items'       	=> __( 'Search Request', 								'woocommerce-product-request' ),
			'parent_item_colon'  	=> __( 'Parent Request', 								'woocommerce-product-request' ),
			'not_found'          	=> __( 'No product requests found.',					'woocommerce-product-request' ),
			'not_found_in_trash'	=> __( 'No product requests found in Trash.',			'woocommerce-product-request' ),
		);

		$args = array(
			'labels'				=> $labels,
			'public'				=> true,
			'show_ui' 				=> true,
			'menu_icon'    			=> 'dashicons-products',
			'publicly_queryable'	=> false,
			'menu_position'			=> 30,
			'show_in_menu'			=> true,
			'query_var' 			=> true,
			'rewrite'            	=> array( 'slug' => 'product-request' ),
			'capability_type' 		=> 'post',
			'has_archive' 			=> true,
			'hierarchical' 			=> false,
			'supports' 				=> array( 'title', 'editor' ),
		);

		register_post_type( 'wbl_product_request', $args );
	}
}

$product_request_post_type = new Wbl_WC_Product_Request_Post_Type();
