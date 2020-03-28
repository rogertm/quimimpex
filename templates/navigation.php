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
 * Top bar. Chat and Webmail options
 *
 * @since Quimimpex 1.0
 */
function quimimpex_top_bar(){
?>
	<div id="top-bar" role="navigation" class="bg-dark text-light">
		<div class="<?php t_em_container(); ?> d-flex">
			<nav class="nav">
				<a href="<?php echo t_em('web_chat_url') ?>" class="nav-link"><?php _e( 'Chat', 'quimimpex' ) ?></a>
				<a href="<?php echo t_em('web_email_url') ?>" class="nav-link"><?php _e( 'Mail', 'quimimpex' ) ?></a>
			</nav>
			<nav class="nav ml-auto"><?php get_search_form() ?></nav>
			<nav class="nav"><?php t_em_user_social_network( 't-em', '', 'list-inline', 'list-inline-item' ) ?></nav>
		</div>
	</div>
<?php
}
add_action( 't_em_action_top', 'quimimpex_top_bar' );
?>
