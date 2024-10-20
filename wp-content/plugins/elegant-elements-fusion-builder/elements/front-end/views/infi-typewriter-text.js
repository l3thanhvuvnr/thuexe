/* global elegant_get_typography_css */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Typewriter Text Element View.
		FusionPageBuilder.iee_typewriter_text = FusionPageBuilder.ParentElementView.extend( {

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
				attributes.attr          = this.buildAttr( atts.params );
				attributes.titleAttr     = this.buildTitleAttr( atts.params );

				// Any extras that need passed on.
				attributes.params            = atts.params;
				attributes.prefix            = atts.params.prefix;
				attributes.suffix            = atts.params.suffix;
				attributes.loop              = atts.params.loop;
				attributes.deleteDelay       = ( 'undefined' !== typeof atts.params.delete_delay ) ? atts.params.delete_delay : 1000;
				attributes.typography_parent = ( 'undefined' !== typeof atts.params.typography_parent ) ? atts.params.typography_parent : '';
				attributes.typography_child  = ( 'undefined' !== typeof atts.params.typography_child ) ? atts.params.typography_child : '';

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
				values.font_size   = _.fusionGetValueWithUnit( values.font_size );
				values.loop        = ( 'undefined' !== typeof values.loop && 'yes' === values.loop ) ? true : false;
				values.alignment   = ( 'undefined' !== typeof values.alignment ) ? values.alignment : 'left';
				values.deleteDelay = ( 'undefined' !== typeof values.delete_delay ) ? values.delete_delay : 1000;
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
					class: 'elegant-typewriter-text-container',
					style: ''
				} );

				attr['class'] += ' elegant-typewriter-text-container-' + this.model.get( 'cid' );
				attr['class'] += ' elegant-align-' + values.alignment;

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
			buildTitleAttr: function( values ) {
				var attr = {
						class: 'elegant-typewriter-text',
						style: ''
					},
					delay;

				delay = ( values.delay ) ? values.delay : 2;

				attr['class'] += ' elegant-typewriter-text-' + this.model.get( 'cid' );

				if ( 'undefined' !== typeof values.typography_parent && '' !== values.typography_parent ) {
					attr['style'] += elegant_get_typography_css( values.typography_parent );
				}

				attr['style'] += 'font-size:' + values.font_size + ';';
				attr['style'] += 'color:' + values.title_color + ';';

				attr['data-counter'] = 'elegant-typewriter-' + this.model.get( 'cid' );

				attr['data-delay'] = parseInt( delay ) * 1000;
				attr['data-loop']  = values.loop;
				attr['data-deletedelay'] = values.deleteDelay;

				return attr;
			}
		} );
	} );
}( jQuery ) );
