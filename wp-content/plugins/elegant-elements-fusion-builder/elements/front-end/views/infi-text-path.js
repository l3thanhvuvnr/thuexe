/* global elegant_get_typography_css */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Text Path Element View.
		FusionPageBuilder.iee_text_path = FusionPageBuilder.ElementView.extend( {

			/**
			 * Modify template attributes.
			 *
			 * @since 3.5.0
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
				attributes.attr        = this.buildAttr( atts.params );
				attributes.svg         = this.getTextPathSvg( atts.params );

				// Any extras that need passed on.
				attributes.content = atts.params.element_content;
				attributes.typography_path_text = atts.params.typography_path_text;

				return attributes;
			},

			/**
			 * Modifies values.
			 *
			 * @since 3.5.0
			 * @param {Object} values - The values.
			 * @return {void}
			 */
			validateValues: function( values ) {
				values.width               = _.fusionGetValueWithUnit( values.width );
				values.title_font_size     = _.fusionGetValueWithUnit( values.title_font_size );
				values.text_letter_spacing = _.fusionGetValueWithUnit( values.text_letter_spacing );
				values.text_word_spacing   = _.fusionGetValueWithUnit( values.text_word_spacing );
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 3.5.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildWrapperAttr: function( values ) {
				var attr = _.fusionVisibilityAtts( values.hide_on_mobile, {
						class: 'elegant-text-path-wrapper',
						style: ''
					} );

				attr['class'] += ' elegant-align-' + values.alignment;

				return attr;
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 3.5.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttr: function( values ) {
				var attr = {
						class: 'elegant-text-path',
						style: ''
					};

				attr['class'] += ' shape-' + values.shape;

				attr['style'] += 'width:' + values.width + ';';
				attr['style'] += '--width:' + values.width + ';';

				if ( '' !== values.typography_path_text ) {
					attr['style'] += elegant_get_typography_css( values.typography_path_text );
				}

				if ( '' !== values.title_font_size ) {
					attr['style'] += 'font-size:' + values.title_font_size + ';';
				}

				if ( '' !== values.text_letter_spacing ) {
					attr['style'] += 'letter-spacing:' + values.text_letter_spacing + ';';
				}

				if ( '' !== values.text_word_spacing ) {
					attr['style'] += '--word-spacing:' + values.text_word_spacing + ';';
				}

				attr['style'] += 'color:' + values.text_color + ';';
				attr['style'] += '--text-color:' + values.text_color + ';';

				if ( values.class ) {
					attr['class'] += ' ' + values.class;
				}

				if ( values.id ) {
					attr['id'] = values.id;
				}

				return attr;
			},

			/**
			 * Return the SVG with text path.
			 *
			 * @since 3.5.0
			 * @access public
			 * @param {array} args Element attributes.
			 * @return {string} SVG with text and path.
			 */
			getTextPathSvg( args ) {
				var $shape = args.shape,
					$text  = args.text,
					$id    = this.model.get( 'cid' ),
					$text_node,
					$svg   = '';

				if ( '' !== args.link ) {
					$text = '<a href="' + args.link + '">' + $text + '</a>';
				}

				$text_node = '<text><textPath id="ee-text-path-' + $id + '" href="#ee-path-' + $id + '" startOffset="0%">' + $text + '</textPath></text>';

				switch ( $shape ) {
					case 'wave':
						$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="250" height="42.4994" viewBox="0 0 250 42.4994"><path id="ee-path-' + $id + '" d="M0,42.2494C62.5,42.2494,62.5.25,125,.25s62.5,41.9994,125,41.9994"/><path d="M-41.6693,49.25"/><path d="M-208.3307-6.75"/>' + $text_node + '</svg>';
						break;
					case 'arc-top':
						$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="250.5" height="125.25" viewBox="0 0 250.5 125.25"><path id="ee-path-' + $id + '" d="M.25,125.25a125,125,0,0,1,250,0"/>' + $text_node + '</svg>';
						break;
					case 'arc-bottom':
						$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="250.5" height="125.25" viewBox="0 0 250.5 125.25"><path id="ee-path-' + $id + '" d="M 0 0 C 0 180 250 180 250 0"/>' + $text_node + '</svg>';
						break;
					case 'circle':
						$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="250.5" height="250.5" viewBox="0 0 250.5 250.5"><path id="ee-path-' + $id + '" d="M.25,125.25a125,125,0,1,1,125,125,125,125,0,0,1-125-125"/>' + $text_node + '</svg>';
						break;
					case 'line-top':
						$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="250" height="22" viewBox="0 0 250 22"><path id="ee-path-' + $id + '" d="M 0 27 l 250 -22"/>' + $text_node + '</svg>';
						break;
					case 'line-bottom':
						$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="250" height="22" viewBox="0 0 250 22"><path id="ee-path-' + $id + '" d="M 0 27 l 250 22"/>' + $text_node + '</svg>';
						break;
					case 'oval':
						$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="250.5" height="125.75" viewBox="0 0 250.5 125.75"><path id="ee-path-' + $id + '" class="b473dc75-7459-43a5-8a1c-89caf910da53" d="M.25,62.875C.25,28.2882,56.2144.25,125.25.25s125,28.0382,125,62.625-55.9644,62.625-125,62.625S.25,97.4619.25,62.875"/>' + $text_node + '</svg>';
						break;
					case 'spiral':
						$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="250.4348" height="239.4454" viewBox="0 0 250.4348 239.4454"><path id="ee-path-' + $id + '" d="M.1848,49.0219a149.3489,149.3489,0,0,1,210.9824-9.8266,119.479,119.479,0,0,1,7.8613,168.786A95.5831,95.5831,0,0,1,84,214.27a76.4666,76.4666,0,0,1-5.0312-108.023"/>' + $text_node + '</svg>';
						break;
				}

				return $svg;
			}
		} );
	} );
}( jQuery ) );
