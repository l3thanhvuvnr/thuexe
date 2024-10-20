<?php
if ( fusion_is_element_enabled( 'iee_fancy_button' ) && ! class_exists( 'IEE_Fancy_Button' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class IEE_Fancy_Button extends Fusion_Element {

		/**
		 * The button counter.
		 *
		 * @access private
		 * @since 1.0
		 * @var int
		 */
		private $button_counter = 1;

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $args;

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-fancy-button', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_elegant-fancy-button-wrapper', array( $this, 'wrapper_attr' ) );
			add_filter( 'fusion_attr_elegant-fancy-button-link', array( $this, 'link_attr' ) );

			add_shortcode( 'iee_fancy_button', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 1.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			// Get default typography for title and description.
			$default_typography = elegant_get_default_typography();

			if ( isset( $args['element_typography'] ) && 'default' === $args['element_typography'] ) {
				$args['typography_button_title'] = $default_typography['title'];
			}

			if ( ! isset( $args['alignment'] ) ) {
				$args['alignment'] = 'left';
			}

			$this->args = $args;

			$html = '';

			if ( '' !== locate_template( 'templates/fancy-button/elegant-fancy-button.php' ) ) {
				include locate_template( 'templates/fancy-button/elegant-fancy-button.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/fancy-button/elegant-fancy-button.php';
			}

			$this->button_counter++;

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-fancy-button-wrap elegant-fancy-button-' . $this->button_counter,
			);

			$attr = fusion_builder_visibility_atts( $this->args['hide_on_mobile'], $attr );

			if ( isset( $this->args['margin'] ) && '' !== $this->args['margin'] ) {
				$attr['style'] = 'margin:' . FusionBuilder::validate_shortcode_attr_value( $this->args['margin'], 'px' ) . ';';
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
		 * @since 1.0
		 * @return array
		 */
		public function wrapper_attr() {
			$attr = array(
				'class' => 'elegant-fancy-button-wrapper',
			);

			if ( $this->args['alignment'] ) {
				$attr['class'] .= ' elegant-align-' . $this->args['alignment'];
			}

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function link_attr() {
			$attr = array(
				'class' => 'elegant-fancy-button-link elegant-button-' . $this->args['style'] . ' elegant-button-' . $this->args['shape'] . ' button-' . $this->args['size'] . '',
				'style' => '',
				'href'  => '#',
			);

			if ( isset( $this->args['icon_position'] ) && '' !== $this->args['icon_position'] ) {
				$attr['class'] .= ' elegant-fancy-button-icon-' . $this->args['icon_position'];
			}

			if ( isset( $this->args['action'] ) && 'custom_link' === $this->args['action'] ) {
				$attr['href']   = ( isset( $this->args['custom_link'] ) && '' !== $this->args['custom_link'] ) ? $this->args['custom_link'] : '#';
				$attr['target'] = ( isset( $this->args['target'] ) && '' !== $this->args['target'] ) ? $this->args['target'] : '_self';
			} elseif ( isset( $this->args['action'] ) && 'image_lightbox' === $this->args['action'] ) {
				$attr['href']     = ( isset( $this->args['lightbox_image_url'] ) && '' !== $this->args['lightbox_image_url'] ) ? $this->args['lightbox_image_url'] : '#';
				$attr['data-rel'] = 'prettyPhoto';
			} elseif ( isset( $this->args['action'] ) && 'video_lightbox' === $this->args['action'] ) {
				$attr['href']     = ( isset( $this->args['lightbox_video_url'] ) && '' !== $this->args['lightbox_video_url'] ) ? $this->args['lightbox_video_url'] : '#';
				$attr['data-rel'] = 'prettyPhoto';
			} elseif ( isset( $this->args['action'] ) && 'modal' === $this->args['action'] ) {
				$attr['data-toggle'] = 'modal';
				$attr['data-target'] = '.modal.' . $this->args['modal_name'];
			} elseif ( isset( $this->args['action'] ) && 'image_block' === $this->args['action'] ) {
				if ( wp_is_mobile() ) {
					$attr['href']     = ( isset( $this->args['lightbox_image_url'] ) && '' !== $this->args['lightbox_image_url'] ) ? $this->args['lightbox_image_url'] : '#';
					$attr['data-rel'] = 'prettyPhoto';
				} else {
					$attr['href']           = 'javascript:void(0);';
					$attr['class']         .= ' elegant-fancy-button-display-image';
					$attr['data-image-div'] = ( isset( $this->args['image_block_class_name'] ) && '' !== $this->args['image_block_class_name'] ) ? $this->args['image_block_class_name'] : '';
					$attr['data-image-src'] = ( isset( $this->args['lightbox_image_url'] ) && '' !== $this->args['lightbox_image_url'] ) ? $this->args['lightbox_image_url'] : '#';
				}
			}

			if ( isset( $this->args['typography_button_title'] ) && '' !== $this->args['typography_button_title'] ) {
				$typography              = $this->args['typography_button_title'];
				$button_title_typography = elegant_get_typography_css( $typography );

				$attr['style'] .= $button_title_typography;
			}

			if ( isset( $this->args['title_font_size'] ) && '' !== $this->args['title_font_size'] ) {
				$attr['style'] .= 'font-size:' . FusionBuilder::validate_shortcode_attr_value( $this->args['title_font_size'], 'px' ) . ';';
				$attr['style'] .= 'line-height: 1em;';
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
				'infi-elegant-fancy-button',
				$elegant_js_folder_url . '/infi-elegant-fancy-button.min.js',
				$elegant_js_folder_path . '/infi-elegant-fancy-button.min.js',
				array( 'jquery' ),
				ELEGANT_ELEMENTS_VERSION,
				true
			);
		}
	}

	new IEE_Fancy_Button();
} // End if().

/**
 * Map shortcode for fancy_button.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_fancy_button() {
	global $fusion_settings;

	// Get default typography for title and description.
	$default_typography = elegant_get_default_typography();

	fusion_builder_map(
		array(
			'name'                      => esc_attr__( 'Elegant Fancy Button', 'elegant-elements' ),
			'shortcode'                 => 'iee_fancy_button',
			'icon'                      => 'fa-hand-point-up far elegant-fancy-button-icon',
			'preview'                   => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/previews/infi-fancy-button-preview.php',
			'preview_id'                => 'elegant-elements-module-infi-fancy-button-preview-template',
			'front-end'                 => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-fancy-button-preview.php',
			'custom_settings_view_name' => 'ModuleSettingElegantView',
			'params'                    => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Button Text', 'elegant-elements' ),
					'param_name'  => 'button_title',
					'value'       => 'Fancy Button',
					'placeholder' => true,
					'description' => esc_attr__( 'Enter button title text.', 'elegant-elements' ),
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Button Style', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the button style.', 'elegant-elements' ),
					'param_name'  => 'style',
					'default'     => 'swipe',
					'value'       => array(
						'swipe'              => esc_attr__( 'Swipe', 'elegant-elements' ),
						'diagonal-swipe'     => esc_attr__( 'Diagonal Swipe', 'elegant-elements' ),
						'double-swipe'       => esc_attr__( 'Double Swipe', 'elegant-elements' ),
						'diagonal-close'     => esc_attr__( 'Diagonal Close', 'elegant-elements' ),
						'zoning-in'          => esc_attr__( 'Zoning In', 'elegant-elements' ),
						'corners'            => esc_attr__( '4 Corners', 'elegant-elements' ),
						'slice'              => esc_attr__( 'Slice', 'elegant-elements' ),
						'position-aware'     => esc_attr__( 'Position Aware', 'elegant-elements' ),
						'alternate'          => esc_attr__( 'Alternate', 'elegant-elements' ),
						'smoosh'             => esc_attr__( 'Smoosh', 'elegant-elements' ),
						'vertical-overlap'   => esc_attr__( 'Vertical Overlap', 'elegant-elements' ),
						'horizontal-overlap' => esc_attr__( 'Horizontal Overlap', 'elegant-elements' ),
						'collision'          => esc_attr__( 'Collision', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_attr__( 'Icon', 'elegant-elements' ),
					'param_name'  => 'button_icon',
					'value'       => '',
					'description' => esc_attr__( 'Choose icon to display in button.', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Icon Position', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the icon position.', 'elegant-elements' ),
					'param_name'  => 'icon_position',
					'default'     => 'left',
					'value'       => array(
						'left'  => esc_attr__( 'Left', 'elegant-elements' ),
						'right' => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'dependency'  => array(
						array(
							'element'  => 'button_icon',
							'value'    => '',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'On Click Action', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the action to perfom on button click.', 'elegant-elements' ),
					'param_name'  => 'action',
					'value'       => array(
						'custom_link'    => esc_attr__( 'Open Custom Link', 'elegant-elements' ),
						'image_lightbox' => esc_attr__( 'Open Image in Lightbox', 'elegant-elements' ),
						'video_lightbox' => esc_attr__( 'Open Video in Lightbox', 'elegant-elements' ),
						'modal'          => esc_attr__( 'Open Modal Dialog', 'elegant-elements' ),
						'image_block'    => esc_attr__( 'Display Image in Custom Block', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Image Block Class Name', 'elegant-elements' ),
					'param_name'  => 'image_block_class_name',
					'value'       => '',
					'description' => esc_attr__( 'Enter the css class name of the HTML div where you want to display the image on button click.', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'action',
							'value'    => 'image_block',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'link_selector',
					'heading'     => esc_attr__( 'Custom Link', 'elegant-elements' ),
					'param_name'  => 'custom_link',
					'value'       => '',
					'description' => esc_attr__( 'Enter or select the link you want to open on button click.', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'action',
							'value'    => 'custom_link',
							'operator' => '==',
						),
					),
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
							'element'  => 'action',
							'value'    => 'custom_link',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'upload',
					'heading'     => esc_attr__( 'Lightbox or Image Block Image', 'elegant-elements' ),
					'param_name'  => 'lightbox_image_url',
					'value'       => '',
					'description' => esc_attr__( 'Upload or select the image from library that you want to open in lightbox or display in the custom HTML block on button click.', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'action',
							'value'    => 'custom_link',
							'operator' => '!=',
						),
						array(
							'element'  => 'action',
							'value'    => 'modal',
							'operator' => '!=',
						),
						array(
							'element'  => 'action',
							'value'    => 'video_lightbox',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Video URL', 'elegant-elements' ),
					'param_name'  => 'lightbox_video_url',
					'value'       => '',
					'description' => esc_attr__( 'Enter video url of YouTube or Vimeo that you want to open in lightbox on button click.', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'action',
							'value'    => 'video_lightbox',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Modal Name', 'elegant-elements' ),
					'param_name'  => 'modal_name',
					'value'       => '',
					'description' => esc_attr__( 'Enter the name of the modal dialog you want to open on button click.', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'action',
							'value'    => 'modal',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => __( 'Button Text & Border Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the button text and border color.', 'elegant-elements' ),
					'param_name'  => 'color',
					'value'       => '#036e25',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => __( 'Button Text Hover Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the button text color on hover.', 'elegant-elements' ),
					'param_name'  => 'color_hover',
					'value'       => '#ffffff',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Button Background on Hover', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the button background color on hover animation.', 'elegant-elements' ),
					'param_name'  => 'background',
					'value'       => '#036e25',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Button Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the button size.', 'elegant-elements' ),
					'param_name'  => 'size',
					'default'     => 'medium',
					'value'       => array(
						'small'  => esc_attr__( 'Small', 'elegant-elements' ),
						'medium' => esc_attr__( 'Medium', 'elegant-elements' ),
						'large'  => esc_attr__( 'Large', 'elegant-elements' ),
						'xlarge' => esc_attr__( 'X-Large', 'elegant-elements' ),
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Button Shape', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the button shape.', 'elegant-elements' ),
					'param_name'  => 'shape',
					'default'     => 'square',
					'value'       => array(
						'square' => esc_attr__( 'Square', 'elegant-elements' ),
						'pill'   => esc_attr__( 'Pill', 'elegant-elements' ),
						'round'  => esc_attr__( 'Round', 'elegant-elements' ),
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'dimension',
					'heading'     => esc_attr__( 'Button Margin', 'elegant-elements' ),
					'param_name'  => 'margin',
					'value'       => '',
					'description' => esc_attr__( 'Enter margin to add space around the button. In Pixels (px) eg. 10px.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Button alignment', 'elegant-elements' ),
					'param_name'  => 'alignment',
					'default'     => 'left',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'center' => esc_attr__( 'Center', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Align the button to left, right or center.', 'elegant-elements' ),
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
					'heading'     => esc_attr__( 'Button Title Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select typography for the button title text.', 'elegant-elements' ),
					'param_name'  => 'typography_button_title',
					'value'       => '',
					'default'     => $default_typography['title'],
					'dependency'  => array(
						array(
							'element'  => 'element_typography',
							'value'    => 'default',
							'operator' => '!=',
						),
					),
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Title Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for button title text. In Pixel (px).', 'elegant-elements' ),
					'param_name'  => 'title_font_size',
					'value'       => '18',
					'min'         => '12',
					'max'         => '75',
					'step'        => '1',
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_fancy_button', 99 );
