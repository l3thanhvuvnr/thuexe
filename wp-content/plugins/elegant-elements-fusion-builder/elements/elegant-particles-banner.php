<?php
if ( fusion_is_element_enabled( 'iee_particles_banner' ) && ! class_exists( 'IEE_Particles_Banner' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 3.3.4
	 */
	class IEE_Particles_Banner extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 3.3.4
		 * @var array
		 */
		protected $args;

		/**
		 * Constructor.
		 *
		 * @since 3.3.4
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-particles-banner', array( $this, 'attr' ) );
			add_shortcode( 'iee_particles_banner', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 3.3.4
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			// Add script.
			wp_enqueue_script( 'infi-particles-banner' );

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'background_image'    => '',
					'background_parallax' => '',
					'background_color'    => '',
					'height'              => '',
					'shape'               => '',
					'nb_sides'            => '',
					'shape_color'         => '',
					'shape_stroke_size'   => '',
					'shape_stroke_color'  => '',
					'number_of_particles' => '',
					'density'             => '',
					'density_value_area'  => '',
					'large_particle_size' => '',
					'animate_particles'   => '',
					'line_color'          => '',
					'hide_on_mobile'      => fusion_builder_default_visibility( 'string' ),
					'class'               => '',
					'id'                  => '',
				),
				$args
			);

			$defaults = apply_filters( 'fusion_builder_default_args', $defaults, 'iee_particles_banner', $args );

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/particles-banner/elegant-particles-banner.php' ) ) {
				include locate_template( 'templates/particles-banner/elegant-particles-banner.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/particles-banner/elegant-particles-banner.php';
			}

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 3.3.4
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-particles-banner',
				'style' => '',
			);

			$attr = fusion_builder_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$height         = FusionBuilder::validate_shortcode_attr_value( $this->args['height'], 'px' );
			$attr['style'] .= 'height:' . $height . ';';
			$attr['style'] .= 'background-image: url( ' . $this->args['background_image'] . ' );';
			$attr['style'] .= 'background-position: center center;';
			$attr['style'] .= 'background-color:' . $this->args['background_color'] . ';';
			$attr['style'] .= 'background-size: cover;';

			if ( 'yes' === $this->args['background_parallax'] ) {
				$attr['style'] .= 'background-attachment: fixed;';
				$attr['style'] .= 'background-repeat: no-repeat;';
			}

			$attr['data-shape']               = $this->args['shape'];
			$attr['data-nb_sides']            = $this->args['nb_sides'];
			$attr['data-shape_color']         = $this->args['shape_color'];
			$attr['data-number_of_particles'] = $this->args['number_of_particles'];
			$attr['data-density']             = $this->args['density'];
			$attr['data-density_value_area']  = $this->args['density_value_area'];
			$attr['data-large_particle_size'] = $this->args['large_particle_size'];
			$attr['data-animate_particles']   = $this->args['animate_particles'];
			$attr['data-shape_stroke_size']   = $this->args['shape_stroke_size'];
			$attr['data-shape_stroke_color']  = $this->args['shape_stroke_color'];
			$attr['data-line_color']          = $this->args['line_color'];

			if ( isset( $this->args['class'] ) ) {
				$attr['class'] .= ' ' . $this->args['class'];
			}

			if ( isset( $this->args['id'] ) ) {
				$attr['id'] = $this->args['id'];
			}

			return $attr;
		}
	}

	new IEE_Particles_Banner();
} // End if().

/**
 * Map shortcode for particles_banner.
 *
 * @since 3.3.4
 * @return void
 */
function map_elegant_elements_particles_banner() {
	global $fusion_settings;

	fusion_builder_map(
		array(
			'name'      => esc_attr__( 'Elegant Particles Banner', 'elegant-elements' ),
			'shortcode' => 'iee_particles_banner',
			'icon'      => 'fa-braille fas particles-banner-icon',
			'front-end' => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-particles-banner-preview.php',
			'params'    => array(
				array(
					'type'         => 'tinymce',
					'heading'      => esc_attr__( 'Content', 'elegant-elements' ),
					'description'  => esc_attr__( 'Content to be displayed on the particles banner.', 'elegant-elements' ),
					'param_name'   => 'element_content',
					'dynamic_data' => true,
					'group'        => esc_attr__( 'Content', 'elegant-elements' ),
				),
				array(
					'type'         => 'upload',
					'heading'      => esc_attr__( 'Banner Background Image', 'elegant-elements' ),
					'description'  => esc_attr__( 'Upload the image to be used as banner background.', 'elegant-elements' ),
					'param_name'   => 'background_image',
					'dynamic_data' => true,
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Background Image Parallax', 'elegant-elements' ),
					'description' => esc_attr__( 'Creates background image parallax effect on scroll.', 'elegant-elements' ),
					'param_name'  => 'background_parallax',
					'default'     => 'yes',
					'value'       => array(
						'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
						'no'  => esc_attr__( 'No', 'elegant-elements' ),
					),
					'dependency'  => array(
						array(
							'element'  => 'background_image',
							'value'    => '',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Background Color', 'elegant-elements' ),
					'param_name'  => 'background_color',
					'value'       => '#333333',
					'description' => esc_attr__( 'Controls the banner background color.', 'elegant-elements' ),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the height for this particles banner.', 'elegant-elements' ),
					'param_name'  => 'height',
					'value'       => '500',
					'min'         => '100',
					'max'         => '1500',
					'step'        => '1',
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Particle Shape Type', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the shape of the particle.', 'elegant-elements' ),
					'param_name'  => 'shape',
					'default'     => 'circle',
					'value'       => array(
						'circle'   => __( 'Circle', 'elegant-elements' ),
						'edge'     => __( 'Edge', 'elegant-elements' ),
						'triangle' => __( 'Triangle', 'elegant-elements' ),
						'polygon'  => __( 'Polygon', 'elegant-elements' ),
						'star'     => __( 'Star', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Number of Sides', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the number of sides for the polygon size.', 'elegant-elements' ),
					'param_name'  => 'nb_sides',
					'value'       => '5',
					'min'         => '2',
					'max'         => '10',
					'step'        => '1',
					'dependency'  => array(
						array(
							'element'  => 'shape',
							'value'    => 'polygon',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Particle Shape Color', 'elegant-elements' ),
					'param_name'  => 'shape_color',
					'value'       => '#222222',
					'description' => esc_attr__( 'Controls the particle shape color.', 'elegant-elements' ),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Particle Shape Stroke Width', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the size of the particle shape stroke.', 'elegant-elements' ),
					'param_name'  => 'shape_stroke_size',
					'value'       => '2',
					'min'         => '1',
					'max'         => '100',
					'step'        => '1',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Particle Shape Stroke Color', 'elegant-elements' ),
					'param_name'  => 'shape_stroke_color',
					'value'       => '#ffffff',
					'description' => esc_attr__( 'Controls the particle shape stroke color.', 'elegant-elements' ),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Number of Particles', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the number of particles to be added.', 'elegant-elements' ),
					'param_name'  => 'number_of_particles',
					'value'       => '50',
					'min'         => '10',
					'max'         => '500',
					'step'        => '1',
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Particle Density', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose whether you want to enable particle density or disable it.', 'elegant-elements' ),
					'param_name'  => 'density',
					'default'     => 'enable',
					'value'       => array(
						'enable'  => esc_attr__( 'Enable', 'elegant-elements' ),
						'disable' => esc_attr__( 'Disable', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Density value area', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the density value. Lower the value area = higher the particle dentisity.', 'elegant-elements' ),
					'param_name'  => 'density_value_area',
					'value'       => '500',
					'min'         => '100',
					'max'         => '5000',
					'step'        => '1',
					'dependency'  => array(
						array(
							'element'  => 'density',
							'value'    => 'enable',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Large Particle Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the size of the large particle shape.', 'elegant-elements' ),
					'param_name'  => 'large_particle_size',
					'value'       => '3',
					'min'         => '1',
					'max'         => '10',
					'step'        => '1',
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Animate Particles', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose whether you want to animate the particles or display as static size.', 'elegant-elements' ),
					'param_name'  => 'animate_particles',
					'default'     => 'no',
					'value'       => array(
						'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
						'no'  => esc_attr__( 'No', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Particle Line Color', 'elegant-elements' ),
					'param_name'  => 'line_color',
					'value'       => '#ffffff',
					'description' => esc_attr__( 'Controls the connector line color.', 'elegant-elements' ),
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_particles_banner', 99 );
