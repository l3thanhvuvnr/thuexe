/* global FusionEvents, FusionPageBuilderApp */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Image Compare Element View.
		FusionPageBuilder.iee_image_compare = FusionPageBuilder.ElementView.extend( {

			/**
			 * Runs when element is first ini.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			onInit: function() {
				this.listenTo( FusionEvents, 'fusion-preview-toggle', this.previewToggle );
				this.listenTo( FusionEvents, 'fusion-wireframe-toggle', this.previewToggle );
				this.listenTo( FusionEvents, 'fusion-iframe-loaded', this.initElement );
			},

			/**
			 * Init Element.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			initElement: function() {
				jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( 'body' ).trigger( 'fusion-element-render-iee_image_compare', this.model.attributes.cid );
			},

			/**
			 * Preview mode toggled..
			 *
			 * @since 2.0
			 * @return {void}
			 */
			previewToggle: function() {
				if ( ! FusionPageBuilderApp.wireframeActive ) {
					if ( jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( 'body' ).hasClass( 'fusion-builder-preview-mode' ) ) {
						this.disableDroppableElement();
					} else {
						this.enableDroppableElement();
					}
				}
			},

			/**
			 * Runs before view DOM is patched.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			beforePatch: function() {
				this.$el.css( 'min-height', this.$el.outerHeight() + 'px' );
			},

			/**
			 * Runs after view DOM is patched.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			afterPatch: function() {
				var self = this;

				this._refreshJs();

				setTimeout( function() {
					self.$el.css( 'min-height', '0px' );
				}, 300 );
			},

			/**
			 * Modify template attributes.
			 *
			 * @since 2.0
			 * @param {Object} atts - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			filterTemplateAtts: function( atts ) {
				var attributes = {};

				// Unique ID for this particular element instance, can be useful.
				attributes.cid = this.model.get( 'cid' );

				// Create attribute objects
				attributes.attr            = this.buildAttr( atts.params );
				attributes.beforeImageAttr = this.buildBeforeImageAttr( atts.params );
				attributes.afterImageAttr  = this.buildAfterImageAttr( atts.params );
				attributes.dragHandleAttr  = this.buildDragHandleAttr( atts.params );

				// Any extras that need passed on.
				attributes.content              = atts.params.element_content;
				attributes.after_image          = atts.params.after_image;
				attributes.after_image_caption  = atts.params.after_image_caption;
				attributes.before_image         = atts.params.before_image;
				attributes.before_image_caption = atts.params.before_image_caption;

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
				var attr = _.fusionVisibilityAtts( values.hide_on_mobile, {
						class: 'elegant-image-compare',
						style: ''
					} );

				if ( '' !== values.image_caption_position ) {
					attr['class'] += ' image-caption-position-' + values.image_caption_position;
				}

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
			buildBeforeImageAttr: function( values ) {
				var attr = {
						class: 'elegant-image-compare-label',
						style: ''
					};

				attr['data-type'] = 'modified';

				if ( '' !== values.before_image_caption_color ) {
					attr['style'] += 'color: ' + values.before_image_caption_color + ';';
				}

				if ( '' !== values.before_image_caption_background_color ) {
					attr['style'] += 'background-color: ' + values.before_image_caption_background_color + ';';
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
			buildAfterImageAttr: function( values ) {
				var attr = {
						class: 'elegant-image-compare-label',
						style: ''
					};

				attr['data-type'] = 'original';

				if ( '' !== values.after_image_caption_color ) {
					attr['style'] += 'color: ' + values.after_image_caption_color + ';';
				}

				if ( '' !== values.after_image_caption_background_color ) {
					attr['style'] += 'background-color: ' + values.after_image_caption_background_color + ';';
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
			buildDragHandleAttr: function( values ) {
				var attr = {
						class: 'elegant-image-compare-handle',
						style: ''
					};

				if ( '' !== values.handle_background_color ) {
					attr['style'] += 'background-color: ' + values.handle_background_color + ';';
				}

				return attr;
			}
		} );
	} );
}( jQuery ) );
