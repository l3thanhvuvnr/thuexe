var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Lottie Image Element View.
		FusionPageBuilder.iee_lottie_animated_image = FusionPageBuilder.ElementView.extend( {

			/**
			 * Runs after view DOM is patched.
			 *
			 * @since 2.5
			 * @return {void}
			 */
			afterPatch: function() {
				var player = this.$el.find( '.lottie-player-' + this.model.get( 'cid' ) ),
					src = player.attr( 'src' );

				player[0].load( src );
			},

			/**
			 * Modify template attributes.
			 *
			 * @since 2.5
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
				attributes.attr       = this.buildAttr( atts.params );
				attributes.playerAttr = this.buildPlayerAttr( atts.params );

				return attributes;
			},

			/**
			 * Modifies values.
			 *
			 * @since 2.5
			 * @param {Object} values - The values.
			 * @return {void}
			 */
			validateValues: function( values ) {
				values.height = _.fusionGetValueWithUnit( values.height );
				values.width  = _.fusionGetValueWithUnit( values.width );
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.5
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttr: function( values ) {
				var attr = _.fusionVisibilityAtts( values.hide_on_mobile, {
						class: 'elegant-lottie-image',
						style: ''
					} );

				attr['style'] += 'height:' + values.height + ';';
				attr['style'] += 'width:' + values.width + ';';

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
			 * @since 2.5
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildPlayerAttr: function( values ) {
				var attr = {
						class: 'elegant-lottie-player lottie-player-' + this.model.get( 'cid' ),
						player: 1,
						style: ''
					};

				attr['src']        = values.json_url;
				attr['background'] = values.background_color;

				attr[ values.animation_play ] = true;

				if ( 'bounce' === values.animation_mode ) {
					attr['mode'] = 'bounce';
				}

				if ( 'yes' === values.animation_loop ) {
					attr['loop'] = true;
				}

				attr['style'] += 'height:' + values.height + ';';
				attr['style'] += 'width:' + values.width + ';';

				return attr;
			}
		} );
	} );
}( jQuery ) );
