<?php
if ( fusion_is_element_enabled( 'iee_image_swap' ) && ! class_exists( 'IEE_Image_Swap' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 3.3.0
	 */
	class IEE_Image_Swap extends Fusion_Element {

		/**
		 * An array of the parent shortcode arguments.
		 *
		 * @access protected
		 * @since 3.3.0
		 * @var array
		 */
		protected $args;

		/**
		 * Image counter.
		 *
		 * @since 3.3.0
		 * @access private
		 * @var object
		 */
		private $image_counter = 0;

		/**
		 * Constructor.
		 *
		 * @since 3.3.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-image-swap', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_elegant-image-swap-main', array( $this, 'main_image_attr' ) );
			add_filter( 'fusion_attr_elegant-image-swap-hover', array( $this, 'swap_image_attr' ) );

			add_shortcode( 'iee_image_swap', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 3.3.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'image_url'            => '',
					'image_id'             => '',
					'swap_image_url'       => '',
					'swap_image_id'        => '',
					'image_width'          => 400,
					'alignment'            => 'left',
					'click_action'         => 'none',
					'lightbox_image'       => '',
					'lightbox_image_meta'  => '',
					'modal_anchor'         => '',
					'url'                  => '',
					'target'               => '_blank',
					'images_border_radius' => '4',
					'hide_on_mobile'       => fusion_builder_default_visibility( 'string' ),
					'class'                => '',
					'id'                   => '',
				),
				$args
			);

			$this->args = $defaults;
			$html       = '';

			if ( '' !== locate_template( 'templates/image-swap/elegant-image-swap.php' ) ) {
				include locate_template( 'templates/image-swap/elegant-image-swap.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/image-swap/elegant-image-swap.php';
			}

			$this->image_counter++;

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 3.3.0
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-image-swap',
				'style' => '',
			);

			$attr['class'] .= ' elegant-image-swap-' . $this->image_counter;
			$attr['class'] .= ' fusion-align' . $this->args['alignment'];

			$image_width    = FusionBuilder::validate_shortcode_attr_value( $this->args['image_width'], 'px' );
			$attr['style'] .= 'width:' . $image_width . ';';

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
		 * Main display image attributes.
		 *
		 * @access public
		 * @since 3.3.0
		 * @return string
		 */
		public function main_image_attr() {
			$attr = array(
				'class' => 'elegant-image-swap-main',
				'style' => '',
			);

			$attr['src'] = $this->args['image_url'];

			$image_alt   = get_post_meta( $this->args['image_id'], '_wp_attachment_image_alt', true );
			$image_alt   = ( '' !== $image_alt ) ? $image_alt : basename( $attr['src'] );
			$attr['alt'] = $image_alt;

			$images_border_radius = FusionBuilder::validate_shortcode_attr_value( $this->args['images_border_radius'], 'px' );
			$attr['style']       .= 'border-radius: ' . $images_border_radius . ' !important;';

			$image_width    = FusionBuilder::validate_shortcode_attr_value( $this->args['image_width'], 'px' );
			$attr['width']  = $image_width;
			$attr['height'] = 'auto';

			return $attr;
		}

		/**
		 * Swap display image attributes.
		 *
		 * @access public
		 * @since 3.3.0
		 * @return string
		 */
		public function swap_image_attr() {
			$attr = array(
				'class' => 'elegant-image-swap-hover',
				'style' => '',
			);

			$attr['src'] = $this->args['swap_image_url'];

			$image_alt   = get_post_meta( $this->args['swap_image_id'], '_wp_attachment_image_alt', true );
			$image_alt   = ( '' !== $image_alt ) ? $image_alt : basename( $attr['src'] );
			$attr['alt'] = $image_alt;

			$images_border_radius = FusionBuilder::validate_shortcode_attr_value( $this->args['images_border_radius'], 'px' );
			$attr['style']       .= 'border-radius: ' . $images_border_radius . ' !important;';

			$image_width    = FusionBuilder::validate_shortcode_attr_value( $this->args['image_width'], 'px' );
			$attr['width']  = $image_width;
			$attr['height'] = 'auto';

			return $attr;
		}
	}

	new IEE_Image_Swap();
} // End if().

/**
 * Map shortcode for image.
 *
 * @since 3.3.0
 * @return void
 */
function map_elegant_elements_image_swap() {
	global $fusion_settings;

	fusion_builder_map(
		array(
			'name'            => esc_attr__( 'Elegant Image Swap', 'elegant-elements' ),
			'shortcode'       => 'iee_image_swap',
			'icon'            => 'fa-images fas shadow-image-icon',
			'allow_generator' => true,
			'front-end'       => ELEGANT_ELEMENTS_PLUGIN_DIR . '/elements/front-end/templates/infi-image-swap-preview.php',
			'params'          => array(
				array(
					'type'        => 'upload',
					'heading'     => esc_attr__( 'Image', 'elegant-elements' ),
					'description' => esc_attr__( 'Upload an image to be used in the filter.', 'elegant-elements' ),
					'param_name'  => 'image_url',
					'value'       => '',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Image ID', 'elegant-elements' ),
					'description' => esc_attr__( 'Image ID from Media Library.', 'elegant-elements' ),
					'param_name'  => 'image_id',
					'value'       => '',
					'hidden'      => true,
				),
				array(
					'type'        => 'upload',
					'heading'     => esc_attr__( 'Swap Image', 'elegant-elements' ),
					'description' => esc_attr__( 'Upload an image to be used to display on swap.', 'elegant-elements' ),
					'param_name'  => 'swap_image_url',
					'value'       => '',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Swap Image ID', 'elegant-elements' ),
					'description' => esc_attr__( 'Swap Image ID from Media Library.', 'elegant-elements' ),
					'param_name'  => 'swap_image_id',
					'value'       => '',
					'hidden'      => true,
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Image Width', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css width for this image. The image height will auto adjusted in proportion. In Pixels (px).', 'elegant-elements' ),
					'param_name'  => 'image_width',
					'value'       => '400',
					'min'         => '50',
					'max'         => '1000',
					'step'        => '1',
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
					'description' => esc_attr__( 'Align the image to the left, right or center.', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'On Click Action', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose what you want to do when user click on the image.', 'elegant-elements' ),
					'param_name'  => 'click_action',
					'default'     => 'none',
					'value'       => array(
						'modal'    => __( 'Open Modal', 'elegant-elements' ),
						'url'      => __( 'Open URL', 'elegant-elements' ),
						'lightbox' => __( 'Open Lightbox', 'elegant-elements' ),
						'none'     => __( 'Do Nothing', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'upload',
					'heading'     => esc_attr__( 'Lightbox Image', 'elegant-elements' ),
					'description' => esc_attr__( 'Upload an image to be opened in the lightbox. Default image will be used instead.', 'elegant-elements' ),
					'param_name'  => 'lightbox_image',
					'value'       => '',
					'dependency'  => array(
						array(
							'element'  => 'click_action',
							'value'    => 'lightbox',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'checkbox_button_set',
					'heading'     => esc_attr__( 'Image Meta in Lightbox', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose what you want to display from the lightbox image meta in lightbox.', 'elegant-elements' ),
					'param_name'  => 'lightbox_image_meta',
					'default'     => '',
					'value'       => array(
						'caption' => __( 'Caption', 'elegant-elements' ),
						'title'   => __( 'Title', 'elegant-elements' ),
					),
					'dependency'  => array(
						array(
							'element'  => 'click_action',
							'value'    => 'lightbox',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Modal Window Anchor', 'elegant-elements' ),
					'description' => esc_attr__( 'Add the class name of the modal window you want to open on the image click.', 'elegant-elements' ),
					'param_name'  => 'modal_anchor',
					'value'       => '',
					'dependency'  => array(
						array(
							'element'  => 'click_action',
							'value'    => 'modal',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'link_selector',
					'heading'     => esc_attr__( 'URL to Open', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter the url you want to open on the image click.', 'elegant-elements' ),
					'param_name'  => 'url',
					'value'       => '',
					'dependency'  => array(
						array(
							'element'  => 'click_action',
							'value'    => 'url',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Link Target', 'elegant-elements' ),
					'description' => esc_attr__( 'Select if you want to open the link in current browser tab or in new tab.', 'elegant-elements' ),
					'param_name'  => 'target',
					'default'     => '_blank',
					'value'       => array(
						'_blank' => __( 'New Tab', 'elegant-elements' ),
						'_self'  => __( 'Current Tab', 'elegant-elements' ),
					),
					'dependency'  => array(
						array(
							'element'  => 'click_action',
							'value'    => 'url',
							'operator' => '==',
						),
						array(
							'element'  => 'url',
							'value'    => '',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Image Border Radius', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the border radius for images in the slider.', 'elegant-elements' ),
					'param_name'  => 'images_border_radius',
					'value'       => '4',
					'min'         => '0',
					'max'         => '500',
					'step'        => '1',
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_image_swap', 99 );
