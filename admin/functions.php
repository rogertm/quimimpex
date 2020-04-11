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

	return $input;
}
add_filter( 't_em_admin_filter_theme_options_validate', 'quimimpex_theme_options_validate' );
?>
