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
 * Override Function: Top menu.
 * This function is attached to the do_action( 't_em_action_header_before' ) action hook
 */
function t_em_top_menu(){
	require_once( T_EM_THEME_DIR_INC_PATH .'/class-navwalker.php' );

	/** This filter is documented in inc/functions.php */
	$bp = apply_filters( 't_em_filter_default_breakpoint', 'lg' );
?>
	<div id="top-menu" class="p-0 p-lg-0 p-md-0 p-sm-0" role="navigation">
	<?php do_action( 't_em_action_top_menu_before' ) ?>
		<nav class="navbar navbar-expand-<?php echo $bp ?> navbar-dark bg-dark">
			<div class="<?php t_em_container(); ?>">
				<div class="brand-wrapper d-flex justify-content-between bg-white">
					<?php
					/**
					 * Filter the navbar brand
					 *
					 * @param string $brand HTML containing the navbar brand
					 */
					$heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'span';
					$brand = ( has_custom_logo() ) ? get_custom_logo() : '<'. $heading_tag .' id="site-title"><a href="'. home_url( '/' ) .'" class="navbar-brand" rel="home">'. get_bloginfo( 'name' ) .'</a></'. $heading_tag .'>';
					echo apply_filters( 't_em_filter_top_menu_brand', $brand );
					?>
					<?php if ( has_nav_menu( 'top-menu' ) ) : ?>
					<button type="button" class="navbar-toggler h-75 my-auto" data-toggle="collapse" data-target="#site-top-menu">
						<span class="navbar-toggler-icon"></span>
					</button>
				</div>
				<div id="site-top-menu" class="collapse navbar-collapse">
				<?php
				do_action( 't_em_action_top_menu_navbar_before' );
				wp_nav_menu( array(
						/**
						 * Filter the menu depth
						 *
						 * @param int How many levels of the hierarchy are to be included where 0 means all. -1 displays links at any depth and arranges them in a single, flat list.
						 * @link http://codex.wordpress.org/Function_Reference/wp_nav_menu
						 * @since Twenty'em 1.0
						 */
						'theme_location'	=> 'top-menu',
						'container'			=> false,
						'menu_class'		=> 'navbar-nav',
						'depth'				=> apply_filters( 't_em_filter_top_menu_depth', 2 ),
						'walker'			=> new Twenty_Em_Navwalker(),
					)
				);
				do_action( 't_em_action_top_menu_navbar_after' ); ?>
				</div>
				<?php endif; ?>
			</div>
		</nav>
		<?php do_action( 't_em_action_top_menu_after' ) ?>
	</div>
<?php
}

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
					<a href="<?php echo t_em('web_chat_url') ?>" class="nav-link hvr-grow"><i class="qmicon-chat mr-2"></i><?php _e( 'Chat', 'quimimpex' ) ?></a>
				</li>
				<li class="nav-item mr-3">
					<a href="<?php echo t_em('web_email_url') ?>" class="nav-link hvr-grow"><i class="qmicon-envelope mr-2"></i><?php _e( 'Web Mail', 'quimimpex' ) ?></a>
				</li>
			</ul>
			<form id="searchform" class="form-inline my-2 my-lg-0 d-none d-lg-inline" action="<?php echo home_url( '/' ); ?>" method="get">
				<input class="form-control form-control-sm bg-transparent border-qm-blue-light mr-sm-1" type="search" value="<?php the_search_query(); ?>" placeholder="Buscar" aria-label="Buscar" name="s">
				<button class="btn btn-qm-blue-light btn-sm" type="submit"><i class="qmicon-search hvr-grow"></i></button>
			</form>
			<div class="offset-1 d-none d-lg-inline"><?php t_em_user_social_network( 'quimimpex', '', 'navbar-nav navbar-dark', 'nav-item', 'nav-link mr-2' ) ?></div>
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
		$counter 	= ( isset( $_SESSION['qm_checkin_products'] ) ) ? $_SESSION['qm_checkin_products'] : array();
		$style 		= ( count( $counter ) > 0 ) ? 'primary' : 'qm-blue-light';
?>
	<div id="checkin-counter" class="my-2 ml-5 pl-5 my-lg-0 pr-3">
		<a href="<?php echo get_permalink( t_em( 'page_checkin' ) ) ?>" class="btn btn-<?php echo $style ?> btn-sm">
			<span class="text-white counter"><?php echo count( $counter ) ?></span>
			<i class="qmicon-car-add text-white hvr-grow ml-2"></i>
		</a>
	</div>
<?php
	endif;
}

/**
 * Add custom elements to navigation menu
 * @param string $items 	The HTML list content for the menu items.
 * @param stdClass $args 	An object containing wp_nav_menu() arguments.
 *
 * @since Quimimpex 1.0
 */
function quimimpex_custom_menu_items( $items, $args ){
	$user_social_network = t_em_social_network_options();
	uasort( $user_social_network, 't_em_sort_by_order' );

	if ( $args->theme_location == 'top-menu' ) :
		$items .= '<li class="menu-item nav-item mr-2 ml-2 ml-lg-4 py-2 py-lg-4 d-none d-sm-inline-flex d-lg-none d-lx-none d-flex justify-content-between">';
		$items .=	'<form id="searchform-sm" class="form-inline d-flex justify-content-between" action="'. home_url( '/' ) .'" method="get">';
		$items .=		'<input class="form-control form-control-sm bg-transparent border-qm-blue-light" type="search" value="'. the_search_query() .'" placeholder="Buscar" aria-label="Buscar" name="s">';
		$items .=		'<button class="btn btn-qm-blue-light btn-sm" type="submit"><i class="qmicon-search hvr-grow"></i></button>';
		$items .=	'</form>';
		$items .= '</li>';
		$items .= '<li class="menu-item nav-item mr-2 ml-2 ml-lg-4 py-2 py-lg-4 d-none d-sm-inline-flex d-lg-none d-lx-none d-flex justify-content-between">';
		$items .= 	'<ul class="list-inline">';
		foreach ( $user_social_network as $social_network ) :
			if ( t_em( $social_network['name'] ) != '' ) :
				$items .= '<li id="'.$social_network['name'].'" class="list-inline-item social-icon">';
				$items .= 	'<a href="'. t_em( $social_network['name'] ) .'" class="">';
				$items .= 		'<span class="'. $social_network['class'] .'"></span>';
				$items .= 		'<span class="network-label sr-only">'. $social_network['item']. '</span>';
				$items .= 	'</a>';
				$items .= '</li>';
			endif;
		endforeach;
		$items .= 	'</ul>';
		$items .= '</li>';
	endif;
	return $items;
}
add_filter( 'wp_nav_menu_items', 'quimimpex_custom_menu_items', 10, 2 );
?>
