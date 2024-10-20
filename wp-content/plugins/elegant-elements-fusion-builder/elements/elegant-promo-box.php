<?php
if ( fusion_is_element_enabled( 'iee_promo_box' ) && ! class_exists( 'IEE_Promo_Box' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class IEE_Promo_Box extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $args;

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-promo-box', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_elegant-promo-box-title', array( $this, 'title_attr' ) );
			add_filter( 'fusion_attr_elegant-promo-box-description-wrapper', array( $this, 'description_wrapper_attr' ) );
			add_filter( 'fusion_attr_elegant-promo-box-description', array( $this, 'description_attr' ) );

			add_shortcode( 'iee_promo_box', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 1.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			$args['content_align'] = ( isset( $args['content_align'] ) && '' !== $args['content_align'] ) ? $args['content_align'] : 'center';

			// Get default typography for title and description.
			$default_typography = elegant_get_default_typography();

			if ( isset( $args['element_typography'] ) && 'default' === $args['element_typography'] ) {
				$args['typography_title']       = $default_typography['title'];
				$args['typography_description'] = $default_typography['description'];
			}

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'image'                  => '',
					'title'                  => '',
					'heading_size'           => 'h2',
					'element_typography'     => 'default',
					'typography_title'       => '',
					'title_font_size'        => '28',
					'title_color'            => '',
					'description'            => esc_attr__( 'Your promo text goes here. You can edit it using Frontend Builder.', 'elegant-elements' ),
					'typography_description' => '',
					'description_font_size'  => '18',
					'description_color'      => '',
					'image_align'            => 'left',
					'content_align'          => 'center',
					'background_color'       => '',
					'background_image'       => '',
					'background_position'    => 'left top',
					'background_repeat'      => 'no-repeat',
					'height'                 => '300',
					'element_content'        => '',
					'hide_on_mobile'         => fusion_builder_default_visibility( 'string' ),
					'class'                  => '',
					'id'                     => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/promo-box/elegant-promo-box.php' ) ) {
				include locate_template( 'templates/promo-box/elegant-promo-box.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/promo-box/elegant-promo-box.php';
			}

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-promo-box',
				'style' => '',
			);

			$attr['class'] .= ' promo-image-align-' . $this->args['image_align'];

			$attr = fusion_builder_visibility_atts( $this->args['hide_on_mobile'], $attr );

			if ( isset( $this->args['background_color'] ) && '' !== $this->args['background_color'] ) {
				$attr['style'] .= 'background-color:' . $this->args['background_color'] . ';';
			}

			if ( isset( $this->args['background_image'] ) && '' !== $this->args['background_image'] ) {
				$attr['style'] .= 'background-image: url("' . $this->args['background_image'] . '");';
				$attr['style'] .= 'background-position:' . $this->args['background_position'] . ';';
				$attr['style'] .= 'background-repeat:' . $this->args['background_repeat'] . ';';
				$attr['style'] .= 'background-blend-mode: overlay;';
			}

			if ( isset( $this->args['height'] ) && '' !== $this->args['height'] ) {
				$attr['style'] .= 'height:' . FusionBuilder::validate_shortcode_attr_value( $this->args['height'], 'px' ) . ';';
			}

			if ( isset( $this->args['class'] ) ) {
				$attr['class'] .= ' ' . $this->args['class'];
			}

			if ( isset( $this->args['id'] ) ) {
				$attr['id'] = $this->args['id'];
			}

			return $attr;
		}

		/**
		 * Builds the attributes array for title.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function title_attr() {
			$attr = array(
				'class' => 'elegant-promo-box-title',
				'style' => '',
			);

			if ( isset( $this->args['typography_title'] ) && '' !== $this->args['typography_title'] ) {
				$typography       = $this->args['typography_title'];
				$title_typography = elegant_get_typography_css( $typography );

				$attr['style'] .= $title_typography;
			}

			if ( isset( $this->args['title_font_size'] ) && '' !== $this->args['title_font_size'] ) {
				$attr['style'] .= 'font-size:' . FusionBuilder::validate_shortcode_attr_value( $this->args['title_font_size'], 'px' ) . ';';
			}

			if ( isset( $this->args['title_color'] ) && '' !== $this->args['title_color'] ) {
				$attr['style'] .= 'color:' . $this->args['title_color'] . ';';
			}

			return $attr;
		}

		/**
		 * Builds the attributes array for description.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function description_wrapper_attr() {
			$attr = array(
				'class' => 'elegant-promo-box-description-wrapper',
			);

			$attr['class'] .= ' elegant-align-' . $this->args['content_align'];

			return $attr;
		}

		/**
		 * Builds the attributes array for description.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function description_attr() {
			$attr = array(
				'class' => 'elegant-promo-box-description',
				'style' => '',
			);

			if ( isset( $this->args['typography_description'] ) && '' !== $this->args['typography_description'] ) {
				$typography             = $this->args['typography_description'];
				$description_typography = elegant_get_typography_css( $typography );

				$attr['style'] .= $description_typography;
			}

			if ( isset( $this->args['description_font_size'] ) && '' !== $this->args['description_font_size'] ) {
				$attr['style'] .= 'font-size:' . FusionBuilder::validate_shortcode_attr_value( $this->args['description_font_size'], 'px' ) . ';';
			}

			if ( isset( $this->args['description_color'] ) && '' !== $this->args['description_color'] ) {
				$attr['style'] .= 'color:' . $this->args['description_color'] . ';';
			}

			return $attr;
		}
	}

	new IEE_Promo_Box();
} // End if().


/**
 * Map shortcode for promo_box.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_promo_box() {
	global $fusion_settings;

	// Get default typography for title and description.
	$default_typography = elegant_get_default_typography();

	fusion_builder_map(
		array(
			'name'                      => esc_attr__( 'Elegant Promo Box', 'elegant-elements' ),
			'shortcode'                 => 'iee_promo_box',
			'icon'                      => 'icon-promo-box',
			'preview'                   => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/previews/infi-promo-box-preview.php',
			'preview_id'                => 'elegant-elements-module-infi-promo-box-preview-template',
			'front-end'                 => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-promo-box-preview.php',
			'inline_editor'             => true,
			'custom_settings_view_name' => 'ModuleSettingElegantView',
			'params'                    => array(
				array(
					'type'         => 'upload',
					'heading'      => esc_attr__( 'Upload Image', 'elegant-elements' ),
					'description'  => esc_attr__( 'Upload the image to be used in promo box.', 'elegant-elements' ),
					'param_name'   => 'image',
					'dynamic_data' => true,
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_attr__( 'Promo Title', 'elegant-elements' ),
					'param_name'   => 'title',
					'value'        => esc_attr__( 'Elegant Promo Box', 'elegant-elements' ),
					'placeholder'  => true,
					'dynamic_data' => true,
					'description'  => esc_attr__( 'Enter the text to be used as promo box title.', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the title size, H1-H6.', 'elegant-elements' ),
					'param_name'  => 'heading_size',
					'value'       => array(
						'h1' => 'H1',
						'h2' => 'H2',
						'h3' => 'H3',
						'h4' => 'H4',
						'h5' => 'H5',
						'h6' => 'H6',
					),
					'default'     => 'h2',
					'dependency'  => array(
						array(
							'element'  => 'title',
							'value'    => '',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Element Typography Override', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose if you want to keep the theme options global typography as default for this element or want to set custom. Controls typography options for all typography fields in this element.', 'elegant-elements' ),
					'param_name'  => 'element_typography',
					'default'     => 'custom',
					'value'       => array(
						'default' => esc_attr__( 'Default', 'elegant-elements' ),
						'custom'  => esc_attr__( 'Custom', 'elegant-elements' ),
					),
					'group'       => 'Typography',
				),
				array(
					'type'        => 'elegant_typography',
					'heading'     => esc_attr__( 'Title Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select typography for the title text.', 'elegant-elements' ),
					'param_name'  => 'typography_title',
					'value'       => '',
					'default'     => $default_typography['title'],
					'dependency'  => array(
						array(
							'element'  => 'element_typography',
							'value'    => 'default',
							'operator' => '!=',
						),
					),
					'group'       => 'Typography',
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Title Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for title text. ( In Pixel. )', 'elegant-elements' ),
					'param_name'  => 'title_font_size',
					'value'       => '28',
					'min'         => '12',
					'max'         => '100',
					'step'        => '1',
					'group'       => 'Typography',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Title Color', 'elegant-elements' ),
					'param_name'  => 'title_color',
					'value'       => '',
					'description' => esc_attr__( 'Controls the text color for promo box title.', 'elegant-elements' ),
				),
				array(
					'type'         => 'textarea',
					'heading'      => esc_attr__( 'Promo Description Text', 'elegant-elements' ),
					'param_name'   => 'description',
					'dynamic_data' => true,
					'value'        => esc_attr__( 'Your promo text goes here. You can inline edit it using Frontend Builder.', 'elegant-elements' ),
					'description'  => esc_attr__( 'Enter the text to be used as promo box description text.', 'elegant-elements' ),
				),
				array(
					'type'        => 'elegant_typography',
					'heading'     => esc_attr__( 'Description Text Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select typography for the description text.', 'elegant-elements' ),
					'param_name'  => 'typography_description',
					'value'       => '',
					'default'     => $default_typography['description'],
					'dependency'  => array(
						array(
							'element'  => 'element_typography',
							'value'    => 'default',
							'operator' => '!=',
						),
					),
					'group'       => 'Typography',
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Description Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for description text. In Pixel (px).', 'elegant-elements' ),
					'param_name'  => 'description_font_size',
					'value'       => '18',
					'min'         => '12',
					'max'         => '100',
					'step'        => '1',
					'group'       => 'Typography',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Description Color', 'elegant-elements' ),
					'param_name'  => 'description_color',
					'value'       => '',
					'description' => esc_attr__( 'Controls the text color for promo box description.', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Image Alignment', 'elegant-elements' ),
					'description' => esc_attr__( 'Select how you want to align the image with text.', 'elegant-elements' ),
					'param_name'  => 'image_align',
					'value'       => array(
						'left'  => __( 'Left', 'elegant-elements' ),
						'right' => __( 'Right', 'elegant-elements' ),
					),
					'default'     => 'left',
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Content Alignment', 'elegant-elements' ),
					'description' => esc_attr__( 'Select box content alighment including title, description and button.', 'elegant-elements' ),
					'param_name'  => 'content_align',
					'default'     => 'center',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'center' => esc_attr__( 'Center', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Promo Box Background Color', 'elegant-elements' ),
					'param_name'  => 'background_color',
					'value'       => 'rgba(247,247,247,0.6)',
					'placeholder' => true,
					'description' => esc_attr__( 'Controls the background color applied to the promo box.', 'elegant-elements' ),
					'group'       => 'Background',
				),
				array(
					'type'         => 'upload',
					'heading'      => esc_attr__( 'Promo Box Background Image', 'elegant-elements' ),
					'description'  => esc_attr__( 'Select the image to be used as background image for the promo box.', 'elegant-elements' ),
					'param_name'   => 'background_image',
					'dynamic_data' => true,
					'group'        => 'Background',
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Promo Box Background Image Position', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the postion of the background image for promo box.', 'elegant-elements' ),
					'param_name'  => 'background_position',
					'default'     => 'left top',
					'dependency'  => array(
						array(
							'element'  => 'background_image',
							'value'    => '',
							'operator' => '!=',
						),
					),
					'value'       => array(
						'left top'      => esc_attr__( 'Left Top', 'elegant-elements' ),
						'left center'   => esc_attr__( 'Left Center', 'elegant-elements' ),
						'left bottom'   => esc_attr__( 'Left Bottom', 'elegant-elements' ),
						'right top'     => esc_attr__( 'Right Top', 'elegant-elements' ),
						'right center'  => esc_attr__( 'Right Center', 'elegant-elements' ),
						'right bottom'  => esc_attr__( 'Right Bottom', 'elegant-elements' ),
						'center top'    => esc_attr__( 'Center Top', 'elegant-elements' ),
						'center center' => esc_attr__( 'Center Center', 'elegant-elements' ),
						'center bottom' => esc_attr__( 'Center Bottom', 'elegant-elements' ),
					),
					'group'       => 'Background',
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Promo Box Background Repeat', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose how the background image repeats for promo box.', 'elegant-elements' ),
					'param_name'  => 'background_repeat',
					'default'     => 'no-repeat',
					'dependency'  => array(
						array(
							'element'  => 'background_image',
							'value'    => '',
							'operator' => '!=',
						),
					),
					'value'       => array(
						'no-repeat' => esc_attr__( 'No Repeat', 'elegant-elements' ),
						'repeat'    => esc_attr__( 'Repeat Vertically and Horizontally', 'elegant-elements' ),
						'repeat-x'  => esc_attr__( 'Repeat Horizontally', 'elegant-elements' ),
						'repeat-y'  => esc_attr__( 'Repeat Vertically', 'elegant-elements' ),
					),
					'group'       => 'Background',
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Promo Box Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the height for promo box. In Pixel (px). eg. 400px', 'elegant-elements' ),
					'param_name'  => 'height',
					'value'       => '300',
					'min'         => '100',
					'max'         => '1000',
					'step'        => '1',
				),
				array(
					'type'        => 'textarea',
					'heading'     => esc_attr__( 'Button', 'elegant-elements' ),
					'param_name'  => 'element_content',
					'value'       => '',
					'description' => __( 'Click the link to generate button shortcode. Please remove the previous shortcode before generating new one.<br/><p><a href="#" class="elegant-elements-add-shortcode" data-type="fusion_button"  data-editor-clone-id="element_content" data-editor-id="element_content">Generate Button Shortcode</a></p>', 'elegant-elements' ),
				),
				array(
					'type'        => 'checkbox_button_set',
					'heading'     => esc_attr__( 'Element Visibility', 'elegant-elements' ),
					'param_name'  => 'hide_on_mobile',
					'value'       => fusion_builder_visibility_options( 'full' ),
					'default'     => fusion_builder_default_visibility( 'array' ),
					'description' => esc_attr__( 'Choose to show or hide the element on small, medium or large screens. You can choose more than one at a time.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'CSS Class', 'elegant-elements' ),
					'param_name'  => 'class',
					'value'       => '',
					'description' => esc_attr__( 'Add a class to the wrapping HTML element.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'CSS ID', 'elegant-elements' ),
					'param_name'  => 'id',
					'value'       => '',
					'description' => esc_attr__( 'Add an ID to the wrapping HTML element.', 'elegant-elements' ),
				),
			),
		)
	);
}

add_action( 'fusion_builder_before_init', 'map_elegant_elements_promo_box', 99 );
