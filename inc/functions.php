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
 * Quimimpex Setup
 *
 * @since Quimimpex 1.0
 */
function quimimpex_setup(){
	// Make Quimimpex available for translation.
	load_child_theme_textdomain( 'quimimpex', get_stylesheet_directory() . '/languages' );

	// Remove Hooks
	remove_action( 't_em_action_entry_meta_header', 't_em_post_author' );
	remove_action( 't_em_action_entry_meta_footer', 't_em_posted_in' );
	remove_action( 't_em_action_entry_meta_footer', 't_em_post_shortlink' );
	remove_action( 't_em_action_entry_meta_footer', 't_em_comments_link' );
	remove_action( 't_em_action_post_after', 't_em_single_navigation', 5 );
	remove_action( 't_em_action_content_before', 't_em_breadcrumb', 5 );
	remove_filter( 'get_the_excerpt', 't_em_custom_excerpt_more' );

	// Add Hooks
	add_action( 't_em_action_post_content_before', 't_em_breadcrumb' );

	// Remove Admin Hooks
	add_filter( 't_em_admin_filter_header_options_no_header_image', '__return_false' );
	add_filter( 't_em_admin_filter_header_options_header_image', '__return_false' );
	add_filter( 't_em_admin_filter_header_options_static_header', '__return_false' );
	add_filter( 't_em_admin_filter_front_page_options_wp_front_page', '__return_false' );
	add_filter( 't_em_admin_filter_archive_options_the_content', '__return_false' );
	remove_action( 't_em_admin_action_layout_options_after', 't_em_layout_width' );
}
add_action( 'after_setup_theme', 'quimimpex_setup' );

/**
 * Layout Options
 *
 * @since Qiomimpex 1.0
 */
function quimimpex_layout_options( $layout_options = '' ){
	unset( $layout_options['two-columns-content-left'] );
	unset( $layout_options['two-columns-content-right'] );
	unset( $layout_options['three-columns-content-left'] );
	unset( $layout_options['three-columns-content-right'] );
	unset( $layout_options['three-columns-content-middle'] );
	return $layout_options;
}
add_filter( 't_em_admin_filter_layout_options', 'quimimpex_layout_options' );

/**
 * Footer Options
 *
 * @since Qiomimpex 1.0
 */
function quimimpex_footer_options( $footer_options = '' ){
	unset( $footer_options['four-footer-widget'] );
	unset( $footer_options['two-footer-widget'] );
	unset( $footer_options['one-footer-widget'] );
	unset( $footer_options['no-footer-widget'] );
	return $footer_options;
}
add_filter( 't_em_admin_filter_footer_options', 'quimimpex_footer_options' );

/**
 * Pagination Options
 *
 * @since quimimpex 1.0
 */
function quimimpex_pagination_options( $archive_pagination = '' ){
	unset( $archive_pagination['prev-next'] );
	return $archive_pagination;
}
add_filter( 't_em_admin_filter_archive_pagination_options', 'quimimpex_pagination_options' );

/**
 * Avoid access to admin panel
 *
 * @since Quimimpex 1.0
 */
function quimimpex_avoid_admin_panel(){
	if ( is_admin() && current_user_can( 'subscriber' ) && ! wp_doing_ajax() ) :
		wp_redirect( home_url() );
		exit;
	endif;
}
add_action( 'init', 'quimimpex_avoid_admin_panel' );

/**
 * Hide admin bar
 *
 * @since Quimimpex 1.0
 */
function quimimpex_hide_admin_bar(){
	if ( current_user_can( 'subscriber' ) )
		return show_admin_bar( false );
}
add_action( 'init', 'quimimpex_hide_admin_bar' );

/**
 * Add custom body classes
 *
 * @since Quimimpex 1.0
 */
function quimimpex_body_class( $classes ){
	global $post;
	if ( is_page() && has_post_thumbnail( $post->id ) ) :
		$classes[] = 'page-has-thumbnail';
	endif;

	if ( is_page( t_em( 'page_blog' ) ) || is_search() || is_archive() ) :
		$classes[] = 'qm-page-archive';
	endif;

	return $classes;
}
add_action( 'body_class', 'quimimpex_body_class' );

/**
 * Make some redirections
 *
 * @since Quimimpex 1.0
 */
function quimimpex_redirect(){
	if ( ! is_admin() && is_post_type_archive( 'qm-export-product' ) ) :
		wp_redirect( get_permalink( t_em( 'page_export_lines' ) ) );
		exit;
	endif;
	if ( ! is_admin() && is_post_type_archive( 'qm-import-product' ) ) :
		wp_redirect( get_permalink( t_em( 'page_import_lines' ) ) );
		exit;
	endif;
}
add_action( 'wp', 'quimimpex_redirect' );

/**
 * Process Contact Form
 *
 * @since Quimimpex 1.0
 */
function quimimpex_process_contact_form(){
	if ( ! isset( $_POST['qm_contact_form_field'] )
		|| ! wp_verify_nonce( $_POST['qm_contact_form_field'], 'qm_contact_form_attr' ) )
		return;

	$author			= ( isset( $_POST['qm_comment_author'] ) && ! empty( $_POST['qm_comment_author'] ) ) ? $_POST['qm_comment_author'] : null;
	$author_email	= ( isset( $_POST['qm_comment_author_email'] ) && ! empty( $_POST['qm_comment_author_email'] ) ) ? $_POST['qm_comment_author_email'] : null;
	$comment 		= ( isset( $_POST['qm_comment_content'] ) && ! empty( $_POST['qm_comment_content'] ) ) ? $_POST['qm_comment_content'] : null;

	if ( ! $author ) :
		$query = array(
			'do_action'	=> 'error',
			'status'	=> 'empty-author',
		);
		wp_redirect( add_query_arg( $query, get_permalink( $_POST['qm_post_id'] ) ) );
		exit;
	endif;

	if ( ! $author_email ) :
		$query = array(
			'do_action'	=> 'error',
			'status'	=> 'empty-email',
		);
		wp_redirect( add_query_arg( $query, get_permalink( $_POST['qm_post_id'] ) ) );
		exit;
	endif;

	if ( ! $comment ) :
		$query = array(
			'do_action'	=> 'error',
			'status'	=> 'empty-comment',
		);
		wp_redirect( add_query_arg( $query, get_permalink( $_POST['qm_post_id'] ) ) );
		exit;
	endif;

	$commentdata = array(
		'comment_approved'		=> 0,
		'comment_author'		=> $_POST['qm_comment_author'],
		'comment_author_email'	=> $_POST['qm_comment_author_email'],
		'comment_content'		=> $comment,
		'comment_post_ID'		=> $_POST['qm_post_id'],
		'comment_type'			=> 'qm_product_request',
		'comment_meta'			=> array( 'qm_products' => $_POST['qm_products'] ),
	);
	$comment = wp_insert_comment( $commentdata );

	$query = array(
		'do_action'	=> 'success',
		'status'	=> 'requested',
	);

	wp_redirect( add_query_arg( $query, get_permalink( $_POST['qm_post_id'] ) ) );
	exit;
}
add_action( 'template_redirect', 'quimimpex_process_contact_form' );

/**
 * Process products check in form
 *
 * @since Quimimpex 1.0
 */
function quimimpex_process_products_checkin_form(){
	if ( ! isset( $_POST['qm_checkin_form_field'] )
		|| ! wp_verify_nonce( $_POST['qm_checkin_form_field'], 'qm_checkin_form_attr' ) )
		return;

	$products 		= ( isset( $_POST['qm_checkin_product'] ) && ! empty( $_POST['qm_checkin_product'] ) ) ? $_POST['qm_checkin_product'] : null;
	$author			= ( isset( $_POST['qm_comment_author'] ) && ! empty( $_POST['qm_comment_author'] ) ) ? $_POST['qm_comment_author'] : null;
	$author_email	= ( isset( $_POST['qm_comment_author_email'] ) && ! empty( $_POST['qm_comment_author_email'] ) ) ? $_POST['qm_comment_author_email'] : null;

	if ( ! $products ) :
		$query = array(
			'do_action'	=> 'error',
			'status'	=> 'empty-products',
		);
		wp_redirect( add_query_arg( $query, get_permalink( $_POST['qm_post_id'] ) ) );
		exit;
	endif;

	if ( ! $author ) :
		$query = array(
			'do_action'	=> 'error',
			'status'	=> 'empty-author',
		);
		wp_redirect( add_query_arg( $query, get_permalink( $_POST['qm_post_id'] ) ) );
		exit;
	endif;

	if ( ! $author_email ) :
		$query = array(
			'do_action'	=> 'error',
			'status'	=> 'empty-email',
		);
		wp_redirect( add_query_arg( $query, get_permalink( $_POST['qm_post_id'] ) ) );
		exit;
	endif;

	$args = array(
		'post_type'			=> array( 'qm-import-product', 'qm-export-product' ),
		'posts_per_page'	=> -1,
		'post__in'			=> $products,
	);

	$products = get_posts( $args );

	$import_products = [];
	$export_products = [];

	$content  = '<h3>'. __( 'Product Request', 'quimimpex' ) .'</h3>';
	$content .= '<h5>'. __( 'Product List:', 'quimimpex' ) .'</h5>';
	$content .= '<ul>';
	foreach ( $products as $product ) :
		if ( get_post_type( $product->ID ) == 'qm-import-product' )
			array_push( $import_products, $product->ID );
		if ( get_post_type( $product->ID ) == 'qm-export-product' )
			array_push( $export_products, $product->ID );

		$content .= '<li><a href="'. get_permalink( $product->ID ) .'" target="_blanck">'. $product->post_title .'</a></li>';
	endforeach;
	$content .= '</ul>';
	$content .= '<h3>'. __( 'Comment:', 'quimimpex' ) .'</h3>';
	$content .= ( $_POST['qm_comment_content'] != '' ) ? $_POST['qm_comment_content'] : __( 'No comment', 'quimimpex' );

	$commentdata = array(
		'comment_approved'		=> 0,
		'comment_author'		=> $_POST['qm_comment_author'],
		'comment_author_email'	=> $_POST['qm_comment_author_email'],
		'comment_content'		=> $content,
		'comment_post_ID'		=> $_POST['qm_post_id'],
		'comment_type'			=> 'qm_product_request',
		'comment_meta'			=> array( 'qm_products' => $_POST['qm_products'] ),
	);
	$comment = wp_insert_comment( $commentdata );

	$to 		= t_em( 'commercial_contact_email' );
	$subject	= __( 'Product Request', 'quimimpex' );
	$message	= __( 'QUIMIMPEX thanks you for your interest in receiving our services. Your request will be attended by our specialists.' );

	// Send email for import products
	if ( ! empty( $import_products ) ) :
		$message	.= '<h5>'. __( 'Import Product:', 'quimimpex' ) .'</h5>';
		$message 	.= '<ul>';
		$import_cc 	= array();
		foreach ( $import_products as $product ) :
			$contact_id		= ( get_post_meta( $product, 'qm_product_contact') ) ? get_post_meta( $product, 'qm_product_contact', true ) : null;
			$request_email	= ( $contact_id && get_post_meta( $contact_id, 'qm_contact_request_email' ) ) ? get_post_meta( $contact_id, 'qm_contact_request_email', true ) : null;
			$email 			= ( $contact_id && get_post_meta( $contact_id, 'qm_contact_email' ) ) ? get_post_meta( $contact_id, 'qm_contact_email', true ) : null;
			array_push( $import_cc, $request_email ?? $email );
			$message .= '<li><a href="'. get_permalink( $product ) .'" target="_blanck">'. get_post_field( 'post_title', $product ) .'</a></li>';
		endforeach;
		$message 	.= '</ul>';
		$headers[]	= __( 'From: Quimimpex <no-replay@quimimpex.cu>' );
		$headers[]	= 'Cc: '. $author_email;
		$headers[]	= 'Cc: '. implode(', ', $import_cc );
		$headers[]	= 'Content-type: text/html';
		$import_mail = wp_mail( $to, $subject, $message, $headers );
	endif;

	// Send email for export products
	if ( ! empty( $export_products ) ) :
		$message	.= '<h5>'. __( 'Export Product:', 'quimimpex' ) .'</h5>';
		$message 	.= '<ul>';
		$export_cc	= array();
		foreach ( $export_products as $product ) :
			$contact_id		= ( get_post_meta( $product, 'qm_product_contact') ) ? get_post_meta( $product, 'qm_product_contact', true ) : null;
			$request_email	= ( $contact_id && get_post_meta( $contact_id, 'qm_contact_request_email' ) ) ? get_post_meta( $contact_id, 'qm_contact_request_email', true ) : null;
			$email 			= ( $contact_id && get_post_meta( $contact_id, 'qm_contact_email' ) ) ? get_post_meta( $contact_id, 'qm_contact_email', true ) : null;
			array_push( $export_cc, $request_email ?? $email );
			$message .= '<li><a href="'. get_permalink( $product ) .'" target="_blanck">'. get_post_field( 'post_title', $product ) .'</a></li>';
		endforeach;
		$message 	.= '</ul>';
		$headers[]	= __( 'From: Quimimpex <no-replay@quimimpex.cu>' );
		$headers[]	= 'Cc: '. $author_email;
		$headers[]	= 'Cc: '. implode( ', ', $export_cc );
		$headers[]	= 'Content-type: text/html';
		$export_mail = wp_mail( $to, $subject, $message, $headers );
	endif;

	$query = array(
		'do_action'	=> 'success',
		'status'	=> 'requested',
	);

	$_SESSION['qm_checkin_products'] = null;

	wp_redirect( add_query_arg( $query, get_permalink( $_POST['qm_post_id'] ) ) );
	exit;
}
add_action( 'template_redirect', 'quimimpex_process_products_checkin_form' );

/**
 * Process cancel subscription
 *
 * @since Quimimpex 1.0
 */
function quimimpex_do_cancel_subscription(){
	if ( ! is_page( t_em( 'page_cancel_subscription' ) ) )
		return;
	$email = ( isset( $_GET['email'] ) ) ? $_GET['email'] : null;
	if ( ! $email && ! wp_verify_nonce( $email, '_qmnonce' ) )
		return;

	$user = get_user_by( 'email', $email );
	require_once( ABSPATH.'wp-admin/includes/user.php' );
	wp_delete_user( $user->id );

	$args = array(
		'unsubscribed'	=> 'success',
	);
	wp_safe_redirect( add_query_arg( $args, get_permalink( t_em( 'page_cancel_subscription' ) ) ) );
	exit;
}
add_action( 'template_redirect', 'quimimpex_do_cancel_subscription' );

/**
 * Add custom Comment Types
 *
 * @since Quimimpex 1.0
 */
function quimimpex_comment_types( $comment_types ){
	$comments = array_merge( $comment_types,
					array(
						'qm_product_request'	=> __( 'Product Requests', 'quimimpex' ),
					)
				);
	return $comments;
}
add_action( 'admin_comment_types_dropdown', 'quimimpex_comment_types' );

/**
 * Add custom mime types to upload
 *
 * @since Quimimpex 1.0
 */
function quimimpex_custom_mime_types( $mime_types ){
	$mime_types['svg'] = 'image/svg+xml';
	return $mime_types;
}
add_filter( 'upload_mimes', 'quimimpex_custom_mime_types' );

/**
 * Get all subscribers email
 * @return array
 *
 * @since Quimimpex 1.0
 */
function quimimpex_get_subscribers_email(){
	$args = [
		'role'	=> 'subscriber',
	];
	$subscribers = get_users( $args );
	$emails = [];
	foreach ( $subscribers as $subscriber ) :
		array_push( $emails, $subscriber->user_email );
	endforeach;
	return $emails;
}

/**
 * Register custom status for newsletters
 *
 * @since Quimimpex 1.0
 */
function quimimpex_newsletter_status(){
	$unsent = [
		'label'						=> __( 'Unsent', 'quimimpex' ),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Unsent <span class="count">(%s)</span>', 'Unsent <span class="count">(%s)</span>', 'quimimpex' ),
	];
	register_post_status( 'unsent', $unsent );

	$sent = [
		'label'						=> __( 'Sent', 'quimimpex' ),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Sent <span class="count">(%s)</span>', 'Sent <span class="count">(%s)</span>', 'quimimpex' ),
	];
	register_post_status( 'sent', $sent );
}
add_action( 'init', 'quimimpex_newsletter_status' );

/**
 * Send the newsletter
 *
 * @since Quimimpex 1.0
 */
function quimimpex_send_newsletter( $new_status, $old_status, $post ){
	if ( $new_status === 'unsent'
		&& $old_status !== 'unsent'
		&& $post->post_type === 'qm-newsletter' ) :

		$attachment_id 	= get_post_meta( $post->ID, 'qm_newsletter_id', true );
		$to 			= quimimpex_get_subscribers_email();
		$subject		= __( 'Quimimpex Newsletter', 'quimimpex' );
		$message		= __( 'You receive this message be cause you are subscribed to our newsletter', 'quimimpex' );
		$headers[]		= 'From: Quimimpex <no-replay@quimimpex.cu>';
		$attachment		= get_attached_file( $attachment_id );
		if ( ! $attachment ) :
			$status = 'unsent';
		else :
			$status = 'sent';
			wp_mail( $to, $subject, $message, $headers, $attachment );
		endif;

		$post_data = [
			'ID'			=> $post->ID,
			'post_status'	=> $status,
		];
		wp_update_post( $post_data );
	endif;
}
add_action( 'transition_post_status', 'quimimpex_send_newsletter', 10, 3 );

/**
 * Set default image
 * @param int $post_id Optional. Post ID. Default is ID of the global $post.
 *
 * @since Quimimpex 1.0
 */
function quimimpex_default_image( $post_id = 0 ){
	$post_id = absint( $post_id );
	if ( ! $post_id )
		$post_id = get_the_ID();

	if ( get_post_type( $post_id ) == 'qm-export-product' || get_post_type( $post_id ) == 'qm-import-product' ) :
		$taxonomy = ( get_post_type( $post_id ) == 'qm-export-product' ) ? 'qm-export-line' : 'qm-import-line';
		$term = wp_get_post_terms( $post_id, $taxonomy, array( 'fields' => 'ids' ) );
		$image = ( $term ) ? get_term_meta( $term[0], 'qm_taxonomy_image', true ) : null;
		return get_stylesheet_directory_uri() .'/assets/images/'. $image;
	endif;
}
add_filter( 't_em_filter_default_post_thumbnail', 'quimimpex_default_image' );

/**
 * Custom social network options
 * @return array
 *
 * @since Quimimpex 1.0
 */
function quimimpex_social_network_options(){
	$options = [
		'facebook_set' => array(
			'name' => 'facebook_set',
			'label' => __( 'Facebook URL', 't_em' ),
			'item' => __( 'Facebook', 't_em' ),
			'class' => 'qmicon-facebook',
			'order' => '0',
		),
		'twitter_set' => array(
			'name' => 'twitter_set',
			'label' => __( 'Twitter URL', 't_em' ),
			'item' => __( 'Twitter', 't_em' ),
			'class' => 'qmicon-twitter',
			'order' => '10',
		),
		'youtube_set' => array(
			'name' => 'youtube_set',
			'label' => __( 'Youtube URL', 't_em' ),
			'item' => __( 'Youtube', 't_em' ),
			'class' => 'qmicon-youtube',
			'order' => '20',
		),
		'instagram_set' => array(
			'name' => 'instagram_set',
			'label' => __( 'Instagram URL', 't_em' ),
			'item' => __( 'Instagram', 't_em' ),
			'class' => 'qmicon-instagram',
			'order' => '30',
		),
	];
	return apply_filters( 'quimimpex_social_network_options', $options );
}
add_filter( 't_em_admin_filter_social_network_options', 'quimimpex_social_network_options' );

/**
 * Exclude some post types from search results
 *
 * @since Quimimpex 1.0
 */
function quimimpex_search_filter( $query ){
	if( ! is_admin() && $query->is_main_query() ) :
		if( $query->is_search ) :
			$query->set( 'post_type', ['qm-export-product', 'qm-import-product'] );
		endif;
	endif;
}
add_action( 'pre_get_posts', 'quimimpex_search_filter' );

/**
 * Highlight search results
 *
 * @since Quimimpex 1.0
 */
function quimimpex_highlight_search_results( $excerpt, $post ){
	if ( ! is_search() )
		return $excerpt;
	$keys = explode( ' ', get_search_query() );

	$description = ( get_post_meta( $post->ID, 'qm_product_description' ) )
					? wp_trim_words( get_post_meta( $post->ID, 'qm_product_description', true ), 35, '...' )
					: null;

	$use 		 = ( get_post_meta( $post->ID, 'qm_product_use' ) )
					? wp_trim_words( get_post_meta( $post->ID, 'qm_product_use', true ), 35, '...' )
					: null;

	$content = sprintf( __( '<strong>Description:</strong> %s | <strong>Use:</strong> %s' ), $description, $use );
	$content = str_replace( ["\r", "\n", "\t"], ' ', $content );

	$excerpt = preg_replace( '/('.implode('|', $keys) .')/iu', '<strong class="search-highlight">\0</strong>', $content );
	return $excerpt;
}
add_filter( 'get_the_excerpt', 'quimimpex_highlight_search_results', 10, 2 );
?>
