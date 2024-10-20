<?php
if ( fusion_is_element_enabled( 'iee_text_path' ) && ! class_exists( 'IEE_Text_Path' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 3.5.0
	 */
	class IEE_Text_Path extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 3.5.0
		 * @var array
		 */
		protected $args;

		/**
		 * Constructor.
		 *
		 * @since 3.5.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-text-path-wrapper', array( $this, 'wrapper_attr' ) );
			add_filter( 'fusion_attr_elegant-text-path', array( $this, 'attr' ) );

			add_shortcode( 'iee_text_path', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 3.5.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'text'                 => esc_attr__( 'Add your text for the text path.', 'elegant-elements' ),
					'shape'                => 'wave',
					'link'                 => '',
					'width'                => '500',
					'element_typography'   => 'default',
					'typography_path_text' => '',
					'title_font_size'      => '',
					'text_letter_spacing'  => '0',
					'text_word_spacing'    => '0',
					'text_color'           => '',
					'alignment'            => 'left',
					'hide_on_mobile'       => fusion_builder_default_visibility( 'string' ),
					'class'                => '',
					'id'                   => '',
				),
				$args
			);

			$defaults = apply_filters( 'fusion_builder_default_args', $defaults, 'iee_text_path', $args );

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/text-path/elegant-text-path.php' ) ) {
				include locate_template( 'templates/text-path/elegant-text-path.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/text-path/elegant-text-path.php';
			}

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 3.5.0
		 * @return array
		 */
		public function wrapper_attr() {
			$attr = array(
				'class' => 'elegant-text-path-wrapper elegant-align-' . $this->args['alignment'],
				'style' => '',
			);

			$attr = fusion_builder_visibility_atts( $this->args['hide_on_mobile'], $attr );

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 3.5.0
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-text-path',
				'style' => '',
			);

			$attr['class'] .= ' shape-' . $this->args['shape'];

			$width          = FusionBuilder::validate_shortcode_attr_value( $this->args['width'], 'px' );
			$attr['style'] .= 'width:100%;';
			$attr['style'] .= '--width:' . $width . ';';

			if ( isset( $this->args['typography_path_text'] ) && '' !== $this->args['typography_path_text'] ) {
				$typography      = $this->args['typography_path_text'];
				$text_typography = elegant_get_typography_css( $typography );

				$attr['style'] .= $text_typography;
			}

			if ( isset( $this->args['title_font_size'] ) && '' !== $this->args['title_font_size'] ) {
				$attr['style'] .= 'font-size:' . FusionBuilder::validate_shortcode_attr_value( $this->args['title_font_size'], 'px' ) . ';';
			}

			if ( isset( $this->args['text_letter_spacing'] ) && '' !== $this->args['text_letter_spacing'] ) {
				$attr['style'] .= 'letter-spacing:' . FusionBuilder::validate_shortcode_attr_value( $this->args['text_letter_spacing'], 'px' ) . ';';
			}

			if ( isset( $this->args['text_word_spacing'] ) && '' !== $this->args['text_word_spacing'] ) {
				$attr['style'] .= '--word-spacing:' . FusionBuilder::validate_shortcode_attr_value( $this->args['text_word_spacing'], 'px' ) . ';';
			}

			$attr['style'] .= 'color:' . $this->args['text_color'] . ';';
			$attr['style'] .= '--text-color:' . $this->args['text_color'] . ';';

			if ( isset( $this->args['class'] ) ) {
				$attr['class'] .= ' ' . $this->args['class'];
			}

			if ( isset( $this->args['id'] ) ) {
				$attr['id'] = $this->args['id'];
			}

			return $attr;
		}
	}

	new IEE_Text_Path();
} // End if().

/**
 * Map shortcode for text_path.
 *
 * @since 3.5.0
 * @return void
 */
function map_elegant_elements_text_path() {
	global $fusion_settings;

	// Get default typography for title and description.
	$default_typography = elegant_get_default_typography();

	fusion_builder_map(
		array(
			'name'      => esc_attr__( 'Elegant Text Path', 'elegant-elements' ),
			'shortcode' => 'iee_text_path',
			'icon'      => 'fa-draw-polygon fas text-path-icon',
			'front-end' => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-text-path-preview.php',
			'params'    => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Text', 'elegant-elements' ),
					'description' => esc_attr__( 'Add your text for the text path.', 'elegant-elements' ),
					'param_name'  => 'text',
					'value'       => esc_attr__( 'Add your text for the text path.', 'elegant-elements' ),
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Path Shape', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the path shape.', 'elegant-elements' ),
					'param_name'  => 'shape',
					'default'     => 'wave',
					'value'       => array(
						'arc-top'     => __( 'Arc - Top', 'elegant-elements' ),
						'arc-bottom'  => __( 'Arc - Bottom', 'elegant-elements' ),
						'circle'      => __( 'Circle', 'elegant-elements' ),
						'line-top'    => __( 'Line - Top', 'elegant-elements' ),
						'line-bottom' => __( 'Line - Bottom', 'elegant-elements' ),
						'oval'        => __( 'Oval', 'elegant-elements' ),
						'spiral'      => __( 'Spiral', 'elegant-elements' ),
						'wave'        => __( 'Wave', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'link_selector',
					'heading'     => esc_attr__( 'URL to Open', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter the url you want to open on the text click.', 'elegant-elements' ),
					'param_name'  => 'link',
					'value'       => '',
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Width', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css width for the text path. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'width',
					'value'       => '500',
					'min'         => '100',
					'max'         => '2000',
					'step'        => '1',
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Alignment', 'elegant-elements' ),
					'param_name'  => 'alignment',
					'default'     => 'left',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'center' => esc_attr__( 'Center', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Align the text path to left, right or center.', 'elegant-elements' ),
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
					'heading'     => esc_attr__( 'Text Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select typography for the path text.', 'elegant-elements' ),
					'param_name'  => 'typography_path_text',
					'value'       => '',
					'default'     => $default_typography['title'],
					'group'       => 'Typography',
					'dependency'  => array(
						array(
							'element'  => 'element_typography',
							'value'    => 'default',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Text Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for path text. ( In Pixel. )', 'elegant-elements' ),
					'param_name'  => 'title_font_size',
					'value'       => '18',
					'min'         => '10',
					'max'         => '100',
					'step'        => '1',
					'group'       => 'Typography',
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Letter Spacing', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the letter spacing for the path text. ( In Pixel. )', 'elegant-elements' ),
					'param_name'  => 'text_letter_spacing',
					'value'       => '0',
					'min'         => '0',
					'max'         => '10',
					'step'        => '1',
					'group'       => 'Typography',
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Word Spacing', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the word spacing for the path text. ( In Pixel. )', 'elegant-elements' ),
					'param_name'  => 'text_word_spacing',
					'value'       => '0',
					'min'         => '0',
					'max'         => '10',
					'step'        => '1',
					'group'       => 'Typography',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Text Color', 'elegant-elements' ),
					'param_name'  => 'text_color',
					'value'       => '',
					'description' => esc_attr__( 'Controls the text color for path text.', 'elegant-elements' ),
					'group'       => 'Typography',
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_text_path', 99 );
