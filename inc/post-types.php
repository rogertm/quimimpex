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
 * Register CPT
 *
 * @since Quimimpex 1.0
 */
function quimimpex_cpt(){
	// Prefix cpt with project initials (Quimimpex = qm)
	$post_types = array(
		'qm-export-product'	=> array(
			'post-type'		=> 'qm-export-product',
			'singular'		=> _x( 'Export Product', 'post type singular name', 'quimimpex' ),
			'plural'		=> _x( 'Export Products', 'post type general name', 'quimimpex' ),
			'label'			=> _x( 'Export Line', 'post type general name', 'quimimpex' ),
			'hierarchical'	=> false,
			'supports'		=> array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'rewrite'		=> _x( 'export-product', 'post type slug', 'quimimpex' ),
			'public'		=> true,
		),
		'qm-import-product'	=> array(
			'post-type'		=> 'qm-import-product',
			'singular'		=> _x( 'Import Product', 'post type singular name', 'quimimpex' ),
			'plural'		=> _x( 'Import Products', 'post type general name', 'quimimpex' ),
			'label'			=> _x( 'Import Line', 'post type general name', 'quimimpex' ),
			'hierarchical'	=> false,
			'supports'		=> array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'rewrite'		=> _x( 'import-product', 'post type slug', 'quimimpex' ),
			'public'		=> true,
		),
		'qm-company'	=> array(
			'post-type'		=> 'qm-company',
			'singular'		=> _x( 'Company', 'post type singular name', 'quimimpex' ),
			'plural'		=> _x( 'Companies', 'post type general name', 'quimimpex' ),
			'label'			=> _x( 'Companies', 'post type general name', 'quimimpex' ),
			'hierarchical'	=> false,
			'supports'		=> array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'rewrite'		=> _x( 'company', 'post type slug', 'quimimpex' ),
			'public'		=> false,
		),
		'qm-customer'	=> array(
			'post-type'		=> 'qm-customer',
			'singular'		=> _x( 'Customer', 'post type singular name', 'quimimpex' ),
			'plural'		=> _x( 'Customers', 'post type general name', 'quimimpex' ),
			'label'			=> _x( 'Customers', 'post type general name', 'quimimpex' ),
			'hierarchical'	=> false,
			'supports'		=> array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'rewrite'		=> _x( 'customer', 'post type slug', 'quimimpex' ),
			'public'		=> false,
		),
		'qm-document'	=> array(
			'post-type'		=> 'qm-document',
			'singular'		=> _x( 'Document', 'post type singular name', 'quimimpex' ),
			'plural'		=> _x( 'Documents', 'post type general name', 'quimimpex' ),
			'label'			=> _x( 'Documents', 'post type general name', 'quimimpex' ),
			'hierarchical'	=> false,
			'supports'		=> array( 'title' ),
			'rewrite'		=> _x( 'document', 'post type slug', 'quimimpex' ),
			'public'		=> false,
		),
		'qm-newsletter'	=> array(
			'post-type'		=> 'qm-newsletter',
			'singular'		=> _x( 'Newsletter', 'post type singular name', 'quimimpex' ),
			'plural'		=> _x( 'Newsletters', 'post type general name', 'quimimpex' ),
			'label'			=> _x( 'Newsletters', 'post type general name', 'quimimpex' ),
			'hierarchical'	=> false,
			'supports'		=> array( 'title' ),
			'rewrite'		=> _x( 'newsletter', 'post type slug', 'quimimpex' ),
			'public'		=> false,
		),
		'qm-contact'	=> array(
			'post-type'		=> 'qm-contact',
			'singular'		=> _x( 'Contact', 'post type singular name', 'quimimpex' ),
			'plural'		=> _x( 'Contacts', 'post type general name', 'quimimpex' ),
			'label'			=> _x( 'Contacts', 'post type general name', 'quimimpex' ),
			'hierarchical'	=> false,
			'supports'		=> array( 'title' ),
			'rewrite'		=> _x( 'contact', 'post type slug', 'quimimpex' ),
			'public'		=> false,
		),
		'qm-executive'	=> array(
			'post-type'		=> 'qm-executive',
			'singular'		=> _x( 'Executive', 'post type singular name', 'quimimpex' ),
			'plural'		=> _x( 'Executives', 'post type general name', 'quimimpex' ),
			'label'			=> _x( 'Executives', 'post type general name', 'quimimpex' ),
			'hierarchical'	=> false,
			'supports'		=> array( 'title', 'thumbnail', 'page-attributes' ),
			'rewrite'		=> _x( 'executive', 'post type slug', 'quimimpex' ),
			'public'		=> false,
		),
	);

	foreach ( $post_types as $post_type => $pt ) :
		$labels = array(
			'name'					=> $pt['label'],
			'singular_name'			=> $pt['singular'],
			'manu_name'				=> $pt['plural'],
			'all_items'				=> sprintf( __( 'All %s', 'quimimpex' ), $pt['plural'] ),
			'add_new'				=> __( 'Add new', 'quimimpex' ),
			'add_new_item'			=> sprintf( __( 'Add new %s', 'quimimpex' ), $pt['singular'] ),
			'edit_item'				=> sprintf( __( 'Edit %s', 'quimimpex' ), $pt['singular'] ),
			'new_item'				=> sprintf( __( 'New %s', 'quimimpex' ), $pt['singular'] ),
			'view_item'				=> sprintf( __( 'View %s', 'quimimpex' ), $pt['singular'] ),
			'search_items'			=> sprintf( __( 'Search %s', 'quimimpex' ), $pt['plural'] ),
			'not_found'				=> sprintf( __( 'No %s found', 'quimimpex' ), $pt['singular'] ),
			'not_found_in_trash'	=> sprintf( __( 'No %s found in trash', 'quimimpex' ), $pt['singular'] ),
			'parent_item_colon'		=> sprintf( __( 'Parent %s', 'quimimpex' ), $pt['singular'] ),
		);

		$args = array(
			'labels'				=> $labels,
			'public'				=> true,
			'exclude_from_search'	=> ( $pt['public'] ) ? false : true,
			'publicly_queryable'	=> ( $pt['public'] ) ? true : false,
			'show_ui'				=> true,
			'show_in_nav_menus'		=> true,
			'show_in_menu'			=> true,
			'show_in_admin_bar'		=> true,
			'capability_type'		=> 'post',
			'hierarchical'			=> $pt['hierarchical'],
			'supports'				=> $pt['supports'],
			'has_archive'			=> ( $pt['public'] ) ? true : false,
			'rewrite'				=> array( 'slug' => $pt['rewrite'] ),
			'query_var'				=> true,
			'can_export'			=> true,
		);

		register_post_type( $pt['post-type'], $args );
	endforeach;

	// Register Custom Taxonomies
	$taxonomies = array(
		'qm-export-line'	=> array(
			'post_type'				=> array( 'qm-export-product' ),
			'singular'				=> _x( 'Export Line', 'taxonomy singular name', 'quimimpex' ),
			'plural'				=> _x( 'Export Lines', 'taxonomy general name', 'quimimpex' ),
			'hierarchical'			=> true,
			'show_ui'				=> true,
			'show_admin_column'		=> true,
			'update_count_callback'	=> '_update_post_term_count',
			'query_var'				=> true,
			'rewrite'				=> array( 'slug' => 'export-line' ),
		),
		'qm-import-line'	=> array(
			'post_type'				=> array( 'qm-import-product' ),
			'singular'				=> _x( 'Import Line', 'taxonomy singular name', 'quimimpex' ),
			'plural'				=> _x( 'Import Lines', 'taxonomy general name', 'quimimpex' ),
			'hierarchical'			=> true,
			'show_ui'				=> true,
			'show_admin_column'		=> true,
			'update_count_callback'	=> '_update_post_term_count',
			'query_var'				=> true,
			'rewrite'				=> array( 'slug' => 'import-line' ),
		),
	);

	foreach ( $taxonomies as $taxonomy => $tax ) :
		$labels = array(
			'name'					=> $tax['plural'],
			'singular_name'			=> $tax['singular'],
			'search_items'			=> sprintf( __( 'Search %s', 'quimimpex' ), $tax['plural'] ),
			'popular_items'			=> sprintf( __( 'Popular %s', 'quimimpex' ), $tax['plural'] ),
			'all_items'				=> sprintf( __( 'All %s', 'quimimpex' ), $tax['plural'] ),
			'parent_item'			=> sprintf( __( 'Parent %s', 'quimimpex' ), $tax['plural'] ),
			'parent_item_colon'		=> sprintf( __( 'Parent %s', 'quimimpex' ), $tax['plural'] ),
			'edit_item'				=> sprintf( __( 'Edit %s', 'quimimpex' ), $tax['plural'] ),
			'update_item'			=> sprintf( __( 'Update %s', 'quimimpex' ), $tax['singular'] ),
			'view_item'				=> sprintf( __( 'View %s', 'quimimpex' ), $tax['singular'] ),
			'add_new_item'			=> sprintf( __( 'Add New %s', 'quimimpex' ), $tax['singular'] ),
			'new_item_name'			=> sprintf( __( 'New %s name', 'quimimpex' ), $tax['singular'] ),
			'not_found'				=> sprintf( __( 'No %s found', 'quimimpex' ), $tax['plural'] ),
			'menu_name'				=> $tax['plural'],
		);

		$args = array(
			'hierarchical'				=> $tax['hierarchical'],
			'labels'					=> $labels,
			'show_ui'					=> $tax['show_ui'],
			'show_admin_column'			=> $tax['show_admin_column'],
			'update_count_callback'		=> $tax['update_count_callback'],
			'query_var'					=> $tax['query_var'],
			'rewrite'					=> $tax['rewrite'],
		);

		register_taxonomy( $taxonomy, $tax['post_type'], $args );
	endforeach;

}
add_action( 'init', 'quimimpex_cpt' );

/**
 * Rewrite rules to get permalinks works when theme will be activated
 *
 * @since Quimimpex 1.0
 */
function quimimpex_rewrite_flush(){
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'quimimpex_rewrite_flush' );
?>
