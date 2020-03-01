jQuery(document).ready(function($) {

	// Media Upload
	function quimimpexMediaUploader( button, parent ){
		$(button).click(function(event){
			event.preventDefault();
			var media_url = $(parent).find('.media-url');
			var media_id = $(parent).find('.media-id');

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
					media_url.val(attachment.attributes.url);
					media_id.val(attachment.attributes.id);
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
	quimimpexMediaUploader( '.media-selector', '#quimimpex-upload-data' );
});
