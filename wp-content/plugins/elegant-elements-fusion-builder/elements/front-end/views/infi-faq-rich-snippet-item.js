/* global FusionPageBuilderElements, fusionAllElements */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// FAQ Rich Snippets Child Element View.
		FusionPageBuilder.iee_faq_rich_snippet_item = FusionPageBuilder.ChildElementView.extend( {

			/**
			 * Modify template attributes.
			 *
			 * @since 2.1.0
			 * @param {Object} atts - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			filterTemplateAtts: function( atts ) {
				var attributes = {},
					parent      = this.model.get( 'parent' ),
					parentModel = FusionPageBuilderElements.find( function( model ) {
						return model.get( 'cid' ) == parent;
					} );

				this.parentValues = jQuery.extend( true, {}, fusionAllElements.iee_faq_rich_snippets.defaults, _.fusionCleanParameters( parentModel.get( 'params' ) ) );

				// Unique ID for this particular element instance, can be useful.
				attributes.cid = this.model.get( 'cid' );

				// Set parent model.
				attributes.parent = parent;

				// Any extras that need passed on.
				attributes.content      = atts.params.element_content;
				attributes.parentValues = this.parentValues;
				attributes.question     = atts.params.question;

				return attributes;
			}
		} );
	} );
}( jQuery ) );
