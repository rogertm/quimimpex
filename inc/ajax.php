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

/** Load the WordPress Core */
$path = preg_replace( '/wp-content.*$/', '', __DIR__ );
require_once( $path .'wp-load.php' );

if ( ! isset( $_POST['qm_newsletter_subscriber_field'] )
		|| ! wp_verify_nonce( $_POST['qm_newsletter_subscriber_field'], 'qm_newsletter_subscriber_attr' ) ) :
	$response = quimimpex_register_subscribers( $_POST['email'] );
endif;
?>
