/* global elegant_get_typography_css */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Profile Panel Element View.
		FusionPageBuilder.iee_profile_panel = FusionPageBuilder.ElementView.extend( {

			/**
			 * Modify template attributes.
			 *
			 * @since 2.0
			 * @param {Object} atts - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			filterTemplateAtts: function( atts ) {
				var attributes = {};

				// Validate values.
				this.validateValues( atts.params );

				// Unique ID for this particular element instance, can be useful.
				attributes.cid = this.model.get( 'cid' );

				// Create attribute objects
				attributes.attr                    = this.buildAttr( atts.params );
				attributes.headerImageAttr         = this.buildHeaderImageAttr( atts.params );
				attributes.profileImageWrapperAttr = this.buildProfileImageWrapperAttr( atts.params );
				attributes.profileImageAttr        = this.buildProfileImageAttr( atts.params );
				attributes.descriptionWrapperAttr  = this.buildDescriptionWrapperAttr();
				attributes.titleAttr               = this.buildTitleAttr( atts.params );
				attributes.descriptionAttr         = this.buildDescriptionAttr( atts.params );

				// Any extras that need passed on.
				attributes.content                = atts.params.element_content;
				attributes.header_image           = ( 'undefined' !== typeof atts.values.header_image ) ? atts.values.header_image : atts.params.header_image;
				attributes.profile_image          = ( 'undefined' !== typeof atts.values.profile_image ) ? atts.values.profile_image : atts.params.profile_image;
				attributes.title                  = ( 'undefined' !== typeof atts.values.title ) ? atts.values.title : atts.params.title;
				attributes.description            = ( 'undefined' !== typeof atts.values.description ) ? atts.values.description : atts.params.description;
				attributes.typography_title       = atts.params.typography_title;
				attributes.typography_description = atts.params.typography_description;

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
				values.profile_header_height = _.fusionGetValueWithUnit( values.profile_header_height );
				values.profile_image_width   = _.fusionGetValueWithUnit( values.profile_image_width );
				values.title_font_size       = _.fusionGetValueWithUnit( values.title_font_size );
				values.description_font_size = _.fusionGetValueWithUnit( values.description_font_size );
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
						class: 'elegant-profile-panel elegant-profile-panel-' + this.model.get( 'cid' ),
						style: ''
					} ),
					alignment;

				alignment      = ( '' !== values.alignment ) ? values.alignment : 'center';

				attr['class'] += ' elegant-profile-panel-align-' + alignment;

				if ( '' !== values.panel_background_color ) {
					attr['style'] += 'background-color: ' + values.panel_background_color + ';';
				}

				if ( values.class ) {
					attr['class'] += ' ' + values.class;
				}

				if ( values.id ) {
					attr['id'] = values.id;
				}

				return attr;
			},

			/**
			 * Builds the attributes array for title.
			 *
			 * @access public
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildHeaderImageAttr: function( values ) {
				var attr = {
						class: 'elegant-profile-panel-header-image-wrapper',
						style: ''
					};

				if ( values.header_image ) {
					attr['style'] += 'background-image: url( ' + values.header_image + ');';
				}

				if ( '' !== values.header_background_color ) {
					attr['style'] += 'background-color: ' + values.header_background_color + ';';
				}

				if ( '' !== values.profile_header_height ) {
					attr['style'] += 'height: ' + values.profile_header_height + ';';
				}

				attr['style'] += 'overflow: hidden;';

				return attr;
			},

			/**
			 * Builds the attributes array for title.
			 *
			 * @access public
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildProfileImageWrapperAttr: function( values ) {
				var attr = {
						class: 'elegant-profile-panel-profile-image-wrapper',
						style: ''
					};

				if ( '' !== values.profile_image_width ) {
					attr['style'] += 'margin-top: calc( -' + values.profile_image_width + ' / 2 );';
				}

				return attr;
			},

			/**
			 * Builds the attributes array for title.
			 *
			 * @access public
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildProfileImageAttr: function( values ) {
				var attr = {
						class: 'elegant-profile-panel-profile-image',
						style: ''
					};

				if ( '' !== values.profile_image_border_type ) {
					attr['class'] += ' elegant-profile-panel-image-' + values.profile_image_border_type;
				}

				if ( '' !== values.profile_image_width ) {
					attr['style'] += 'max-width: ' + values.profile_image_width + ';';
				}

				if ( '' !== values.profile_background_color ) {
					attr['style'] += 'background-color: ' + values.profile_background_color + ';';
				}

				if ( '' !== values.profile_border_color ) {
					attr['style'] += 'border-color: ' + values.profile_border_color + ';';
				}

				return attr;
			},

			/**
			 * Builds the attributes array for title.
			 *
			 * @access public
			 * @since 2.0
			 * @return {array} Attributes array for wrapper.
			 */
			buildDescriptionWrapperAttr: function() {
				var attr = {
						class: 'elegant-profile-panel-description-wrapper',
						style: ''
					};

				return attr;
			},

			/**
			 * Builds the attributes array for title.
			 *
			 * @access public
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildTitleAttr: function( values ) {
				var attr = {
						class: 'elegant-profile-panel-title',
						style: ''
					},
					typography,
					title_typography;

				if ( '' !== values.typography_title ) {
					typography       = values.typography_title;
					title_typography = elegant_get_typography_css( typography );

					attr['style'] += title_typography;
				}

				if ( '' !== values.title_font_size ) {
					attr['style'] += 'font-size:' + values.title_font_size + ';';
				}

				if ( '' !== values.title_color ) {
					attr['style'] += 'color:' + values.title_color + ';';
				}

				attr = _.fusionInlineEditor(
					{
						cid: this.model.get( 'cid' ),
						param: 'title',
						toolbar: false,
						'disable-return': true
					},
					attr
				);

				return attr;
			},

			/**
			 * Builds the attributes array for description.
			 *
			 * @access public
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildDescriptionAttr: function( values ) {
				var attr = {
						class: 'elegant-profile-panel-description',
						style: ''
					},
					typography,
					description_typography;

				if ( '' !== values.typography_description ) {
					typography             = values.typography_description;
					description_typography = elegant_get_typography_css( typography );

					attr['style'] += description_typography;
				}

				if ( '' !== values.description_font_size ) {
					attr['style'] += 'font-size:' + values.description_font_size + ';';
				}

				if ( '' !== values.description_color ) {
					attr['style'] += 'color:' + values.description_color + ';';
				}

				attr = _.fusionInlineEditor(
					{
						cid: this.model.get( 'cid' ),
						param: 'description',
						toolbar: false,
						'disable-return': true
					},
					attr
				);

				return attr;
			}
		} );
	} );
}( jQuery ) );
