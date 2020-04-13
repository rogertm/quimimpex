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
 * Quimimpex Setup
 *
 * @since Quimimpex 1.0
 */
function quimimpex_setup(){
	// Make Quimimpex available for translation.
	load_child_theme_textdomain( 'quimimpex', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'quimimpex_setup' );

/**
 * Avoid access to admin panel
 *
 * @since Quimimpex 1.0
 */
function quimimpex_avoid_admin_panel(){
	if ( is_admin() && current_user_can( 'subscriber' ) && ! wp_doing_ajax() ) :
		wp_redirect( home_url() );
		exit;
	endif;
}
add_action( 'init', 'quimimpex_avoid_admin_panel' );

/**
 * Hide admin bar
 *
 * @since Quimimpex 1.0
 */
function quimimpex_hide_admin_bar(){
	if ( current_user_can( 'subscriber' ) )
		return show_admin_bar( false );
}
add_action( 'init', 'quimimpex_hide_admin_bar' );

/**
 * Process Contact Form
 *
 * @since Quimimpex 1.0
 */
function quimimpex_process_contact_form(){
	if ( ! isset( $_POST['qm_contact_form_field'] )
		|| ! wp_verify_nonce( $_POST['qm_contact_form_field'], 'qm_contact_form_attr' ) )
		return;

	$cpt 			= ( isset( $_POST['qm_product_cpt'] ) && ! empty( $_POST['qm_product_cpt'] ) ) ? $_POST['qm_product_cpt'] : null;
	$tax 			= ( isset( $_POST['qm_product_tax'] ) && ! empty( $_POST['qm_product_tax'] ) ) ? $_POST['qm_product_tax'] : null;
	$posts 			= ( isset( $_POST['qm_products'] ) && ! empty( $_POST['qm_products'] ) ) ? $_POST['qm_products'] : null;
	$author			= ( isset( $_POST['qm_comment_author'] ) && ! empty( $_POST['qm_comment_author'] ) ) ? $_POST['qm_comment_author'] : null;
	$author_email	= ( isset( $_POST['qm_comment_author_email'] ) && ! empty( $_POST['qm_comment_author_email'] ) ) ? $_POST['qm_comment_author_email'] : null;

	if ( ! $cpt || ! $tax ) :
		$query = array(
			'do_action'	=> 'error',
			'status'	=> 'bad-request',
		);
		wp_redirect( add_query_arg( $query, get_permalink( $_POST['qm_post_id'] ) ) );
		exit;
	endif;

	if ( ! $posts ) :
		$query = array(
			'do_action'	=> 'error',
			'status'	=> 'empty-products',
		);
		wp_redirect( add_query_arg( $query, get_permalink( $_POST['qm_post_id'] ) ) );
		exit;
	endif;

	if ( ! $author ) :
		$query = array(
			'do_action'	=> 'error',
			'status'	=> 'empty-author',
		);
		wp_redirect( add_query_arg( $query, get_permalink( $_POST['qm_post_id'] ) ) );
		exit;
	endif;

	if ( ! $author_email ) :
		$query = array(
			'do_action'	=> 'error',
			'status'	=> 'empty-author-email',
		);
		wp_redirect( add_query_arg( $query, get_permalink( $_POST['qm_post_id'] ) ) );
		exit;
	endif;

	$taxonomy = get_taxonomy( $tax );

	$args = array(
		'post_type'			=> $cpt,
		'posts_per_page'	=> -1,
		'post__in'			=> $posts,
	);

	$products = get_posts( $args );

	$content  = '<h3>'. sprintf( __( '%1$s Product Request', 'quimimpex' ), $taxonomy->labels->name ) .'</h3>';
	$content .= '<h5>'. __( 'Product List:', 'quimimpex' ) .'</h5>';
	$content .= '<ul>';
	foreach ( $products as $product ) :
		$content .= '<li><a href="'. get_permalink( $product->ID ) .'" target="_blanck">'. $product->post_title .'</a></li>';
	endforeach;
	$content .= '</ul>';
	$content .= '<h3>'. __( 'Comment:', 'quimimpex' ) .'</h3>';
	$content .= ( $_POST['qm_comment_content'] != '' ) ? $_POST['qm_comment_content'] : __( 'No comment', 'quimimpex' );

	$commentdata = array(
		'comment_approved'		=> 0,
		'comment_author'		=> $_POST['qm_comment_author'],
		'comment_author_email'	=> $_POST['qm_comment_author_email'],
		'comment_content'		=> $content,
		'comment_post_ID'		=> $_POST['qm_post_id'],
		'comment_type'			=> 'qm_product_request',
		'comment_meta'			=> array( 'qm_products' => $_POST['qm_products'] ),
	);
	$comment = wp_insert_comment( $commentdata );

	$query = array(
		'do_action'	=> 'success',
		'status'	=> 'requested',
	);

	wp_redirect( add_query_arg( $query, get_permalink( $_POST['qm_post_id'] ) ) );
	exit;
}
add_action( 'template_redirect', 'quimimpex_process_contact_form' );

/**
 * Process products check in form
 *
 * @since Quimimpex 1.0
 */
function quimimpex_process_products_checkin_form(){
	if ( ! isset( $_POST['qm_checkin_form_field'] )
		|| ! wp_verify_nonce( $_POST['qm_checkin_form_field'], 'qm_checkin_form_attr' ) )
		return;

	$products 			= ( isset( $_POST['qm_checkin_product'] ) && ! empty( $_POST['qm_checkin_product'] ) ) ? $_POST['qm_checkin_product'] : null;
	$author			= ( isset( $_POST['qm_comment_author'] ) && ! empty( $_POST['qm_comment_author'] ) ) ? $_POST['qm_comment_author'] : null;
	$author_email	= ( isset( $_POST['qm_comment_author_email'] ) && ! empty( $_POST['qm_comment_author_email'] ) ) ? $_POST['qm_comment_author_email'] : null;

	if ( ! $products ) :
		$query = array(
			'do_action'	=> 'error',
			'status'	=> 'empty-products',
		);
		wp_redirect( add_query_arg( $query, get_permalink( $_POST['qm_post_id'] ) ) );
		exit;
	endif;

	if ( ! $author ) :
		$query = array(
			'do_action'	=> 'error',
			'status'	=> 'empty-author',
		);
		wp_redirect( add_query_arg( $query, get_permalink( $_POST['qm_post_id'] ) ) );
		exit;
	endif;

	if ( ! $author_email ) :
		$query = array(
			'do_action'	=> 'error',
			'status'	=> 'empty-email',
		);
		wp_redirect( add_query_arg( $query, get_permalink( $_POST['qm_post_id'] ) ) );
		exit;
	endif;

	$args = array(
		'post_type'			=> array( 'qm-import-product', 'qm-export-product' ),
		'posts_per_page'	=> -1,
		'post__in'			=> $products,
	);

	$products = get_posts( $args );

	$content  = '<h3>'. __( 'Product Request', 'quimimpex' ) .'</h3>';
	$content .= '<h5>'. __( 'Product List:', 'quimimpex' ) .'</h5>';
	$content .= '<ul>';
	foreach ( $products as $product ) :
		$content .= '<li><a href="'. get_permalink( $product->ID ) .'" target="_blanck">'. $product->post_title .'</a></li>';
	endforeach;
	$content .= '</ul>';
	$content .= '<h3>'. __( 'Comment:', 'quimimpex' ) .'</h3>';
	$content .= ( $_POST['qm_comment_content'] != '' ) ? $_POST['qm_comment_content'] : __( 'No comment', 'quimimpex' );

	$commentdata = array(
		'comment_approved'		=> 0,
		'comment_author'		=> $_POST['qm_comment_author'],
		'comment_author_email'	=> $_POST['qm_comment_author_email'],
		'comment_content'		=> $content,
		'comment_post_ID'		=> $_POST['qm_post_id'],
		'comment_type'			=> 'qm_product_request',
		'comment_meta'			=> array( 'qm_products' => $_POST['qm_products'] ),
	);
	$comment = wp_insert_comment( $commentdata );

	$query = array(
		'do_action'	=> 'success',
		'status'	=> 'requested',
	);

	$_SESSION['qm_checkin_products'] = null;

	wp_redirect( add_query_arg( $query, get_permalink( $_POST['qm_post_id'] ) ) );
	exit;
}
add_action( 'template_redirect', 'quimimpex_process_products_checkin_form' );

/**
 * Add custom Comment Types
 *
 * @since Quimimpex 1.0
 */
function quimimpex_comment_types( $comment_types ){
	$comments = array_merge( $comment_types,
					array(
						'qm_product_request'	=> __( 'Product Requests', 'quimimpex' ),
					)
				);
	return $comments;
}
add_action( 'admin_comment_types_dropdown', 'quimimpex_comment_types' );

/**
 * Check in products
 *
 * @since Quimimpex 1.0
 **/
function quimimpex_checkin_product(){
	global $post;
	if ( get_post_type( $post->ID ) == 'qm-import-product'
		|| get_post_type( $post->ID ) == 'qm-export-product' ) :
		echo '<div class="small d-inline mr-3"><span class="icomoon-shopping-cart text-muted"></span> <a href="#" class="qm-checkin-product" data-product-id="'. $post->ID .'">'. __( 'Check in', 'quimimpex' ) .'</a></div>';
	endif;
}
add_action( 't_em_action_entry_meta_footer', 'quimimpex_checkin_product', 15 );

/**
 * Add custom mime types to upload
 *
 * @since Quimimpex 1.0
 */
function quimimpex_custom_mime_types( $mime_types ){
	$mime_types['svg'] = 'image/svg+xml';
	return $mime_types;
}
add_filter( 'upload_mimes', 'quimimpex_custom_mime_types' );
?>
