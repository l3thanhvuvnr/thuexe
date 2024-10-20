var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Instagram Gallery Element View.
		FusionPageBuilder.iee_instagram_gallery = FusionPageBuilder.ElementView.extend( {

			/**
			 * Modify template attributes.
			 *
			 * @since 2.0
			 * @param {Object} atts - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			filterTemplateAtts: function( atts ) {
				var attributes = {};

				// Unique ID for this particular element instance, can be useful.
				attributes.cid = this.model.get( 'cid' );

				// Validate values.
				this.validateValues( atts.params );

				// Create attribute objects
				attributes.attr = this.buildAttr( atts.params );

				// Any extras that need passed on.
				attributes.content       = atts.params.element_content;
				attributes.username      = atts.params.username;
				attributes.limit         = atts.params.photos_count;
				attributes.size          = atts.params.photo_size;
				attributes.target        = atts.params.link_target;
				attributes.show_likes    = atts.params.show_likes;
				attributes.show_comments = atts.params.show_comments;
				attributes.hover_type    = atts.params.hover_type;
				attributes.url           = atts.params.url;
				attributes.user_id       = atts.params.user_id;

				return attributes;
			},

			/**
			 * Modifies values.
			 *
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {void}
			 */
			validateValues: function( values ) {
				var username = jQuery.trim( values.username.toLowerCase() ),
					url = '',
					hashtag = '';

				switch ( username.substr( 0, 1 ) ) {
					case '#':
						hashtag = username.replace( '#', '' );
						url     = 'https://instagram.com/explore/tags/' + hashtag;
						break;

					default:
						username = username.replace( '@', '' );
						url      = 'https://instagram.com/' + username;
						break;
				}

				values.url = url + '/channel/?__a=1';
				values.user_id = values.username.replace( '@', '' ).replace( '#', '' ).replace( '.', '' );
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttr: function( values ) {
				var attr = _.fusionVisibilityAtts( values.hide_on_mobile, {
						class: 'elegant-instagram-gallery',
						style: ''
					} );

				if ( 'none' !== values.hover_type ) {
					attr['class'] += ' fusion-image-hovers';
				}

				if ( values.class ) {
					attr['class'] += ' ' + values.class;
				}

				if ( values.id ) {
					attr['id'] = values.id;
				}

				return attr;
			}
		} );
	} );
}( jQuery ) );
