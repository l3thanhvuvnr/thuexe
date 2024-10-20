var FusionPageBuilder = FusionPageBuilder || {};

( function( $ ) {

	$( document ).ready(
		function() {
				jQuery( '#fusion_builder_controls #fusion-page-builder-tabs' ).append( '<li><a href="#" class="fusion-builder-elegant-templates-dialog">' + elegantText.templates + '</a>' );

				// Delay
				var FusionDelay = ( function() {
					var timer = 0;

					return function( callback, ms ) {
						clearTimeout( timer );
						timer = setTimeout( callback, ms );
					};
				})();

				// Register Templates App view.
				FusionPageBuilder.FusionTemplatesApp = window.wp.Backbone.View.extend(
					{

						template: FusionPageBuilder.template( $( '#fusion-builder-elegant-templates' ).html() ),
						events: {
							'click .fusion-builder-modal-close': 'closeModal',
							'click .fusion_builder_modal_overlay': 'closeModal',
							'click .elegant-elements-template-button-load': 'insertTemplate',
							'change .elegant-template-search': 'sortElegantTemplates',
							'keyup .elegant-template-search': 'sortElegantTemplates'
						},

						initialize: function() {
							this.listenTo( FusionPageBuilderEvents, 'elegant-close-modal', this.closeModal );
						},

						render: function( target ) {
							this.$el.html( this.template() );
							return this;
						},

						insertTemplate: function( event ) {
							var templateID  = $( event.currentTarget ).data( 'key' ),
							postID          = $( event.currentTarget ).data( 'post-id' ),
							loadType        = $( event.currentTarget ).data( 'load-type' ),
							templateUrl     = $( event.currentTarget ).data( 'template-url' ),
							content         = fusionBuilderGetContent( 'content' ),
							templateContent = '',
							contentToReplace,
							$customCSS      = jQuery( '#fusion-custom-css-field' ).val();

							event.preventDefault();

							$.ajax(
								{
									type: 'GET',
									url: templateUrl,
									data: {
										elegant_templates_security: elegantElementsConfig.elegant_templates_security,
										template_id: templateID,
									},
									beforeSend: function() {
										FusionPageBuilderEvents.trigger( 'fusion-show-loader' );
										FusionPageBuilderEvents.trigger( 'elegant-close-modal' );

										$( 'body' ).removeClass( 'fusion_builder_inner_row_no_scroll' );
										$( '.fusion_builder_modal_inner_row_overlay' ).remove();
										$( '#fusion-builder-layouts' ).hide();

									},
									success: function( template ) {

										// Import and replace images in the content.
										$.ajax(
											{
												type: 'POST',
												url: elegantElementsConfig.ajaxurl,
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
															$( '#fusion-custom-css-field' ).val( template.custom_css + '\n' + $customCSS );
														}

													} else if ( 'below' === loadType ) {
														contentToReplace = content + templateContent;

														// Set custom css below
														if ( 'undefined' !== typeof ( template.custom_css ) ) {
															if ( $customCSS.length ) {
																$( '#fusion-custom-css-field' ).val( $customCSS + '\n' + template.custom_css );
															} else {
																$( '#fusion-custom-css-field' ).val( template.custom_css );
															}
														}

													} else {
														contentToReplace = templateContent;

														// Set custom css.
														if ( 'undefined' !== typeof ( template.custom_css ) ) {
															$( '#fusion-custom-css-field' ).val( template.custom_css );
														}

													}

													FusionPageBuilderApp.clearBuilderLayout();

													FusionPageBuilderApp.createBuilderLayout( contentToReplace );

													FusionPageBuilderApp.layoutIsLoading = false;

													// Set page template to 100% width template.
													$( '#page_template' ).val( '100-width.php' );
												},
												complete: function() {
													FusionPageBuilderEvents.trigger( 'fusion-hide-loader' );
												}
											}
										);
									}
								}
							);
						},

						sortElegantTemplates: function( event ) {
							var $this = this,
							thisEl,
							search,
							name,
							value,
							templates = jQuery( '.elegant-elements-template' );

							thisEl = $( event.target );

							FusionDelay(
								function() {
									if ( thisEl.val() ) {
										value = thisEl.val().toLowerCase();

										_.each(
											templates, function( template ) {
												name = jQuery( template ).find( '.elegant-elements-template-title' ).text().toLowerCase();

												jQuery( template ).hide();
												if ( name.search( value ) !== -1 ) {
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
						},

						closeModal: function( event ) {
							if ( event ) {
								event.preventDefault();
							}
							$( '.fusion_builder_modal_overlay' ).remove();
							$( '#fusion-templates-container' ).remove();
							$( 'body' ).removeClass( 'fusion_builder_no_scroll' );
						},

						generateMasonry: function() {
							var macy = Macy({
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
							});
						}
					}
				);

				jQuery( 'body' ).find( '.fusion-builder-elegant-templates-dialog' ).click(
					function( event ) {
						var fusionTemplates;

						event.preventDefault();

						fusionTemplates        = new FusionPageBuilder.FusionTemplatesApp();
						window.fusionTemplates = fusionTemplates;
						$( 'body' ).append( '<div class="fusion_builder_modal_overlay"></div><div id="fusion-templates-container" class="elegant-elements-modal-settings-container fusion-builder-modal-settings-container"></div>' );
						$( 'body' ).find( '#fusion-templates-container' ).html( fusionTemplates.render().el );
						$( 'body' ).addClass( 'fusion_builder_no_scroll' );
					}
				);
		}
	);
} )( jQuery );
