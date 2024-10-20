/* global FusionPageBuilderElements, fusionAllElements, elegant_get_typography_css */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Typewriter Text Child Element View.
		FusionPageBuilder.iee_typewriter_text_child = FusionPageBuilder.ChildElementView.extend( {

			/**
			 * Runs after view DOM is patched.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			afterPatch: function() {
				var parentView = window.FusionPageBuilderViewManager.getView( this.model.get( 'parent' ) ),
					parentCid = this.model.get( 'parent' );

				if ( 'undefined' !== typeof this.model.attributes.selectors ) {
					this.model.attributes.selectors[ 'class' ] += ' ' + this.className;
					this.setElementAttributes( this.$el, this.model.attributes.selectors );
				}

				// Force re-render for child option changes.
				setTimeout( function() {
					jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( 'body' ).trigger( 'fusion-element-render-iee_typewriter_text', parentCid );
				}, 200 );

				// Using non debounced version for smoothness.
				this.refreshJs();

				parentView._refreshJs();
			},

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

				this.parentValues = jQuery.extend( true, {}, fusionAllElements.iee_typewriter_text.defaults, _.fusionCleanParameters( parentModel.get( 'params' ) ) );

				// Validate values.
				this.validateValues( atts.params );

				// Unique ID for this particular element instance, can be useful.
				attributes.cid = this.model.get( 'cid' );

				// Create attribute objects
				attributes.attr        = this.buildAttr( atts.params );

				// Any extras that need passed on.
				attributes.content     = atts.params.element_content;
				attributes.title       = atts.params.title;
				attributes.title_color = atts.params.title_color;
				attributes.class       = atts.params.class;
				attributes.id          = atts.params.id;

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
				var titleColor = ( '' !== this.parentValues.title_color ) ? this.parentValues.title_color : '';
				values.title_color = ( '' !== values.title_color ) ? values.title_color : titleColor;
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
						class: 'elegant-typewriter-text-wrap',
						style: ''
					};

				if ( 'undefined' !== typeof this.parentValues.typography_child && '' !== this.parentValues.typography_child ) {
					attr['style'] += elegant_get_typography_css( this.parentValues.typography_child );
				}

				if ( '' !== values.title_color ) {
					attr['style'] += ' color:' + values.title_color + ';';
				}

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
