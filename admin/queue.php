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
 * Enqueue admin stuff
 *
 * @since Quimimpex 1.0
 */
function quimimpex_admin_queue(){
	wp_register_script( 'qm-admin-js', T_EM_CHILD_THEME_DIR_URL .'/assets/dist/js/admin.js', array( 'jquery' ), false, true );
	wp_enqueue_script( 'qm-admin-js' );
	// L10n for qm-admin-js
	$l10n = array(
		'upm_title'		=> __( 'Select Document', 'quimimpex' ),
		'upm_button'	=> __( 'Use selected document', 'quimimpex' ),
	);
	wp_localize_script( 'qm-admin-js', 'qm_l10n_admin', $l10n );
	wp_enqueue_media();
	wp_enqueue_style( 'media' );
}
add_action( 'admin_enqueue_scripts', 'quimimpex_admin_queue' );
?>
