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
			<div class="<?php echo t_em_grid( 7 ) ?> position-relative">
				<?php t_em_featured_post_thumbnail( 1200, 600, true, 'about-us-image '. t_em_grid( 10 ), t_em( 'page_about_us' ) ) ?>
				<div class="about-us-title position-absolute">
					<a href="<?php echo get_permalink( t_em( 'page_about_us' ) ) ?>">
						<p class="text-white h1 p-6 hvr-grow"><?php echo get_the_title( t_em( 'page_about_us' ) ) ?></p>
					</a>
				</div>
			</div>
			<div class="<?php echo t_em_grid( 5 ) ?> px-5 py-0 about-us-text">
				<?php echo t_em_wrap_paragraph( get_post_field( 'post_excerpt', t_em( 'page_about_us' ) ) ) ?>
		        <div class="about-us-action mt-5">
		      		<button type="button" class="btn btn-outline-primary mr-3 mb-3"><i class="qmicon-phone pr-2"></i><?php echo t_em( 'principal_contact_phone' ) ?></button>
		      		<button type="button" class="btn btn-outline-primary mb-3"><i class="qmicon-envelope pr-2"></i><?php echo t_em( 'principal_contact_email' ) ?></button>
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
		<section id="services" class="<?php echo t_em( 'text_widget_template' ) ?> full-width services py-lg-5 img-bg">
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
 * Override Function: Display featured post thumbnail on top of a single post if it is set by the
 * user in "General Options" in the admin options page. This function is attached to the
 * do_action( 't_em_action_post_inside_before' ) action hook.
 *
 * @since Twenty'em 1.0
 */
function t_em_single_post_thumbnail(){
	if ( is_page_template( 'page-templates/template-blog-excerpt.php' ) )
		return;
	if ( ! is_page() )
		return;
	global $post;
	if ( t_em( 'single_featured_img' )
		&& ( is_singular() && has_post_thumbnail() )
		|| ( t_em( 'archive_set' ) == 'the-content'
			&& ( is_home() || is_front_page() || is_archive() )
		)
		|| ( is_page_template( 'page-templates/template-blog-content.php' ) )
	) :
		$attr = array(
			'class'	=> 'featured-post-thumbnail',
			'alt'	=> $post->post_title,
		);
		echo '<span class="featured-post-thumbnail-wrapper">' . get_the_post_thumbnail( $post->ID, 'full', $attr ) . '</span>';
?>
		<header>
			<h1 class="entry-title"><?php the_title(); ?></h1>
		</header>
<?php
	else :
?>
	<div class="full-width bg-secondary mb-5">
		<header class="<?php echo t_em_container() ?> py-5">
			<h1 class="entry-title text-white"><?php the_title(); ?></h1>
		</header>
	</div>
<?php
	endif;
}

/**
 * Override Function: Display Page title and content for custom pages templates.
 * This function is attached to the t_em_action_content_before action hook.
 *
 * @since Twenty'em 1.0
 */
function t_em_custom_template_content(){
	if ( is_page_template() && is_page() && get_post_meta( get_the_ID(), '_wp_page_template', true ) != 'page-templates/template-one-column.php' ) :
	$template_data = get_page( get_the_ID() );
?>
	<div id="featured-header-template-<?php the_ID(); ?>" <?php post_class( 'featured-header featured-header-template custom-template-content full-width bg-secondary mb-5' ); ?>>
		<header class="<?php echo t_em_container() ?> py-5">
			<h1 class="entry-title text-white"><?php echo apply_filters( 'the_title', $template_data->post_title ); ?></h1>
		</header>
	</div><!-- .featured-header -->
	<?php do_action( 't_em_action_post_content_before' ) ?>
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
	<section id="latest-news" class="py-5 mt-5">
		<h3 class="h1 text-center mb-4"><?php _e( 'Latest news', 'quimimpex' ) ?></h3>
		<div class="row">
			<div class="card-deck <?php echo t_em_grid( 10 ) ?> mx-auto">
<?php foreach ( $featured_posts as $featured ) : ?>
				<div class="card text-center border-0 shadow-sm">
					<?php t_em_featured_post_thumbnail( 600, 700, true, 'card-img-top', $featured->ID ) ?>
					<div class="card-body">
						<h5 class="card-title mt-2"><?php echo $featured->post_title ?></h5>
						<time class="d-block small mb-2"><i class="qmicon-calendar mx-1 text-primary"></i><?php echo get_the_date( get_option( 'date_format' ), $featured->ID ) ?></time>
						<?php t_em_get_post_excerpt( $featured->ID ) ?>
						<a href="<?php echo get_permalink( $featured->ID ) ?>" class="btn btn-link btn-block stretched-link"><?php _e( 'Read more...', 'quimimpex' ) ?></a>
						<span class="card-link h2 p-3 d-inline-block text-center position-absolute rounded-circle text-white bg-primary"><i class="qmicon-link"></i></span>
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
		'posts_per_page'	=> '7',
		'orderby'				=> 'rand',
	);

	$companies = get_posts( $args );
	if ( $companies ) :
		$attr = array(
			'class'	=> 'card-img-top pt-3 pb-1 py-sm-5',
		);
?>
	<section id="featured-companies">
		<div class="<?php echo t_em_container() ?> container">
			<div class="card-group d-flex justify-content-center">
<?php foreach ( $companies as $company ) : ?>
				<div class="card border-0">
					<div class="card-wrapper">
						<?php echo get_the_post_thumbnail( $company->ID, 'full', $attr ) ?>
					</div>
				</div>
<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php
	endif;
}
add_action( 't_em_action_main_after', 'quimimpex_companies_section' );

/**
 * Common Modal
 *
 * @since Quimimpex 1.0
 */
function quimimpex_modal(){
?>
	<div id="qm-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="qm-modal-label" aria-hidden="true">
		<div class="modal-dialog modal-xl modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header row no-gutters">
					<div class="col-lg-8">
						<h3 id="qm-modal-label" class="modal-title text-center text-lg-left"></h3>
					</div>
					<div class="col-lg-4 text-center text-lg-right">
						<div class="modal-actions d-inline"></div>
						<a href="#" class="btn btn-light rounded-circle" data-dismiss="modal" aria-label="<?php _e( 'Close', 'quimimpex' ) ?>">
							<i class="icomoon-cross text-muted h6 mb-0"></i>
						</a>
					</div>
				</div>
				<div class="modal-body">
					<div class="container-fluid">
						<div class="row">
							<div class="<?php echo t_em_grid( 5 ) ?> p-0">
								<div class="modal-thumbnail mb-3"></div>
							</div>
							<div class="<?php echo t_em_grid( 7 ) ?> py-1 px-lg-3 p-0">
								<div class="modal-post-content mb-3"></div>
								<!-- <h6 class="font-weight-bold modal-title-contact pb-2"></h6>
								<div class="modal-post-contact row">
									<div class="contact-phone <?php echo t_em_grid( 6 ) ?>"></div>
									<div class="contact-email <?php echo t_em_grid( 6 ) ?>"></div>
								</div> -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
}
add_action( 't_em_action_top', 'quimimpex_modal' );

/**
 * Show related products
 *
 * @since Quimimpex 1.0
 */
function quimimpex_products_related_posts(){
	if ( is_singular( array( 'qm-export-product', 'qm-import-product' ) ) && t_em( 'single_related_posts' ) ) :
		global $post;
		$post_id = $post->ID;
		$taxonomies = get_taxonomies( array( 'public' => true ), 'object' );
		$post_type = get_post_type( $post_id );
		$labels = get_post_type_object( $post_type );
		$taxonomy = array();

		foreach ( $taxonomies as $key => $value ) :
			if ( in_array( $post_type, $value->object_type ) ) :
				array_push( $taxonomy, $key );
			endif;
		endforeach;

		/**
		 * Filter the amount of related post to display
		 *
		 * @param int Number of posts to display
		 * @since Twenty'em 1.0
		 */
		$limit = apply_filters( 't_em_filter_single_limit_related_posts', 3 );

		$query_args = array(
			'post_type'			=> $post_type,
			'posts_per_page'	=> $limit,
			'post__not_in'		=> array( $post_id ),
			'post_status'		=> 'publish',
			'orderby'			=> 'rand',
			'tax_query'			=> array(
				'relation'		=> 'OR',
			),
		);
		foreach ( $taxonomy as $tax ) :
			$terms = get_the_terms( $post_id, $tax );
			if ( ! $terms ) continue;
			$terms_ids = array();
			foreach ( $terms as $term ) :
				array_push( $terms_ids, $term->term_id );
			endforeach;
			$key = array(
				'taxonomy'	=> $tax,
				'field'		=> 'id',
				'terms'		=> $terms_ids,
			);
			array_push( $query_args['tax_query'], $key );
		endforeach;

		/**
		 * Filter the related post query arguments
		 * @param array 	Query arguments
		 *
		 * @since Twenty'em 1.2
		 */
		$all_posts = apply_filters( 't_em_filter_single_related_post_query', get_posts( $query_args ) );
?>
		<section id="related-products">
			<div class="my-5 border-top">
<?php 	if ( ! empty( $all_posts ) ) : ?>
			<h3 class="related-posts-title my-5"><?php printf( _x( 'Similar %s', 'similar custom post type label', 't_em' ), $labels->labels->name ); ?></h3>
			<div class="row">
		<?php foreach( $all_posts as $post ) : setup_postdata( $post ); ?>
				<div class="card text-center shadow-sm border-0 <?php echo t_em_grid( 4 ) ?>">
					<?php t_em_featured_post_thumbnail( 700, 460, true, 'card-img-top border p-1', $post->ID ) ?>
					<div class="card-body">
						<h5 class="card-title font-weight-superbold mt-2 mb-3">
							<a href="<?php echo get_permalink() ?>"><?php echo get_the_title() ?></a>
						</h5>
						<div class="d-flex justify-content-center">
							<?php if ( get_post_type( $post->ID ) == 'qm-export-product' ) : ?>
							<a href="#" class="text-muted ml-3" data-toggle="modal" data-target="#qm-modal" data-id="<?php echo $post->ID ?>"><i class="qmicon-eye"></i></a>
							<?php endif; ?>
							<?php echo quimimpex_checkin_btn( $post->ID ); ?>
							<?php if ( get_post_type( $post->ID ) == 'qm-export-product' ) : ?>
							<a href="<?php echo get_permalink() ?>" class="text-muted ml-3"><i class="qmicon-link"></i></a>
							<?php endif; ?>
						</div>
					</div>
				</div>
		<?php endforeach; wp_reset_query(); ?>
			</div>
<?php 	else : ?>
			<h3 class="no-related-posts-title"><?php printf( _x( 'No Similar %s', 'no similar custom post type label', 't_em' ), $labels->labels->name ); ?></h3>
<?php 	endif; ?>
			</div>
		</section>
<?php
	endif;
}
add_action( 't_em_action_post_after', 'quimimpex_products_related_posts' );

/**
 * Override Function: Show related posts to the current single post if it's set by the user in
 * "General Options" in admin theme options page.
 * This function is attached to the t_em_action_post_after action hook.
 *
 * @return string HTML list of items
 *
 * @since Twenty'em 1.0
 * @since Twenty'em 1.2		Support for custom post types
 */
function t_em_single_related_posts(){
	if ( is_singular( 'post' ) && t_em( 'single_related_posts' ) ) :
		global $post;
		$post_id = $post->ID;
		$taxonomies = get_taxonomies( array( 'public' => true ), 'object' );
		$post_type = get_post_type( $post_id );
		$labels = get_post_type_object( $post_type );
		$taxonomy = array();

		foreach ( $taxonomies as $key => $value ) :
			if ( in_array( $post_type, $value->object_type ) ) :
				array_push( $taxonomy, $key );
			endif;
		endforeach;

		/**
		 * Filter the amount of related post to display
		 *
		 * @param int Number of posts to display
		 * @since Twenty'em 1.0
		 */
		$limit = apply_filters( 't_em_filter_single_limit_related_posts', 3 );

		$query_args = array(
			'post_type'			=> $post_type,
			'posts_per_page'	=> $limit,
			'post__not_in'		=> array( $post_id ),
			'post_status'		=> 'publish',
			'tax_query'			=> array(
				'relation'		=> 'OR',
			),
		);
		foreach ( $taxonomy as $tax ) :
			$terms = get_the_terms( $post_id, $tax );
			if ( ! $terms ) continue;
			$terms_ids = array();
			foreach ( $terms as $term ) :
				array_push( $terms_ids, $term->term_id );
			endforeach;
			$key = array(
				'taxonomy'	=> $tax,
				'field'		=> 'id',
				'terms'		=> $terms_ids,
			);
			array_push( $query_args['tax_query'], $key );
		endforeach;

		/**
		 * Filter the related post query arguments
		 * @param array 	Query arguments
		 *
		 * @since Twenty'em 1.2
		 */
		$all_posts = apply_filters( 't_em_filter_single_related_post_query', get_posts( $query_args ) );
?>
		<section id="related-posts">
<?php 	if ( ! empty( $all_posts ) ) : ?>
			<h3 class="related-posts-title text-center mb-4"><?php printf( _x( 'Similar %s', 'similar custom post type label', 't_em' ), $labels->labels->name ); ?></h3>

		<div class="card-deck <?php echo t_em_grid( 10 ) ?> mx-auto">
			<div class="row no-gutters">
<?php foreach ( $all_posts as $featured ) : ?>
				<div class="card text-center <?php echo t_em_grid( 4 ) ?>">
					<?php t_em_featured_post_thumbnail( 600, 700, true, 'card-img-top', $featured->ID ) ?>
					<div class="card-body">
						<h5 class="card-title"><?php echo $featured->post_title ?></h5>
						<time class="d-block"><i class="qmicon-calendar mx-1"></i><?php echo get_the_date( get_option( 'date_format' ), $featured->ID ) ?></time>
						<?php t_em_get_post_excerpt( $featured->ID ) ?>
						<a href="<?php echo get_permalink( $featured->ID ) ?>" class="btn btn-link btn-block stretched-link"><?php _e( 'Read more...', 'quimimpex' ) ?></a>
						<span class="card-link h2 p-3 d-inline-block text-center position-absolute rounded-circle text-white bg-primary"><i class="qmicon-link"></i></span>
					</div>
				</div>
<?php endforeach; ?>
			</div>
		</div>
<?php 	else : ?>
			<h3 class="no-related-posts-title"><?php printf( _x( 'No Similar %s', 'no similar custom post type label', 't_em' ), $labels->labels->name ); ?></h3>
<?php 	endif; ?>
		</section>
<?php
	endif;
}

/**
 * Check in product button
 * @param int $product_id 	Product ID
 *
 * @since Quimimpex 1.0
 */
function quimimpex_checkin_btn( $product_id ){
	$products = ( isset( $_SESSION['qm_checkin_products'] ) ) ? $_SESSION['qm_checkin_products'] : array();
	$checked = ( in_array( $product_id, $products ) ) ? 'qm-product-checked' : 'qm-product-unchecked';
	return '<a href="#" class="qm-checkin-product ml-3 '. $checked .'" data-product-id="'. $product_id .'"><i class="qmicon-car-add"></i></a>';
}

/**
 * Override Function
 */
function t_em_edit_post_link(){
	return;
}
?>
