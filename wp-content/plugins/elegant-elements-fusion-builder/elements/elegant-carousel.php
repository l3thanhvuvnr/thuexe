<?php
if ( fusion_is_element_enabled( 'iee_carousel' ) && ! class_exists( 'IEE_Carousel' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class IEE_Carousel extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 2.3
		 * @var array
		 */
		protected $args;

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 2.3
		 * @var array
		 */
		protected $child_args;

		/**
		 * Carousel counter.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $carousel_counter = 1;

		/**
		 * Carousel item counter.
		 *
		 * @since 2.3
		 * @access private
		 * @var object
		 */
		private $carousel_item_counter = 1;

		/**
		 * Carousel Items.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $carousel_items = array();

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-carousel', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_elegant-carousel-item', array( $this, 'child_attr' ) );

			add_shortcode( 'iee_carousel', array( $this, 'render_parent' ) );
			add_shortcode( 'iee_carousel_item', array( $this, 'render_child' ) );

			add_filter( 'fusion_options_sliders_not_in_pixels', array( $this, 'fusion_options_sliders_not_in_pixels' ) );
		}

		/**
		 * Render the parent shortcode.
		 *
		 * @access public
		 * @since 1.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render_parent( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			// Reset the carousel items.
			$this->carousel_items = array();

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'fade'                => '',
					'slides_to_show'      => '',
					'slides_to_scroll'    => '',
					'speed'               => '',
					'variable_width'      => '',
					'infinite'            => '',
					'accessibility'       => '',
					'adaptive_height'     => '',
					'arrows'              => '',
					'next_arrow_icon'     => '',
					'prev_arrow_icon'     => '',
					'arrow_color'         => '',
					'arrow_font_size'     => '',
					'dots'                => '',
					'dots_icon_class'     => '',
					'dots_color'          => '',
					'dots_color_active'   => '',
					'dots_font_size'      => '',
					'autoplay'            => '',
					'autoplay_speed'      => '',
					'random_order'        => 'no',
					'initial_height'      => '0',
					'center_padding'      => '',
					'draggable'           => '',
					'item_padding'        => '',
					'item_margin'         => '',
					'pause_on_hover'      => '',
					'pause_on_dots_hover' => '',
					'responsive'          => '',
					'border_size'         => '',
					'border_color'        => '',
					'border_style'        => '',
					'border_radius'       => '',
					'hide_on_mobile'      => '',
					'class'               => '',
					'id'                  => '',
				),
				$args
			);

			$args       = $defaults;
			$this->args = $args;

			$html = '';

			$args['ipad_landscape_slides_to_show']   = ( '' !== $fusion_settings->get( 'elegant_carousel_slides_ipad_landscape' ) ) ? $fusion_settings->get( 'elegant_carousel_slides_ipad_landscape' ) : '3';
			$args['ipad_portrait_slides_to_show']    = ( '' !== $fusion_settings->get( 'elegant_carousel_slides_ipad' ) ) ? $fusion_settings->get( 'elegant_carousel_slides_ipad' ) : '2';
			$args['mobile_landscape_slides_to_show'] = ( '' !== $fusion_settings->get( 'elegant_carousel_slides_mobile' ) ) ? $fusion_settings->get( 'elegant_carousel_slides_mobile' ) : '1';

			if ( '' !== locate_template( 'templates/carousel/elegant-carousel-parent.php' ) ) {
				include locate_template( 'templates/carousel/elegant-carousel-parent.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/carousel/elegant-carousel-parent.php';
			}

			$this->carousel_counter++;

			return $html;
		}

		/**
		 * Render the child shortcode.
		 *
		 * @access public
		 * @since 1.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render_child( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'trigger_class' => '',
					'class'         => '',
					'id'            => '',
				),
				$args
			);

			$args             = $defaults;
			$this->child_args = $args;

			$child_html = '';

			if ( '' !== locate_template( 'templates/carousel/elegant-carousel-child.php' ) ) {
				include locate_template( 'templates/carousel/elegant-carousel-child.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/carousel/elegant-carousel-child.php';
			}

			$this->carousel_item_counter++;

			return $child_html;
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
				'class' => 'elegant-carousel-container elegant-carousel-' . $this->carousel_counter,
				'style' => '',
			);

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
		public function child_attr() {
			$attr = array(
				'class' => 'elegant-carousel-item elegant-carousel-item-' . $this->carousel_item_counter,
				'style' => '',
			);

			if ( $this->child_args['trigger_class'] ) {
				$attr['data-trigger-class'] = '.' . $this->child_args['trigger_class'];
			}

			if ( $this->child_args['class'] ) {
				$attr['class'] .= ' ' . $this->child_args['class'];
			}

			if ( $this->child_args['id'] ) {
				$attr['id'] = $this->child_args['id'];
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
			global $elegant_js_folder_url, $elegant_js_folder_path;

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
		 * Sliders that are not in pixels.
		 *
		 * @access public
		 * @since 1.3.0
		 * @param array $fields An array of fields.
		 * @return array
		 */
		public function fusion_options_sliders_not_in_pixels( $fields ) {
			$extra_fields = array(
				'elegant_carousel_slides_ipad_landscape',
				'elegant_carousel_slides_ipad',
				'elegant_carousel_slides_mobile',
			);
			return array_unique( array_merge( $fields, $extra_fields ) );
		}
	}

	new IEE_Carousel();
} // End if().


/**
 * Map shortcode for carousel.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_carousel() {
	global $fusion_settings;

	$parent_args = array(
		'name'          => esc_attr__( 'Elegant Carousel', 'elegant-elements' ),
		'shortcode'     => 'iee_carousel',
		'icon'          => 'fusiona-images',
		'multi'         => 'multi_element_parent',
		'element_child' => 'iee_carousel_item',
		'preview'       => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/previews/infi-carousel-preview.php',
		'preview_id'    => 'elegant-elements-module-infi-carousel-preview-template',
		'child_ui'      => true,
		'params'        => array(
			array(
				'type'        => 'tinymce',
				'heading'     => esc_attr__( 'Content', 'elegant-elements' ),
				'description' => esc_attr__( 'Enter some content for this contentbox.', 'elegant-elements' ),
				'param_name'  => 'element_content',
				'value'       => '[iee_carousel_item title="' . esc_attr__( 'Carousel item 1', 'elegant-elements' ) . '" /]',
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Slide effect', 'elegant-elements' ),
				'param_name'  => 'fade',
				'default'     => 'slide',
				'value'       => array(
					'slide' => esc_attr__( 'Slide', 'elegant-elements' ),
					'fade'  => esc_attr__( 'Fade', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Slide will allow you to select number of slides to show. Fade will display only 1 slide at a time.', 'elegant-elements' ),
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Slides to show', 'elegant-elements' ),
				'param_name'  => 'slides_to_show',
				'value'       => '3',
				'min'         => '1',
				'max'         => '10',
				'step'        => '1',
				'dependency'  => array(
					array(
						'element'  => 'fade',
						'value'    => 'fade',
						'operator' => '!=',
					),
				),
				'description' => esc_attr__( 'Number of slides to show at a time.', 'elegant-elements' ),
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Slides to scroll', 'elegant-elements' ),
				'param_name'  => 'slides_to_scroll',
				'value'       => '3',
				'min'         => '1',
				'max'         => '10',
				'step'        => '1',
				'dependency'  => array(
					array(
						'element'  => 'fade',
						'value'    => 'fade',
						'operator' => '!=',
					),
				),
				'description' => esc_attr__( 'Number of slides to scroll at a time. Set less than number of slides to show.', 'elegant-elements' ),
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
				'heading'     => esc_attr__( 'Enable Variable Width', 'elegant-elements' ),
				'param_name'  => 'variable_width',
				'default'     => 'no',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'The first and last slides will display as cut off.', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Infinite Loop', 'elegant-elements' ),
				'param_name'  => 'infinite',
				'default'     => 'yes',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Enables infinite looping.', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Keyboard Accessibility', 'elegant-elements' ),
				'param_name'  => 'accessibility',
				'default'     => 'yes',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Enables tabbing and arrow key navigation.', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Adaptive Height', 'elegant-elements' ),
				'param_name'  => 'adaptive_height',
				'default'     => 'yes',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Adapts slider height to the current slide.', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Next / Previous Arrows', 'elegant-elements' ),
				'param_name'  => 'arrows',
				'default'     => 'yes',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Enable next / previous arrows.', 'elegant-elements' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Next arrow icon name', 'elegant-elements' ),
				'param_name'  => 'next_arrow_icon',
				'value'       => '',
				'dependency'  => array(
					array(
						'element'  => 'arrows',
						'value'    => 'yes',
						'operator' => '==',
					),
				),
				'description' => esc_attr__( 'Provide FontAwesome icon class name to use for next icon arrow. eg. fa-arrow-right', 'elegant-elements' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Previous arrow icon name', 'elegant-elements' ),
				'param_name'  => 'prev_arrow_icon',
				'value'       => '',
				'dependency'  => array(
					array(
						'element'  => 'arrows',
						'value'    => 'yes',
						'operator' => '==',
					),
				),
				'description' => esc_attr__( 'Provide FontAwesome icon class name to use for previous icon arrow. eg. fa-arrow-left', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Arrow icon color', 'elegant-elements' ),
				'param_name'  => 'arrow_color',
				'value'       => '#666666',
				'dependency'  => array(
					array(
						'element'  => 'arrows',
						'value'    => 'yes',
						'operator' => '==',
					),
				),
				'description' => esc_attr__( 'Controls the next / previous icon color.', 'elegant-elements' ),
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Arrow icon font size', 'elegant-elements' ),
				'param_name'  => 'arrow_font_size',
				'value'       => '24',
				'min'         => '12',
				'max'         => '72',
				'step'        => '1',
				'dependency'  => array(
					array(
						'element'  => 'arrows',
						'value'    => 'yes',
						'operator' => '==',
					),
				),
				'description' => esc_attr__( 'Controls the next / previous icon font size. In pixels (px).', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Navigation Dots', 'elegant-elements' ),
				'param_name'  => 'dots',
				'default'     => 'yes',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Current slide navigation dots.', 'elegant-elements' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Dots icon class', 'elegant-elements' ),
				'param_name'  => 'dots_icon_class',
				'value'       => '',
				'dependency'  => array(
					array(
						'element'  => 'dots',
						'value'    => 'yes',
						'operator' => '==',
					),
				),
				'description' => esc_attr__( 'Provide FontAwesome icon class to use for navigation dots icon. eg. fa-circle', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Dots icon color', 'elegant-elements' ),
				'param_name'  => 'dots_color',
				'value'       => '#666666',
				'dependency'  => array(
					array(
						'element'  => 'dots',
						'value'    => 'yes',
						'operator' => '==',
					),
				),
				'description' => esc_attr__( 'Controls the navigation dots icon color.', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Active dot icon color', 'elegant-elements' ),
				'param_name'  => 'dots_color_active',
				'value'       => '#333333',
				'dependency'  => array(
					array(
						'element'  => 'dots',
						'value'    => 'yes',
						'operator' => '==',
					),
				),
				'description' => esc_attr__( 'Controls the active slide dots icon color.', 'elegant-elements' ),
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Dots icon font size', 'elegant-elements' ),
				'param_name'  => 'dots_font_size',
				'value'       => '18',
				'min'         => '12',
				'max'         => '72',
				'step'        => '1',
				'dependency'  => array(
					array(
						'element'  => 'dots',
						'value'    => 'yes',
						'operator' => '==',
					),
				),
				'description' => esc_attr__( 'Controls the navigation dots icon font size. In pixels (px).', 'elegant-elements' ),
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
				'heading'     => esc_attr__( 'Display Items in Random Order', 'elegant-elements' ),
				'param_name'  => 'random_order',
				'default'     => 'no',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Display carousel items in random order initially when page is loaded.', 'elegant-elements' ),
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Carousel Initial Height', 'elegant-elements' ),
				'param_name'  => 'initial_height',
				'value'       => '0',
				'min'         => '0',
				'max'         => '1000',
				'step'        => '0.1',
				'description' => esc_attr__( 'Controls the carousel initial height. If you\'re getting the page jumping issue while loading, set the initial height to the carousel height after it is fully loaded to avoid the jumps. Set 0 for auto height. In pixels (px).', 'elegant-elements' ),
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Side Padding', 'elegant-elements' ),
				'param_name'  => 'center_padding',
				'value'       => '100',
				'min'         => '0',
				'max'         => '500',
				'step'        => '1',
				'dependency'  => array(
					array(
						'element'  => 'fade',
						'value'    => 'fade',
						'operator' => '!=',
					),
				),
				'description' => esc_attr__( 'Side padding when in center mode. In pixels (px).', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Draggable Slides', 'elegant-elements' ),
				'param_name'  => 'draggable',
				'default'     => 'yes',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Enables desktop dragging.', 'elegant-elements' ),
			),
			array(
				'type'        => 'dimension',
				'heading'     => esc_attr__( 'Slide item padding ', 'fusion-builder' ),
				'description' => esc_attr__( 'Controls the padding around the slider items. Enter values including any valid CSS unit, ex: 15px, 15px, 15px, 15px.', 'elegant-elements' ),
				'param_name'  => 'item_padding',
				'value'       => '',
			),
			array(
				'type'        => 'dimension',
				'heading'     => esc_attr__( 'Slide item margin ', 'fusion-builder' ),
				'description' => esc_attr__( 'Controls the margin around the slider items. Enter values including any valid CSS unit, ex: 15px, 15px, 15px, 15px.', 'elegant-elements' ),
				'param_name'  => 'item_margin',
				'value'       => '',
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Pause on hover', 'elegant-elements' ),
				'param_name'  => 'pause_on_hover',
				'default'     => 'yes',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Pauses autoplay on hover.', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Pause on dots hover', 'elegant-elements' ),
				'param_name'  => 'pause_on_dots_hover',
				'default'     => 'yes',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Pauses autoplay when a dot is hovered.', 'elegant-elements' ),
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
				'type'        => 'range',
				'heading'     => esc_attr__( 'Border Size', 'elegant-elements' ),
				'description' => esc_attr__( 'Controls the border size of the carousel item. In pixels.', 'elegant-elements' ),
				'param_name'  => 'border_size',
				'value'       => '0',
				'min'         => '0',
				'max'         => '50',
				'step'        => '1',
				'group'       => esc_attr__( 'Design', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Border Color', 'elegant-elements' ),
				'description' => esc_attr__( 'Controls the border color.', 'elegant-elements' ),
				'param_name'  => 'border_color',
				'value'       => '#dddddd',
				'group'       => esc_attr__( 'Design', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Border Style', 'elegant-elements' ),
				'description' => esc_attr__( 'Controls the border style.', 'elegant-elements' ),
				'param_name'  => 'border_style',
				'default'     => 'solid',
				'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				'value'       => array(
					'solid'  => esc_attr__( 'Solid', 'elegant-elements' ),
					'dashed' => esc_attr__( 'Dashed', 'elegant-elements' ),
					'dotted' => esc_attr__( 'Dotted', 'elegant-elements' ),
				),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Border Radius', 'elegant-elements' ),
				'description' => esc_attr__( 'Controls the border radius.', 'elegant-elements' ),
				'param_name'  => 'border_radius',
				'default'     => 'square',
				'value'       => array(
					'square' => esc_attr__( 'Square', 'elegant-elements' ),
					'round'  => esc_attr__( 'Round', 'elegant-elements' ),
				),
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

	$child_args = array(
		'name'              => esc_attr__( 'Carousel Item', 'elegant-elements' ),
		'shortcode'         => 'iee_carousel_item',
		'hide_from_builder' => true,
		'allow_generator'   => true,
		'on_change'         => 'elegantRefreshCarousel',
		'tag_name'          => 'div',
		'selectors'         => array(
			'class' => 'elegant-carousel-item',
		),
		'params'            => array(
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Carousel Title', 'elegant-elements' ),
				'param_name'  => 'title',
				'value'       => esc_attr__( 'Carousel item', 'elegant-elements' ),
				'placeholder' => true,
				'description' => esc_attr__( 'The title is only used as placeholder to identify the carousel item.', 'elegant-elements' ),
			),
			array(
				'type'        => 'tinymce',
				'heading'     => esc_attr__( 'Carousel Content', 'elegant-elements' ),
				'description' => esc_attr__( 'Enter content to be displayed in this carousel item.', 'elegant-elements' ),
				'param_name'  => 'element_content',
				'value'       => esc_attr__( 'Your content goes here', 'elegant-elements' ),
				'placeholder' => true,
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Trigger Class Name', 'elegant-elements' ),
				'param_name'  => 'trigger_class',
				'value'       => '',
				'description' => esc_attr__( 'Use this class name in any element to trigger this slide display. e.g slide-1', 'elegant-elements' ),
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
				'IEE_Carousel',
				$parent_args,
				'parent'
			)
		);

		fusion_builder_map(
			fusion_builder_frontend_data(
				'IEE_Carousel',
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_carousel', 99 );
