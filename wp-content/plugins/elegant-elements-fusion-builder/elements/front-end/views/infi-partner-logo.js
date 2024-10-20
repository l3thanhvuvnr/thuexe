/* global FusionPageBuilderElements, fusionAllElements */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Partner Logo Child Element View.
		FusionPageBuilder.iee_partner_logo = FusionPageBuilder.ChildElementView.extend( {

			/**
			 * Modify template attributes.
			 *
			 * @since 2.0
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
				this.parentValues = jQuery.extend( true, {}, fusionAllElements.iee_list_box.defaults, _.fusionCleanParameters( parentModel.get( 'params' ) ) );

				// Unique ID for this particular element instance, can be useful.
				attributes.cid = this.model.get( 'cid' );

				// Create attribute objects
				attributes.attr      = this.buildAttr( atts.params );
				attributes.imageAttr = this.buildImageAttr( atts.params );

				// Any extras that need passed on.
				attributes.content      = atts.params.element_content;
				attributes.image_url    = atts.params.image_url;
				attributes.title        = atts.params.title;
				attributes.click_action = atts.params.click_action;
				attributes.modal_anchor = atts.params.modal_anchor;
				attributes.url          = atts.params.url;
				attributes.target       = atts.params.target;
				attributes.class        = atts.params.class;
				attributes.id           = atts.params.id;

				return attributes;
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
				var attr = {
						class: 'elegant-partner-logo elegant-partner-logo-' + this.model.get( 'cid' )
					};

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
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildImageAttr: function( values ) {
				var attr = {
						class: 'elegant-partner-logo-' + this.model.get( 'cid' )
					};

				if ( '' !== values.modal_anchor ) {
					attr['data-toggle'] = 'modal';
					attr['data-target'] = '.fusion-modal.' + values.modal_anchor;
				}

				attr['src'] = values.image_url;

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
