var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Partner Logo Element View.
		FusionPageBuilder.iee_partner_logos = FusionPageBuilder.ParentElementView.extend( {

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
				attributes.customStyle = this.buildStyles( atts.params );

				// Any extras that need passed on.
				attributes.content = atts.params.element_content;

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
				values.border  = _.fusionGetValueWithUnit( values.border );
				values.padding = _.fusionGetValueWithUnit( values.padding );
				values.margin  = _.fusionGetValueWithUnit( values.margin );
				values.width   = _.fusionGetValueWithUnit( values.width );
				values.height  = _.fusionGetValueWithUnit( values.height );
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
						class: 'elegant-partner-logos'
					} );

				attr['class'] += ' fusion-child-element';

				if ( '' !== values.logo_alignment ) {
					attr['class'] += ' elegant-partner-logo-align-' + values.logo_alignment;
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
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {String} Custom styles according to the attributes.
			 */
			buildStyles: function( values ) {
				var styles = '',
					main_class = '.elegant-partner-logos-container.elegant-partner-logo-container-' + this.model.get( 'cid' );

				styles += main_class + ' .elegant-partner-logo {';

				if ( values.border ) {
					styles += 'border-width: ' + values.border + ';';
					styles += 'border-color: ' + values.border_color + ';';
					styles += 'border-style: ' + values.border_style + ';';
				}

				if ( '' !== values.padding ) {
					styles += 'padding:' + values.padding + ';';
				}

				if ( '' !== values.margin ) {
					styles += 'margin:' + values.margin + ';';
				}

				if ( '' !== values.width ) {
					styles += 'max-width: ' + values.width + ';';
				}

				if ( '' !== values.height ) {
					styles += 'max-height: ' + values.height + ';';
				}

				return styles;
			}
		} );
	} );
}( jQuery ) );
