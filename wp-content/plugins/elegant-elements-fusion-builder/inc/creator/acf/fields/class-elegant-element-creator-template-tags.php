<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Check if class already exists.
if ( ! class_exists( 'Elegant_Element_Creator_Template_Tags' ) ) :

	class Elegant_Element_Creator_Template_Tags extends acf_field {

		/**
		 * The constructor.
		 *
		 * This function will setup the field type data
		 *
		 * @type function
		 * @param array $settings Settings for the field.
		 * @since 3.0
		 * @return void
		 */
		public function __construct( $settings ) {

			// Name (string) Single word, no spaces. Underscores allowed.
			$this->name = 'element_creator_template_tags';

			// Label (string) Multiple words, can include spaces, visible when selecting a field type.
			$this->label = __( 'Template Tags', 'wcppb' );

			// Category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME.
			$this->category = 'Elegant Elements';

			// Defaults (array) Array of default settings which are merged into the field object. These are used later in settings.
			$this->defaults = array();

			// l10n (array) Array of strings that are used in JavaScript.
			$this->l10n = array(
				'error'	=> __( 'Error! Please enter a higher value', 'wcppb' ),
			);

			// Settings (array) Store plugin settings (url, path, version) as a reference for later use with assets.
			$this->settings = $settings;

			// DO NOT DELETE!
			parent::__construct();

		}

		/**
		 *  Render field.
		 *
		 *  Create the HTML interface for your field.
		 *
		 * @since 3.0
		 * @param array $field The $field being edited.
		 * @return void
		 */
		public function render_field( $field ) {

			// Get attributes list.
			$attributes = get_field( 'attributes', array() );
			$attributes = ( isset( $attributes['attribute'] ) ) ? $attributes['attribute'] : array();
			?>
			<div class="elegant-element-creator-template-tags">
				<ul id="core-template-tags" class="core-template-tags wp-clearfix">
					<li class="element-creator-core-template-tag" data-tag="element_id">
						<a href="javascript:void(0);" style="color: #673AB7;" class="elegant-element-creator-template-tag-link" data-template-tag="element_id">{{element_id}}</a>
					</li>
				</ul>
				<ul id="template-tags-container" class="template-tags-container wp-clearfix">
				<?php
				if ( is_array( $attributes ) && ! empty( $attributes ) ) {

					// @codingStandardsIgnoreStart
					foreach ( $attributes as $attribute ) {
						$template_tag = str_replace( array( ' ', '-' ), '_', strtolower( $attribute['param_name'] ) );
						echo '<li class="elegant-element-creator-template-tag" data-tag="' . $template_tag . '">
								<a href="javascript:void(0);" class="elegant-element-creator-template-tag-link" data-template-tag="' . $template_tag . '">{{' . $template_tag . '}}</a>
							</li>';
					}
				}
				// @codingStandardsIgnoreEnd
				?>
				</ul>
			</div>
			<style type="text/css">
				a.elegant-element-creator-template-tag-link {
					text-decoration: none;
				}
				div[data-type="textarea"] .acf-input {
					border: 1px solid #eee;
				}
			</style>
			<?php

			// Reset creator transients.
			delete_transient( 'elegant_element_creator_posts' );
		}
	}

	// initialize.
	new Elegant_Element_Creator_Template_Tags( $this->settings );

	// class_exists check.
endif;
