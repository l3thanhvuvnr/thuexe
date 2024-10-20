<script type="text/template" id="elegant-elements-module-infi-partner-logos-preview-template">

	<h4 class="fusion_module_title"><span class="fusion-module-icon {{ fusionAllElements[element_type].icon }}"></span>{{ fusionAllElements[element_type].name }}<br/></h4>

	<div>
		<#
		var
		content = typeof params.element_content !== 'undefined' ? params.element_content : '',
		partner_logo_reg_exp = window.wp.shortcode.regexp( 'iee_partner_logo' ),
		partner_logo_inner_reg_exp = new RegExp( '\\[(\\[?)(iee_partner_logo)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*(?:\\[(?!\\/\\2\\])[^\\[]*)*)(\\[\\/\\2\\]))?)(\\]?)' ),
		partner_logo_matches = content.match( partner_logo_reg_exp );

		if( null !== partner_logo_matches && partner_logo_matches.length ) {
			_.each( partner_logo_matches.slice(0,6), function ( partner_logo_shortcode ) {
				var
				partner_logo_shortcode_element = partner_logo_shortcode.match( partner_logo_inner_reg_exp ),
				partner_logo_shortcode_content = partner_logo_shortcode_element[5],
				partner_logo_shortcode_attributes = partner_logo_shortcode_element[3] !== '' ? window.wp.shortcode.attrs( partner_logo_shortcode_element[3] ) : ''; #>

				<img src="{{ partner_logo_shortcode_attributes.named['image_url'] }}" class="elegant-logo-preview" />
			<#
			});
		}
		#>
	</div>
</script>
