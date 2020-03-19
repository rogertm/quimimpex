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
	add_meta_box( 'quimimpex-upload-data', __( 'Data Sheet' ), 'quimimpex_export_data_sheet_callback', 'qm-export-product', 'advanced', 'high' );

	// Import Products
	add_meta_box( 'quimimpex-import-product-data', __( 'Product Data' ), 'quimimpex_import_product_data_callback', 'qm-import-product', 'advanced', 'high' );
	add_meta_box( 'quimimpex-upload-data', __( 'Data Sheet' ), 'quimimpex_import_data_sheet_callback', 'qm-import-product', 'advanced', 'high' );

	// Company
	add_meta_box( 'quimimpex-company-data', __( 'Company Data' ), 'quimimpex_company_data_callback', 'qm-company', 'advanced', 'high' );

	// Contact
	add_meta_box( 'quimimpex-contact-data', __( 'Contact Data' ), 'quimimpex_contact_data_callback', 'qm-contact', 'advanced', 'high' );

	// Documents
	add_meta_box( 'quimimpex-upload-data', __( 'Document Data' ), 'quimimpex_document_data_callback', 'qm-document', 'advanced', 'high' );

	// Newsletter
	add_meta_box( 'quimimpex-upload-data', __( 'Newsletter Data' ), 'quimimpex_newsletter_data_callback', 'qm-newsletter', 'advanced', 'high' );
}
add_action( 'add_meta_boxes', 'quimimpex_meta_boxes' );

/**
 * Includes
 */
require( get_stylesheet_directory() . '/meta/export-products.php' );
require( get_stylesheet_directory() . '/meta/import-products.php' );
require( get_stylesheet_directory() . '/meta/company.php' );
require( get_stylesheet_directory() . '/meta/contact.php' );
require( get_stylesheet_directory() . '/meta/documents.php' );
require( get_stylesheet_directory() . '/meta/newsletters.php' );
?>
