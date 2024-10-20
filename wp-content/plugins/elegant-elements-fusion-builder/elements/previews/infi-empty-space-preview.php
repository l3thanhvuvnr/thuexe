<script type="text/template" id="elegant-elements-module-infi-empty-space-preview-template">
	<h4 class="fusion_module_title"><span class="fusion-module-icon {{ fusionAllElements[element_type].icon }}"></span>{{ fusionAllElements[element_type].name }}</h4>

	<#
	var space = ( 'vertical' == params.type ) ? params.height : params.width;
		<!-- type = ( 'vertical' == params.type ) ? <?php esc_html_e( 'Height', 'elegant-elements' ); ?> : <?php esc_html_e( 'Width', 'elegant-elements' ); ?>; -->

	if ( 'vertical' == params.type ) { #>
		<?php esc_html_e( 'Height', 'elegant-elements' ); ?>
	<# } else { #>
		<?php esc_html_e( 'Width', 'elegant-elements' ); ?>
	<# } #>
 = {{ space }}px

</script>
