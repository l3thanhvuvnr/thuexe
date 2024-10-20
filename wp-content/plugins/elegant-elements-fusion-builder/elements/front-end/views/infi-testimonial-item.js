/* global FusionPageBuilderElements, fusionAllElements */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Testimonials Child Element View.
		FusionPageBuilder.iee_testimonial = FusionPageBuilder.ChildElementView.extend( {

			/**
			 * Runs during render() call.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			onRender: function() {
				var self = this,
					parentView = window.FusionPageBuilderViewManager.getView( this.model.get( 'parent' ) );

				if ( 'undefined' !== typeof this.model.attributes.selectors ) {
					this.model.attributes.selectors[ 'class' ] += ' ' + this.className;
					this.setElementAttributes( this.$el, this.model.attributes.selectors );
				}

				// Re-render the parent view if the child was cloned
				if ( 'undefined' !== typeof self.model.attributes.cloned && true === self.model.attributes.cloned ) {
					delete self.model.attributes.cloned;
					parentView.reRender();
				}
			},

			/**
			 * Runs on child element init.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			onInit: function() {
				var parentView = window.FusionPageBuilderViewManager.getView( this.model.get( 'parent' ) );

				parentView.onRenderChild();
			},

			/**
			 * Runs before element is removed.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			beforeRemove: function() {
				var cid = this.model.get( 'cid' ),
					parentView = window.FusionPageBuilderViewManager.getView( this.model.get( 'parent' ) ),
					navItem = parentView.$el.find( '.elegant-image-filter-navigation-item-' + cid );

				parentView.onRenderChild();
				navItem.remove();
			},

			/**
			 * Runs after view DOM is patched.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			beforePatch: function() {
				var parentView = window.FusionPageBuilderViewManager.getView( this.model.get( 'parent' ) );
				parentView._refreshJs();
			},

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
					jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( 'body' ).trigger( 'fusion-element-render-iee_testimonials', parentCid );
				}, 100 );

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

				this.parentValues      = jQuery.extend( true, {}, fusionAllElements.iee_testimonial.defaults, _.fusionCleanParameters( parentModel.get( 'params' ) ) );
				attributes.parentModel = parentModel;

				// Unique ID for this particular element instance, can be useful.
				attributes.cid = this.model.get( 'cid' );

				// Set parent model.
				attributes.parent = parent;

				// Create attribute objects
				attributes.attr = this.buildAttr( atts.params );

				// Any extras that need passed on.
				attributes.content         = atts.params.element_content;
				attributes.title           = atts.params.title;
				attributes.title_color     = ( 'undefined' !== typeof atts.params.title_color && '' !== atts.params.title_color ) ? atts.params.title_color : '';
				attributes.sub_title       = atts.params.sub_title;
				attributes.sub_title_color = ( 'undefined' !== typeof atts.params.sub_title_color && '' !== atts.params.sub_title_color ) ? atts.params.sub_title_color : '';
				attributes.image_url       = ( 'undefined' !== typeof atts.params.image_url ) ? atts.params.image_url : '';

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
						class: 'elegant-testimonials-image',
						style: ''
					};

				attr['data-key'] += ' elegant-testimonial-' + this.model.get( 'cid' );

				if ( 'undefined' !== typeof values.class ) {
					attr['class'] += ' ' + values.class;
				}

				if ( 'undefined' !== typeof values.id ) {
					attr['id'] = values.id;
				}

				return attr;
			}
		} );
	} );
}( jQuery ) );
