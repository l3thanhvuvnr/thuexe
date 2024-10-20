/* global elegant_get_typography_css */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Content Toggle Element View.
		FusionPageBuilder.iee_content_toggle = FusionPageBuilder.ElementView.extend( {

			/**
			 * Modify template attributes.
			 *
			 * @since 2.1.0
			 * @param {Object} atts - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			filterTemplateAtts: function( atts ) {
				var attributes = {};

				// Unique ID for this particular element instance, can be useful.
				attributes.cid = this.model.get( 'cid' );

				// Create attribute objects
				attributes.attr   = this.buildAttr( atts.params );
				attributes.styles = this.buildStyles( atts.params );

				// Any extras that need passed on.
				attributes.content                = atts.params.element_content;
				attributes.title_first            = atts.params.title_first;
				attributes.title_last             = atts.params.title_last;
				attributes.template_content_first = atts.params.content_first;
				attributes.template_content_last  = atts.params.content_last;
				attributes.typography_title       = atts.params.typography_title;

				return attributes;
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.1.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttr: function( values ) {
				var attr = _.fusionVisibilityAtts( values.hide_on_mobile, {
						class: 'elegant-content-toggle',
						style: ''
					} );

				attr['class'] += ' elegant-content-toggle-' + this.model.get( 'cid' );
				attr['class'] += ' fusion-clearfix';

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
			 * @since 2.1.0
			 * @param {Object} values - The values.
			 * @return {string} Styles.
			 */
			buildStyles: function( values ) {
				var main_class = '.elegant-content-toggle.elegant-content-toggle-' + this.model.get( 'cid' ),
					style = '';

				if ( values.switch_bg_inactive ) {
					style += main_class + ' .content-toggle-switch-button .content-toggle-switch-label {';
					style += 'background:' + values.switch_bg_inactive + ';';
					style += '}';
				}

				if ( values.switch_bg_active ) {
					style += main_class + ' .switch-active .content-toggle-switch-label {';
					style += 'background:' + values.switch_bg_active + ';';
					style += '}';
				}

				style += main_class + ' .content-toggle-switch-first,';
				style += main_class + ' .content-toggle-switch-last {';

				if ( values.typography_title ) {
					style += elegant_get_typography_css( values.typography_title );
				}

				if ( values.title_color ) {
					style += 'color:' + values.title_color + ';';
				}

				if ( values.title_font_size ) {
					style += 'font-size:' + _.fusionGetValueWithUnit( values.title_font_size, 'px' ) + ';';
				}

				style += '}';

				return style;
			}
		} );
	} );
}( jQuery ) );
