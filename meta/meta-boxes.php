<?php
/**
 * Quimimpex
 *
 * @package			WordPress
 * @subpackage		Quimimpex
 * @author			RogerTM
 * @license			license.txt
 * @link			https://themingisprose.com/twenty-em
 * @since 			Quimimpex 1.0
 */

/**
 * Register meta boxes for post types
 *
 * @since Quimimpex 1.0
 */
function quimimpex_meta_boxes(){
	// Export Products
	add_meta_box( 'quimimpex-export-product-data', __( 'Product Data' ), 'quimimpex_export_product_data_callback', 'qm-export-product', 'advanced', 'high' );

	// Contact
	add_meta_box( 'quimimpex-expcontact-data', __( 'Contact Data' ), 'quimimpex_contact_data_callback', 'qm-contact', 'advanced', 'high' );

	// Documents
	add_meta_box( 'quimimpex-document-data', __( 'Document Data' ), 'quimimpex_document_data_callback', 'qm-document', 'advanced', 'high' );
}
add_action( 'add_meta_boxes', 'quimimpex_meta_boxes' );

/**
 * Includes
 */
require( get_stylesheet_directory() . '/meta/export-products.php' );
require( get_stylesheet_directory() . '/meta/contact.php' );
require( get_stylesheet_directory() . '/meta/documents.php' );
?>
