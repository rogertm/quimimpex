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
	<div id="top-bar" role="navigation" class="bg-secondary">
		<div class="<?php t_em_container(); ?> navbar navbar-dark navbar-expand py-0">
			<ul class="navbar-nav mr-auto small">
				<li class="nav-item mr-3">
					<a href="<?php echo t_em('web_chat_url') ?>" class="nav-link"><i class="icomoon-chat mr-2"></i><?php _e( 'Chat', 'quimimpex' ) ?></a>
				</li>
				<li class="nav-item mr-3">
					<a href="<?php echo t_em('web_email_url') ?>" class="nav-link"><i class="icomoon-mail mr-2"></i><?php _e( 'Mail', 'quimimpex' ) ?></a>
				</li>
			</ul>
			<form id="searchform" class="form-inline my-2 my-lg-0" action="<?php echo home_url( '/' ); ?>" method="get">
				<input class="form-control form-control-sm bg-transparent border-qm-blue-light mr-sm-1" type="search" value="<?php the_search_query(); ?>" placeholder="Buscar" aria-label="Buscar" name="s">
				<button class="btn btn-qm-blue-light btn-sm" type="submit"><i class="icomoon-search"></i></button>
			</form>
			<div class="offset-1"><?php t_em_user_social_network( 'quimimpex', '', 'navbar-nav navbar-dark', 'nav-item', 'nav-link mr-2' ) ?></div>
			<?php echo quimimpex_cart_counter() ?>
		</div>
	</div>
<?php
}
add_action( 't_em_action_top_menu_before', 'quimimpex_top_bar', 0 );

/**
 * Check in cart counter
 * @return int
 *
 * @since Quimimpex 1.0
 */
function quimimpex_cart_counter(){
	if ( t_em( 'page_checkin' ) ) :
		$counter 	= ( $_SESSION['qm_checkin_products'] ) ? $_SESSION['qm_checkin_products'] : array();
		$style 		= ( count( $counter ) > 0 ) ? 'primary' : 'qm-blue-light';
?>
	<div id="checkin-counter" class="my-2 ml-5 pl-5 my-lg-0 pr-3">
		<a href="<?php echo get_permalink( t_em( 'page_checkin' ) ) ?>" class="btn btn-<?php echo $style ?> btn-sm">
			<span class="text-white counter"><?php echo count( $counter ) ?></span>
			<i class="icomoon-shopping-cart text-white"></i>
		</a>
	</div>
<?php
	endif;
}
?>
