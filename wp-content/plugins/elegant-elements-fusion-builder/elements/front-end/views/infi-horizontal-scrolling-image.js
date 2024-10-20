/* global FusionPageBuilderElements, fusionAllElements */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Horizontal Scrolling Child Element View.
		FusionPageBuilder.iee_horizontal_scrolling_image = FusionPageBuilder.ChildElementView.extend( {

			/**
			 * Runs just before view is removed.
			 *
			 * @since 3.3.0
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
			 * @since 3.3.0
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
			 * @since 3.3.0
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
			 * @since 3.3.0
			 * @param {Object} atts - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			filterTemplateAtts: function( atts ) {
				var attributes = {},
					parent      = this.model.get( 'parent' ),
					parentModel = FusionPageBuilderElements.find( function( model ) {
						return model.get( 'cid' ) == parent;
					} );

				this.parentValues = jQuery.extend( true, {}, fusionAllElements.iee_horizontal_scrolling_image.defaults, _.fusionCleanParameters( parentModel.get( 'params' ) ) );
				this.parentExtras = parentModel.get( 'extras' );

				// Unique ID for this particular element instance, can be useful.
				attributes.cid = this.model.get( 'cid' );
				attributes.parentcid = parentModel.get( 'cid' );

				// Validate values.
				this.validateValues( atts.params );

				// Create attribute objects
				attributes.attr = this.buildAttr( atts.params );

				// Set parent model.
				attributes.parent = parent;

				// Any extras that need passed on.
				attributes.content              = atts.params.element_content;
				attributes.image_url            = atts.params.image_url;
				attributes.click_action         = atts.params.click_action;
				attributes.modal_anchor         = atts.params.modal_anchor;
				attributes.url                  = atts.params.url;
				attributes.target               = atts.params.target;
				attributes.lightbox_image       = atts.params.lightbox_image;
				attributes.lightbox_image_meta  = ( 'undefined' !== typeof atts.params.lightbox_image_meta ) ? atts.params.lightbox_image_meta : '';
				attributes.image_max_width      = ( 'undefined' !== typeof atts.params.image_max_width ) ? atts.params.image_max_width : '400px';
				attributes.image_shape          = atts.params.image_shape;
				attributes.blob_shape           = atts.params.blob_shape;
				attributes.images_border_radius = atts.params.images_border_radius;

				return attributes;
			},

			/**
			 * Modifies values.
			 *
			 * @since 3.3.0
			 * @param {Object} values - The values.
			 * @return {void}
			 */
			validateValues: function( values ) {
				values.image_max_width      = _.fusionGetValueWithUnit( values.image_max_width );
				values.images_border_radius = _.fusionGetValueWithUnit( values.images_border_radius );
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 3.3.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttr: function( values ) {
				var attr = {
						class: 'elegant-horizontal-scrolling-image-item-' + this.model.get( 'cid' ),
						style: ''
					};

				if ( values.class ) {
					attr['class'] += ' ' + values.class;
				}

				return attr;
			}
		} );
	} );
}( jQuery ) );
