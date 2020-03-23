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
 * Register users through Newsletter Subscriber widget form
 *
 * @since Quimimpex 1.0
 */
function quimimpex_ajax_register_subscribers(){
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
add_action( 'wp_ajax_email_subscriber', 'quimimpex_ajax_register_subscribers' );
add_action( 'wp_ajax_nopriv_email_subscriber', 'quimimpex_ajax_register_subscribers' );

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
 * Add products to check in list
 *
 * @since Quimimpex 1.0
 */
function quimimpex_ajax_checkin_products(){
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

	$_SESSION['qm_checkin_products'] = ( ! empty( $_SESSION['qm_checkin_products'] ) ) ? $_SESSION['qm_checkin_products'] : array();
	$product_id = $_POST['product_id'];
	if ( ! in_array( $product_id, $_SESSION['qm_checkin_products'] ) )
		array_push( $_SESSION['qm_checkin_products'], $product_id );

	$response = array(
		'status'	=> 'success',
		'session'	=> $_SESSION['qm_checkin_products'],
	);
	return wp_send_json( $response );
}
add_action( 'wp_ajax_checkin_product', 'quimimpex_ajax_checkin_products' );
add_action( 'wp_ajax_nopriv_checkin_product', 'quimimpex_ajax_checkin_products' );

/**
 * Remove product from check in list
 *
 * @since Quimimpex 1.0
 */
function quimimpex_ajax_remove_checked_product(){
	$product_id = $_POST['product_id'];
	$products 	= $_SESSION['qm_checkin_products'];

	if ( ( $key = array_search( $product_id, $products ) ) !== false ) :
		unset( $products[$key] );
	endif;

	$_SESSION['qm_checkin_products'] = ( ! empty( $products ) ) ? $products : null;

	$response = array(
		'status'	=> 'success',
		'session'	=> $products,
	);
	return wp_send_json( $response );
}
add_action( 'wp_ajax_remove_checkin_product', 'quimimpex_ajax_remove_checked_product' );
add_action( 'wp_ajax_nopriv_remove_checkin_product', 'quimimpex_ajax_remove_checked_product' );
?>
