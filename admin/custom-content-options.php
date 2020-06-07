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
 * WP_Query arguments
 * @param string $type 	Required 'post_type'
 *
 * @since Quimimpex 1.0
 */
function quimimpex_custom_content_query( $type ){
	$args = array(
		'post_type'			=> $type,
		'posts_per_page'	=> -1,
		'orderby'			=> 'menu_order',
		'post_status'		=> array( 'publish', 'private' ),
		'order'				=> 'ASC',
	);
	$get_args = get_posts( $args );
	sort( $get_args );
	return apply_filters( 'quimimpex_custom_content_query', $get_args );
}

/**
 * Custom Pages builder
 *
 * @since Quimimpex 1.0
 */
function quimimpex_custom_pages(){
	$custom_pages = array(
		'page_blog'			=> array(
			'value'			=> 'page_blog',
			'label'			=> __( 'Page Blog', 'quimimpex' ),
			'public_label'	=> __( 'Blog', 'quimimpex' ),
			'type'			=> 'page',
		),
		'page_about_us'		=> array(
			'value'			=> 'page_about_us',
			'label'			=> __( 'Page About Us', 'quimimpex' ),
			'public_label'	=> __( 'About Us', 'quimimpex' ),
			'type'			=> 'page',
		),
		'page_inport_lines'	=> array(
			'value'			=> 'page_inport_lines',
			'label'			=> __( 'Page Import Lines', 'quimimpex' ),
			'public_label'	=> __( 'Import Lines', 'quimimpex' ),
			'type'			=> 'page',
		),
		'page_export_lines'	=> array(
			'value'			=> 'page_export_lines',
			'label'			=> __( 'Page Export Lines', 'quimimpex' ),
			'public_label'	=> __( 'Export Lines', 'quimimpex' ),
			'type'			=> 'page',
		),
		'page_docs'			=> array(
			'value'			=> 'page_docs',
			'label'			=> __( 'Page Documents', 'quimimpex' ),
			'public_label'	=> __( 'Documents', 'quimimpex' ),
			'type'			=> 'page',
		),
		'page_checkin'		=> array(
			'value'			=> 'page_checkin',
			'label'			=> __( 'Page Check In', 'quimimpex' ),
			'public_label'	=> __( 'Check In', 'quimimpex' ),
			'type'			=> 'page',
		),
		'page_cancel_subscription'	=> array(
			'value'			=> 'page_cancel_subscription',
			'label'			=> __( 'Page Cancel Subscription', 'quimimpex' ),
			'public_label'	=> __( 'Cancel Subscription', 'quimimpex' ),
			'type'			=> 'page',
		),
	);
	return apply_filters( 'quimimpex_custom_pages', $custom_pages );
}

/**
 * Render the form in Custom Page Options in Twenty'em admin panel
 *
 * @since Quimimpex 1.0
 */
function quimimpex_setting_fields_custom_pages(){
?>
	<h2><?php _e( 'Custom Pages', 'quimimpex' ) ?></h2>
<?php
	foreach ( quimimpex_custom_pages() as $page ) :
?>
	<div class="text-option custom-pages">
		<label class="">
			<span><?php echo $page['label']; ?></span>
			<select name="t_em_theme_options[<?php echo $page['value'] ?>]">
				<option value="0"><?php _e( '&mdash; Select &mdash;', 'quimimpex' ); ?></option>
				<?php foreach ( quimimpex_custom_content_query( $page['type'] ) as $list ) :
				?>
					<?php $selected = selected( t_em( $page['value'] ), $list->ID, false ); ?>
					<option value="<?php echo $list->ID ?>" <?php echo $selected; ?>><?php echo $list->post_title ?></option>
				<?php endforeach; ?>
			</select>
		</label>
		<?php if ( t_em( $page['value'] ) ) : ?>
			<div class="row-action">
				<span class="edit" style="display: inline;"><a href="<?php echo get_edit_post_link( t_em( $page['value'] ) ) ?>"><?php _e( 'Edit', 'quimimpex' ) ?></a> | </span>
				<span class="view" style="display: inline;"><a href="<?php echo get_permalink( t_em( $page['value'] ) ) ?>"><?php _e( 'View', 'quimimpex' ) ?></a></span>
			</div>
		<?php endif; ?>
	</div>
<?php
	endforeach;
}
?>
