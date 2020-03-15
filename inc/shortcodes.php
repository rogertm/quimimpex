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

	$args = array(
		'taxonomy'		=> 'qm-'. $line .'-line',
		'fields'		=> 'id=>name',
	);
	$lines = get_terms( $args );

	$taxonomy = get_taxonomy( 'qm-'. $line .'-line' );

	// echo '<pre>'. print_r( $taxonomy, true ) .'</pre>';

	$form = '<form id="qm-contact-form" method="post">';
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
	$form .= 		'<label for="qm-comment">'. __( 'Leave a comment', 'quimimpex' ) .'</label>';
	$form .=		'<textarea id="qm-comment" class="form-control" rows="5"></textarea>';
	$form .= 	'</div>';
	$form .= 	'<input type="hidden" name="qm_product_cpt" value="qm-'. $line .'-product">';
	$form .= 	'<input type="hidden" name="qm_product_tax" value="qm-'. $line .'-line">';
	$form .= 	'<button type="submit" class="btn btn-primary" name="qm_submit_contact_form">'. __( 'Send Request', 'quimimpex' ) .'</button>';
	$form .= '</form>';

	return $form;
}
add_shortcode( 'qm_contact_form', 'quimimpex_shortcode_contact_form' );
?>
