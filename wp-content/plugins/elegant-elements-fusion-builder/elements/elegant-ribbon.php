<?php
if ( fusion_is_element_enabled( 'iee_ribbon' ) && ! class_exists( 'IEE_Ribbon' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 2.4
	 */
	class IEE_Ribbon extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 2.4
		 * @var array
		 */
		protected $args;

		/**
		 * Elementor counter.
		 *
		 * @access protected
		 * @since 2.4
		 * @var array
		 */
		protected $counter = 1;

		/**
		 * Constructor.
		 *
		 * @since 2.4
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-ribbon', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_elegant-ribbon-wrapper', array( $this, 'wrapper_attr' ) );

			add_shortcode( 'iee_ribbon', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 2.4
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'style'               => 'style01',
					'ribbon_text'         => '',
					'text_transform'      => 'none',
					'letter_spacing'      => '3',
					'font_size'           => '21',
					'text_color'          => '#ffffff',
					'background_color'    => '#434757',
					'position'            => 'top',
					'horizontal_position' => 'left',
					'hide_on_mobile'      => '',
					'class'               => '',
					'id'                  => '',
				),
				$args
			);

			$defaults = apply_filters( 'fusion_builder_default_args', $defaults, 'iee_ribbon', $args );

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/ribbon/elegant-ribbon.php' ) ) {
				include locate_template( 'templates/ribbon/elegant-ribbon.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/ribbon/elegant-ribbon.php';
			}

			$this->counter++;

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 2.4
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-ribbon',
				'style' => '',
			);

			$attr['class'] .= ' elegant-ribbon-' . $this->counter;
			$attr['class'] .= ' ribbon-position-' . $this->args['position'];
			$attr['class'] .= ' ribbon-style-' . $this->args['style'];

			if ( 'style05' === $this->args['style'] || 'style06' === $this->args['style'] ) {
				$attr['class'] .= ' ribbon-horizontal-' . $this->args['horizontal_position'];
			}

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
		 * @since 2.4
		 * @return array
		 */
		public function wrapper_attr() {
			$attr = array(
				'class' => 'elegant-ribbon-wrapper',
				'style' => '',
			);

			$font_size      = FusionBuilder::validate_shortcode_attr_value( $this->args['font_size'], 'px' );
			$letter_spacing = FusionBuilder::validate_shortcode_attr_value( $this->args['letter_spacing'], 'px' );

			if ( '' !== $this->args['text_color'] ) {
				$attr['style'] .= 'color:' . $this->args['text_color'] . ';';
			}

			$attr['style'] .= 'font-size:' . $font_size . ';';
			$attr['style'] .= 'border-color:' . $this->args['background_color'] . ';';
			$attr['style'] .= 'background:' . $this->args['background_color'] . ';';
			$attr['style'] .= '--background:' . $this->args['background_color'] . ';';
			$attr['style'] .= 'text-transform:' . $this->args['text_transform'] . ';';
			$attr['style'] .= 'letter-spacing:' . $letter_spacing . ';';

			return $attr;
		}
	}

	new IEE_Ribbon();
} // End if().

/**
 * Map shortcode for ribbon.
 *
 * @since 2.4
 * @return void
 */
function map_elegant_elements_ribbon() {
	global $fusion_settings;

	fusion_builder_map(
		array(
			'name'      => esc_attr__( 'Elegant Ribbon', 'elegant-elements' ),
			'shortcode' => 'iee_ribbon',
			'icon'      => 'fa-ribbon fas ribbon-icon',
			'front-end' => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-ribbon-preview.php',
			'params'    => array(
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Ribbon Style', 'elegant-elements' ),
					'param_name'  => 'style',
					'default'     => 'style01',
					'value'       => array(
						'style01' => esc_attr__( 'Style 01', 'elegant-elements' ),
						'style02' => esc_attr__( 'Style 02', 'elegant-elements' ),
						'style03' => esc_attr__( 'Style 03', 'elegant-elements' ),
						'style04' => esc_attr__( 'Style 04', 'elegant-elements' ),
						'style05' => esc_attr__( 'Style 05', 'elegant-elements' ),
						'style06' => esc_attr__( 'Style 06', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Select the ribbon style.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Ribbon Text', 'elegant-elements' ),
					'param_name'  => 'ribbon_text',
					'value'       => esc_attr__( 'Elegant Ribbon', 'elegant-elements' ),
					'placeholder' => true,
					'description' => esc_attr__( 'Add a class to the wrapping HTML element.', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Text Transform', 'elegant-elements' ),
					'param_name'  => 'text_transform',
					'default'     => 'none',
					'value'       => array(
						'none'      => esc_attr__( 'Normal', 'elegant-elements' ),
						'uppercase' => esc_attr__( 'Uppercase', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Choose how the text is displayed.', 'elegant-elements' ),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Letter Spacing', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the letter spacing of the ribbon text. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'letter_spacing',
					'value'       => '3',
					'min'         => '1',
					'max'         => '10',
					'step'        => '1',
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the font size of the ribbon text. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'font_size',
					'value'       => '16',
					'min'         => '1',
					'max'         => '100',
					'step'        => '1',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Text Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the color for the content.', 'elegant-elements' ),
					'param_name'  => 'text_color',
					'value'       => '#ffffff',
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the background color for the ribbon.', 'elegant-elements' ),
					'param_name'  => 'background_color',
					'value'       => '#434757',
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Vertical Position', 'elegant-elements' ),
					'param_name'  => 'position',
					'default'     => 'top',
					'value'       => array(
						'top'    => esc_attr__( 'Top', 'elegant-elements' ),
						'middle' => esc_attr__( 'Middle', 'elegant-elements' ),
						'bottom' => esc_attr__( 'Bottom', 'elegant-elements' ),
					),
					'dependency'  => array(
						array(
							'element'  => 'style',
							'value'    => 'style04',
							'operator' => '!=',
						),
						array(
							'element'  => 'style',
							'value'    => 'style06',
							'operator' => '!=',
						),
					),
					'description' => esc_attr__( 'How do you want to position the ribbon vertically in the column.', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Horizontal Position', 'elegant-elements' ),
					'param_name'  => 'horizontal_position',
					'default'     => 'left',
					'value'       => array(
						'left'  => esc_attr__( 'Left', 'elegant-elements' ),
						'right' => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'dependency'  => array(
						array(
							'element'  => 'style',
							'value'    => 'style01',
							'operator' => '!=',
						),
						array(
							'element'  => 'style',
							'value'    => 'style02',
							'operator' => '!=',
						),
						array(
							'element'  => 'style',
							'value'    => 'style03',
							'operator' => '!=',
						),
						array(
							'element'  => 'style',
							'value'    => 'style04',
							'operator' => '!=',
						),
					),
					'description' => esc_attr__( 'How do you want to position the ribbon horizontally in the column.', 'elegant-elements' ),
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_ribbon', 99 );
