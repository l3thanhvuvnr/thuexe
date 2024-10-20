<?php
if ( fusion_is_element_enabled( 'iee_retina_image' ) && ! class_exists( 'IEE_Retina_Image' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 2.3
	 */
	class IEE_Retina_Image extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 2.3
		 * @var array
		 */
		protected $args;

		/**
		 * Constructor.
		 *
		 * @since 2.3
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-retina-image', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_elegant-retina-image-src', array( $this, 'attr_image' ) );

			add_shortcode( 'iee_retina_image', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 2.3
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'image'          => '',
					'image_retina'   => '',
					'width'          => '400',
					'link_url'       => '',
					'target'         => '',
					'alignment'      => 'left',
					'hide_on_mobile' => fusion_builder_default_visibility( 'string' ),
					'class'          => '',
					'id'             => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/retina-image/elegant-retina-image.php' ) ) {
				include locate_template( 'templates/retina-image/elegant-retina-image.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/retina-image/elegant-retina-image.php';
			}

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 2.3
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-retina-image',
			);

			$attr['class'] .= ' fusion-align' . $this->args['alignment'];

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
		 * @since 2.3
		 * @return array
		 */
		public function attr_image() {
			$attr = array(
				'class' => 'retina-image',
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
	}

	new IEE_Retina_Image();
} // End if().

/**
 * Map shortcode for retina_image.
 *
 * @since 2.3
 * @return void
 */
function map_elegant_elements_retina_image() {
	global $fusion_settings;

	fusion_builder_map(
		array(
			'name'       => esc_attr__( 'Elegant Retina Image', 'elegant-elements' ),
			'shortcode'  => 'iee_retina_image',
			'icon'       => 'fa-eye fas retina-image-icon',
			'preview'    => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/previews/infi-retina-image-preview.php',
			'preview_id' => 'elegant-elements-module-infi-retina-image-preview-template',
			'front-end'  => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-retina-image-preview.php',
			'params'     => array(
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
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Alignment', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the image and video alignment.', 'elegant-elements' ),
					'param_name'  => 'alignment',
					'value'       => array(
						'none'   => 'Text Flow',
						'left'   => 'Left',
						'center' => 'Center',
						'right'  => 'Right',
					),
					'default'     => 'left',
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_retina_image', 99 );
