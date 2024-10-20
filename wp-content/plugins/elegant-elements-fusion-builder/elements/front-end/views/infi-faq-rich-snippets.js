var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// FAQ Rich Snippets Element View.
		FusionPageBuilder.iee_faq_rich_snippets = FusionPageBuilder.ParentElementView.extend( {

			/**
			 * Modify template attributes.
			 *
			 * @since 2.1.0
			 * @param {Object} atts - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			filterTemplateAtts: function( atts ) {
				var attributes = {};

				// Unique ID for this particular element instance, can be useful.
				attributes.cid = this.model.get( 'cid' );

				// Any extras that need passed on.
				attributes.params      = atts.params;
				attributes.content     = atts.params.element_content;
				attributes.title       = atts.params.title;
				attributes.output_type = atts.params.output_type;

				return attributes;
			}

		} );
	} );
}( jQuery ) );
