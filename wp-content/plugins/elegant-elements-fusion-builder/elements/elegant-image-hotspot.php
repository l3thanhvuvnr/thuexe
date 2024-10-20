<?php
if ( fusion_is_element_enabled( 'iee_image_hotspot' ) && ! class_exists( 'IEE_Image_Hotspot' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 2.0
	 */
	class IEE_Image_Hotspot extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 2.0
		 * @var array
		 */
		protected $args;

		/**
		 * An array of the child shortcode arguments.
		 *
		 * @access protected
		 * @since 2.0
		 * @var array
		 */
		protected $child_args;

		/**
		 * Image Filters counter.
		 *
		 * @since 2.0
		 * @access private
		 * @var object
		 */
		private $image_hotspot_counter = 1;

		/**
		 * Image Filters child counter.
		 *
		 * @since 2.0
		 * @access private
		 * @var object
		 */
		private $image_hotspot_child_counter = 0;

		/**
		 * Constructor.
		 *
		 * @since 2.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			// Parent filters.
			add_filter( 'fusion_attr_elegant-image-hotspot', array( $this, 'attr' ) );

			// Child filters.
			add_filter( 'fusion_attr_elegant-image-hotspot-item', array( $this, 'hotspot_item_attr' ) );

			add_shortcode( 'iee_image_hotspot', array( $this, 'render_parent' ) );
			add_shortcode( 'iee_image_hotspot_item', array( $this, 'render_child' ) );
		}

		/**
		 * Render the parent shortcode.
		 *
		 * @access public
		 * @since 2.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render_parent( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			$this->image_hotspot_child_counter = 1;

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'hotspot_image'                  => '',
					'hotspot_size'                   => '16',
					'hotspot_text_color'             => '#ffffff',
					'hotspot_background_color'       => '#333333',
					'hotspot_background_color_hover' => '#666666',
					'tooltip_text_color'             => '#ffffff',
					'tooltip_background_color'       => '#333333',
					'pointer_effect'                 => 'pulse',
					'pointer_shape'                  => 'circle',
					'hide_on_mobile'                 => fusion_builder_default_visibility( 'string' ),
					'class'                          => '',
					'id'                             => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/image-hotspot/elegant-image-hotspot-parent.php' ) ) {
				include locate_template( 'templates/image-hotspot/elegant-image-hotspot-parent.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/image-hotspot/elegant-image-hotspot-parent.php';
			}

			$this->image_hotspot_counter++;

			return $html;
		}

		/**
		 * Render the child shortcode.
		 *
		 * @access public
		 * @since 2.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render_child( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			$args['tooltip_text_color']       = ( isset( $args['tooltip_text_color'] ) && '' !== $args['tooltip_text_color'] ) ? $args['tooltip_text_color'] : $this->args['tooltip_text_color'];
			$args['tooltip_background_color'] = ( isset( $args['tooltip_background_color'] ) && '' !== $args['tooltip_background_color'] ) ? $args['tooltip_background_color'] : $this->args['tooltip_background_color'];

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'title'                    => esc_attr__( 'Hotspot Item', 'elegant-elements' ),
					'disable_tooltip'          => 'no',
					'link_url'                 => '',
					'target'                   => '',
					'hotspot_position_left'    => '50',
					'hotspot_position_top'     => '50',
					'pointer_type'             => 'count',
					'pointer_icon'             => '',
					'pointer_custom_text'      => '',
					'custom_pointer_title'     => '',
					'pointer_title_position'   => 'right',
					'pointer_title_spacing'    => '0',
					'tooltip_position'         => 'top',
					'tooltip_text_color'       => '#ffffff',
					'tooltip_background_color' => '#333333',
				),
				$args
			);

			$this->child_args = $defaults;
			$child_html       = '';

			if ( '' !== locate_template( 'templates/image-hotspot/elegant-image-hotspot-child.php' ) ) {
				include locate_template( 'templates/image-hotspot/elegant-image-hotspot-child.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/image-hotspot/elegant-image-hotspot-child.php';
			}

			$this->image_hotspot_child_counter++;

			return $child_html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 2.0
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-image-hotspot elegant-image-hotspot-container',
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
		 * @since 2.0
		 * @return array
		 */
		public function hotspot_item_attr() {
			$attr = array(
				'class' => 'elegant-image-hotspot-item',
				'style' => '',
			);

			if ( 'text' === $this->child_args['pointer_type'] ) {
				$attr['class'] .= ' custom-text-pointer';
			}

			$attr['style'] .= 'font-size:' . FusionBuilder::validate_shortcode_attr_value( $this->args['hotspot_size'], 'px' ) . ';';
			$attr['style'] .= 'top:' . $this->child_args['hotspot_position_top'] . '%;';
			$attr['style'] .= 'left:' . $this->child_args['hotspot_position_left'] . '%;';
			$attr['style'] .= 'color:' . $this->args['hotspot_text_color'] . ';';
			$attr['style'] .= '--background-color:' . $this->args['hotspot_background_color'] . ';';
			$attr['style'] .= '--hover-background-color:' . $this->args['hotspot_background_color_hover'] . ';';
			$attr['style'] .= '--tooltip-text-color:' . $this->child_args['tooltip_text_color'] . ';';
			$attr['style'] .= '--tooltip-background-color:' . $this->child_args['tooltip_background_color'] . ';';
			$attr['style'] .= '--hotspot-title-spacing:' . FusionBuilder::validate_shortcode_attr_value( $this->child_args['pointer_title_spacing'], 'px' ) . ';';

			return $attr;
		}
	}

	new IEE_Image_Hotspot();
} // End if().

/**
 * Map shortcode for image_hotspot.
 *
 * @since 2.0
 * @return void
 */
function map_elegant_elements_image_hotspot() {
	global $fusion_settings;

	$parent_args = array(
		'name'          => esc_attr__( 'Elegant Image Hotspot', 'elegant-elements' ),
		'shortcode'     => 'iee_image_hotspot',
		'multi'         => 'multi_element_parent',
		'element_child' => 'iee_image_hotspot_item',
		'icon'          => 'fa-file-image fas image-hotspot-icon',
		'front-end'     => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-image-hotspot-preview.php',
		'child_ui'      => true,
		'params'        => array(
			array(
				'type'        => 'tinymce',
				'heading'     => esc_attr__( 'Hotspots', 'elegant-elements' ),
				'description' => esc_attr__( 'Enter some content for this contentbox.', 'elegant-elements' ),
				'param_name'  => 'element_content',
				'value'       => '[iee_image_hotspot_item title="' . esc_attr__( 'Hotspot item 1', 'elegant-elements' ) . '" hotspot_position_left="60" hotspot_position_top="45" tooltip_position="top" tooltip_background_color="#666666" /][iee_image_hotspot_item title="' . esc_attr__( 'Hotspot item 2', 'elegant-elements' ) . '" hotspot_position_left="32" hotspot_position_top="16" tooltip_position="top" tooltip_background_color="#666666" /]',
			),
			array(
				'type'        => 'upload',
				'heading'     => esc_attr__( 'Hotspot Image', 'elegant-elements' ),
				'param_name'  => 'hotspot_image',
				'value'       => '',
				'description' => esc_attr__( 'Upload the image to be displayed hotspot locations on.', 'elegant-elements' ),
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Hotspot Size', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the font size of the hotspot pointer text. ( In Pixel ).', 'elegant-elements' ),
				'param_name'  => 'hotspot_size',
				'value'       => '16',
				'min'         => '10',
				'max'         => '100',
				'step'        => '1',
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Hotspot Text Color', 'elegant-elements' ),
				'param_name'  => 'hotspot_text_color',
				'value'       => '#ffffff',
				'placeholder' => true,
				'description' => esc_attr__( 'Choose the text or icon color of the hotspot pointer.', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Hotspot Background Color', 'elegant-elements' ),
				'param_name'  => 'hotspot_background_color',
				'value'       => '#333333',
				'placeholder' => true,
				'description' => esc_attr__( 'Choose the background color of the hotspot pointer.', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Hotspot Hover Background Color', 'elegant-elements' ),
				'param_name'  => 'hotspot_background_color_hover',
				'value'       => '#666666',
				'placeholder' => true,
				'description' => esc_attr__( 'Choose the background color of the hotspot pointer when mouse hover.', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Tooltip Text Color', 'elegant-elements' ),
				'param_name'  => 'tooltip_text_color',
				'default'     => '',
				'value'       => '',
				'description' => esc_attr__( 'Choose the text color of the tooltip.', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Tooltip Background Color', 'elegant-elements' ),
				'param_name'  => 'tooltip_background_color',
				'default'     => '',
				'value'       => '',
				'description' => esc_attr__( 'Choose the background color of the tooltip.', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Pointer Effect', 'elegant-elements' ),
				'param_name'  => 'pointer_effect',
				'default'     => 'pulse',
				'value'       => array(
					'pulse' => esc_attr__( 'Pulse', 'elegant-elements' ),
					'sonar' => esc_attr__( 'Sonar', 'elegant-elements' ),
					'none'  => esc_attr__( 'None', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Select the animation effect for the hotspot pointer.', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Pointer Shape', 'elegant-elements' ),
				'param_name'  => 'pointer_shape',
				'default'     => 'circle',
				'value'       => array(
					'circle' => esc_attr__( 'Circle', 'elegant-elements' ),
					'square' => esc_attr__( 'Square', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Select the hotspot pointer shape.', 'elegant-elements' ),
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
		'name'              => esc_attr__( 'Hotspot Item', 'elegant-elements' ),
		'shortcode'         => 'iee_image_hotspot_item',
		'hide_from_builder' => true,
		'allow_generator'   => false,
		'tag_name'          => 'div',
		'selectors'         => array(
			'class' => 'elegant-image-hotspot-childitem',
		),
		'params'            => array(
			array(
				'type'         => 'textfield',
				'heading'      => esc_attr__( 'Title', 'elegant-elements' ),
				'param_name'   => 'title',
				'dynamic_data' => true,
				'value'        => esc_attr__( 'Hotspot Item', 'elegant-elements' ),
				'description'  => esc_attr__( 'Enter title for this hotspot item to be displayed when hover as tooltip.', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Disable Tooltip', 'elegant-elements' ),
				'param_name'  => 'disable_tooltip',
				'default'     => 'no',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Enable or disable tooltip on hover.', 'elegant-elements' ),
			),
			array(
				'type'         => 'link_selector',
				'heading'      => esc_attr__( 'Link URL', 'elegant-elements' ),
				'param_name'   => 'link_url',
				'value'        => '',
				'dynamic_data' => true,
				'dependency'   => array(
					array(
						'element'  => 'title',
						'value'    => '',
						'operator' => '!=',
					),
					array(
						'element'  => 'disable_tooltip',
						'value'    => 'yes',
						'operator' => '!=',
					),
				),
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
				'type'        => 'range',
				'heading'     => esc_attr__( 'Hotspot Pointer Position - Left', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the left position of the hotspot pointer on image. ( In % ).', 'elegant-elements' ),
				'param_name'  => 'hotspot_position_left',
				'value'       => '50',
				'min'         => '1',
				'max'         => '100',
				'step'        => '.1',
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Hotspot Pointer Position - Top', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the top position of the hotspot pointer on image. ( In % ).', 'elegant-elements' ),
				'param_name'  => 'hotspot_position_top',
				'value'       => '50',
				'min'         => '1',
				'max'         => '100',
				'step'        => '.1',
			),
			array(
				'type'        => 'select',
				'heading'     => esc_attr__( 'Pointer Type', 'elegant-elements' ),
				'param_name'  => 'pointer_type',
				'default'     => 'count',
				'value'       => array(
					'count' => esc_attr__( 'The Item Counter Number', 'elegant-elements' ),
					'icon'  => esc_attr__( 'FontAwesome Icon', 'elegant-elements' ),
					'text'  => esc_attr__( 'Custom Text', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Select the pointer placeholder for this item.', 'elegant-elements' ),
			),
			array(
				'type'        => 'iconpicker',
				'heading'     => esc_attr__( 'Pointer Icon', 'elegant-elements' ),
				'param_name'  => 'pointer_icon',
				'value'       => 'fa fa-map-marker',
				'description' => esc_attr__( 'Select the icon to be used as hotspot pointer.', 'elegant-elements' ),
				'dependency'  => array(
					array(
						'element'  => 'pointer_type',
						'value'    => 'icon',
						'operator' => '==',
					),
				),
			),
			array(
				'type'        => 'raw_textarea',
				'heading'     => esc_attr__( 'Pointer Custom Text', 'elegant-elements' ),
				'param_name'  => 'pointer_custom_text',
				'value'       => '',
				'description' => esc_attr__( 'Enter the text to be used for the hotspot pointer.', 'elegant-elements' ),
				'dependency'  => array(
					array(
						'element'  => 'pointer_type',
						'value'    => 'text',
						'operator' => '==',
					),
				),
			),
			array(
				'type'        => 'raw_textarea',
				'heading'     => esc_attr__( 'Custom Pointer Title', 'elegant-elements' ),
				'param_name'  => 'custom_pointer_title',
				'value'       => '',
				'description' => esc_attr__( 'Enter the title to be used alongside the hotspot pointer. ', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Pointer Title Position', 'elegant-elements' ),
				'param_name'  => 'pointer_title_position',
				'default'     => 'right',
				'value'       => array(
					'left'  => esc_attr__( 'Left', 'elegant-elements' ),
					'right' => esc_attr__( 'Right', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Select the position for the pointer title.', 'elegant-elements' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Icon and Text Spacing', 'elegant-elements' ),
				'param_name'  => 'pointer_title_spacing',
				'value'       => '',
				'description' => esc_attr__( 'Enter value in pixels to set the icon and text spacing.', 'elegant-elements' ),
				'dependency'  => array(
					array(
						'element'  => 'custom_pointer_title',
						'value'    => '',
						'operator' => '!=',
					),
				),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Tooltip Position', 'elegant-elements' ),
				'param_name'  => 'tooltip_position',
				'default'     => 'top',
				'value'       => array(
					'left'   => esc_attr__( 'Left', 'elegant-elements' ),
					'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					'top'    => esc_attr__( 'Top', 'elegant-elements' ),
					'bottom' => esc_attr__( 'Bottom', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Select the tooltip position for this hotspot item.', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Tooltip Text Color', 'elegant-elements' ),
				'param_name'  => 'tooltip_text_color',
				'default'     => '#ffffff',
				'value'       => '',
				'description' => esc_attr__( 'Choose the text color of the tooltip.', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Tooltip Background Color', 'elegant-elements' ),
				'param_name'  => 'tooltip_background_color',
				'default'     => '#333333',
				'value'       => '',
				'description' => esc_attr__( 'Choose the background color of the tooltip.', 'elegant-elements' ),
			),
		),
	);

	if ( function_exists( 'fusion_builder_frontend_data' ) ) {
		fusion_builder_map(
			fusion_builder_frontend_data(
				'IEE_Image_Hotspot',
				$parent_args,
				'parent'
			)
		);

		fusion_builder_map(
			fusion_builder_frontend_data(
				'IEE_Image_Hotspot',
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_image_hotspot', 99 );
