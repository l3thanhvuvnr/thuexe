/* global FusionPageBuilderApp, elegant_get_typography_css */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Modal view.
		FusionPageBuilder.iee_modal_dialog = FusionPageBuilder.ElementView.extend( {

			/**
			 * Runs during initialize() call.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			onInit: function() {
				var $modal = jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( this.$el );

				$modal.on( 'shown.bs.modal', function() {
					jQuery( 'body' ).addClass( 'fusion-builder-no-ui fusion-dialog-ui-active' );
					$modal.closest( '.fusion-builder-column' ).css( 'z-index', 'auto' ); // Because of animated items getting z-index 2000.
					$modal.closest( '#main' ).css( 'z-index', 'auto' );
					$modal.closest( '.fusion-row' ).css( 'z-index', 'auto' );
					$modal.closest( '.fusion-builder-container' ).css( 'z-index', 'auto' );
				} );

				$modal.on( 'hide.bs.modal', function() {
					jQuery( 'body' ).removeClass( 'fusion-builder-no-ui fusion-dialog-ui-active' );
					$modal.closest( '.fusion-builder-column' ).css( 'z-index', '' );
					$modal.closest( '#main' ).css( 'z-index', '' );
					$modal.closest( '.fusion-row' ).css( 'z-index', '' );
					$modal.closest( '.fusion-builder-container' ).css( 'z-index', '' );
				} );
			},

			/**
			 * Open actual modal.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			onSettingsOpen: function() {
				var self   = this,
					$modal = jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( this.$el.find( '.elegant-modal' ) );

				this.disableDroppableElement();
				jQuery( this.$el ).closest( '.fusion-builder-live-element' ).css( 'cursor', 'default' );
				jQuery( this.$el ).closest( '.fusion-builder-column' ).css( 'z-index', 'auto' ); // Because of animated items getting z-index 2000.
				jQuery( this.$el ).closest( '.fusion-row' ).css( 'z-index', 'auto' );
				jQuery( this.$el ).closest( '.fusion-builder-container' ).css( 'z-index', 'auto' );

				setTimeout( function() {
					if ( jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( '.fusion-footer-parallax' ).length && 'fixed' === jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( '.fusion-footer-parallax' ).css( 'position' ) ) {
						jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( '#main' ).css( 'z-index', 'auto' );
						jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( '.fusion-footer-parallax' ).css( 'z-index', '-1' );

						if ( jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( '#sliders-container' ).find( '.tfs-slider[data-parallax="1"]' ).length ) {
							jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( '#sliders-container' ).css( 'z-index', 'auto' );
						}
					}
				}, 1 );

				$modal.addClass( 'in' ).show();
				$modal.find( '.elegant-modal-dialog' ).removeClass( 'animated' );

				$modal.find( 'button[data-dismiss="modal"], .fusion-button[data-dismiss="modal"], .modal-footer-button[data-dismiss="modal"]' ).one( 'click', function() {
					window.FusionEvents.trigger( 'fusion-close-settings-' + self.model.get( 'cid' ) );
				} );
			},

			/**
			 * Close the modal.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			onSettingsClose: function() {
				var $modal = jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( this.$el.find( '.elegant-modal' ) );

				$modal.find( 'button[data-dismiss="modal"], .fusion-button[data-dismiss="modal"]' ).off( 'click' );

				this.enableDroppableElement();
				jQuery( this.$el ).closest( '.fusion-builder-live-element' ).css( 'cursor', '' );
				jQuery( this.$el ).closest( '.fusion-builder-column' ).css( 'z-index', '' );
				jQuery( this.$el ).closest( '.fusion-row' ).css( 'z-index', '' );
				jQuery( this.$el ).closest( '.fusion-builder-container' ).css( 'z-index', '' );

				if ( jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( '.fusion-footer-parallax' ).length ) {
					jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( '#main' ).css( 'z-index', '' );
					jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( '.fusion-footer-parallax' ).css( 'z-index', '' );
					jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( '#sliders-container' ).css( 'z-index', '' );
				}

				$modal.removeClass( 'in' ).hide();
			},

			/**
			 * Runs after view DOM is patched.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			afterPatch: function() {
				var $modal = jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( this.$el.find( '.elegant-modal' ) );

				if ( jQuery( '.fusion-builder-module-settings[data-element-cid="' + this.model.get( 'cid' ) + '"]' ).length ) {
					$modal.addClass( 'in' ).show();
					$modal.find( '.full-video, .video-shortcode, .wooslider .slide-content' ).fitVids();
				}
			},

			/**
			 * Modify template attributes.
			 *
			 * @since 2.0
			 * @param {Object} atts - The attributes.
			 * @return {Object} attributes The attributes.
			 */
			filterTemplateAtts: function( atts ) {
				var attributes = {};

				// Validate values.
				this.validateValues( atts.params );

				// Create attribute objects
				attributes.attrModal                = this.buildModalAttr( atts.params );
				attributes.attrDialog               = this.buildDialogAttr( atts.params );
				attributes.attrHeader               = this.buildHeaderAttr( atts.params );
				attributes.attrContent              = this.buildContentAttr( atts.params );
				attributes.attrButton               = this.buildButtonAttr( atts.params );
				attributes.attrHeading              = this.buildHeadingAttr( atts.params );
				attributes.attrFooter               = this.buildFooterAttr( atts.params );
				attributes.attrFooterButton         = this.buildHFooterButtonAttr( atts.params );
				attributes.closeButton              = this.buildFooterButton( atts.params );
				attributes.attrBody                 = this.buildBodyAttr( atts.params );
				attributes.triggerAttr              = this.buildTriggerAttr( atts.params );
				attributes.triggerContent           = this.buildTriggerContent( atts.params );
				attributes.borderColor              = atts.params.border_color;
				attributes.title                    = atts.params.title;
				attributes.showFooter               = atts.params.show_footer;
				attributes.closeText                = atts.params.button_title;
				attributes.elementContent           = atts.params.element_content;
				attributes.name                     = atts.params.name;
				attributes.typography_title         = atts.params.typography_title;
				attributes.typography_content       = atts.params.typography_content;
				attributes.typography_footer_button = atts.params.typography_footer_button;
				attributes.label                    = window.fusionAllElements[ this.model.get( 'element_type' ) ].name;
				attributes.icon                     = window.fusionAllElements[ this.model.get( 'element_type' ) ].icon;

				// Any extras that need passed on.
				attributes.cid = this.model.get( 'cid' );

				return attributes;
			},

			/**
			 * Modifies values.
			 *
			 * @since 2.0
			 * @param {Object} values - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			validateValues: function( values ) {
				values.modal_width       = _.fusionGetValueWithUnit( values.modal_width );
				values.content_font_size = _.fusionGetValueWithUnit( values.content_font_size );
				values.title_font_size   = _.fusionGetValueWithUnit( values.title_font_size );

				try {
					if ( FusionPageBuilderApp.base64Encode( FusionPageBuilderApp.base64Decode( values.button_shortcode ) ) === values.button_shortcode ) {
						values.button_shortcode = FusionPageBuilderApp.base64Decode( values.button_shortcode );
					}
				} catch ( error ) {
					// Print error here.
				}

				try {
					if ( FusionPageBuilderApp.base64Encode( FusionPageBuilderApp.base64Decode( values.icon_shortcode ) ) === values.icon_shortcode ) {
						values.icon_shortcode = FusionPageBuilderApp.base64Decode( values.icon_shortcode );
					}
				} catch ( error ) {
					// Print error here.
				}
			},

			/**
			 * Builds attributes.
			 *
			 * @since 2.0
			 * @param {Object} values - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			buildModalAttr: function( values ) {
				var attrModal = {
					class: 'elegant-modal fusion-modal modal fade modal-' + this.model.get( 'cid' ),
					tabindex: '-1',
					role: 'dialog',
					style: 'z-index: 9999999; background: rgba(0,0,0,0.5);',
					'aria-labelledby': 'modal-heading-' + this.model.get( 'cid' ),
					'aria-hidden': 'true',
					'data-animation-start': ( '' !== values.entry_animation ) ? 'infi-' + values.entry_animation : 'infi-fadeIn',
					'data-animation-exit': ( '' !== values.exit_animation ) ? 'infi-' + values.exit_animation : 'infi-fadeOut'
				};

				if ( '' !== values.name ) {
					attrModal[ 'class' ] += ' ' + values.name;
				}

				if ( '' !== values[ 'class' ] ) {
					attrModal[ 'class' ] += ' ' + values[ 'class' ];
				}

				if ( '' !== values.id ) {
					attrModal.id = values.id;
				}

				return attrModal;
			},

			/**
			 * Builds attributes.
			 *
			 * @since 2.0
			 * @param {Object} values - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			buildDialogAttr: function( values ) {
				var attrDialog = {
					class: 'elegant-modal-dialog modal-dialog',
					style: ''
				},
				modal_size;

				modal_size = ( 'small' === values.size ) ? ' modal-sm' : ' modal-lg';
				attrDialog['data-size'] = modal_size;
				attrDialog[ 'class' ]  += modal_size;

				if ( '' !== values.modal_width && 'custom' === values.size ) {
					attrDialog['style'] += 'width:' + values.modal_width + ';';
					attrDialog['style'] += 'max-width:' + values.modal_width + ';';
				}

				return attrDialog;
			},

			/**
			 * Builds attributes.
			 *
			 * @since 2.0
			 * @param {Object} values - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			buildHeaderAttr: function( values ) {
				var attrHeader = {
					class: 'elegant-modal-header modal-header',
					'aria-hidden': 'true',
					style: ''
				};

				if ( '' !== values.header_background ) {
					attrHeader.style = 'background-color:' + values.header_background;
				}

				return attrHeader;
			},

			/**
			 * Builds attributes.
			 *
			 * @since 2.0
			 * @param {Object} values - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			buildContentAttr: function( values ) {
				var attrContent = {
					class: 'elegant-modal-content modal-content fusion-modal-content'
				};
				if ( '' !== values.body_background ) {
					attrContent.style = 'background-color:' + values.body_background;
				}

				return attrContent;
			},

			/**
			 * Builds attributes.
			 *
			 * @since 2.0
			 * @param {Object} values - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			buildBodyAttr: function( values ) {
				var attrBody = {
						class: 'elegant-modal-body modal-body',
						'aria-hidden': 'true',
						style: ''
					},
					typography,
					content_typography;

				if ( '' !== values.typography_content ) {
					typography         = values.typography_content;
					content_typography = elegant_get_typography_css( typography );

					attrBody['style'] += content_typography;
				}

				if ( '' !== values.content_font_size ) {
					attrBody['style'] += 'font-size:' + values.content_font_size + ';';
				}

				attrBody = _.fusionInlineEditor( {
					cid: this.model.get( 'cid' )
				}, attrBody );

				return attrBody;
			},

			/**
			 * Builds attributes.
			 *
			 * @since 2.0
			 * @param {Object} values - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			buildButtonAttr: function( values ) {
				var attrButton = {
					class: 'close',
					type: 'button',
					'data-dismiss': 'modal',
					'aria-hidden': 'true',
					style: ''
				};

				if ( '' !== values.title_color ) {
					attrButton['style'] += 'color:' + values.title_color + ';';
				}

				return attrButton;
			},

			/**
			 * Builds attributes.
			 *
			 * @since 2.0
			 * @param {Object} values - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			buildHeadingAttr: function( values ) {
				var attrHeading = {
					class: 'elegant-modal-title modal-title',
					id: 'modal-heading-' + this.model.get( 'cid' ),
					'data-dismiss': 'modal',
					'aria-hidden': 'true',
					style: ''
				},
				typography,
				title_typography;

				if ( '' !== values.title_color ) {
					attrHeading['style'] += 'color:' + values.title_color + ';';
				}

				if ( '' !== values.typography_title ) {
					typography       = values.typography_title;
					title_typography = elegant_get_typography_css( typography );

					attrHeading['style'] += title_typography;
				}

				if ( '' !== values.title_font_size ) {
					attrHeading['style'] += 'font-size:' + values.title_font_size + ';';
				}

				attrHeading = _.fusionInlineEditor( {
					cid: this.model.get( 'cid' ),
					param: 'title',
					'disable-return': true,
					'disable-extra-spaces': true,
					toolbar: false
				}, attrHeading );

				return attrHeading;
			},

			/**
			 * Builds attributes.
			 *
			 * @since 2.0
			 * @param {Object} values - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			buildFooterAttr: function( values ) {
				var footerAttr = {
						'class': 'elegant-modal-footer modal-footer',
						'aria-hidden': 'true',
						'style': ''
					};

				if ( '' !== values.footer_background ) {
					footerAttr['style'] += 'background-color:' + values.footer_background;
				}

				return footerAttr;
			},

			/**
			 * Builds attributes.
			 *
			 * @since 2.0
			 * @param {Object} values - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			buildHFooterButtonAttr: function( values ) {
				var attrFooterButton = {
					class: 'elegant-modal-dialog-button-wrapper modal-footer-button',
					'data-dismiss': 'modal',
					style: ''
				},
				typography,
				button_typography;

				if ( '' !== values.typography_footer_button ) {
					typography        = values.typography_footer_button;
					button_typography = elegant_get_typography_css( typography );

					attrFooterButton['style'] += button_typography;
				}

				return attrFooterButton;
			},

			/**
			 * Builds attributes.
			 *
			 * @since 2.0
			 * @param {Object} values - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			buildFooterButton: function( values ) {
				var button_title = ( '' !== values.button_title ) ? values.button_title : 'Close',
					button_color = ( '' !== values.button_color ) ? values.button_color : 'default',
					button_attr,
					button_shortcode = '';

				if ( 'custom' === button_color ) {
					button_attr += ' link="#"';
					button_attr += ' title="' + button_title + '"';
					button_attr += ' color="' + button_color + '"';
					button_attr += ' button_gradient_top_color="' + values.button_gradient_top_color + '"';
					button_attr += ' button_gradient_bottom_color="' + values.button_gradient_bottom_color + '"';
					button_attr += ' button_gradient_top_color_hover="' + values.button_gradient_top_color_hover + '"';
					button_attr += ' button_gradient_bottom_color_hover="' + values.button_gradient_bottom_color_hover + '"';
					button_attr += ' accent_color="' + values.accent_color + '"';
					button_attr += ' accent_hover_color="' + values.accent_hover_color + '"';
					button_shortcode = '[fusion_button' + button_attr + ' link_attributes="data-dismiss=\'modal\' aria-hidden=\'true\'"]' + button_title + '[/fusion_button]';
				} else {
					button_shortcode = '[fusion_button link_attributes="data-dismiss=\'modal\' aria-hidden=\'true\'" color="' + button_color + '"]' + button_title + '[/fusion_button]';
				}

				return button_shortcode;
			},

			/**
			 * Builds attributes.
			 *
			 * @since 2.0
			 * @param {Object} values - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			buildTriggerAttr: function( values ) {
				var triggerAttr = {
						'class': 'elegant-modal-trigger',
						'data-toggle': 'modal',
						'data-target': '.elegant-modal.' + values.name
					};

				return triggerAttr;
			},

			/**
			 * Builds attributes.
			 *
			 * @since 2.0
			 * @param {Object} values - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			buildTriggerContent: function( values ) {
				var triggerContent = '';

				switch ( values.modal_trigger ) {
					case 'button':
						triggerContent = ( '' !== values.button_shortcode ) ? values.button_shortcode : '';
						break;
					case 'image':
						triggerContent = ( '' !== values.image_url ) ? '<img src="' + values.image_url + '">' : '';
						break;
					case 'icon':
						triggerContent = ( '' !== values.icon_shortcode ) ? values.icon_shortcode : '';
						break;
					case 'text':
						triggerContent = ( '' !== values.custom_text ) ? '<span>' + values.custom_text + '</span>' : '';
						break;
				}

				return triggerContent;
			}
		} );
	} );

	_.extend( FusionPageBuilder.Callback.prototype, {
		// Elegant modal shortcode filter. Encode button and icon shortcodes.
		elegantModalDialogShortcodeFilter: function( attributes, view ) {

			var modal = view.$el,
				button_value = modal.find( '#button_shortcode' ).val(),
				icon_value = modal.find( '#icon_shortcode' ).val(),
				button_shortcode,
				icon_shortcode;

			button_shortcode = FusionPageBuilderApp.base64Encode( button_value );
			icon_shortcode   = FusionPageBuilderApp.base64Encode( icon_value );

			attributes.params.button_shortcode = button_shortcode;
			attributes.params.icon_shortcode   = icon_shortcode;

			return attributes;

		}
	} );

}( jQuery ) );
