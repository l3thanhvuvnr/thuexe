<?php
if ( fusion_is_element_enabled( 'iee_animated_dividers' ) && ! class_exists( 'IEE_Animated_Dividers' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 2.4
	 */
	class IEE_Animated_Dividers extends Fusion_Element {

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

			add_filter( 'fusion_attr_elegant-animated-divider', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_elegant-animated-divider-block', array( $this, 'block_attr' ) );

			add_shortcode( 'iee_animated_dividers', array( $this, 'render' ) );
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

			$this->args = $args;

			$html = '';

			if ( '' !== locate_template( 'templates/animated-dividers/elegant-animated-dividers.php' ) ) {
				include locate_template( 'templates/animated-dividers/elegant-animated-dividers.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/animated-dividers/elegant-animated-dividers.php';
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
				'class' => 'elegant-animated-divider',
				'style' => 'position: relative;',
			);

			$attr['class'] .= ' elegant-animated-divider-' . $this->counter;
			$attr['class'] .= ' elegant-divider-' . $this->args['type'];

			$attr = fusion_builder_visibility_atts( $this->args['hide_on_mobile'], $attr );

			if ( isset( $this->args['margin_top'] ) && '' !== $this->args['margin_top'] ) {
				$attr['style'] .= 'margin-top:' . FusionBuilder::validate_shortcode_attr_value( $this->args['margin_top'], 'px' ) . ';';
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
		 * @since 2.4
		 * @return array
		 */
		public function block_attr() {
			$attr = array(
				'class' => 'elegant-animated-divider-block',
			);

			$height        = FusionBuilder::validate_shortcode_attr_value( $this->args['height'], 'px' );
			$mobile_height = FusionBuilder::validate_shortcode_attr_value( $this->args['mobile_height'], 'px' );

			$attr['data-elegant-divider']               = $this->args['type'];
			$attr['data-elegant-divider-zindex']        = $this->args['z_index'];
			$attr['data-elegant-divider-height']        = $height;
			$attr['data-elegant-divider-mobile-height'] = $mobile_height;

			return $attr;
		}

		/**
		 * Sets the necessary scripts.
		 *
		 * @access public
		 * @since 2.3
		 * @return void
		 */
		public function add_scripts() {
			global $elegant_js_folder_url, $elegant_js_folder_path;

			Fusion_Dynamic_JS::enqueue_script(
				'infi-elegant-dividers',
				$elegant_js_folder_url . '/infi-elegant-dividers.min.js',
				$elegant_js_folder_path . '/infi-elegant-dividers.min.js',
				array( 'jquery' ),
				'1',
				true
			);
		}
	}

	new IEE_Animated_Dividers();
} // End if().

/**
 * Map shortcode for animated_dividers.
 *
 * @since 2.4
 * @return void
 */
function map_elegant_elements_animated_dividers() {
	global $fusion_settings;

	fusion_builder_map(
		array(
			'name'      => esc_attr__( 'Elegant Animated Dividers', 'elegant-elements' ),
			'shortcode' => 'iee_animated_dividers',
			'icon'      => 'fa-water fas animated-dividers-icon',
			'front-end' => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-animated-dividers-preview.php',
			'params'    => array(
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Divider Type', 'elegant-elements' ),
					'param_name'  => 'type',
					'value'       => array(
						'slantRight'        => esc_attr__( 'Slant - Right', 'elegant-elements' ),
						'slantLeft'         => esc_attr__( 'Slant - Left', 'elegant-elements' ),
						'slantLeftOpacity'  => esc_attr__( 'Slant - Left / Opacity', 'elegant-elements' ),
						'slantRightOpacity' => esc_attr__( 'Slant - Right / Opacity', 'elegant-elements' ),
						'slantRightBg'      => esc_attr__( 'Slant - Left Solid / Right Opacity', 'elegant-elements' ),
						'slantLeftBg'       => esc_attr__( 'Slant - Right Solid / Left Opacity', 'elegant-elements' ),
						'slantBothBg'       => esc_attr__( 'Slant - Both Side / Opacity', 'elegant-elements' ),
						'triangleTop'       => esc_attr__( 'Triangle - Top', 'elegant-elements' ),
						'triangleBottom'    => esc_attr__( 'Triangle - Bottom', 'elegant-elements' ),
						'triangleTopBg'     => esc_attr__( 'Triangle - Top / Opacity', 'elegant-elements' ),
						'triangleBottomBg'  => esc_attr__( 'Triangle - Bottom / Opacity', 'elegant-elements' ),
						'curveTop'          => esc_attr__( 'Curve - Top', 'elegant-elements' ),
						'curveBottom'       => esc_attr__( 'Curve - Bottom', 'elegant-elements' ),
						'curveLeft'         => esc_attr__( 'Curve - Left', 'elegant-elements' ),
						'curveRight'        => esc_attr__( 'Curve - Right', 'elegant-elements' ),
						'curveTopBg'        => esc_attr__( 'Curve - Top / Opacity', 'elegant-elements' ),
						'curveBottomBg'     => esc_attr__( 'Curve - Bottom / Opacity', 'elegant-elements' ),
						'curveLeftBg'       => esc_attr__( 'Curve - Left / Opacity', 'elegant-elements' ),
						'curveRightBg'      => esc_attr__( 'Curve - Right / Opacity', 'elegant-elements' ),
						'waves'             => esc_attr__( 'Waves', 'elegant-elements' ),
						'waveLeft'          => esc_attr__( 'Waves - Left', 'elegant-elements' ),
						'waveRight'         => esc_attr__( 'Waves - Right', 'elegant-elements' ),
						'waveLeftBg'        => esc_attr__( 'Waves - Left / Opacity', 'elegant-elements' ),
						'waveRightBg'       => esc_attr__( 'Waves - Right / Opacity', 'elegant-elements' ),
						'wavesOpacity'      => esc_attr__( 'Waves - Opacity', 'elegant-elements' ),
						'hills'             => esc_attr__( 'Hills', 'elegant-elements' ),
						'hillsRounded'      => esc_attr__( 'Hills - Rounded', 'elegant-elements' ),
						'cloudsLarge'       => esc_attr__( 'Cloud - Large', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Enter content for this element.', 'elegant-elements' ),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the divider height. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'height',
					'value'       => '100',
					'min'         => '10',
					'max'         => '1000',
					'step'        => '1',
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Mobile Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the divider height on mobile devices under 768px. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'mobile_height',
					'value'       => '60',
					'min'         => '10',
					'max'         => '500',
					'step'        => '1',
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the background color for the divider shape. The top side of the divider is transparent, if you put the divider at the top of the container and the current container has background color, please choose the previous container background color in the below option.', 'elegant-elements' ),
					'param_name'  => 'background_color',
					'value'       => '#03a9f4',
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Top Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the top background color for the divider shape. Usually, this should be the previous container background color if you put the divider at the top of the container.', 'elegant-elements' ),
					'param_name'  => 'background_color_top',
					'value'       => 'rgba(255,255,255,0)',
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Divider Z-Index', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the z-index of the divider and helps you to put the divider in back side or front side of an element above it.', 'elegant-elements' ),
					'param_name'  => 'z_index',
					'value'       => '2',
					'min'         => '-1',
					'max'         => '100',
					'step'        => '1',
				),
				array(
					'type'        => 'dimension',
					'heading'     => esc_attr__( 'Top Margin', 'elegant-elements' ),
					'param_name'  => 'margin_top',
					'value'       => array(
						'margin_top' => '',
					),
					'description' => esc_attr__( 'Enter top margin for the divider to add space or move the divider under the other elements as background. In pixels (px) eg. -100px.', 'elegant-elements' ),
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_animated_dividers', 99 );
