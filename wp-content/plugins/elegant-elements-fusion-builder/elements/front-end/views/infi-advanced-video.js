var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	'use strict';

	jQuery( document ).ready( function() {

		// Advanced Video Element View.
		FusionPageBuilder.iee_advanced_video = FusionPageBuilder.ElementView.extend( {

			/**
			 * Modify template attributes.
			 *
			 * @since 2.3
			 * @param {Object} atts - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			filterTemplateAtts: function( atts ) {
				var attributes = {},
					channelID = '';

				// Validate values.
				this.validateValues( atts.params );

				// Unique ID for this particular element instance, can be useful.
				attributes.cid = this.model.get( 'cid' );

				// Create attribute objects
				attributes.attr      = this.buildAttr( atts.params );
				attributes.imageAttr = this.buildAttrImage( atts.params );
				attributes.iconAttr  = this.buildAttrIcon( atts.params );

				// Any extras that need passed on.
				attributes.content                  = atts.params.element_content;
				attributes.image                    = atts.params.image;
				attributes.image_overlay            = atts.params.image_overlay;
				attributes.icon_type                = atts.params.icon_type;
				attributes.image_icon               = atts.params.image_icon;
				attributes.video_provider           = atts.params.video_provider;
				attributes.width                    = atts.params.width;
				attributes.youtube_subscribe        = ( 'undefined' !== typeof atts.params.youtube_subscribe ) ? atts.params.youtube_subscribe : 'no';
				attributes.youtube_channel          = ( 'undefined' !== typeof atts.params.youtube_channel ) ? atts.params.youtube_channel : 'GoogleDevelopers';
				attributes.subscribe_text           = ( 'undefined' !== typeof atts.params.subscribe_text ) ? atts.params.subscribe_text : 'Subscribe to our YouTube channel.';
				attributes.subscribe_bar_background = ( 'undefined' !== typeof atts.params.subscribe_bar_background ) ? atts.params.subscribe_bar_background : '#666666';
				attributes.subscribe_bar_text_color = ( 'undefined' !== typeof atts.params.subscribe_bar_text_color ) ? atts.params.subscribe_bar_text_color : '#ffffff';

				channelID               = attributes.youtube_channel;
				attributes.channel_data = -1 !== channelID.indexOf( 'UC' ) ? 'channel' : 'channelid';

				return attributes;
			},

			/**
			 * Modifies values.
			 *
			 * @since 2.3
			 * @param {Object} values - The values.
			 * @return {void}
			 */
			validateValues: function( values ) {
				values.width = _.fusionGetValueWithUnit( values.width );
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.3
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttr: function( values ) {
				var attr = _.fusionVisibilityAtts( values.hide_on_mobile, {
						class: 'elegant-advanced-video',
						style: ''
					} );

				attr['class'] += ' fusion-align' + values.alignment;
				attr['style'] += 'max-width:' + values.width + ';';

				if ( values.class ) {
					attr['class'] += ' ' + values.class;
				}

				if ( values.id ) {
					attr['id'] = values.id;
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
			buildAttrImage: function( values ) {
				var attr = {
					class: 'elegant-advanced-video-preview',
					style: '',
					src: '',
					srcset: ''
				};

				attr['src'] = values.image;

				if ( 'undefined' !== typeof values.image_retina ) {
					attr['srcset']  = values.image + ' 1x, ';
					attr['srcset'] += values.image_retina + ' 2x ';
				}

				attr['style']          = 'max-width:' + values.width + ';';
				attr['style']          = 'width: 100%;';
				attr['data-embed-url'] = this.getEmbedUrlByProvider( values.video_provider, values.video_id );

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
			buildAttrIcon: function( values ) {
				var attr = {
						class: 'elegant-advanced-video-play-icon',
						style: ''
					};

				attr['class'] += ' ' + _.fusionFontAwesome( values.video_play_icon );
				attr['style'] += 'color: ' + values.icon_color + ';';
				attr['style'] += 'font-size: ' + values.icon_font_size + 'px;';

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
						embedUrl = 'https://www.youtube.com/embed/' + videoID + '?autoplay=1';
						break;
					case 'vimeo':
						embedUrl = 'https://player.vimeo.com/video/' + videoID + '?autoplay=1';
						break;
					case 'wistia':
						embedUrl = 'https://fast.wistia.net/embed/iframe/' + videoID + '?dnt=1&videoFoam=true&autoplay=1';
						break;
				}

				return embedUrl;
			}
		} );
	} );
}( jQuery ) );
