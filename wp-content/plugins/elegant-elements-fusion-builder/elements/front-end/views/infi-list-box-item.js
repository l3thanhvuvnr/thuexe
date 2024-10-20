/* global FusionPageBuilderElements, fusionAllElements */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// List Box Child Element View.
		FusionPageBuilder.iee_list_box_item = FusionPageBuilder.ChildElementView.extend( {

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

				this.parentValues = jQuery.extend( true, {}, fusionAllElements.iee_list_box.defaults, _.fusionCleanParameters( parentModel.get( 'params' ) ) );
				this.parentExtras = parentModel.get( 'extras' );

				// Unique ID for this particular element instance, can be useful.
				attributes.cid = this.model.get( 'cid' );

				// Set parent model.
				attributes.parent = parent;

				// Create attribute objects
				attributes.spanAttr        = this.buildSpanAttr( atts.params );
				attributes.iconAttr        = this.buildIconAttr( atts.params );
				attributes.itemContentAttr = this.buildItemContentAttr();

				// Any extras that need passed on.
				attributes.content = atts.params.element_content;

				return attributes;
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildSpanAttr: function( values ) {
				var attr = {
						class: '',
						style: ''
					},
					circle_class = '',
					circlecolor = '';

				if ( 'yes' === values.circle || 'yes' === this.parentValues.circle ) {
					circle_class = ' circle-yes';

					if ( ! values.circlecolor ) {
						circlecolor = this.parentValues.circlecolor;
					} else {
						circlecolor = values.circlecolor;
					}
					attr['style'] += 'background-color:' + circlecolor + ';';
					attr['style'] += 'font-size:' + this.parentExtras.circle_yes_font_size + 'px;';
				}

				attr['class'] += 'icon-wrapper' + circle_class;

				attr['style'] += 'height:' + this.parentExtras.line_height + 'px;';
				attr['style'] += 'width:' + this.parentExtras.line_height + 'px;';
				attr['style'] += 'margin-' + this.parentExtras.icon_margin_position + ':' + this.parentExtras.icon_margin + 'px;';

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
			buildIconAttr: function( values ) {
				var attr = {
					class: 'elegant-list-item-icon',
					style: ''
				},
				icon,
				iconcolor;

				if ( ! values.icon ) {
					icon = _.fusionFontAwesome( this.parentValues.icon );
				} else {
					icon = _.fusionFontAwesome( values.icon );
				}

				if ( ! values.iconcolor ) {
					iconcolor = this.parentValues.iconcolor;
				} else {
					iconcolor = values.iconcolor;
				}

				attr['class'] += ' fusion-li-icon ' + icon;

				attr['style'] += 'color:' + iconcolor + ';';

				return attr;
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.0
			 * @return {array} Attributes array for wrapper.
			 */
			buildItemContentAttr: function() {
				var attr = {
					class: 'elegant-list-item-content fusion-li-item-content',
					style: 'margin-' + this.parentExtras.content_margin_position + ':' + this.parentExtras.content_margin + 'px;'
				};

				attr = _.fusionInlineEditor( {
					cid: this.model.get( 'cid' ),
					'data-disable-return': true,
					'data-disable-extra-spaces': true
				}, attr );

				return attr;
			}
		} );
	} );
}( jQuery ) );
