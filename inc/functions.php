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
	// Check if the user intended to change this value.
	if ( ! isset( $_POST['qm_newsletter_subscriber_field'] )
			|| ! wp_verify_nonce( $_POST['qm_newsletter_subscriber_field'], 'qm_newsletter_subscriber_attr' ) )
		return;

	if ( ! isset( $_POST['qm_subscriber_email'] )
			|| empty( $_POST['qm_subscriber_email'] )
			|| ! is_email( $_POST['qm_subscriber_email'] )
			|| email_exists( $email ) )
		return;

	$email 		= $_POST['qm_subscriber_email'];
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

	wp_redirect( home_url() );
	exit;
}
add_action( 'init', 'quimimpex_register_subscribers' );
?>
