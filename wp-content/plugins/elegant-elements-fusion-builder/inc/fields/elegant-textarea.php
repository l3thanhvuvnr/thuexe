<#
option_value = FusionPageBuilderApp.base64Decode( option_value );
#>
<textarea
	name="{{ param.param_name }}"
	id="{{ param.param_name }}"
	class="elegant-textarea"
	cols="20"
	rows="5"
>{{ option_value }}</textarea>
