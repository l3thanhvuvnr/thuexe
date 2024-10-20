/* global FusionPageBuilderElements, fusionAllElements */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Carousel Child Element View.
		FusionPageBuilder.iee_carousel_item = FusionPageBuilder.ChildElementView.extend( {

			/**
			 * Runs just before view is removed.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			beforeRemove: function() {
				if ( jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( this.$el.closest( '.elegant-slick-initialized' ) ).length ) {
					jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( this.$el.closest( '.elegant-slick-initialized' ) ).elegant_slick( 'unslick' );
				}
			},

			/**
			 * Runs during render() call.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			onRender: function() {
				if ( 'undefined' !== typeof this.model.attributes.selectors ) {
					this.model.attributes.selectors[ 'class' ] += ' ' + this.className;
					this.setElementAttributes( this.$el, this.model.attributes.selectors );
				}
			},

			/**
			 * Runs after view DOM is patched.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			afterPatch: function() {

				if ( 'undefined' !== typeof this.model.attributes.selectors ) {
					this.model.attributes.selectors[ 'class' ] += ' ' + this.className;
					this.setElementAttributes( this.$el, this.model.attributes.selectors );
				}

				this._refreshJs();

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

				this.parentValues = jQuery.extend( true, {}, fusionAllElements.iee_carousel_item.defaults, _.fusionCleanParameters( parentModel.get( 'params' ) ) );
				this.parentExtras = parentModel.get( 'extras' );

				// Unique ID for this particular element instance, can be useful.
				attributes.cid = this.model.get( 'cid' );

				// Set parent model.
				attributes.parent = parent;

				// Any extras that need passed on.
				attributes.content = ( 'undefined' !== typeof atts.params.element_content ) ? atts.params.element_content : '';

				return attributes;
			}
		} );
	} );
}( jQuery ) );
