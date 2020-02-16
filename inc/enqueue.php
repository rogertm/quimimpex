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
 * Enqueue and register all css and js
 *
 * @since Quimimpex 1.0
 */
function quimimpex_enqueue(){
	wp_register_style( 'quimimpex-', t_em_get_css( 'theme', T_EM_THEME_DIR_PATH .'/assets/dist/css', T_EM_THEME_DIR_URL .'/assets/dist/css' ), '', t_em_theme( 'Version' ), 'all' );
	wp_enqueue_style( 'quimimpex-' );
}
add_action( 'wp_enqueue_scripts', 'quimimpex_enqueue' );

/**
 * Dequeue styles form parent theme
 *
 * @since Quimimpex 1.2
 */
function quimimpex_dequeue(){
	wp_dequeue_style( 'twenty-em-style' );
	wp_deregister_style( 'twenty-em-style' );
}
add_action( 'wp_enqueue_scripts', 'quimimpex_dequeue', 999 );
?>
