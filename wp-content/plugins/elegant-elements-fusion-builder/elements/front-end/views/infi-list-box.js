var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// List Box Element View.
		FusionPageBuilder.iee_list_box = FusionPageBuilder.ParentElementView.extend( {

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

				// Add computed values that child uses.
				this.buildExtraVars( atts.params );

				// Unique ID for this particular element instance, can be useful.
				attributes.cid = this.model.get( 'cid' );

				// Create attribute objects
				attributes.titleAttr       = this.buildTitleAttr( atts.params );
				attributes.titleSpanAttr   = this.buildTitleSpanAttr( atts.params );
				attributes.itemsAttr       = this.buildItemsAttr( atts.params );
				attributes.listNodeAttr    = this.buildListNodeAttr( atts.params );

				// Any extras that need passed on.
				attributes.params = atts.params;
				attributes.title  = atts.params.title;

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
				values.border_size     = _.fusionGetValueWithUnit( values.border_size );
				values.title_font_size = _.fusionGetValueWithUnit( values.title_font_size );

				if ( ! values.title_font_size ) {
					if ( 'small' === values.size ) {
						values.title_font_size = '13px';
					} else if ( 'medium' === values.size ) {
						values.title_font_size = '18px';
					} else if ( 'large' === values.size ) {
						values.title_font_size = '40px';
					}
				}
			},

			/**
			 * Sets extra args in the model.
			 *
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {void}
			 */
			buildExtraVars: function( values ) {
				var extras = {},
					font_size = values.size.replace( 'px', '' ),
					line_height = font_size * 1.7,
					icon_margin = font_size * 0.7;

				extras.circle_yes_font_size    = font_size * 0.88;
				extras.line_height             = line_height;
				extras.icon_margin             = icon_margin;
				extras.icon_margin_position    = ( jQuery( 'body' ).hasClass( 'rtl' ) ) ? 'left' : 'right';
				extras.content_margin          = line_height + icon_margin;
				extras.content_margin_position = ( jQuery( 'body' ).hasClass( 'rtl' ) ) ? 'right' : 'left';

				this.model.set( 'extras', extras );
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
						class: 'elegant-list-box-title',
						style: ''
					},
					font_size = values.title_font_size;

				attr['class'] += ' elegant-align-' + values.title_align;

				attr['style'] += 'color:' + values.title_color + ';';
				attr['style'] += 'font-size:' + font_size + ';line-height:calc( ' + font_size + ' * 1.2 );margin-bottom:calc( -' + font_size + ' * 1.7 );';

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
			buildTitleSpanAttr: function( values ) {
				var attr = {
						class: 'elegant-list-box-border-' + values.border_radius,
						style: ''
					};

				attr['style'] += 'border-color:' + values.border_color + ';';
				attr['style'] += 'border-style:' + values.border_style + ';';
				attr['style'] += 'border-width:' + values.border_size + ';';

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
			buildItemsAttr: function( values ) {
				var attr = {
					class: 'elegant-list-box-items',
					style: ''
				};

				attr['style'] += 'border-color:' + values.border_color + ';';
				attr['style'] += 'border-style:' + values.border_style + ';';
				attr['style'] += 'border-width:' + values.border_size + ';';
				attr['style'] += 'padding-top: calc( ' + values.size + ' * 2 );';

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
			buildListNodeAttr: function( values ) {
				var attr = {
					class: 'elegant-list-box fusion-checklist elegant-list-box-' + this.model.get( 'cid' ),
					style: ''
				};

				attr['class'] += ' elegant-align-' + values.item_align;
				attr['class'] += ' fusion-child-element';

				attr['style'] += 'font-size:' + values.size + ';line-height: calc( ' + values.size + ' * 1.7 );';

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
