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

/**
 *
 */
function quimimpex_ajax_get_products(){
	$nonce = check_ajax_referer( '_qmnonce', '_qmnonce', false );
	if ( !$ nonce ) :
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
?>
