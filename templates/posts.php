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
 * About Us on front page
 *
 * @since Quimimpex 1.0
 */
function quimimpex_front_about_us(){
	if ( ! t_em( 'page_about_us' ) )
		return;
?>
	<section id="about-us" class="my-9 about-us">
		<div class="row">
			<div class="<?php echo t_em_grid( 7 ) ?>">
				<a href="<?php echo get_permalink( t_em( 'page_about_us' ) ) ?>">
					<?php echo get_the_post_thumbnail( t_em( 'page_about_us' ), 'full', array( 'class' => 'about-us-image col-10' ) ) ?>
				</a>
				<div class="about-us-title position-absolute">
					<a href="<?php echo get_permalink( t_em( 'page_about_us' ) ) ?>">
						<p class="text-white h1 p-6 hvr-grow"><?php echo get_the_title( t_em( 'page_about_us' ) ) ?></p>
					</a>
				</div>
			</div>
			<div class="<?php echo t_em_grid( 5 ) ?> px-5 py-0 about-us-text">
				<?php echo t_em_wrap_paragraph( get_post_field( 'post_excerpt', t_em( 'page_about_us' ) ) ) ?>
		        <div class="mt-5">
		      		<button type="button" class="btn btn-outline-primary mr-3"><i class="icomoon-phone pr-2"></i><?php echo t_em( 'principal_contact_phone' ) ?></button>
		      		<button type="button" class="btn btn-outline-primary"><i class="icomoon-mail pr-2"></i><?php echo t_em( 'principal_contact_email' ) ?></button>
		        </div>
			</div>
		</div>
	</section>
<?php
}
add_action( 't_em_action_custom_front_page_before', 'quimimpex_front_about_us' );

/**
 * Override Function: Render Featured Text Widgets in front page if it's is set by the user in
 * "Front Page Options" in admin panel.
 *
 * This function is directly call from custom-front-page.php template
 *
 * @since Twenty'em 1.0
 */
function t_em_front_page_widgets(){
	if ( 'widgets-front-page' == t_em( 'front_page_set' ) ) :
?>
		<section id="services" class="<?php echo t_em( 'text_widget_template' ) ?> full-width services py-5 img-bg">
			<div class="container">
				<div class="row">
					<?php do_action( 't_em_action_custom_front_page_inside_before' ); ?>
					<?php get_template_part( '/template-parts/front', 'template-jumbotron' ) ?>
					<?php do_action( 't_em_action_custom_front_page_inside_after' ); ?>
				</div>
			</div>
		</section><!-- #featured-widget-area -->
<?php
	endif;
}

/**
 * Posts section on front page
 *
 * @since Quimimpex 1.0
 */
function quimimpex_front_posts_section(){
	$args = array(
		'post_type'			=> 'post',
		'posts_per_page'	=> 3,
		'cat'				=> '-'. t_em('slider_category'),
	);

	$featured_posts = get_posts( $args );
	if ( $featured_posts ) :
?>
	<section id="latest-news" class="py-5">
		<h3 class="h1 text-center mb-4"><?php _e( 'Latest news', 'quimimpex' ) ?></h3>
		<div class="row">
			<div class="card-deck <?php echo t_em_grid( 9 ) ?> mx-auto">
<?php foreach ( $featured_posts as $featured ) : ?>
				<div class="card text-center">
					<?php t_em_featured_post_thumbnail( 600, 700, true, 'card-img-top', $featured->ID ) ?>
					<div class="card-body">
						<h5 class="card-title"><?php echo $featured->post_title ?></h5>
						<time class="d-block"><i class="icomoon-calendar mx-1"></i><?php echo get_the_date( get_option( 'date_format' ), $featured->ID ) ?></time>
						<?php t_em_get_post_excerpt( $featured->ID ) ?>
						<a href="<?php echo get_permalink( $featured->ID ) ?>" class="btn btn-link btn-block"><?php _e( 'Read more...', 'quimimpex' ) ?></a>
					</div>
				</div>
<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php
	endif;
}
add_action( 't_em_action_custom_front_page_after', 'quimimpex_front_posts_section' );

/**
 * Companies section
 *
 * @since Quimimpex 1.0
 */
function quimimpex_companies_section(){
	$args = array(
		'post_type'			=> 'qm-company',
		'posts_per_page'	=> '5',
		'orderby'				=> 'rand',
	);

	$companies = get_posts( $args );
	if ( $companies ) :
		$attr = array(
			'class'	=> 'card-img-top',
		);
?>
	<section id="featured-companies">
		<div class="<?php echo t_em_container() ?>">
			<div class="card-group">
<?php foreach ( $companies as $company ) : ?>
				<div class="card border-0">
					<?php echo get_the_post_thumbnail( $company->ID, 'full', $attr ) ?>
				</div>
<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php
	endif;
}
add_action( 't_em_action_main_after', 'quimimpex_companies_section' );
?>
