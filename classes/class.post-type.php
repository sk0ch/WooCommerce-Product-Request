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
			'name'               	=> __( 'Product Requests', 'post type general name', 	WBL_WC_PRODUCT_REQUEST_DOMAIN ),
			'singular_name'      	=> __( 'Product Request', 'post type singular name', 	WBL_WC_PRODUCT_REQUEST_DOMAIN ),
			'menu_name'          	=> __( 'Product Requests', 'admin menu', 				WBL_WC_PRODUCT_REQUEST_DOMAIN ),
			'name_admin_bar'     	=> __( 'Product Request', 'add new on admin bar', 		WBL_WC_PRODUCT_REQUEST_DOMAIN ),
			'add_new'            	=> __( 'Add New', 'Request', 							WBL_WC_PRODUCT_REQUEST_DOMAIN ),
			'add_new_item'       	=> __( 'Add New Request',								WBL_WC_PRODUCT_REQUEST_DOMAIN ),
			'new_item'           	=> __( 'New Request', 									WBL_WC_PRODUCT_REQUEST_DOMAIN ),
			'edit_item'          	=> __( 'Edit Request', 									WBL_WC_PRODUCT_REQUEST_DOMAIN ),
			'view_item'          	=> __( 'View Request', 									WBL_WC_PRODUCT_REQUEST_DOMAIN ),
			'all_items'          	=> __( 'All Requests', 									WBL_WC_PRODUCT_REQUEST_DOMAIN ),
			'search_items'       	=> __( 'Search Request', 								WBL_WC_PRODUCT_REQUEST_DOMAIN ),
			'parent_item_colon'  	=> __( 'Parent Request', 								WBL_WC_PRODUCT_REQUEST_DOMAIN ),
			'not_found'          	=> __( 'No product requests found.',					WBL_WC_PRODUCT_REQUEST_DOMAIN ),
			'not_found_in_trash'	=> __( 'No product requests found in Trash.',			WBL_WC_PRODUCT_REQUEST_DOMAIN ),
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
