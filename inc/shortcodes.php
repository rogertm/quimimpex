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
 * Shortcode [qm_documents]
 * Behavior: [qm_documents]
 *
 * @since Quimimpex 1.0
 */
function quimimpex_shortcode_documents(){
	$args = array(
		'post_type'			=> 'qm-document',
		'posts_per_page'	=> -1,
		'post_status'		=> 'publish',
		'meta_key'			=> 'qm_document_url',
	);
	$documents = get_posts( $args );

	$docs = '<ul class="list-group list-group-flush">';
	foreach( $documents as $document ) :
		$doc_url = get_post_meta( $document->ID, 'qm_document_url', true );
		$docs .= '<li class="list-group-item" id="document-'. $document->ID .'">';
		$docs .= 	$document->post_title;
		$docs .= 		'<a href="'. $doc_url .'" class="float-right ml-3" download><i class="icomoon-cloud-download"></i></a>';
		$docs .= 		'<a href="'. $doc_url .'" class="float-right" target="_blanck"><i class="icomoon-eye"></i></a>';
		$docs .= '</li>';
	endforeach;
	$docs .= '</ul>';
	return $docs;
}
add_shortcode( 'qm_documents', 'quimimpex_shortcode_documents' );

/**
 * Shortcode [qm_contact_form]
 * Behavior: [qm_contact_form line=""]
 * 0. line: 	Required. Default value: "empty". Options: "export", "import"
 *
 * @since Quimimpex 1.0
 */
function quimimpex_shortcode_contact_form( $atts, $content = null ){
	extract( shortcode_atts( array(
		'line'	=> null,
	), $atts ) );

	if ( ! $line )
		return;

	if ( $line != 'import' && $line != 'export' ) :
		if ( current_user_can( 'manage_options' ) ) :
			return '<p class="text-danger">'. __( 'An error has occurred with the <code>qm_contact_form</code> shortcode. Please review the <code>line</code> parameter', 'quimimpex' ) .'</p>';
		endif;
		return;
	endif;

	global $post;
	$args = array(
		'taxonomy'		=> 'qm-'. $line .'-line',
		'fields'		=> 'id=>name',
	);
	$lines = get_terms( $args );

	$taxonomy = get_taxonomy( 'qm-'. $line .'-line' );

	$do_action 	= ( isset( $_GET['do_action'] ) && ! empty( $_GET['do_action'] ) ) ? $_GET['do_action'] : null;
	$status 	= ( isset( $_GET['status'] ) && ! empty( $_GET['status'] ) ) ? $_GET['status'] : null;

	switch ( $do_action ) :
		case 'error':
			$class = 'danger';
			$label = __( 'Error', 'quimimpex' );
			break;
		case 'success':
			$class = 'success';
			$label = __( 'Success', 'quimimpex' );
			break;
		default:
			$class = null;
			$label = null;
			break;
	endswitch;

	switch ( $status ) :
		case 'bad-request':
			$msg = __( 'An error has occurred. Refresh your page and try again', 'quimimpex' );
			break;
		case 'empty-products':
			$msg = __( 'You should select at least one product from the list', 'quimimpex' );
			break;
		case 'empty-author':
			$msg = __( 'You should specify your name', 'quimimpex' );
			break;
		case 'empty-email':
			$msg = __( 'You should specify your email', 'quimimpex' );
			break;
		case 'requested':
			$msg = __( 'Your massage has been send successfully', 'quimimpex' );
			break;
		default:
			$msg = null;
			break;
	endswitch;

	if ( $do_action && $status ) :
		$alert 	= '<div class="alert alert-'. $class .' alert-dismissible fade show" role="alert">';
		$alert .= 	'<strong>'. $label .'</strong>';
		$alert .= 	'<p>'. $msg .'</p>';
		$alert .= 	'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		$alert .= '</div>';
	else :
		$alert  = null;
	endif;

	$form  = '<form id="qm-contact-form" method="post">';
	$form .= 	$alert;
	$form .= 	wp_nonce_field( 'qm_contact_form_attr', 'qm_contact_form_field' );
	$form .= 	'<div class="form-group">';
	$form .= 		'<label for="qm-select-line">'. $taxonomy->label .'</label>';
	$form .= 		'<select id="qm-select-line" class="custom-select" name="qm_select_line">';
						$form .= '<option value="0">'. sprintf( __( '&mdash; Select %s &mdash;', 'quimimpex' ), $taxonomy->labels->singular_name ) .'</option>';
					foreach ( $lines as $id => $name ) :
						$form .= '<option value="'. $id .'">'. $name .'</option>';
					endforeach;
	$form .= 		'</select>';
	$form .= 	'</div>';
	$form .= 	'<div class="row">';
	$form .= 		'<div class="'. t_em_grid( 6 ) .'">';
	$form .=			'<div class="card">';
	$form .=				'<div class="card-body">';
	$form .=					'<h6 class="card-title">'. __( 'Some Title', 'quimimpex' ) .'</h6>';
	$form .=				'</div>';
	$form .=				'<ul id="qm-list-products" class="list-group list-group-flush" style="height:15rem; overflow:auto;"></ul>';
	$form .=			'</div>';
	$form .= 		'</div>';
	$form .= 		'<div class="'. t_em_grid( 6 ) .'">';
	$form .=			'<div class="card">';
	$form .=				'<div class="card-body">';
	$form .=					'<h6 class="card-title">'. __( 'Some Title', 'quimimpex' ) .'</h6>';
	$form .=				'</div>';
	$form .=				'<ul id="qm-list-selected-products" class="list-group list-group-flush" style="height:15rem; overflow:auto;"></ul>';
	$form .=			'</div>';
	$form .= 		'</div>';
	$form .= 	'</div>';
	$form .= 	'<div class="form-group">';
	$form .= 		'<label for="qm-user-name">'. __( 'Your Name', 'quimimpex' ) .'</label>';
	$form .= 		'<input type="text" id="qm-user-name" class="form-control" name="qm_comment_author">';
	$form .= 	'</div>';
	$form .= 	'<div class="form-group">';
	$form .= 		'<label for="qm-user-email">'. __( 'Email', 'quimimpex' ) .'</label>';
	$form .= 		'<input type="email" id="qm-user-email" class="form-control" name="qm_comment_author_email">';
	$form .= 	'</div>';
	$form .= 	'<div class="form-group">';
	$form .= 		'<label for="qm-comment">'. __( 'Leave a comment', 'quimimpex' ) .'</label>';
	$form .=		'<textarea id="qm-comment" class="form-control" rows="5" name="qm_comment_content"></textarea>';
	$form .= 	'</div>';
	$form .= 	'<input type="hidden" name="qm_product_cpt" value="qm-'. $line .'-product">';
	$form .= 	'<input type="hidden" name="qm_product_tax" value="qm-'. $line .'-line">';
	$form .= 	'<input type="hidden" name="qm_post_id" value="'. $post->ID .'">';
	$form .= 	'<button type="submit" class="btn btn-primary" name="qm_submit_contact_form">'. __( 'Send Request', 'quimimpex' ) .'</button>';
	$form .= '</form>';

	return $form;
}
add_shortcode( 'qm_contact_form', 'quimimpex_shortcode_contact_form' );

/**
 * Shortcode [qm_checkin]
 * Behavior: [qm_checkin]
 *
 * @since Quimimpex 1.0
 */
function quimimpex_shortcode_checkin(){
	$checked_products = ( isset( $_SESSION['qm_checkin_products'] ) ) ? $_SESSION['qm_checkin_products'] : null;
	if ( ! isset( $checked_products ) ) :
		$alert 	= '<div class="alert alert-warning" role="alert">';
		$alert .= 	'<strong>'. __( 'Warning', 'quimimpex' ) .'</strong>';
		$alert .= 	'<p>'. __( 'You have not selected any product yet', 'quimimpex' ) .'</p>';
		$alert .= '</div>';
		return $alert;
	endif;

	global $post;
	$do_action 	= ( isset( $_GET['do_action'] ) && ! empty( $_GET['do_action'] ) ) ? $_GET['do_action'] : null;
	$status 	= ( isset( $_GET['status'] ) && ! empty( $_GET['status'] ) ) ? $_GET['status'] : null;

	switch ( $do_action ) :
		case 'error':
			$class = 'danger';
			$label = __( 'Error', 'quimimpex' );
			break;
		case 'success':
			$class = 'success';
			$label = __( 'Success', 'quimimpex' );
			break;
		default:
			$class = null;
			$label = null;
			break;
	endswitch;

	switch ( $status ) :
		case 'empty-products':
			$msg = __( 'You should select at least one product from the list', 'quimimpex' );
			break;
		case 'empty-author':
			$msg = __( 'You should specify your name', 'quimimpex' );
			break;
		case 'empty-email':
			$msg = __( 'You should specify your email', 'quimimpex' );
			break;
		case 'requested':
			$msg = __( 'Your massage has been send successfully', 'quimimpex' );
			break;
		default:
			$msg = null;
			break;
	endswitch;

	if ( $do_action && $status ) :
		$alert 	= '<div class="alert alert-'. $class .' alert-dismissible fade show" role="alert">';
		$alert .= 	'<strong>'. $label .'</strong>';
		$alert .= 	'<p>'. $msg .'</p>';
		$alert .= 	'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		$alert .= '</div>';
	else :
		$alert  = null;
	endif;

	$args = array(
		'post_type'			=> array( 'qm-export-product', 'qm-import-product' ),
		'posts_per_page'	=> -1,
		'post__in'			=> $checked_products,
	);
	$products = get_posts( $args );

	$output  = '<form id="qm-checkin-form" method="post">';
	$output  	.= $alert;
	$output  	.= wp_nonce_field( 'qm_checkin_form_attr', 'qm_checkin_form_field' );
	$output  	.= '<h3 class="h4">'. __( 'List of products', 'quimimpex' ) .'</h3>';
	$output  	.= '<div id="qm-checkin-accordion" class="accordion mb-3">';
	foreach ( $products as $product ) :
		$output .= 	'<div id="checkin-product-'. $product->ID .'" class="card mb-0">';
		$output .=	 	'<div id="product-heading-'. $product->ID .'" class="card-header">';
		$output .=	 		'<h5 class="mb-0">';
		$output .=	 			'<button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse-product-'. $product->ID .'" aria-expanded="false" aria-controls="collapse-product-'. $product->ID .'">';
		$output .=	 				$product->post_title;
		$output .=	 			'</button>';
		$output .=	 			'<a href="#" class="delete-checkin-product border float-right m-1 text-danger" data-target="#checkin-product-'. $product->ID .'" data-product-id="'. $product->ID .'"><i class="icomoon-cross"></i></a>';
		$output .=	 		'</h5>';
		$output .=	 	'</div>';
		$output .=	 	'<div id="collapse-product-'. $product->ID .'" class="collapse" aria-labelledby="product-heading-'. $product->ID .'" data-parent="#qm-checkin-accordion">';
		$output .=	 		'<div class="card-body">';
		$output .=	 			t_em_get_post_excerpt( $product->ID, false );
		$output .=	 		'</div>';
		$output .= 			'<input type="hidden" name="qm_checkin_product[]" value="'. $product->ID .'">';
		$output .=	 	'</div>';
		$output .= '</div>';
	endforeach;
	$output 	.= 	'</div>';
	$output 	.= 	'<div class="form-group">';
	$output 	.= 		'<label for="qm-user-name">'. __( 'Your Name', 'quimimpex' ) .'</label>';
	$output 	.= 		'<input type="text" id="qm-user-name" class="form-control" name="qm_comment_author">';
	$output 	.= 	'</div>';
	$output 	.= 	'<div class="form-group">';
	$output 	.= 		'<label for="qm-user-email">'. __( 'Email', 'quimimpex' ) .'</label>';
	$output 	.= 		'<input type="email" id="qm-user-email" class="form-control" name="qm_comment_author_email">';
	$output 	.= 	'</div>';
	$output 	.= 	'<div class="form-group">';
	$output 	.= 		'<label for="qm-comment">'. __( 'Leave a comment', 'quimimpex' ) .'</label>';
	$output 	.=		'<textarea id="qm-comment" class="form-control" rows="5" name="qm_comment_content"></textarea>';
	$output 	.= 	'</div>';
	$output 	.= 	'<button type="submit" class="btn btn-primary" name="qm_submit_contact_form">'. __( 'Send Request', 'quimimpex' ) .'</button>';
	$output 	.= 	'<input type="hidden" name="qm_post_id" value="'. $post->ID .'">';
	$output .= '</form>';
	return $output;
}
add_shortcode( 'qm_checkin', 'quimimpex_shortcode_checkin' );

/**
 * Shortcode [qm_executives]
 * Behavior: [qm_executives]
 *
 * @since Quimimpex 1.0
 */
function quimimpex_shortcode_executives(){
	$args = array(
		'post_type'			=> 'qm-executive',
		'posts_per_page'	=> -1,
		'meta_key'			=> '_thumbnail_id',
		'orderby'			=> 'menu_order',
		'order'				=> 'ASC',
	);

	$executives = get_posts( $args );
	if ( $executives ) :
		$output = '<section id="qm-executives">';
		$output .=	'<h3 class="h1">'. __( 'Executives', 'quimimpex' ) .'</h3>';
		$output .=	'<div class="row row-cols-3">';
		foreach ( $executives as $executive ) :
			$output .= '<div class="col mb-4">';
			$output .= 		'<div class="card h-100 text-center">';
			$output .=			'<img src="'. t_em_image_resize( 600, 600, get_post_meta( $executive->ID, '_thumbnail_id', true ) ) .'" class="card-img-top">';
			$output .=			'<div class="card-body">';
			$output .=				'<h5 class="card-title">'. $executive->post_title .'</h5>';
			$output .=				'<h6 class="card-subtitle mb-2 text-muted">'. get_post_meta( $executive->ID, 'qm_executive_position', true ) .'</h6>';
			$output .=				'<hr>';
			$output .=				'<a href="tel:'. get_post_meta( $executive->ID, 'qm_executive_phone', true ) .'" class="d-block">';
			$output .=					'<i class="icomoon-phone mr-1"></i>';
			$output .=					get_post_meta( $executive->ID, 'qm_executive_phone', true );
			$output .=				'</a>';
			$output .=				'<a href="tel:'. get_post_meta( $executive->ID, 'qm_executive_email', true ) .'" class="d-block">';
			$output .=					'<i class="icomoon-mail mr-1"></i>';
			$output .=					get_post_meta( $executive->ID, 'qm_executive_email', true );
			$output .=				'</a>';
			$output .=			'</div>';
			$output .=		'</div>';
			$output .=	'</div>';
		endforeach;
		$output .=	'</div>';
		$output .=	'</section>';
		return $output;
	endif;
}
add_shortcode( 'qm_executives', 'quimimpex_shortcode_executives' );

/**
 * Shortcode [qm_line]
 * Behavior [qm_line line=""]
 *
 * @since Quimimpex 1.0
 */
function quimimpex_shortcode_line( $atts, $content = null ){
	extract( shortcode_atts( array(
		'line'	=> null,
	), $atts ) );

	if ( ! $line )
		return;

	if ( $line != 'import' && $line != 'export' ) :
		if ( current_user_can( 'manage_options' ) ) :
			return '<p class="text-danger">'. __( 'An error has occurred with the <code>qm_contact_form</code> shortcode. Please review the <code>line</code> parameter', 'quimimpex' ) .'</p>';
		endif;
		return;
	endif;

	$args = array(
		'taxonomy'	=> 'qm-'. $line .'-line',
	);
	$lines = get_terms( $args );

	if ( $lines ) :
		$taxonomy = get_taxonomy( 'qm-'. $line .'-line' );

		$output  = '<section id="qm-'. $line .'-line">';
		$output .=	'<h3 class="h1">'. $taxonomy->labels->singular_name .'</h3>';
		$output .=	'<div class="row row-cols-3">';
		foreach ( $lines as $line ) :
			$output .= '<div class="col mb-4">';
			$output .= 		'<div class="card h-100 text-center">';
			$output .=			'<div class="card-body">';
			$output .=				'<span class="card-icon rounded-circle d-flex justify-content-center h2">';
			$output .=					'<i class="'. get_term_meta( $line->term_id, 'qm_taxonomy_icon', true ) .'"></i>';
			$output .=				'</span>';
			$output .=				'<h5 class="card-title">'. $line->name .'</h5>';
			$output .=				'<p class="card-text">'. $line->description .'</p>';
			$output .=			'</div>';
			$output .=			'<div class="card-footer">';
			$output .=				'<a href="'. get_term_link( $line->term_id ) .'" class="btn btn-primary">'. __( 'See products', 'quimimpex' ) .'</a>';
			$output .=			'</div>';
			$output .=		'</div>';
			$output .=	'</div>';
		endforeach;
		$output .=	'</div>';
		$output .= '</section>';

		return $output;
	endif;

}
add_shortcode( 'qm_line', 'quimimpex_shortcode_line' );
?>
