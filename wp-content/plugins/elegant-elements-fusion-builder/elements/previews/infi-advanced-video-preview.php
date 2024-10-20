<script type="text/template" id="elegant-elements-module-infi-advanced-video-preview-template">
	<h4 class="fusion_module_title"><span class="fusion-module-icon {{ fusionAllElements[element_type].icon }}"></span>{{ fusionAllElements[element_type].name }}</h4>
	<#
	if ( params.image ) {
		#>
		<img src="{{{ params.image }}}"
		<#
		if ( params.image_retina ) {
			#>
			srcset="{{{ params.image }}} 1x, {{{ params.image_retina }}} 2x"
			<#
		}
		#>
		/>
		<#
	}
	#>
</script>
