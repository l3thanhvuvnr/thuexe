<?php
if ( fusion_is_element_enabled( 'iee_special_heading' ) && ! class_exists( 'IEE_Special_Heading' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class IEE_Special_Heading extends Fusion_Element {

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

			add_filter( 'fusion_attr_elegant-special-heading', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_elegant-special-heading-wrapper', array( $this, 'attr_heading_wrapper' ) );
			add_filter( 'fusion_attr_elegant-special-heading-title', array( $this, 'title_attr' ) );
			add_filter( 'fusion_attr_elegant-special-heading-description', array( $this, 'description_attr' ) );

			add_shortcode( 'iee_special_heading', array( $this, 'render' ) );
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

			// Get default typography for title and description.
			$default_typography = elegant_get_default_typography();

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'title'                    => '',
					'heading_size'             => '',
					'element_typography'       => '',
					'typography_title'         => '',
					'title_color'              => '',
					'title_font_size'          => '',
					'description'              => '',
					'typography_description'   => '',
					'description_color'        => '',
					'description_font_size'    => '',
					'background_color'         => '',
					'background_image'         => '',
					'background_position'      => '',
					'background_repeat'        => '',
					'height'                   => '',
					'container_padding'        => '',
					'container_padding_mobile' => '',
					'alignment'                => '',
					'element_content'          => '',
					'separator_position'       => '',
					'class'                    => '',
					'id'                       => '',
					'hide_on_mobile'           => fusion_builder_default_visibility( 'string' ),
				),
				$args
			);

			$args = $defaults;

			if ( ! isset( $args['container_padding'] ) ) {
				$padding_values           = array();
				$padding_values['top']    = ( isset( $args['padding_top'] ) && '' !== $args['padding_top'] ) ? $args['padding_top'] : '0px';
				$padding_values['right']  = ( isset( $args['padding_right'] ) && '' !== $args['padding_right'] ) ? $args['padding_right'] : '0px';
				$padding_values['bottom'] = ( isset( $args['padding_bottom'] ) && '' !== $args['padding_bottom'] ) ? $args['padding_bottom'] : '0px';
				$padding_values['left']   = ( isset( $args['padding_left'] ) && '' !== $args['padding_left'] ) ? $args['padding_left'] : '0px';

				$args['container_padding'] = implode( ' ', $padding_values );
			}

			if ( ! isset( $args['container_padding_mobile'] ) ) {
				$padding_values           = array();
				$padding_values['top']    = ( isset( $args['mobile_padding_top'] ) && '' !== $args['mobile_padding_top'] ) ? $args['mobile_padding_top'] : '0px';
				$padding_values['right']  = ( isset( $args['mobile_padding_right'] ) && '' !== $args['mobile_padding_right'] ) ? $args['mobile_padding_right'] : '0px';
				$padding_values['bottom'] = ( isset( $args['mobile_padding_bottom'] ) && '' !== $args['mobile_padding_bottom'] ) ? $args['mobile_padding_bottom'] : '0px';
				$padding_values['left']   = ( isset( $args['mobile_padding_left'] ) && '' !== $args['mobile_padding_left'] ) ? $args['mobile_padding_left'] : '0px';

				$args['container_padding_mobile'] = implode( ' ', $padding_values );
			}

			if ( isset( $args['element_typography'] ) && 'default' === $args['element_typography'] ) {
				$args['typography_title']       = $default_typography['title'];
				$args['typography_description'] = $default_typography['description'];
			}

			$this->args = $args;

			$html = '';

			if ( '' !== locate_template( 'templates/special-heading/elegant-special-heading.php' ) ) {
				include locate_template( 'templates/special-heading/elegant-special-heading.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/special-heading/elegant-special-heading.php';
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
				'class' => 'elegant-special-heading',
				'style' => '',
			);

			$attr['class'] .= ' special-heading-align-' . $this->args['alignment'];

			$attr = fusion_builder_visibility_atts( $this->args['hide_on_mobile'], $attr );

			if ( isset( $this->args['container_padding_mobile'] ) && '' !== $this->args['container_padding_mobile'] ) {
				$attr['style'] .= '--padding:' . FusionBuilder::validate_shortcode_attr_value( $this->args['container_padding_mobile'], 'px' ) . ';';
			}

			if ( isset( $this->args['height'] ) && '' !== $this->args['height'] ) {
				$attr['style'] .= 'height:' . FusionBuilder::validate_shortcode_attr_value( $this->args['height'], 'px' ) . ';';
			}

			if ( isset( $this->args['background_color'] ) && '' !== $this->args['background_color'] ) {
				$attr['style'] .= 'background-color:' . $this->args['background_color'] . ';';
			}

			if ( isset( $this->args['background_image'] ) && '' !== $this->args['background_image'] ) {
				$attr['style'] .= 'background-image: url("' . $this->args['background_image'] . '");';
				$attr['style'] .= 'background-position:' . $this->args['background_position'] . ';';
				$attr['style'] .= 'background-repeat:' . $this->args['background_repeat'] . ';';
				$attr['style'] .= 'background-blend-mode: overlay;';
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
		 * @since 2.2
		 * @return array
		 */
		public function attr_heading_wrapper() {
			$attr = array(
				'class' => 'elegant-special-heading-wrapper',
				'style' => '',
			);

			if ( isset( $this->args['container_padding'] ) && '' !== $this->args['container_padding'] ) {
				$attr['style'] .= 'padding:' . FusionBuilder::validate_shortcode_attr_value( $this->args['container_padding'], 'px' ) . ';';
			}

			if ( isset( $this->args['container_padding_mobile'] ) && '' !== $this->args['container_padding_mobile'] ) {
				$attr['style'] .= '--padding:' . FusionBuilder::validate_shortcode_attr_value( $this->args['container_padding_mobile'], 'px' ) . ';';
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
				'class' => 'elegant-special-heading-title',
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
		public function description_attr() {
			$attr = array(
				'class' => 'elegant-special-heading-description',
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

	new IEE_Special_Heading();
} // End if().


/**
 * Map shortcode for special_heading.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_special_heading() {
	global $fusion_settings;

	// Get default typography for title and description.
	$default_typography = elegant_get_default_typography();

	fusion_builder_map(
		array(
			'name'                      => esc_attr__( 'Elegant Special Heading', 'elegant-elements' ),
			'shortcode'                 => 'iee_special_heading',
			'icon'                      => 'icon-heading',
			'preview'                   => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/previews/infi-special-heading-preview.php',
			'preview_id'                => 'elegant-elements-module-infi-special-heading-preview-template',
			'front-end'                 => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-special-heading-preview.php',
			'inline_editor'             => true,
			'custom_settings_view_name' => 'ModuleSettingElegantView',
			'params'                    => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Heading Title', 'elegant-elements' ),
					'param_name'  => 'title',
					'value'       => esc_attr__( 'Elegant Special Heading', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter the text for the heading title.', 'elegant-elements' ),
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
					'heading'     => esc_attr__( 'Heading Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the typography for the heading title.', 'elegant-elements' ),
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
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Title Color', 'elegant-elements' ),
					'param_name'  => 'title_color',
					'value'       => '',
					'description' => esc_attr__( 'Select the color for the heading title.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
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
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
				),
				array(
					'type'        => 'textarea',
					'heading'     => esc_attr__( 'Heading Description', 'elegant-elements' ),
					'param_name'  => 'description',
					'value'       => esc_attr__( 'Your special heading description text goes here. You can inline edit it using Frontend Builder.', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter the text for the heading description.', 'elegant-elements' ),
				),
				array(
					'type'        => 'elegant_typography',
					'heading'     => esc_attr__( 'Description Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the typography for the heading description.', 'elegant-elements' ),
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
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Description Color', 'elegant-elements' ),
					'param_name'  => 'description_color',
					'value'       => '',
					'description' => esc_attr__( 'Select the color for the heading description.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
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
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Heading Container Background Color', 'elegant-elements' ),
					'param_name'  => 'background_color',
					'value'       => '',
					'description' => esc_attr__( 'Select the color for the heading container background.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'upload',
					'heading'     => esc_attr__( 'Heading Container Background Image', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the image to be used as background image for the heading container.', 'elegant-elements' ),
					'param_name'  => 'background_image',
					'group'       => 'Design',
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Heading Container Background Image Position', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the postion of the background image for heading container.', 'elegant-elements' ),
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
					'group'       => 'Design',
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Heading Container Background Repeat', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose how the background image repeats for heading container.', 'elegant-elements' ),
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
					'group'       => 'Design',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Heading Container Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css height To be applied for the heading container. In Pixel (px). eg. 250px.', 'elegant-elements' ),
					'param_name'  => 'height',
					'value'       => '',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'             => 'dimension',
					'remove_from_atts' => true,
					'heading'          => esc_attr__( 'Heading Container Padding', 'elegant-elements' ),
					'param_name'       => 'container_padding',
					'value'            => array(
						'padding_top'    => '',
						'padding_right'  => '',
						'padding_bottom' => '',
						'padding_left'   => '',
					),
					'description'      => esc_attr__( 'Enter padding to add space around heading container. In Pixels (px) eg. 10px.', 'elegant-elements' ),
					'group'            => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'             => 'dimension',
					'remove_from_atts' => true,
					'heading'          => esc_attr__( 'Heading Container Padding for Mobile', 'elegant-elements' ),
					'param_name'       => 'container_padding_mobile',
					'value'            => array(
						'mobile_padding_top'    => '',
						'mobile_padding_right'  => '',
						'mobile_padding_bottom' => '',
						'mobile_padding_left'   => '',
					),
					'description'      => esc_attr__( 'Enter padding to add space around heading container on mobile devices. In Pixels (px) eg. 10px.', 'elegant-elements' ),
					'group'            => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Heading Alignment', 'elegant-elements' ),
					'param_name'  => 'alignment',
					'default'     => 'center',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'center' => esc_attr__( 'Center', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Select the heading title alignment.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textarea',
					'heading'     => esc_attr__( 'Separator', 'elegant-elements' ),
					'param_name'  => 'element_content',
					'value'       => '',
					'description' => __( 'Click the link to generate separator shortcode. Please remove the previous shortcode before generating new one.<br/><p><a href="#" class="elegant-elements-add-shortcode" data-type="fusion_separator" data-editor-clone-id="element_content" data-editor-id="element_content">Generate Separator Shortcode</a></p>', 'elegant-elements' ),
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Separator Position', 'elegant-elements' ),
					'param_name'  => 'separator_position',
					'default'     => 'after_heading',
					'value'       => array(
						'above_heading'    => esc_attr__( 'Above Heading Text', 'elegant-elements' ),
						'after_heading'    => esc_attr__( 'Between Heading and Description', 'elegant-elements' ),
						'after_decription' => esc_attr__( 'After Description', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Select the heading title alignment.', 'elegant-elements' ),
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_special_heading', 99 );
