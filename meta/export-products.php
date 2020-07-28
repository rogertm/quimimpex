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
 * Export Products content fields
 * @return array 	Array of fields
 *
 * @since Quimimpex 1.0
 */
function quimimpex_export_product_content_fields(){
	$fields = array(
		'description'	=> array(
			'label'		=> __( 'Description', 'quimimpex' ),
			'meta'		=> 'qm_product_description',
		),
		'chemical_formula'	=> array(
			'label'		=> __( 'Chemical Formula', 'quimimpex' ),
			'meta'		=> 'qm_product_chemical_formula',
		),
		'use'			=> array(
			'label'		=> __( 'Use', 'quimimpex' ),
			'meta'		=> 'qm_product_use',
		),
		'expiration'	=> array(
			'label'		=> __( 'Expiration', 'quimimpex' ),
			'meta'		=> 'qm_product_expiration',
		),
	);
	return apply_filters( 'quimimpex_export_product_content_fields', $fields );
}

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
			'order'		=> '10',
		),
		'code'			=> array(
			'label'		=> __( 'External URL', 'quimimpex' ),
			'meta'		=> 'qm_product_external_url',
			'type'		=> 'text',
			'order'		=> '20',
		),
		'product'		=> array(
			'label'		=> __( 'Is Product', 'quimimpex' ),
			'meta'		=> 'qm_product_is_product',
			'type'		=> 'select',
			'options'	=> array(
								'1'		=> __( 'Yes', 'quimimpex' ),
								'00'	=> __( 'No', 'quimimpex' ),
							),
			'order'		=> '30',
		),
	);
	uasort( $fields, 't_em_sort_by_order' );
	return apply_filters( 'quimimpex_export_product_data_fields', $fields );
}

/**
 * Merge the companies settings into Export Products Fields
 * @return array 	Array of fields
 *
 * @since Quimimpex 1.0
 */
function quimimpex_export_products_query_fields( $custom_fields = array() ){
	$fields = array(
		'companies'		=> array(
			'label'		=> __( 'Companies', 'quimimpex' ),
			'meta'		=> 'qm_product_company',
			'type'		=> 'select',
			'options'	=> array(),
			'order'		=> '05',
		),
		'contacts'		=> array(
			'label'		=> __( 'Contacts', 'quimimpex' ),
			'meta'		=> 'qm_product_contact',
			'type'		=> 'select',
			'options'	=> array(),
			'order'		=> '06',
		),
	);

	// Companies
	$companies_args = array(
		'post_type'			=> 'qm-company',
		'posts_per_page'	=> -1,
		'post_status'		=> 'publish',
	);
	$companies = get_posts( $companies_args );

	$companies_options = array( '0' => __( '&mdash; Select Company &mdash;', 'quimimpex' ) );
	foreach ( $companies as $company ) :
		$key = array( '\''. $company->ID .'\'' => $company->post_title );
		$companies_options = array_merge( $companies_options, $key );
	endforeach;

	// Contacts
	$contact_args = array(
		'post_type'			=> 'qm-contact',
		'posts_per_page'	=> -1,
		'post_status'		=> 'publish',
	);
	$contacts = get_posts( $contact_args );

	$contacts_options = array( '0' => __( '&mdash; Select Contact &mdash;', 'quimimpex' ) );
	foreach ( $contacts as $contact ) :
		$key = array( '\''. $contact->ID .'\'' => $contact->post_title );
		$contacts_options = array_merge( $contacts_options, $key );
	endforeach;

	$fields['companies']['options'] = array_merge( $fields['companies']['options'], $companies_options );
	$fields['contacts']['options'] = array_merge( $fields['contacts']['options'], $contacts_options );
	uasort( $fields, 't_em_sort_by_order' );
	return array_merge( $custom_fields, $fields );
}
add_filter( 'quimimpex_export_product_data_fields', 'quimimpex_export_products_query_fields' );

/**
 * Export Product content callback
 *
 * @since Quimimpex 1.0
 */
function quimimpex_export_product_content_callback( $post ){
	wp_nonce_field( 'qm_export_attr', 'qm_export_field' );
	$fields = quimimpex_export_product_content_fields();

	foreach ( $fields as $key => $value ) :
		$meta_value = get_post_meta( $post->ID, $value['meta'], true );
?>
	<h4><label for="<?php echo $value['meta'] ?>"><?php echo $value['label'] ?></label></h4>
	<textarea id="<?php echo $value['meta'] ?>" name="<?php echo $value['meta'] ?>" cols="100" rows="5"><?php echo $meta_value ?></textarea>
<?php
	endforeach;
}

/**
 * Export Product data callback
 *
 * @since Quimimpex 1.0
 */
function quimimpex_export_product_data_callback( $post ){
	wp_nonce_field( 'qm_export_attr', 'qm_export_field' );
	$fields = quimimpex_export_product_data_fields();

	foreach ( $fields as $key => $value ) :
		$meta_value = get_post_meta( $post->ID, $value['meta'], true );
?>
	<h4><label for="<?php echo $value['meta'] ?>"><?php echo $value['label'] ?></label></h4>
<?php if ( $value['type'] == 'text' ) : ?>
	<input id="<?php echo $value['meta'] ?>" type="<?php echo $value['type'] ?>" name="<?php echo $value['meta'] ?>" value="<?php echo $meta_value ?>">
<?php elseif ( $value['type'] == 'select' ) : ?>
	<select id="<?php echo $value['meta'] ?>" name="<?php echo $value['meta'] ?>">
	<?php foreach ( $value['options'] as $key => $value ) : ?>
		<option value="<?php echo str_replace('\'', '', $key ) ?>" <?php selected( $meta_value, str_replace('\'', '', $key ), true ) ?>><?php echo $value ?></option>
	<?php endforeach; ?>
	</select>
<?php endif; ?>
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
	$fields = quimimpex_export_product_content_fields();
	foreach ( $fields as $key => $value ) :
		if ( isset( $_POST[$value['meta']] ) && $_POST[$value['meta']] ) :
			update_post_meta( $post_id, $value['meta'], $_POST[$value['meta']] );
		else :
			delete_post_meta( $post_id, $value['meta'] );
		endif;
	endforeach;

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

/**
 * Data Sheet data callback
 *
 * @since Quimimpex 1.0
 */
function quimimpex_export_data_sheet_callback( $post ){
	wp_nonce_field( 'qm_data_sheet_attr', 'qm_data_sheet_field' );

	$fields = quimimpex_data_sheet_data_fields();
	foreach ( $fields as $key => $value ) :
		$meta_value = get_post_meta( $post->ID, $value['meta'], true );
?>
	<h4><label for="<?php echo $value['meta'] ?>"><?php echo $value['label'] ?></label></h4>
	<input id="<?php echo $value['meta'] ?>" class="<?php echo $value['attr']['class'] ?>" type="<?php echo $value['type'] ?>" name="<?php echo $value['meta'] ?>" value="<?php echo $meta_value ?>" required="<?php echo $value['attr']['required'] ?>">
	<?php if ( $value['upload'] ) : ?>
		<a href="#" class="button media-selector"><?php _e( 'Upload Data Sheet', 'quimimpex' ) ?></a>
	<?php endif; ?>
<?php
	endforeach;
}

/**
 * Save the data
 *
 * @since Quimimpex 1.0
 */
function quimimpex_save_export_data_sheet_meta( $post_id ){
	// Check if the current user is authorized to do this action.
	if ( ! current_user_can( 'edit_posts' ) )
		return;
	// Check if the user intended to change this value.
	if ( ! isset( $_POST['qm_data_sheet_field'] ) || ! wp_verify_nonce( $_POST['qm_data_sheet_field'], 'qm_data_sheet_attr' ) )
		return;
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;

	// Save the data
	$fields = quimimpex_data_sheet_data_fields();
	foreach ( $fields as $key => $value ) :
		if ( isset( $_POST[$value['meta']] ) && $_POST[$value['meta']] ) :
			update_post_meta( $post_id, $value['meta'], $_POST[$value['meta']] );
		else :
			delete_post_meta( $post_id, $value['meta'] );
		endif;
	endforeach;
}
add_action( 'save_post', 'quimimpex_save_export_data_sheet_meta' );
?>
