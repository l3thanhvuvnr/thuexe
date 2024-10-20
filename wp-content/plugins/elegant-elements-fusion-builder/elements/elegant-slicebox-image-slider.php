<?php
if ( fusion_is_element_enabled( 'iee_slicebox_image_slider' ) && ! class_exists( 'IEE_Slicebox_Image_Slider' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 3.3.0
	 */
	class IEE_Slicebox_Image_Slider extends Fusion_Element {

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
		 * Slicebox Image Slider counter.
		 *
		 * @since 3.3.0
		 * @access private
		 * @var object
		 */
		private $slicebox_image_slider_counter = 1;

		/**
		 * Slicebox Image Slider child counter.
		 *
		 * @since 3.3.0
		 * @access private
		 * @var object
		 */
		private $slicebox_image_slider_child_counter = 0;

		/**
		 * Slicebox Image Slider.
		 *
		 * @since 3.3.0
		 * @access private
		 * @var object
		 */
		private $slicebox_image_slider = array();

		/**
		 * Constructor.
		 *
		 * @since 3.3.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-slicebox-image-slider', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_elegant-slicebox-image-slider-items', array( $this, 'items_attr' ) );
			add_filter( 'fusion_attr_elegant-slicebox-navigation-arrows', array( $this, 'navigation_attr' ) );
			add_filter( 'fusion_attr_elegant-slicebox-image-slider-item', array( $this, 'item_attr' ) );

			add_shortcode( 'iee_slicebox_image_slider', array( $this, 'render_parent' ) );
			add_shortcode( 'iee_slicebox_image_slider_item', array( $this, 'render_child' ) );
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

			wp_enqueue_script( 'infi-elegant-slicebox-image-slider' );

			$this->slicebox_image_slider_child_counter = 1;

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'orientation'            => 'random',
					'slices_count'           => 5,
					'rotation_interval'      => 150,
					'speed'                  => 300,
					'autoplay'               => true,
					'autoplay_speed'         => 3,
					'enable_disperse_factor' => 'no',
					'disperse_factor'        => '20',
					'slide_title_font_size'  => 24,
					'navigation_arrows'      => 'yes',
					'arrow_position'         => 'middle',
					'next_slide_icon'        => 'fa-arrow-circle-right fas',
					'prev_slide_icon'        => 'fa-arrow-circle-left fas',
					'icon_color'             => '#333333',
					'icon_size'              => '32',
					'hide_on_mobile'         => fusion_builder_default_visibility( 'string' ),
					'class'                  => '',
					'id'                     => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/slicebox-image-slider/elegant-slicebox-image-slider-parent.php' ) ) {
				include locate_template( 'templates/slicebox-image-slider/elegant-slicebox-image-slider-parent.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/slicebox-image-slider/elegant-slicebox-image-slider-parent.php';
			}

			$this->slicebox_image_slider_counter++;

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
					'slide_title'            => '',
					'image_url'              => '',
					'image_id'               => '',
					'url'                    => '',
					'target'                 => '_blank',
					'slide_title_background' => 'rgba(33, 150, 243, 0.8)',
					'slide_title_text_color' => '#ffffff',
					'hide_on_mobile'         => fusion_builder_default_visibility( 'string' ),
					'class'                  => '',
					'id'                     => '',
				),
				$args
			);

			$this->child_args = $defaults;
			$child_html       = '';

			if ( '' !== locate_template( 'templates/slicebox-image-slider/elegant-slicebox-image-slider-child.php' ) ) {
				include locate_template( 'templates/slicebox-image-slider/elegant-slicebox-image-slider-child.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/slicebox-image-slider/elegant-slicebox-image-slider-child.php';
			}

			$this->slicebox_image_slider_child_counter++;
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
				'class' => 'elegant-slicebox-image-slider',
			);

			$attr['class'] .= ' elegant-slicebox-image-slider-' . $this->slicebox_image_slider_counter;

			$attr['data-orientation']       = ( 'horizontal' === $this->args['orientation'] ) ? 'h' : ( ( 'vertical' === $this->args['orientation'] ) ? 'v' : 'r' );
			$attr['data-slices_count']      = $this->args['slices_count'];
			$attr['data-rotation_interval'] = $this->args['rotation_interval'];
			$attr['data-speed']             = $this->args['speed'];
			$attr['data-autoplay']          = ( 'yes' === $this->args['autoplay'] ) ? 'true' : 'false';
			$attr['data-autoplay_speed']    = $this->args['autoplay_speed'] * 1000;
			$attr['data-disperse_factor']   = ( 'yes' === $this->args['enable_disperse_factor'] ) ? $this->args['disperse_factor'] : 0;

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
				'class' => 'elegant-slicebox-image-slider-items',
				'style' => '',
			);

			$attr['class'] .= ' eesb-slider';

			$attr['class'] .= ' elegant-slicebox-nav-pos-' . $this->args['arrow_position'];

			if ( 'top_left' === $this->args['arrow_position'] || 'top_right' === $this->args['arrow_position'] ) {
				$attr['style'] .= 'padding-top:' . FusionBuilder::validate_shortcode_attr_value( ( $this->args['icon_size'] + 6 ), 'px' ) . ';';
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
		public function navigation_attr() {
			$attr = array(
				'class' => 'elegant-slicebox-navigation-arrows',
				'style' => '',
			);

			$icon_size = FusionBuilder::validate_shortcode_attr_value( $this->args['icon_size'], 'px' );

			$attr['class'] .= ' elegant-slicebox-navigation-' . $this->args['arrow_position'];

			$attr['style'] .= 'color:' . $this->args['icon_color'] . ';';
			$attr['style'] .= 'font-size:' . $icon_size . ';';

			if ( 'middle' === $this->args['arrow_position'] ) {
				$attr['style'] .= 'top: calc( 50% - ' . $icon_size . ' / 2 );
			    left: calc( -' . $icon_size . ' - 4px );
			    width: calc( 100% + ( ' . $icon_size . ' * 2 ) + 8px );';
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
		public function item_attr() {
			$attr = array(
				'class' => 'elegant-slicebox-image-slider-item',
				'style' => '',
			);

			$attr['class'] .= ' elegant-slicebox-image-slider-item-' . $this->slicebox_image_slider_child_counter;

			if ( $this->child_args['class'] ) {
				$attr['class'] .= ' ' . $this->child_args['class'];
			}

			return $attr;
		}
	}

	new IEE_Slicebox_Image_Slider();
} // End if().

/**
 * Map shortcode for slicebox_image_slider.
 *
 * @since 3.3.0
 * @return void
 */
function map_elegant_elements_slicebox_image_slider() {
	global $fusion_settings;

	$parent_args = array(
		'name'          => esc_attr__( 'Elegant Slicebox Image Slider ( BETA )', 'elegant-elements' ),
		'shortcode'     => 'iee_slicebox_image_slider',
		'icon'          => 'icon-filter',
		'multi'         => 'multi_element_parent',
		'element_child' => 'iee_slicebox_image_slider_item',
		'child_ui'      => true,
		'params'        => array(
			array(
				'type'        => 'tinymce',
				'heading'     => esc_attr__( 'Content', 'elegant-elements' ),
				'description' => esc_attr__( 'Upload Images.', 'elegant-elements' ),
				'param_name'  => 'element_content',
				'value'       => '[iee_slicebox_image_slider_item]',
			),
			array(
				'type'             => 'info',
				'heading'          => esc_attr__( 'BETA ELEMENT', 'elegant-elements' ),
				'param_name'       => 'settings_info',
				'remove_from_atts' => true,
				'content'          => esc_attr__( 'The element is currently in beta version. Please do not use on production sites if you found any issues. While live preview in builder, the slider will display only fade effect to speed up the editing.', 'elegant-elements' ),
			),
			array(
				'type'             => 'multiple_upload',
				'heading'          => esc_attr__( 'Bulk Image Upload', 'elegant-elements' ),
				'description'      => __( 'This option allows you to select multiple images at once and they will populate into individual items. It saves time instead of adding one image at a time.', 'elegant-elements' ),
				'param_name'       => 'multiple_upload',
				'element_target'   => 'iee_slicebox_image_slider_item',
				'param_target'     => 'image_url',
				'child_params'     => array(
					'image_url' => 'url',
					'image_id'  => 'id',
				),
				'remove_from_atts' => true,
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Slice Orientation', 'elegant-elements' ),
				'param_name'  => 'orientation',
				'default'     => 'random',
				'value'       => array(
					'vertical'   => esc_attr__( 'Vertical', 'elegant-elements' ),
					'horizontal' => esc_attr__( 'Horizontal', 'elegant-elements' ),
					'random'     => esc_attr__( 'Random', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Controls the slicebox orientation.', 'elegant-elements' ),
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Number of Slices', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the number of slices you want to make for each image in the slider.', 'elegant-elements' ),
				'param_name'  => 'slices_count',
				'value'       => '5',
				'min'         => '2',
				'max'         => '15',
				'step'        => '1',
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Rotation Interval', 'elegant-elements' ),
				'description' => esc_attr__( 'Controls the interval between each rotation. In Miliseconds (ms)', 'elegant-elements' ),
				'param_name'  => 'rotation_interval',
				'value'       => '150',
				'min'         => '100',
				'max'         => '500',
				'step'        => '10',
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Animation Speed', 'elegant-elements' ),
				'description' => esc_attr__( 'Controls the speed that takes "1" slide to rotate. In Miliseconds (ms).', 'elegant-elements' ),
				'param_name'  => 'speed',
				'value'       => '600',
				'min'         => '100',
				'max'         => '5000',
				'step'        => '50',
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
				'heading'     => esc_attr__( 'Move Slide Cubes From Outside ', 'elegant-elements' ),
				'param_name'  => 'enable_disperse_factor',
				'default'     => 'no',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Each cube of the slide will start slightly from outside from left and top depent on the orientation.', 'elegant-elements' ),
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Move Cube Distance', 'elegant-elements' ),
				'param_name'  => 'disperse_factor',
				'value'       => '20',
				'min'         => '1',
				'max'         => '50',
				'step'        => '1',
				'dependency'  => array(
					array(
						'element'  => 'enable_disperse_factor',
						'value'    => 'yes',
						'operator' => '==',
					),
				),
				'description' => esc_attr__( 'The middle cuboid doesn\'t move. the middle cube\'s neighbors will move with this distance. In pixels (px).', 'elegant-elements' ),
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Slide Title Font Size', 'elegant-elements' ),
				'param_name'  => 'slide_title_font_size',
				'value'       => '24',
				'min'         => '10',
				'max'         => '100',
				'step'        => '1',
				'description' => esc_attr__( 'Controls the slide title text font size. In pixels (px).', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Navigation Arrows', 'elegant-elements' ),
				'param_name'  => 'navigation_arrows',
				'default'     => 'yes',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Enable navigation arrows.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Navigation', 'elegant-elements' ),
			),
			array(
				'type'        => 'select',
				'heading'     => esc_attr__( 'Arrow Position', 'elegant-elements' ),
				'param_name'  => 'arrow_position',
				'default'     => 'middle',
				'value'       => array(
					'middle'       => esc_attr__( 'Middle on Sides', 'elegant-elements' ),
					'top_left'     => esc_attr__( 'Top Left', 'elegant-elements' ),
					'top_right'    => esc_attr__( 'Top Right', 'elegant-elements' ),
					'bottom_left'  => esc_attr__( 'Bottom Left', 'elegant-elements' ),
					'bottom_right' => esc_attr__( 'Bottom Right', 'elegant-elements' ),
				),
				'dependency'  => array(
					array(
						'element'  => 'navigation_arrows',
						'value'    => 'yes',
						'operator' => '==',
					),
				),
				'description' => esc_attr__( 'Controls the slicebox orientation.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Navigation', 'elegant-elements' ),
			),
			array(
				'type'        => 'iconpicker',
				'heading'     => esc_attr__( 'Next Slide Icon', 'elegant-elements' ),
				'param_name'  => 'next_slide_icon',
				'value'       => 'fa-arrow-circle-right fas',
				'dependency'  => array(
					array(
						'element'  => 'navigation_arrows',
						'value'    => 'yes',
						'operator' => '==',
					),
				),
				'description' => esc_attr__( 'Select the icon for next arrow.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Navigation', 'elegant-elements' ),
			),
			array(
				'type'        => 'iconpicker',
				'heading'     => esc_attr__( 'Previous Slide Icon', 'elegant-elements' ),
				'param_name'  => 'prev_slide_icon',
				'value'       => 'fa-arrow-circle-left fas',
				'dependency'  => array(
					array(
						'element'  => 'navigation_arrows',
						'value'    => 'yes',
						'operator' => '==',
					),
				),
				'description' => esc_attr__( 'Select the icon for previous arrow.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Navigation', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Icon Color', 'elegant-elements' ),
				'param_name'  => 'icon_color',
				'value'       => '#333333',
				'dependency'  => array(
					array(
						'element'  => 'navigation_arrows',
						'value'    => 'yes',
						'operator' => '==',
					),
				),
				'description' => esc_attr__( 'Controls the font color of the navigation icon.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Navigation', 'elegant-elements' ),
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Icon Size', 'elegant-elements' ),
				'param_name'  => 'icon_size',
				'value'       => '32',
				'min'         => '10',
				'max'         => '100',
				'step'        => '1',
				'dependency'  => array(
					array(
						'element'  => 'navigation_arrows',
						'value'    => 'yes',
						'operator' => '==',
					),
				),
				'description' => esc_attr__( 'Controls the navigation icon font size. In pixels (px).', 'elegant-elements' ),
				'group'       => esc_attr__( 'Navigation', 'elegant-elements' ),
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
		'name'              => esc_attr__( 'Image Slide', 'elegant-elements' ),
		'shortcode'         => 'iee_slicebox_image_slider_item',
		'hide_from_builder' => true,
		'allow_generator'   => true,
		'tag_name'          => 'div',
		'selectors'         => array(
			'class' => 'elegant-slicebox-image-slider-item',
		),
		'params'            => array(
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Slide Title', 'elegant-elements' ),
				'param_name'  => 'slide_title',
				'placeholder' => true,
				'value'       => esc_attr__( 'Image title goes here', 'elegant-elements' ),
				'description' => esc_attr__( 'Controls the slide title text.', 'elegant-elements' ),
			),
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
				'type'        => 'link_selector',
				'heading'     => esc_attr__( 'URL to Open', 'elegant-elements' ),
				'description' => esc_attr__( 'Enter the url you want to open on the image click.', 'elegant-elements' ),
				'param_name'  => 'url',
				'value'       => '',
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
						'element'  => 'url',
						'value'    => '',
						'operator' => '!=',
					),
				),
			),
			array(
				'type'        => 'colorpickeralpha',
				'heading'     => esc_attr__( 'Title Background Color', 'elegant-elements' ),
				'param_name'  => 'slide_title_background',
				'value'       => 'rgba(33, 150, 243, 0.8)',
				'description' => esc_attr__( 'Controls the background color of the slide title.', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Title Text Color', 'elegant-elements' ),
				'param_name'  => 'slide_title_text_color',
				'value'       => '#ffffff',
				'description' => esc_attr__( 'Controls the text color of the slide text.', 'elegant-elements' ),
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
				'IEE_Slicebox_Image_Slider',
				$parent_args,
				'parent'
			)
		);

		fusion_builder_map(
			fusion_builder_frontend_data(
				'IEE_Slicebox_Image_Slider',
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_slicebox_image_slider', 99 );
