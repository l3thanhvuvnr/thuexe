/* global elegant_gradient_color */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Blob Shape Image Element View.
		FusionPageBuilder.iee_blob_shape_image = FusionPageBuilder.ElementView.extend( {

			/**
			 * Modify template attributes.
			 *
			 * @since 2.5
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
				attributes.wrapperAttr    = this.buildWrapperAttr( atts.params );
				attributes.attr           = this.buildAttr( atts.params );
				attributes.backgroundAttr = this.buildBackgroundAttr( atts.params );
				attributes.contentAttr    = this.buildContentAttr();

				// Any extras that need passed on.
				attributes.content = atts.params.element_content;

				return attributes;
			},

			/**
			 * Modifies values.
			 *
			 * @since 2.5
			 * @param {Object} values - The values.
			 * @return {void}
			 */
			validateValues: function( values ) {
				values.height = _.fusionGetValueWithUnit( values.height );
				values.width  = _.fusionGetValueWithUnit( values.width );
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.5
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildWrapperAttr: function( values ) {
				var attr = _.fusionVisibilityAtts( values.hide_on_mobile, {
						class: 'elegant-blob-shape-image-wrapper',
						style: ''
					} );

				attr['class'] += ' animate-blob-' + ( 'undefined' !== typeof values.animate_blob ? values.animate_blob : 'no' );
				attr['class'] += ' fusion-clearfix';
				attr['style'] += 'display: flex;';

				if ( 'center' === values.alignment ) {
					attr['style'] += 'justify-content: center;';
				} else if ( 'right' === values.alignment ) {
					attr['style'] += 'justify-content: flex-end;';
				} else {
					attr['style'] += 'justify-content: flex-start;';
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
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.5
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttr: function( values ) {
				var attr = {
						class: 'elegant-blob-shape-image',
						style: ''
					};

				attr['style'] += 'height:' + values.height + ';';
				attr['style'] += 'width:' + values.width + ';';
				attr['style'] += 'max-width: 100%;';
				attr['style'] += 'border-radius:' + values.blob_shape + ';';
				attr['style'] += elegant_gradient_color( values.gradient_angle, values.background_color_1, values.background_color_2, values.fade_offset, values.background_color_1_offset, values.background_color_2_offset );

				return attr;
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.5
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildBackgroundAttr: function( values ) {
				var attr = {
						class: 'elegant-blob-shape-image-background',
						style: ''
					};

				if ( '' !== values.image ) {
					attr['style'] += 'background-image:url(' + values.image + ');';
					attr['style'] += 'background-blend-mode: overlay;';
					attr['style'] += 'mix-blend-mode: overlay;';
					attr['style'] += 'background-size: cover;';
					attr['style'] += 'background-repeat: no-repeat;';
					attr['style'] += 'background-position: center;';
					attr['style'] += 'height: 100%;';
					attr['style'] += 'border-radius: inherit;';
				}

				return attr;
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.5
			 * @return {array} Attributes array for wrapper.
			 */
			buildContentAttr: function() {
				var attr = {
						class: 'elegant-blob-shape-image-content',
						style: ''
					};

				attr['style'] += 'transform: translate(0%, -100%);';
				attr['style'] += 'height: inherit;';
				attr['style'] += 'display: flex;';
				attr['style'] += 'align-items: center;';
				attr['style'] += 'justify-content: center;';
				attr['style'] += 'padding: 40px;';

				return attr;
			}
		} );
	} );
}( jQuery ) );
