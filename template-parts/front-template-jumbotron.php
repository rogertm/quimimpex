<?php
/**
 * Display Jumbotron template for Front Page text widgets
 */
foreach ( t_em_front_page_widgets_options() as $widget ) :
	if ( ! empty( t_em( 'headline_'.$widget['name'] ) ) || ! empty( t_em( 'content_'.$widget['name'] ) ) ) :
	$widget_icon_class	= ( t_em( 'headline_icon_class_'.$widget['name'] ) ) ?
		'<span class="'. t_em( 'headline_icon_class_'.$widget['name'] ) .'"></span> ' : '';

	$widget_thumbnail_url	= ( t_em( 'thumbnail_src_'.$widget['name'] ) ) ?
		'<div class="jumbo-widget-thumbnail"><img src="'. t_em( 'thumbnail_src_'.$widget['name'] ) .'" alt="'. sanitize_text_field( t_em( 'headline_'.$widget['name'] ) ).'" /></div>' : null;

	$widget_headline	= ( t_em( 'headline_'.$widget['name'] ) ) ?
		'<h2 class="jumbo-widget-heading text-body">'. $widget_icon_class . t_em( 'headline_'.$widget['name'] ) .'</h2>' : '';

	$widget_content		= ( t_em( 'content_'.$widget['name'] ) ) ?
		'<div class="jumbo-widget-body">'. t_em_wrap_paragraph( do_shortcode( t_em( 'content_'.$widget['name'] ) ) ) .'</div>' : '';

	$primary_link_text			= ( t_em( 'primary_button_text_'.$widget['name'] ) ) ? t_em( 'primary_button_text_'.$widget['name'] ) : null;
	$primary_link_icon_class	= ( t_em( 'primary_button_icon_class_'.$widget['name'] ) ) ? t_em( 'primary_button_icon_class_'.$widget['name'] ) : null;
	$primary_button_link 		= ( t_em( 'primary_button_link_'.$widget['name'] ) ) ? t_em( 'primary_button_link_'.$widget['name'] ) : null;
	$secondary_link_text		= ( t_em( 'secondary_button_text_'.$widget['name'] ) ) ? t_em( 'secondary_button_text_'.$widget['name'] ) : null;
	$secondary_link_icon_class	= ( t_em( 'secondary_button_icon_class_'.$widget['name'] ) ) ? t_em( 'secondary_button_icon_class_'.$widget['name'] ) : null;
	$secondary_button_link 		= ( t_em( 'secondary_button_link_'.$widget['name'] ) ) ? t_em( 'secondary_button_link_'.$widget['name'] ) : null;

	if ( ( $primary_button_link && $primary_link_text ) || ( $secondary_button_link && $secondary_link_text ) ) :
			$primary_button_link_url = ( $primary_button_link && $primary_link_text ) ?
				'<a href="'. $primary_button_link .'" class="btn btn-primary">
				<span class="'.$primary_link_icon_class.'"></span> <span class="button-text">'. $primary_link_text .'</span></a>' : null;

			$secondary_button_link_url = ( $secondary_button_link && $secondary_link_text ) ?
				'<a href="'. $secondary_button_link .'" class="btn btn-secondary">
				<span class="'.$secondary_link_icon_class.'"></span> <span class="button-text">'. $secondary_link_text .'</span></a>' : null;

		$widget_footer = '<div class="jumbo-widget-footer">'. $primary_button_link_url . ' ' . $secondary_button_link_url .'</div>';
	else :
		$widget_footer = null;
	endif;

	$section = ( $widget['name'] == 'text_widget_one' ) ? 'primary-featured-widget-area' : 'secondary-featured-widget-area';
	$jumbo = ( $widget['name'] == 'text_widget_one' ) ? 'jumbotron' : 'ih-item circle colored effect13 from_left_and_right';
?>
	<div <?php t_em_breakpoint( $section ); ?>>
<?php if ( $widget['name'] == 'text_widget_one' ) : ?>
		<div id="front-page-widget-<?php echo str_replace( 'text_widget_', '', $widget['name'] ) ?>" class="jumbo-widget my-0 jumbotron">
			<?php	echo $widget_headline;
					echo $widget_content;
					echo $widget_footer; ?>
		</div>
<?php else :
		$widget_left = ( $widget['name'] == 'text_widget_two' ) ? 'ml-auto' : null;
		$widget_right = ( $widget['name'] == 'text_widget_three' ) ? 'mr-auto' : null;
?>
		<div class="d-flex justify-content-center pb-8 <?php echo t_em_grid( 7 ) .' '. $widget_left . $widget_right ?>">
			<div id="front-page-widget-<?php echo str_replace( 'text_widget_', '', $widget['name'] ) ?>" class="jumbo-widget mb-5 ih-item circle colored effect13 from_left_and_right">
				<a href="<?php echo $primary_button_link ?>" class="d-block">
					<div class="img">
						<?php echo $widget_thumbnail_url; ?>
					</div>
					<div class="info">
						<div class="info-back">
							<span class="h2 mt-5 mb-4 d-block"><i class="qmicon-search"></i></span>
							<span class="text-white"><?php echo $widget_content; ?></span>
						</div>
					</div>
					<div class="text-center pt-3 border-top mt-6">
						<?php echo $widget_headline; ?>
					</div>
				</a>
			</div>
		</div>
<?php endif; ?>
	</div>
<?php
	endif;
endforeach;
?>
