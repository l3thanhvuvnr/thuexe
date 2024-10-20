<#
var iconClass = ('' !== option_value ) ? option_value.split( ' ' ) : 'fa';
iconClass = ( 'undefined' !== typeof iconClass[1] && 'fa' !== iconClass ) ? iconClass[1] : 'fa';

var fontClass = ( '' === option_value ) ? 'no-icon' : option_value;
jQuery( document ).trigger( 'elegant_icon_selector_loaded', [ atts.params ] );
#>
<div class="elegant-element-icon-selector-container">
	<div class="elegant-element-icon-selector-fields-wrapper">
		<div class="elegant-element-icon-selector-field">
			<input
			type="hidden"
			name="{{ param.param_name }}"
			id="{{ param.param_name }}"
			value="{{ option_value }}"
			class="elegant-icon-selector-input"
			/>
			<div class="elegant-icon-selector-controls elegant-element-field-controls">
				<a href="#" class="elegant-element-icon-selector-add icon-selector-placeholder" title="{{ fontClass }}"><span class="{{ iconClass }} {{ fontClass }}"></span></a>
			</div>
		</div>
	</div>
</div>
