<?php
if ( fusion_is_element_enabled( 'iee_cards' ) && ! class_exists( 'IEE_Cards' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class IEE_Cards extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $args;

		/**
		 * The shortcode content.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $content;

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-cards', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_elegant-cards-image-wrapper', array( $this, 'image_attr' ) );
			add_filter( 'fusion_attr_elegant-cards-description-wrapper', array( $this, 'description_wrapper_attr' ) );
			add_filter( 'fusion_attr_elegant-cards-title', array( $this, 'title_attr' ) );
			add_filter( 'fusion_attr_elegant-cards-description', array( $this, 'description_attr' ) );

			add_shortcode( 'iee_cards', array( $this, 'render' ) );

			// add_filter( 'fusion_builder_map_descriptions', array( $this, 'fusion_builder_map_descriptions' ) ); TODO: remove this line before release.
		}

		/**
		 * Returns equivalent global information for FB param.
		 *
		 * @since 1.2.0
		 * @param array $shortcode_option_map Element option data.
		 * @return array|bool       Element option data.
		 */
		public function fusion_builder_map_descriptions( $shortcode_option_map = array() ) {

			$shortcode_option_map['background_color']['iee_cards'] = array(
				'theme-option' => 'elegant_card_background_color',
				'reset'        => true,
			);

			$shortcode_option_map['border_color']['iee_cards'] = array(
				'theme-option' => 'elegant_card_border_color',
				'reset'        => true,
			);

			return $shortcode_option_map;
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

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'alignment'                  => 'left',
					'element_typography'         => 'custom',
					'heading_size'               => 'h2',
					'title'                      => '',
					'typography_title'           => $default_typography['title'],
					'title_font_size'            => '28',
					'title_color'                => '',
					'description'                => '',
					'typography_description'     => $default_typography['description'],
					'description_font_size'      => '18',
					'description_color'          => '',
					'hide_on_mobile'             => fusion_builder_default_visibility( 'string' ),
					'class'                      => '',
					'id'                         => '',
					'background_color'           => ( '' !== $fusion_settings->get( 'elegant_card_background_color' ) ) ? $fusion_settings->get( 'elegant_card_background_color' ) : '#ffffff',
					'border_color'               => ( '' !== $fusion_settings->get( 'elegant_card_border_color' ) ) ? $fusion_settings->get( 'elegant_card_border_color' ) : '#dddddd',
					'border_radius_top_left'     => '',
					'border_radius_top_right'    => '',
					'border_radius_bottom_right' => '',
					'border_radius_bottom_left'  => '',
					'disable_hover_effect'       => 'no',
					'enable_box_shadow'          => 'no',
					'link_type'                  => 'button',
					'link_url'                   => '',
					'target'                     => '',
					'image'                      => '',
				),
				$args
			);

			$defaults = apply_filters( 'fusion_builder_default_args', $defaults, 'iee_cards', $args );

			if ( 'default' === $defaults['element_typography'] ) {
				$defaults['typography_title']       = $default_typography['title'];
				$defaults['typography_description'] = $default_typography['description'];
			}

			$this->args = $defaults;

			if ( 'button' === $this->args['link_type'] ) {
				$this->content = $content;
			} else {
				$content       = '';
				$this->content = '';
			}

			$html = '';

			if ( '' !== locate_template( 'templates/cards/elegant-cards.php' ) ) {
				include locate_template( 'templates/cards/elegant-cards.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/cards/elegant-cards.php';
			}

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
				'class' => 'elegant-cards',
				'style' => '',
			);

			if ( 'yes' === $this->args['disable_hover_effect'] ) {
				$attr['class'] .= ' no-hover-effect';
			}

			if ( 'yes' === $this->args['enable_box_shadow'] ) {
				$attr['class'] .= ' elegant-card-shadow';
			}

			$attr = fusion_builder_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$border_radius_top_left     = isset( $this->args['border_radius_top_left'] ) ? fusion_library()->sanitize->get_value_with_unit( $this->args['border_radius_top_left'] ) : '0px';
			$border_radius_top_right    = isset( $this->args['border_radius_top_right'] ) ? fusion_library()->sanitize->get_value_with_unit( $this->args['border_radius_top_right'] ) : '0px';
			$border_radius_bottom_right = isset( $this->args['border_radius_bottom_right'] ) ? fusion_library()->sanitize->get_value_with_unit( $this->args['border_radius_bottom_right'] ) : '0px';
			$border_radius_bottom_left  = isset( $this->args['border_radius_bottom_left'] ) ? fusion_library()->sanitize->get_value_with_unit( $this->args['border_radius_bottom_left'] ) : '0px';
			$border_radius              = $border_radius_top_left . ' ' . $border_radius_top_right . ' ' . $border_radius_bottom_right . ' ' . $border_radius_bottom_left;
			$border_radius              = ( '0px 0px 0px 0px' === $border_radius ) ? '' : $border_radius;

			if ( '' !== $border_radius ) {
				$attr['style'] .= 'border-radius:' . esc_attr( $border_radius ) . ';';
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
		 * Builds the attributes array for title.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function image_attr() {
			$attr = array(
				'class' => 'elegant-cards-image-wrapper',
				'style' => '',
			);

			return $attr;
		}

		/**
		 * Builds the attributes array for title.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function description_wrapper_attr() {
			$attr = array(
				'class' => 'elegant-cards-description-wrapper elegant-align-' . $this->args['alignment'],
				'style' => '',
			);

			if ( isset( $this->args['background_color'] ) && '' !== $this->args['background_color'] ) {
				$attr['style'] .= 'background-color:' . $this->args['background_color'] . ';';
			}

			if ( isset( $this->args['border_color'] ) && '' !== $this->args['border_color'] ) {
				$attr['style'] .= 'border-color:' . $this->args['border_color'] . ';';
			}

			if ( ! isset( $this->args['image'] ) || '' == $this->args['image'] ) {
				$attr['style'] .= 'border-top-style: solid;';
				$attr['style'] .= 'border-top-width: 1px;';
			}

			if ( '' == $this->content ) {
				$attr['style'] .= 'padding-bottom: 5px;';
			}

			return $attr;
		}

		/**
		 * Builds the attributes array for title.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function title_attr() {
			$attr = array(
				'class' => 'elegant-cards-title',
				'style' => '',
			);

			if ( isset( $this->args['typography_title'] ) && '' !== $this->args['typography_title'] ) {
				$typography       = $this->args['typography_title'];
				$title_typography = elegant_get_typography_css( $typography );

				$attr['style'] .= $title_typography;
			}

			if ( isset( $this->args['title_font_size'] ) && '' !== $this->args['title_font_size'] ) {
				$attr['style'] .= 'font-size:' . FusionBuilder::validate_shortcode_attr_value( $this->args['title_font_size'], 'px' ) . ';';
			}

			if ( isset( $this->args['title_color'] ) && '' !== $this->args['title_color'] ) {
				$attr['style'] .= 'color:' . $this->args['title_color'] . ';';
			}

			return $attr;
		}

		/**
		 * Builds the attributes array for description.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function description_attr() {
			$attr = array(
				'class' => 'elegant-cards-description',
				'style' => '',
			);

			if ( isset( $this->args['typography_description'] ) && '' !== $this->args['typography_description'] ) {
				$typography             = $this->args['typography_description'];
				$description_typography = elegant_get_typography_css( $typography );

				$attr['style'] .= $description_typography;
			}

			if ( isset( $this->args['description_font_size'] ) && '' !== $this->args['description_font_size'] ) {
				$attr['style'] .= 'font-size:' . FusionBuilder::validate_shortcode_attr_value( $this->args['description_font_size'], 'px' ) . ';';
			}

			if ( isset( $this->args['description_color'] ) && '' !== $this->args['description_color'] ) {
				$attr['style'] .= 'color:' . $this->args['description_color'] . ';';
			}

			return $attr;
		}
	}

	new IEE_Cards();
} // End if().


/**
 * Map shortcode for cards.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_cards() {
	global $fusion_settings;

	// Get default typography for title and description.
	$default_typography = elegant_get_default_typography();

	fusion_builder_map(
		array(
			'name'                      => esc_attr__( 'Elegant Cards', 'elegant-elements' ),
			'shortcode'                 => 'iee_cards',
			'icon'                      => 'icon-cards',
			'preview'                   => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/previews/infi-cards-preview.php',
			'preview_id'                => 'elegant-elements-module-infi-cards-preview-template',
			'custom_settings_view_name' => 'ModuleSettingElegantView',
			'front-end'                 => ELEGANT_ELEMENTS_PLUGIN_DIR . '/elements/front-end/templates/infi-cards-preview.php',
			'inline_editor'             => true,
			'inline_editor_shortcodes'  => true,
			'params'                    => array(
				array(
					'type'         => 'upload',
					'heading'      => esc_attr__( 'Upload Image', 'elegant-elements' ),
					'description'  => esc_attr__( 'Upload the image to be used in card header.', 'elegant-elements' ),
					'param_name'   => 'image',
					'dynamic_data' => true,
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_attr__( 'Card Title', 'elegant-elements' ),
					'param_name'   => 'title',
					'value'        => esc_attr__( 'Elegant Image Card', 'elegant-elements' ),
					'placeholder'  => true,
					'dynamic_data' => true,
					'description'  => esc_attr__( 'Enter the text to be used as card title.', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the title size, H1-H6.', 'elegant-elements' ),
					'param_name'  => 'heading_size',
					'value'       => array(
						'h1' => 'H1',
						'h2' => 'H2',
						'h3' => 'H3',
						'h4' => 'H4',
						'h5' => 'H5',
						'h6' => 'H6',
					),
					'default'     => 'h2',
					'dependency'  => array(
						array(
							'element'  => 'title',
							'value'    => '',
							'operator' => '!=',
						),
					),
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
					'group'       => 'Typography',
					'dependency'  => array(
						array(
							'element'  => 'element_typography',
							'value'    => 'default',
							'operator' => '!=',
						),
					),
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
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Title Color', 'elegant-elements' ),
					'param_name'  => 'title_color',
					'value'       => '',
					'description' => esc_attr__( 'Controls the text color for card title.', 'elegant-elements' ),
					'group'       => 'Typography',
				),
				array(
					'type'         => 'textarea',
					'heading'      => esc_attr__( 'Card Description Text', 'elegant-elements' ),
					'param_name'   => 'description',
					'dynamic_data' => true,
					'value'        => esc_attr__( 'Your card description text goes here. You can edit it inline using Frontend Builder', 'elegant-elements' ),
					'placeholder'  => true,
					'description'  => esc_attr__( 'Enter the text to be used as card description text.', 'elegant-elements' ),
				),
				array(
					'type'        => 'elegant_typography',
					'heading'     => esc_attr__( 'Description Text Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select typography for the description text.', 'elegant-elements' ),
					'param_name'  => 'typography_description',
					'value'       => '',
					'default'     => $default_typography['description'],
					'group'       => 'Typography',
					'dependency'  => array(
						array(
							'element'  => 'element_typography',
							'value'    => 'default',
							'operator' => '!=',
						),
					),
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
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Description Color', 'elegant-elements' ),
					'param_name'  => 'description_color',
					'value'       => '',
					'description' => esc_attr__( 'Controls the text color for card description.', 'elegant-elements' ),
					'group'       => 'Typography',
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Card Background Color', 'elegant-elements' ),
					'param_name'  => 'background_color',
					'value'       => '',
					'default'     => '#ffffff', // TODO: $fusion_settings->get( 'elegant_card_background_color' ).
					'description' => esc_attr__( 'Controls the background color applied to the card box.', 'elegant-elements' ),
					'group'       => 'Design',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Card Border Color', 'elegant-elements' ),
					'param_name'  => 'border_color',
					'value'       => '',
					'default'     => '#dddddd', // TODO: $fusion_settings->get( 'elegant_card_border_color' ).
					'description' => esc_attr__( 'Controls the border color applied to the card box.', 'elegant-elements' ),
					'group'       => 'Design',
				),
				array(
					'type'             => 'dimension',
					'remove_from_atts' => true,
					'heading'          => esc_attr__( 'Border Radius', 'elegant-elements' ),
					'description'      => __( 'Enter values including any valid CSS unit, ex: 10px.', 'elegant-elements' ),
					'param_name'       => 'border_radius',
					'value'            => array(
						'border_radius_top_left'     => '',
						'border_radius_top_right'    => '',
						'border_radius_bottom_right' => '',
						'border_radius_bottom_left'  => '',
					),
					'group'            => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Disable Image Hover Effect', 'elegant-elements' ),
					'param_name'  => 'disable_hover_effect',
					'default'     => 'no',
					'value'       => array(
						'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
						'no'  => esc_attr__( 'No', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Select yes if you want to disable the image zoom effect on hover.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Add Box Shadow to Card', 'elegant-elements' ),
					'param_name'  => 'enable_box_shadow',
					'default'     => 'no',
					'value'       => array(
						'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
						'no'  => esc_attr__( 'No', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Select yes if you want to disable the image zoom effect on hover.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Link type', 'elegant-elements' ),
					'param_name'  => 'link_type',
					'default'     => 'button',
					'value'       => array(
						'button' => esc_attr__( 'Button', 'elegant-elements' ),
						'image'  => esc_attr__( 'Image', 'elegant-elements' ),
						'card'   => esc_attr__( 'Card', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Choose how you want to add link to the card. You can add button, link to image or entire card.', 'elegant-elements' ),
				),
				array(
					'type'             => 'textarea',
					'heading'          => esc_attr__( 'Button', 'elegant-elements' ),
					'param_name'       => 'element_content_placeholder',
					'value'            => '',
					'remove_from_atts' => true,
					'description'      => __( 'Click the link to generate button shortcode. Please remove the previous shortcode before generating new one.<br/><p class="elegant-element-shortcode-generate"><a href="#" class="elegant-elements-add-shortcode" data-type="fusion_button" data-editor-clone-id="element_content_placeholder" data-editor-id="element_content">Generate Button Shortcode</a></p>', 'elegant-elements' ),
					'hidden'           => true,
				),
				array(
					'type'        => 'textarea',
					'heading'     => esc_attr__( 'Button', 'elegant-elements' ),
					'param_name'  => 'element_content',
					'value'       => '',
					'dependency'  => array(
						array(
							'element'  => 'link_type',
							'value'    => 'button',
							'operator' => '==',
						),
					),
					'description' => __( 'Click the link to generate button shortcode. Please remove the previous shortcode before generating new one.<br/><p class="elegant-element-shortcode-generate"><a href="#" class="elegant-elements-add-shortcode" data-type="fusion_button" data-editor-clone-id="element_content" data-editor-id="element_content">Generate Button Shortcode</a></p>', 'elegant-elements' ),
				),
				array(
					'type'         => 'link_selector',
					'heading'      => esc_attr__( 'Link URL', 'elegant-elements' ),
					'param_name'   => 'link_url',
					'value'        => '',
					'dynamic_data' => true,
					'dependency'   => array(
						array(
							'element'  => 'link_type',
							'value'    => 'button',
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
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Description alignment', 'elegant-elements' ),
					'param_name'  => 'alignment',
					'default'     => 'left',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'center' => esc_attr__( 'Center', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Align the description text to left, right or center.', 'elegant-elements' ),
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_cards', 99 );
