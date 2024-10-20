/* global elegant_get_typography_css, imagesLoaded */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Image Filters Element View.
		FusionPageBuilder.iee_image_filters = FusionPageBuilder.ParentElementView.extend( {
			events: {
				'click .fusion-builder-remove': 'removeElement',
				'click .fusion-builder-clone': 'cloneElement',
				'click .fusion-builder-settings': 'settings',
				'click .fusion-builder-add-child': 'addChildElement',
				'click .fusion-builder-element-save': 'openLibrary',
				'click a': 'disableLink',
				'click .fusion-builder-element-drag': 'preventDefault',
				'click .elegant-image-filters-navigation-item a': 'filterImages'
			},

			/**
			 * Filters images when navigation item is clicked.
			 *
			 * @since 2.0
			 * @param {Object} event - The event.
			 * @return {void}
			 */
			filterImages: function( event ) {

				// Relayout isotope based on filter selection.
				var $filterActive = jQuery( event.target ).data( 'filter' );

				event.preventDefault();

				jQuery( this.$el ).find( '.elegant-image-filters-wrapper' ).find( '.elegant-image-filters-content' ).isotope( { filter: $filterActive } );

				// Remove active filter class from old filter item and add it to new.
				jQuery( this.$el ).find( '.elegant-image-filters-navigation' ).find( '.elegant-image-filters-navigation-item' ).removeClass( 'filter-active' );
				jQuery( event.target ).parent().addClass( 'filter-active' );
			},

			/**
			 * Runs on parent element init.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			onInit: function() {
				this.fusionIsotope = new FusionPageBuilder.IsotopeManager( {
					selector: '.elegant-image-filters-content',
					layoutMode: 'packery',
					itemSelector: '.elegant-image-filter-item',
					isOriginLeft: jQuery( 'body.rtl' ).length ? false : true,
					resizable: true,
					initLayout: true,
					filter: '*',
					view: this
				} );
			},

			/**
			 * Runs during render() call.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			onRender: function() {
				var $this = this,
					galleryElements = this.$el.find( '.elegant-image-filter-item' );

				jQuery( window ).on( 'load', function() {
					$this._refreshJs();
				} );

				imagesLoaded( galleryElements, function() {
					$this.fusionIsotope.updateLayout();
				} );
			},

			/**
			 * Extendable function for when child elements get generated.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			onGenerateChildElements: function() {
				this.fusionIsotope.init();
			},

			/**
			 * Runs during onRender() call in child.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			onRenderChild: function() {
				var that = this,
					cid = this.model.get( 'cid' );

				setTimeout( function() {
					var children = window.FusionPageBuilderViewManager.getChildViews( cid ),
						navItems = {},
						filterNav = '',
						params = that.model.get( 'params' ),
						filter_separator = '',
						all_filter_text = '',
						activeFilterClass = '',
						index = 1,
						keys;

					if ( 'undefined' !== typeof params.filter_separator && 'horizontal' == params.navigation_layout ) {
						filter_separator = ( '' !== params.filter_separator ) ? '<span class="image-filter-navigation-separator">' + params.filter_separator + '</span>' : '';
					}

					// Add "All" filter.
					if ( 'yes' === params.use_all_filter ) {
						all_filter_text   = ( '' !== params.all_filter_text ) ? params.all_filter_text : 'All';
						activeFilterClass = that.$el.find( '.elegant-image-filter-navigation-item-' + cid + '.nav-filter-all' ).hasClass( 'filter-active' ) ? ' filter-active' : '';

						filterNav += '<li class="elegant-image-filters-navigation-item elegant-image-filter-navigation-item-' + cid + activeFilterClass + ' nav-filter-all">';
						filterNav += '<a href="#" data-filter="*">' + all_filter_text + '</a>';
						filterNav += '</li>';
						filterNav += filter_separator;
					}

					_.each( children, function( child ) {
						var childValues = child.model.get( 'params' );
						child.$el[0].className = 'elegant-image-filter-item fusion-builder-live-child-element fusion-builder-data-cid fusion-builder-live-child-element fusion-builder-data-cid';

						if ( childValues.orientation ) {
							child.$el[0].className += ' elegant-image-' + childValues.orientation;
						}

						_.each( childValues, function( value, type ) {
							var navigation = ( 'navigation' === type ) ? value : '',
								title = value;

							if ( 'undefined' !== typeof navigation && '' !== navigation ) {
								navigation = navigation.split( ',' );

								_.each( navigation, function( navItem ) {
									var navigationItem = '';

									navItem = navItem.trim();
									navigationItem = navItem.replace( ' ', '' ).replace( '-', '' ).replace( '_', '' ).toLowerCase();
									title = navItem;

									child.$el[0].className += ' ' + navigationItem;

									navItems[ navigationItem ] = title;
								} );
							}
						} );
					} );

					keys = Object.keys( navItems );

					_.each( navItems, function( navItem, title ) {
						activeFilterClass = that.$el.find( '.elegant-image-filter-navigation-item-' + cid + '.nav-filter-' + title ).hasClass( 'filter-active' ) ? ' filter-active' : '';
						filterNav += '<li class="elegant-image-filters-navigation-item elegant-image-filter-navigation-item-' + cid + activeFilterClass + ' nav-filter-' + title + '"><a href="#" data-filter=".' + title + '">' + navItem + '</a></li>';

						if ( index < keys.length ) {
							filterNav += filter_separator;
						}

						index++;
					} );

					that.$el.find( '.elegant-image-filters-navigation' ).html( filterNav );

					if ( ! that.$el.find( '.elegant-image-filter-navigation-item-' + cid + '.filter-active' ).length ) {
						jQuery( that.$el.find( '.elegant-image-filter-navigation-item-' + cid + ':first-child' ) ).find( 'a' ).trigger( 'click' );
					}

					setTimeout( function() {
						that.fusionIsotope.reInit();
						that.refreshJs();
					}, 100 );
				}, 100 );
			},

			/**
			 * Runs after view DOM is patched.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			afterPatch: function() {

				// TODO: save DOM and apply instead of generating
				this.generateChildElements();

				this.fusionIsotope.reInit();

				this._refreshJs();
			},

			/**
			 * Triggers custom event for filters element on settings change.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			refreshJs: function() {
				jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( 'body' ).trigger( 'fusion-element-render-iee_image_filters', this.model.attributes.cid );
			},

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

				// Unique ID for this particular element instance, can be useful.
				attributes.cid = this.model.get( 'cid' );

				// Create attribute objects
				attributes.wrapperAttr = this.buildWrapperAttr( atts.params );
				attributes.navAttr     = this.buildNavAttr( atts.params );
				attributes.contentAttr = this.buildContentAttr( atts.params );
				attributes.styles      = this.buildStyles( atts.params );

				// Any extras that need passed on.
				attributes.params                             = atts.params;
				attributes.title                              = atts.params.title,
				attributes.use_all_filter                     = atts.params.use_all_filter;
				attributes.multiple_upload                    = atts.params.multiple_upload;
				attributes.columns                            = atts.params.columns;
				attributes.grid_item_padding                  = atts.params.grid_item_padding;
				attributes.element_typography                 = atts.params.element_typography;
				attributes.typography_navigation_title        = atts.params.typography_navigation_title;
				attributes.navigation_title_font_size         = atts.params.navigation_title_font_size;
				attributes.navigation_title_color             = atts.params.navigation_title_color;
				attributes.typography_image_title             = atts.params.typography_image_title;
				attributes.image_title_font_size              = atts.params.image_title_font_size;
				attributes.image_title_color                  = atts.params.image_title_color;
				attributes.image_title_position               = atts.params.image_title_position;
				attributes.image_title_layout                 = atts.params.image_title_layout;
				attributes.lightbox_image_meta                = ( 'undefined' !== typeof atts.params.lightbox_image_meta ) ? atts.params.lightbox_image_meta : '';
				attributes.boxed_background_color             = atts.params.boxed_background_color;
				attributes.overlay_background_color           = atts.params.overlay_background_color;
				attributes.navigation_layout                  = atts.params.navigation_layout;
				attributes.navigation_alignment               = atts.params.navigation_alignment;
				attributes.navigation_position                = atts.params.navigation_position;
				attributes.active_navigation_border_type      = atts.params.active_navigation_border_type;
				attributes.use_all_filter                     = atts.params.use_all_filter;
				attributes.all_filter_text                    = atts.params.all_filter_text;
				attributes.filter_separator                   = atts.params.filter_separator;
				attributes.navigation_active_color            = atts.params.navigation_active_color;
				attributes.navigation_active_background_color = atts.params.navigation_active_background_color;

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
				values.navigation_title_font_size = _.fusionGetValueWithUnit( values.navigation_title_font_size );
				values.grid_item_padding          = _.fusionGetValueWithUnit( values.grid_item_padding );
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildWrapperAttr: function( values ) {
				var attr = _.fusionVisibilityAtts( values.hide_on_mobile, {
						class: 'elegant-image-filters-wrapper elegant-image-filters-' + this.model.get( 'cid' ),
						style: ''
					} );

				if ( 'horizontal' === values.navigation_layout ) {
					attr['class'] += ' image-filter-navigation-layout-horizontal image-filter-navigation-align-' + values.navigation_alignment;
				} else {
					attr['class'] += ' image-filter-navigation-layout-vertical image-filter-navigation-position-' + values.navigation_position;
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
			buildNavAttr: function( values ) {
				var attr = {
						class: 'elegant-image-filters-navigation elegant-image-filters-navigation-' + this.model.get( 'cid' ),
						role: 'menu',
						'aria-label': 'filters',
						style: ''
					};

				attr['class'] += ' fusion-child-contents-' + this.model.get( 'cid' );

				if ( '' !== values.active_navigation_border_type ) {
					attr['class'] += ' image-filters-active-navigation-' + values.active_navigation_border_type;
				}

				if ( 'undefined' !== typeof values.typography_navigation_title ) {
					attr['style'] += elegant_get_typography_css( values.typography_navigation_title );
				}

				if ( '' !== values.navigation_title_font_size ) {
					attr['style'] += 'font-size:' + values.navigation_title_font_size + ';';
				}

				if ( '' !== values.navigation_title_color ) {
					attr['style'] += 'color:' + values.navigation_title_color + ';';
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
			buildContentAttr: function( values ) {
				var attr = {
						class: 'elegant-image-filters-content fusion-child-element elegant-image-filters-content-' + this.model.get( 'cid' ),
						style: 'opacity:1;'
					},
					columns;

				columns = ( '' !== values.columns ) ? values.columns : '3';

				attr['class'] += ' elegant-image-filter-grid-' + columns;

				return attr;
			},

			/**
			 * Builds the styles.
			 *
			 * @access public
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {string} Styles.
			 */
			buildStyles: function( values ) {
				var style  = '<style type="text/css">';

				style += '.elegant-image-filters-wrapper.elegant-image-filters-' + this.model.get( 'cid' ) + ' .elegant-image-filters-navigation-item.filter-active {';
				style += 'color:' + values.navigation_active_color + ';';
				style += 'background-color:' + values.navigation_active_background_color + ';';
				style += '}';
				style += '</style>';

				return style;
			}
		} );
	} );

	_.extend( FusionPageBuilder.Callback.prototype, {
		// Image filter navigation filter.
		elegantImageFilterShortcodeFilter: function( attributes, view ) {

			var parentView = window.FusionPageBuilderViewManager.getView( view.model.get( 'parent' ) );

			parentView.onRenderChild();

			return attributes;

		}
	} );
}( jQuery ) );
