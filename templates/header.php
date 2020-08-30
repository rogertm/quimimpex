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
 * Slider Query. This function is hooked to 't_em_filter_slider_query_args' filter
 * @return array 	Query arguments
 *
 * @since Quimimpex 1.0
 */
function quimimpex_slider_query_args(){
	$args = array(
		'post_type'			=> 'qm-banner',
		'posts_per_page'	=> t_em( 'slider_number' ),
		'orderby'			=> 'date',
		'order'				=> 'DESC',
		'meta_key'			=> '_thumbnail_id',
		'meta_query'		=> array(
			array(
				'key'		=> '_thumbnail_id',
			),
			array(
				'key'		=> 'quimimpex_banner_link',
			),
		),
	);
	return apply_filters( 'quimimpex_slider_query_args', $args );
}
add_action( 't_em_filter_slider_query_args', 'quimimpex_slider_query_args' );

/**
 * Override Function: Display Bootstrap carousel of featured posts if it's set by the user in
 * 'Header Options > Slider' admin panel
 *
 * @param $args array Query arguments
 *
 * @since Twenty'em 1.0
 */
function t_em_slider_bootstrap_carousel( $args ){
	global $post;
	if ( ( 'slider' == t_em( 'header_set' ) )
		&& ( ( '1' == t_em( 'slider_home_only' ) && is_home() )
		|| ( '0' == t_em( 'slider_home_only' ) ) ) ) :

		if ( ! $args ) $args = t_em_slider_query_args();

			$slider_posts = get_posts( $args );
			$slider_fade = ( t_em( 'bootstrap_carousel_fade' ) ) ? 'carousel-fade' : null;
			$slider_wrap = ( t_em( 'bootstrap_carousel_wrap' ) ) ? 'false' : 'true';
			$slider_pause = ( t_em( 'bootstrap_carousel_pause' ) ) ? 'hover' : 'false';
	?>
			<section id="slider-carousel" class="carousel slide container-fluid mt-8 <?php echo $slider_fade ?>" data-ride="carousel" data-wrap="<?php echo $slider_wrap; ?>" data-pause="<?php echo $slider_pause; ?>" data-interval="<?php echo t_em( 'bootstrap_carousel_interval' ) ?>">
			<?php
			/**
			 * Fires before the slider carousel section. Full width;
			 *
			 * @since Twenty'em 1.1
			 */
			do_action( 't_em_action_slider_before' );
			?>
<?php 		if ( $slider_posts ) : ?>
<?php 			$tp = count( $slider_posts ) ?>
				<ol class="carousel-indicators">
			<?php $s = 0; while ( $s < $tp ) : ?>
					<li data-target="#slider-carousel" data-slide-to="<?php echo $s ?>"></li>
			<?php $s++; endwhile; ?>
				</ol><!-- .carousel-indicators -->
				<div class="carousel-inner">
				<?php
				/**
				 * Fires in and before the slider carousel section. Container width;
				 *
				 * @since Twenty'em 1.1
				 */
				do_action( 't_em_action_slider_inner_before' );
				?>
				<?php foreach ( $slider_posts as $post ) : setup_postdata( $post );
					$thumbnail = t_em_image_resize( 1200, t_em( 'slider_height' ), $post->ID ); ?>
					<div class="carousel-item">
						<?php t_em_featured_post_thumbnail( 1900, 600, false, 'd-block w-auto', $post->ID ); ?>
						<div id="<?php echo $post->post_name ?>-<?php echo $post->ID; ?>" class="carousel-caption">
							<div class="<?php echo t_em_grid( 8 ) ?> <?php echo t_em_grid( 2, '', true ) ?> px-5">
								<h3 class="item-title text-uppercase h2">
									<a href="<?php echo get_post_meta( $post->ID, 'quimimpex_banner_link', true ); ?>"><?php echo get_the_title(); ?></a>
								</h3>
								<p class="item-summary d-none d-lg-block"><?php t_em_get_post_excerpt(); ?></p>
							</div>
						</div>
					</div><!-- .item -->
				<?php endforeach; wp_reset_postdata(); ?>
				<?php
				/**
				 * Fires in and after the slider carousel section. Container width;
				 *
				 * @since Twenty'em 1.1
				 */
				do_action( 't_em_action_slider_inner_after' );
				?>
				</div><!-- .carousel-inner -->
				<a class="carousel-control-prev" href="#slider-carousel" role="button" data-slide="prev">
					<span class="carousel-control-prev-icon" aria-hidden="true"></span>
					<span class="sr-only"><?php _e( 'Previous', 't_em' ); ?></span>
				</a>
				<a class="carousel-control-next" href="#slider-carousel" role="button" data-slide="next">
					<span class="carousel-control-next-icon" aria-hidden="true"></span>
					<span class="sr-only"><?php _e( 'Next', 't_em' ) ?></span>
				</a>
<?php 	endif; ?>
		<?php
		/**
		 * Fires after the slider carousel section. Full width;
		 *
		 * @since Twenty'em 1.1
		 */
		do_action( 't_em_action_slider_after' );
		?>
	</section><!-- #slider-carousel -->
<?php
	endif;
}
?>
