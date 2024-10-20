<?php
if ( fusion_is_element_enabled( 'iee_horizontal_scrolling_images' ) && ! class_exists( 'IEE_Horizontal_Scrolling_Images' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 3.3.0
	 */
	class IEE_Horizontal_Scrolling_Images extends Fusion_Element {

		/**
		 * An array of the parent shortcode arguments.
		 *
		 * @access protected
		 * @since 3.3.0
		 * @var array
		 */
		protected $args;

		/**
		 * An array of the child shortcode arguments.
		 *
		 * @access protected
		 * @since 3.3.0
		 * @var array
		 */
		protected $child_args;

		/**
		 * Horizontal Scrolling Images counter.
		 *
		 * @since 3.3.0
		 * @access private
		 * @var object
		 */
		private $horizontal_scrolling_images_counter = 1;

		/**
		 * Horizontal Scrolling Images child counter.
		 *
		 * @since 3.3.0
		 * @access private
		 * @var object
		 */
		private $horizontal_scrolling_images_child_counter = 0;

		/**
		 * Horizontal Scrolling Images.
		 *
		 * @since 3.3.0
		 * @access private
		 * @var object
		 */
		private $horizontal_scrolling_images = array();

		/**
		 * Constructor.
		 *
		 * @since 3.3.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-horizontal-scrolling-images', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_elegant-horizontal-scrolling-images-items', array( $this, 'items_attr' ) );
			add_filter( 'fusion_attr_elegant-horizontal-scrolling-image-item', array( $this, 'item_attr' ) );

			add_shortcode( 'iee_horizontal_scrolling_images', array( $this, 'render_parent' ) );
			add_shortcode( 'iee_horizontal_scrolling_image', array( $this, 'render_child' ) );
		}

		/**
		 * Render the parent shortcode.
		 *
		 * @access public
		 * @since 3.3.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render_parent( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			wp_enqueue_style( 'infi-elegant-carousel' );

			$this->horizontal_scrolling_images_child_counter = 1;

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'images_to_show'         => '2',
					'images_to_scroll'       => '1',
					'image_padding'          => '',
					'image_shape'            => 'custom',
					'blob_shape'             => '59% 41% 41% 59% / 29% 48% 52% 71%',
					'images_border_radius'   => '4',
					'speed'                  => 300,
					'autoplay'               => true,
					'autoplay_speed'         => 3000,
					'odd_even_layout'        => 'yes',
					'alternate_slide_offset' => '60',
					'responsive'             => 'yes',
					'force_visible_images'   => 'no',
					'hide_on_mobile'         => fusion_builder_default_visibility( 'string' ),
					'class'                  => '',
					'id'                     => '',
				),
				$args
			);

			$this->args = $defaults;

			$this->args['ipad_landscape_slides_to_show']   = ( '' !== $fusion_settings->get( 'elegant_carousel_slides_ipad_landscape' ) ) ? $fusion_settings->get( 'elegant_carousel_slides_ipad_landscape' ) : '3';
			$this->args['ipad_portrait_slides_to_show']    = ( '' !== $fusion_settings->get( 'elegant_carousel_slides_ipad' ) ) ? $fusion_settings->get( 'elegant_carousel_slides_ipad' ) : '2';
			$this->args['mobile_landscape_slides_to_show'] = ( '' !== $fusion_settings->get( 'elegant_carousel_slides_mobile' ) ) ? $fusion_settings->get( 'elegant_carousel_slides_mobile' ) : '1';

			$html = '';

			if ( '' !== locate_template( 'templates/horizontal-scrolling-images/elegant-horizontal-scrolling-images-parent.php' ) ) {
				include locate_template( 'templates/horizontal-scrolling-images/elegant-horizontal-scrolling-images-parent.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/horizontal-scrolling-images/elegant-horizontal-scrolling-images-parent.php';
			}

			$this->horizontal_scrolling_images_counter++;

			return $html;
		}

		/**
		 * Render the child shortcode.
		 *
		 * @access public
		 * @since 3.3.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render_child( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'image_url'            => '',
					'image_id'             => '',
					'image_max_width'      => 400,
					'click_action'         => 'none',
					'lightbox_image'       => '',
					'lightbox_image_meta'  => 'caption,title',
					'modal_anchor'         => '',
					'url'                  => '',
					'target'               => '_blank',
					'image_shape'          => 'custom',
					'blob_shape'           => '59% 41% 41% 59% / 29% 48% 52% 71%',
					'images_border_radius' => '4',
					'hide_on_mobile'       => fusion_builder_default_visibility( 'string' ),
					'class'                => '',
					'id'                   => '',
				),
				$args
			);

			$this->child_args = $defaults;
			$child_html       = '';

			if ( '' !== locate_template( 'templates/horizontal-scrolling-images/elegant-horizontal-scrolling-images-child.php' ) ) {
				include locate_template( 'templates/horizontal-scrolling-images/elegant-horizontal-scrolling-images-child.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/horizontal-scrolling-images/elegant-horizontal-scrolling-images-child.php';
			}

			$this->horizontal_scrolling_images_child_counter++;
			return $child_html;
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
				'class' => 'elegant-horizontal-scrolling-images',
			);

			$attr['class'] .= ' elegant-horizontal-scrolling-images-' . $this->horizontal_scrolling_images_counter;

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
		 * @since 3.3.0
		 * @return array
		 */
		public function items_attr() {
			$attr = array(
				'class' => 'elegant-horizontal-scrolling-images-items',
				'style' => '',
			);

			$attr['class']           .= ' elegant-carousel elegant-slick';
			$attr['data-carousel-id'] = 'ehc' . $this->horizontal_scrolling_images_counter;

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 3.3.0
		 * @return array
		 */
		public function item_attr() {
			$attr = array(
				'class' => 'elegant-horizontal-scrolling-image-item',
				'style' => '',
			);

			$attr['class'] .= ' elegant-horizontal-scrolling-image-item-' . $this->horizontal_scrolling_images_child_counter;
			$attr['style'] .= 'outline: none;';

			if ( 'none' !== $this->child_args['click_action'] ) {
				$attr['style'] .= 'cursor: pointer;';
			}

			if ( $this->child_args['class'] ) {
				$attr['class'] .= ' ' . $this->child_args['class'];
			}

			return $attr;
		}

		/**
		 * Sets the necessary scripts.
		 *
		 * @access public
		 * @since 3.3.0
		 * @return void
		 */
		public function add_scripts() {
			global $elegant_js_folder_url, $elegant_js_folder_path, $elegant_css_folder_url, $elegant_css_folder_path;

			Fusion_Dynamic_JS::enqueue_script(
				'infi-elegant-carousel',
				$elegant_js_folder_url . '/infi-elegant-carousel.min.js',
				$elegant_js_folder_path . '/infi-elegant-carousel.min.js',
				array( 'jquery' ),
				'1',
				true
			);
		}

		/**
		 * Generate custom css based on element settings.
		 *
		 * @access public
		 * @since 3.3.0
		 * @return string
		 */
		public function add_style() {
			$css = '';

			if ( 'yes' === $this->args['odd_even_layout'] ) {
				$alternate_slide_offset = FusionBuilder::validate_shortcode_attr_value( $this->args['alternate_slide_offset'], 'px' );
				$css                   .= '.elegant-horizontal-scrolling-images-' . $this->horizontal_scrolling_images_counter . ' .elegant-slick-initialized .elegant-slick-slide:nth-child(2n) {
				    margin-top: ' . $alternate_slide_offset . ' !important;
				}';
			}

			if ( 'custom' === $this->args['image_shape'] ) {
				$images_border_radius = FusionBuilder::validate_shortcode_attr_value( $this->args['images_border_radius'], 'px' );
				$css                 .= '.elegant-horizontal-scrolling-images-' . $this->horizontal_scrolling_images_counter . ' img {
					border-radius: ' . $images_border_radius . ' !important;
				}';
			} else {
				$blob_shape = $this->args['blob_shape'];
				$css       .= '.elegant-horizontal-scrolling-images-' . $this->horizontal_scrolling_images_counter . ' img {
					border-radius: ' . $blob_shape . ' !important;
				}';
			}

			return $css;
		}

		/**
		 * Generate custom css based on element settings.
		 *
		 * @access public
		 * @since 3.3.0
		 * @return string
		 */
		public function add_child_style() {
			$css = '';

			if ( 'custom' === $this->child_args['image_shape'] ) {
				$images_border_radius = FusionBuilder::validate_shortcode_attr_value( $this->child_args['images_border_radius'], 'px' );
				$css                 .= '.elegant-horizontal-scrolling-images-' . $this->horizontal_scrolling_images_counter . ' .elegant-horizontal-scrolling-image-item-' . $this->horizontal_scrolling_images_child_counter . ' img {
					border-radius: ' . $images_border_radius . ' !important;
				}';
			} elseif ( 'blob' === $this->child_args['image_shape'] ) {
				$blob_shape = $this->child_args['blob_shape'];
				$css       .= '.elegant-horizontal-scrolling-images-' . $this->horizontal_scrolling_images_counter . ' .elegant-horizontal-scrolling-image-item-' . $this->horizontal_scrolling_images_child_counter . ' img {
					border-radius: ' . $blob_shape . ' !important;
				}';
			}

			return $css;
		}
	}

	new IEE_Horizontal_Scrolling_Images();
} // End if().

/**
 * Map shortcode for horizontal_scrolling_images.
 *
 * @since 3.3.0
 * @return void
 */
function map_elegant_elements_horizontal_scrolling_images() {
	global $fusion_settings;

	$parent_args = array(
		'name'          => esc_attr__( 'Elegant Horizontal Scrolling Images', 'elegant-elements' ),
		'shortcode'     => 'iee_horizontal_scrolling_images',
		'icon'          => 'icon-filter',
		'multi'         => 'multi_element_parent',
		'element_child' => 'iee_horizontal_scrolling_image',
		'child_ui'      => true,
		'params'        => array(
			array(
				'type'        => 'tinymce',
				'heading'     => esc_attr__( 'Content', 'elegant-elements' ),
				'description' => esc_attr__( 'Upload Images.', 'elegant-elements' ),
				'param_name'  => 'element_content',
				'value'       => '[iee_horizontal_scrolling_image]',
			),
			array(
				'type'             => 'multiple_upload',
				'heading'          => esc_attr__( 'Bulk Image Upload', 'elegant-elements' ),
				'description'      => __( 'This option allows you to select multiple images at once and they will populate into individual items. It saves time instead of adding one image at a time.', 'elegant-elements' ),
				'param_name'       => 'multiple_upload',
				'element_target'   => 'iee_horizontal_scrolling_image',
				'param_target'     => 'image_url',
				'child_params'     => array(
					'image_url' => 'url',
					'image_id'  => 'id',
				),
				'remove_from_atts' => true,
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Number of Visible Images', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the number of images you want to be visible in the slider.', 'elegant-elements' ),
				'param_name'  => 'images_to_show',
				'value'       => '3',
				'min'         => '2',
				'max'         => '10',
				'step'        => '1',
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Number of Images to Scroll', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the number of images you want to scroll at a time in the slider.', 'elegant-elements' ),
				'param_name'  => 'images_to_scroll',
				'value'       => '1',
				'min'         => '2',
				'max'         => '10',
				'step'        => '1',
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Image Padding', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the space you want around the images in the slider. In Pixels (px).', 'elegant-elements' ),
				'param_name'  => 'image_padding',
				'value'       => '10',
				'min'         => '0',
				'max'         => '100',
				'step'        => '1',
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Image Shape', 'elegant-elements' ),
				'param_name'  => 'image_shape',
				'default'     => 'custom',
				'value'       => array(
					'custom' => esc_attr__( 'Custom Border Radius', 'elegant-elements' ),
					'blob'   => esc_attr__( 'Blob Shape', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Select how you want to shape the image. You can override this for individual image from the image settings.', 'elegant-elements' ),
			),
			array(
				'type'        => 'elegant_blob_shape_generator',
				'heading'     => esc_attr__( 'Blob Shape', 'elegant-elements' ),
				'description' => esc_attr__( 'Click the button to generate blob shape.', 'elegant-elements' ),
				'param_name'  => 'blob_shape',
				'value'       => '59% 41% 41% 59% / 29% 48% 52% 71%',
				'dependency'  => array(
					array(
						'element'  => 'image_shape',
						'value'    => 'blob',
						'operator' => '==',
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
				'dependency'  => array(
					array(
						'element'  => 'image_shape',
						'value'    => 'custom',
						'operator' => '==',
					),
				),
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Slide transition speed', 'elegant-elements' ),
				'param_name'  => 'speed',
				'value'       => '300',
				'min'         => '100',
				'max'         => '1500',
				'step'        => '50',
				'description' => esc_attr__( 'Transition speed in mili-seconds.', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Autoplay Slides', 'elegant-elements' ),
				'param_name'  => 'autoplay',
				'default'     => 'yes',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Enable Auto play of slides.', 'elegant-elements' ),
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Autoplay Speed', 'elegant-elements' ),
				'param_name'  => 'autoplay_speed',
				'value'       => '3',
				'min'         => '1',
				'max'         => '15',
				'step'        => '1',
				'dependency'  => array(
					array(
						'element'  => 'autoplay',
						'value'    => 'yes',
						'operator' => '==',
					),
				),
				'description' => esc_attr__( 'Auto play change interval. In seconds.', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Odd/Even Layout', 'elegant-elements' ),
				'param_name'  => 'odd_even_layout',
				'default'     => 'yes',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Enables the slider images to be displayed in zigzag type layout.', 'elegant-elements' ),
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Alternate Slide Offset', 'elegant-elements' ),
				'param_name'  => 'alternate_slide_offset',
				'value'       => '60',
				'min'         => '10',
				'max'         => '200',
				'step'        => '1',
				'dependency'  => array(
					array(
						'element'  => 'odd_even_layout',
						'value'    => 'yes',
						'operator' => '==',
					),
				),
				'description' => esc_attr__( 'Push the alternate slide down by this size. In pixels.', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Enable responsive breakpoints', 'elegant-elements' ),
				'param_name'  => 'responsive',
				'default'     => 'yes',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Enables responsive breakpoints for slider. Slider will auto adjusted on devices with the standard setting.', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Force display visible images', 'elegant-elements' ),
				'param_name'  => 'force_visible_images',
				'default'     => 'no',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Set yes if you want to display the number of visible images forcefully. This will affect the edge slides partial display position slightly.', 'elegant-elements' ),
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
	);

	$child_args = array(
		'name'              => esc_attr__( 'Image', 'elegant-elements' ),
		'shortcode'         => 'iee_horizontal_scrolling_image',
		'hide_from_builder' => true,
		'allow_generator'   => true,
		'on_change'         => 'elegantRefreshCarousel',
		'tag_name'          => 'div',
		'selectors'         => array(
			'class' => 'elegant-horizontal-scrolling-image-item',
		),
		'params'            => array(
			array(
				'type'        => 'upload',
				'heading'     => esc_attr__( 'Image', 'elegant-elements' ),
				'description' => esc_attr__( 'Upload an image.', 'elegant-elements' ),
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
				'type'        => 'range',
				'heading'     => esc_attr__( 'Image Max Width', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the maximum width this image should fit into. The image height will auto adjusted in proportion. In Pixels (px).', 'elegant-elements' ),
				'param_name'  => 'image_max_width',
				'value'       => '400',
				'min'         => '50',
				'max'         => '1000',
				'step'        => '1',
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
				'default'     => 'caption,title',
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
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Image Shape', 'elegant-elements' ),
				'param_name'  => 'image_shape',
				'default'     => 'default',
				'value'       => array(
					'default' => esc_attr__( 'Parent Default', 'elegant-elements' ),
					'custom'  => esc_attr__( 'Custom Border Radius', 'elegant-elements' ),
					'blob'    => esc_attr__( 'Blob Shape', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Select how you want to shape the image. Set parent default to inherit setting from parent settings.', 'elegant-elements' ),
			),
			array(
				'type'        => 'elegant_blob_shape_generator',
				'heading'     => esc_attr__( 'Blob Shape', 'elegant-elements' ),
				'description' => esc_attr__( 'Click the button to generate blob shape.', 'elegant-elements' ),
				'param_name'  => 'blob_shape',
				'value'       => '59% 41% 41% 59% / 29% 48% 52% 71%',
				'dependency'  => array(
					array(
						'element'  => 'image_shape',
						'value'    => 'blob',
						'operator' => '==',
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
				'dependency'  => array(
					array(
						'element'  => 'image_shape',
						'value'    => 'custom',
						'operator' => '==',
					),
				),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'CSS Class', 'elegant-elements' ),
				'param_name'  => 'class',
				'value'       => '',
				'description' => esc_attr__( 'Add a class to the wrapping HTML element.', 'elegant-elements' ),
			),
		),
	);

	if ( function_exists( 'fusion_builder_frontend_data' ) ) {
		fusion_builder_map(
			fusion_builder_frontend_data(
				'IEE_Horizontal_Scrolling_Images',
				$parent_args,
				'parent'
			)
		);

		fusion_builder_map(
			fusion_builder_frontend_data(
				'IEE_Horizontal_Scrolling_Images',
				$child_args,
				'child'
			)
		);
	} else {
		fusion_builder_map(
			$parent_args
		);

		fusion_builder_map(
			$child_args
		);
	}
}

add_action( 'fusion_builder_before_init', 'map_elegant_elements_horizontal_scrolling_images', 99 );
