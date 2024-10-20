/* global elegant_get_typography_css */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Video List Element View.
		FusionPageBuilder.iee_video_list = FusionPageBuilder.ParentElementView.extend( {

			/**
			 * Modify template attributes.
			 *
			 * @since 2.3
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
				attributes.customStyle = this.buildStyles( atts.params );

				// Any extras that need passed on.
				attributes.content = atts.params.element_content;

				return attributes;
			},

			/**
			 * Modifies values.
			 *
			 * @since 2.3
			 * @param {Object} values - The values.
			 * @return {void}
			 */
			validateValues: function( values ) {
				values.border_size  = _.fusionGetValueWithUnit( values.border_size );
				values.border_radius_top_left     = values.border_radius_top_left ? _.fusionGetValueWithUnit( values.border_radius_top_left ) : '0px';
				values.border_radius_top_right    = values.border_radius_top_right ? _.fusionGetValueWithUnit( values.border_radius_top_right ) : '0px';
				values.border_radius_bottom_right = values.border_radius_bottom_right ? _.fusionGetValueWithUnit( values.border_radius_bottom_right ) : '0px';
				values.border_radius_bottom_left  = values.border_radius_bottom_left ? _.fusionGetValueWithUnit( values.border_radius_bottom_left ) : '0px';
				values.border_radius              = values.border_radius_top_left + ' ' + values.border_radius_top_right + ' ' + values.border_radius_bottom_right + ' ' + values.border_radius_bottom_left;
				values.border_radius              = ( '0px 0px 0px 0px' === values.border_radius ) ? '0px' : values.border_radius;
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.3
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttr: function( values ) {
				var attr = _.fusionVisibilityAtts( values.hide_on_mobile, {
						class: 'elegant-video-list elegant-video-list-container elegant-video-list-' + this.model.get( 'cid' )
					} );

				if ( '' !== values.video_position ) {
					attr['class'] += ' video-position-' + values.video_position;
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
			 * Builds the styles.
			 *
			 * @access public
			 * @since 2.3
			 * @param {Object} values - The values.
			 * @return {String} Custom styles according to the attributes.
			 */
			buildStyles: function( values ) {
				var styles = '',
					cid = this.model.get( 'cid' ),
					borderPosition = values.border_position,
					main_class = '.elegant-video-list.elegant-video-list-' + this.model.get( 'cid' ) + ' .elegant-video-list-items .elegant-video-list-item',
					typography = '',
					title_typography = '';

				// Styles for the list item.
				styles += main_class + ' {';

				if ( values.border_size ) {
					borderPosition = ( 'all' !== borderPosition ) ? '-' + borderPosition : '';
					styles += 'border' + borderPosition + ': ' + values.border_size + ' ' + values.border_style + ' ' + values.border_color + ';';
				}

				if ( '' !== values.border_radius ) {
					styles += 'border-radius:' + values.border_radius + ';';
				}

				if ( values.list_item_background_color ) {
					styles += 'background: ' + values.list_item_background_color + ';';
				}

				styles += '}';

				// Styles for the list item on hover and active.
				styles += main_class + '.active-item,';
				styles += main_class + ':hover{';
				if ( values.active_list_item_background_color ) {
					styles += 'background: ' + values.active_list_item_background_color + ';';
				}
				styles += '}';

				// Styles for the icon.
				styles += main_class + ' .elegant-video-list-item-title .video-list-icon {';
				styles += 'font-size:' + _.fusionGetValueWithUnit( values.icon_font_size ) + ';';

				if ( '' !== values.icon_color ) {
					styles += 'color:' + values.icon_color + ';';
				}

				styles += '}';

				// Styles for the title.
				styles += main_class + ' .elegant-video-list-item-title {';
				styles += 'font-size:' + _.fusionGetValueWithUnit( values.title_font_size ) + ';';

				if ( '' !== values.title_color ) {
					styles += 'color:' + values.title_color + ';';
				}

				if ( '' !== values.typography_title ) {
					typography       = values.typography_title;
					title_typography = elegant_get_typography_css( typography );

					styles += title_typography;
				}

				styles += '}';

				// Styles for the active item title.
				if ( '' !== values.active_title_color ) {
					styles += main_class + '.active-item .elegant-video-list-item-title,';
					styles += main_class + ':hover .elegant-video-list-item-title {';
					styles += 'color:' + values.active_title_color + ';';
					styles += '}';
				}

				// Styles for the active item icon.
				if ( '' !== values.active_icon_color ) {
					styles += main_class + '.active-item .elegant-video-list-item-title .video-list-icon,';
					styles += main_class + ':hover .elegant-video-list-item-title .video-list-icon {';
					styles += 'color:' + values.active_icon_color + ';';
					styles += '}';
				}

				jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( 'body' ).trigger( 'fusion-element-render-iee_video_list', cid );

				return styles;
			}
		} );
	} );
}( jQuery ) );
