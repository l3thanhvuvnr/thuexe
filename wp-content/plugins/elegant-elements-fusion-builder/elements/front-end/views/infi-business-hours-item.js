/* global FusionPageBuilderElements, fusionAllElements */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Business Hours Child Element View.
		FusionPageBuilder.iee_business_hours_item = FusionPageBuilder.ChildElementView.extend( {

			/**
			 * Modify template attributes.
			 *
			 * @since 2.3
			 * @param {Object} atts - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			filterTemplateAtts: function( atts ) {
				var attributes = {},
					parent      = this.model.get( 'parent' ),
					parentModel = FusionPageBuilderElements.find( function( model ) {
						return model.get( 'cid' ) == parent;
					} );

				this.parentValues = jQuery.extend( true, {}, fusionAllElements.iee_business_hours.defaults, _.fusionCleanParameters( parentModel.get( 'params' ) ) );

				// Unique ID for this particular element instance, can be useful.
				attributes.cid = this.model.get( 'cid' );

				// Validate values.
				this.validateValues( atts.params );

				// Set parent model.
				attributes.parent = parent;

				// Create attribute objects
				attributes.attr          = this.buildAttr( atts.params );
				attributes.dayAttr       = this.buildDayAttr( atts.params );
				attributes.hoursAttr     = this.buildHoursAttr( atts.params );
				attributes.separatorAttr = this.buildSeparatorAttr( this.parentValues );

				// Any extras that need passed on.
				attributes.content    = atts.params.element_content;
				attributes.title      = atts.params.title;
				attributes.hours_text = atts.params.hours_text;

				return attributes;
			},

			/**
			 * Modifies values.
			 *
			 * @since 2.3
			 * @param {Object} values - The values.
			 * @return {void}
			 */
			validateValues: function( values ) {
				values.day_alignment   = this.parentValues.day_alignment;
				values.hours_alignment = this.parentValues.hours_alignment;
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.3
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttr: function( values ) {
				var attr = {
						class: 'elegant-business-hours-item business-hours-item-' + this.model.get( 'cid' ),
						style: ''
					};

				if ( 'undefined' !== typeof values.text_color && '' !== values.text_color ) {
					attr['style'] += 'color:' + values.text_color + ';';
				}

				return attr;
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.3
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildDayAttr: function( values ) {
				var attr = {
					class: 'elegant-business-hours-item-day',
					style: ''
				};

				attr['class'] += ' elegant-align-' + values.day_alignment;

				attr = _.fusionInlineEditor( {
					cid: this.model.get( 'cid' ),
					param: 'title',
					toolbar: false,
					'data-disable-return': true,
					'data-disable-extra-spaces': true,
					'disable-return': true,
					'disable-extra-spaces': true
				}, attr );

				return attr;
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.3
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildHoursAttr: function( values ) {
				var attr = {
					class: 'elegant-business-hours-item-hours',
					style: ''
				};

				attr['class'] += ' elegant-align-' + values.hours_alignment;

				attr = _.fusionInlineEditor( {
					cid: this.model.get( 'cid' ),
					param: 'hours_text',
					toolbar: false,
					'data-disable-return': true,
					'data-disable-extra-spaces': true,
					'disable-return': true,
					'disable-extra-spaces': true
				}, attr );

				return attr;
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.3
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildSeparatorAttr: function( values ) {
				var attr = {
					class: 'elegant-business-hours-sep elegant-content-sep',
					style: ''
				},
				separator = values.separator_type.split( ' ' );

				_.each( separator, function( sep ) {
					attr['class'] += ' sep-' + sep;
				} );

				if ( '' !== values.sep_color ) {
					attr['style'] += 'border-color: ' + values.sep_color + ';';
				}

				return attr;
			}
		} );
	} );
}( jQuery ) );
