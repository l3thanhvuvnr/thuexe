var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Distortion Hover Image Element View.
		FusionPageBuilder.iee_distortion_hover_image = FusionPageBuilder.ElementView.extend( {

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
				attributes.wrapperAttr = this.buildWrapperAttr( atts.params );
				attributes.attr        = this.buildAttr( atts.params );
				attributes.contentAttr = this.buildContentAttr( atts.params );

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
				values.height          = _.fusionGetValueWithUnit( values.height );
				values.width           = _.fusionGetValueWithUnit( values.width );
				values.content_overlay = ( values.content_overlay ) ? values.content_overlay : 'rgba(0,0,0,0.3)';
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
						class: 'elegant-distortion-hover-image-wrapper',
						style: ''
					} );

				attr['style'] += 'width:' + values.width + ';';
				attr['style'] += 'height:' + values.height + ';';
				attr['class'] += ' fusion-clearfix';

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
						class: 'elegant-distortion-hover-image distortion-hover-image-' + this.model.get( 'cid' )
					};

				attr['data-firstImage']        = values.first_image;
				attr['data-secondImage']       = values.second_image;
				attr['data-displacementImage'] = values.displacement_image;
				attr['data-intensity']         = ( 'from_left' === values.distortion_position ) ? -0.5 : 0.5;

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
			buildContentAttr: function( values ) {
				var attr = {
						class: 'elegant-distortion-hover-content',
						style: ''
					};

				attr['style'] += 'background: ' + values.content_overlay + ';';

				return attr;
			}
		} );
	} );
}( jQuery ) );
