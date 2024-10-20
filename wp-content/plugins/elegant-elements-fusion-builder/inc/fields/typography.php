<#
var webfonts            = elegantGoogleFonts,
	allVariants         = webfonts.allVariants,
	googleFonts         = webfonts['Google Fonts'],
	systemFonts         = webfonts['System Fonts'],
	fontOptionValue     = '',
	fontOptionVariation = '',
	fontArray           = [],
	variants            = [],
	defaults            = '';

defaults            = ( 'undefined' !== typeof param.default ) ? param.default : 'Roboto:100';
option_value        = ( '' !== option_value ) ? option_value : defaults;
fontArray           = option_value.split(":");
fontOptionValue     = fontArray[0];
fontOptionVariation = fontArray[1];

if ( ( 'undefined' !== typeof googleFonts[ fontOptionValue ] ) ) {
	variants = googleFonts[ fontOptionValue ].variants.split(",");
} else {
	variants = webfonts.systemVariants.split(",");
}
#>
<div class="elegant-typography">
	<style id="elegant-typography" type="text/css">
	<#
	if ( ( 'undefined' !== typeof googleFonts[ fontOptionValue ] ) ) {
	#>
	@import url("https://fonts.googleapis.com/css?family={{ option_value }}" );
	<# } #>
	</style>
	<div class="fusion_builder_column_layout_1_2">
		<span class="elegant-font-select-label"><?php esc_attr_e( 'Font Family:', 'elegant-elements' ); ?></span>
		<div class="select_arrow"></div>
		<select data-param="{{ param.param_name }}" class="elegant-font-family font-family-{{ param.param_name }} elegant-select-field fusion-select-field<?php echo ( is_rtl() ) ? 'chosen-rtl fusion-select-field-rtl' : ''; ?>">
		<#
		_.each( webfonts, function( fonts, fontType ) {
			if ( 'allVariants' === fontType ) {
				return;
			}
			if ( 'systemVariants' === fontType ) {
				return;
			}
			#>
			<optgroup label="{{ fontType }}">
				<#
				_.each( fonts, function( fontData, index ) { #>
					<#
					var fontValue = fontData.family;
					#>
					<option value="{{ fontValue }}"
						data-variants="{{ fontData.variants }}"
						class="{{ fontData.family }}"
						{{ typeof( option_value ) !== 'undefined' && fontValue === fontOptionValue ?  ' selected="selected"' : '' }} >{{ fontData.family }}
					</option>
			<# }); #>
		<# }); #>
		</select>
	</div>
	<div class="fusion_builder_column_layout_1_2">
		<span class="elegant-font-select-label"><?php esc_attr_e( 'Font Style:', 'elegant-elements' ); ?></span>
		<div class="select_arrow"></div>
		<select data-param="{{ param.param_name }}" class="elegant-font-variants font-variants-{{ param.param_name }} elegant-select-field fusion-select-field<?php echo ( is_rtl() ) ? 'chosen-rtl fusion-select-field-rtl' : ''; ?>">
		<#
		_.each( variants, function( variant, index ) {
			if ( 'undefined' !== typeof allVariants ) {
				#>
				<option value="{{ variant }}"
					class="{{ variant }}"
					{{ typeof( option_value ) !== 'undefined' && variant === fontOptionVariation ?  ' selected="selected"' : '' }}>
					{{ allVariants[ variant ] }}
				</option>
			<# } #>
		<# }); #>
		</select>
	</div>
</div>
</div>
<div class="elegant-font-preview fusion_builder_column_layout_1_1" style="font-family:{{ fontOptionValue }};"><?php esc_attr_e( 'the quick brown fox jumped over the lazy dog', 'elegant-elements' ); ?>
<input id="{{ param.param_name }}" class="hidden typography-input" name="{{ param.param_name }}" value="{{ option_value }}" />
