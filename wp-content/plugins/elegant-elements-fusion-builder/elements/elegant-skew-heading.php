<?php
if ( fusion_is_element_enabled( 'iee_skew_heading' ) && ! class_exists( 'IEE_Skew_Heading' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 2.0
	 */
	class IEE_Skew_Heading extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 2.0
		 * @var array
		 */
		protected $args;

		/**
		 * Constructor.
		 *
		 * @since 2.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-skew-heading-wrapper', array( $this, 'wrapper_attr' ) );
			add_filter( 'fusion_attr_elegant-skew-heading', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_elegant-skew-heading-text', array( $this, 'heading_attr' ) );

			add_shortcode( 'iee_skew_heading', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 2.0
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
				'heading_text'       => '',
				'heading_tag'        => 'h2',
				'skew_direction'     => 'left',
				'element_typography' => 'custom',
				'typography_heading' => $default_typography['title'],
				'heading_font_size'  => '',
				'heading_color'      => '',
				'background_color_1' => '',
				'background_color_2' => '',
				'gradient_direction' => '',
				'hide_on_mobile'     => '',
				'class'              => '',
				'id'                 => '',
				),
				$args
			);

			$defaults = apply_filters( 'fusion_builder_default_args', $defaults, 'iee_skew_heading', $args );

			if ( 'default' === $defaults['element_typography'] ) {
				$defaults['typography_title']       = $default_typography['title'];
				$defaults['typography_description'] = $default_typography['description'];
			}

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/skew-heading/elegant-skew-heading.php' ) ) {
				include locate_template( 'templates/skew-heading/elegant-skew-heading.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/skew-heading/elegant-skew-heading.php';
			}

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 2.0
		 * @return array
		 */
		public function wrapper_attr() {
			$attr = array(
				'class' => 'elegant-skew-heading-wrapper',
				'style' => '',
			);

			$attr = fusion_builder_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$attr['style'] .= '--background_color_1:' . $this->args['background_color_1'] . ';';
			$attr['style'] .= '--background_color_2:' . ( ( $this->args['background_color_2'] ) ? $this->args['background_color_2'] : $this->args['background_color_1'] ) . ';';

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
		 * @since 2.0
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-skew-heading',
				'style' => '',
			);

			$attr['class'] .= ' elegant-skew-direction-' . $this->args['skew_direction'];

			if ( $this->args['background_color_1'] && '' !== $this->args['background_color_1'] && '' === $this->args['background_color_2'] ) {
				$attr['style'] .= 'background:' . $this->args['background_color_1'] . ';';
			}

			if ( '' !== $this->args['background_color_1'] && '' !== $this->args['background_color_2'] ) {
				$attr['style'] .= 'background: ' . $this->get_gradient_color() . ';';
			}

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 2.0
		 * @return array
		 */
		public function heading_attr() {
			$attr = array(
				'class' => 'elegant-skew-heading-text',
				'style' => '',
			);

			if ( isset( $this->args['typography_heading'] ) && '' !== $this->args['typography_heading'] ) {
				$typography       = $this->args['typography_heading'];
				$title_typography = elegant_get_typography_css( $typography );

				$attr['style'] .= $title_typography;
			}

			if ( isset( $this->args['heading_color'] ) && '' !== $this->args['heading_color'] ) {
				$attr['style'] .= 'color:' . $this->args['heading_color'] . ';';
			}

			if ( isset( $this->args['heading_font_size'] ) && '' !== $this->args['heading_font_size'] ) {
				$attr['style'] .= 'font-size:' . FusionBuilder::validate_shortcode_attr_value( $this->args['heading_font_size'], 'px' ) . ';';
			}

			return $attr;
		}

		/**
		 * Generates and returns the gradient color for heading.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return array
		 */
		public function get_gradient_color() {
			$gradient_color_1   = $this->args['background_color_1'];
			$gradient_color_2   = $this->args['background_color_2'];
			$gradient_direction = $this->args['gradient_direction'];

			if ( 'vertical' === $gradient_direction ) {
				$gradient_direction = 'top';
				// Safari 4-5, Chrome 1-9 support.
				$gradient = 'background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(' . $gradient_color_1 . '), to(' . $gradient_color_2 . '));';
			} else {
				// Safari 4-5, Chrome 1-9 support.
				$gradient = 'background: -webkit-gradient(linear, left top, right top, from(' . $gradient_color_1 . '), to(' . $gradient_color_2 . '));';
			}

			// Safari 5.1, Chrome 10+ support.
			$gradient .= 'background: -webkit-linear-gradient(' . $gradient_direction . ', ' . $gradient_color_1 . ', ' . $gradient_color_2 . ');';

			// Firefox 3.6+ support.
			$gradient .= 'background: -moz-linear-gradient(' . $gradient_direction . ', ' . $gradient_color_1 . ', ' . $gradient_color_2 . ');';

			// IE 10+ support.
			$gradient .= 'background: -ms-linear-gradient(' . $gradient_direction . ', ' . $gradient_color_1 . ', ' . $gradient_color_2 . ');';

			// Opera 11.10+ support.
			$gradient .= 'background: -o-linear-gradient(' . $gradient_direction . ', ' . $gradient_color_1 . ', ' . $gradient_color_2 . ');';

			return $gradient;
		}
	}

	new IEE_Skew_Heading();
} // End if().

/**
 * Map shortcode for skew_heading.
 *
 * @since 2.0
 * @return void
 */
function map_elegant_elements_skew_heading() {
	global $fusion_settings;

	// Get default typography for title and description.
	$default_typography = elegant_get_default_typography();

	fusion_builder_map(
		array(
			'name'                      => esc_attr__( 'Elegant Skew Heading', 'elegant-elements' ),
			'shortcode'                 => 'iee_skew_heading',
			'icon'                      => 'fa-h-square fas skew-heading-icon',
			'front-end'                 => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-skew-heading-preview.php',
			'inline_editor'             => true,
			'inline_editor_shortcodes'  => false,
			'custom_settings_view_name' => 'ModuleSettingElegantView',
			'params'                    => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Heading Text', 'elegant-elements' ),
					'param_name'  => 'heading_text',
					'value'       => esc_attr__( 'Skew Heading' ),
					'description' => esc_attr__( 'Text to be used for this heading.', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Heading Tag', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the heading tag, H1-H6, Div or Span.', 'elegant-elements' ),
					'param_name'  => 'heading_tag',
					'value'       => array(
						'h1'   => 'H1',
						'h2'   => 'H2',
						'h3'   => 'H3',
						'h4'   => 'H4',
						'h5'   => 'H5',
						'h6'   => 'H6',
						'div'  => 'Div',
						'span' => 'Span',
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
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Skew Direction', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose how you want to set the skew direction.', 'elegant-elements' ),
					'param_name'  => 'skew_direction',
					'default'     => 'left',
					'value'       => array(
						'left'  => __( 'Left', 'elegant-elements' ),
						'right' => __( 'Right', 'elegant-elements' ),
					),
					'dependency'  => array(
						array(
							'element'  => 'heading_text',
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
					'description' => esc_attr__( 'Select typography for the title text.', 'elegant-elements' ),
					'param_name'  => 'typography_heading',
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
					'heading'     => esc_attr__( 'Heading Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for title text. ( In Pixel. )', 'elegant-elements' ),
					'param_name'  => 'heading_font_size',
					'value'       => '28',
					'min'         => '12',
					'max'         => '100',
					'step'        => '1',
					'group'       => 'Typography',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Heading Color', 'elegant-elements' ),
					'param_name'  => 'heading_color',
					'value'       => '#ffffff',
					'description' => esc_attr__( 'Controls the text color for heading.', 'elegant-elements' ),
					'group'       => 'Typography',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Background Color', 'elegant-elements' ),
					'param_name'  => 'background_color_1',
					'value'       => '#ae02f2',
					'description' => esc_attr__( 'Controls the skew background color.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Background Color 2', 'elegant-elements' ),
					'param_name'  => 'background_color_2',
					'value'       => '#0087ef',
					'description' => esc_attr__( 'Controls the second background color. If set, it will form gradient background.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Gradient Direction', 'elegant-elements' ),
					'param_name'  => 'gradient_direction',
					'default'     => '0deg',
					'value'       => array(
						'vertical' => esc_attr__( 'Vertical From Top to Bottom', 'elegant-elements' ),
						'0deg'     => esc_attr__( 'Gradient From Left to Right', 'elegant-elements' ),
						'45deg'    => esc_attr__( 'Gradient From Bottom - Left Angle', 'elegant-elements' ),
						'-45deg'   => esc_attr__( 'Gradient From Top - Left Angle', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Controls the gradient background color direction for this heading. Works only if both the background colors are set.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_skew_heading', 99 );
