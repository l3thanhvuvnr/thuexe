<?php
if ( fusion_is_element_enabled( 'iee_dual_style_heading' ) && ! class_exists( 'IEE_Dual_Style_Heading' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 2.1.0
	 */
	class IEE_Dual_Style_Heading extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 2.1.0
		 * @var array
		 */
		protected $args;

		/**
		 * Constructor.
		 *
		 * @since 2.1.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-dual-style-heading', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_elegant-dual-style-heading-first', array( $this, 'attr_heading_first' ) );
			add_filter( 'fusion_attr_elegant-dual-style-heading-last', array( $this, 'attr_heading_last' ) );

			add_shortcode( 'iee_dual_style_heading', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 2.1.0
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
					'heading_first'                    => '',
					'heading_last'                     => '',
					'heading_tag'                      => 'h2',
					'alignment'                        => 'left',
					'padding_top'                      => '',
					'padding_right'                    => '',
					'padding_bottom'                   => '',
					'padding_left'                     => '',
					'padding'                          => '',
					'heading_gap'                      => '0px',
					'element_typography'               => 'default',
					'typography_heading_first'         => '',
					'typography_heading_last'          => '',
					'font_size'                        => '18px',
					'heading_first_text_color'         => '',
					'heading_first_background_color'   => '',
					'heading_first_background_color_2' => '',
					'heading_first_gradient_type'      => 'horizontal',
					'heading_first_gradient_direction' => '0deg',
					'heading_first_border_size'        => '',
					'heading_first_border_color'       => '',
					'heading_first_border_style'       => '',
					'heading_first_border_position'    => 'all',
					'heading_first_border_radius'      => '',
					'first_border_radius_top_left'     => '0px',
					'first_border_radius_top_right'    => '0px',
					'first_border_radius_bottom_left'  => '0px',
					'first_border_radius_bottom_right' => '0px',
					'heading_last_text_color'          => '',
					'heading_last_background_color'    => '',
					'heading_last_background_color_2'  => '',
					'heading_last_gradient_type'       => 'horizontal',
					'heading_last_gradient_direction'  => '0deg',
					'heading_last_border_size'         => '',
					'heading_last_border_color'        => '',
					'heading_last_border_style'        => '',
					'heading_last_border_position'     => 'all',
					'heading_last_border_radius'       => '',
					'last_border_radius_top_left'      => '0px',
					'last_border_radius_top_right'     => '0px',
					'last_border_radius_bottom_left'   => '0px',
					'last_border_radius_bottom_right'  => '0px',
					'hide_on_mobile'                   => '',
					'class'                            => '',
					'id'                               => '',
				),
				$args
			);

			if ( 'default' === $defaults['element_typography'] ) {
				$defaults['typography_heading_first'] = $default_typography['title'];
				$defaults['typography_heading_last']  = $default_typography['title'];
			}

			if ( ! isset( $args['padding'] ) ) {
				$padding_values           = array();
				$padding_values['top']    = ( isset( $args['padding_top'] ) && '' !== $args['padding_top'] ) ? FusionBuilder::validate_shortcode_attr_value( $args['padding_top'], 'px' ) : $defaults['padding_top'];
				$padding_values['right']  = ( isset( $args['padding_right'] ) && '' !== $args['padding_right'] ) ? FusionBuilder::validate_shortcode_attr_value( $args['padding_right'], 'px' ) : $defaults['padding_right'];
				$padding_values['bottom'] = ( isset( $args['padding_bottom'] ) && '' !== $args['padding_bottom'] ) ? FusionBuilder::validate_shortcode_attr_value( $args['padding_bottom'], 'px' ) : $defaults['padding_bottom'];
				$padding_values['left']   = ( isset( $args['padding_left'] ) && '' !== $args['padding_left'] ) ? FusionBuilder::validate_shortcode_attr_value( $args['padding_left'], 'px' ) : $defaults['padding_left'];

				$defaults['padding'] = implode( ' ', $padding_values );
				$defaults['padding'] = trim( $defaults['padding'] );
			}

			$args = $defaults;

			$this->args = $args;

			$html = '';

			if ( '' !== locate_template( 'templates/dual-style-heading/elegant-dual-style-heading.php' ) ) {
				include locate_template( 'templates/dual-style-heading/elegant-dual-style-heading.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/dual-style-heading/elegant-dual-style-heading.php';
			}

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 2.1.0
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-dual-style-heading',
			);

			$attr['class'] .= ' elegant-align-' . $this->args['alignment'];

			$attr = fusion_builder_visibility_atts( $this->args['hide_on_mobile'], $attr );

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
		 * @since 2.1.0
		 * @return array
		 */
		public function attr_heading_first() {
			$attr = array(
				'class' => 'elegant-dual-style-heading-first',
				'style' => '',
			);

			if ( isset( $this->args['heading_first_text_color'] ) && '' !== $this->args['heading_first_text_color'] ) {
				$attr['style'] .= 'color: ' . $this->args['heading_first_text_color'] . ';';
			}

			if ( isset( $this->args['heading_first_background_color'] ) && '' !== $this->args['heading_first_background_color'] ) {
				$attr['style'] .= 'background-color: ' . $this->args['heading_first_background_color'] . ';';
			}

			if ( isset( $this->args['heading_first_background_color_2'] ) && '' !== $this->args['heading_first_background_color_2'] ) {
				$gradient_direction = ( 'vertical' == $this->args['heading_first_gradient_type'] ) ? 'top' : $this->args['heading_first_gradient_direction'];
				$attr['style']     .= elegant_build_gradient_color( $this->args['heading_first_background_color'], $this->args['heading_first_background_color_2'], $gradient_direction );
			}

			if ( isset( $this->args['padding'] ) && '' !== $this->args['padding'] ) {
				$attr['style'] .= 'padding: ' . $this->args['padding'] . ';';
			}

			if ( isset( $this->args['font_size'] ) && '' !== $this->args['font_size'] ) {
				$attr['style'] .= 'font-size: ' . FusionBuilder::validate_shortcode_attr_value( $this->args['font_size'], 'px' ) . ';';
			}

			if ( isset( $this->args['heading_gap'] ) && '' !== $this->args['heading_gap'] ) {
				$attr['style'] .= 'margin-right: ' . FusionBuilder::validate_shortcode_attr_value( $this->args['heading_gap'], 'px' ) . ';';
			}

			// Border.
			if ( $this->args['heading_first_border_color'] && $this->args['heading_first_border_size'] && $this->args['heading_first_border_style'] ) {
				$border_position = ( 'all' !== $this->args['heading_first_border_position'] ) ? '-' . $this->args['heading_first_border_position'] : '';
				$border_size     = FusionBuilder::validate_shortcode_attr_value( $this->args['heading_first_border_size'], 'px' );
				$attr['style']  .= 'border' . $border_position . ':' . $border_size . ' ' . $this->args['heading_first_border_style'] . ' ' . $this->args['heading_first_border_color'] . ';';
			}

			// Border radius.
			$border_radius_top_left     = ( isset( $this->args['first_border_radius_top_left'] ) && '' !== $this->args['first_border_radius_top_left'] ) ? FusionBuilder::validate_shortcode_attr_value( $this->args['first_border_radius_top_left'], 'px' ) : '0px';
			$border_radius_top_right    = ( isset( $this->args['first_border_radius_top_right'] ) && '' !== $this->args['first_border_radius_top_right'] ) ? FusionBuilder::validate_shortcode_attr_value( $this->args['first_border_radius_top_right'], 'px' ) : '0px';
			$border_radius_bottom_right = ( isset( $this->args['first_border_radius_bottom_right'] ) && '' !== $this->args['first_border_radius_bottom_right'] ) ? FusionBuilder::validate_shortcode_attr_value( $this->args['first_border_radius_bottom_right'], 'px' ) : '0px';
			$border_radius_bottom_left  = ( isset( $this->args['first_border_radius_bottom_left'] ) && '' !== $this->args['first_border_radius_bottom_left'] ) ? FusionBuilder::validate_shortcode_attr_value( $this->args['first_border_radius_bottom_left'], 'px' ) : '0px';
			$border_radius              = $border_radius_top_left . ' ' . $border_radius_top_right . ' ' . $border_radius_bottom_right . ' ' . $border_radius_bottom_left;
			$border_radius              = ( '0px 0px 0px 0px' === $border_radius ) ? '' : $border_radius;

			if ( '' !== $border_radius ) {
				$attr['style'] .= 'border-radius: ' . $border_radius . ';';
			}

			// Typography.
			if ( isset( $this->args['typography_heading_first'] ) && '' !== $this->args['typography_heading_first'] ) {
				$attr['style'] .= elegant_get_typography_css( $this->args['typography_heading_first'] );
			}

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 2.1.0
		 * @return array
		 */
		public function attr_heading_last() {
			$attr = array(
				'class' => 'elegant-dual-style-heading-last',
				'style' => '',
			);

			if ( isset( $this->args['heading_last_text_color'] ) && '' !== $this->args['heading_last_text_color'] ) {
				$attr['style'] .= 'color: ' . $this->args['heading_last_text_color'] . ';';
			}

			if ( isset( $this->args['heading_last_background_color'] ) && '' !== $this->args['heading_last_background_color'] ) {
				$attr['style'] .= 'background-color: ' . $this->args['heading_last_background_color'] . ';';
			}

			if ( isset( $this->args['heading_last_background_color_2'] ) && '' !== $this->args['heading_last_background_color_2'] ) {
				$gradient_direction = ( 'vertical' == $this->args['heading_last_gradient_type'] ) ? 'top' : $this->args['heading_last_gradient_direction'];
				$attr['style']     .= elegant_build_gradient_color( $this->args['heading_last_background_color'], $this->args['heading_last_background_color_2'], $gradient_direction );
			}

			if ( isset( $this->args['padding'] ) && '' !== $this->args['padding'] ) {
				$attr['style'] .= 'padding: ' . $this->args['padding'] . ';';
			}

			if ( isset( $this->args['font_size'] ) && '' !== $this->args['font_size'] ) {
				$attr['style'] .= 'font-size: ' . FusionBuilder::validate_shortcode_attr_value( $this->args['font_size'], 'px' ) . ';';
			}

			// Border.
			if ( $this->args['heading_last_border_color'] && $this->args['heading_last_border_size'] && $this->args['heading_last_border_style'] ) {
				$border_position = ( 'all' !== $this->args['heading_last_border_position'] ) ? '-' . $this->args['heading_last_border_position'] : '';
				$border_size     = FusionBuilder::validate_shortcode_attr_value( $this->args['heading_last_border_size'], 'px' );
				$attr['style']  .= 'border' . $border_position . ':' . $border_size . ' ' . $this->args['heading_last_border_style'] . ' ' . $this->args['heading_last_border_color'] . ';';
			}

			// Border radius.
			$border_radius_top_left     = ( isset( $this->args['last_border_radius_top_left'] ) && '' !== $this->args['last_border_radius_top_left'] ) ? FusionBuilder::validate_shortcode_attr_value( $this->args['last_border_radius_top_left'], 'px' ) : '0px';
			$border_radius_top_right    = ( isset( $this->args['last_border_radius_top_right'] ) && '' !== $this->args['last_border_radius_top_right'] ) ? FusionBuilder::validate_shortcode_attr_value( $this->args['last_border_radius_top_right'], 'px' ) : '0px';
			$border_radius_bottom_right = ( isset( $this->args['last_border_radius_bottom_right'] ) && '' !== $this->args['last_border_radius_bottom_right'] ) ? FusionBuilder::validate_shortcode_attr_value( $this->args['last_border_radius_bottom_right'], 'px' ) : '0px';
			$border_radius_bottom_left  = ( isset( $this->args['last_border_radius_bottom_left'] ) && '' !== $this->args['last_border_radius_bottom_left'] ) ? FusionBuilder::validate_shortcode_attr_value( $this->args['last_border_radius_bottom_left'], 'px' ) : '0px';
			$border_radius              = $border_radius_top_left . ' ' . $border_radius_top_right . ' ' . $border_radius_bottom_right . ' ' . $border_radius_bottom_left;
			$border_radius              = ( '0px 0px 0px 0px' === $border_radius ) ? '' : $border_radius;

			if ( '' !== $border_radius ) {
				$attr['style'] .= 'border-radius: ' . $border_radius . ';';
			}

			// Typography.
			if ( isset( $this->args['typography_heading_last'] ) && '' !== $this->args['typography_heading_last'] ) {
				$attr['style'] .= elegant_get_typography_css( $this->args['typography_heading_last'] );
			}

			return $attr;
		}
	}

	new IEE_Dual_Style_Heading();
} // End if().

/**
 * Map shortcode for dual_style_heading.
 *
 * @since 2.1.0
 * @return void
 */
function map_elegant_elements_dual_style_heading() {
	global $fusion_settings;

	// Get default typography for title and description.
	$default_typography = elegant_get_default_typography();

	fusion_builder_map(
		array(
			'name'                      => esc_attr__( 'Elegant Dual Style Heading', 'elegant-elements' ),
			'shortcode'                 => 'iee_dual_style_heading',
			'icon'                      => 'fa-h-square fas dual-style-heading-icon',
			'front-end'                 => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-dual-style-heading-preview.php',
			'inline_editor'             => true,
			'custom_settings_view_name' => 'ModuleSettingElegantView',
			'params'                    => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Heading Text 1', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the text for the heading to be displayed in first place.', 'elegant-elements' ),
					'param_name'  => 'heading_first',
					'value'       => __( 'Elegant Elements', 'elegant-elements' ),
					'placeholder' => true,
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Heading Text 2', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the text for the heading to be displayed in second place.', 'elegant-elements' ),
					'param_name'  => 'heading_last',
					'value'       => __( 'for Fusion Builder', 'elegant-elements' ),
					'placeholder' => true,
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
						'div'  => 'DIV',
						'span' => 'SPAN',
					),
					'default'     => 'h2',
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
					'description' => esc_attr__( 'Align the heading to left, right or center.', 'elegant-elements' ),
				),
				array(
					'type'             => 'dimension',
					'remove_from_atts' => true,
					'heading'          => esc_attr__( 'Padding', 'elegant-elements' ),
					'description'      => __( 'Enter values including any valid CSS unit, ex: 10px.', 'elegant-elements' ),
					'param_name'       => 'padding',
					'value'            => array(
						'padding_top'    => '4px',
						'padding_right'  => '10px',
						'padding_bottom' => '4px',
						'padding_left'   => '10px',
					),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Heading Gap', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the gap between two heading texts. In pixels.', 'elegant-elements' ),
					'param_name'  => 'heading_gap',
					'value'       => '0',
					'min'         => '0',
					'max'         => '50',
					'step'        => '1',
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Element Typography Override', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose if you want to keep the theme options global typography as default for this element or want to set custom. Controls typography options for all typography fields in this element.', 'elegant-elements' ),
					'param_name'  => 'element_typography',
					'default'     => 'default',
					'value'       => array(
						'default' => esc_attr__( 'Default', 'elegant-elements' ),
						'custom'  => esc_attr__( 'Custom', 'elegant-elements' ),
					),
					'group'       => 'Typography',
				),
				array(
					'type'        => 'elegant_typography',
					'heading'     => esc_attr__( 'Heading 1 Text Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select typography for the first heading text.', 'elegant-elements' ),
					'param_name'  => 'typography_heading_first',
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
					'type'        => 'elegant_typography',
					'heading'     => esc_attr__( 'Heading 2 Text Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select typography for the second heading text.', 'elegant-elements' ),
					'param_name'  => 'typography_heading_last',
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
					'heading'     => esc_attr__( 'Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for heading text. In Pixel (px).', 'elegant-elements' ),
					'param_name'  => 'font_size',
					'value'       => '18',
					'min'         => '12',
					'max'         => '100',
					'step'        => '1',
					'group'       => 'Typography',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Text Color', 'elegant-elements' ),
					'param_name'  => 'heading_first_text_color',
					'value'       => '#ffffff',
					'default'     => '',
					'description' => esc_attr__( 'Controls the text color for the first heading text.', 'elegant-elements' ),
					'group'       => 'Heading 1 Style',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Background Color', 'elegant-elements' ),
					'param_name'  => 'heading_first_background_color',
					'value'       => '#333333',
					'default'     => '',
					'description' => esc_attr__( 'Controls the background color for the first heading text.', 'elegant-elements' ),
					'group'       => 'Heading 1 Style',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Background Color 2', 'elegant-elements' ),
					'param_name'  => 'heading_first_background_color_2',
					'value'       => '',
					'description' => esc_attr__( 'Controls the second background color for the first heading text, that will help to form the gradient background.', 'elegant-elements' ),
					'group'       => 'Heading 1 Style',
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Gradient Type', 'elegant-elements' ),
					'description' => esc_attr__( 'Select how you want the gradient to be applied.', 'elegant-elements' ),
					'param_name'  => 'heading_first_gradient_type',
					'default'     => 'vertical',
					'group'       => esc_attr__( 'Heading 1 Style', 'elegant-elements' ),
					'value'       => array(
						'vertical'   => esc_attr__( 'Vertical', 'elegant-elements' ),
						'horizontal' => esc_attr__( 'Horizontal', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Horizontal Gradient Direction', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the gradient color direction for horizontal gradient.', 'elegant-elements' ),
					'param_name'  => 'heading_first_gradient_direction',
					'default'     => '0deg',
					'group'       => esc_attr__( 'Heading 1 Style', 'elegant-elements' ),
					'value'       => array(
						'0deg'   => esc_attr__( 'Left to Right', 'elegant-elements' ),
						'45deg'  => esc_attr__( 'Bottom - Left Angle', 'elegant-elements' ),
						'-45deg' => esc_attr__( 'Top - Left Angle', 'elegant-elements' ),
					),
					'dependency'  => array(
						array(
							'element'  => 'heading_first_gradient_type',
							'value'    => 'vertical',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Border Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border size of the heading. In pixels.', 'elegant-elements' ),
					'param_name'  => 'heading_first_border_size',
					'value'       => '0',
					'min'         => '0',
					'max'         => '50',
					'step'        => '1',
					'group'       => esc_attr__( 'Heading 1 Style', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Border Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border color.', 'elegant-elements' ),
					'param_name'  => 'heading_first_border_color',
					'value'       => '',
					'group'       => esc_attr__( 'Heading 1 Style', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'heading_first_border_size',
							'value'    => '0',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Border Style', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border style.', 'elegant-elements' ),
					'param_name'  => 'heading_first_border_style',
					'default'     => 'solid',
					'group'       => esc_attr__( 'Heading 1 Style', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'heading_first_border_size',
							'value'    => '0',
							'operator' => '!=',
						),
					),
					'value'       => array(
						'solid'  => esc_attr__( 'Solid', 'elegant-elements' ),
						'dashed' => esc_attr__( 'Dashed', 'elegant-elements' ),
						'dotted' => esc_attr__( 'Dotted', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Border Position', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the postion of the border.', 'elegant-elements' ),
					'param_name'  => 'heading_first_border_position',
					'default'     => 'all',
					'group'       => esc_attr__( 'Heading 1 Style', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'heading_first_border_size',
							'value'    => '0',
							'operator' => '!=',
						),
					),
					'value'       => array(
						'all'    => esc_attr__( 'All', 'elegant-elements' ),
						'top'    => esc_attr__( 'Top', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
						'bottom' => esc_attr__( 'Bottom', 'elegant-elements' ),
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
					),
				),
				array(
					'type'             => 'dimension',
					'remove_from_atts' => true,
					'heading'          => esc_attr__( 'Border Radius', 'elegant-elements' ),
					'description'      => __( 'Enter values including any valid CSS unit, ex: 10px.', 'elegant-elements' ),
					'param_name'       => 'heading_first_border_radius',
					'value'            => array(
						'first_border_radius_top_left'     => '',
						'first_border_radius_top_right'    => '',
						'first_border_radius_bottom_left'  => '',
						'first_border_radius_bottom_right' => '',
					),
					'group'            => esc_attr__( 'Heading 1 Style', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Text Color', 'elegant-elements' ),
					'param_name'  => 'heading_last_text_color',
					'value'       => '#333333',
					'default'     => '',
					'description' => esc_attr__( 'Controls the text color for the last heading text.', 'elegant-elements' ),
					'group'       => 'Heading 2 Style',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Background Color', 'elegant-elements' ),
					'param_name'  => 'heading_last_background_color',
					'value'       => '#fbfbfb',
					'default'     => '',
					'description' => esc_attr__( 'Controls the background color for the last heading text.', 'elegant-elements' ),
					'group'       => 'Heading 2 Style',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Background Color 2', 'elegant-elements' ),
					'param_name'  => 'heading_last_background_color_2',
					'value'       => '',
					'description' => esc_attr__( 'Controls the second background color for the last heading text, that will help to form the gradient background.', 'elegant-elements' ),
					'group'       => 'Heading 2 Style',
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Gradient Type', 'elegant-elements' ),
					'description' => esc_attr__( 'Select how you want the gradient to be applied.', 'elegant-elements' ),
					'param_name'  => 'heading_last_gradient_type',
					'default'     => 'vertical',
					'group'       => esc_attr__( 'Heading 2 Style', 'elegant-elements' ),
					'value'       => array(
						'vertical'   => esc_attr__( 'Vertical', 'elegant-elements' ),
						'horizontal' => esc_attr__( 'Horizontal', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Horizontal Gradient Direction', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the gradient color direction for horizontal gradient.', 'elegant-elements' ),
					'param_name'  => 'heading_last_gradient_direction',
					'default'     => '0deg',
					'group'       => esc_attr__( 'Heading 2 Style', 'elegant-elements' ),
					'value'       => array(
						'0deg'   => esc_attr__( 'Left to Right', 'elegant-elements' ),
						'45deg'  => esc_attr__( 'Bottom - Left Angle', 'elegant-elements' ),
						'-45deg' => esc_attr__( 'Top - Left Angle', 'elegant-elements' ),
					),
					'dependency'  => array(
						array(
							'element'  => 'heading_last_gradient_type',
							'value'    => 'vertical',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Border Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border size of the heading. In pixels.', 'elegant-elements' ),
					'param_name'  => 'heading_last_border_size',
					'value'       => '0',
					'min'         => '0',
					'max'         => '50',
					'step'        => '1',
					'group'       => esc_attr__( 'Heading 2 Style', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Border Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border color.', 'elegant-elements' ),
					'param_name'  => 'heading_last_border_color',
					'value'       => '',
					'group'       => esc_attr__( 'Heading 2 Style', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'heading_last_border_size',
							'value'    => '0',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Border Style', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border style.', 'elegant-elements' ),
					'param_name'  => 'heading_last_border_style',
					'default'     => 'solid',
					'group'       => esc_attr__( 'Heading 2 Style', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'heading_last_border_size',
							'value'    => '0',
							'operator' => '!=',
						),
					),
					'value'       => array(
						'solid'  => esc_attr__( 'Solid', 'elegant-elements' ),
						'dashed' => esc_attr__( 'Dashed', 'elegant-elements' ),
						'dotted' => esc_attr__( 'Dotted', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Border Position', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the postion of the border.', 'elegant-elements' ),
					'param_name'  => 'heading_last_border_position',
					'default'     => 'all',
					'group'       => esc_attr__( 'Heading 2 Style', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'heading_last_border_size',
							'value'    => '0',
							'operator' => '!=',
						),
					),
					'value'       => array(
						'all'    => esc_attr__( 'All', 'elegant-elements' ),
						'top'    => esc_attr__( 'Top', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
						'bottom' => esc_attr__( 'Bottom', 'elegant-elements' ),
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
					),
				),
				array(
					'type'             => 'dimension',
					'remove_from_atts' => true,
					'heading'          => esc_attr__( 'Border Radius', 'elegant-elements' ),
					'description'      => __( 'Enter values including any valid CSS unit, ex: 10px.', 'elegant-elements' ),
					'param_name'       => 'heading_last_border_radius',
					'value'            => array(
						'last_border_radius_top_left'     => '',
						'last_border_radius_top_right'    => '',
						'last_border_radius_bottom_left'  => '',
						'last_border_radius_bottom_right' => '',
					),
					'group'            => esc_attr__( 'Heading 2 Style', 'elegant-elements' ),
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_dual_style_heading', 99 );
