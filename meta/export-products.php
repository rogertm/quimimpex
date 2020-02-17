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
 * Export Products fields
 * @return array 	Array of fields
 *
 * @since Quimimpex 1.0
 */
function quimimpex_export_product_data_fields(){
	$fields = array(
		'external_url'	=> array(
			'label'		=> __( 'Code', 'quimimpex' ),
			'meta'		=> 'qm_product_code',
			'type'		=> 'text',
		),
		'code'			=> array(
			'label'		=> __( 'External URL', 'quimimpex' ),
			'meta'		=> 'qm_product_external_url',
			'type'		=> 'text',
		),
	);
	return apply_filters( 'quimimpex_export_product_data_fields', $fields );
}

/**
 * Export Product data callback
 *
 * @since Quimimpex 1.0
 */
function quimimpex_export_product_data_callback( $post ){
	wp_nonce_field( 'qm_export_attr', 'qm_export_field' );
	$args = array(
		'post_type'			=> 'qm-company',
		'posts_per_page'	=> -1,
		'post_status'		=> 'publish',
	);
	$companies = get_posts( $args );
	$product_company = get_post_meta( $post->ID, 'qm_product_company', true );
?>
	<h4><label for="qm_product_company"><?php _e( 'Select the Company this product belong to', 'quimimpex' ) ?></label></h4>
	<select id="qm_product_company" name="qm_product_company">
		<option value=""><?php _e( '&mdash; Select Company &mdash;', 'quimimpex' ) ?></option>
<?php foreach ( $companies as $company ) : ?>
		<option value="<?php echo $company->ID ?>" <?php selected( $product_company, $company->ID, true ) ?>><?php echo $company->post_title ?></option>
<?php endforeach; ?>
	</select>
<?php
	$fields = quimimpex_export_product_data_fields();
	foreach ( $fields as $key => $value ) :
		$meta_value = get_post_meta( $post->ID, $value['meta'], true );
?>
	<h4><label for="<?php echo $value['meta'] ?>"><?php echo $value['label'] ?></label></h4>
	<input id="<?php echo $value['meta'] ?>" type="<?php echo $value['type'] ?>" name="<?php echo $value['meta'] ?>" value="<?php echo $meta_value ?>">
<?php
	endforeach;
}

/**
 * Save the data
 *
 * @since Quimimpex 1.0
 */
function quimimpex_save_export_product_meta( $post_id ){
	// Check if the current user is authorized to do this action.
	if ( ! current_user_can( 'edit_posts' ) )
		return;
	// Check if the user intended to change this value.
	if ( ! isset( $_POST['qm_export_field'] ) || ! wp_verify_nonce( $_POST['qm_export_field'], 'qm_export_attr' ) )
		return;
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;

	// Save the data
	if ( isset( $_POST['qm_product_company'] ) && $_POST['qm_product_company'] ) :
		update_post_meta( $post_id, 'qm_product_company', $_POST['qm_product_company'] );
	else :
		delete_post_meta( $post_id, 'qm_product_company' );
	endif;

	$fields = quimimpex_export_product_data_fields();
	foreach ( $fields as $key => $value ) :
		if ( isset( $_POST[$value['meta']] ) && $_POST[$value['meta']] ) :
			update_post_meta( $post_id, $value['meta'], $_POST[$value['meta']] );
		else :
			delete_post_meta( $post_id, $value['meta'] );
		endif;
	endforeach;
}
add_action( 'save_post', 'quimimpex_save_export_product_meta' );
?>
