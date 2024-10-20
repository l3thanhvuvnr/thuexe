var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Particles Banner Element View.
		FusionPageBuilder.iee_particles_banner = FusionPageBuilder.ElementView.extend( {

			/**
			 * Modify template attributes.
			 *
			 * @since 3.3.4
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
				attributes.attr = this.buildAttr( atts.params );

				// Any extras that need passed on.
				attributes.content = atts.params.element_content;

				return attributes;
			},

			/**
			 * Modifies values.
			 *
			 * @since 3.3.4
			 * @param {Object} values - The values.
			 * @return {void}
			 */
			validateValues: function( values ) {
				values.height = _.fusionGetValueWithUnit( values.height );
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 3.3.4
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttr: function( values ) {
				var attr = _.fusionVisibilityAtts( values.hide_on_mobile, {
						class: 'elegant-particles-banner',
						style: ''
					} );

				attr['style']  = 'height:' + values.height + ';';

				attr['style'] += 'background-image: url( ' + values.background_image + ' );';
				attr['style'] += 'background-position: center center;';
				attr['style'] += 'background-color:' + values.background_color + ';';
				attr['style'] += 'background-size: cover;';

				if ( 'yes' === values.background_parallax ) {
					attr['style'] += 'background-attachment: fixed;';
					attr['style'] += 'background-repeat: no-repeat;';
				}

				attr['data-shape']               = values.shape;
				attr['data-nb_sides']            = values.nb_sides;
				attr['data-shape_color']         = values.shape_color;
				attr['data-number_of_particles'] = values.number_of_particles;
				attr['data-density']             = values.density;
				attr['data-density_value_area']  = values.density_value_area;
				attr['data-large_particle_size'] = values.large_particle_size;
				attr['data-animate_particles']   = values.animate_particles;
				attr['data-shape_stroke_size']   = values.shape_stroke_size;
				attr['data-shape_stroke_color']  = values.shape_stroke_color;
				attr['data-line_color']          = values.line_color;

				if ( values.class ) {
					attr['class'] += ' ' + values.class;
				}

				if ( values.id ) {
					attr['id'] = values.id;
				}

				return attr;
			}
		} );
	} );
}( jQuery ) );
