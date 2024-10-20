/* global FusionPageBuilder, FusionPageBuilderElements */
/**
 * Generate css based on typography provided.
 *
 * @since 2.0
 * @access public
 * @param {string}  typography Typography from shortcode atts.
 * @param {boolean} important  Whether to add !important attribute or not.
 * @return {string} typography_css Generated css.
 */
var elegant_get_typography_css = function( typography, important = false ) {
	var cssImportant = ( important ) ? ' !important' : '',
		font_style = '',
		font_weight = '',
		weight,
		typography_css = '';

	if ( 'undefined' === typeof typography ) {
		return '';
	}

	typography = typography.split( ':' );

	if ( 'undefined' !== typeof typography[ 1 ] || ( Number.isInteger( typography[ 0 ] ) ) ) {
		weight = ( Number.isInteger( typography[ 0 ] ) ) ? typography[ 0 ] : typography[ 1 ];
		font_weight = ( 'regular' === weight.trim() ) ? 400 : weight;
		font_weight = ( ! Number.isInteger( font_weight ) ) ? weight.substr( 0, 3 ) : font_weight;

		font_style = ( ! Number.isInteger( weight ) ) ? weight.substr( 3 ) : '';
		font_style = ( 'regular' === weight ) ? '' : font_style;
	}

	if ( 'regular' !== typography[ 0 ] && ( ! Number.isInteger( parseInt( typography[ 0 ] ) ) ) ) {
		typography_css = 'font-family: ' + typography[ 0 ] + cssImportant + ';';
	}

	typography_css += 'font-weight: ' + font_weight + cssImportant + ';';

	if ( '' !== font_style ) {
		typography_css += 'font-style: ' + font_style + cssImportant + ';';
	}

	return typography_css;
};

/**
 * Generates and returns the gradient color.
 *
 * @access public
 * @since 2.0
 * @param {Object} values - The values.
 * @return {array} Array with gradient colors.
 */
var get_gradient_color = function( values ) {
	var gradient_color_1   = values.background_color_1,
		gradient_color_2   = values.background_color_2,
		gradient_direction = values.gradient_direction,
		gradient           = '',
		force_gradient     = ( 'undefined' !== typeof values.force_gradient && values.force_gradient ) ? '!important' : '';

	if ( 'vertical' == gradient_direction ) {
		gradient_direction = 'top';
		// Safari 4-5, Chrome 1-9 support.
		gradient = 'background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(' + gradient_color_1 + '), to(' + gradient_color_2 + '))' + force_gradient + ';';
	} else {
		// Safari 4-5, Chrome 1-9 support.
		gradient = 'background: -webkit-gradient(linear, left top, right top, from(' + gradient_color_1 + '), to(' + gradient_color_2 + '))' + force_gradient + ';';
	}

	// Safari 5.1, Chrome 10+ support.
	gradient += 'background: -webkit-linear-gradient(' + gradient_direction + ', ' + gradient_color_1 + ', ' + gradient_color_2 + ')' + force_gradient + ';';

	// Firefox 3.6+ support.
	gradient += 'background: -moz-linear-gradient(' + gradient_direction + ', ' + gradient_color_1 + ', ' + gradient_color_2 + ')' + force_gradient + ';';

	// IE 10+ support.
	gradient += 'background: -ms-linear-gradient(' + gradient_direction + ', ' + gradient_color_1 + ', ' + gradient_color_2 + ')' + force_gradient + ';';

	// Opera 11.10+ support.
	gradient += 'background: -o-linear-gradient(' + gradient_direction + ', ' + gradient_color_1 + ', ' + gradient_color_2 + ')' + force_gradient + ';';

	return gradient;
};

/**
 * Builds the gradient color with all the parameters.
 *
 * @access public
 * @since 2.5
 * @param {string} angle          Gradient angle.
 * @param {string} color_1        Gradient color 1.
 * @param {string} color_2        Gradient color 2.
 * @param {string} offset         Gradient offset.
 * @param {string} color_1_offset First gradient color offset.
 * @param {string} color_2_offset Second gradient color offset.
 * @return {string} Generated gradient color.
 */
var elegant_gradient_color = function( angle, color_1, color_2, offset, color_1_offset, color_2_offset ) {
	if ( '' === color_1 || '' === color_2 ) {
		return 'background-color:' + ( ( '' !== color_1 ) ? color_1 : color_2 );
	}

	// General.
	gradient = 'background: linear-gradient(' + angle + 'deg, ' + color_1 + ' ' + color_1_offset + '%, ' + offset + '%, ' + color_2 + ' ' + color_2_offset + '%);';

	// Safari 5.1, Chrome 10+ support.
	gradient += 'background: -webkit-linear-gradient(' + angle + 'deg, ' + color_1 + ' ' + color_1_offset + '%, ' + offset + '%, ' + color_2 + ' ' + color_2_offset + '%);';

	// Firefox 3.6+ support.
	gradient += 'background: -moz-linear-gradient(' + angle + 'deg, ' + color_1 + ' ' + color_1_offset + '%, ' + offset + '%, ' + color_2 + ' ' + color_2_offset + '%);';

	// IE 10+ support.
	gradient += 'background: -ms-linear-gradient(' + angle + 'deg, ' + color_1 + ' ' + color_1_offset + '%, ' + offset + '%, ' + color_2 + ' ' + color_2_offset + '%);';

	// Opera 11.10+ support.
	gradient += 'background: -o-linear-gradient(' + angle + 'deg, ' + color_1 + ' ' + color_1_offset + '%, ' + offset + '%, ' + color_2 + ' ' + color_2_offset + '%);';

	return gradient;
};

/**
 * Generates the background slider for container.
 *
 * @access public
 * @since 3.2.0
 * @param {Array}  params Container settings.
 * @param {string} cid    Container unique ID.
 * @return {void}
 */
var elegant_container_background_slider = function( params, cid ) {
	var enable_background_slider,
		imageScale,
		sliderTransition,
		imageIds,
		imageURL,
		sliderImages,
		containerEl,
		transitionEffects = [
			"fade",
			"fade_in_out",
			"push_left",
			"push_right",
			"push_up",
			"push_down",
			"cover_left",
			"cover_right",
			"cover_up",
			"cover_down"
		],
		imageObject,
		imageURL;

	if ( 'yes' === params.enable_background_slider ) {
		imageIds = params.image_ids;
		containerEl = jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( '.fusion-builder-row-live-' + cid );

		if ( '' !== imageIds ) {
			imageScale       = params.elegant_background_scale;
			sliderTransition = params.elegant_transition_effect;
			sliderTransition = ( "random" === sliderTransition ) ? transitionEffects : sliderTransition;
			imageIds         = imageIds.split( ',' );
			sliderImages     = []
			i = 0;

			jQuery.each( imageIds, function( index, imageID ) {
				if ( 3 === i ) {
					return false;
				}
				imageID = parseInt( imageID );
				var imageFetch = wp.media.model.Attachment.get( imageID ).fetch();
				imageFetch.done( function() {
					imageObject = wp.media.model.Attachment.get( imageID ),
					imageURL    = imageObject.attributes.url;

					if ( imageURL ) {
						sliderImages.push( imageURL );
					}
				} );
				i++;
			} );

			setTimeout( function() {
				containerEl.elegantbackgroundslider( sliderImages, { duration: 3000, transition:sliderTransition, transitionDuration: 750, scale:imageScale } );
			}, 1500 );
		}
	}
};

/**
 * Generates the background slider for column.
 *
 * @access public
 * @since 3.2.0
 * @param {Array}  params Column settings.
 * @param {string} cid    Column unique ID.
 * @return {void}
 */
var elegant_column_background_slider = function( params, cid ) {
	var enable_background_slider,
		imageScale,
		sliderTransition,
		imageIds,
		imageURL,
		sliderImages,
		columnEl,
		transitionEffects = [
			"fade",
			"fade_in_out",
			"push_left",
			"push_right",
			"push_up",
			"push_down",
			"cover_left",
			"cover_right",
			"cover_up",
			"cover_down"
		],
		imageObject,
		imageURL,
		timeInterval,
		timeInterval = 0;

	if ( 'yes' === params.enable_background_slider ) {
		imageIds = params.image_ids;
		columnEl = jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( '.fusion-builder-column-live-' + cid );

		if ( '' !== imageIds ) {
			imageScale       = params.elegant_background_scale;
			sliderTransition = params.elegant_transition_effect;
			sliderTransition = ( "random" === sliderTransition ) ? transitionEffects : sliderTransition;
			imageIds         = imageIds.split( ',' );
			sliderImages     = [],
			wpJsonRoot = wpApiSettings.root;

			jQuery.each( imageIds, function( index, imageID ) {
				imageID = parseInt( imageID );
				wp.media.model.Attachment.get( imageID ).fetch();
				imageObject = wp.media.model.Attachment.get( imageID ),
				imageURL    = imageObject.attributes.url;
				timeInterval += 100;

				if ( imageURL ) {
					sliderImages.push( imageURL );
				}
			} );

			setTimeout( function() {
				if ( sliderImages.length ) {
					columnEl.elegantbackgroundslider( sliderImages, { duration: 3000, transition:sliderTransition, transitionDuration: 750, scale:imageScale } );
				}
			}, timeInterval );
		}
	}
};

_.extend( FusionPageBuilder.Callback.prototype, {

	/**
	 * Set the column gradient background on color option change.
	 *
	 * @since 2.0
	 * @param {Object} attributes - Column attributes.
	 * @param {Object} view - Column Settings view.
	 * @return {void}
	 */
	updateColumnBackground: function( attributes, view ) {
		var $el = view.$targetEl,
			columnEl = $el[0].firstElementChild,
			cid = jQuery( columnEl ).parent().data( 'cid' ),
			gradientValues = {
				background_color_1: attributes.params.gradient_top_color,
				background_color_2: attributes.params.gradient_bottom_color,
				gradient_direction: ( ( 'vertical' == attributes.params.gradient_type ) ? 'top' : attributes.params.gradient_direction ),
				force_gradient: true
			},
			gradientColor = get_gradient_color( gradientValues ),
			styles = '',
			psudoStyle = 'content:"";position: absolute;width: 100%;height: 100%;top: 0;left: 0;';

		jQuery( columnEl ).parents('body').find( '#fusion-column-gradient-' + cid ).remove();

		// Set background slider if enabled.
		setTimeout( function() {
			elegant_column_background_slider( attributes.params, cid );
		}, 100 );

		styles  = '<style type="text/css" id="fusion-column-gradient-' + cid + '">'
		styles += '.fusion-column-wrapper-' + cid + '.fusion-column-wrapper:before {' + gradientColor + psudoStyle + '}';
		styles += '</style>';

		jQuery( columnEl ).parents('body').append( styles );

		return attributes;
	}
} );

_.extend( FusionPageBuilder.Callback.prototype, {

	/**
	 * Set the container gradient background on color option change.
	 *
	 * @since 2.0
	 * @param {Object} attributes - Container attributes.
	 * @param {Object} view - Container Settings view.
	 * @return {void}
	 */
	updateContainerBackground: function( attributes, view ) {
		var $el = view.$targetEl,
			containerEl = $el[0].firstElementChild,
			cid = jQuery( containerEl ).parent().data( 'cid' ),
			gradientValues = {
				background_color_1: attributes.params.gradient_top_color,
				background_color_2: attributes.params.gradient_bottom_color,
				gradient_direction: ( ( 'vertical' == attributes.params.gradient_type ) ? 'top' : attributes.params.gradient_direction ),
				force_gradient: true
			},
			gradientColor = get_gradient_color( gradientValues ),
			styles = '',
			psudoStyle = 'content:"";position: absolute;width: 100%;height: 100%;top: 0;left: 0;';

		jQuery( containerEl ).parents('body').find( '#fusion-container-gradient-' + cid ).remove();

		// Set background slider if enabled.
		setTimeout( function() {
			elegant_container_background_slider( attributes.params, cid );
		}, 100 );

		styles  = '<style type="text/css" id="fusion-container-gradient-' + cid + '">'
		styles += '#fusion-container-' + cid + '.fusion-builder-container .fusion-fullwidth:before {' + gradientColor + psudoStyle + '}';
		styles += '</style>';

		jQuery( containerEl ).parents('body').append( styles );

		return attributes;
	}
} );

( function() {

	jQuery( window ).on( 'load', function() {
		var gradientValues = {
				force_gradient: true
			},
			styles = '';

		FusionPageBuilderElements.find( function( model ) {
			var elementType = model.get( 'element_type' ),
				params = model.get( 'params' ),
				cid = model.get( 'cid' ),
				gradientColor = '',
				psudoStyle = 'content:"";position: absolute;width: 100%;height: 100%;top: 0;left: 0;';

			if ( 'fusion_builder_container' === elementType ) {
				gradientValues.background_color_1 = params.gradient_top_color,
				gradientValues.background_color_2 = params.gradient_bottom_color,
				gradientValues.gradient_direction = ( ( 'vertical' == params.gradient_type ) ? 'top' : params.gradient_direction );
				gradientColor = get_gradient_color( gradientValues );

				// Set background slider if enabled.
				elegant_container_background_slider( params, cid );

				styles += '<style type="text/css" id="fusion-container-gradient-' + cid + '">'
				styles += '#fusion-container-' + cid + '.fusion-builder-container .fusion-fullwidth:before{' + gradientColor + psudoStyle + '}';
				styles += '</style>';
			}

			if ( 'fusion_builder_column' === elementType ) {
				gradientValues.background_color_1 = params.gradient_top_color,
				gradientValues.background_color_2 = params.gradient_bottom_color,
				gradientValues.gradient_direction = ( ( 'vertical' == params.gradient_type ) ? 'top' : params.gradient_direction );
				gradientColor = get_gradient_color( gradientValues );

				// Set background slider if enabled.
				elegant_column_background_slider( params, cid );

				styles += '<style type="text/css" id="fusion-column-gradient-' + cid + '">'
				styles += '.fusion-column-wrapper-' + cid + '.fusion-column-wrapper:before{' + gradientColor + psudoStyle + '}';
				styles += '</style>';
			}

		} );

		jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( 'body' ).append( styles );

	} );

	function elegantGenerateBlob() {
		var percentage11,
			percentage21,
			percentage31,
			percentage41,
			borderRadius = '';

		const rndInt = Math.floor( Math.random() * 4 ) + 1;

		const percentage1 = _.random(10, 80);
		const percentage2 = _.random(15, 85);
		const percentage3 = _.random(20, 80);
		const percentage4 = _.random(15, 85);

		percentage11 = 100 - percentage1;
		percentage21 = 100 - percentage2;
		percentage31 = 100 - percentage3;
		percentage41 = 100 - percentage4;

		if ( 1 === rndInt ) {
			borderRadius = percentage1 + '% ' + percentage11 + '% ' + percentage21 + '% / ' + percentage3 + '% ' + percentage4 + '% ' + percentage41 + '% ' + percentage31 + '%';
		} else if ( 2 === rndInt ) {
			borderRadius = percentage1 + '% ' + percentage11 + '% ' + percentage21 + '% / ' + percentage3 + '% ' + percentage4 + '% ' + percentage41 + '%';
		} else if ( 3 === rndInt ) {
			borderRadius = percentage1 + '% ' + percentage21 + '% ' + percentage11 + '% ' + percentage2 + '% / ' + percentage3 + '% ' + percentage4 + '% ' + percentage31 + '%';
		} else {
			borderRadius = percentage1 + '% ' + percentage11 + '% ' + percentage21 + '% ' + percentage2 + '% / ' + percentage3 + '% ' + percentage4 + '% ' + percentage41 + '% ' + percentage31 + '%';
		}

		return borderRadius;
	}

	// Blob Shape Generator.
	jQuery( 'body' ).on( 'click', 'a.elegant-element-blob-shape-generator-button', function( event ) {
		var blobGeneratorInput = jQuery( this ).closest( '.elegant-element-blob-shape-generator-field' ).find( 'input' ),
			blobShapePreview = jQuery( this ).next( '.elegant-blob-shape-generator-placeholder-wrapper' ).find( '.elegant-blob-shape-generator-placeholder' ),
			borderRadius = elegantGenerateBlob();

		// Prevent the default browser action.
		event.preventDefault();

		// Update the shape in preview.
		blobShapePreview.css( 'border-radius', borderRadius );

		// Update input value and trigger the change.
		blobGeneratorInput.val( borderRadius ).trigger( 'change' );
	} );
}( jQuery ) );
