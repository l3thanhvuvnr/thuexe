<div class="select_arrow"></div>
<select id="{{ param.param_name }}" name="{{ param.param_name }}" class="elegant-select-field fusion-select-field<?php echo ( is_rtl() ) ? 'chosen-rtl fusion-select-field-rtl' : ''; ?>">
<# _.each( param.value, function( name, value ) { #>
	<optgroup label="{{ value }}">
		<# _.each( param.value[ value ], function( optionName, optionValue ) { #>
			<option value="{{ optionValue }}" {{ typeof( option_value ) !== 'undefined' && optionValue === option_value ?  ' selected="selected"' : '' }} >{{ optionName }}</option>
		<# }); #>
	</optgroup>
<# }); #>
</select>
