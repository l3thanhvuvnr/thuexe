<?php
if ( fusion_is_element_enabled( 'iee_empty_space' ) && ! class_exists( 'IEE_Empty_Space' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class IEE_Empty_Space extends Fusion_Element {

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

			add_filter( 'fusion_attr_elegant-empty-space', array( $this, 'attr' ) );
			add_shortcode( 'iee_empty_space', array( $this, 'render' ) );
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

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'type'           => 'vertical',
					'width'          => 10,
					'height'         => 10,
					'hide_on_mobile' => fusion_builder_default_visibility( 'string' ),
					'class'          => '',
					'id'             => '',
				),
				$args
			);

			$defaults = apply_filters( 'fusion_builder_default_args', $defaults, 'iee_empty_space', $args );

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/empty-space/elegant-empty-space.php' ) ) {
				include locate_template( 'templates/empty-space/elegant-empty-space.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/empty-space/elegant-empty-space.php';
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
				'class' => 'elegant-empty-space',
				'style' => '',
			);

			$attr['class'] .= ' space-' . $this->args['type'];

			$attr = fusion_builder_visibility_atts( $this->args['hide_on_mobile'], $attr );

			if ( 'vertical' == $this->args['type'] ) {
				$attr['class'] .= ' fusion-clearfix';
				$height         = FusionBuilder::validate_shortcode_attr_value( $this->args['height'], 'px' );
				$attr['style']  = 'height:' . $height . ';';
			} else {
				$width         = FusionBuilder::validate_shortcode_attr_value( $this->args['width'], 'px' );
				$attr['style'] = 'width:' . $width . ';';
			}

			if ( isset( $this->args['class'] ) ) {
				$attr['class'] .= ' ' . $this->args['class'];
			}

			if ( isset( $this->args['id'] ) ) {
				$attr['id'] = $this->args['id'];
			}

			return $attr;
		}
	}

	new IEE_Empty_Space();
} // End if().

/**
 * Map shortcode for empty_space.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_empty_space() {
	global $fusion_settings;

	fusion_builder_map(
		array(
			'name'       => esc_attr__( 'Elegant Empty Space', 'elegant-elements' ),
			'shortcode'  => 'iee_empty_space',
			'icon'       => 'icon-empty-space',
			'preview'    => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/previews/infi-empty-space-preview.php',
			'preview_id' => 'elegant-elements-module-infi-empty-space-preview-template',
			'front-end'  => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-empty-space-preview.php',
			'params'     => array(
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Space Type', 'elegant-elements' ),
					'description' => esc_attr__( 'Select how you want to add empty space, vertically or horizontally.', 'elegant-elements' ),
					'param_name'  => 'type',
					'default'     => 'vertical',
					'value'       => array(
						'vertical'   => __( 'Vertical space between two elements', 'elegant-elements' ),
						'horizontal' => __( 'Horizontal space between two elements', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css height to create empty space between two elements. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'height',
					'value'       => '10',
					'min'         => '1',
					'max'         => '500',
					'step'        => '1',
					'dependency'  => array(
						array(
							'element'  => 'type',
							'value'    => 'vertical',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Width', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css width to create empty space between two elements. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'width',
					'value'       => '10',
					'min'         => '1',
					'max'         => '500',
					'step'        => '1',
					'dependency'  => array(
						array(
							'element'  => 'type',
							'value'    => 'horizontal',
							'operator' => '==',
						),
					),
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_empty_space', 99 );
