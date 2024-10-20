<?php
if ( fusion_is_element_enabled( 'iee_image_compare' ) && ! class_exists( 'IEE_Image_Compare' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.1.0
	 */
	class IEE_Image_Compare extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.1.0
		 * @var array
		 */
		protected $args;

		/**
		 * Constructor.
		 *
		 * @since 1.1.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-image-compare', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_elegant-image-compare-label-before', array( $this, 'before_image_attr' ) );
			add_filter( 'fusion_attr_elegant-image-compare-label-after', array( $this, 'after_image_attr' ) );
			add_filter( 'fusion_attr_elegant-image-compare-handle', array( $this, 'drag_handle_attr' ) );

			add_shortcode( 'iee_image_compare', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 1.1.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			$this->args = $args;

			$html = '';

			if ( '' !== locate_template( 'templates/image-compare/elegant-image-compare.php' ) ) {
				include locate_template( 'templates/image-compare/elegant-image-compare.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/image-compare/elegant-image-compare.php';
			}

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-image-compare',
			);

			if ( isset( $this->args['image_caption_position'] ) && '' !== $this->args['image_caption_position'] ) {
				$attr['class'] .= ' image-caption-position-' . $this->args['image_caption_position'];
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
		 * @since 1.1.0
		 * @return array
		 */
		public function before_image_attr() {
			$attr = array(
				'class' => 'elegant-image-compare-label',
				'style' => '',
			);

			$attr['data-type'] = 'modified';

			if ( isset( $this->args['before_image_caption_color'] ) && '' !== $this->args['before_image_caption_color'] ) {
				$attr['style'] .= 'color: ' . $this->args['before_image_caption_color'] . ';';
			}

			if ( isset( $this->args['before_image_caption_background_color'] ) && '' !== $this->args['before_image_caption_background_color'] ) {
				$attr['style'] .= 'background-color: ' . $this->args['before_image_caption_background_color'] . ';';
			}

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return array
		 */
		public function after_image_attr() {
			$attr = array(
				'class' => 'elegant-image-compare-label',
				'style' => '',
			);

			$attr['data-type'] = 'original';

			if ( isset( $this->args['after_image_caption_color'] ) && '' !== $this->args['after_image_caption_color'] ) {
				$attr['style'] .= 'color: ' . $this->args['after_image_caption_color'] . ';';
			}

			if ( isset( $this->args['after_image_caption_background_color'] ) && '' !== $this->args['after_image_caption_background_color'] ) {
				$attr['style'] .= 'background-color: ' . $this->args['after_image_caption_background_color'] . ';';
			}

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return array
		 */
		public function drag_handle_attr() {
			$attr = array(
				'class' => 'elegant-image-compare-handle',
				'style' => '',
			);

			if ( isset( $this->args['handle_background_color'] ) && '' !== $this->args['handle_background_color'] ) {
				$attr['style'] .= 'background-color: ' . $this->args['handle_background_color'] . ';';
			}

			return $attr;
		}

		/**
		 * Sets the necessary scripts.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return void
		 */
		public function add_scripts() {
			global $elegant_js_folder_url, $elegant_js_folder_path, $elegant_css_folder_url, $elegant_css_folder_path;

			Fusion_Dynamic_JS::enqueue_script(
				'infi-elegant-image-compare-mobile',
				$elegant_js_folder_url . '/jquery.mobile.custom.min.js',
				$elegant_js_folder_path . '/jquery.mobile.custom.min.js',
				array( 'jquery' ),
				'1',
				true
			);
			Fusion_Dynamic_JS::enqueue_script(
				'infi-elegant-image-compare',
				$elegant_js_folder_url . '/infi-elegant-image-compare.min.js',
				$elegant_js_folder_path . '/infi-elegant-image-compare.min.js',
				array( 'infi-elegant-image-compare-mobile' ),
				'1',
				true
			);
		}
	}

	new IEE_Image_Compare();
} // End if().


/**
 * Map shortcode for image_compare.
 *
 * @since 1.1.0
 * @return void
 */
function map_elegant_elements_image_compare() {
	global $fusion_settings;

	fusion_builder_map(
		array(
			'name'       => esc_attr__( 'Elegant Image Compare', 'elegant-elements' ),
			'shortcode'  => 'iee_image_compare',
			'icon'       => 'icon-compare',
			'preview'    => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/previews/infi-image-compare-preview.php',
			'preview_id' => 'elegant-elements-module-infi-image-compare-preview-template',
			'front-end'  => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-image-compare-preview.php',
			'params'     => array(
				array(
					'type'        => 'upload',
					'heading'     => esc_attr__( 'Before Image', 'elegant-elements' ),
					'param_name'  => 'before_image',
					'value'       => '',
					'description' => esc_attr__( 'Upload the image to be displayed as before image.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Before Image Caption', 'elegant-elements' ),
					'param_name'  => 'before_image_caption',
					'value'       => esc_attr__( 'Original', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter text to be displayed for the original image or "Before" image.', 'elegant-elements' ),
				),
				array(
					'type'        => 'upload',
					'heading'     => esc_attr__( 'After Image', 'elegant-elements' ),
					'param_name'  => 'after_image',
					'value'       => '',
					'description' => esc_attr__( 'Upload the image to be displayed as after image.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'After Image Caption', 'elegant-elements' ),
					'param_name'  => 'after_image_caption',
					'value'       => esc_attr__( 'Modified', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter text to be displayed for the modified image or "After" image.', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Before Image Caption Color', 'elegant-elements' ),
					'param_name'  => 'before_image_caption_color',
					'value'       => '',
					'description' => esc_attr__( 'Choose the text color for original image caption text.', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Before Image Caption Background Color', 'elegant-elements' ),
					'param_name'  => 'before_image_caption_background_color',
					'value'       => '',
					'description' => esc_attr__( 'Choose the background color for original image caption text.', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'After Image Caption Color', 'elegant-elements' ),
					'param_name'  => 'after_image_caption_color',
					'value'       => '',
					'description' => esc_attr__( 'Choose the text color for modified image caption text.', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'After Image Caption Background Color', 'elegant-elements' ),
					'param_name'  => 'after_image_caption_background_color',
					'value'       => '',
					'description' => esc_attr__( 'Choose the background color for modified image caption text.', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Drag Handle Background Color', 'elegant-elements' ),
					'param_name'  => 'handle_background_color',
					'value'       => '',
					'description' => esc_attr__( 'Choose the background color for image drag handle.', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Caption Position', 'elegant-elements' ),
					'param_name'  => 'image_caption_position',
					'default'     => 'bottom',
					'value'       => array(
						'top'    => esc_attr__( 'Top', 'elegant-elements' ),
						'middle' => esc_attr__( 'Middle', 'elegant-elements' ),
						'bottom' => esc_attr__( 'Bottom', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Choose the image caption position on the image.', 'elegant-elements' ),
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_image_compare', 99 );
