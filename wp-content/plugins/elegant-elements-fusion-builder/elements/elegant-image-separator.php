<?php
if ( fusion_is_element_enabled( 'iee_image_separator' ) && ! class_exists( 'IEE_Image_Separator' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 2.1.0
	 */
	class IEE_Image_Separator extends Fusion_Element {

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

			add_filter( 'fusion_attr_elegant-image-separator', array( $this, 'attr' ) );
			add_shortcode( 'iee_image_separator', array( $this, 'render' ) );
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

			$this->args = $args;

			$html = '';

			if ( '' !== locate_template( 'templates/image-separator/elegant-image-separator.php' ) ) {
				include locate_template( 'templates/image-separator/elegant-image-separator.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/image-separator/elegant-image-separator.php';
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
				'class' => 'elegant-image-separator',
				'style' => '',
			);

			$attr['class'] .= ' image-separator-' . $this->args['type'];

			$attr = fusion_builder_visibility_atts( $this->args['hide_on_mobile'], $attr );

			if ( $this->args['width'] ) {
				$attr['style'] .= 'width:' . FusionBuilder::validate_shortcode_attr_value( $this->args['width'], 'px' ) . ';';
			}

			if ( $this->args['height'] ) {
				$attr['style'] .= 'height:' . FusionBuilder::validate_shortcode_attr_value( $this->args['height'], 'px' ) . ';';
			}

			if ( 'vertical' === $this->args['type'] ) {
				$attr['style'] .= 'top: calc( 50% - ' . FusionBuilder::validate_shortcode_attr_value( $this->args['height'], 'px' ) . ' );';
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
		 * Sets the necessary scripts.
		 *
		 * @access public
		 * @since 2.1.0
		 * @return void
		 */
		public function add_scripts() {
			global $elegant_js_folder_url, $elegant_js_folder_path;

			Fusion_Dynamic_JS::enqueue_script(
				'infi-elegant-image-separator',
				$elegant_js_folder_url . '/infi-elegant-image-separator.min.js',
				$elegant_js_folder_path . '/infi-elegant-image-separator.min.js',
				array( 'jquery' ),
				'1',
				true
			);
		}
	}

	new IEE_Image_Separator();
} // End if().

/**
 * Map shortcode for image_separator.
 *
 * @since 2.1.0
 * @return void
 */
function map_elegant_elements_image_separator() {
	global $fusion_settings;

	fusion_builder_map(
		array(
			'name'      => esc_attr__( 'Elegant Image Separator', 'elegant-elements' ),
			'shortcode' => 'iee_image_separator',
			'icon'      => 'fa-image far image-separator-icon',
			'front-end' => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-image-separator-preview.php',
			'params'    => array(
				array(
					'type'        => 'upload',
					'heading'     => esc_attr__( 'Separator Image', 'elegant-elements' ),
					'param_name'  => 'image',
					'value'       => '',
					'description' => esc_attr__( 'Select the image to be used for separator.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Separator Image ID', 'elegant-elements' ),
					'param_name'  => 'image_id',
					'hidden'      => true,
					'value'       => '',
					'description' => esc_attr__( 'Image ID to be used for separator.', 'elegant-elements' ),
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Separator Type', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the image separator placement, vertical ( for columns ) or horizontal ( for containers ).', 'elegant-elements' ),
					'param_name'  => 'type',
					'default'     => 'horizontal',
					'value'       => array(
						'horizontal' => __( 'Horizontal ( For Containers )', 'elegant-elements' ),
						'vertical'   => __( 'Vertical ( For Columns )', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Image Width', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css width for this image. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'width',
					'value'       => '120',
					'min'         => '1',
					'max'         => '1000',
					'step'        => '1',
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Image Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css height for this image. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'height',
					'value'       => '120',
					'min'         => '1',
					'max'         => '1000',
					'step'        => '1',
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_image_separator', 99 );
