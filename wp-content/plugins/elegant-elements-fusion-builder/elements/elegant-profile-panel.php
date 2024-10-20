<?php
if ( fusion_is_element_enabled( 'iee_profile_panel' ) && ! class_exists( 'IEE_Profile_Panel' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.1.0
	 */
	class IEE_Profile_Panel extends Fusion_Element {

		/**
		 * The profile panel counter.
		 *
		 * @access private
		 * @since 1.1.0
		 * @var int
		 */
		private $panel_counter = 1;

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.1.0
		 * @var array
		 */
		protected $args;

		/**
		 * The shortcode content.
		 *
		 * @access protected
		 * @since 1.1.0
		 * @var array
		 */
		protected $content;

		/**
		 * Constructor.
		 *
		 * @since 1.1.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-profile-panel', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_elegant-profile-panel-header-image-wrapper', array( $this, 'header_image_attr' ) );
			add_filter( 'fusion_attr_elegant-profile-panel-profile-image-wrapper', array( $this, 'profile_image_wrapper_attr' ) );
			add_filter( 'fusion_attr_elegant-profile-panel-profile-image', array( $this, 'profile_image_attr' ) );
			add_filter( 'fusion_attr_elegant-profile-panel-description-wrapper', array( $this, 'description_wrapper_attr' ) );
			add_filter( 'fusion_attr_elegant-profile-panel-title', array( $this, 'title_attr' ) );
			add_filter( 'fusion_attr_elegant-profile-panel-description', array( $this, 'description_attr' ) );

			add_shortcode( 'iee_profile_panel', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 1.1.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			// Get default typography for title and description.
			$default_typography = elegant_get_default_typography();

			if ( isset( $args['element_typography'] ) && 'default' === $args['element_typography'] ) {
				$args['typography_title']       = $default_typography['title'];
				$args['typography_description'] = $default_typography['description'];
			}

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'header_image'              => '',
					'header_background_color'   => '',
					'profile_header_height'     => '150',
					'profile_image'             => '',
					'profile_background_color'  => '',
					'profile_image_width'       => '100',
					'profile_image_border_type' => 'circle',
					'title'                     => esc_attr__( 'Elegant Profile Panel', 'elegant-elements' ),
					'element_typography'        => 'default',
					'typography_title'          => '',
					'title_font_size'           => '28',
					'title_color'               => '',
					'description'               => esc_attr__( 'Your profile description text goes here. You can edit it using Frontend Builder', 'elegant-elements' ),
					'typography_description'    => '',
					'description_font_size'     => '18',
					'description_color'         => '',
					'panel_background_color'    => '',
					'profile_border_color'      => '',
					'element_content'           => '',
					'alignment'                 => 'center',
					'hide_on_mobile'            => fusion_builder_default_visibility( 'string' ),
					'class'                     => '',
					'id'                        => '',
				),
				$args
			);

			$this->args    = $defaults;
			$this->content = $content;

			$html = '';

			if ( '' !== locate_template( 'templates/profile-panel/elegant-profile-panel.php' ) ) {
				include locate_template( 'templates/profile-panel/elegant-profile-panel.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/profile-panel/elegant-profile-panel.php';
			}

			$this->panel_counter++;

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-profile-panel elegant-profile-panel-' . $this->panel_counter,
				'style' => '',
			);

			$alignment      = ( isset( $this->args['alignment'] ) && '' !== $this->args['alignment'] ) ? $this->args['alignment'] : 'center';
			$attr['class'] .= ' elegant-profile-panel-align-' . $alignment;

			$attr = fusion_builder_visibility_atts( $this->args['hide_on_mobile'], $attr );

			if ( isset( $this->args['panel_background_color'] ) && '' !== $this->args['panel_background_color'] ) {
				$attr['style'] .= 'background-color: ' . $this->args['panel_background_color'] . ';';
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
		 * @since 1.1.0
		 * @return array
		 */
		public function header_image_attr() {
			$attr = array(
				'class' => 'elegant-profile-panel-header-image-wrapper',
				'style' => '',
			);

			if ( isset( $this->args['header_image'] ) && '' !== $this->args['header_image'] ) {
				$attr['style'] .= 'background-image: url( ' . $this->args['header_image'] . ');';
			}

			if ( isset( $this->args['header_background_color'] ) && '' !== $this->args['header_background_color'] ) {
				$attr['style'] .= 'background-color: ' . $this->args['header_background_color'] . ';';
			}

			if ( isset( $this->args['profile_header_height'] ) && '' !== $this->args['profile_header_height'] ) {
				$attr['style'] .= 'height: ' . FusionBuilder::validate_shortcode_attr_value( $this->args['profile_header_height'], 'px' ) . ';';
			}

			return $attr;
		}

		/**
		 * Builds the attributes array for title.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return array
		 */
		public function profile_image_wrapper_attr() {
			$attr = array(
				'class' => 'elegant-profile-panel-profile-image-wrapper',
				'style' => '',
			);

			if ( isset( $this->args['profile_image_width'] ) && '' !== $this->args['profile_image_width'] ) {
				$attr['style'] .= 'margin-top: -' . FusionBuilder::validate_shortcode_attr_value( ( $this->args['profile_image_width'] / 2 ), 'px' ) . ';';
			}

			return $attr;
		}

		/**
		 * Builds the attributes array for title.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return array
		 */
		public function profile_image_attr() {
			$attr = array(
				'class' => 'elegant-profile-panel-profile-image',
				'style' => '',
			);

			if ( isset( $this->args['profile_image_border_type'] ) && '' !== $this->args['profile_image_border_type'] ) {
				$attr['class'] .= ' elegant-profile-panel-image-' . $this->args['profile_image_border_type'];
			}

			if ( isset( $this->args['profile_image_width'] ) && '' !== $this->args['profile_image_width'] ) {
				$attr['style'] .= 'max-width: ' . FusionBuilder::validate_shortcode_attr_value( $this->args['profile_image_width'], 'px' ) . ';';
			}

			if ( isset( $this->args['profile_background_color'] ) && '' !== $this->args['profile_background_color'] ) {
				$attr['style'] .= 'background-color: ' . $this->args['profile_background_color'] . ';';
			}

			if ( isset( $this->args['profile_border_color'] ) && '' !== $this->args['profile_border_color'] ) {
				$attr['style'] .= 'border-color: ' . $this->args['profile_border_color'] . ';';
			}

			return $attr;
		}

		/**
		 * Builds the attributes array for title.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return array
		 */
		public function description_wrapper_attr() {
			$attr = array(
				'class' => 'elegant-profile-panel-description-wrapper',
				'style' => '',
			);

			return $attr;
		}

		/**
		 * Builds the attributes array for title.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return array
		 */
		public function title_attr() {
			$attr = array(
				'class' => 'elegant-profile-panel-title',
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
		 * @since 1.1.0
		 * @return array
		 */
		public function description_attr() {
			$attr = array(
				'class' => 'elegant-profile-panel-description',
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

	new IEE_Profile_Panel();
} // End if().


/**
 * Map shortcode for profile panel.
 *
 * @since 1.1.0
 * @return void
 */
function map_elegant_elements_profile_panel() {
	global $fusion_settings;

	// Get default typography for title and description.
	$default_typography = elegant_get_default_typography();

	fusion_builder_map(
		array(
			'name'                      => esc_attr__( 'Elegant Profile Panel', 'elegant-elements' ),
			'shortcode'                 => 'iee_profile_panel',
			'icon'                      => 'icon-profile-panel',
			'preview'                   => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/previews/infi-profile-panel-preview.php',
			'preview_id'                => 'elegant-elements-module-infi-profile-panel-preview-template',
			'custom_settings_view_name' => 'ModuleSettingElegantView',
			'front-end'                 => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-profile-panel-preview.php',
			'inline_editor'             => true,
			'inline_editor_shortcodes'  => true,
			'params'                    => array(
				array(
					'type'         => 'upload',
					'heading'      => esc_attr__( 'Upload Header Image', 'elegant-elements' ),
					'description'  => esc_attr__( 'Upload the image to be used in profile panel header.', 'elegant-elements' ),
					'param_name'   => 'header_image',
					'dynamic_data' => true,
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Profile Header Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the background color for profile panel header. If image is selected, background color will not be applied.', 'elegant-elements' ),
					'param_name'  => 'header_background_color',
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Profile Header Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css height to be applied to the profile header. ( In Pixel. )', 'elegant-elements' ),
					'param_name'  => 'profile_header_height',
					'value'       => '150',
					'min'         => '100',
					'max'         => '1000',
					'step'        => '10',
				),
				array(
					'type'         => 'upload',
					'heading'      => esc_attr__( 'Upload Profile Image', 'elegant-elements' ),
					'description'  => esc_attr__( 'Upload the image to be used in profile image.', 'elegant-elements' ),
					'param_name'   => 'profile_image',
					'dynamic_data' => true,
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Profile Image Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the background color for profile image. If image is selected, background color will not be applied.', 'elegant-elements' ),
					'param_name'  => 'profile_background_color',
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Profile Image Width', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css width to be applied to the profile image. ( In Pixel. )', 'elegant-elements' ),
					'param_name'  => 'profile_image_width',
					'value'       => '100',
					'min'         => '10',
					'max'         => '1000',
					'step'        => '1',
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Profile Image Border Type', 'elegant-elements' ),
					'param_name'  => 'profile_image_border_type',
					'default'     => 'circle',
					'value'       => array(
						'circle' => esc_attr__( 'Circle', 'elegant-elements' ),
						'square' => esc_attr__( 'Square', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Choose if you want to apply circle on profile image or keep it square.', 'elegant-elements' ),
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_attr__( 'Profile Title', 'elegant-elements' ),
					'param_name'   => 'title',
					'value'        => esc_attr__( 'Elegant Profile Panel', 'elegant-elements' ),
					'placeholder'  => true,
					'dynamic_data' => true,
					'description'  => esc_attr__( 'Enter the text to be used as profile title.', 'elegant-elements' ),
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
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Title Color', 'elegant-elements' ),
					'param_name'  => 'title_color',
					'value'       => '',
					'description' => esc_attr__( 'Controls the text color for profile title.', 'elegant-elements' ),
					'group'       => 'Typography',
				),
				array(
					'type'         => 'textarea',
					'heading'      => esc_attr__( 'Profile Description Text', 'elegant-elements' ),
					'param_name'   => 'description',
					'value'        => esc_attr__( 'Your profile description text goes here. You can edit it inline using Frontend Builder', 'elegant-elements' ),
					'placeholder'  => true,
					'dynamic_data' => true,
					'description'  => esc_attr__( 'Enter the text to be used as profile description text.', 'elegant-elements' ),
				),
				array(
					'type'        => 'elegant_typography',
					'heading'     => esc_attr__( 'Description Text Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select typography for the description text.', 'elegant-elements' ),
					'param_name'  => 'typography_description',
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
					'description' => esc_attr__( 'Controls the text color for profile description.', 'elegant-elements' ),
					'group'       => 'Typography',
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Profile Panel Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the background color for the title and description area.', 'elegant-elements' ),
					'param_name'  => 'panel_background_color',
					'value'       => '#ffffff',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Profile Image Border Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border color for the profile image.', 'elegant-elements' ),
					'param_name'  => 'profile_border_color',
					'value'       => '#ffffff',
				),
				array(
					'type'        => 'textarea',
					'heading'     => esc_attr__( 'Button', 'elegant-elements' ),
					'param_name'  => 'element_content',
					'value'       => '',
					'description' => __( 'Click the link to generate button shortcode. Please remove the previous shortcode before generating new one.<br/><p><a href="#" class="elegant-elements-add-shortcode" data-type="fusion_button"  data-editor-clone-id="element_content" data-editor-id="element_content">Generate Button Shortcode</a></p>', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Alignment', 'elegant-elements' ),
					'param_name'  => 'alignment',
					'default'     => 'center',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'center' => esc_attr__( 'Center', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'description' => __( 'Set the alignment for profile image and content.', 'elegant-elements' ),
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_profile_panel', 99 );
