/* global FusionPageBuilderApp */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Lottie Content Box Element View.
		FusionPageBuilder.iee_lottie_content_box = FusionPageBuilder.ElementView.extend( {

			/**
			 * Modify template attributes.
			 *
			 * @since 3.6.0
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
				attributes.attr             = this.buildAttr( atts.params );
				attributes.icon_attr        = this.buildIconAttr( atts.params );
				attributes.player_attr      = this.buildPlayerAttr( atts.params );
				attributes.content_attr     = this.buildContentAttr( atts.params );
				attributes.heading_attr     = this.buildHeadingAttr( atts.params );
				attributes.description_attr = this.buildDescriptionAttr( atts.params );
				attributes.link_text_attr   = this.buildLinkTextAttr( atts.params );

				// Any extras that need passed on.
				attributes.heading_text                    = atts.params.heading_text;
				attributes.heading_size                    = atts.params.heading_size;
				attributes.heading_font_size               = atts.params.heading_font_size;
				attributes.description_font_size           = atts.params.description_font_size;
				attributes.link_text_font_size             = atts.params.link_text_font_size;
				attributes.description_text                = atts.params.description_text;
				attributes.icon_url                        = atts.params.icon_url;
				attributes.icon_height                     = atts.params.icon_height;
				attributes.icon_width                      = atts.params.icon_width;
				attributes.animation_mode                  = atts.params.animation_mode;
				attributes.animation_play                  = atts.params.animation_play;
				attributes.animation_loop                  = atts.params.animation_loop;
				attributes.icon_position                   = atts.params.icon_position;
				attributes.content_alignment               = atts.params.content_alignment;
				attributes.icon_alignment                  = atts.params.icon_alignment;
				attributes.box_background_color            = atts.params.box_background_color;
				attributes.heading_text_color              = atts.params.heading_text_color;
				attributes.content_text_color              = atts.params.content_text_color;
				attributes.box_padding_top                 = atts.params.box_padding_top;
				attributes.box_padding_right               = atts.params.box_padding_right;
				attributes.box_padding_bottom              = atts.params.box_padding_bottom;
				attributes.box_padding_left                = atts.params.box_padding_left;
				attributes.border_size                     = atts.params.border_size;
				attributes.border_color                    = atts.params.border_color;
				attributes.border_style                    = atts.params.border_style;
				attributes.border_radius                   = atts.params.border_radius;
				attributes.border_radius_top_left          = atts.params.border_radius_top_left;
				attributes.border_radius_top_right         = atts.params.border_radius_top_right;
				attributes.border_radius_bottom_right      = atts.params.border_radius_bottom_right;
				attributes.border_radius_bottom_left       = atts.params.border_radius_bottom_left;
				attributes.icon_border_size                = atts.params.icon_border_size;
				attributes.icon_background_color           = atts.params.icon_background_color;
				attributes.icon_border_color               = atts.params.icon_border_color;
				attributes.icon_border_style               = atts.params.icon_border_style;
				attributes.icon_border_radius              = atts.params.icon_border_radius;
				attributes.icon_border_radius_top_left     = atts.params.icon_border_radius_top_left;
				attributes.icon_border_radius_top_right    = atts.params.icon_border_radius_top_right;
				attributes.icon_border_radius_bottom_right = atts.params.icon_border_radius_bottom_right;
				attributes.icon_border_radius_bottom_left  = atts.params.icon_border_radius_bottom_left;
				attributes.icon_padding_top                = atts.params.icon_padding_top;
				attributes.icon_padding_right              = atts.params.icon_padding_right;
				attributes.icon_padding_bottom             = atts.params.icon_padding_bottom;
				attributes.icon_padding_left               = atts.params.icon_padding_left;
				attributes.link_type                       = atts.params.link_type;
				attributes.link_text                       = atts.params.link_text;
				attributes.link_url                        = atts.params.link_url;
				attributes.link_target                     = atts.params.link_target;
				attributes.link_text_color                 = atts.params.link_text_color;
				attributes.content                         = atts.params.element_content;

				return attributes;
			},

			/**
			 * Modifies values.
			 *
			 * @since 3.6.0
			 * @param {Object} values - The values.
			 * @return {void}
			 */
			validateValues: function( values ) {
				var paddingValues;

				if ( ! values.box_padding_top ) {
					values.box_padding_top = '25px';
				} else {
					values.box_padding_top = _.fusionGetValueWithUnit( values.box_padding_top );
				}

				if ( ! values.box_padding_right ) {
					values.box_padding_right = '25px';
				} else {
					values.box_padding_right = _.fusionGetValueWithUnit( values.box_padding_right );
				}

				if ( ! values.box_padding_bottom ) {
					values.box_padding_bottom = '25px';
				} else {
					values.box_padding_bottom = _.fusionGetValueWithUnit( values.box_padding_bottom );
				}

				if ( ! values.box_padding_left ) {
					values.box_padding_left = '25px';
				} else {
					values.box_padding_left = _.fusionGetValueWithUnit( values.box_padding_left );
				}

				paddingValues  = 'padding-top:' + values.box_padding_top + ';';
				paddingValues += 'padding-right:' + values.box_padding_right + ';';
				paddingValues += 'padding-bottom:' + values.box_padding_bottom + ';';
				paddingValues += 'padding-left:' + values.box_padding_left + ';';

				values.box_padding = paddingValues;

				if ( ! values.icon_padding_top ) {
					values.icon_padding_top = '15px';
				} else {
					values.icon_padding_top = _.fusionGetValueWithUnit( values.icon_padding_top );
				}

				if ( ! values.icon_padding_right ) {
					values.icon_padding_right = '15px';
				} else {
					values.icon_padding_right = _.fusionGetValueWithUnit( values.icon_padding_right );
				}

				if ( ! values.icon_padding_bottom ) {
					values.icon_padding_bottom = '15px';
				} else {
					values.icon_padding_bottom = _.fusionGetValueWithUnit( values.icon_padding_bottom );
				}

				if ( ! values.icon_padding_left ) {
					values.icon_padding_left = '15px';
				} else {
					values.icon_padding_left = _.fusionGetValueWithUnit( values.icon_padding_left );
				}

				paddingValues  = 'padding-top:' + values.icon_padding_top + ';';
				paddingValues += 'padding-right:' + values.icon_padding_right + ';';
				paddingValues += 'padding-bottom:' + values.icon_padding_bottom + ';';
				paddingValues += 'padding-left:' + values.icon_padding_left + ';';

				values.icon_padding = paddingValues;

				values.icon_height = _.fusionGetValueWithUnit( values.icon_height );
				values.icon_width  = _.fusionGetValueWithUnit( values.icon_width );

				// Decode the button shortcode.
				try {
					if ( FusionPageBuilderApp.base64Encode( FusionPageBuilderApp.base64Decode( values.element_content ) ) === values.element_content ) {
						values.element_content = FusionPageBuilderApp.base64Decode( values.element_content );
					}
				} catch ( error ) {
					// Print error here.
				}

				// Check if the lottie file url changed.
				if ( values.icon_url !== jQuery( this.$el ).find( '.elegant-lottie-content-box' ).attr( 'src' ) ) {
					jQuery( this.$el ).find( '.elegant-lottie-content-box' ).html( '' );
					jQuery( this.$el ).find( '.elegant-lottie-content-box' ).attr( 'src', values.icon_url );
				}
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 3.6.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttr: function( values ) {
				var attr = _.fusionVisibilityAtts( values.hide_on_mobile, {
						class: 'elegant-lottie-content-box',
						style: ''
					} );

				attr['class'] += ' icon-position-' + values.icon_position;
				attr['class'] += ' link-type-' + values.link_type;

				if ( '0px' !== _.fusionGetValueWithUnit( values.border_size ) ) {
					attr['style'] += 'border-width:' + _.fusionGetValueWithUnit( values.border_size ) + ';';
					attr['style'] += 'border-color:' +  values.border_color + ';';
					attr['style'] += 'border-style:' +  values.border_style + ';';
				}

				if ( values.border_radius_top_left ) {
					attr['style'] += 'border-top-left-radius:' + _.fusionGetValueWithUnit( values.border_radius_top_left ) + ';';
				}

				if ( values.border_radius_top_right ) {
					attr['style'] += 'border-top-right-radius:' + _.fusionGetValueWithUnit( values.border_radius_top_right ) + ';';
				}

				if ( values.border_radius_bottom_right ) {
					attr['style'] += 'border-bottom-right-radius:' + _.fusionGetValueWithUnit( values.border_radius_bottom_right ) + ';';
				}

				if ( values.border_radius_bottom_left ) {
					attr['style'] += 'border-bottom-left-radius:' + _.fusionGetValueWithUnit( values.border_radius_bottom_left ) + ';';
				}

				attr['style'] += values.box_padding;

				if ( '' !== values.box_background_color ) {
					attr['style'] += 'background-color:' + values.box_background_color + ';';
				}

				if ( 'border_top' === values.icon_position ) {
					attr['style'] += 'margin-top:' + values.icon_width + ';';
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
			 * @since 3.6.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildIconAttr: function( values ) {
				var attr = {
						class: 'elegant-lottie-content-box-icon',
						style: ''
					},
					$icon_width,
					$icon_width_full,
					$box_padding_top,
					$icon_padding_top,
					$icon_border;

				attr['style'] += 'height:' + values.icon_height + ';';
				attr['style'] += 'width:' + values.icon_width + ';';

				if ( '0px' !==  _.fusionGetValueWithUnit( values.icon_border_size ) ) {
					attr['style'] += 'border-width:' + _.fusionGetValueWithUnit( values.icon_border_size ) + ';';
					attr['style'] += 'border-color:' + values.icon_border_color + ';';
					attr['style'] += 'border-style:' + values.icon_border_style + ';';
				}

				if ( values.icon_border_radius_top_left ) {
					attr['style'] += 'border-top-left-radius:' + _.fusionGetValueWithUnit( values.icon_border_radius_top_left ) + ';';
				}

				if ( values.icon_border_radius_top_left ) {
					attr['style'] += 'border-top-right-radius:' + _.fusionGetValueWithUnit( values.icon_border_radius_top_right ) + ';';
				}

				if ( values.icon_border_radius_top_left ) {
					attr['style'] += 'border-bottom-right-radius:' + _.fusionGetValueWithUnit( values.icon_border_radius_bottom_right ) + ';';
				}

				if ( values.icon_border_radius_top_left ) {
					attr['style'] += 'border-bottom-left-radius:' + _.fusionGetValueWithUnit( values.icon_border_radius_bottom_left ) + ';';
				}

				if ( '' !== values.icon_background_color ) {
					attr['style'] += 'background-color:' + values.icon_background_color + ';';
				}

				attr['style'] += values.icon_padding + ' box-sizing: content-box;';

				$icon_width       = 'calc(' + _.fusionGetValueWithUnit( values.icon_width ) + ' / 2 )';
				$icon_width_full  = _.fusionGetValueWithUnit( values.icon_width );
				$box_padding_top  = _.fusionGetValueWithUnit( values.box_padding_top );
				$icon_padding_top = _.fusionGetValueWithUnit( values.icon_padding_top );
				$icon_border      = 'calc(' + _.fusionGetValueWithUnit( values.icon_border_size ) + ' / 2 )';

				attr['class'] += ' elegant-align-' + values.icon_alignment;

				if ( 'border_top' === values.icon_position ) {
					attr['style'] += 'margin-top: calc( -' + ( '' === $box_padding_top ? '15px' : $box_padding_top ) + ' - ' + $icon_width + ' - ' + ( '' === $icon_padding_top ? '1' : $icon_padding_top ) + ' - ' + $icon_border + ' );';
					attr['style'] += 'margin-bottom: ' + $box_padding_top + ';';
				}

				if ( 'right' === values.icon_alignment ) {
					attr['style'] += 'right: calc( -100% + ' + $icon_width_full + ');';
				}

				return attr;
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 3.6.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildPlayerAttr: function( values ) {
				var attr = {
						class: 'elegant-lottie-content-box',
						src: values.icon_url,
						background: values.icon_background_color,
						speed: 1,
						style: ''
					};

				if ( 'bounce' === values.animation_mode ) {
					attr['mode'] = 'bounce';
				}

				if ( 'yes' === values.animation_loop ) {
					attr['loop'] = true;
				}

				attr[ values.animation_play ] = true;

				return attr;
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 3.6.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildContentAttr: function( values ) {
				var attr = {
						class: 'elegant-lottie-content-box-content',
						style: ''
					},
					$box_padding_left,
					$icon_width,
					$box_padding_right;

				attr['class'] += ' elegant-align-' + values.content_alignment;

				$box_padding_left  = _.fusionGetValueWithUnit( values.box_padding_left );
				$box_padding_right = _.fusionGetValueWithUnit( values.box_padding_right );

				if ( 'left' === values.icon_position ) {
					$icon_width    = _.fusionGetValueWithUnit( values.icon_width );
					attr['style'] += 'width: calc( 100% - ' + $box_padding_left + ' - ' + $box_padding_right + ' - ' + $icon_width + ' );';
				}

				if ( 'right' === values.icon_position ) {
					$icon_width    = _.fusionGetValueWithUnit( values.icon_width );
					attr['style'] += 'width: calc( 100% - ' + $box_padding_left + ' - ' + $box_padding_right + ' - ' + $icon_width + ' );';
				}

				return attr;
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 3.6.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildHeadingAttr: function( values ) {
				var attr = {
						class: 'elegant-lottie-content-box-heading',
						style: ''
					},
					$font_size;

				if ( '' !== values.heading_text_color ) {
					attr['style'] += 'color:' + values.heading_text_color + ';';
				}

				$font_size     = _.fusionGetValueWithUnit( values.heading_font_size );
				attr['style'] += 'font-size:' + $font_size + ';';

				attr = _.fusionInlineEditor(
					{
						cid: this.model.get( 'cid' ),
						param: 'heading_text',
						toolbar: false,
						'disable-return': true
					},
					attr
				);

				return attr;
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 3.6.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildDescriptionAttr: function( values ) {
				var attr = {
						class: 'elegant-lottie-content-box-description',
						style: ''
					},
					$font_size;

				if ( '' !== values.content_text_color ) {
					attr['style'] += 'color:' + values.content_text_color + ';';
				}

				$font_size     = _.fusionGetValueWithUnit( values.description_font_size );
				attr['style'] += 'font-size:' + $font_size + ';';

				attr = _.fusionInlineEditor(
					{
						cid: this.model.get( 'cid' ),
						param: 'description_text',
						toolbar: false,
						'disable-return': true
					},
					attr
				);

				return attr;
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 3.6.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildLinkTextAttr: function( values ) {
				var attr = {
						class: 'elegant-lottie-content-box-link-text',
						style: ''
					},
					$font_size;

				if ( '' !== values.link_text_color ) {
					attr['style'] += 'color:' + values.link_text_color + ';';
				}

				if ( '' !== values.link_text_font_size ) {
					$font_size     = _.fusionGetValueWithUnit( values.link_text_font_size );
					attr['style'] += 'font-size:' + $font_size + ';';
				}

				attr['href']   = values.link_url;
				attr['target'] = values.link_target;

				return attr;
			}
		} );
	} );
}( jQuery ) );
