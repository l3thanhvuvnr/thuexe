<script type="text/template" id="elegant-elements-module-infi-business-hours-preview-template">

	<h4 class="fusion_module_title"><span class="fusion-module-icon {{ fusionAllElements[element_type].icon }}"></span>{{ fusionAllElements[element_type].name }}<br/></h4>

	<ul>
		<#
		var
		content = typeof params.element_content !== 'undefined' ? params.element_content : '',
		shortcode_reg_exp = window.wp.shortcode.regexp( 'iee_business_hours_item' ),
		shortcode_inner_reg_exp = new RegExp( '\\[(\\[?)(iee_business_hours_item)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*(?:\\[(?!\\/\\2\\])[^\\[]*)*)(\\[\\/\\2\\]))?)(\\]?)' ),
		shortcode_matches = content.match( shortcode_reg_exp ),
		counter = 0;

		_.each( shortcode_matches, function ( inner_item ) {

			if ( counter < 3 ) {
				var
				shortcode_element = inner_item.match( shortcode_inner_reg_exp ),
				shortcode_content = shortcode_element[5];
				shortcode_attributes = shortcode_element[3] !== '' ? window.wp.shortcode.attrs( shortcode_element[3] ) : '';
				#>
				<li>{{ shortcode_attributes.named['title'] }} - {{ shortcode_attributes.named['hours_text'] }}</li>

			<#
			}

			counter++;

		});

		if ( counter > 3 ) { #>
			<span>...</span>
		<#
		}
		#>
	</ul>
</script>
