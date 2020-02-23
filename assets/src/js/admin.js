jQuery(document).ready(function($) {

	// Media Upload
	function quimimpexMediaUploader( container, button ){
		var clicked_button = false;
		var container_id = false;

		$(button).click(function(event){
			event.preventDefault();
			var clicked_button = $(this);
			var button_id = $(clicked_button).attr('id');
			var container_id = $(clicked_button).siblings(container).attr('id');

			// configuration of the media manager new instance
			wp.media.frames.qm_frame = wp.media({
				title: qm_l10n_admin.upm_title,
				multiple: false,
				library: {
					type: 'application/pdf',
				},
				button: {
					text: qm_l10n_admin.upm_button,
				}
			});

			// Function used for the object selection and media manager closing
			var qm_upload_media = function(){
				var selection = wp.media.frames.qm_frame.state().get('selection');

				// If no selection
				if (!selection) {
					return;
				}

				// iterate through selected elements
				selection.each(function(attachment){
					var file = attachment.attributes.url;
					var element = $('#'+container_id);
					$(element).val(file);

				})
			};

			// closing event for media manger
			wp.media.frames.qm_frame.on('close', null);
			// media selection event
			wp.media.frames.qm_frame.on('select', qm_upload_media);
			// showing media manager
			wp.media.frames.qm_frame.open();

		});
	}
	quimimpexMediaUploader( '.media-url', '.media-selector' );
});
