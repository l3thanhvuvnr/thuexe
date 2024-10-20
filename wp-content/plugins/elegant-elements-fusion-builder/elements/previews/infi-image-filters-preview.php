<script type="text/template" id="elegant-elements-module-infi-image-filters-preview-template">

	<h4 class="fusion_module_title"><span class="fusion-module-icon {{ fusionAllElements[element_type].icon }}"></span>{{ fusionAllElements[element_type].name }}<br/></h4>

	<div>
		<#
		var
		content = typeof params.element_content !== 'undefined' ? params.element_content : '',
		filter_image_reg_exp = window.wp.shortcode.regexp( 'iee_filter_image' ),
		filter_image_inner_reg_exp = new RegExp( '\\[(\\[?)(iee_filter_image)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*(?:\\[(?!\\/\\2\\])[^\\[]*)*)(\\[\\/\\2\\]))?)(\\]?)' ),
		filter_image_matches = content.match( filter_image_reg_exp );

		if( null !== filter_image_matches && filter_image_matches.length ) {
			_.each( filter_image_matches.slice(0,6), function ( filter_image_shortcode ) {
				var
				filter_image_shortcode_element = filter_image_shortcode.match( filter_image_inner_reg_exp ),
				filter_image_shortcode_content = filter_image_shortcode_element[5],
				filter_image_shortcode_attributes = filter_image_shortcode_element[3] !== '' ? window.wp.shortcode.attrs( filter_image_shortcode_element[3] ) : ''; #>

				<img src="{{ filter_image_shortcode_attributes.named['image_url'] }}" class="elegant-logo-preview" />
			<#
			});
		}
		#>
	</div>
</script>
