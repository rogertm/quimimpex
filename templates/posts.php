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
	<section id="latest-news">
		<h3 class="h1 text-center"><?php _e( 'Latest news', 'quimimpex' ) ?></h3>
		<div class="card-deck">
<?php
		foreach ( $featured_posts as $featured ) :
?>
			<div class="card text-center">
				<?php t_em_featured_post_thumbnail( 600, 800, true, 'card-img-top', $featured->ID ) ?>
				<div class="card-body">
					<h5 class="card-title"><?php echo $featured->post_title ?></h5>
					<time class="d-block"><i class="icomoon-calendar mx-1"></i><?php echo get_the_date( get_option( 'date_format' ), $featured->ID ) ?></time>
					<?php t_em_get_post_excerpt( $featured->ID ) ?>
					<a href="<?php echo get_permalink( $featured->ID ) ?>"><?php _e( 'Read more...', 'quimimpex' ) ?></a>
				</div>
			</div>
<?php
		endforeach;
?>
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
			<div class="card">
				<?php echo get_the_post_thumbnail( $company->ID, 'full', $attr ) ?>
			</div>
<?php endforeach; ?>
		</div>
		</div>
	</section>
<?php
	endif;
}
add_action( 't_em_action_footer_before', 'quimimpex_companies_section' );
?>
