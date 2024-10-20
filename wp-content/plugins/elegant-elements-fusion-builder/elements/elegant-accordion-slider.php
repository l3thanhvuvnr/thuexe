<?php
if ( fusion_is_element_enabled( 'iee_accordion_slider' ) && ! class_exists( 'IEE_Accordion_Slider' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 3.3.5
	 */
	class IEE_Accordion_Slider extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 3.3.5
		 * @var array
		 */
		protected $args;

		/**
		 * Accordion slider counter.
		 *
		 * @since 3.3.5
		 * @access private
		 * @var object
		 */
		private $accordion_slider_counter = 1;

		/**
		 * Constructor.
		 *
		 * @since 3.3.5
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			// Parent filter.
			add_filter( 'fusion_attr_elegant-accordion-slider', array( $this, 'attr' ) );

			// Child item filters.
			add_filter( 'fusion_attr_elegant-accordion-slider-item', array( $this, 'item_attr' ) );
			add_filter( 'fusion_attr_elegant-accordion-slider-item-image', array( $this, 'item_attr_image' ) );
			add_filter( 'fusion_attr_elegant-accordion-slider-item-title', array( $this, 'item_attr_title' ) );
			add_filter( 'fusion_attr_elegant-accordion-slider-description', array( $this, 'description_attr' ) );

			add_shortcode( 'iee_accordion_slider', array( $this, 'render' ) );
		}

		/**
		 * Render the parent shortcode.
		 *
		 * @access public
		 * @since 3.3.5
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'source'                    => 'images',
					'post_type'                 => 'post',
					'post_count'                => 5,
					'images'                    => '',
					'display_image_title'       => 'no',
					'display_image_description' => 'no',
					'slider_height'             => 250,
					'background_color'          => '',
					'title_color'               => '',
					'description_color'         => '',
					'description_font_size'     => 14,
					'font_size'                 => 24,
					'hide_on_mobile'            => fusion_builder_default_visibility( 'string' ),
					'class'                     => '',
					'id'                        => '',
				),
				$args
			);

			$defaults = apply_filters( 'fusion_builder_default_args', $defaults, 'iee_accordion_slider', $args );

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/accordion-slider/elegant-accordion-slider.php' ) ) {
				include locate_template( 'templates/accordion-slider/elegant-accordion-slider.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/accordion-slider/elegant-accordion-slider.php';
			}

			$this->accordion_slider_counter++;

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 3.3.5
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-accordion-slider',
				'style' => '',
			);

			$attr = fusion_builder_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$attr['style'] .= 'height:' . FusionBuilder::validate_shortcode_attr_value( $this->args['slider_height'], 'px' ) . ';';
			$attr['style'] .= '--top: calc( ' . FusionBuilder::validate_shortcode_attr_value( $this->args['slider_height'], 'px' ) . ' - 110px );';
			$attr['style'] .= '--content-bg: ' . $this->args['background_color'] . ';';

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
		 * @since 3.3.5
		 * @return array
		 */
		public function item_attr() {
			$attr = array(
				'class' => 'elegant-accordion-slider-item',
				'style' => '',
			);

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 3.3.5
		 * @return array
		 */
		public function item_attr_image() {
			$attr = array(
				'class' => 'elegant-accordion-slider-item-image',
				'style' => '',
			);

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 3.3.5
		 * @return array
		 */
		public function item_attr_title() {
			$attr = array(
				'class' => 'elegant-accordion-slider-item-title',
				'style' => '',
			);

			if ( '' !== $this->args['title_color'] ) {
				$attr['style'] .= 'color: ' . $this->args['title_color'] . ';';
			}

			$attr['style'] .= 'font-size:' . FusionBuilder::validate_shortcode_attr_value( $this->args['font_size'], 'px' ) . ';';

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 3.3.5
		 * @return array
		 */
		public function description_attr() {
			$attr = array(
				'class' => 'elegant-accordion-slider-description',
				'style' => '',
			);

			if ( '' !== $this->args['description_color'] ) {
				$attr['style'] .= 'color: ' . $this->args['description_color'] . ';';
			}

			$attr['style'] .= 'font-size:' . FusionBuilder::validate_shortcode_attr_value( $this->args['description_font_size'], 'px' ) . ';';

			return $attr;
		}
	}

	new IEE_Accordion_Slider();
} // End if().


/**
 * Map shortcode for accordion_slider.
 *
 * @since 3.3.5
 * @return void
 */
function map_elegant_elements_accordion_slider() {
	global $fusion_settings;

	$post_types = elegant_get_post_types();

	$parent_args = array(
		'name'      => esc_attr__( 'Elegant Accordion Slider', 'elegant-elements' ),
		'shortcode' => 'iee_accordion_slider',
		'icon'      => 'fa-map fas accordion-slider-icon',
		'params'    => array(
			array(
				'type'        => 'hidden',
				'heading'     => esc_attr__( 'Source', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the source for the slider items.', 'elegant-elements' ),
				'param_name'  => 'source',
				'default'     => 'images',
				'value'       => array(
					'images'    => esc_attr__( 'Images', 'elegant-elements' ),
					'post_type' => esc_attr__( 'Post Type', 'elegant-elements' ),
				),
			),
			array(
				'type'        => 'select',
				'heading'     => esc_attr__( 'Post Type', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the post type you want to pull the posts to be displayed in accordion slider.', 'elegant-elements' ),
				'param_name'  => 'post_type',
				'default'     => 'post',
				'value'       => $post_types,
				'dependency'  => array(
					array(
						'element'  => 'source',
						'value'    => 'post_type',
						'operator' => '==',
					),
				),
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Number of posts', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the number of posts you want to display. Usually, displaying max 6-7 posts will make the accordion slider look awesome.', 'elegant-elements' ),
				'param_name'  => 'post_count',
				'value'       => '5',
				'min'         => '2',
				'max'         => '15',
				'step'        => '1',
				'dependency'  => array(
					array(
						'element'  => 'source',
						'value'    => 'post_type',
						'operator' => '==',
					),
				),
			),
			array(
				'type'        => 'elegant_upload_images',
				'heading'     => esc_attr__( 'Images for Slider', 'elegant-elements' ),
				'param_name'  => 'images',
				'value'       => '',
				'description' => esc_attr__( 'Upload images for accordion slider.', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Display Image Title', 'elegant-elements' ),
				'description' => esc_attr__( 'Select yes if you want to use the image title. You need to set the image title in the image title meta field.', 'elegant-elements' ),
				'param_name'  => 'display_image_title',
				'default'     => 'no',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Display Image Description', 'elegant-elements' ),
				'description' => esc_attr__( 'Select yes if you want to use the image description. You need to set the image description in the image description meta field.', 'elegant-elements' ),
				'param_name'  => 'display_image_description',
				'default'     => 'no',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Slider Height', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the CSS height of the slider. Images will are set to fit in the content so there might be blank space on right side if the image is smaller in size. In Pixels. ( px )', 'elegant-elements' ),
				'param_name'  => 'slider_height',
				'value'       => '250',
				'min'         => '100',
				'max'         => '1500',
				'step'        => '1',
			),
			array(
				'type'        => 'colorpickeralpha',
				'heading'     => esc_attr__( 'Slider Item Content Background Color', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the background color for the content on the slider item.', 'elegant-elements' ),
				'param_name'  => 'background_color',
				'value'       => 'rgba(0,0,0,.4)',
				'default'     => 'rgba(0,0,0,.4)',
				'group'       => esc_attr__( 'Design', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Title Color', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the text color for the title text.', 'elegant-elements' ),
				'param_name'  => 'title_color',
				'value'       => '',
				'group'       => esc_attr__( 'Design', 'elegant-elements' ),
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Title Font Size', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the font-size for the title text. Title is displayed only if selected for images.', 'elegant-elements' ),
				'param_name'  => 'font_size',
				'value'       => '24',
				'min'         => '10',
				'max'         => '100',
				'step'        => '1',
				'group'       => esc_attr__( 'Design', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Description Color', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the text color for the description content.', 'elegant-elements' ),
				'param_name'  => 'description_color',
				'value'       => '',
				'group'       => esc_attr__( 'Design', 'elegant-elements' ),
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Description Font Size', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the font-size for the description text.', 'elegant-elements' ),
				'param_name'  => 'description_font_size',
				'value'       => '14',
				'min'         => '10',
				'max'         => '100',
				'step'        => '1',
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
	);

	if ( function_exists( 'fusion_builder_frontend_data' ) ) {
		fusion_builder_map(
			fusion_builder_frontend_data(
				'IEE_Accordion_Slider',
				$parent_args,
				'parent'
			)
		);
	} else {
		fusion_builder_map(
			$parent_args
		);
	}
}

add_action( 'fusion_builder_before_init', 'map_elegant_elements_accordion_slider', 99 );
