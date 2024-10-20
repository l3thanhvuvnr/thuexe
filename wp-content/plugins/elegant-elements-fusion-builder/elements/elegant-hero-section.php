<?php
if ( fusion_is_element_enabled( 'iee_hero_section' ) && ! class_exists( 'IEE_Hero_Section' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 3.6.0
	 */
	class IEE_Hero_Section extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 3.6.0
		 * @var array
		 */
		protected $args;

		/**
		 * Constructor.
		 *
		 * @since 3.6.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-hero-section', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_elegant-hero-section-content', array( $this, 'attr_content' ) );
			add_filter( 'fusion_attr_elegant-hero-section-heading', array( $this, 'attr_heading' ) );
			add_filter( 'fusion_attr_elegant-hero-section-description', array( $this, 'attr_description' ) );
			add_filter( 'fusion_attr_elegant-hero-section-image-src', array( $this, 'attr_image_src' ) );

			add_shortcode( 'iee_hero_section', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 3.6.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			if ( 'lottie' === $args['secondary_content'] ) {
				// Enqueue Lottie Player js.
				wp_enqueue_script( 'infi-lottie-player' );
			}

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'heading_text'             => '',
					'heading_size'             => 'h2',
					'description_text'         => '',
					'hero_image'               => '',
					'image_retina'             => '',
					'secondary_content'        => 'image',
					'lottie_image_shortcode'   => '',
					'heading_font_size'        => '48',
					'heading_font_size_medium' => '',
					'heading_font_size_small'  => '',
					'description_font_size'    => '16',
					'heading_text_color'       => '#333333',
					'description_text_color'   => '#666666',
					'space_after_heading'      => '20',
					'space_after_description'  => '20',
					'button_1'                 => '',
					'button_2'                 => '',
					'image_position'           => 'right',
					'content_padding'          => '',
					'section_padding'          => '',
					'content_padding_top'      => '',
					'content_padding_right'    => '',
					'content_padding_bottom'   => '',
					'content_padding_left'     => '',
					'section_padding_top'      => '',
					'section_padding_right'    => '',
					'section_padding_bottom'   => '',
					'section_padding_left'     => '',
					'hide_on_mobile'           => fusion_builder_default_visibility( 'string' ),
					'class'                    => '',
					'id'                       => '',
				),
				$args
			);

			if ( ! isset( $args['section_padding'] ) ) {
				$section_padding_values           = array();
				$section_padding_values['top']    = ( isset( $args['section_padding_top'] ) && '' !== $args['section_padding_top'] ) ? FusionBuilder::validate_shortcode_attr_value( $args['section_padding_top'], 'px' ) : '0px';
				$section_padding_values['right']  = ( isset( $args['section_padding_right'] ) && '' !== $args['section_padding_right'] ) ? FusionBuilder::validate_shortcode_attr_value( $args['section_padding_right'], 'px' ) : '0px';
				$section_padding_values['bottom'] = ( isset( $args['section_padding_bottom'] ) && '' !== $args['section_padding_bottom'] ) ? FusionBuilder::validate_shortcode_attr_value( $args['section_padding_bottom'], 'px' ) : '0px';
				$section_padding_values['left']   = ( isset( $args['section_padding_left'] ) && '' !== $args['section_padding_left'] ) ? FusionBuilder::validate_shortcode_attr_value( $args['section_padding_left'], 'px' ) : '0px';

				$defaults['section_padding'] = implode( ' ', $section_padding_values );
				$defaults['section_padding'] = trim( $defaults['section_padding'] );
			}

			if ( ! isset( $args['content_padding'] ) ) {
				$content_padding_values           = array();
				$content_padding_values['top']    = ( isset( $args['content_padding_top'] ) && '' !== $args['content_padding_top'] ) ? FusionBuilder::validate_shortcode_attr_value( $args['content_padding_top'], 'px' ) : '0px';
				$content_padding_values['right']  = ( isset( $args['content_padding_right'] ) && '' !== $args['content_padding_right'] ) ? FusionBuilder::validate_shortcode_attr_value( $args['content_padding_right'], 'px' ) : '0px';
				$content_padding_values['bottom'] = ( isset( $args['content_padding_bottom'] ) && '' !== $args['content_padding_bottom'] ) ? FusionBuilder::validate_shortcode_attr_value( $args['content_padding_bottom'], 'px' ) : '0px';
				$content_padding_values['left']   = ( isset( $args['content_padding_left'] ) && '' !== $args['content_padding_left'] ) ? FusionBuilder::validate_shortcode_attr_value( $args['content_padding_left'], 'px' ) : '0px';

				$defaults['content_padding'] = implode( ' ', $content_padding_values );
				$defaults['content_padding'] = trim( $defaults['content_padding'] );
			}

			$defaults = apply_filters( 'fusion_builder_default_args', $defaults, 'iee_hero_section', $args );

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/hero-section/elegant-hero-section.php' ) ) {
				include locate_template( 'templates/hero-section/elegant-hero-section.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/hero-section/elegant-hero-section.php';
			}

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 3.6.0
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-hero-section',
				'style' => '',
			);

			$attr['class'] .= ' image-position-' . $this->args['image_position'];

			$attr = fusion_builder_visibility_atts( $this->args['hide_on_mobile'], $attr );

			if ( '' !== $this->args['section_padding'] ) {
				$padding        = FusionBuilder::validate_shortcode_attr_value( $this->args['section_padding'], 'px' );
				$attr['style'] .= 'padding:' . $padding . ';';
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
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 3.6.0
		 * @return array
		 */
		public function attr_content() {
			$attr = array(
				'class' => 'elegant-hero-section-content',
				'style' => '',
			);

			if ( '' !== $this->args['content_padding'] ) {
				$padding        = FusionBuilder::validate_shortcode_attr_value( $this->args['content_padding'], 'px' );
				$attr['style'] .= 'padding:' . $padding . ';';
			}

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 3.6.0
		 * @return array
		 */
		public function attr_heading() {
			$attr = array(
				'class' => 'elegant-hero-section-heading',
				'style' => '',
			);

			$font_size      = FusionBuilder::validate_shortcode_attr_value( $this->args['heading_font_size'], 'px' );
			$attr['style'] .= 'font-size:' . $font_size . ';';

			if ( '' !== $this->args['heading_font_size_medium'] ) {
				$font_size_medium = FusionBuilder::validate_shortcode_attr_value( $this->args['heading_font_size_medium'], 'px' );
				$attr['style']   .= '--medium-font-size:' . $font_size_medium . ';';
			}

			if ( '' !== $this->args['heading_font_size_small'] ) {
				$font_size_small = FusionBuilder::validate_shortcode_attr_value( $this->args['heading_font_size_small'], 'px' );
				$attr['style']  .= '--small-font-size:' . $font_size_small . ';';
			}

			if ( isset( $this->args['heading_text_color'] ) ) {
				$attr['style'] .= 'color:' . $this->args['heading_text_color'] . ';';
			}

			if ( isset( $this->args['space_after_heading'] ) ) {
				$spacing        = FusionBuilder::validate_shortcode_attr_value( $this->args['space_after_heading'], 'px' );
				$attr['style'] .= 'margin-bottom:' . $spacing . ';';
			}

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 3.6.0
		 * @return array
		 */
		public function attr_description() {
			$attr = array(
				'class' => 'elegant-hero-section-description',
				'style' => '',
			);

			$font_size      = FusionBuilder::validate_shortcode_attr_value( $this->args['description_font_size'], 'px' );
			$attr['style'] .= 'font-size:' . $font_size . ';';

			if ( isset( $this->args['description_text_color'] ) ) {
				$attr['style'] .= 'color:' . $this->args['description_text_color'] . ';';
			}

			if ( isset( $this->args['space_after_description'] ) ) {
				$spacing        = FusionBuilder::validate_shortcode_attr_value( $this->args['space_after_description'], 'px' );
				$attr['style'] .= 'margin-bottom:' . $spacing . ';';
			}

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 3.6.0
		 * @return array
		 */
		public function attr_image_src() {
			$attr = array(
				'class' => '',
			);

			$attr['src'] = $this->args['hero_image'];
			$attr['alt'] = basename( $this->args['hero_image'] );

			if ( isset( $this->args['image_retina'] ) && '' !== $this->args['image_retina'] ) {
				$attr['srcset']  = $this->args['image'] . ' 1x, ';
				$attr['srcset'] .= $this->args['image_retina'] . ' 2x ';
			}

			return $attr;
		}
	}

	new IEE_Hero_Section();
} // End if().

/**
 * Map shortcode for hero_section.
 *
 * @since 3.6.0
 * @return void
 */
function map_elegant_elements_hero_section() {
	global $fusion_settings;

	fusion_builder_map(
		array(
			'name'                      => esc_attr__( 'Elegant Hero Section', 'elegant-elements' ),
			'shortcode'                 => 'iee_hero_section',
			'icon'                      => 'fa-digital-tachograph fas icon-hero-section',
			'allow_generator'           => true,
			'custom_settings_view_name' => 'ModuleSettingElegantView',
			'inline_editor'             => true,
			'inline_editor_shortcodes'  => true,
			'front-end'                 => ELEGANT_ELEMENTS_PLUGIN_DIR . '/elements/front-end/templates/infi-hero-section-preview.php',
			'params'                    => array(
				array(
					'type'         => 'textfield',
					'heading'      => esc_attr__( 'Heading Text', 'elegant-elements' ),
					'param_name'   => 'heading_text',
					'value'        => esc_attr__( 'Elegant Elements Hero Section', 'elegant-elements' ),
					'placeholder'  => true,
					'dynamic_data' => true,
					'description'  => esc_attr__( 'Enter the text to be used as hero section heading.', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Heading Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the heading size, H1-H6.', 'elegant-elements' ),
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
							'element'  => 'heading_text',
							'value'    => '',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'         => 'textarea',
					'heading'      => esc_attr__( 'Description Text', 'elegant-elements' ),
					'param_name'   => 'description_text',
					'value'        => esc_attr__( 'Hero section description text goes here.', 'elegant-elements' ),
					'placeholder'  => true,
					'dynamic_data' => true,
					'description'  => esc_attr__( 'Hero section description text goes here.', 'elegant-elements' ),
				),
				array(
					'type'        => 'raw_textarea',
					'heading'     => esc_attr__( 'Button 1 Shortcode', 'elegant-elements' ),
					'param_name'  => 'button_1',
					'value'       => '',
					'description' => __( 'Click the link to generate button 1 shortcode. Please remove the previous shortcode before generating new one.<br/><p><a href="#" class="elegant-elements-add-shortcode" data-type="fusion_button" data-base="hero_section" data-editor-id="button_1">Generate Button 1 Shortcode</a></p>', 'elegant-elements' ),
				),
				array(
					'type'        => 'raw_textarea',
					'heading'     => esc_attr__( 'Button 2 Shortcode', 'elegant-elements' ),
					'param_name'  => 'button_2',
					'value'       => '',
					'description' => __( 'Click the link to generate button 2 shortcode. Please remove the previous shortcode before generating new one.<br/><p><a href="#" class="elegant-elements-add-shortcode" data-type="fusion_button" data-base="hero_section" data-editor-id="button_2">Generate Button 2 Shortcode</a></p>', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Secondary Content', 'elegant-elements' ),
					'description' => __( 'Select what you want to place in the secondary content place.', 'elegant-elements' ),
					'default'     => 'image',
					'param_name'  => 'secondary_content',
					'value'       => array(
						'image'  => esc_attr__( 'Image', 'elegant-elements' ),
						'lottie' => esc_attr__( 'Lottie', 'elegant-elements' ),
						'custom' => esc_attr__( 'Custom', 'elegant-elements' ),
					),
				),
				array(
					'type'         => 'upload',
					'heading'      => esc_attr__( 'Hero Image', 'elegant-elements' ),
					'description'  => esc_attr__( 'Upload the image to be used as the main hero image.', 'elegant-elements' ),
					'param_name'   => 'hero_image',
					'dynamic_data' => true,
					'dependency'   => array(
						array(
							'element'  => 'secondary_content',
							'value'    => 'image',
							'operator' => '==',
						),
					),
				),
				array(
					'type'         => 'upload',
					'heading'      => esc_attr__( 'Retina Image', 'elegant-elements' ),
					'description'  => esc_attr__( 'Upload or select the image to be used on retina devices as hero image.', 'elegant-elements' ),
					'param_name'   => 'image_retina',
					'dynamic_data' => true,
					'dependency'   => array(
						array(
							'element'  => 'secondary_content',
							'value'    => 'image',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'raw_textarea',
					'heading'     => esc_attr__( 'Lottie Image Shortcode', 'elegant-elements' ),
					'param_name'  => 'lottie_image_shortcode',
					'value'       => '',
					'description' => __( 'Click the link to generate Lottie Animated Image shortcode. Please remove the previous shortcode before generating new one.<br/><p><a href="#" class="elegant-elements-add-shortcode" data-type="iee_lottie_animated_image" data-base="hero_section" data-editor-id="lottie_image_shortcode">Generate Lottie Image Shortcode</a></p>', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'secondary_content',
							'value'    => 'lottie',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'tinymce',
					'heading'     => esc_attr__( 'Custom Content', 'elegant-elements' ),
					'description' => esc_attr__( 'Add your custom content or use Avada Shortcode Generator to add any other element.', 'elegant-elements' ),
					'param_name'  => 'element_content',
					'value'       => '',
					'dependency'  => array(
						array(
							'element'  => 'secondary_content',
							'value'    => 'custom',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Secondary Content Position', 'elegant-elements' ),
					'description' => __( 'Select the position for the secondary content.', 'elegant-elements' ),
					'default'     => 'right',
					'param_name'  => 'image_position',
					'value'       => array(
						'left'   => esc_attr__( 'Left Side of Content', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right Side of Content', 'elegant-elements' ),
						'top'    => esc_attr__( 'Top Side of Content', 'elegant-elements' ),
						'bottom' => esc_attr__( 'Bottom Side of Content', 'elegant-elements' ),
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Heading Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for heading text. ( In Pixel. )', 'elegant-elements' ),
					'param_name'  => 'heading_font_size',
					'value'       => '48',
					'min'         => '10',
					'max'         => '100',
					'step'        => '1',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
					'responsive'  => array(
						'state' => 'large',
					),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Description Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for description text. ( In Pixel. )', 'elegant-elements' ),
					'param_name'  => 'description_font_size',
					'value'       => '16',
					'min'         => '10',
					'max'         => '50',
					'step'        => '1',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Heading Text Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the text color for the content box heading text.', 'elegant-elements' ),
					'param_name'  => 'heading_text_color',
					'value'       => '#333333',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Description Text Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the text color for the description text.', 'elegant-elements' ),
					'param_name'  => 'description_text_color',
					'value'       => '#666666',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Space Between Heading and Description Text', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the space between heading and the description text. ( In Pixel. )', 'elegant-elements' ),
					'param_name'  => 'space_after_heading',
					'value'       => '20',
					'min'         => '0',
					'max'         => '100',
					'step'        => '1',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Space Between Description and Buttons', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the space between the description text and the buttons. ( In Pixel. )', 'elegant-elements' ),
					'param_name'  => 'space_after_description',
					'value'       => '20',
					'min'         => '0',
					'max'         => '100',
					'step'        => '1',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'             => 'dimension',
					'heading'          => esc_attr__( 'Content Padding', 'elegant-elements' ),
					'description'      => esc_attr__( 'Spacing around the content area. In pixels (px), ex: 10px.', 'elegant-elements' ),
					'param_name'       => 'content_padding',
					'remove_from_atts' => true,
					'value'            => array(
						'content_padding_top'    => '',
						'content_padding_right'  => '',
						'content_padding_bottom' => '',
						'content_padding_left'   => '',
					),
					'group'            => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'             => 'dimension',
					'heading'          => esc_attr__( 'Section Wrapper Padding', 'elegant-elements' ),
					'description'      => esc_attr__( 'Spacing around the section. In pixels (px), ex: 10px.', 'elegant-elements' ),
					'param_name'       => 'section_padding',
					'remove_from_atts' => true,
					'value'            => array(
						'section_padding_top'    => '',
						'section_padding_right'  => '',
						'section_padding_bottom' => '',
						'section_padding_left'   => '',
					),
					'group'            => esc_attr__( 'Design', 'elegant-elements' ),
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_hero_section', 99 );
