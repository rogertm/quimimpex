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
 * Show the map
 *
 * @since Quimimpex 1.0
 */
function quimimpex_map(){
	if ( ! is_home() && ! is_page( t_em( 'page_about_us' ) ) && ! is_page( t_em( 'page_contact' ) ) )
		return;
?>
	<div class="d-block">
		<img src="<?php echo get_stylesheet_directory_uri(). '/assets/images/map.jpg'; ?>" class="w-100">
	</div>
<?php
}
add_action( 't_em_action_main_after', 'quimimpex_map', 5 );
?>
