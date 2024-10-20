/* global elegant_get_typography_css */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Testimonials Element View.
		FusionPageBuilder.iee_testimonials = FusionPageBuilder.ParentElementView.extend( {

			/**
			 * Runs during render() call.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			onRender: function() {
				var $this = this;

				jQuery( window ).on( 'load', function() {
					$this._refreshJs();
				} );
			},

			/**
			 * Runs during onRender() call in child.
			 *
			 * @since 2.0
			 * @param {String} childCid - The model CID.
			 * @return {void}
			 */
			onRenderChild: function( childCid ) {
				var that = this,
					cid = this.model.get( 'cid' );

				setTimeout( function() {
					var children = window.FusionPageBuilderViewManager.getChildViews( cid ),
						params = that.model.get( 'params' ),
						index = 1;

					_.every( children, function( child ) {
						var childValues = child.model.get( 'params' ),
							testimonialDescription = '',
							activeClass = '';

						childValues.heading_size    = params.heading_size;
						childValues.cid             = child.model.get( 'cid' );
						childValues.title_color     = ( 'undefined' !== typeof childValues.title_color && '' !== childValues.title_color ) ? childValues.title_color : '';
						childValues.sub_title_color = ( 'undefined' !== typeof childValues.sub_title_color && '' !== childValues.sub_title_color ) ? childValues.sub_title_color : '';

						if ( childCid === childValues.cid ) {
							that.$el.find(  '.elegant-testimonial-' + childValues.cid ).siblings().removeClass( 'active-description' );
							activeClass = 'active-description';
						} else {
							activeClass = ( 1 == index ) ? 'active-description' : '';
						}

						index++;

						childValues.active_class    = activeClass;

						testimonialDescription = that.getTestimonialDescriptionContent( childValues );

						if ( childCid === childValues.cid ) {
							that.$el.find(  '.elegant-testimonial-' + childValues.cid ).replaceWith( testimonialDescription );
							return false;
						}

						if ( ! that.$el.find( '.elegant-testimonial-' + childValues.cid ).length ) {
							that.$el.find( '.elegant-testimonials-description-container' ).append( testimonialDescription );
						}

						return true;
					} );
				}, 100 );
			},

			/**
			 * Compiles and returns the testimonial description content.
			 *
			 * @since 2.0
			 * @param {Object} data - The values.
			 * @return {String} Content after compiling the template file.
			 */
			getTestimonialDescriptionContent: function( data ) {
				var testimonialDescriptionTemplate = FusionPageBuilder.template( jQuery( '#tmpl-iee-testimonial-content-template' ).html() ),
					attributes        = {};

				if ( 'object' !== typeof data ) {
					return '';
				}

				attributes = data;

				return testimonialDescriptionTemplate( attributes );
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
			},

			/**
			 * Triggers custom event for filters element on settings change.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			refreshJs: function() {
				jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( 'body' ).trigger( 'fusion-element-render-iee_testimonials', this.model.attributes.cid );
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

				// Validate values.
				this.validateValues( atts.params );

				// Unique ID for this particular element instance, can be useful.
				attributes.cid = this.model.get( 'cid' );

				// Create attribute objects
				attributes.wrapperAttr = this.buildWrapperAttr( atts.params );
				attributes.styles      = this.buildStyles( atts.params );

				// Any extras that need passed on.
				attributes.params                  = atts.params;
				attributes.images_background_color = atts.images_background_color;
				attributes.position                = atts.params.description_position;
				attributes.typography_title        = atts.params.typography_title;
				attributes.typography_sub_title    = atts.params.typography_sub_title;
				attributes.typography_content      = atts.params.typography_content;

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
				values.images_background_color = ( 'undefined' !== values.background_color && '' !== values.background_color ) ? values.background_color : '';
				values.content_font_size       = _.fusionGetValueWithUnit( values.content_font_size );
				values.title_font_size         = _.fusionGetValueWithUnit( values.title_font_size );
				values.sub_title_font_size     = _.fusionGetValueWithUnit( values.sub_title_font_size );
				values.description_position    = ( 'undefined' !== typeof values.description_position ) ? values.description_position : 'left';
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildWrapperAttr: function( values ) {
				var attr = _.fusionVisibilityAtts( values.hide_on_mobile, {
						class: 'elegant-testimonials-container elegant-testimonials-position-' + values.description_position + ' elegant-testimonial-container-' + this.model.get( 'cid' ),
						style: ''
					} );

				if ( values.class ) {
					attr['class'] += ' ' + values.class;
				}

				if ( values.id ) {
					attr['id'] = values.id;
				}

				return attr;
			},

			/**
			 * Builds the custom style.
			 *
			 * @access public
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {String} Custom styling.
			 */
			buildStyles: function( values ) {
				var main_class      = '.elegant-testimonials-container.elegant-testimonial-container-' + this.model.get( 'cid' ),
					title_class     = main_class + ' ' + values.heading_size + '.elegant-testimonials-title',
					sub_title_class = main_class + ' .elegant-testimonials-subtitle',
					content_class   = main_class + ' .elegant-testimonials-content',
					title_font_family = '',
					sub_title_font_family = '',
					content_font_family = '',
					style = '';

				if ( 'undefined' !== typeof values.typography_title && '' !== values.typography_title ) {
					title_font_family = elegant_get_typography_css( values.typography_title, true );
				}

				if ( 'undefined' !== typeof values.typography_sub_title && '' !== values.typography_sub_title ) {
					sub_title_font_family = elegant_get_typography_css( values.typography_sub_title, true );
				}

				if ( 'undefined' !== typeof values.typography_content && '' !== values.typography_content ) {
					content_font_family = elegant_get_typography_css( values.typography_content, true );
				}

				style += main_class + '{';
				if ( '' !== values.text_color ) {
					style += 'color: ' + values.text_color + ';';
				}
				if ( '' !== values.content_font_size ) {
					style += 'font-size: ' + values.content_font_size + ';';
				}
				if ( '' !== values.background_image ) {
					style += 'background-image: url(' + values.background_image + ');';
					style += 'background-position: ' + values.background_position + ';';
				}
				if ( '' !== values.background_color ) {
					style += 'background-color: ' + values.background_color + ';';
					style += 'background-blend-mode: overlay;';
				}
				style += '}';

				style += title_class + '{';
				style += title_font_family;

				if ( '' !== values.title_font_size ) {
					style += 'font-size: ' + values.title_font_size + ' !important;';
				}

				style += '}';

				style += sub_title_class + '{';
				style += sub_title_font_family;

				if ( '' !== values.sub_title_font_size ) {
					style += 'font-size: ' + values.sub_title_font_size + ';';
				}

				style += '}';

				style += content_class + '{';
				style += content_font_family;
				style += '}';

				return style;
			}
		} );
	} );

	_.extend( FusionPageBuilder.Callback.prototype, {
		// Testimonials filter.
		elegantTestimonialsShortcodeFilter: function( attributes, view ) {

			var parentView = window.FusionPageBuilderViewManager.getView( view.model.get( 'parent' ) ),
				cid = view.model.get( 'cid' );

			parentView.onRenderChild( cid );

			return attributes;

		}
	} );
}( jQuery ) );
