<?php
if ( fusion_is_element_enabled( 'iee_image_overlay_button' ) && ! class_exists( 'IEE_Image_Overlay_Button' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 3.6.0
	 */
	class IEE_Image_Overlay_Button extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 3.6.0
		 * @var array
		 */
		protected $args;

		/**
		 * Constructor.
		 *
		 * @since 3.6.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-image-overlay-button-wrapper', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_elegant-image-overlay-button-image', array( $this, 'attr_image' ) );
			add_filter( 'fusion_attr_elegant-image-overlay-button', array( $this, 'attr_button' ) );
			add_filter( 'fusion_attr_elegant-image-overlay-button-overlay', array( $this, 'attr_overlay' ) );

			add_shortcode( 'iee_image_overlay_button', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 3.6.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'image'                  => '',
					'image_retina'           => '',
					'width'                  => '400',
					'overlay_color'          => '#f2f2f2',
					'overlay_appearance'     => 'fade',
					'image_background_color' => 'rgba(0,0,0,0.6)',
					'hide_on_mobile'         => fusion_builder_default_visibility( 'string' ),
					'class'                  => '',
					'id'                     => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/image-overlay-button/elegant-image-overlay-button.php' ) ) {
				include locate_template( 'templates/image-overlay-button/elegant-image-overlay-button.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/image-overlay-button/elegant-image-overlay-button.php';
			}

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 3.6.0
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-image-overlay-button-wrapper',
				'style' => '',
			);

			$attr['class'] .= ' elegant-align-center';

			$attr = fusion_builder_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$attr['style'] .= 'background-color:' . $this->args['image_background_color'];

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
		 * @since 3.6.0
		 * @return array
		 */
		public function attr_image() {
			$attr = array(
				'class' => 'image-overlay-button-image',
			);

			$attr['src'] = $this->args['image'];
			$attr['alt'] = basename( $this->args['image'] );

			if ( isset( $this->args['image_retina'] ) && '' !== $this->args['image_retina'] ) {
				$attr['srcset']  = $this->args['image'] . ' 1x, ';
				$attr['srcset'] .= $this->args['image_retina'] . ' 2x ';
			}

			$attr['style'] = 'max-width:' . FusionBuilder::validate_shortcode_attr_value( $this->args['width'], 'px' ) . ';';

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 3.6.0
		 * @return array
		 */
		public function attr_button() {
			$attr = array(
				'class' => 'image-overlay-button',
			);

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 3.6.0
		 * @return array
		 */
		public function attr_overlay() {
			$attr = array(
				'class' => 'elegant-image-overlay',
				'style' => '',
			);

			$attr['class'] .= ' overlay-appearance-' . $this->args['overlay_appearance'];

			$attr['style'] .= 'background-color:' . $this->args['overlay_color'];

			return $attr;
		}
	}

	new IEE_Image_Overlay_Button();
} // End if().

/**
 * Map shortcode for image_overlay_button.
 *
 * @since 3.6.0
 * @return void
 */
function map_elegant_elements_image_overlay_button() {
	global $fusion_settings;

	fusion_builder_map(
		array(
			'name'                      => esc_attr__( 'Elegant Image Overlay Button', 'elegant-elements' ),
			'shortcode'                 => 'iee_image_overlay_button',
			'icon'                      => 'fa-images fas image-overlay-button-icon',
			'custom_settings_view_name' => 'ModuleSettingElegantView',
			'params'                    => array(
				array(
					'type'         => 'upload',
					'heading'      => esc_attr__( 'Image', 'elegant-elements' ),
					'description'  => esc_attr__( 'Upload or select the image.', 'elegant-elements' ),
					'param_name'   => 'image',
					'dynamic_data' => true,
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Image ID', 'elegant-elements' ),
					'description' => esc_attr__( 'Upload or select the image.', 'elegant-elements' ),
					'param_name'  => 'image_id',
					'hidden'      => true,
				),
				array(
					'type'         => 'upload',
					'heading'      => esc_attr__( 'Retina Image', 'elegant-elements' ),
					'description'  => esc_attr__( 'Upload or select the image to be used on retina devices.', 'elegant-elements' ),
					'param_name'   => 'image_retina',
					'dynamic_data' => true,
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Retina Image ID', 'elegant-elements' ),
					'description' => esc_attr__( 'Upload or select the retina image.', 'elegant-elements' ),
					'param_name'  => 'image_retina_id',
					'hidden'      => true,
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Image Max Width', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the maximum css width for the image. Height will change in the proportion automatically. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'width',
					'value'       => '320',
					'min'         => '50',
					'max'         => '2000',
					'step'        => '1',
				),
				array(
					'type'             => 'textarea',
					'heading'          => esc_attr__( 'Button', 'elegant-elements' ),
					'param_name'       => 'element_content_placeholder',
					'value'            => '',
					'remove_from_atts' => true,
					'description'      => __( 'Click the link to generate button shortcode. Please remove the previous shortcode before generating new one.<br/><p class="elegant-element-shortcode-generate"><a href="#" class="elegant-elements-add-shortcode" data-type="fusion_button" data-editor-clone-id="element_content_placeholder" data-editor-id="element_content">Generate Button Shortcode</a></p>', 'elegant-elements' ),
					'hidden'           => true,
				),
				array(
					'type'        => 'textarea',
					'heading'     => esc_attr__( 'Button Shortcode', 'elegant-elements' ),
					'param_name'  => 'element_content',
					'value'       => '',
					'description' => __( 'Click the link to generate button shortcode. Please remove the previous shortcode before generating new one.<br/><p class="elegant-element-shortcode-generate"><a href="#" class="elegant-elements-add-shortcode" data-type="fusion_button" data-editor-clone-id="element_content" data-editor-id="element_content">Generate Button Shortcode</a></p>', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Overlay Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the overlay color.', 'elegant-elements' ),
					'param_name'  => 'overlay_color',
					'value'       => 'rgba(0,0,0,0.6)',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Overlay Appearance', 'elegant-elements' ),
					'description' => __( 'Select how the overlay should appear.', 'elegant-elements' ),
					'default'     => 'fade',
					'param_name'  => 'overlay_appearance',
					'value'       => array(
						'fade'         => esc_attr__( 'Fade', 'elegant-elements' ),
						'slide_left'   => esc_attr__( 'Slide From Left', 'elegant-elements' ),
						'slide_right'  => esc_attr__( 'Slide From Right', 'elegant-elements' ),
						'slide_top'    => esc_attr__( 'Slide From Top', 'elegant-elements' ),
						'slide_bottom' => esc_attr__( 'Slide From Bottom', 'elegant-elements' ),
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Image Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the image background color. If the image does not fit in the column, this background color will fill the empty space.', 'elegant-elements' ),
					'param_name'  => 'image_background_color',
					'value'       => '#f2f2f2',
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_image_overlay_button', 99 );
