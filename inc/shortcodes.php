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
?>
