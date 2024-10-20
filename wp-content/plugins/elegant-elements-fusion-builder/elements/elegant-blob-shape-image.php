<?php
if ( fusion_is_element_enabled( 'iee_blob_shape_image' ) && ! class_exists( 'IEE_Blob_Shape_Image' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 2.5
	 */
	class IEE_Blob_Shape_Image extends Fusion_Element {

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

			add_filter( 'fusion_attr_elegant-blob-shape-image-wrapper', array( $this, 'wrapper_attr' ) );
			add_filter( 'fusion_attr_elegant-blob-shape-image', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_elegant-blob-shape-image-background', array( $this, 'background_attr' ) );
			add_filter( 'fusion_attr_elegant-blob-shape-image-content', array( $this, 'content_attr' ) );

			add_shortcode( 'iee_blob_shape_image', array( $this, 'render' ) );
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

			if ( isset( $args['animate_blob'] ) && 'yes' === $args['animate_blob'] ) {
				wp_enqueue_style( 'infi-elegant-animated-blob-shape-image' );
			}

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'blob_shape'                => '59% 41% 41% 59% / 29% 48% 52% 71%',
					'width'                     => 400,
					'height'                    => 400,
					'animate_blob'              => 'no',
					'alignment'                 => 'left',
					'image'                     => '',
					'link_url'                  => '',
					'target'                    => '',
					'background_color_1'        => '',
					'background_color_2'        => '',
					'gradient_angle'            => '45',
					'fade_offset'               => '50',
					'background_color_1_offset' => '0',
					'background_color_2_offset' => '100',
					'hide_on_mobile'            => fusion_builder_default_visibility( 'string' ),
					'class'                     => '',
					'id'                        => '',
				),
				$args
			);

			$defaults = apply_filters( 'fusion_builder_default_args', $defaults, 'iee_blob_shape_image', $args );

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/blob-shape-image/elegant-blob-shape-image.php' ) ) {
				include locate_template( 'templates/blob-shape-image/elegant-blob-shape-image.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/blob-shape-image/elegant-blob-shape-image.php';
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
		public function wrapper_attr() {
			$attr = array(
				'class' => 'elegant-blob-shape-image-wrapper',
				'style' => '',
			);

			$attr['class'] .= ' animate-blob-' . $this->args['animate_blob'];

			$attr = fusion_builder_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$attr['class'] .= ' fusion-clearfix';
			$attr['style'] .= 'display: flex;';

			if ( 'center' === $this->args['alignment'] ) {
				$attr['style'] .= 'justify-content: center;';
			} elseif ( 'right' === $this->args['alignment'] ) {
				$attr['style'] .= 'justify-content: flex-end;';
			} else {
				$attr['style'] .= 'justify-content: flex-start;';
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
		 * @since 2.5
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-blob-shape-image',
				'style' => '',
			);

			$height = FusionBuilder::validate_shortcode_attr_value( $this->args['height'], 'px' );
			$width  = FusionBuilder::validate_shortcode_attr_value( $this->args['width'], 'px' );

			$attr['style'] .= 'height:' . $height . ';';
			$attr['style'] .= 'width:' . $width . ';';
			$attr['style'] .= 'max-width: 100%;';
			$attr['style'] .= 'border-radius:' . $this->args['blob_shape'] . ';';
			$attr['style'] .= elegant_gradient_color( $this->args['gradient_angle'], $this->args['background_color_1'], $this->args['background_color_2'], $this->args['fade_offset'], $this->args['background_color_1_offset'], $this->args['background_color_2_offset'] );

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 2.5
		 * @return array
		 */
		public function background_attr() {
			$attr = array(
				'class' => 'elegant-blob-shape-image-background',
				'style' => '',
			);

			if ( '' !== $this->args['image'] ) {
				$attr['style'] .= 'background-image:url(' . $this->args['image'] . ');';
				$attr['style'] .= 'background-blend-mode: overlay;';
				$attr['style'] .= 'background-size: cover;';
				$attr['style'] .= 'background-repeat: no-repeat;';
				$attr['style'] .= 'background-position: center;';
				$attr['style'] .= 'height: 100%;';
				$attr['style'] .= 'border-radius: inherit;';

				if ( '' !== $this->args['background_color_1'] ) {
					$attr['style'] .= 'mix-blend-mode: overlay;';
				}
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
		public function content_attr() {
			$attr = array(
				'class' => 'elegant-blob-shape-image-content',
				'style' => '',
			);

			$attr['style'] .= 'transform: translate(0%, -100%);';
			$attr['style'] .= 'height: inherit;';
			$attr['style'] .= 'display: flex;';
			$attr['style'] .= 'flex-direction: column;';
			$attr['style'] .= 'align-items: center;';
			$attr['style'] .= 'justify-content: center;';
			$attr['style'] .= 'padding: 40px;';

			return $attr;
		}
	}

	new IEE_Blob_Shape_Image();
} // End if().

/**
 * Map shortcode for blob_shape_image.
 *
 * @since 2.5
 * @return void
 */
function map_elegant_elements_blob_shape_image() {
	global $fusion_settings;

	fusion_builder_map(
		array(
			'name'      => esc_attr__( 'Elegant Blob Shape Image', 'elegant-elements' ),
			'shortcode' => 'iee_blob_shape_image',
			'icon'      => 'fa-shapes fas blob-shape-image-icon',
			'front-end' => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-blob-shape-image-preview.php',
			'params'    => array(
				array(
					'type'        => 'elegant_blob_shape_generator',
					'heading'     => esc_attr__( 'Blob Shape', 'elegant-elements' ),
					'description' => esc_attr__( 'Click the button to generate blob shape.', 'elegant-elements' ),
					'param_name'  => 'blob_shape',
					'value'       => '59% 41% 41% 59% / 29% 48% 52% 71%',
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css height for this blob shape. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'height',
					'value'       => '400',
					'min'         => '1',
					'max'         => '1200',
					'step'        => '1',
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Width', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css width for this blob shape. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'width',
					'value'       => '400',
					'min'         => '1',
					'max'         => '1200',
					'step'        => '1',
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Animate Blob', 'elegant-elements' ),
					'param_name'  => 'animate_blob',
					'default'     => 'no',
					'value'       => array(
						'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
						'no'  => esc_attr__( 'No', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Animate this blob shape.', 'elegant-elements' ),
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
					'description' => esc_attr__( 'Align the blob to left, right or center.', 'elegant-elements' ),
				),
				array(
					'type'         => 'link_selector',
					'heading'      => esc_attr__( 'Link URL', 'elegant-elements' ),
					'param_name'   => 'link_url',
					'value'        => '',
					'dynamic_data' => true,
					'description'  => esc_attr__( 'Enter the external url or select existing page to link to.', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Link Target', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the link target.', 'elegant-elements' ),
					'param_name'  => 'target',
					'default'     => '_self',
					'value'       => array(
						'_self'  => esc_attr__( 'Current Tab', 'elegant-elements' ),
						'_blank' => esc_attr__( 'New Tab', 'elegant-elements' ),
					),
					'dependency'  => array(
						array(
							'element'  => 'link_url',
							'value'    => '',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'         => 'upload',
					'heading'      => __( 'Image', 'elegant-elements' ),
					'description'  => esc_attr__( 'The background image for this blob shape.', 'elegant-elements' ),
					'param_name'   => 'image',
					'value'        => '',
					'dynamic_data' => true,
					'group'        => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => __( 'Background Color 1', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the background color for the blob.', 'elegant-elements' ),
					'param_name'  => 'background_color_1',
					'value'       => 'rgba(174,0,255,0.5)',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => __( 'Background Color 2', 'elegant-elements' ),
					'description' => esc_attr__( 'If set, both colors will form a gradient color.', 'elegant-elements' ),
					'param_name'  => 'background_color_2',
					'value'       => 'rgba(255,106,0,0.5)',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Gradient Angle', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the gradient angle.', 'elegant-elements' ),
					'param_name'  => 'gradient_angle',
					'value'       => '45',
					'min'         => '0',
					'max'         => '180',
					'step'        => '1',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'background_color_2',
							'value'    => '',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Fade Offset', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the gradient fade offset.', 'elegant-elements' ),
					'param_name'  => 'fade_offset',
					'value'       => '50',
					'min'         => '0',
					'max'         => '100',
					'step'        => '1',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'background_color_2',
							'value'    => '',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Background Color 1 Offset', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the background color 1 offset.', 'elegant-elements' ),
					'param_name'  => 'background_color_1_offset',
					'value'       => '0',
					'min'         => '0',
					'max'         => '100',
					'step'        => '1',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'background_color_2',
							'value'    => '',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Background Color 2 Offset', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the background color 2 offset.', 'elegant-elements' ),
					'param_name'  => 'background_color_2_offset',
					'value'       => '100',
					'min'         => '0',
					'max'         => '100',
					'step'        => '1',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'background_color_2',
							'value'    => '',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'tinymce',
					'heading'     => esc_attr__( 'Content on Image', 'elegant-elements' ),
					'param_name'  => 'element_content',
					'value'       => '',
					'description' => esc_attr__( 'Enter content you want to display over the image. Leave blank to display image only.', 'elegant-elements' ),
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_blob_shape_image', 99 );
