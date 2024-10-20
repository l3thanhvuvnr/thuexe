/* global elegant_get_typography_css */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Fancy Button Element View.
		FusionPageBuilder.iee_fancy_button = FusionPageBuilder.ElementView.extend( {

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
				attributes.attr        = this.buildAttr( atts.params );
				attributes.linkAttr    = this.buildLinkAttr( atts.params );
				attributes.customStyle = this.buildCustomStyle( atts.params );

				// Any extras that need passed on.
				attributes.content                 = atts.params.element_content;
				attributes.button_title            = atts.params.button_title;
				attributes.style                   = atts.params.style;
				attributes.button_icon             = atts.params.button_icon;
				attributes.icon_position           = atts.params.icon_position;
				attributes.action                  = atts.params.action;
				attributes.custom_link             = atts.params.custom_link;
				attributes.target                  = atts.params.target;
				attributes.lightbox_image_url      = atts.params.lightbox_image_url;
				attributes.lightbox_video_url      = atts.params.lightbox_video_url;
				attributes.modal_name              = atts.params.modal_name;
				attributes.color                   = atts.params.color;
				attributes.color_hover             = atts.params.color_hover;
				attributes.background              = atts.params.background;
				attributes.size                    = atts.params.size;
				attributes.shape                   = atts.params.shape;
				attributes.margin                  = atts.params.margin;
				attributes.element_typography      = atts.params.element_typography;
				attributes.typography_button_title = atts.params.typography_button_title;
				attributes.title_font_size         = atts.params.title_font_size;
				attributes.alignment               = atts.params.alignment;

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
				values.title_font_size = _.fusionGetValueWithUnit( values.title_font_size );

				values.alignment = ( 'undefined' !== typeof values.alignment ) ? values.alignment : 'left';
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
						class: 'elegant-fancy-button-wrap elegant-fancy-button-' + this.model.get( 'cid' ),
						style: ''
					} );

				if ( '' !== values.margin ) {
					attr['style'] = 'margin:' + values.margin + ';';
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
			buildLinkAttr: function( values ) {
				var attr = {
						class: 'elegant-fancy-button-link elegant-button-' + values.style + ' fusion-button-' + values.shape + ' button-' + values.size,
						style: '',
						href: '#'
					},
					typography,
					button_title_typography;

				if ( '' !== values.icon_position ) {
					attr['class'] += ' elegant-fancy-button-icon-' + values.icon_position;
				}

				if ( 'custom_link' === values.action ) {
					attr['href']   = ( '' !== values.custom_link ) ? values.custom_link : '#';
					attr['target'] = ( '' !== values.target ) ? values.target : '_self';
				} else if ( 'image_lightbox' === values.action ) {
					attr['href']     = ( '' !== values.lightbox_image_url ) ? values.lightbox_image_url : '#';
					attr['data-rel'] = 'prettyPhoto';
				} else if ( 'video_lightbox' === values.action ) {
					attr['href']     = ( '' !== values.lightbox_video_url ) ? values.lightbox_video_url : '#';
					attr['data-rel'] = 'prettyPhoto';
				} else if ( 'modal' === values.action ) {
					attr['data-toggle'] = 'modal';
					attr['data-target'] = '.modal.' + values.modal_name;
				}

				if ( '' !== values.typography_button_title ) {
					typography              = values.typography_button_title;
					button_title_typography = elegant_get_typography_css( typography );

					attr['style'] += button_title_typography;
				}

				if ( '' !== values.title_font_size ) {
					attr['style'] += 'font-size:' + values.title_font_size + ';';
					attr['style'] += 'line-height: 1em;';
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
			buildCustomStyle: function( values ) {
				var colorObj,
					color_css,
					hover_color,
					style_class,
					customStyle = '',
					cid = this.model.get( 'cid' );

				if ( '' !== values.color ) {
					hover_color = ( '' !== values.color_hover ) ? '--color:' + values.color_hover + ';' : '',
					customStyle += '.elegant-fancy-button-wrap.elegant-fancy-button-' + cid + ' .elegant-fancy-button-link { color:' + values.color + ';' + hover_color + ' border-color:' + values.color + ';--border-color:' + values.background + ' }';
				}

				if ( '' !== values.background ) {
					style_class = '.elegant-fancy-button-wrap.elegant-fancy-button-' + cid + ' .elegant-fancy-button-link.elegant-button-' + values.style;

					switch ( values.style ) {
						case 'swipe':
							customStyle += style_class + ':before{ background:' + values.background + ';}';
							break;
						case 'diagonal-swipe':
							customStyle += style_class + ':after { border-top-color:' + values.background + ';}';
							break;
						case 'double-swipe':
							customStyle += style_class + ':before { border-left-color:' + values.background + ';}';
							customStyle += style_class + ':after { border-bottom-color:' + values.background + ';}';
							break;
						case 'zoning-in':
							customStyle += style_class + ':before { border-left-color:' + values.background + ';}';
							customStyle += style_class + ':after { border-right-color:' + values.background + ';}';
							customStyle += style_class + ' span:before { border-bottom-color:' + values.background + ';}';
							customStyle += style_class + ' span:after { border-top-color:' + values.background + ';}';
							break;
						case 'diagonal-close':
							customStyle += style_class + ':before { border-left-color:' + values.background + ';}';
							customStyle += style_class + ':after { border-right-color:' + values.background + ';}';
							break;
						case 'corners':
							customStyle += style_class + ':before, ' + style_class + ':after, ' + style_class + ' span:before, ' + style_class + ' span:after { border-color:' + values.background + ';}';
							break;
						case 'alternate':
							customStyle += style_class + ':before, ' + style_class + ':after, ' + style_class + ' span:before, ' + style_class + ' span:after { background-color:' + values.background + ';}';
							break;
						case 'slice':
							customStyle += style_class + ':before{ border-left-color:' + values.background + ';}';
							customStyle += style_class + ':after{ border-right-color:' + values.background + ';}';
							break;
						case 'position-aware':
							customStyle += style_class + ' span{ background-color:' + values.background + ';}';
							break;
						case 'smoosh':
						case 'collision':
							customStyle += style_class + ':before, ' + style_class + ':after{ background-color:' + values.background + ';}';
							break;
						case 'vertical-overlap':
						case 'horizontal-overlap':
							colorObj     = jQuery.Color( values.background );
							color_css    = colorObj.alpha( 0.5 ).toRgbaString();
							customStyle += style_class + ':before, ' + style_class + ':after{ background-color:' + color_css + ';}';
							customStyle += style_class + ' span:before, ' + style_class + ' span:after{ background-color:' + color_css + ';}';
							break;
					}
				}

				return customStyle;
			}
		} );
	} );
}( jQuery ) );
