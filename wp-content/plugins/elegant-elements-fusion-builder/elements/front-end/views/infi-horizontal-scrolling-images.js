/* global FusionEvents */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Horizontal Scrolling Images Element View.
		FusionPageBuilder.iee_horizontal_scrolling_images = FusionPageBuilder.ParentElementView.extend( {

			/**
			 * Re-init slider on template import.
			 *
			 * @since 3.3.0
			 * @return {void}
			 */
			onInit: function() {
				this.listenTo( FusionEvents, 'elegant-template-imported', this.reInitSlider );
			},

			/**
			 * Runs on parent element init.
			 *
			 * @since 3.3.0
			 * @return {void}
			 */
			reInitSlider: function() {
				var self = this,
					element = jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( this.$el.find( '.elegant-slick-initialized' ) );

				// Destroy the slider, so that we can re-init after DOM patch.
				element.elegant_slick( 'unslick' );

				this.generateChildElements();

				setTimeout( function() {
					self.refreshSlider();
				}, 200 );
			},

			/**
			 * Fired when child view is added.
			 *
			 * @since 3.3.0
			 * @return {void}
			 */
			childViewAdded: function() {
				this.reInitSlider();
			},

			/**
			 * Fired when child view is removed.
			 *
			 * @since 3.3.0
			 * @return {void}
			 */
			childViewRemoved: function() {
				this.reInitSlider();
			},

			/**
			 * Fired when child view is cloned.
			 *
			 * @since 3.3.0
			 * @return {void}
			 */
			childViewCloned: function() {
				this.reInitSlider();
			},

			/**
			 * Runs during render() call.
			 *
			 * @since 3.3.0
			 * @return {void}
			 */
			onRender: function() {
				var that = this;

				jQuery( window ).on( 'load', function() {
					that.afterPatch();
				} );
			},

			/**
			 * Runs before element is removed.
			 *
			 * @since 3.3.0
			 * @return {void}
			 */
			beforeRemove: function() {
				this.beforePatch();
			},

			/**
			 * Runs after view DOM is patched.
			 *
			 * @since 3.3.0
			 * @return {void}
			 */
			beforePatch: function() {
				var element = jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( this.$el.find( '.elegant-slick-initialized' ) );

				// Destroy the slider, so that we can re-init after DOM patch.
				element.elegant_slick( 'unslick' );

			},

			/**
			 * Runs after view DOM is patched.
			 *
			 * @since 3.3.0
			 * @return {void}
			 */
			afterPatch: function() {

				// TODO: save DOM and apply instead of generating
				this.generateChildElements();

				this._refreshJs();

				this.refreshSlider();
			},

			/**
			 * Modify template attributes.
			 *
			 * @since 3.3.0
			 * @param {Object} atts - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			filterTemplateAtts: function( atts ) {
				var attributes = {};

				// Unique ID for this particular element instance, can be useful.
				attributes.cid = this.model.get( 'cid' );

				// Validate values.
				this.validateValues( atts.params );

				// Create attribute objects
				attributes.attr = this.buildAttr( atts.params );

				// Any extras that need passed on.
				attributes.params                 = atts.params;
				attributes.images_to_show         = atts.params.images_to_show;
				attributes.images_to_scroll       = atts.params.images_to_scroll;
				attributes.image_padding          = atts.params.image_padding;
				attributes.speed                  = atts.params.speed;
				attributes.autoplay               = atts.params.autoplay;
				attributes.autoplay_speed         = atts.params.autoplay_speed;
				attributes.odd_even_layout        = atts.params.odd_even_layout;
				attributes.alternate_slide_offset = atts.params.alternate_slide_offset;
				attributes.image_shape            = atts.params.image_shape;
				attributes.blob_shape             = atts.params.blob_shape;
				attributes.images_border_radius   = atts.params.images_border_radius;
				attributes.hide_on_mobile         = atts.params.hide_on_mobile;
				attributes.class                  = atts.params.class;
				attributes.id                     = atts.params.id;

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
				values.alternate_slide_offset = _.fusionGetValueWithUnit( values.alternate_slide_offset );
				values.images_border_radius   = _.fusionGetValueWithUnit( values.images_border_radius );
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
						class: 'elegant-horizontal-scrolling-images',
						style: ''
					};

				attr['class'] += ' elegant-horizontal-scrolling-images-' + this.model.get( 'cid' );

				if ( values.class ) {
					attr['class'] += ' ' + values.class;
				}

				if ( values.id ) {
					attr['id'] = values.id;
				}

				return attr;
			},

			/**
			 * Refresh slider.
			 *
			 * @since 3.3.0
			 * @return {void}
			 */
			refreshSlider: function() {
				var carouselSettings = {},
					cid = this.model.get( 'cid' ),
					values = this.model.get( 'params' );

				carouselSettings['slidesToShow']   = ( '' !== values.images_to_show ) ? parseInt( values.images_to_show ) : 1;
				carouselSettings['slidesToScroll'] = ( '' !== values.images_to_scroll ) ? parseInt( values.images_to_scroll ) : 1;
				carouselSettings['itemPadding']    = ( '' !== values.image_padding ) ? values.image_padding : '0px 0px 0px 0px';
				carouselSettings['speed']          = ( '' !== values.speed ) ? values.speed : 300;
				carouselSettings['autoplay']       = ( '' !== values.autoplay && 'yes' === values.autoplay ) ? true : true;
				carouselSettings['autoplaySpeed']  = ( '' !== values.autoplay_speed ) ? ( values.autoplay_speed * 1000 ) : 3000;
				carouselSettings['itemMargin']     = '0px 0px 0px 0px';
				carouselSettings['adaptiveHeight'] = true;
				carouselSettings['vertical']       = false;
				carouselSettings['arrows']         = false;
				carouselSettings['dots']           = false;
				carouselSettings['centerPadding']  = false;
				carouselSettings['draggable']      = true;
				carouselSettings['variableWidth']  = true;
				carouselSettings['centerMode']     = true;
				carouselSettings['infinite']       = false;

				if ( jQuery( 'body' ).hasClass( 'rtl' ) ) {
					carouselSettings['rtl'] = true;
				}

				jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( 'body' ).trigger( 'fusion-element-render-iee_carousel-render', [ cid, carouselSettings ] );
			}
		} );
	} );

	_.extend( FusionPageBuilder.Callback.prototype, {
		// Refresh slider on change.
		elegantRefreshCarousel: function( attributes, view ) {

			var carouselSettings = {},
				values = view.model.get( 'params' ),
				cid;

			carouselSettings['slidesToShow']   = ( '' !== values.images_to_show ) ? parseInt( values.images_to_show ) : 1;
			carouselSettings['slidesToScroll'] = ( '' !== values.images_to_scroll ) ? parseInt( values.images_to_scroll ) : 1;
			carouselSettings['itemPadding']    = ( '' !== values.image_padding ) ? values.image_padding : '0px 0px 0px 0px';
			carouselSettings['speed']          = ( '' !== values.speed ) ? values.speed : 300;
			carouselSettings['autoplay']       = ( '' !== values.autoplay && 'yes' === values.autoplay ) ? true : true;
			carouselSettings['autoplaySpeed']  = ( '' !== values.autoplay_speed ) ? ( values.autoplay_speed * 1000 ) : 3000;
			carouselSettings['itemMargin']     = '0px 0px 0px 0px';
			carouselSettings['adaptiveHeight'] = true;
			carouselSettings['vertical']       = false;
			carouselSettings['arrows']         = false;
			carouselSettings['dots']           = false;
			carouselSettings['centerPadding']  = false;
			carouselSettings['draggable']      = true;
			carouselSettings['variableWidth']  = true;
			carouselSettings['centerMode']     = true;
			carouselSettings['infinite']       = true;

			if ( jQuery( 'body' ).hasClass( 'rtl' ) ) {
				carouselSettings['rtl'] = true;
			}

			cid = view.model.get( 'cid' );

			jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( 'body' ).trigger( 'fusion-element-render-iee_carousel-render', [ cid, carouselSettings ] );

			return attributes;

		}
	} );
}( jQuery ) );
