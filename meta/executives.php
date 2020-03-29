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
 * Executive fields
 * @return array 	Array of fields
 *
 * @since Quimimpex 1.0
 */
function quimimpex_executive_data_fields(){
	$fields = array(
		'position'	=> array(
			'label'	=> __( 'Position', 'quimimpex' ),
			'meta'	=> 'qm_executive_position',
			'type'	=> 'text',
		),
		'phone'	=> array(
			'label'	=> __( 'Phone Number', 'quimimpex' ),
			'meta'	=> 'qm_executive_phone',
			'type'	=> 'text',
		),
		'email'	=> array(
			'label'	=> __( 'Email', 'quimimpex' ),
			'meta'	=> 'qm_executive_email',
			'type'	=> 'email',
		),
	);
	return apply_filters( 'quimimpex_executive_data_fields', $fields );
}

/**
 * Executive data callback
 *
 * @since Quimimpex 1.0
 */
function quimimpex_executive_data_callback( $post ){
	wp_nonce_field( 'qm_executive_attr', 'qm_executive_field' );

	$fields = quimimpex_executive_data_fields();
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
function quimimpex_save_executive_meta( $post_id ){
	// Check if the current user is authorized to do this action.
	if ( ! current_user_can( 'edit_posts' ) )
		return;
	// Check if the user intended to change this value.
	if ( ! isset( $_POST['qm_executive_field'] ) || ! wp_verify_nonce( $_POST['qm_executive_field'], 'qm_executive_attr' ) )
		return;
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;

	// Save the data
	$fields = quimimpex_executive_data_fields();
	foreach ( $fields as $key => $value ) :
		if ( isset( $_POST[$value['meta']] ) && $_POST[$value['meta']] ) :
			update_post_meta( $post_id, $value['meta'], $_POST[$value['meta']] );
		else :
			delete_post_meta( $post_id, $value['meta'] );
		endif;
	endforeach;
}
add_action( 'save_post', 'quimimpex_save_executive_meta' );
?>
