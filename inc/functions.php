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
 * Register users through Newsletter Subscriber widget form
 *
 * @since Quimimpex 1.0
 */
function quimimpex_register_subscribers(){
	$nonce = check_ajax_referer( '_qmnonce', '_qmnonce', false );
	if ( ! $nonce ) :
		$status	= 'error';
		$msg 	= __( 'Unknown error. Please try again', 'quimimpex' );

		$response = array(
			'status'	=> $status,
			'msg'		=> $msg,
		);
		return wp_send_json( $response );
	endif;

	$email = $_POST['email'];

	if ( ! isset( $email ) || empty( $email ) ) :
		$status	= 'error';
		$msg 	= __( 'The email is required', 'quimimpex' );
	elseif ( ! is_email( $email ) ) :
		$status	= 'error';
		$msg 	= __( 'You should enter a valid email address', 'quimimpex' );
	elseif ( email_exists( $email ) ) :
		$status	= 'error';
		$msg 	= __( 'That email address already exists', 'quimimpex' );
	else :
		$status	= 'success';
		$msg 	= __( 'Your email has been registered successfully', 'quimimpex' );

		$password 	= wp_generate_password();
		wp_create_user( $email, $password, $email );

		// Notify via email
		$sitename 	= wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		$content	= __(
		'Hello.

Your subscription to our newsletter has been successfully complete.

Thank you.

###SITENAME### team.
###SITEURL###' );

		$content 	= str_replace( '###SITENAME###', $sitename, $content );
		$content 	= str_replace( '###SITEURL###', home_url(), $content );
		wp_mail( $email, sprintf( __( '[%s] Newsletter Subscription' ), $sitename ), $content );

	endif;

	$response = array(
		'status'	=> $status,
		'msg'		=> $msg,
	);
	return wp_send_json( $response );
}
add_action( 'wp_ajax_email_subscriber', 'quimimpex_register_subscribers' );
add_action( 'wp_ajax_nopriv_email_subscriber', 'quimimpex_register_subscribers' );

/**
 * Fill products list in Contact Form
 *
 * @since Quimimpex 1.0
 */
function quimimpex_ajax_get_products(){
	$nonce = check_ajax_referer( '_qmnonce', '_qmnonce', false );
	if ( ! $nonce ) :
		$status	= 'bad_request';
		$msg 	= __( 'Unknown error. Refresh your page and try again', 'quimimpex' );

		$response = array(
			'status'	=> $status,
			'msg'		=> $msg,
		);
		return wp_send_json( $response );
	endif;

	$cpt 		= $_POST['cpt'];
	$tax 		= $_POST['tax'];
	$term_id 	= $_POST['term_id'];

	if ( $term_id == 0 ) :
		$status	= 'empty';
		$msg 	= null;

		$response = array(
			'status'	=> $status,
			'msg'		=> $msg,
		);
		return wp_send_json( $response );
	endif;

	$args = array(
		'post_type'			=> $cpt,
		'posts_per_page'	=> -1,
		'tax_query'			=> array(
			array(
				'taxonomy'	=> $tax,
				'field'		=> 'id',
				'terms'		=> array( $term_id ),
			),
		),
	);
	$products = get_posts( $args );
	if ( $products ) :
		$response = array(
			'status'	=> 'success',
			'data'		=> array(),
			'msg'		=> __( 'Success', 'quimimpex' ),
		);
		foreach ( $products as $product ) :
			$data = array(
				'id'	=> $product->ID,
				'title'	=> $product->post_title,
			);
			array_push( $response['data'], $data );
		endforeach;
	else :
		$response = array(
			'status'	=> 'error',
			'data'		=> null,
			'msg'		=> __( 'Unknown error. Please try again', 'quimimpex' ),
		);
	endif;
	return wp_send_json( $response );
}
add_action( 'wp_ajax_contact_form', 'quimimpex_ajax_get_products' );
add_action( 'wp_ajax_nopriv_contact_form', 'quimimpex_ajax_get_products' );

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
?>
