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
	// Hide header on scroll down, show on scroll up
	// http://jsfiddle.net/mariusc23/s6mLJ/31/
	function qmMenuScroll( target, height ){
		var didScroll;
		var lastScrollTop = 0;
		var delta = 5;
		var targetHeight = height;

		$(window).scroll(function(event){
		    didScroll = true;
		});

		setInterval(function() {
		    if (didScroll) {
		        hasScrolled();
		        didScroll = false;
		    }
		}, 250);

		function hasScrolled() {
		    var st = $(this).scrollTop();

		    // Make sure they scroll more than delta
		    if(Math.abs(lastScrollTop - st) <= delta)
		        return;

		    // If they scrolled down and are past the target. Do stuff.
		    // This is necessary so you never see what is "behind" the target.
		    if (st > lastScrollTop && st > targetHeight){
		        // Scroll Down
		        $(target).fadeOut();
		    } else {
		        // Scroll Up
		        if(st + $(window).height() < $(document).height()) {
		            $(target).fadeIn();
		        }
		    }

		    lastScrollTop = st;
		}
	}
	// qmMenuScroll( $('#top-menu'), 500 );

	/**
	 * Register subscribers via Ajax
	 */
	$('#qm-newsletter-subscribe').click(function(e){
		e.preventDefault();
		var button	= $(e.relatedTarget);
		var widget	= $('.qm_newsletter_subscriber');
		var email 	= widget.find('input[name=qm_subscriber_email]').val();
		var loading	= '<div class="request-loading text-muted"><i class="icomoon-cycle"></i></div>';
		widget.find('.request-loading-wrapper').append(loading);

		$.ajax({
			url: ajaxurl,
			type: 'post',
			data: {
				email: email,
				action: 'email_subscriber',
				_qmnonce: qm_l10n._qmnonce,
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
		});
	});

	/**
	 * Cancel subscription
	 */
	$('#qm-newsletter-cancel').click(function(e){
		e.preventDefault();
		var button	= $(e.relatedTarget);
		var widget	= $('.qm_newsletter_subscriber');
		var email 	= widget.find('input[name=qm_subscriber_email]').val();
		var loading	= '<div class="request-loading text-muted"><i class="icomoon-cycle"></i></div>';
		widget.find('.request-loading-wrapper').append(loading);

		$.ajax({
			url: ajaxurl,
			type: 'post',
			data: {
				email: email,
				action: 'email_cancel_subscription',
				_qmnonce: qm_l10n._qmnonce,
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
		});
	})

	/**
	 * Select products via Ajax
	 *
	 * DEPRECATED
	 */
	$('#qm-select-line').change(function(e){
		e.preventDefault();
		var button			= $(e.relatedTarget);
		var form 			= $('#qm-contact-form');
		var cpt 			= form.find('input[name="qm_product_cpt"]').val();
		var tax 			= form.find('input[name="qm_product_tax"]').val();
		var term_id			= form.find('select[name="qm_select_line"]').val();
		var list_product	= form.find('#qm-list-products');
		var loading			= '<span class="request-loading text-muted"><i class="icomoon-cycle"></i></span>';

		form.find('label[for="qm-select-product"] small').append(loading);

		list_product
			.find('li')
			.remove();
		$('#qm-bad-request').remove();

		$.ajax({
			url: ajaxurl,
			type: 'post',
			data: {
				cpt: cpt,
				tax: tax,
				term_id: term_id,
				action: 'contact_form',
				_qmnonce: qm_l10n._qmnonce,
			},
			success: function(response){
				switch (response.status){
					case 'success':
						form.find('label[for="qm-select-product"] small')
							.text('');
						var opt = response.data.forEach(function(item, index, arr){
							var li = $('<li />', {
								'class': 'list-group-item d-flex align-items-center justify-content-between',
								 'data-item-id': item.id,
							})
							.appendTo(list_product);
							var txt = $('<span />')
								.text(item.title)
								.appendTo(li);
							$('<span class="qm-request-product btn btn-outline-primary btn-sm ml-2"><i class="icomoon-arrow-right2"></i></span>')
								.appendTo(li);
						});
						list_product.prop('disabled', false);
						break;
					case 'empty':
						form.find('label[for="qm-select-product"] small')
							.text('');
						break;
					case 'error':
						form.find('label[for="qm-select-product"] small')
							.text(response.msg);
						break;
					case 'bad_request':
						$('<p id="qm-bad-request">'+ response.msg +'</p>').insertBefore(form);
						break;
				}
			}
		});
	});

	/**
	 * Send products to the right panel
	 *
	 * DEPRECATED
	 */
	$(document).on('click', '#qm-contact-form .qm-request-product', function(){
		var item 			= $(this);
		var li 				= item.parent('li');
		var item_id			= li.attr('data-item-id');
		var products 		= $('#qm-list-selected-products');
		var product 		= li.clone();
		var all_products	= document.querySelectorAll('#qm-list-selected-products li[data-item-id]');

		product
			.find('.qm-request-product')
			.removeClass('qm-request-product')
			.addClass('qm-delete-product');

		var checked = $('<input />', {
			'type': 'checkbox',
			'value': item_id,
			'name': 'qm_products[]',
			'checked': 'checked',
			'class': 'd-none',
		})
			.appendTo(product);

		var action = product
					.find('.qm-delete-product');

		action.find('i').remove();
		$('<i class="icomoon-cross"></i>').appendTo(action);

		var arr = Object.values(all_products);

		/**
		 * TODO: Make this to work
		 * The product cannot be repeated
		 */
		if ( arr.includes(product) === false ){
			product.appendTo(products);
		}
	})

	/**
	 * Delete products from the list
	 *
	 * DEPRECATED
	 */
	$(document).on('click', '#qm-contact-form .qm-delete-product', function(){
		var item 			= $(this);
		var li 				= item.parent('li');

		li.remove();
	});

	/**
	 * Check in products
	 */
	$(document).on('click', '.qm-checkin-product', function(e){
		e.preventDefault();
		var item 		= $(this);
		var product_id	= item.attr('data-product-id');
		var counter		= $('#checkin-counter .counter');
		var counter_btn	= $('#checkin-counter .btn');

		$.ajax({
			url: ajaxurl,
			type: 'post',
			data: {
				product_id: product_id,
				action: 'checkin_product',
				_qmnonce: qm_l10n._qmnonce,
			},
			success: function(response){
				switch (response.status){
					case 'success':
						item.addClass('qm-product-checked')
							.removeClass('qm-product-unchecked')
						if( response.counter > 0 ){
							counter_btn.removeClass('btn-qm-blue-light')
										.addClass('btn-primary');
						}
						counter.text(response.counter);
						break;
					case 'bad_request':
						console.log(response.msg);
						break;
				}
			}
		});
	});

	/**
	 * Delete products from check in list
	 */
	$('.delete-checkin-product').click(function(e){
		e.preventDefault();
		var item 		= $(this);
		var target 		= item.attr('data-target');
		var product_id	= item.attr('data-product-id');
		var counter		= $('#checkin-counter .counter');
		var counter_btn	= $('#checkin-counter .btn');

		$.ajax({
			url: ajaxurl,
			type: 'post',
			data: {
				product_id: product_id,
				action: 'remove_checkin_product',
				_qmnonce: qm_l10n._qmnonce,
			},
			success: function(response){
				switch (response.status){
					case 'success':
						if( response.counter == 0 ){
							counter_btn.removeClass('btn-primary')
										.addClass('btn-qm-blue-light');
						}
						counter.text(response.counter);
						break;
					case 'error':
						console.log(response);
						break;
				}
			}
		});
		$(target).remove();
	});

	/**
	 * Modal
	 */
	$('#qm-modal').modal('handleUpdate');
	$('#qm-modal').on('shown.bs.modal', function(e){
		var obj 	= $(e.relatedTarget);
		var post_id	= obj.data('id');
		var loading	= '<div class="modal-loading text-center py-6 text-muted"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>';
		$('.modal-body').append(loading);

		$.ajax({
			url: ajaxurl,
			type: 'post',
			data: {
				post_id: post_id,
				action: 'qm_modal',
				_qmnonce: qm_l10n._qmnonce,
			},
			success: function(response){
				switch (response.status){
					case 'success':
						$('.modal-loading').remove();
						$('.modal-title').append(response.title);
						$('.modal-actions').append(response.checkin);
						$('.modal-actions').append(response.data_sheet);
						$('.modal-thumbnail').append(response.thumbnail);
						$('.modal-post-content').append(response.content);
						break;
					case 'error':
						console.log(response.msg);
						break;
				}
			}
		});
	});

	$('#qm-modal').on('hidden.bs.modal', function(e){
		$('.modal-title').empty();
		$('.modal-actions').empty();
		$('.modal-loading').empty();
		$('.modal-thumbnail').empty();
		$('.modal-title-description').empty();
		$('.modal-post-content').empty();
	})
});
