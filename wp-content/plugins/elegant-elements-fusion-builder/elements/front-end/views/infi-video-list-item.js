/* global FusionPageBuilderElements, fusionAllElements */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Video List Item Element View.
		FusionPageBuilder.iee_video_list_item = FusionPageBuilder.ChildElementView.extend( {

			/**
			 * Modify template attributes.
			 *
			 * @since 2.3
			 * @param {Object} atts - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			filterTemplateAtts: function( atts ) {
				var attributes = {},
					parent      = this.model.get( 'parent' ),
					parentModel = FusionPageBuilderElements.find( function( model ) {
						return model.get( 'cid' ) == parent;
					} );

				// Set parent values.
				this.parentValues = jQuery.extend( true, {}, fusionAllElements.iee_video_list.defaults, _.fusionCleanParameters( parentModel.get( 'params' ) ) );

				// Unique ID for this particular element instance, can be useful.
				attributes.cid = this.model.get( 'cid' );

				// Create attribute objects
				attributes.attr      = this.buildAttr();
				attributes.titleAttr = this.buildTitleAttr( atts.params );
				attributes.iconAttr  = this.buildIconAttr( this.parentValues );

				// Any extras that need passed on.
				attributes.content = atts.params.element_content;
				attributes.title   = atts.params.title;

				return attributes;
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.3
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttr: function() {
				var attr = {
						class: 'elegant-video-list-item elegant-video-list-item-' + this.model.get( 'cid' )
					};

				return attr;
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.3
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildTitleAttr: function( values ) {
				var attr = {
						class: 'elegant-video-list-item-title'
					};

				if ( 'hosted' !== values.video_provider ) {
					attr['data-embed-url'] = this.getEmbedUrlByProvider( values.video_provider, values.video_id );
				} else {
					attr['data-embed-url'] = values.video_file;
				}

				return attr;
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.3
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildIconAttr: function( values ) {
				var attr = {
						class: 'video-list-icon'
					};

				attr['class'] += ' ' + _.fusionFontAwesome( values.video_list_icon );

				return attr;
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.3
			 * @param {String} videoProvider - The video provider name.
			 * @param {String} videoID       - The video ID from the url.
			 * @return {array} Attributes array for wrapper.
			 */
			getEmbedUrlByProvider: function( videoProvider, videoID ) {
				var embedUrl = '';

				switch ( videoProvider ) {
					case 'youtube':
						embedUrl = 'https://www.youtube.com/embed/' + videoID;
						break;
					case 'vimeo':
						embedUrl = 'https://player.vimeo.com/video/' + videoID;
						break;
					case 'wistia':
						embedUrl = 'https://fast.wistia.net/embed/iframe/' + videoID + '?dnt=1&videoFoam=true';
						break;
				}

				return embedUrl;
			}
		} );
	} );
}( jQuery ) );
