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
 * Widget to subscribe users as Subscribers
 *
 * @since Quimimpex 1.0
 */
class Quimimpex_Newsletter_Subscribers extends WP_Widget{
	function __construct(){
		$args = array(
			'classname'		=> 'qm_newsletter_subscriber',
			'description'	=> __( 'Newsletter Subscriber', 'quimimpex' ),
		);
		parent::__construct( 'qm_newsletter_subscriber', sprintf( __( '%1$s Newsletter Subscribers', 'quimimpex' ), '[QM]' ), $args );
		$this->alt_option_name = 'qm_newsletter_subscriber';
	}

	function widget( $args, $instance ){
		$cache = wp_cache_get( 'qm_newsletter_subscriber', 'widget' );

		if ( ! is_array( $cache ) )
			$cache = array();

		if ( ! isset( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		if ( isset( $cache[ $args['widget_id'] ] ) ) :
			echo $cache[ $args['widget_id'] ];
			return;
		endif;

		ob_start();
		extract( $args );

		$title = apply_filters( 'widget_title',
								empty( $instance['title'] ) ? __( 'Subscribe to our Newsletter', 'quimimpex' ) : $instance['title'],
								$instance,
								$this->id_base );
		$description = ( ! empty( $instance['description'] ) ) ? $instance['description'] : null;

		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
		?>
		<form class="form-inline" method="post">
			<?php wp_nonce_field( 'qm_newsletter_subscriber_attr', 'qm_newsletter_subscriber_field' ); ?>
			<?php if ( $description ) : ?>
				<p><?php echo $description; ?></p>
			<?php endif; ?>
			<div class="form-row">
				<div class="request-loading-wrapper <?php echo t_em_grid( 12 ) ?>"></div>
				<div class="form-group <?php echo t_em_grid( 8 ) ?>">
					<label for="qm_subscriber_email" class="sr-only"><?php _e( 'Email address', 'quimimpex' ); ?></label>
					<input id="qm_subscriber_email" class="form-control" type="email" name="qm_subscriber_email" placeholder="<?php _e( 'Subscribe', 'quimimpex' ) ?>" required>
				</div>
				<div class="form-group <?php echo t_em_grid( 4 ) ?>">
					<button id="qm-newsletter-subscribe" class="btn btn-secondary btn-block" type="button">
						<?php _e( 'Subscribe', 'quimimpex' ); ?>
					</button>
				</div>
			</div>
		</form>
		<?php
		echo $after_widget;

		$cache[ $args['widget_id'] ] = ob_get_flush();
		wp_cache_set( 'qm_newsletter_subscriber', $cache, 'widget' );
	}

	function update( $new_instance, $old_instance ){
		$instance = array();
		$instance['title'] 			= strip_tags( $new_instance['title'] );
		$instance['description']	= strip_tags( $new_instance['description'] );
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['qm_newsletter_subscriber'] ) )
			delete_option( 'qm_newsletter_subscriber' );

		return $instance;
	}

	function flush_widget_cache(){
		wp_cache_delete( 'qm_newsletter_subscriber', 'widget' );
	}

	function form( $instance ){
		$instance = wp_parse_args(
						(array) $instance,
						array(
							'description'	=> null,
							'title'			=> '',
						)
					);
		$title 			= isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : null;
		$description	= isset( $instance['description'] ) ? esc_attr( $instance['description'] ) : null;
	?>
		<p><label for="<?php echo $this->get_field_id('title') ?>"><?php _e( 'Title:', 'quimimpex' ); ?></label><br>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('description') ?>"><?php _e( 'Description:', 'quimimpex' ); ?></label><br>
			<input class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" type="text" value="<?php echo $description; ?>" /></p>
	<?php
	}
}

/**
 * Register widgets
 */
function quimimpex_register_widgets() {
	register_widget( 'Quimimpex_Newsletter_Subscribers' );
}
add_action( 'widgets_init', 'quimimpex_register_widgets' );
?>
