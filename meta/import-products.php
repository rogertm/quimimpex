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
 * Import Products content fields
 * @return array 	Array of fields
 *
 * @since Quimimpex 1.0
 */
function quimimpex_import_product_content_fields(){
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
	return apply_filters( 'quimimpex_import_product_content_fields', $fields );
}

/**
 * Import Products fields
 * @return array 	Array of fields
 *
 * @since Quimimpex 1.0
 */
function quimimpex_import_product_data_fields(){
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
	return apply_filters( 'quimimpex_import_product_data_fields', $fields );
}

/**
 * Import Product content callback
 *
 * @since Quimimpex 1.0
 */
function quimimpex_import_product_content_callback( $post ){
	wp_nonce_field( 'qm_import_attr', 'qm_import_field' );
	$fields = quimimpex_import_product_content_fields();

	foreach ( $fields as $key => $value ) :
		$meta_value = get_post_meta( $post->ID, $value['meta'], true );
?>
	<h4><label for="<?php echo $value['meta'] ?>"><?php echo $value['label'] ?></label></h4>
	<textarea id="<?php echo $value['meta'] ?>" name="<?php echo $value['meta'] ?>" cols="100" rows="5"><?php echo $meta_value ?></textarea>
<?php
	endforeach;
}

/**
 * Import Product data callback
 *
 * @since Quimimpex 1.0
 */
function quimimpex_import_product_data_callback( $post ){
	wp_nonce_field( 'qm_import_attr', 'qm_import_field' );
	$fields = quimimpex_import_product_data_fields();

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
function quimimpex_save_import_product_meta( $post_id ){
	// Check if the current user is authorized to do this action.
	if ( ! current_user_can( 'edit_posts' ) )
		return;
	// Check if the user intended to change this value.
	if ( ! isset( $_POST['qm_import_field'] ) || ! wp_verify_nonce( $_POST['qm_import_field'], 'qm_import_attr' ) )
		return;
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;

	// Save the data
	$fields = quimimpex_import_product_content_fields();
	foreach ( $fields as $key => $value ) :
		if ( isset( $_POST[$value['meta']] ) && $_POST[$value['meta']] ) :
			update_post_meta( $post_id, $value['meta'], $_POST[$value['meta']] );
		else :
			delete_post_meta( $post_id, $value['meta'] );
		endif;
	endforeach;

	$fields = quimimpex_import_product_data_fields();
	foreach ( $fields as $key => $value ) :
		if ( isset( $_POST[$value['meta']] ) && $_POST[$value['meta']] ) :
			update_post_meta( $post_id, $value['meta'], $_POST[$value['meta']] );
		else :
			delete_post_meta( $post_id, $value['meta'] );
		endif;
	endforeach;
}
add_action( 'save_post', 'quimimpex_save_import_product_meta' );

/**
 * Data Sheet fields
 * @return array 	Array of fields
 *
 * @since Quimimpex 1.0
 */
function quimimpex_data_sheet_data_fields(){
	$fields = array(
		'data_sheet_url'	=> array(
			'label'		=> __( 'Data Sheet URL', 'quimimpex' ),
			'meta'		=> 'qm_data_sheet_url',
			'type'		=> 'url',
			'upload'	=> true,
			'attr'		=> array(
								'class' 	=> 'media-url',
								'required'	=> 'required',
							),
		),
		'data_sheet_id'	=> array(
			'label'		=> null,
			'meta'		=> 'qm_data_sheet_id',
			'type'		=> 'hidden',
			'upload'	=> null,
			'attr'		=> array(
								'class' => 'media-id',
								'required'	=> null,
							),
		),
	);
	return apply_filters( 'quimimpex_data_sheet_data_fields', $fields );
}

/**
 * Data Sheet data callback
 *
 * @since Quimimpex 1.0
 */
function quimimpex_import_data_sheet_callback( $post ){
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
function quimimpex_save_import_data_sheet_meta( $post_id ){
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
add_action( 'save_post', 'quimimpex_save_import_data_sheet_meta' );
?>
