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

jQuery(document).ready(function($) {
	/**
	 * Register subscribers via Ajax
	 */
	$('#qm-newsletter-subscribe').click(function(e){
		e.preventDefault();
		var button	= $(e.relatedTarget);
		var widget	= $('.qm_newsletter_subscriber');
		var email 	= widget.find('input[name=qm_subscriber_email]').val();
		var nonce 	= widget.find('input[name=qm_newsletter_subscriber_field]').val();
		var loading	= '<div class="request-loading text-muted"><i class="icomoon-cycle"></i></div>';
		widget.find('.request-loading-wrapper').append(loading);

		$.ajax({
			url: qm_l10n.ajaxurl,
			type: 'post',
			data: {
				email: email,
				nonce: nonce,
			},
			success: function(response){
				switch (response.status){
					case 'success':
						widget.find('.request-loading-wrapper').html(response.msg);
						widget.find('input[name=qm_subscriber_email]').val('');
                        break;
                    case 'error':
						widget.find('.request-loading-wrapper').html(response.msg);
						break;
				}
			},
		})
	})
})
