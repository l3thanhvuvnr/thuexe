<?php
if ( fusion_is_element_enabled( 'iee_icon_block' ) && ! class_exists( 'IEE_Icon_Block' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 2.0
	 */
	class IEE_Icon_Block extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 2.0
		 * @var array
		 */
		protected $args;

		/**
		 * Constructor.
		 *
		 * @since 2.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-icon-block', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_elegant-icon-block-title', array( $this, 'title_attr' ) );
			add_filter( 'fusion_attr_elegant-icon-block-description', array( $this, 'description_attr' ) );
			add_filter( 'fusion_attr_elegant-icon-block-icon-wrapper', array( $this, 'icon_wrapper_attr' ) );
			add_filter( 'fusion_attr_elegant-icon-block-icon', array( $this, 'icon_attr' ) );

			add_shortcode( 'iee_icon_block', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 2.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'title'                   => '',
					'description'             => '',
					'icon'                    => '',
					'icon_display'            => 'top',
					'icon_size'               => '48',
					'link'                    => '',
					'target'                  => '',
					'content_align'           => 'center',
					'title_color'             => '',
					'title_color_hover'       => '',
					'description_color'       => '',
					'description_color_hover' => '',
					'icon_color'              => '',
					'icon_color_hover'        => '',
					'background_color_1'      => '',
					'background_color_2'      => '',
					'gradient_direction'      => '0deg',
					'element_typography'      => 'default',
					'typography_title'        => '',
					'title_font_size'         => '28',
					'typography_description'  => '',
					'description_font_size'   => '18',
					'hide_on_mobile'          => fusion_builder_default_visibility( 'string' ),
					'class'                   => '',
					'id'                      => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/icon-block/elegant-icon-block.php' ) ) {
				include locate_template( 'templates/icon-block/elegant-icon-block.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/icon-block/elegant-icon-block.php';
			}

			return $html;
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
				'class' => 'elegant-icon-block',
				'style' => '',
			);

			$attr = fusion_builder_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$attr['class'] .= ' elegant-align-' . $this->args['content_align'];

			if ( $this->args['background_color_1'] && '' !== $this->args['background_color_1'] && '' === $this->args['background_color_2'] ) {
				$attr['style'] .= 'background:' . $this->args['background_color_1'] . ';';
			}

			if ( '' !== $this->args['background_color_1'] && '' !== $this->args['background_color_2'] ) {
				$attr['style'] .= 'background: ' . $this->get_gradient_color() . ';';
			}

			$attr['style'] .= 'border-color:' . $this->args['background_color_1'] . ';';

			if ( '' !== $this->args['title_color_hover'] ) {
				$attr['style'] .= '--title-hover:' . $this->args['title_color_hover'] . ';';
			}

			if ( '' !== $this->args['description_color_hover'] ) {
				$attr['style'] .= '--description-hover:' . $this->args['description_color_hover'] . ';';
			}

			if ( '' !== $this->args['icon_color_hover'] ) {
				$attr['style'] .= '--icon-hover:' . $this->args['icon_color_hover'] . ';';
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
		 * @since 2.0
		 * @return array
		 */
		public function title_attr() {
			$attr = array(
				'class' => 'elegant-icon-block-title',
				'style' => '',
			);

			if ( isset( $this->args['typography_title'] ) && '' !== $this->args['typography_title'] ) {
				$attr['style'] .= elegant_get_typography_css( $this->args['typography_title'] );
			}

			$attr['style'] .= 'color:' . $this->args['title_color'] . ';';
			$attr['style'] .= 'font-size:' . FusionBuilder::validate_shortcode_attr_value( $this->args['title_font_size'], 'px' ) . ';';

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 2.0
		 * @return array
		 */
		public function description_attr() {
			$attr = array(
				'class' => 'elegant-icon-block-description',
				'style' => '',
			);

			if ( isset( $this->args['typography_description'] ) && '' !== $this->args['typography_description'] ) {
				$attr['style'] .= elegant_get_typography_css( $this->args['typography_description'] );
			}

			$attr['style'] .= 'color:' . $this->args['description_color'] . ';';
			$attr['style'] .= 'font-size:' . FusionBuilder::validate_shortcode_attr_value( $this->args['description_font_size'], 'px' ) . ';';

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 2.0
		 * @return array
		 */
		public function icon_wrapper_attr() {
			$attr = array(
				'class' => 'elegant-icon-block-icon-wrapper',
			);

			$attr['class'] .= ' elegant-icon-block-icon-position-' . $this->args['icon_display'];

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 2.0
		 * @return array
		 */
		public function icon_attr() {
			$attr = array(
				'class' => 'elegant-icon-block-icon',
				'style' => '',
			);

			$attr['class'] .= ' ' . $this->args['icon'];

			$attr['style'] .= 'color:' . $this->args['icon_color'] . ';';
			$attr['style'] .= 'font-size:' . FusionBuilder::validate_shortcode_attr_value( $this->args['icon_size'], 'px' ) . ';';

			return $attr;
		}

		/**
		 * Generates and returns the gradient color for heading.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return array
		 */
		public function get_gradient_color() {
			$gradient_color_1   = $this->args['background_color_1'];
			$gradient_color_2   = $this->args['background_color_2'];
			$gradient_direction = $this->args['gradient_direction'];

			if ( 'vertical' === $gradient_direction ) {
				$gradient_direction = 'top';
				// Safari 4-5, Chrome 1-9 support.
				$gradient = 'background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(' . $gradient_color_1 . '), to(' . $gradient_color_2 . '));';
			} else {
				// Safari 4-5, Chrome 1-9 support.
				$gradient = 'background: -webkit-gradient(linear, left top, right top, from(' . $gradient_color_1 . '), to(' . $gradient_color_2 . '));';
			}

			// Safari 5.1, Chrome 10+ support.
			$gradient .= 'background: -webkit-linear-gradient(' . $gradient_direction . ', ' . $gradient_color_1 . ', ' . $gradient_color_2 . ');';

			// Firefox 3.6+ support.
			$gradient .= 'background: -moz-linear-gradient(' . $gradient_direction . ', ' . $gradient_color_1 . ', ' . $gradient_color_2 . ');';

			// IE 10+ support.
			$gradient .= 'background: -ms-linear-gradient(' . $gradient_direction . ', ' . $gradient_color_1 . ', ' . $gradient_color_2 . ');';

			// Opera 11.10+ support.
			$gradient .= 'background: -o-linear-gradient(' . $gradient_direction . ', ' . $gradient_color_1 . ', ' . $gradient_color_2 . ');';

			return $gradient;
		}
	}

	new IEE_Icon_Block();
} // End if().

/**
 * Map shortcode for icon_block.
 *
 * @since 2.0
 * @return void
 */
function map_elegant_elements_icon_block() {
	global $fusion_settings;

	// Get default typography for title and description.
	$default_typography = elegant_get_default_typography();

	fusion_builder_map(
		array(
			'name'                      => esc_attr__( 'Elegant Icon Block', 'elegant-elements' ),
			'shortcode'                 => 'iee_icon_block',
			'icon'                      => 'fa-font-awesome-alt fab el-icon-block-icon',
			'preview'                   => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/previews/infi-icon-block-preview.php',
			'preview_id'                => 'elegant-elements-module-infi-icon-block-preview-template',
			'front-end'                 => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-icon-block-preview.php',
			'inline_editor'             => true,
			'custom_settings_view_name' => 'ModuleSettingElegantView',
			'params'                    => array(
				array(
					'type'         => 'textfield',
					'heading'      => esc_attr__( 'Title', 'elegant-elements' ),
					'param_name'   => 'title',
					'value'        => esc_attr__( 'Icon Block', 'elegant-elements' ),
					'placeholder'  => true,
					'dynamic_data' => true,
					'description'  => esc_attr__( 'Enter the title.', 'elegant-elements' ),
				),
				array(
					'type'         => 'textarea',
					'heading'      => esc_attr__( 'Description', 'elegant-elements' ),
					'param_name'   => 'description',
					'value'        => esc_attr__( 'Do you know? Elegant Elements for Fusion Builder also comes with pre-designed demos?', 'elegant-elements' ),
					'placeholder'  => true,
					'dynamic_data' => true,
					'description'  => esc_attr__( 'Enter the description text.', 'elegant-elements' ),
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_attr__( 'Icon', 'elegant-elements' ),
					'param_name'  => 'icon',
					'value'       => 'fa-award fas',
					'description' => esc_attr__( 'Select the icon.', 'elegant-elements' ),
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Icon Display', 'elegant-elements' ),
					'description' => esc_attr__( 'Select how you want the icon to be displayed.', 'elegant-elements' ),
					'param_name'  => 'icon_display',
					'default'     => 'top',
					'value'       => array(
						'top'        => __( 'Before the content', 'elegant-elements' ),
						'bottom'     => __( 'After the content', 'elegant-elements' ),
						'background' => __( 'As block background', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Icon Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the size of the icon. If using as background, set the larger size for better look. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'icon_size',
					'value'       => '48',
					'min'         => '10',
					'max'         => '500',
					'step'        => '1',
				),
				array(
					'type'         => 'link_selector',
					'heading'      => esc_attr__( 'Link', 'elegant-elements' ),
					'param_name'   => 'link',
					'value'        => '',
					'dynamic_data' => true,
					'description'  => esc_attr__( 'Adds link to the entire icon block.', 'elegant-elements' ),
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
							'element'  => 'link',
							'value'    => '',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Content Alignment', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the content alighment including title, description and icon.', 'elegant-elements' ),
					'param_name'  => 'content_align',
					'default'     => 'center',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'center' => esc_attr__( 'Center', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Title Color', 'elegant-elements' ),
					'param_name'  => 'title_color',
					'value'       => '',
					'description' => esc_attr__( 'Controls the title color for this icon block.', 'elegant-elements' ),
					'group'       => 'Design',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Title Color - Hover', 'elegant-elements' ),
					'param_name'  => 'title_color_hover',
					'value'       => '',
					'description' => esc_attr__( 'Controls the title hover color for this icon block.', 'elegant-elements' ),
					'group'       => 'Design',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Description Color', 'elegant-elements' ),
					'param_name'  => 'description_color',
					'value'       => '',
					'description' => esc_attr__( 'Controls the description color for this icon block.', 'elegant-elements' ),
					'group'       => 'Design',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Description Color - Hover', 'elegant-elements' ),
					'param_name'  => 'description_color_hover',
					'value'       => '',
					'description' => esc_attr__( 'Controls the description hover color for this icon block.', 'elegant-elements' ),
					'group'       => 'Design',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Icon Color', 'elegant-elements' ),
					'param_name'  => 'icon_color',
					'value'       => '',
					'description' => esc_attr__( 'Controls the icon color for this icon block.', 'elegant-elements' ),
					'group'       => 'Design',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Icon Color - Hover', 'elegant-elements' ),
					'param_name'  => 'icon_color_hover',
					'value'       => '',
					'description' => esc_attr__( 'Controls the icon color for hover state of the icon block.', 'elegant-elements' ),
					'group'       => 'Design',
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Background Color', 'elegant-elements' ),
					'param_name'  => 'background_color_1',
					'value'       => '',
					'description' => esc_attr__( 'Controls the background color for this icon block.', 'elegant-elements' ),
					'group'       => 'Design',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Background Color 2', 'elegant-elements' ),
					'param_name'  => 'background_color_2',
					'value'       => '',
					'description' => esc_attr__( 'Controls the second background color. If set, it will form gradient background.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Gradient Direction', 'elegant-elements' ),
					'param_name'  => 'gradient_direction',
					'default'     => '0deg',
					'value'       => array(
						'vertical' => esc_attr__( 'Vertical From Top to Bottom', 'elegant-elements' ),
						'0deg'     => esc_attr__( 'Gradient From Left to Right', 'elegant-elements' ),
						'45deg'    => esc_attr__( 'Gradient From Bottom - Left Angle', 'elegant-elements' ),
						'-45deg'   => esc_attr__( 'Gradient From Top - Left Angle', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Controls the gradient background color direction for this heading. Works only if both the background colors are set.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Element Typography Override', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose if you want to keep the theme options global typography as default for this element or want to set custom. Controls typography options for all typography fields in this element.', 'elegant-elements' ),
					'param_name'  => 'element_typography',
					'default'     => 'custom',
					'value'       => array(
						'default' => esc_attr__( 'Default', 'elegant-elements' ),
						'custom'  => esc_attr__( 'Custom', 'elegant-elements' ),
					),
					'group'       => 'Typography',
				),
				array(
					'type'        => 'elegant_typography',
					'heading'     => esc_attr__( 'Title Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select typography for the title text.', 'elegant-elements' ),
					'param_name'  => 'typography_title',
					'value'       => '',
					'default'     => $default_typography['title'],
					'dependency'  => array(
						array(
							'element'  => 'element_typography',
							'value'    => 'default',
							'operator' => '!=',
						),
					),
					'group'       => 'Typography',
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Title Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for title text. ( In Pixel. )', 'elegant-elements' ),
					'param_name'  => 'title_font_size',
					'value'       => '28',
					'min'         => '12',
					'max'         => '100',
					'step'        => '1',
					'group'       => 'Typography',
				),
				array(
					'type'        => 'elegant_typography',
					'heading'     => esc_attr__( 'Description Text Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select typography for the description text.', 'elegant-elements' ),
					'param_name'  => 'typography_description',
					'value'       => '',
					'default'     => $default_typography['description'],
					'dependency'  => array(
						array(
							'element'  => 'element_typography',
							'value'    => 'default',
							'operator' => '!=',
						),
					),
					'group'       => 'Typography',
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Description Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for description text. In Pixel (px).', 'elegant-elements' ),
					'param_name'  => 'description_font_size',
					'value'       => '18',
					'min'         => '12',
					'max'         => '100',
					'step'        => '1',
					'group'       => 'Typography',
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_icon_block', 99 );
