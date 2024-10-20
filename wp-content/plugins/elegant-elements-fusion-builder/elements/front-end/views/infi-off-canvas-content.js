var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Off-canvas Content Element View.
		FusionPageBuilder.iee_off_canvas_content = FusionPageBuilder.ElementView.extend( {

			afterPatch: function() {
				this.reRenderContent();
			},
			onRender: function() {
				this.reRenderContent();
			},
			reRenderContent: function() {
				var that = this,
					contentTemplate = '',
					body = jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( 'body' ),
					params = this.model.get( 'params' ),
					canvas_id = ( 'undefined' !== params.canvas_id && '' !== params.canvas_id ) ? params.canvas_id : 'off-canvas-id-' + this.model.get( 'cid' ),
					oldCanvasContent = body.find( '#' + canvas_id );

				this.$el.parents( 'body' ).find( '#' + canvas_id ).remove();
				oldCanvasContent.remove();
				setTimeout( function() {
					contentTemplate = that.getContentHTML( that.model.get( 'params' ) );
					jQuery( contentTemplate ).appendTo( body );
				}, 1000 );
			},

			/**
			 * Modify template attributes.
			 *
			 * @since 3.2.0
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
				attributes.attr            = this.buildAttr( atts.params );
				attributes.attrTriggerIcon = this.buildAttrTriggerIcon( atts.params );

				// Any extras that need passed on.
				attributes.content = atts.params.element_content;
				attributes.args    = atts.params;

				return attributes;
			},

			/**
			 * Modifies values.
			 *
			 * @since 3.2.0
			 * @param {Object} values - The values.
			 * @return {void}
			 */
			validateValues: function( values ) {
				var paddingValues;

				paddingValues  = 'padding-top:' + ( ( 'undefined' !== typeof values.content_padding_top ) ? _.fusionGetValueWithUnit( values.content_padding_top ) + ';' : '0px;' );
				paddingValues += 'padding-right:' + ( ( 'undefined' !== typeof values.content_padding_right ) ? _.fusionGetValueWithUnit( values.content_padding_right ) + ';' : '0px;' );
				paddingValues += 'padding-bottom:' + ( ( 'undefined' !== typeof values.content_padding_bottom ) ? _.fusionGetValueWithUnit( values.content_padding_bottom ) + ';' : '0px;' );
				paddingValues += 'padding-left:' + ( ( 'undefined' !== typeof values.content_padding_left ) ? _.fusionGetValueWithUnit( values.content_padding_left ) + ';' : '0px;' );

				values.content_padding = paddingValues;

				values.height = _.fusionGetValueWithUnit( values.height );
				values.width  = _.fusionGetValueWithUnit( values.width );
				values.canvas_id = ( 'undefined' !== values.canvas_id && '' !== values.canvas_id ) ? values.canvas_id : 'off-canvas-id-' + this.model.get( 'cid' );
				values.trigger_icon_size = _.fusionGetValueWithUnit( values.trigger_icon_size );
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 3.2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttr: function( values ) {
				var attr = _.fusionVisibilityAtts( values.hide_on_mobile, {
						class: 'elegant-off-canvas-content',
						style: ''
					} );

				attr['class'] += ' off-canvas-position-' + values.position;
				attr['class'] += ' elegant-align-' + values.trigger_alignment;
				attr['class'] += ' off-canvas-animation-' + values.content_animation;

				attr['data-position']  = values.position;
				attr['data-animation'] = values.content_animation;
				attr['data-canvas-id'] = values.canvas_id;

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
			 * @since 3.2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttrContentWrapper: function( values ) {
				var attr = {
					class: 'elegant-off-canvas-content-wrapper',
					style: ''
				};

				attr['class'] += ' off-canvas-content-' + values.position;
				attr['class'] += ' off-canvas-content-animation-' + values.content_animation;
				attr['class'] += ' off-canvas-id-' + this.model.get( 'cid' );
				attr['id']     = values.canvas_id;

				if ( 'top' == values.position || 'bottom' == values.position ) {
					attr['style'] = 'height:' + values.height + ';';
				} else {
					attr['style'] = 'width:' + values.width + ';';
				}

				if ( '' !== values.canvas_background_color ) {
					attr['style'] += 'background:' + values.canvas_background_color + ';';
				}

				if ( '' !== values.canvas_text_color ) {
					attr['style'] += 'color:' + values.canvas_text_color + ';';
					attr['style'] += 'fill:' + values.canvas_text_color + ';';
				}

				return attr;
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 3.2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttrContentBody: function( values ) {
				var attr = {
						class: 'elegant-off-canvas-content-body',
						style: ''
					};

				if ( '' !== values.content_padding ) {
					attr['style'] += values.content_padding;
				}

				return attr;
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 3.2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttrTriggerIcon: function( values ) {
				var attr = {
						class: 'elegant-off-canvas-content-trigger-icon',
						style: ''
					};

				attr['class'] += ' ' + values.trigger_icon;

				if ( '' !== values.trigger_icon_size ) {
					attr['style'] += 'font-size:' + values.trigger_icon_size + ';';
				}

				if ( '' !== values.trigger_icon_color ) {
					attr['style'] += 'color:' + values.trigger_icon_color + ';';
				}

				return attr;
			},

			/**
			 * Builds the canvas content.
			 *
			 * @access public
			 * @since 3.2.0
			 * @param {Object} atts - The values.
			 * @return {string} Canvas content HTML.
			 */
			getContentHTML: function( atts ) {
				var offCanvasContentTemplate = FusionPageBuilder.template( jQuery( '#tmpl-iee_off_canvas_content-template' ).html() ),
					attributes        = {};

				if ( 'object' !== typeof atts ) {
					return '';
				}

				// Validate values.
				this.validateValues( atts );

				// Unique ID for this particular off-canvas instance.
				attributes.cid                = this.model.get( 'cid' );
				attributes.attrContentWrapper = this.buildAttrContentWrapper( atts );
				attributes.attrContentBody    = this.buildAttrContentBody( atts );
				attributes.content            = atts.element_content;
				attributes.args               = atts;

				return offCanvasContentTemplate( attributes );
			}
		} );
	} );
}( jQuery ) );
