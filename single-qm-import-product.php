<?php
/**
 * Twenty'em WordPress Framework.
 *
 * WARNING: This file is part of Twenty'em WordPress Framework.
 * DO NOT edit this file under any circumstances. Do all your modifications in the form of a child theme.
 *
 * @package			WordPress
 * @subpackage		Twenty'em
 * @author			RogerTM
 * @license			license.txt
 * @link			https://themingisprose.com/twenty-em
 * @since 			Twenty'em 1.0
 */

/**
 * The template for displaying all single posts.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 */

get_header(); ?>

		<section id="main-content" <?php t_em_breakpoint( 'main-content' ); ?>>
			<section id="content" role="main" <?php t_em_breakpoint( 'content' ); ?>>
			<?php do_action( 't_em_action_content_before' ); ?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<?php
$contact_id	= get_post_meta( $post->ID, 'qm_product_contact', true );
$land_phone		= ( $contact_id && get_post_meta( $contact_id, 'qm_contact_land_phones', true ) )
					? '<span class="py-1 d-block"><i class="icomoon-phone mr-3 text-primary"></i> <a href="tel:'. get_post_meta( $contact_id, 'qm_contact_land_phones', true ) .'" class="modal-contact-link">'. get_post_meta( $contact_id, 'qm_contact_land_phones', true ) .'</a></span>'
					: null;
$mobil_phone	= ( $contact_id && get_post_meta( $contact_id, 'qm_contact_mobil_phones', true ) )
					? '<span class="py-1 d-block"><i class="icomoon-phone mr-3 text-primary"></i> <a href="tel:'. get_post_meta( $contact_id, 'qm_contact_mobil_phones', true ) .'" class="modal-contact-link">'. get_post_meta( $contact_id, 'qm_contact_mobil_phones', true ) .'</a></span>'
					: null;
$email			= ( $contact_id && get_post_meta( $contact_id, 'qm_contact_email', true ) )
					? '<span class="py-1 d-block"><i class="icomoon-mail mr-3 text-primary"></i> <a href="mailto:'. get_post_meta( $contact_id, 'qm_contact_email', true ) .'" class="modal-contact-link">'. get_post_meta( $contact_id, 'qm_contact_email', true ) .'</a></span>'
					: null;
$request_email	= ( $contact_id && get_post_meta( $contact_id, 'qm_contact_request_email', true ) )
					? '<span class="py-1 d-block"><i class="icomoon-mail mr-3 text-primary"></i> <a href="mailto:'. get_post_meta( $contact_id, 'qm_contact_request_email', true ) .'" class="modal-contact-link">'. get_post_meta( $contact_id, 'qm_contact_request_email', true ) .'</a></span>'
					: null;

$checked		= ( isset( $_SESSION['qm_checkin_products'] ) && in_array( $post->ID, $_SESSION['qm_checkin_products'] ) ) ? 'qm-product-checked' : null;
$checkin 		= '<a href="#" class="btn btn-secondary rounded-circle mr-3 qm-checkin-product '. $checked .'" data-product-id="'. $post->ID .'" aria-label="'. __( 'Checkin', 'quimimpex' ) .'"><i class="icomoon-shopping-cart h6 mb-0"></i></a>';
$data_sheet		= ( get_post_meta( $post->ID, 'qm_data_sheet_url' ) )
					? '<a href="'. get_post_meta( $post->ID, 'qm_data_sheet_url', true ) .'" class="btn btn-secondary rounded-circle mr-3" aria-label="'. __( 'Data sheet', 'quimimpex' ) .'" download><i class="icomoon-text-document h6 mb-0"></i></a>'
					: null;
?>
		<?php do_action( 't_em_action_post_before' ); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php do_action( 't_em_action_post_inside_before' ); ?>
			<header class="full-width py-5">
				<div class="<?php t_em_container() ?>">
					<h1 class="entry-title m-0"><?php the_title(); ?></h1>
				</div>
			</header>

			<?php do_action( 't_em_action_post_content_before' ); ?>
			<div class="entry-content row mt-5">
				<div class="<?php echo t_em_grid( 5 ) ?>">
					<?php t_em_featured_post_thumbnail( 700, 460, false ) ?>
				</div>
				<div class="<?php echo t_em_grid( 7 ) ?>">
					<div class="d-flex justify-content-between align-items-center mb-4">
						<h4 class="mb-0"><?php the_title(); ?></h4>
						<div><?php echo $checkin; echo $data_sheet; ?></div>
					</div>
<?php
	$content = quimimpex_import_product_content_fields();
	foreach ( $content as $key => $value ) :
		if ( get_post_meta( $post->ID, $value['meta'] ) ) :
?>
					<div class="mb-3">
						<h6 class="font-weight-bold"><?php echo $value['label'] ?></h6>
						<?php echo t_em_wrap_paragraph( get_post_meta( $post->ID, $value['meta'], true ) ) ?>
					</div>
<?php
		endif;
	endforeach;
?>
					<h6 class="font-weight-bold border-bottom pb-2 mt-5 text-primary"><?php _e( 'Contact Information', 'quimimpex' ) ?></h6>
					<div class="row">
						<div class="contact-phone <?php echo t_em_grid( 6 ) ?>">
							<?php echo $land_phone; echo $mobil_phone ?>
						</div>
						<div class="contact-email <?php echo t_em_grid( 6 ) ?>">
							<?php echo $email; echo $request_email ?>
						</div>
					</div>
				</div>
			</div><!-- .entry-content -->

			<?php do_action( 't_em_action_post_content_after' ); ?>

			<footer class="entry-meta entry-meta-footer mb-3">
				<?php do_action( 't_em_action_entry_meta_footer' ) ?>
			</footer><!-- .entry-meta .entry-meta-footer -->

			<?php do_action( 't_em_action_post_inside_after' ); ?>
		</article><!-- #post-## -->
		<?php do_action( 't_em_action_post_after' ); ?>

<?php endwhile; // end of the loop. ?>

				<?php t_em_comments_template(); ?>
				<?php do_action( 't_em_action_content_after' ); ?>
			</section><!-- #content -->
			<?php get_sidebar(); ?>
			<?php get_sidebar( 'alt' ); ?>
		</section><!-- #main-content -->

<?php get_footer(); ?>
