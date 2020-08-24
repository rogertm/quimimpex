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
	add_meta_box( 'quimimpex-export-product-content', __( 'Product Content', 'quimimpex' ), 'quimimpex_export_product_content_callback', 'qm-export-product', 'advanced', 'high' );
	add_meta_box( 'quimimpex-export-product-data', __( 'Product Data', 'quimimpex' ), 'quimimpex_export_product_data_callback', 'qm-export-product', 'advanced', 'high' );
	add_meta_box( 'quimimpex-upload-data', __( 'Data Sheet', 'quimimpex' ), 'quimimpex_export_data_sheet_callback', 'qm-export-product', 'advanced', 'high' );

	// Import Products
	add_meta_box( 'quimimpex-import-product-content', __( 'Product Content', 'quimimpex' ), 'quimimpex_import_product_content_callback', 'qm-import-product', 'advanced', 'high' );
	add_meta_box( 'quimimpex-import-product-data', __( 'Product Data', 'quimimpex' ), 'quimimpex_import_product_data_callback', 'qm-import-product', 'advanced', 'high' );
	add_meta_box( 'quimimpex-upload-data', __( 'Data Sheet', 'quimimpex' ), 'quimimpex_import_data_sheet_callback', 'qm-import-product', 'advanced', 'high' );

	// Company
	add_meta_box( 'quimimpex-company-data', __( 'Company Data', 'quimimpex' ), 'quimimpex_company_data_callback', 'qm-company', 'advanced', 'high' );

	// Contact
	add_meta_box( 'quimimpex-contact-data', __( 'Contact Data', 'quimimpex' ), 'quimimpex_contact_data_callback', 'qm-contact', 'advanced', 'high' );
	add_meta_box( 'quimimpex-contact-public', __( 'Public', 'quimimpex' ), 'quimimpex_contact_public_callback', 'qm-contact', 'side', 'high' );

	// Documents
	add_meta_box( 'quimimpex-upload-data', __( 'Document Data', 'quimimpex' ), 'quimimpex_document_data_callback', 'qm-document', 'advanced', 'high' );

	// Newsletter
	add_meta_box( 'quimimpex-upload-data', __( 'Newsletter Data', 'quimimpex' ), 'quimimpex_newsletter_data_callback', 'qm-newsletter', 'advanced', 'high' );

	// Executives
	add_meta_box( 'quimimpex-upload-data', __( 'Executive Data', 'quimimpex' ), 'quimimpex_executive_data_callback', 'qm-executive', 'advanced', 'high' );

	// Banners
	add_meta_box( 'quimimpex-banner-data', __( 'Banner Data', 'quimimpex' ), 'quimimpex_banner_data_callback', 'qm-banner', 'advanced', 'high' );
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
require( get_stylesheet_directory() . '/meta/executives.php' );
require( get_stylesheet_directory() . '/meta/banners.php' );
?>
