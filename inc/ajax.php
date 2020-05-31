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
		'counter'	=> count( $_SESSION['qm_checkin_products'] ),
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
	$counter = ( ! empty( $products ) ) ? count( $products ) : 0;

	$response = array(
		'status'	=> 'success',
		'counter'	=> $counter,
		'session'	=> $products,
	);
	return wp_send_json( $response );
}
add_action( 'wp_ajax_remove_checkin_product', 'quimimpex_ajax_remove_checked_product' );
add_action( 'wp_ajax_nopriv_remove_checkin_product', 'quimimpex_ajax_remove_checked_product' );

/**
 * Render modal
 *
 * @since Quimimpex 1.0
 */
function quimimpex_ajax_modal(){
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

	$post_id		= $_POST['post_id'];
	$title 			= get_the_title( $post_id );
	$attachment_id 	= get_post_thumbnail_id( $post_id );
	$thumbnail		= '<img src="'. t_em_image_resize( 700, 460, $attachment_id ) .'" class="m-0">';
	$contact_id		= get_post_meta( $post_id, 'qm_product_contact', true );
	$land_phone		= ( $contact_id && get_post_meta( $contact_id, 'qm_contact_land_phones', true ) )
						? '<span class="py-1 d-block"><i class="icomoon-phone mr-3 text-primary"></i> <a href="tel:'. get_post_meta( $contact_id, 'qm_contact_land_phones', true ) .'" class="modal-contact-link">'. get_post_meta( $contact_id, 'qm_contact_land_phones', true ) .'</a></span>'
						: null;
	$mobil_phone	= ( $contact_id && get_post_meta( $contact_id, 'qm_contact_mobil_phones', true ) )
						? '<span class="py-1 d-block"><i class="icomoon-phone mr-3 text-primary"></i> <a href="tel:'. get_post_meta( $contact_id, 'qm_contact_mobil_phones', true ) .'" class="modal-contact-link">'. get_post_meta( $contact_id, 'qm_contact_mobil_phones', true ) .'</a></span>'
						: null;
	$email			= ( $contact_id && get_post_meta( $contact_id, 'qm_contact_email', true ) )
						? '<span class="py-1 d-block"><i class="icomoon-mail mr-3 text-primary"></i> <a href="mailto:'. get_post_meta( $contact_id, 'qm_contact_email', true ) .'" class="modal-contact-link">'. get_post_meta( $contact_id, 'qm_contact_email', true ) .'</a></span>'
						: null;
	$request_email	= ( $contact_id && get_post_meta( $contact_id, 'qm_contact_request_email', true ) )
						? '<span class="py-1 d-block"><i class="icomoon-mail mr-3 text-primary"></i> <a href="mailto:'. get_post_meta( $contact_id, 'qm_contact_request_email', true ) .'" class="modal-contact-link">'. get_post_meta( $contact_id, 'qm_contact_request_email', true ) .'</a></span>'
						: null;
	$checkin 		= '<a href="#" class="btn btn-light rounded-circle mr-3 qm-checkin-product" data-product-id="'. $post_id .'" aria-label="'. __( 'Checkin', 'quimimpex' ) .'"><i class="icomoon-shopping-cart text-muted h6 mb-0"></i></a>';
	$data_sheet		= ( get_post_meta( $post_id, 'qm_data_sheet_url' ) )
						? '<a href="'. get_post_meta( $post_id, 'qm_data_sheet_url', true ) .'" class="btn btn-light rounded-circle mr-3" aria-label="'. __( 'Data sheet', 'quimimpex' ) .'" download><i class="icomoon-text-document text-muted h6 mb-0"></i></a>'
						: null;

	$response = array(
		'status'		=> 'success',
		'title'			=> $title,
		'thumbnail'		=> $thumbnail,
		'title_description' => __( 'Product Description', 'quimimpex' ),
		'title_contact' => __( 'Contact information', 'quimimpex' ),
		'content'		=> t_em_wrap_paragraph( get_post_field( 'post_content', $post_id ) ),
		'checkin'		=> $checkin,
		'data_sheet'	=> $data_sheet,
		'land_phone' 	=> $land_phone,
		'mobil_phone' 	=> $mobil_phone,
		'email' 		=> $email,
		'request_email' => $request_email,
	);
	return wp_send_json( $response );
}
add_action( 'wp_ajax_qm_modal', 'quimimpex_ajax_modal' );
add_action( 'wp_ajax_nopriv_qm_modal', 'quimimpex_ajax_modal' );
?>
