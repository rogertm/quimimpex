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
 * Override Function: Copy Right.
 * This function is attached to the t_em_action_site_info action hook.
 */
function t_em_copy_right(){
?>
	<div id="copyright" class="">
		<a href="<?php echo home_url( '/' ) ?>" title="<?php echo esc_attr( get_bloginfo( 'description', 'display' ) ); ?>" rel="home">
			<img src="<?php echo get_stylesheet_directory_uri() .'/assets/images/logo-white.svg' ?>" alt="<?php bloginfo( 'name' ); ?>">
			<small>&copy; <?php echo date( 'Y' ); ?></small>
		</a>
	</div><!-- #copyright -->
<?php
}
?>
