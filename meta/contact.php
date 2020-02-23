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
 * Contact fields
 * @return array 	Array of fields
 *
 * @since Quimimpex 1.0
 */
function quimimpex_contact_data_fields(){
	$fields = array(
		'land_phone'	=> array(
			'label'		=> __( 'Land Phones', 'quimimpex' ),
			'meta'		=> 'qm_contact_land_phones',
			'type'		=> 'text',
		),
		'mobil_phone'	=> array(
			'label'		=> __( 'Mobil Phones', 'quimimpex' ),
			'meta'		=> 'qm_contact_mobil_phones',
			'type'		=> 'text',
		),
		'contact_email'	=> array(
			'label'		=> __( 'Contact Email', 'quimimpex' ),
			'meta'		=> 'qm_contact_email_phones',
			'type'		=> 'email',
		),
		'request_email'	=> array(
			'label'		=> __( 'Email to send requests and services', 'quimimpex' ),
			'meta'		=> 'qm_contact_request_email_phones',
			'type'		=> 'email',
		),
	);
	return apply_filters( 'quimimpex_contact_data_fields', $fields );
}

/**
 * Contact data callback
 *
 * @since Quimimpex 1.0
 */
function quimimpex_contact_data_callback( $post ){
	wp_nonce_field( 'qm_contact_attr', 'qm_contact_field' );

	$fields = quimimpex_contact_data_fields();
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
function quimimpex_save_contact_meta( $post_id ){
	// Check if the current user is authorized to do this action.
	if ( ! current_user_can( 'edit_posts' ) )
		return;
	// Check if the user intended to change this value.
	if ( ! isset( $_POST['qm_contact_field'] ) || ! wp_verify_nonce( $_POST['qm_contact_field'], 'qm_contact_attr' ) )
		return;
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;

	// Save the data
	$fields = quimimpex_contact_data_fields();
	foreach ( $fields as $key => $value ) :
		if ( isset( $_POST[$value['meta']] ) && $_POST[$value['meta']] ) :
			update_post_meta( $post_id, $value['meta'], $_POST[$value['meta']] );
		else :
			delete_post_meta( $post_id, $value['meta'] );
		endif;
	endforeach;
}
add_action( 'save_post', 'quimimpex_save_contact_meta' );
?>
