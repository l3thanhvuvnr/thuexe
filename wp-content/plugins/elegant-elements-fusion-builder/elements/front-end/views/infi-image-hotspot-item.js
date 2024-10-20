/* global FusionPageBuilderApp, FusionPageBuilderElements, fusionAllElements */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Image Hotspot Child Element View.
		FusionPageBuilder.iee_image_hotspot_item = FusionPageBuilder.ChildElementView.extend( {

			/**
			 * Modify template attributes.
			 *
			 * @since 2.0
			 * @param {Object} atts - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			filterTemplateAtts: function( atts ) {
				var attributes = {},
					parent      = this.model.get( 'parent' ),
					parentModel = FusionPageBuilderElements.find( function( model ) {
						return model.get( 'cid' ) == parent;
					} );

				this.parentValues = jQuery.extend( true, {}, fusionAllElements.iee_image_hotspot.defaults, _.fusionCleanParameters( parentModel.get( 'params' ) ) );

				// Validate values.
				this.validateValues( atts.params );

				// Unique ID for this particular element instance, can be useful.
				attributes.cid = this.model.get( 'cid' );
				attributes.pcid = parentModel.get( 'cid' );

				// Set parent model.
				attributes.parent = parent;

				// Create attribute objects
				attributes.attr = this.buildAttr( atts.params );

				// Any extras that need passed on.
				attributes.content          = atts.params.element_content;
				attributes.pointer_type     = atts.params.pointer_type;
				attributes.tooltip_position = atts.params.tooltip_position;
				attributes.title            = ( 'undefined' !== typeof atts.values.title ) ? atts.values.title : atts.params.title;
				attributes.link_url         = ( 'undefined' !== typeof atts.values.link_url ) ? atts.values.link_url : atts.params.link_url;
				attributes.pointer_icon     = atts.params.pointer_icon;
				attributes.position_top     = atts.params.hotspot_position_top;
				attributes.position_left    = atts.params.hotspot_position_left;
				attributes.pointer_custom_text = atts.params.pointer_custom_text;
				attributes.custom_pointer_title = ( 'undefined' !== typeof atts.params.custom_pointer_title ) ? atts.params.custom_pointer_title : '';
				attributes.pointer_title_position = ( 'undefined' !== typeof atts.params.pointer_title_position ) ? atts.params.pointer_title_position : 'right';
				attributes.disable_tooltip  = ( 'undefined' !== typeof atts.params.disable_tooltip ) ? atts.params.disable_tooltip : 'no';

				// Assign parent values.
				attributes.pointer_effect = this.parentValues.pointer_effect;
				attributes.pointer_shape  = ( 'undefined' !== typeof this.parentValues.pointer_shape ) ? this.parentValues.pointer_shape : 'circle';
				attributes.hotspot_size   = atts.params.hotspot_size;

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
				values.hotspot_size = _.fusionGetValueWithUnit( this.parentValues.hotspot_size );
				values.link_url     = ( 'undefined' !== typeof values.link_url ) ? values.link_url : '';
				values.tooltip_text_color = ( 'undefined' !== typeof values.tooltip_text_color ) ? values.tooltip_text_color : '#fff';
				values.tooltip_background_color = ( 'undefined' !== typeof values.tooltip_background_color ) ? values.tooltip_background_color : '#333';


				if ( 'undefined' !== typeof values.pointer_title_spacing ) {
					values.pointer_title_spacing = _.fusionGetValueWithUnit( values.pointer_title_spacing );
				} else {
					values.pointer_title_spacing = '0px';
				}

				try {
					if ( FusionPageBuilderApp.base64Encode( FusionPageBuilderApp.base64Decode( values.pointer_custom_text ) ) === values.pointer_custom_text ) {
						values.pointer_custom_text = FusionPageBuilderApp.base64Decode( values.pointer_custom_text );
					}

					if ( 'undefined' !== typeof values.custom_pointer_title ) {
						if ( FusionPageBuilderApp.base64Encode( FusionPageBuilderApp.base64Decode( values.custom_pointer_title ) ) === values.custom_pointer_title ) {
							values.custom_pointer_title = FusionPageBuilderApp.base64Decode( values.custom_pointer_title );
						}
					}
				} catch ( error ) {
					// Print error here.
				}
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
				var attr = {
						class: 'elegant-image-hotspot-item elegant-image-hotspot-item-' + this.model.get( 'cid' ),
						style: ''
					};

				if ( 'text' === values.pointer_type ) {
					attr['class'] += ' custom-text-pointer';
				}

				attr['style'] += 'font-size:' + values.hotspot_size + ';';
				attr['style'] += 'top:' + values.hotspot_position_top + '%;';
				attr['style'] += 'left:' + values.hotspot_position_left + '%;';
				attr['style'] += 'color:' + this.parentValues.hotspot_text_color + ';';
				attr['style'] += '--background-color:' + this.parentValues.hotspot_background_color + ';';
				attr['style'] += '--hover-background-color:' + this.parentValues.hotspot_background_color_hover + ';';
				attr['style'] += '--tooltip-text-color:' + values.tooltip_text_color + ';';
				attr['style'] += '--tooltip-background-color:' + values.tooltip_background_color + ';';
				attr['style'] += '--hotspot-title-spacing:' + values.pointer_title_spacing + ';';
				attr['style'] += 'position: static;';

				return attr;
			}
		} );
	} );
}( jQuery ) );
