var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Document Viewer Element View.
		FusionPageBuilder.iee_document_viewer = FusionPageBuilder.ElementView.extend( {

			/**
			 * Modify template attributes.
			 *
			 * @since 2.3
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
				attributes.content  = atts.params.element_content;
				attributes.file_url = '//docs.google.com/viewer?embedded=true&url=' + atts.params.file_url;

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
				values.document_height = _.fusionGetValueWithUnit( values.document_height );
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
						class: 'elegant-document-viewer',
						style: ''
					} );

				attr['class'] += ' document-type-' + values.file_type;
				attr['style']  = 'height:' + values.document_height + ';';

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
