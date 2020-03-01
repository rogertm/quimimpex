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
 * Document fields
 * @return array 	Array of fields
 *
 * @since Quimimpex 1.0
 */
function quimimpex_document_data_fields(){
	$fields = array(
		'document_url'	=> array(
			'label'		=> __( 'Document URL', 'quimimpex' ),
			'meta'		=> 'qm_document_url',
			'type'		=> 'url',
			'upload'	=> true,
			'attr'		=> array(
								'class' 	=> 'media-url',
								'required'	=> 'required',
							),
		),
		'document_id'	=> array(
			'label'		=> null,
			'meta'		=> 'qm_document_id',
			'type'		=> 'hidden',
			'upload'	=> null,
			'attr'		=> array(
								'class' => 'media-id',
								'required'	=> null,
							),
		),
	);
	return apply_filters( 'quimimpex_document_data_fields', $fields );
}

/**
 * Contact data callback
 *
 * @since Quimimpex 1.0
 */
function quimimpex_document_data_callback( $post ){
	wp_nonce_field( 'qm_document_attr', 'qm_document_field' );

	$fields = quimimpex_document_data_fields();
	foreach ( $fields as $key => $value ) :
		$meta_value = get_post_meta( $post->ID, $value['meta'], true );
?>
	<h4><label for="<?php echo $value['meta'] ?>"><?php echo $value['label'] ?></label></h4>
	<input id="<?php echo $value['meta'] ?>" class="<?php echo $value['attr']['class'] ?>" type="<?php echo $value['type'] ?>" name="<?php echo $value['meta'] ?>" value="<?php echo $meta_value ?>" required="<?php echo $value['attr']['required'] ?>">
	<?php if ( $value['upload'] ) : ?>
		<a href="#" class="button media-selector"><?php _e( 'Upload Document', 'quimimpex' ) ?></a>
	<?php endif; ?>
<?php
	endforeach;
}

/**
 * Save the data
 *
 * @since Quimimpex 1.0
 */
function quimimpex_save_document_meta( $post_id ){
	// Check if the current user is authorized to do this action.
	if ( ! current_user_can( 'edit_posts' ) )
		return;
	// Check if the user intended to change this value.
	if ( ! isset( $_POST['qm_document_field'] ) || ! wp_verify_nonce( $_POST['qm_document_field'], 'qm_document_attr' ) )
		return;
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;

	// Save the data
	$fields = quimimpex_document_data_fields();
	foreach ( $fields as $key => $value ) :
		if ( isset( $_POST[$value['meta']] ) && $_POST[$value['meta']] ) :
			update_post_meta( $post_id, $value['meta'], $_POST[$value['meta']] );
		else :
			delete_post_meta( $post_id, $value['meta'] );
		endif;
	endforeach;
}
add_action( 'save_post', 'quimimpex_save_document_meta' );
?>
