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
 * Get custom taxonomies
 * @return array 	Array
 *
 * @since Quimimpex 1.0
 */
function quimimpex_get_taxonomies(){
	$args = array(
		'public'	=> true,
		'show_ui'	=> true,
	);
	/**
	 * Filter arguments
	 * @param array $args 	An array of key => value arguments to match against the taxonomy objects.
	 *
	 * @since Quimimpex 1.0
	 */
	$args = apply_filters( 'quimimpex_get_taxonomies', $args );
	return get_taxonomies( $args, 'objects' );
}

/**
 * Initialize hooks for every taxonomy
 *
 * @since Quimimpex 1.0
 */
function quimimpex_taxonomies_init_hooks(){
	$filters = quimimpex_get_taxonomies();
	$custom_taxonomies = array( 'qm-export-line', 'qm-import-line' );
	foreach ( $filters as $filter => $value ) :
		if ( in_array( $filter, $custom_taxonomies ) ) :
			add_action( $filter .'_add_form_fields', 'quimimpex_taxonomy_form_fields' );
			add_action( $filter .'_edit_form_fields', 'quimimpex_taxonomy_form_fields' );
			add_action( 'create_'. $filter, 'quimimpex_taxonomy_save_form_fields' );
			add_action( 'edited_'. $filter, 'quimimpex_taxonomy_save_form_fields' );
		endif;
	endforeach;
}
add_action( 'admin_init', 'quimimpex_taxonomies_init_hooks' );

/**
 * Add the forms
 *
 * @since Quimimpex 1.0
 */
function quimimpex_taxonomy_form_fields( $term ){
	$filters = quimimpex_get_taxonomies();
	$custom_taxonomies = array( 'qm-export-line', 'qm-import-line' );

	foreach ( $filters as $filter => $value ) :
		if ( in_array( $filter, $custom_taxonomies ) ) :
			if ( current_filter() == $filter .'_add_form_fields' ) :
?>
		<div class="form-field term-taxonomy-image">
			<label for="qm_taxonomy_icon"><?php printf( __( '%s Icon', 'quimimpex' ), $value->labels->singular_name ); ?></label>
			<select id="qm_taxonomy_icon" name="qm_taxonomy_icon">
				<option value=""><?php _e( '&mdash; Select an Icon &mdash;', 'quimimpex' ) ?></option>
				<?php foreach ( quimimpex_taxonomy_icons() as $icon => $label ) : ?>
				<option value="<?php echo $icon ?>"><?php echo $label ?></option>
				<?php endforeach; ?>
			</select>
		</div>
<?php
			elseif ( current_filter() == $filter .'_edit_form_fields' ) :
				$data = get_term_meta( $term->term_id, 'qm_taxonomy_icon', true );
?>
		<tr class="form-field">
			<th scope="row">
				<label for="qm_taxonomy_icon"><?php printf( __( '%s Icon', 'taxonomy_image' ), $value->labels->singular_name ); ?></label>
			</th>
			<td>
				<select id="qm_taxonomy_icon" name="qm_taxonomy_icon">
					<option value=""><?php _e( '&mdash; Select an Icon &mdash;', 'quimimpex' ) ?></option>
					<?php foreach ( quimimpex_taxonomy_icons() as $icon => $label ) : ?>
					<option value="<?php echo $icon ?>" <?php selected( $icon, $data ) ?>><?php echo $label ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
<?php
			endif;
		endif;
	endforeach;
}

/**
 * Set of icons
 * @return array 	Pair 'icon-name' => 'Icon Label'...
 *
 * @since Quimimpex 1.0
 */
function quimimpex_taxonomy_icons(){
	$icons = array(
		'icomoon-lab-flask'			=> __( 'Nitrogenous', 'quimimpex' ),
		'icomoon-lab-flask'			=> __( 'Chemicals', 'quimimpex' ),
		'icomoon-hour-glass'		=> __( 'Glasses', 'quimimpex' ),
		'icomoon-cake'				=> __( 'Industrial Gases', 'quimimpex' ),
		'icomoon-circular-graph'	=> __( 'Tire', 'quimimpex' ),
		'icomoon-paper-plane'		=> __( 'Paper', 'quimimpex' ),
	);

	/**
	 * Filter the icons array
	 * @param array $icons 		Array of 'icon-name' => 'Icon Label' pairs
	 *
	 * @since Quimimpex 1.0
	 */
	return apply_filters( 'quimimpex_taxonomy_icons', $icons );
}

/**
 * Save the data
 *
 * @since Quimimpex 1.0
 */
function quimimpex_taxonomy_save_form_fields( $term_id ){
	if ( ! isset( $_POST['qm_taxonomy_icon'] ) )
		return;

	if ( ! empty( $_POST['qm_taxonomy_icon'] ) ) :
		update_term_meta( $term_id, 'qm_taxonomy_icon', sanitize_text_field( $_POST['qm_taxonomy_icon'] ) );
	else :
		delete_term_meta( $term_id, 'qm_taxonomy_icon' );
	endif;
}
?>
