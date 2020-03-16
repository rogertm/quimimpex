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
	wp_register_style( 'quimimpex-css', t_em_get_css( 'theme', T_EM_THEME_DIR_PATH .'/assets/dist/css', T_EM_THEME_DIR_URL .'/assets/dist/css' ), '', t_em_theme( 'Version' ), 'all' );
	wp_enqueue_style( 'quimimpex-css' );

	wp_register_script( 'child-app-utils', t_em_get_js( 'index', T_EM_CHILD_THEME_DIR_PATH .'/assets/dist/js', T_EM_CHILD_THEME_DIR_URL .'/assets/dist/js' ), array( 'jquery' ), t_em_theme( 'Version' ), true );
	// l10n for index.js
	$translation = array(
		'_qmnonce'	=> wp_create_nonce( '_qmnonce' ),
	);
	wp_localize_script( 'child-app-utils', 'qm_l10n', $translation );
	wp_enqueue_script( 'child-app-utils' );
}
add_action( 'wp_enqueue_scripts', 'quimimpex_enqueue' );

/**
 * Define the 'ajaxurl' JS variable
 *
 * @since Quimimpex 1.0
 */
function quimimpex_ajax_url(){
?>
	<script type="text/javascript">
		var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
	</script>
<?php
}
add_action( 'wp_head', 'quimimpex_ajax_url' );

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
