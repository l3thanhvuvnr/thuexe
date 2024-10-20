var FusionPageBuilder = FusionPageBuilder || {};

( function( $ ) {

	$( document ).ready(
		function() {

			FusionPageBuilderApp.ElegantElementShortcodeFilter = function( attributes, view ) {
				attributes.params.element_content = attributes.params.element_content_placeholder;
				delete attributes.params.element_content_placeholder;

				return attributes;
			};

			FusionPageBuilder.ModuleSettingElegantView = FusionPageBuilder.ElementSettingsView.extend(
				{

					events: {
						'click .elegant-elements-add-shortcode': 'addShortcode',
						'click .option-dynamic-content': 'addDynamicContent'
					},

					initialize: function() {
						if ( 'object' !== typeof this.model.attributes.params.element_content_placeholder && 'undefined' !== typeof this.model.attributes.params.element_content ) {
							this.model.attributes.params.element_content_placeholder = this.model.attributes.params.element_content;
						}

						this.listenTo( FusionPageBuilderEvents, 'fusion-dynamic-data-removed', this.removeDynamicStatus );
						this.listenTo( FusionPageBuilderEvents, 'fusion-dynamic-data-added', this.addDynamicStatus );
						this.dynamicSelection = false;
						this.dynamicParams    = 'object' === typeof this.options && 'object' === typeof this.options.dynamicParams ? this.options.dynamicParams : false;
					},

					addShortcode: function( event ) {

						var defaultParams,
							params,
							elementType,
							editorID,
							value;

						if ( event ) {
							event.preventDefault();
						}

						elementType = $( event.currentTarget ).data( 'type' );
						editorID    = ( 'undefined' !== typeof $( event.currentTarget ).data( 'editor-clone-id' ) ) ? $( event.currentTarget ).data( 'editor-clone-id' ) : $( event.currentTarget ).data( 'editor-id' );

						FusionPageBuilderApp.manualGenerator            = FusionPageBuilderApp.shortcodeGenerator;
						FusionPageBuilderApp.manualEditor               = FusionPageBuilderApp.shortcodeGeneratorEditorID;
						FusionPageBuilderApp.manuallyAdded              = true;
						FusionPageBuilderApp.shortcodeGenerator         = true;
						FusionPageBuilderApp.shortcodeGeneratorEditorID = editorID;

						// Get default options
						defaultParams = fusionAllElements[elementType].params;
						params        = {};

						// Process default parameters from shortcode
						_.each(
							defaultParams, function( param )  {
								if ( _.isObject( param.value ) ) {
									value = param.default;
								} else {
									value = param.value;
								}
								params[param.param_name] = value;
							}
						);

						this.collection.add(
							[ {
								type: 'generated_element',
								added: 'manually',
								element_type: elementType,
								params: params
							} ]
						);
					}
				}
			);
		}
	);
} )( jQuery );
