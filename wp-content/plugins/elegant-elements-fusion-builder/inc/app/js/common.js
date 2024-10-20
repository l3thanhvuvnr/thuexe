( function() {

	'use strict';
	// Handle the multi image upload.
	jQuery( 'body' ).on( 'click', '.elegant-elements-upload-images', function( event ) {
		var $thisEl,
			fileFrame,
			multiImageContainer,
			multiImageInput,
			multiImages    = true,
			multiImageHtml = '',
			ids            = '',
			attachment     = '',
			attachments    = [];

		if ( event ) {
			event.preventDefault();
		}

		$thisEl = jQuery( this );

		multiImageContainer = jQuery( $thisEl.next( '.fusion-multiple-image-container' ) )[ 0 ];
		multiImageInput     = jQuery( $thisEl ).prev( '.fusion-multi-image-input' );

		fileFrame = wp.media( { // eslint-disable-line camelcase
			library: {
				type: $thisEl.data( 'type' )
			},
			title: $thisEl.data( 'title' ),
			multiple: 'between',
			frame: 'post',
			className: 'media-frame mode-select fusion-builder-media-dialog wp-admin ' + $thisEl.data( 'id' ),
			displayUserSettings: false,
			displaySettings: true,
			allowLocalEdits: true
		} );
		wp.media.frames.file_frame = fileFrame;

		ids         = multiImageInput.val().split( ',' );
		attachments = [];
		attachment  = '';

		jQuery.each( ids, function( index, id ) {
			if ( '' !== id && 'NaN' !== id ) {
				attachment = wp.media.attachment( id );
				attachment.fetch();
				attachments.push( attachment );
			}
		} );

		wp.media._galleryDefaults.link  = 'none';
		wp.media._galleryDefaults.size  = 'thumbnail';
		fileFrame.options.syncSelection = true;

		fileFrame.options.state = ( attachments.length ) ? 'gallery-edit' : 'gallery';

		// Select currently active image automatically.
		fileFrame.on( 'open', function() {
			var selection = fileFrame.state().get( 'selection' ),
				library   = fileFrame.state().get( 'library' );

			if ( multiImages ) {
				if ( 'gallery-edit' !== fileFrame.options.state ) {
					jQuery( '.fusion-builder-media-dialog' ).addClass( 'hide-menu' );
				}
				selection.add( attachments );
				library.add( attachments );
			} else {
				jQuery( '.fusion-builder-media-dialog' ).addClass( 'hide-menu' );
			}
		} );

		// Set the attachment ids from gallery selection.
		if ( multiImages ) {
			fileFrame.on( 'update', function( selection ) {
				var imageIDs = '',
					imageURL = '';

				imageIDs = selection.map( function( scopedAttachment ) {
					var imageID = scopedAttachment.id;

					if ( scopedAttachment.attributes.sizes && 'undefined' !== typeof scopedAttachment.attributes.sizes.thumbnail ) {
						imageURL = scopedAttachment.attributes.sizes.thumbnail.url;
					} else if ( scopedAttachment.attributes.url ) {
						imageURL = scopedAttachment.attributes.url;
					}

					if ( multiImages ) {
						multiImageHtml += '<div class="fusion-multi-image" data-image-id="' + imageID + '">';
						multiImageHtml += '<img src="' + imageURL + '"/>';
						multiImageHtml += '<span class="fusion-multi-image-remove dashicons dashicons-no-alt"></span>';
						multiImageHtml += '</div>';
					}
					return scopedAttachment.id;
				} );

				multiImageInput.val( imageIDs );
				jQuery( multiImageContainer ).html( multiImageHtml );
				jQuery( multiImageContainer ).trigger( 'change' );
				multiImageInput.trigger( 'change' );
			} );
		}

		fileFrame.on( 'select insert', function() {

			var imageURL,
				imageID,
				imageIDs,
				state = fileFrame.state();

			if ( 'undefined' === typeof state.get( 'selection' ) ) {
				imageURL = jQuery( fileFrame.$el ).find( '#embed-url-field' ).val();
			} else {

				imageIDs = state.get( 'selection' ).map( function( scopedAttachment ) {
					return scopedAttachment.id;
				} );

				// If its a multi image element, add the images container and IDs to input field.
				if ( multiImages ) {
					multiImageInput.val( imageIDs );
				}

				state.get( 'selection' ).map( function( scopedAttachment ) {
					var element = scopedAttachment.toJSON(),
						display = state.display( scopedAttachment ).toJSON();

					imageID = element.id;
					if ( element.sizes && element.sizes[ display.size ] && element.sizes[ display.size ].url ) {
						imageURL    = element.sizes[ display.size ].url;
					} else if ( element.url ) {
						imageURL    = element.url;
					}

					if ( multiImages ) {
						multiImageHtml += '<div class="fusion-multi-image" data-image-id="' + imageID + '">';
						multiImageHtml += '<img src="' + imageURL + '"/>';
						multiImageHtml += '<span class="fusion-multi-image-remove dashicons dashicons-no-alt"></span>';
						multiImageHtml += '</div>';
					}

					return scopedAttachment;
				} );

				$thisEl.trigger( 'change' );
			}

			jQuery( multiImageContainer ).html( multiImageHtml );
		} );

		fileFrame.open();

		return false;
	} );

	jQuery( 'body' ).on( 'click', '.fusion-multi-image-remove', function() {
		var input = jQuery( this ).closest( '.fusion-multiple-upload-images' ).find( '.fusion-multi-image-input' ),
			imageIDs,
			imageID,
			imageIndex;

		imageID = jQuery( this ).parent( '.fusion-multi-image' ).data( 'image-id' );
		imageIDs = input.val().split( ',' ).map( function( v ) {
			return parseInt( v, 10 );
		} );

		imageIndex = imageIDs.indexOf( imageID );

		if ( -1 !== imageIndex ) {
			imageIDs.splice( imageIndex, 1 );
		}

		imageIDs = imageIDs.join( ',' );
		input.val( imageIDs ).trigger( 'change' );

		jQuery( this ).parent( '.fusion-multi-image' ).remove();
	} );
}( jQuery ) );
