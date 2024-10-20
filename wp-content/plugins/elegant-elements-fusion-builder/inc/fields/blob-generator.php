<div class="elegant-element-blob-shape-generator-container">
	<div class="elegant-element-blob-shape-generator-fields-wrapper">
		<div class="elegant-element-blob-shape-generator-field">
			<input
			type="hidden"
			name="{{ param.param_name }}"
			id="{{ param.param_name }}"
			value="{{ option_value }}"
			class="elegant-blob-shape-generator-input"
			/>
			<div class="elegant-blob-shape-generator-controls elegant-element-field-controls" style="text-align: center;">
				<a href="#" class="button button-primary elegant-element-blob-shape-generator-button" style="margin-bottom: 15px;"><?php esc_attr_e( 'Generate Blob Shape', 'elegant-elements' ); ?></a>
				<div class="elegant-blob-shape-generator-placeholder-wrapper">
					<div class="elegant-blob-shape-generator-placeholder button-primary" style="border-radius:{{ option_value }};"></div>
				</div>
			</div>
		</div>
	</div>
</div>
