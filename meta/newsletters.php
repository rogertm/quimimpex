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
 * Newsletter fields
 * @return array 	Array of fields
 *
 * @since Quimimpex 1.0
 */
function quimimpex_newsletter_data_fields(){
	$fields = array(
		'newsletter_url'	=> array(
			'label'		=> __( 'Newsletter URL', 'quimimpex' ),
			'meta'		=> 'qm_newsletter_url',
			'type'		=> 'url',
			'upload'	=> true,
			'attr'		=> array(
								'class' 	=> 'media-url',
								'required'	=> 'required',
							),
		),
		'newsletter_id'	=> array(
			'label'		=> null,
			'meta'		=> 'qm_newsletter_id',
			'type'		=> 'hidden',
			'upload'	=> null,
			'attr'		=> array(
								'class' => 'media-id',
								'required'	=> null,
							),
		),
	);
	return apply_filters( 'quimimpex_newsletter_data_fields', $fields );
}

/**
 * Newsletter data callback
 *
 * @since Quimimpex 1.0
 */
function quimimpex_newsletter_data_callback( $post ){
	wp_nonce_field( 'qm_newsletter_attr', 'qm_newsletter_field' );

	$fields = quimimpex_newsletter_data_fields();
	foreach ( $fields as $key => $value ) :
		$meta_value = get_post_meta( $post->ID, $value['meta'], true );
?>
	<h4><label for="<?php echo $value['meta'] ?>"><?php echo $value['label'] ?></label></h4>
	<input id="<?php echo $value['meta'] ?>" class="<?php echo $value['attr']['class'] ?>" type="<?php echo $value['type'] ?>" name="<?php echo $value['meta'] ?>" value="<?php echo $meta_value ?>" required="<?php echo $value['attr']['required'] ?>">
	<?php if ( $value['upload'] ) : ?>
		<a href="#" class="button media-selector"><?php _e( 'Upload Newsletter', 'quimimpex' ) ?></a>
	<?php endif; ?>
<?php
	endforeach;
}

/**
 * Save the data
 *
 * @since Quimimpex 1.0
 */
function quimimpex_save_newsletter_meta( $post_id ){
	// Check if the current user is authorized to do this action.
	if ( ! current_user_can( 'edit_posts' ) )
		return;
	// Check if the user intended to change this value.
	if ( ! isset( $_POST['qm_newsletter_field'] ) || ! wp_verify_nonce( $_POST['qm_newsletter_field'], 'qm_newsletter_attr' ) )
		return;
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;

	// Save the data
	$fields = quimimpex_newsletter_data_fields();
	foreach ( $fields as $key => $value ) :
		if ( isset( $_POST[$value['meta']] ) && $_POST[$value['meta']] ) :
			update_post_meta( $post_id, $value['meta'], $_POST[$value['meta']] );
		else :
			delete_post_meta( $post_id, $value['meta'] );
		endif;
	endforeach;

	/**
	 * Send the newsletter
	 */
	if ( get_post_meta( $post_id, 'qm_newsletter_sent', true ) != 1 ) :
		$attachment_id 	= get_post_meta( $post_id, 'qm_newsletter_id', true );
		$to 			= quimimpex_get_subscribers_email();
		$subject		= __( 'Quimimpex Newsletter', 'quimimpex' );
		$message		= '';
		$headers[]		= __( 'From: Quimimpex <no-replay@quimimpex.cu>' );
		$headers[]		= 'Content-type: text/html';
		$attachments	= get_attached_file( $attachment_id );
		wp_mail( $to, $subject, $message, $headers, $attachments );
		update_post_meta( $post_id, 'qm_newsletter_sent', 1 );
	endif;
}
add_action( 'save_post', 'quimimpex_save_newsletter_meta' );
?>
