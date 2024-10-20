<?php
if ( fusion_is_element_enabled( 'iee_distortion_hover_image' ) && ! class_exists( 'IEE_Distortion_Hover_Image' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 2.5
	 */
	class IEE_Distortion_Hover_Image extends Fusion_Element {

		/**
		 * Elementor counter.
		 *
		 * @access protected
		 * @since 2.5
		 * @var array
		 */
		protected $counter = 1;

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 2.5
		 * @var array
		 */
		protected $args;

		/**
		 * Constructor.
		 *
		 * @since 2.5
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-distortion-hover-image-wrapper', array( $this, 'wrapper_attr' ) );
			add_filter( 'fusion_attr_elegant-distortion-hover-image', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_elegant-distortion-hover-content', array( $this, 'content_attr' ) );

			add_shortcode( 'iee_distortion_hover_image', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 2.5
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'first_image'         => '',
					'second_image'        => '',
					'displacement_image'  => '',
					'width'               => 300,
					'height'              => 300,
					'distortion_position' => 'from_left',
					'content_overlay'     => 'rgba(0,0,0,0.3)',
					'hide_on_mobile'      => fusion_builder_default_visibility( 'string' ),
					'class'               => '',
					'id'                  => '',
				),
				$args
			);

			$defaults = apply_filters( 'fusion_builder_default_args', $defaults, 'iee_distortion_hover_image', $args );

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/distortion-hover-image/elegant-distortion-hover-image.php' ) ) {
				include locate_template( 'templates/distortion-hover-image/elegant-distortion-hover-image.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/distortion-hover-image/elegant-distortion-hover-image.php';
			}

			$this->counter++;

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 2.5
		 * @return array
		 */
		public function wrapper_attr() {
			$attr = array(
				'class' => 'elegant-distortion-hover-image-wrapper',
				'style' => '',
			);

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
		 * @since 2.5
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-distortion-hover-image distortion-hover-image-' . $this->counter,
				'style' => '',
			);

			$first_image        = $this->args['first_image'];
			$second_image       = $this->args['second_image'];
			$displacement_image = $this->args['displacement_image'];

			$attr['data-firstImage']        = $first_image;
			$attr['data-secondImage']       = $second_image;
			$attr['data-displacementImage'] = $displacement_image;
			$attr['data-intensity']         = ( 'from_left' === $this->args['distortion_position'] ) ? -0.5 : 0.5;

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 2.5
		 * @return array
		 */
		public function content_attr() {
			$attr = array(
				'class' => 'elegant-distortion-hover-content',
				'style' => '',
			);

			$attr['style'] .= 'background: ' . $this->args['content_overlay'] . ';';

			return $attr;
		}
	}

	new IEE_Distortion_Hover_Image();
} // End if().

/**
 * Map shortcode for distortion_hover_image.
 *
 * @since 2.5
 * @return void
 */
function map_elegant_elements_distortion_hover_image() {
	global $fusion_settings;

	fusion_builder_map(
		array(
			'name'      => esc_attr__( 'Elegant Distortion Hover Image', 'elegant-elements' ),
			'shortcode' => 'iee_distortion_hover_image',
			'icon'      => 'fa-images fas distortion-hover-image-icon',
			'front-end' => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-distortion-hover-image-preview.php',
			'params'    => array(
				array(
					'type'        => 'upload',
					'heading'     => esc_attr__( 'Image 1', 'elegant-elements' ),
					'description' => esc_attr__( 'Upload the first image of the animation.', 'elegant-elements' ),
					'param_name'  => 'first_image',
					'value'       => '',
				),
				array(
					'type'        => 'upload',
					'heading'     => esc_attr__( 'Image 2', 'elegant-elements' ),
					'description' => esc_attr__( 'Upload the second image of the animation.', 'elegant-elements' ),
					'param_name'  => 'second_image',
					'value'       => '',
				),
				array(
					'type'        => 'upload',
					'heading'     => esc_attr__( 'Displacement Image', 'elegant-elements' ),
					'description' => esc_attr__( 'Upload the image to do the transition between the above two images.', 'elegant-elements' ),
					'param_name'  => 'displacement_image',
					'value'       => '',
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Width', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css width to create empty space between two elements. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'width',
					'value'       => '500',
					'min'         => '1',
					'max'         => '2000',
					'step'        => '1',
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css height for this image. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'height',
					'value'       => '500',
					'min'         => '1',
					'max'         => '2000',
					'step'        => '1',
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Distortion Position', 'elegant-elements' ),
					'description' => esc_attr__( 'Select how you want to animate the image.', 'elegant-elements' ),
					'param_name'  => 'distortion_position',
					'default'     => 'from_left',
					'value'       => array(
						'from_left'  => __( 'From Left', 'elegant-elements' ),
						'from_right' => __( 'From Right', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'tinymce',
					'heading'     => esc_attr__( 'Content on Image', 'elegant-elements' ),
					'param_name'  => 'element_content',
					'value'       => esc_attr__( 'Your content goes here', 'elegant-elements' ),
					'placeholder' => true,
					'description' => esc_attr__( 'Enter content you want to display over the image. Leave blank to display image only.', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Content Overlay Color', 'elegant-elements' ),
					'param_name'  => 'content_overlay',
					'value'       => '',
					'default'     => 'rgba(0,0,0,0.3)',
					'placeholder' => true,
					'description' => esc_attr__( 'Choose the overlay color for the content on image.', 'elegant-elements' ),
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_distortion_hover_image', 99 );
