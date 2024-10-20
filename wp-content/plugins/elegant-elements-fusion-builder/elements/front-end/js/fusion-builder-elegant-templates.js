/* global FusionApp, FusionPageBuilderApp, FusionEvents, fusionAppConfig, elegantElementsConfig, elegantText, Macy */
var FusionPageBuilder = FusionPageBuilder || {};

( function( $ ) {

	jQuery( window ).on( 'load',
		function() {

			// Delay
			var FusionDelay = ( function() {
				var timer = 0;

				return function( callback, ms ) {
					clearTimeout( timer );
					timer = setTimeout( callback, ms );
				};
			}() );

			// Register Templates App view.
			FusionPageBuilder.FusionTemplatesApp = window.wp.Backbone.View.extend( {

				template: FusionPageBuilder.template( $( '#fusion-builder-elegant-templates' ).html() ),
				el: '#fusion-builder-elegant-templates',
				events: {
					'click .fusion-builder-modal-close': 'closeModal',
					'click .fusion_builder_modal_overlay': 'closeModal',
					'click .elegant-elements-template-button-load': 'insertTemplate',
					'change .elegant-template-search': 'sortElegantTemplates',
					'keyup .elegant-template-search': 'sortElegantTemplates'
				},

				/**
				 * Initialize the events.
				 *
				 * @since 2.0
				 * @return {void}
				 */
				initialize: function() {
					this.listenTo( FusionEvents, 'elegant-close-modal', this.closeModal );

					// Loader animation
					this.listenTo( FusionEvents, 'fusion-show-loader', this.showLoader );
					this.listenTo( FusionEvents, 'fusion-hide-loader', this.hideLoader );
				},

				/**
				 * Renders the loader.
				 *
				 * @since 2.0
				 * @return {void}
				 */
				showLoader: function() {
					jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( '.fusion-builder-live-editor' ).css( 'height', '148px' );
					jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( '.fusion-builder-live-editor' ).append( '<div class="fusion-builder-element-content fusion-loader"><span class="fusion-builder-loader"></span></div>' );
					jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( '#fusion_builder_container' ).hide();
				},

				/**
				 * Removes the loader.
				 *
				 * @since 2.0
				 * @return {void}
				 */
				hideLoader: function() {
					jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( '#fusion_builder_container' ).fadeIn( 'fast' );
					jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( '.fusion-builder-live-editor > .fusion-builder-element-content.fusion-loader' ).remove();
					jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( '.fusion-builder-live-editor' ).removeAttr( 'style' );
				},

				/**
				 * Renders the view.
				 *
				 * @since 2.0
				 * @return {Object} this
				 */
				render: function() {
					var self = this;

					this.$el = this.$el.dialog( {
						title: elegantText.elegant_templates,
						width: FusionApp.dialog.dialogWidth,
						height: FusionApp.dialog.dialogHeight,
						draggable: false,
						resizable: false,
						modal: true,
						dialogClass: 'fusion-builder-large-templates-dialog fusion-builder-dialog fusion-builder-templates-dialog',

						open: function() {
							FusionApp.dialog.resizeDialog();
						},

						close: function() {
							self.removeView();
						}
					} ).closest( '.ui-dialog' );

					this.sortElegantTemplates( this.$el );

					return this;
				},

				/**
				 * Inserts the template from library.
				 *
				 * @since 2.0
				 * @param {Object} event - The current event.
				 * @return {Object} this
				 */
				insertTemplate: function( event ) {
					var self = this,
						templateID  = $( event.currentTarget ).data( 'key' ),
						postID          = $( event.currentTarget ).data( 'post-id' ),
						loadType        = $( event.currentTarget ).data( 'load-type' ),
						templateUrl     = $( event.currentTarget ).data( 'template-url' ),
						content         = '',
						templateContent = '',
						contentToReplace,
						$customCSS      = FusionApp.data.postMeta._fusion_builder_custom_css;

					event.preventDefault();

					if ( true === FusionPageBuilderApp.layoutIsLoading ) {
						return;
					}
					FusionPageBuilderApp.layoutIsLoading = true;

					FusionPageBuilderApp.builderToShortcodes();
					content = FusionApp.getPost( 'post_content' );

					FusionPageBuilderApp.loaded = false;

					$.ajax(
						{
							type: 'GET',
							url: templateUrl,
							data: {
								elegant_templates_security: elegantElementsConfig.elegant_templates_security,
								template_id: templateID
							},
							beforeSend: function() {
								FusionEvents.trigger( 'fusion-show-loader' );

								// Hide library dialog.
								self.$el.css( 'display', 'none' );
								self.$el.next( '.ui-widget-overlay' ).css( 'display', 'none' );

							},
							success: function( template ) {

								// Import and replace images in the content.
								$.ajax(
									{
										type: 'POST',
										url: fusionAppConfig.ajaxurl,
										data: {
											action: 'elegant_get_template_data',
											elegant_templates_security: elegantElementsConfig.elegant_templates_security,
											template_content: template.content,
											post_id: postID
										},
										success: function( templateData ) {
											// New layout loaded
											FusionPageBuilderApp.layoutLoaded();

											templateContent = templateData;

											if ( 'above' === loadType ) {
												contentToReplace = templateContent + content;

												// Set custom css above
												if ( 'undefined' !== typeof ( template.custom_css ) ) {
													if ( 'undefined' !== typeof $customCSS && $customCSS.length ) {
														FusionApp.data.postMeta._fusion_builder_custom_css = template.custom_css + '\n' + $customCSS;
													} else {
														FusionApp.data.postMeta._fusion_builder_custom_css = template.custom_css;
													}
												}

											} else if ( 'below' === loadType ) {
												contentToReplace = content + templateContent;

												// Set custom css below
												if ( 'undefined' !== typeof ( template.custom_css ) ) {
													if ( 'undefined' !== typeof $customCSS && $customCSS.length ) {
														FusionApp.data.postMeta._fusion_builder_custom_css = $customCSS + '\n' + template.custom_css;
													} else {
														FusionApp.data.postMeta._fusion_builder_custom_css = template.custom_css;
													}
												}

											} else {
												contentToReplace = templateContent;

												// Set custom css.
												if ( 'undefined' !== typeof ( template.custom_css ) ) {
													FusionApp.data.postMeta._fusion_builder_custom_css = template.custom_css;
												}

												FusionApp.contentChange( 'page', 'page-option' );
											}

											FusionApp.setPost( 'post_content', content );
											FusionApp.contentChange( 'page', 'builder-content' );

											FusionPageBuilderApp.clearBuilderLayout();

											FusionPageBuilderApp.createBuilderLayout( contentToReplace );

											FusionPageBuilderApp.layoutIsLoading = false;

											// Set page template to 100% width template.
											$( '#page_template' ).val( '100-width.php' );
											FusionApp.data.postMeta['page_template'] = '100-width.php';
										},
										complete: function() {
											FusionPageBuilderApp.loaded = true;
											FusionEvents.trigger( 'fusion-builder-loaded' );

											FusionEvents.trigger( 'fusion-hide-loader' );

											FusionEvents.trigger( 'elegant-template-imported' );
											self.removeView();
										}
									}
								);
							}
						}
					);
				},

				/**
				 * Perform template search.
				 *
				 * @since 2.0
				 * @param {Object} thisEl - The current element.
				 * @return {Object} this
				 */
				sortElegantTemplates: function( thisEl ) {
					var name,
						value,
						templates = jQuery( '.elegant-elements-template' ),
						$this = this;

					thisEl.find( '.elegant-template-search' ).on( 'change paste keyup', function() {
						var searcnInput = jQuery( this );

						FusionDelay(
							function() {
								if ( searcnInput.val() ) {
									value = searcnInput.val().toLowerCase();

									_.each(
										templates, function( template ) {
											name = jQuery( template ).find( '.elegant-elements-template-title' ).text().toLowerCase();

											jQuery( template ).hide();
											if ( -1 !== name.search( value ) ) {
												jQuery( template ).show();
											}
										}
									);

									$this.generateMasonry();

								} else {
									_.each(
										templates, function( template ) {
											jQuery( template ).show();
										}
									);

									$this.generateMasonry();
								}
							}, 500
						);
					} );
				},

				/**
				 * Close the model
				 *
				 * @since 2.0
				 * @return {Object} this
				 */
				closeModal: function() {
					$( '.fusion_builder_modal_overlay' ).remove();
					$( '#fusion-templates-container' ).remove();
					$( 'body' ).removeClass( 'fusion_builder_no_scroll' );
				},

				/**
				 * Removes the view.
				 *
				 * @since 2.0
				 * @return {void}
				 */
				removeView: function() {
					this.$el.find( '.fusion-save-element-fields' ).remove();
					this.$el.find( '.fusion-builder-modal-top-container' ).prependTo( '#fusion-builder-elegant-templates' );

					FusionApp.dialogCloseResets( this );

					this.remove();
				},

				generateMasonry: function() {
					Macy( {
						container: '#elegant-elements-templates-container',
						trueOrder: true,
						waitForImages: true,
						margin: 20,
						columns: 3,
						breakAt: {
							1200: 3,
							940: 3,
							520: 2,
							400: 1
						}
					} );
				}
			} );
		}
	);
}( jQuery ) );
