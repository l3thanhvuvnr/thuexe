/* global FusionPageBuilderApp, elegantGoogleFonts, fusionAllElements */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready(
		function() {

			FusionPageBuilder.ModuleSettingElegantView = FusionPageBuilder.ElementSettingsView.extend(
				{

					events: {
						'click .elegant-elements-add-shortcode': 'addShortcode',
						'click [id$="fusion_shortcodes_text_mode"]': 'activateSCgenerator',
						'change input': 'optionChange',
						'keyup input:not(.fusion-slider-input)': 'optionChange',
						'change select.elegant-font-family': 'handleFontFamily',
						'change select.elegant-font-variants': 'handleFontVariants',
						'change select:not(.elegant-font-family)': 'optionChange',
						'change select:not(.elegant-font-variants)': 'optionChange',
						'keyup textarea': 'optionChange',
						'change textarea': 'optionChange',
						'paste textarea': 'optionChangePaste',
						'fusion-change input': 'optionChange',
						'click .upload-image-remove': 'removeImage',
						'click .option-preview-toggle': 'previewToggle',
						'click .fusion-panel-shortcut:not(.dialog-more-menu-item)': 'defaultPreview',
						'click .fusion-panel-description': 'showHideDescription',
						'click #fusion-close-element-settings': 'saveSettings',
						'click .fusion-builder-go-back': 'openParent'
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

						elementType = jQuery( event.currentTarget ).data( 'type' );
						editorID    = ( 'undefined' !== typeof jQuery( event.currentTarget ).data( 'editor-clone-id' ) ) ? jQuery( event.currentTarget ).data( 'editor-clone-id' ) : jQuery( event.currentTarget ).data( 'editor-id' );

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
							[
								{
									type: 'generated_element',
									added: 'manually',
									element_type: elementType,
									params: params
								}
							]
						);
					},

					handleFontFamily: function( event ) {
						var that = jQuery( event.currentTarget ),
							typography = that.closest( '.elegant-typography' ),
							variants   = typography.find( '.elegant-font-variants.fusion-select-field' ),
							fontFamily = that.val(),
							fontStyles = that.find( ':selected' ).data( 'variants' ).split( ',' ),
							styleOptions = '',
							webfonts = elegantGoogleFonts,
							googleFonts = webfonts['Google Fonts'],
							allVariants = [],
							selectedFont = '',
							fontVariant = '',
							$target,
							$option,
							paramName;

						$target   = jQuery( event.target ),
						$option   = $target.closest( '.fusion-builder-option' ),
						paramName = this.getParamName( $target, $option );

						fontFamily      = this.$el.find( '.font-family-' + paramName ).val();

						event.preventDefault();
						event.stopPropagation();
						event.stopImmediatePropagation();

						if ( ( 'undefined' == typeof googleFonts[ fontFamily ] ) ) {
							allVariants = webfonts.systemVariants;
						} else {
							allVariants = webfonts.allVariants;
						}

						if ( 'System Fonts' == that.find( ':selected' ).parent( 'optgroup' )[0].label ) {
							fontStyles = allVariants.split( ',' );
						}

						jQuery.each( fontStyles, function( index, variant ) { /* eslint-disable */
							styleOptions += '<option value="' + variant + '"\
								data-family="' + fontFamily + '"\
								class="' + variant + '">' + webfonts.allVariants[ variant ] + '\
							</option>';
						} );

						variants.html( styleOptions ).trigger( 'chosen:updated' );
						fontVariant = this.$el.find( '.font-variants-' + paramName ).val();

						selectedFont  = fontFamily + ':' + fontVariant;

						this.$el.find( '#' + paramName ).val( selectedFont ).attr( 'value', selectedFont ).trigger( 'change' );
					},

					handleFontVariants: function( event ) {
						var that = jQuery( event.currentTarget ),
							typography = that.closest( '.elegant-typography' ),
				            fontFamily   = typography.find( '.elegant-font-family.fusion-select-field' ).val(),
				            variant = that.val(),
				            selectedFont = '',
				            webfonts = elegantGoogleFonts,
				            googleFonts = webfonts['Google Fonts'],
							$target,
							$option,
							paramName;

						$target   = jQuery( event.target ),
						$option   = $target.closest( '.fusion-builder-option' ),
						paramName = this.getParamName( $target, $option );

						fontFamily = this.$el.find( '.font-family-' + paramName ).val();

						event.preventDefault();
						event.stopPropagation();
						event.stopImmediatePropagation();

						selectedFont = fontFamily + ':' + variant;

						this.$el.find( '#' + paramName ).val( selectedFont ).attr( 'value', selectedFont ).trigger( 'change' );
					}
				}
			);
		}
	);
} ( jQuery ) );
