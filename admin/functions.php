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
 * Register Setting
 * This function is attached to the t_em_admin_action_add_settings_field action hook
 *
 * @link http://codex.wordpress.org/Settings_API
 * @since Quimimpex 1.0
 */
function quimimpex_register_setting_init(){
	add_settings_field( 'quimimpex_custom_pages', __( 'Custom Content', 'quimimpex' ), 'quimimpex_setting_fields_custom_pages', 'twenty-em-options', 'twenty-em-section' );
}
add_action( 't_em_admin_action_add_settings_field', 'quimimpex_register_setting_init' );

/**
 * Merge into default theme options
 * This function is attached to the "t_em_admin_filter_default_theme_options" filter hook
 * @return array 	Array of options
 *
 * @since Quimimpex 1.0
 */
function quimimpex_default_theme_options( $default_theme_options ){
	$quimimpex_default_options = array(
		'company_address'				=> '',
		'principal_contact_email'		=> '',
		'commercial_contact_email'		=> '',
		'principal_contact_phone'		=> '',
		'commercial_contact_phone'		=> '',
		'web_chat_url'					=> '',
		'web_email_url'					=> '',
	);

	// Get custom pages from the original function
	foreach ( quimimpex_custom_pages() as $pages => $value ) :
		$key = array( $value['value'] => '' );
		$quimimpex_default_options = array_merge( $quimimpex_default_options, array_slice( $key, -1 ) );
	endforeach;

	$default_options = array_merge( $default_theme_options, $quimimpex_default_options );

	return $default_options;
}
add_filter( 't_em_admin_filter_default_theme_options', 'quimimpex_default_theme_options' );

/**
 * Sanitize and validate the input.
 * This function is attached to the "t_em_admin_filter_theme_options_validate" filter hook
 * @param $input array  Array of options to validate
 * @return array
 *
 * @since Quimimpex 1.0
 */
function quimimpex_theme_options_validate( $input ){
	if ( ! $input )
		return;

	// Text inputs
	foreach ( array(
		'company_address',
		'principal_contact_email',
		'commercial_contact_email',
		'principal_contact_phone',
		'commercial_contact_phone',
		'web_chat_url',
		'web_email_url',
	) as $text_field ) :
		$input[$text_field] = ( isset( $input[$text_field] ) ) ? trim( $input[$text_field] ) : '';
	endforeach;

	// Let's go for pages
	$pages = quimimpex_custom_pages();
	foreach ( $pages as $key => $value ) :
		if ( array_key_exists( $input[$value['value']], $pages ) ) :
			$input[$key] = $input[$value['value']];
		endif;
	endforeach;

	return $input;
}
add_filter( 't_em_admin_filter_theme_options_validate', 'quimimpex_theme_options_validate' );
?>
