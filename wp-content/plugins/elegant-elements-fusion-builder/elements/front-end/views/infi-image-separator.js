var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Image Separator Element View.
		FusionPageBuilder.iee_image_separator = FusionPageBuilder.ElementView.extend( {

			/**
			 * Runs during render() call.
			 *
			 * @since 2.1.0
			 * @return {void}
			 */
			onRender: function() {
				var that = this;

				setTimeout( function() {
					if ( jQuery( that.$el ).find( '.image-separator-horizontal' ).length ) {
						jQuery( that.$el ).addClass( 'horizontal-separator' );
						jQuery( that.$el ).find( '.image-separator-horizontal' ).css( { right: 'auto', left: '50%', bottom: '-135px' } );
					}
				}, 500 );
			},

			/**
			 * Modify template attributes.
			 *
			 * @since 2.1.0
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
				attributes.attr = this.buildAttr( atts.params );

				// Any extras that need passed on.
				attributes.content   = atts.params.element_content;
				attributes.image_url = atts.params.image;

				return attributes;
			},

			/**
			 * Modifies values.
			 *
			 * @since 2.1.0
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
			 * @since 2.1.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttr: function( values ) {
				var attr = _.fusionVisibilityAtts( values.hide_on_mobile, {
						class: 'elegant-image-separator',
						style: ''
					} );

				attr['class'] += ' image-separator-' + this.model.get( 'cid' );
				attr['class'] += ' image-separator-' + values.type;

				if ( 'vertical' == values.type ) {
					attr['style'] += 'top: calc( 50% - ' + values.height + ' );';
				}

				attr['style'] += 'height:' + values.height + ';';
				attr['style'] += 'width:' + values.width + ';';

				attr['style'] += 'bottom: -135px;';
				attr['style'] += 'top: auto';

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
