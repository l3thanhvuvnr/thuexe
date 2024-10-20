/* global FusionPageBuilderElements, fusionAllElements, elegant_get_typography_css */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Image Filters Child Element View.
		FusionPageBuilder.iee_filter_image = FusionPageBuilder.ChildElementView.extend( {

			/**
			 * Runs during render() call.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			onRender: function() {
				var self = this,
					parentView = window.FusionPageBuilderViewManager.getView( this.model.get( 'parent' ) );

				if ( 'undefined' !== typeof this.model.attributes.selectors ) {
					this.model.attributes.selectors[ 'class' ] += ' ' + this.className;
					this.setElementAttributes( this.$el, this.model.attributes.selectors );
				}

				// Re-render the parent view if the child was cloned
				if ( 'undefined' !== typeof self.model.attributes.cloned && true === self.model.attributes.cloned ) {
					delete self.model.attributes.cloned;
					parentView.reRender();
					parentView.fusionIsotope.reloadItems();
				}

				// Update isotope layout
				setTimeout( function() {
					parentView.fusionIsotope.append( self.$el );
				}, 50 );
			},

			/**
			 * Runs on child element init.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			onInit: function() {
				var self = this,
					parentView = window.FusionPageBuilderViewManager.getView( this.model.get( 'parent' ) ),
					params = this.model.get( 'params' ),
					navItem = ( 'undefined' !== typeof params.navigation ) ? params.navigation : '',
					navigationItem = '';

				parentView.onRenderChild();

				navItem = navItem.split( ',' );

				_.each( navItem, function( navTitle ) {
					navTitle = navTitle.trim();
					navigationItem = navTitle.replace( ' ', '' ).replace( '-', '' ).replace( '_', '' ).toLowerCase();
					self.model.attributes.selectors[ 'class' ] += ' ' + navigationItem.trim();
				} );

				this.setElementAttributes( this.$el, this.model.attributes.selectors );
			},

			/**
			 * Runs before element is removed.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			beforeRemove: function() {
				var self = this,
					cid = this.model.get( 'cid' ),
					parentView = window.FusionPageBuilderViewManager.getView( this.model.get( 'parent' ) ),
					navItem = parentView.$el.find( '.elegant-image-filter-navigation-item-' + cid );

				parentView.fusionIsotope.remove( self.$el );

				setTimeout( function() {
					parentView.fusionIsotope.reloadItems();
				}, 100 );

				parentView.onRenderChild();
				navItem.remove();
			},

			/**
			 * Runs after view DOM is patched.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			beforePatch: function() {
				var parentView = window.FusionPageBuilderViewManager.getView( this.model.get( 'parent' ) );
				parentView._refreshJs();
			},

			/**
			 * Runs after view DOM is patched.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			afterPatch: function() {
				var self = this,
					parentView = window.FusionPageBuilderViewManager.getView( this.model.get( 'parent' ) ),
					parentCid = this.model.get( 'parent' ),
					link  = jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( this.$el.find( '.fusion-lightbox' ) );

				if ( 'undefined' !== typeof this.model.attributes.selectors ) {
					this.model.attributes.selectors[ 'class' ] += ' ' + this.className;
					this.setElementAttributes( this.$el, this.model.attributes.selectors );
				}

				// Force re-render for child option changes.
				setTimeout( function() {
					jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( 'body' ).trigger( 'fusion-element-render-iee_image_filters', parentCid );
					parentView.fusionIsotope.updateLayout();

					if ( 'object' === typeof jQuery( '#fb-preview' )[ 0 ].contentWindow.avadaLightBox ) {
						if ( 'undefined' !== typeof this.iLightbox ) {
							self.iLightbox.destroy();
						}

						if ( link.length ) {
							self.iLightbox = link.iLightBox( jQuery( '#fb-preview' )[ 0 ].contentWindow.avadaLightBox.prepare_options( 'single' ) );
						}
					}
				}, 100 );

				// Using non debounced version for smoothness.
				this.refreshJs();

				parentView._refreshJs();
			},

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

				this.parentValues      = jQuery.extend( true, {}, fusionAllElements.iee_filter_image.defaults, _.fusionCleanParameters( parentModel.get( 'params' ) ) );
				attributes.parentModel = parentModel;

				// Validate values.
				this.validateValues( atts.params );

				// Unique ID for this particular element instance, can be useful.
				attributes.cid = this.model.get( 'cid' );

				// Set parent model.
				attributes.parent = parent;

				// Create attribute objects
				attributes.attr             = this.buildAttr();
				attributes.filterAttr       = this.buildFilterAttr( atts.params );
				attributes.titleAttr        = this.buildTitleAttr( atts.params );
				attributes.titleOverlayAttr = this.buildTitleOverlayAttr( atts.params );

				// Any extras that need passed on.
				attributes.content              = atts.params.element_content;
				attributes.title                = atts.params.title;
				attributes.image_url            = atts.params.image_url;
				attributes.navigationTitle      = ( 'undefined' !== typeof atts.params.navigation ) ? atts.params.navigation : 'Category';
				attributes.click_action         = atts.params.click_action;
				attributes.modal_anchor         = atts.params.modal_anchor;
				attributes.url                  = atts.params.url;
				attributes.target               = atts.params.target;
				attributes.lightbox_image_url   = atts.params.lightbox_image_url;
				attributes.lightbox_image_meta  = ( 'undefined' !== typeof atts.params.lightbox_image_meta ) ? atts.params.lightbox_image_meta : '';
				attributes.query_data           = atts.query_data;

				attributes.image_title_position = this.parentValues.image_title_position;


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
				var imageUrl = ( 'undefined' !== typeof values.image_url ) ? values.image_url : '';

				values.grid_item_padding     = _.fusionGetValueWithUnit( this.parentValues.grid_item_padding );
				values.image_title_font_size = _.fusionGetValueWithUnit( values.image_title_font_size );
				this.parentValues.image_title_font_size = _.fusionGetValueWithUnit( this.parentValues.image_title_font_size );

				values.lightbox_image_url = ( 'undefined' !== typeof values.lightbox_image_url ) ? values.lightbox_image_url : imageUrl;
				values.lightbox_image_url = ( 'undefined' !== typeof values.lightbox_image_url ) ? values.lightbox_image_url.replace( '×', 'x' ).replace( '&#215;', 'x' ) : '';
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.0
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttr: function() {
				var attr = {
						class: 'elegant-image-filter-container',
						style: ''
					};

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
			buildFilterAttr: function( values ) {
				var attr = {
						class: 'elegant-image-filter-content-item',
						style: 'padding:' + values.grid_item_padding + ';'
					};

				if ( values.orientation ) {
					attr['class'] += ' elegant-image-' + values.orientation;
				}

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
						class: 'elegant-image-filter-title',
						style: ''
					},
					boxed_background_color;

				if ( 'undefined' !== typeof this.parentValues.typography_image_title ) {
					attr['style'] += elegant_get_typography_css( this.parentValues.typography_image_title );
				}

				if ( '' !== this.parentValues.image_title_font_size ) {
					attr['style'] += 'font-size:' + this.parentValues.image_title_font_size + ';';
				}

				if ( '' !== values.image_title_color && 'undefined' !== typeof values.image_title_color ) {
					attr['style'] += 'color:' + values.image_title_color + ';';
				} else if ( '' !== this.parentValues.image_title_color ) {
					attr['style'] += 'color:' + this.parentValues.image_title_color + ';';
				}

				if ( 'on_image_hover' !== this.parentValues.image_title_position ) {
					if ( 'unboxed' !== this.parentValues.image_title_layout ) {
						boxed_background_color = ( '' !== values.boxed_background_color && 'undefined' !== typeof values.boxed_background_color ) ? values.boxed_background_color : this.parentValues.boxed_background_color;
						attr['class']         += ' image-filter-title-layout-boxed';
						attr['style']         += 'background-color:' + boxed_background_color + ';';
					}
				} else {
					attr['class'] += ' image-filter-title-layout-overlay';
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
			buildTitleOverlayAttr: function( values ) {
				var attr = {
						class: 'image-filter-title-overlay',
						style: ''
					},
					overlay_background_color;

				if ( '' !== values.grid_item_padding ) {
					attr['style'] += 'top:' + values.grid_item_padding + ';';
					attr['style'] += 'left:' + values.grid_item_padding + ';';
					attr['style'] += 'width: calc( 100% - calc( ' + values.grid_item_padding + ' * 2 ) );';
					attr['style'] += 'height: calc( 100% - calc( ' + values.grid_item_padding + ' * 2 ) );';
				}

				overlay_background_color = ( '' !== values.overlay_background_color ) ? values.overlay_background_color : this.parentValues.overlay_background_color;

				attr['style'] += 'background-color:' + overlay_background_color + ';';

				return attr;
			}
		} );
	} );
}( jQuery ) );
