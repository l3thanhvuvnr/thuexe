/* global FusionEvents */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Carousel Element View.
		FusionPageBuilder.iee_carousel = FusionPageBuilder.ParentElementView.extend( {

			/**
			 * Re-init slider on template import.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			onInit: function() {
				this.listenTo( FusionEvents, 'elegant-template-imported', this.reInitSlider );
			},

			/**
			 * Runs on parent element init.
			 *
			 * @since 2.0
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
			 * @since 2.0
			 * @return {void}
			 */
			childViewAdded: function() {
				this.reInitSlider();
			},

			/**
			 * Fired when child view is removed.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			childViewRemoved: function() {
				this.reInitSlider();
			},

			/**
			 * Fired when child view is cloned.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			childViewCloned: function() {
				this.reInitSlider();
			},

			/**
			 * Runs during render() call.
			 *
			 * @since 2.0
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
			 * @since 2.0
			 * @return {void}
			 */
			beforeRemove: function() {
				this.beforePatch();
			},

			/**
			 * Runs after view DOM is patched.
			 *
			 * @since 2.0
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
			 * @since 2.0
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
			 * @since 2.0
			 * @param {Object} atts - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			filterTemplateAtts: function( atts ) {
				var attributes = {};

				// Unique ID for this particular element instance, can be useful.
				attributes.cid = this.model.get( 'cid' );

				// Any extras that need passed on.
				attributes.params        = atts.params;
				attributes.border_size   = ( 'undefined' !== typeof atts.params.border_size ) ? atts.params.border_size : '';
				attributes.border_color  = ( 'undefined' !== typeof atts.params.border_color ) ? atts.params.border_color : '#dddddd';
				attributes.border_style  = ( 'undefined' !== typeof atts.params.border_style ) ? atts.params.border_style : 'solid';

				if ( 'undefined' !== typeof atts.params.border_radius && 'round' === atts.params.border_radius ) {
					attributes.border_radius = '3px';
				} else {
					attributes.border_radius = '0px';
				}

				return attributes;
			},

			/**
			 * Refresh slider.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			refreshSlider: function() {
				var carouselSettings = {},
					cid = this.model.get( 'cid' ),
					values = this.model.get( 'params' );

				carouselSettings['vertical']         = false;
				carouselSettings['slidesToShow']     = ( 'slide' === values.fade ) ? parseInt( values.slides_to_show ) : 1;
				carouselSettings['slidesToScroll']   = ( 'slide' === values.fade ) ? parseInt( values.slides_to_scroll ) : 1;
				carouselSettings['speed']            = ( '' !== values.speed ) ? values.speed : 300;
				carouselSettings['fade']             = ( 'fade' === values.fade ) ? true : false;
				carouselSettings['infinite']         = false;
				carouselSettings['centerMode']       = false;
				carouselSettings['centerPadding']    = ( 'yes' === values.center_padding ) ? true : false;
				carouselSettings['accessibility']    = false;
				carouselSettings['adaptiveHeight']   = ( 'yes' === values.adaptive_height ) ? true : false;
				carouselSettings['arrows']           = ( 'yes' === values.arrows ) ? true : false;
				carouselSettings['dots']             = ( 'yes' === values.dots ) ? true : false;
				carouselSettings['autoplay']         = ( 'yes' === values.autoplay ) ? true : false;
				carouselSettings['autoplaySpeed']    = ( values.autoplay_speed ) ? ( values.autoplay_speed * 1000 ) : 3000;
				carouselSettings['draggable']        = ( 'yes' === values.draggable ) ? true : false;
				carouselSettings['itemPadding']      = ( values.item_padding ) ? values.item_padding : '0px 0px 0px 0px';
				carouselSettings['itemMargin']       = ( values.item_margin ) ? values.item_margin : '0px 0px 0px 0px';
				carouselSettings['nextArrowIcon']    = ( '' !== values.next_arrow_icon ) ? values.next_arrow_icon : '';
				carouselSettings['prevArrowIcon']    = ( '' !== values.prev_arrow_icon ) ? values.prev_arrow_icon : '';
				carouselSettings['arrowColor']       = ( '' !== values.arrow_color ) ? values.arrow_color : '';
				carouselSettings['arrowFontSize']    = ( '' !== values.arrow_font_size ) ? values.arrow_font_size + 'px' : '24px';
				carouselSettings['dotsIcon']         = ( '' !== values.dots_icon_class ) ? values.dots_icon_class : '';
				carouselSettings['dotsColor']        = ( '' !== values.dots_color ) ? values.dots_color : '';
				carouselSettings['dotsColorActive']  = ( '' !== values.dots_color_active ) ? values.dots_color_active : '';
				carouselSettings['dotsFontSize']     = ( '' !== values.dots_font_size ) ? values.dots_font_size + 'px' : '18px';
				carouselSettings['pauseOnHover']     = ( 'yes' === values.pause_on_hover ) ? true : false;
				carouselSettings['pauseOnDotsHover'] = ( 'yes' === values.pause_on_dots_hover ) ? true : false;

				if ( 'yes' === values.responsive ) {
					carouselSettings['responsive'] = [
						{
							'breakpoint': 1024,
							'settings': {
								'slidesToShow': values.ipad_landscape_slides_to_show,
								'slidesToScroll': values.ipad_landscape_slides_to_show
							}
						},
						{
							'breakpoint': 768,
							'settings': {
								'slidesToShow': values.ipad_portrait_slides_to_show,
								'slidesToScroll': values.ipad_portrait_slides_to_show,
								'initialSlide': 0
							}
						},
						{
							'breakpoint': 480,
							'settings': {
								'slidesToShow': values.mobile_landscape_slides_to_show,
								'slidesToScroll': values.mobile_landscape_slides_to_show,
								'initialSlide': 0
							}
						}
					];
				}

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

			carouselSettings['vertical']         = false;
			carouselSettings['slidesToShow']     = ( 'slide' === values.fade ) ? parseInt( values.slides_to_show ) : 1;
			carouselSettings['slidesToScroll']   = ( 'slide' === values.fade ) ? parseInt( values.slides_to_scroll ) : 1;
			carouselSettings['speed']            = ( '' !== values.speed ) ? values.speed : 300;
			carouselSettings['fade']             = ( 'fade' === values.fade ) ? true : false;
			carouselSettings['infinite']         = false;
			carouselSettings['centerMode']       = false;
			carouselSettings['centerPadding']    = ( 'yes' === values.center_padding ) ? true : false;
			carouselSettings['accessibility']    = false;
			carouselSettings['adaptiveHeight']   = ( 'yes' === values.adaptive_height ) ? true : false;
			carouselSettings['arrows']           = ( 'yes' === values.arrows ) ? true : false;
			carouselSettings['dots']             = ( 'yes' === values.dots ) ? true : false;
			carouselSettings['autoplay']         = ( 'yes' === values.autoplay ) ? true : false;
			carouselSettings['autoplaySpeed']    = ( values.autoplay_speed ) ? ( values.autoplay_speed * 1000 ) : 3000;
			carouselSettings['draggable']        = ( 'yes' === values.draggable ) ? true : false;
			carouselSettings['itemPadding']      = ( values.item_padding ) ? values.item_padding : '0px 0px 0px 0px';
			carouselSettings['itemMargin']       = ( values.item_margin ) ? values.item_margin : '0px 0px 0px 0px';
			carouselSettings['nextArrowIcon']    = ( '' !== values.next_arrow_icon ) ? values.next_arrow_icon : '';
			carouselSettings['prevArrowIcon']    = ( '' !== values.prev_arrow_icon ) ? values.prev_arrow_icon : '';
			carouselSettings['arrowColor']       = ( '' !== values.arrow_color ) ? values.arrow_color : '';
			carouselSettings['arrowFontSize']    = ( '' !== values.arrow_font_size ) ? values.arrow_font_size + 'px' : '24px';
			carouselSettings['dotsIcon']         = ( '' !== values.dots_icon_class ) ? values.dots_icon_class : '';
			carouselSettings['dotsColor']        = ( '' !== values.dots_color ) ? values.dots_color : '';
			carouselSettings['dotsColorActive']  = ( '' !== values.dots_color_active ) ? values.dots_color_active : '';
			carouselSettings['dotsFontSize']     = ( '' !== values.dots_font_size ) ? values.dots_font_size + 'px' : '18px';
			carouselSettings['pauseOnHover']     = ( 'yes' === values.pause_on_hover ) ? true : false;
			carouselSettings['pauseOnDotsHover'] = ( 'yes' === values.pause_on_dots_hover ) ? true : false;

			if ( 'yes' === values.responsive ) {
				carouselSettings['responsive'] = [
					{
						'breakpoint': 1024,
						'settings': {
							'slidesToShow': values.ipad_landscape_slides_to_show,
							'slidesToScroll': values.ipad_landscape_slides_to_show
						}
					},
					{
						'breakpoint': 768,
						'settings': {
							'slidesToShow': values.ipad_portrait_slides_to_show,
							'slidesToScroll': values.ipad_portrait_slides_to_show,
							'initialSlide': 0
						}
					},
					{
						'breakpoint': 480,
						'settings': {
							'slidesToShow': values.mobile_landscape_slides_to_show,
							'slidesToScroll': values.mobile_landscape_slides_to_show,
							'initialSlide': 0
						}
					}
				];
			}

			if ( jQuery( 'body' ).hasClass( 'rtl' ) ) {
				carouselSettings['rtl'] = true;
			}

			cid = view.model.get( 'cid' );

			jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( 'body' ).trigger( 'fusion-element-render-iee_carousel-render', [ cid, carouselSettings ] );

			return attributes;

		}
	} );
}( jQuery ) );
