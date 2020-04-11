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
 * Add custom General Options
 *
 * @since Quimimpex 1.0
 */
function quimimpex_custom_general_options(){
?>
	<div class="sub-layout text-option general">
		<label class="description single-option">
			<p><?php _e( 'Company address', 'quimimpex' ) ?></p>
			<input type="text" class="regular-text" name="t_em_theme_options[company_address]" value="<?php echo t_em( 'company_address' ) ?>" />
		</label>
	</div>
	<div class="sub-layout text-option general">
		<label class="description single-option">
			<p><?php _e( 'Principal Contact Email', 'quimimpex' ) ?></p>
			<input type="email" class="regular-text" name="t_em_theme_options[principal_contact_email]" value="<?php echo t_em( 'principal_contact_email' ) ?>" />
		</label>
	</div>
	<div class="sub-layout text-option general">
		<label class="description single-option">
			<p><?php _e( 'Commercial Contact Email', 'quimimpex' ) ?></p>
			<input type="email" class="regular-text" name="t_em_theme_options[commercial_contact_email]" value="<?php echo t_em( 'commercial_contact_email' ) ?>" />
		</label>
	</div>
	<div class="sub-layout text-option general">
		<label class="description single-option">
			<p><?php _e( 'Principal Phone Number', 'quimimpex' ); ?></p>
			<input type="text" class="regular-text" name="t_em_theme_options[principal_contact_phone]" value="<?php echo t_em( 'principal_contact_phone' ) ?>" />
		</label>
	</div>
	<div class="sub-layout text-option general">
		<label class="description single-option">
			<p><?php _e( 'Commercial Phone Number', 'quimimpex' ); ?></p>
			<input type="text" class="regular-text" name="t_em_theme_options[commercial_contact_phone]" value="<?php echo t_em( 'commercial_contact_phone' ) ?>" />
		</label>
	</div>
	<div class="sub-layout text-option general">
		<label class="description single-option">
			<p><?php _e( 'Web Chat URL', 'quimimpex' ); ?></p>
			<input type="text" class="regular-text" name="t_em_theme_options[web_chat_url]" value="<?php echo t_em( 'web_chat_url' ) ?>" />
		</label>
	</div>
	<div class="sub-layout text-option general">
		<label class="description single-option">
			<p><?php _e( 'Webmail URL', 'quimimpex' ); ?></p>
			<input type="text" class="regular-text" name="t_em_theme_options[web_email_url]" value="<?php echo t_em( 'web_email_url' ) ?>" />
		</label>
	</div>
<?php
}
add_action( 't_em_admin_action_general_options_after', 'quimimpex_custom_general_options', 15 );
?>
