<?php
if ( fusion_is_element_enabled( 'iee_notification_box' ) && ! class_exists( 'IEE_Notification_Box' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class IEE_Notification_Box extends Fusion_Element {

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

			add_filter( 'fusion_attr_elegant-notification-box', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_elegant-notification-content', array( $this, 'content_attr' ) );
			add_filter( 'fusion_attr_elegant-notification-title', array( $this, 'title_attr' ) );

			add_shortcode( 'iee_notification_box', array( $this, 'render' ) );
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
				$args['typography_notification_title']   = $default_typography['title'];
				$args['typography_notification_content'] = $default_typography['description'];
			}

			$this->args = $args;

			$html = '';

			if ( '' !== locate_template( 'templates/notification-box/elegant-notification-box.php' ) ) {
				include locate_template( 'templates/notification-box/elegant-notification-box.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/notification-box/elegant-notification-box.php';
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
			global $fusion_library;

			$attr = array(
				'class' => 'elegant-notification-box',
				'style' => '',
			);

			$attr['class'] .= ' elegant-notification-type-' . $this->args['type'];
			$attr['class'] .= ' elegant-notification-color-type-' . $this->args['color_type'];

			$attr = fusion_builder_visibility_atts( $this->args['hide_on_mobile'], $attr );

			if ( 'custom' === $this->args['color_type'] && isset( $this->args['custom_color_background'] ) && '' !== $this->args['custom_color_background'] ) {
				$color          = Fusion_Color::new_color( $this->args['custom_color_background'] );
				$color->alpha   = 0.20;
				$darken_color   = $color->getNew( 'brightness', 0.20 );
				$darken_color   = $fusion_library->sanitize->color( $fusion_library->sanitize->color( $darken_color->color ) );
				$attr['style'] .= 'border-color:' . $darken_color . ';';

				$attr['style'] .= 'background:' . $this->args['custom_color_background'] . ';';
			}

			if ( 'custom' === $this->args['color_type'] && $this->args['class'] ) {
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
		public function title_attr() {
			global $fusion_library;

			$attr = array(
				'class' => 'elegant-notification-title',
				'style' => '',
			);

			if ( 'custom' === $this->args['color_type'] && isset( $this->args['custom_color_title'] ) && '' !== $this->args['custom_color_title'] ) {
				$attr['style'] .= 'color:' . $this->args['custom_color_title'] . ';';
			}

			if ( 'custom' === $this->args['color_type'] && isset( $this->args['custom_color_background'] ) && '' !== $this->args['custom_color_background'] ) {
				$color          = Fusion_Color::new_color( $this->args['custom_color_background'] );
				$color->alpha   = 0.20;
				$darken_color   = $color->getNew( 'brightness', 0.20 );
				$darken_color   = $fusion_library->sanitize->color( $fusion_library->sanitize->color( $darken_color->color ) );
				$attr['style'] .= 'background:' . $darken_color . ';';

				if ( 'modern' === $this->args['type'] ) {
					$attr['style'] .= 'border-color:' . $darken_color . ';';
				}
			}

			if ( isset( $this->args['typography_notification_title'] ) && '' !== $this->args['typography_notification_title'] ) {
				$typography                    = $this->args['typography_notification_title'];
				$notification_title_typography = elegant_get_typography_css( $typography );

				$attr['style'] .= $notification_title_typography;
			}

			if ( isset( $this->args['title_font_size'] ) && '' !== $this->args['title_font_size'] ) {
				$attr['style'] .= 'font-size:' . FusionBuilder::validate_shortcode_attr_value( $this->args['title_font_size'], 'px' ) . ';';
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
		public function content_attr() {
			global $fusion_library;

			$attr = array(
				'class' => 'elegant-notification-content',
				'style' => '',
			);

			if ( 'custom' === $this->args['color_type'] && isset( $this->args['custom_color_background'] ) && '' !== $this->args['custom_color_background'] && 'classic' === $this->args['type'] ) {
				$color          = Fusion_Color::new_color( $this->args['custom_color_background'] );
				$color->alpha   = 0.20;
				$darken_color   = $color->getNew( 'brightness', 0.20 );
				$darken_color   = $fusion_library->sanitize->color( $fusion_library->sanitize->color( $darken_color->color ) );
				$attr['style'] .= 'border-color:' . $darken_color . ';';
			}

			if ( 'custom' === $this->args['color_type'] && isset( $this->args['custom_color_content'] ) && '' !== $this->args['custom_color_content'] ) {
				$attr['style'] .= 'color:' . $this->args['custom_color_content'] . ';';
			}

			if ( isset( $this->args['typography_notification_content'] ) && '' !== $this->args['typography_notification_content'] ) {
				$typography                      = $this->args['typography_notification_content'];
				$notification_content_typography = elegant_get_typography_css( $typography );

				$attr['style'] .= $notification_content_typography;
			}

			if ( isset( $this->args['content_font_size'] ) && '' !== $this->args['content_font_size'] ) {
				$attr['style'] .= 'font-size:' . FusionBuilder::validate_shortcode_attr_value( $this->args['content_font_size'], 'px' ) . ';';
				$attr['style'] .= 'line-height: 1em;';
			}

			return $attr;
		}
	}

	new IEE_Notification_Box();
} // End if().


/**
 * Map shortcode for notification_box.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_notification_box() {
	global $fusion_settings;

	// Get default typography for title and description.
	$default_typography = elegant_get_default_typography();

	fusion_builder_map(
		array(
			'name'                      => esc_attr__( 'Elegant Notification Box', 'elegant-elements' ),
			'shortcode'                 => 'iee_notification_box',
			'icon'                      => 'fa fa-exclamation',
			'preview'                   => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/previews/infi-notification-box-preview.php',
			'preview_id'                => 'elegant-elements-module-infi-notification-box-preview-template',
			'front-end'                 => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-notification-box-preview.php',
			'custom_settings_view_name' => 'ModuleSettingElegantView',
			'params'                    => array(
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Notification Type', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the style type for notification box.', 'elegant-elements' ),
					'param_name'  => 'type',
					'default'     => 'classic',
					'value'       => array(
						'classic' => __( 'Classic', 'elegant-elements' ),
						'modern'  => __( 'Modern', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Title', 'elegant-elements' ),
					'param_name'  => 'title',
					'value'       => esc_attr__( 'Info', 'elegnat-elements' ),
					'placeholder' => true,
					'description' => esc_attr__( 'Controls the title for this notification box.', 'elegant-elements' ),
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_attr__( 'Icon', 'elegant-elements' ),
					'param_name'  => 'icon',
					'value'       => 'fa-info',
					'description' => esc_attr__( 'Controls the title for this notification box.', 'elegant-elements' ),
				),
				array(
					'type'        => 'tinymce',
					'heading'     => esc_attr__( 'Content', 'elegant-elements' ),
					'param_name'  => 'element_content',
					'value'       => esc_attr__( 'Your content goes here.', 'elegnat-elements' ),
					'placeholder' => true,
					'description' => esc_attr__( 'Controls the title for this notification box.', 'elegant-elements' ),
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Notification Color', 'elegant-elements' ),
					'param_name'  => 'color_type',
					'default'     => 'general',
					'value'       => array(
						'general' => __( 'General', 'elegant-elements' ),
						'success' => __( 'Success', 'elegant-elements' ),
						'error'   => __( 'Error', 'elegant-elements' ),
						'warning' => __( 'Warning', 'elegant-elements' ),
						'info'    => __( 'Info', 'elegant-elements' ),
						'custom'  => __( 'Custom Color', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Select the color template from pre-defined colors or choose custom color.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Background Color', 'elegant-elements' ),
					'param_name'  => 'custom_color_background',
					'value'       => '',
					'dependency'  => array(
						array(
							'element'  => 'color_type',
							'value'    => 'custom',
							'operator' => '==',
						),
					),
					'description' => esc_attr__( 'Select the background color to be applied for this notification box.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Title Text Color', 'elegant-elements' ),
					'param_name'  => 'custom_color_title',
					'value'       => '',
					'dependency'  => array(
						array(
							'element'  => 'color_type',
							'value'    => 'custom',
							'operator' => '==',
						),
					),
					'description' => esc_attr__( 'Select the notification text color for this notification box.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Content Color', 'elegant-elements' ),
					'param_name'  => 'custom_color_content',
					'value'       => '',
					'dependency'  => array(
						array(
							'element'  => 'color_type',
							'value'    => 'custom',
							'operator' => '==',
						),
					),
					'description' => esc_attr__( 'Select the content text color for this notification box.', 'elegant-elements' ),
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
					'description' => esc_attr__( 'Select typography for the notification title text.', 'elegant-elements' ),
					'param_name'  => 'typography_notification_title',
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
					'description' => esc_attr__( 'Select the font size for notification title text. In Pixel (px).', 'elegant-elements' ),
					'param_name'  => 'title_font_size',
					'value'       => '18',
					'min'         => '12',
					'max'         => '75',
					'step'        => '1',
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
				),
				array(
					'type'        => 'elegant_typography',
					'heading'     => esc_attr__( 'Content Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select typography for the notification content.', 'elegant-elements' ),
					'param_name'  => 'typography_notification_content',
					'value'       => '',
					'default'     => $default_typography['description'],
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
					'heading'     => esc_attr__( 'Content Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for notification content. Only applies to paragraph text. In Pixel (px).', 'elegant-elements' ),
					'param_name'  => 'content_font_size',
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_notification_box', 99 );
