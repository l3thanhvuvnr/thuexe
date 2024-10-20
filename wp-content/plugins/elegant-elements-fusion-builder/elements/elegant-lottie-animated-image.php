<?php
if ( fusion_is_element_enabled( 'iee_lottie_animated_image' ) && ! class_exists( 'IEE_Lottie_Animated_Image' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 2.5
	 */
	class IEE_Lottie_Animated_Image extends Fusion_Element {

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

			add_filter( 'fusion_attr_elegant-lottie-image', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_elegant-lottie-player', array( $this, 'player_attr' ) );

			add_shortcode( 'iee_lottie_animated_image', array( $this, 'render' ) );

			// Allow JSON file upload.
			add_filter( 'upload_mimes', array( $this, 'add_json_mime_type' ), 1 );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 2.5
		 * @param  array  $atts    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $atts, $content = '' ) {
			global $fusion_library, $fusion_settings;

			// Enqueue Lottie Player js.
			wp_enqueue_script( 'infi-lottie-player' );

			$heart_animation_json_url = ELEGANT_ELEMENTS_PLUGIN_URL . 'assets/json/heart-animation.json';

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'json_url'         => $heart_animation_json_url,
					'hide_on_mobile'   => fusion_builder_default_visibility( 'string' ),
					'height'           => 300,
					'width'            => 300,
					'animation_mode'   => 'normal',
					'animation_play'   => 'autoplay',
					'animation_loop'   => 'yes',
					'background_color' => '',
					'class'            => '',
					'id'               => '',
				),
				$atts
			);

			$defaults = apply_filters( 'fusion_builder_default_args', $defaults, 'iee_lottie_animated_image', $atts );

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/lottie-image/elegant-lottie-image.php' ) ) {
				include locate_template( 'templates/lottie-image/elegant-lottie-image.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/lottie-image/elegant-lottie-image.php';
			}

			return $html;
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
				'class' => 'elegant-lottie-image',
				'style' => '',
			);

			$attr = fusion_builder_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$height         = FusionBuilder::validate_shortcode_attr_value( $this->args['height'], 'px' );
			$width          = FusionBuilder::validate_shortcode_attr_value( $this->args['width'], 'px' );
			$attr['style'] .= 'height:' . $height . ';';
			$attr['style'] .= 'width:' . $width . ';';

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
		public function player_attr() {
			$attr = array(
				'src'        => $this->args['json_url'],
				'background' => $this->args['background_color'],
				'speed'      => 1,
				'style'      => '',
			);

			if ( 'bounce' === $this->args['animation_mode'] ) {
				$attr['mode'] = 'bounce';
			}

			if ( 'yes' === $this->args['animation_loop'] ) {
				$attr['loop'] = true;
			}

			$attr[ $this->args['animation_play'] ] = true;

			$height         = FusionBuilder::validate_shortcode_attr_value( $this->args['height'], 'px' );
			$width          = FusionBuilder::validate_shortcode_attr_value( $this->args['width'], 'px' );
			$attr['style'] .= 'height:' . $height . ';';
			$attr['style'] .= 'width:' . $width . ';';

			return $attr;
		}

		/**
		 * Allow JSON file upload in the media library.
		 *
		 * @access public
		 * @since 2.5
		 * @param array $mime_types Current array of mime types.
		 * @return array Updated array of mime types with JSON type included.
		 */
		public function add_json_mime_type( $mime_types ) {

			$mime_types['json'] = 'text/plain';

			return $mime_types;
		}
	}

	new IEE_Lottie_Animated_Image();
} // End if().

/**
 * Map shortcode for lottie_animated_image.
 *
 * @since 2.5
 * @return void
 */
function map_elegant_elements_lottie_animated_image() {
	global $fusion_settings;
	$heart_animation_json_url = ELEGANT_ELEMENTS_PLUGIN_URL . 'assets/json/heart-animation.json';

	fusion_builder_map(
		array(
			'name'      => esc_attr__( 'Elegant Lottie Animated Image', 'elegant-elements' ),
			'shortcode' => 'iee_lottie_animated_image',
			'icon'      => 'fa-magic fas lottie-image-icon',
			'front-end' => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-lottie-image-preview.php',
			'params'    => array(
				array(
					'type'        => 'uploadfile',
					'heading'     => esc_attr__( 'Lottie Animation JSON File.', 'elegant-elements' ),
					'description' => esc_attr__( 'Upload the Lottie JSON file or enter the Lottie image animation url from https://lottiefiles.com.', 'elegant-elements' ),
					'param_name'  => 'json_url',
					'value'       => $heart_animation_json_url,
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css height for this image. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'height',
					'value'       => '300',
					'min'         => '1',
					'max'         => '2000',
					'step'        => '1',
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Width', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css width for this image. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'width',
					'value'       => '300',
					'min'         => '1',
					'max'         => '2000',
					'step'        => '1',
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Play Animation', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls if the animation should start as autoplay or on mouse hover.', 'elegant-elements' ),
					'param_name'  => 'animation_play',
					'value'       => array(
						'autoplay' => 'Autoplay',
						'hover'    => 'Hover',
					),
					'default'     => 'autoplay',
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Play Animation in Loop', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls if the animation should play continuously in loop or only once.', 'elegant-elements' ),
					'param_name'  => 'animation_loop',
					'value'       => array(
						'yes' => 'Yes',
						'no'  => 'No',
					),
					'default'     => 'yes',
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Play Mode', 'elegant-elements' ),
					'description' => esc_attr__( 'Normal mode will play animation in one direction and the bounce mode will play animation in revese after the normal animation.', 'elegant-elements' ),
					'param_name'  => 'animation_mode',
					'value'       => array(
						'normal' => 'Normal',
						'bounce' => 'Bounce',
					),
					'default'     => 'normal',
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the background color for the image.', 'elegant-elements' ),
					'param_name'  => 'background_color',
					'value'       => '',
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_lottie_animated_image', 99 );
