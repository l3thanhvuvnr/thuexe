<?php
if ( fusion_is_element_enabled( 'iee_content_toggle' ) && ! class_exists( 'IEE_Content_Toggle' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 2.1.0
	 */
	class IEE_Content_Toggle extends Fusion_Element {

		/**
		 * Toggle counter.
		 *
		 * @since 2.1.0
		 * @access private
		 * @var object
		 */
		private $toggle_counter = 1;

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 2.1.0
		 * @var array
		 */
		protected $args;

		/**
		 * Constructor.
		 *
		 * @since 2.1.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-content-toggle', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_content-toggle-first', array( $this, 'attr_first_toggle' ) );
			add_filter( 'fusion_attr_content-toggle-last', array( $this, 'attr_last_toggle' ) );

			add_shortcode( 'iee_content_toggle', array( $this, 'render' ) );
			add_shortcode( 'elegant_libray_element', array( $this, 'render_library_element' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 2.1.0
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
					'title_first'        => '',
					'content_first'      => '',
					'title_last'         => '',
					'content_last'       => '',
					'switch_bg_inactive' => '',
					'switch_bg_active'   => '',
					'element_typography' => '',
					'typography_title'   => '',
					'title_font_size'    => '',
					'title_color'        => '',
					'typography_heading' => $default_typography['title'],
					'hide_on_mobile'     => '',
					'class'              => '',
					'id'                 => '',
				),
				$args
			);

			$defaults = apply_filters( 'fusion_builder_default_args', $defaults, 'iee_content_toggle', $args );

			$this->args = $args;

			$html = '';

			if ( '' !== locate_template( 'templates/content-toggle/elegant-content-toggle.php' ) ) {
				include locate_template( 'templates/content-toggle/elegant-content-toggle.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/content-toggle/elegant-content-toggle.php';
			}

			$this->toggle_counter++;

			return $html;
		}

		/**
		 * Render the library element content.
		 *
		 * @access public
		 * @since 2.1.0
		 * @param array $args Shortcode paramters.
		 * @return string     HTML output.
		 */
		public function render_library_element( $args ) {
			$post_id               = $args['id'];
			$template_content_post = get_post( $post_id );
			$template_content      = $template_content_post->post_content;

			return do_shortcode( $template_content );
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 2.1.0
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-content-toggle',
			);

			$attr['class'] .= ' elegant-content-toggle-' . $this->toggle_counter;
			$attr['class'] .= ' fusion-clearfix';

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
		 * @since 2.1.0
		 * @return array
		 */
		public function attr_first_toggle() {
			$attr = array(
				'class' => 'content-toggle-first active-content',
			);

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 2.1.0
		 * @return array
		 */
		public function attr_last_toggle() {
			$attr = array(
				'class' => 'content-toggle-last',
			);

			return $attr;
		}

		/**
		 * Builds the styles.
		 *
		 * @access public
		 * @since 2.1.0
		 * @return array
		 */
		public function build_styles() {
			$main_class = '.elegant-content-toggle.elegant-content-toggle-' . $this->toggle_counter;

			$style = '<style type="text/css">';

			if ( isset( $this->args['switch_bg_inactive'] ) && '' !== $this->args['switch_bg_inactive'] ) {
				$style .= $main_class . ' .content-toggle-switch-label {';
				$style .= 'background:' . $this->args['switch_bg_inactive'] . ';';
				$style .= '}';
			}

			if ( isset( $this->args['switch_bg_active'] ) && '' !== $this->args['switch_bg_active'] ) {
				$style .= $main_class . ' .switch-active .content-toggle-switch-label {';
				$style .= 'background:' . $this->args['switch_bg_active'] . ';';
				$style .= '}';
			}

			$style .= $main_class . ' .content-toggle-switch-first,';
			$style .= $main_class . ' .content-toggle-switch-last {';

			if ( isset( $this->args['typography_title'] ) && '' !== $this->args['typography_title'] ) {
				$style .= elegant_get_typography_css( $this->args['typography_title'] );
			}

			if ( isset( $this->args['title_color'] ) && '' !== $this->args['title_color'] ) {
				$style .= 'color:' . $this->args['title_color'] . ';';
			}

			if ( isset( $this->args['title_font_size'] ) && '' !== $this->args['title_font_size'] ) {
				$style .= 'font-size:' . FusionBuilder::validate_shortcode_attr_value( $this->args['title_font_size'], 'px' ) . ';';
			}

			$style .= '}';

			$style .= '</style>';

			return $style;
		}

		/**
		 * Sets the necessary scripts.
		 *
		 * @access public
		 * @since 2.1.0
		 * @return void
		 */
		public function add_scripts() {
			global $elegant_js_folder_url, $elegant_js_folder_path;

			Fusion_Dynamic_JS::enqueue_script(
				'infi-elegant-content-toggle',
				$elegant_js_folder_url . '/infi-elegant-content-toggle.min.js',
				$elegant_js_folder_path . '/infi-elegant-content-toggle.min.js',
				array( 'jquery' ),
				'1',
				true
			);
		}
	}

	new IEE_Content_Toggle();
} // End if().

/**
 * Map shortcode for content_toggle.
 *
 * @since 2.1.0
 * @return void
 */
function map_elegant_elements_content_toggle() {
	global $fusion_settings;

	// Get default typography for title and description.
	$default_typography = elegant_get_default_typography();

	fusion_builder_map(
		array(
			'name'                      => esc_attr__( 'Elegant Content Toggle', 'elegant-elements' ),
			'shortcode'                 => 'iee_content_toggle',
			'icon'                      => 'fa-toggle-on fas content-toggle-icon',
			'custom_settings_view_name' => 'ModuleSettingElegantView',
			'front-end'                 => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-content-toggle-preview.php',
			'params'                    => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Title', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter the title for the first toggle content.', 'elegant-elements' ),
					'param_name'  => 'title_first',
					'value'       => __( 'Annual', 'elegant-elements' ),
					'group'       => __( 'First Toggle', 'elegant-elements' ),
				),
				array(
					'type'        => 'elegant_select_optgroup',
					'heading'     => esc_attr__( 'Content Template', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the content template from saved elements library to display in the first toggle.', 'elegant-elements' ),
					'param_name'  => 'content_first',
					'value'       => elegant_get_library_collection(),
					'group'       => __( 'First Toggle', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Title', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter the title for the second toggle content.', 'elegant-elements' ),
					'param_name'  => 'title_last',
					'value'       => __( 'Lifetime', 'elegant-elements' ),
					'group'       => __( 'Last Toggle', 'elegant-elements' ),
				),
				array(
					'type'        => 'elegant_select_optgroup',
					'heading'     => esc_attr__( 'Content Template', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the content template from saved elements library to display in the second toggle.', 'elegant-elements' ),
					'param_name'  => 'content_last',
					'value'       => elegant_get_library_collection(),
					'group'       => __( 'Last Toggle', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Inactive Switch Color', 'elegant-elements' ),
					'param_name'  => 'switch_bg_inactive',
					'value'       => '#807e7e',
					'description' => esc_attr__( 'Controls the background color of inactive switch button.', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Active Switch Color', 'elegant-elements' ),
					'param_name'  => 'switch_bg_active',
					'value'       => '#4CAF50',
					'description' => esc_attr__( 'Controls the background color of inactive switch button.', 'elegant-elements' ),
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
					'description' => esc_attr__( 'Select typography for the toggle title text.', 'elegant-elements' ),
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
					'description' => esc_attr__( 'Select the font size for toggle title text. ( In Pixel. )', 'elegant-elements' ),
					'param_name'  => 'title_font_size',
					'value'       => '18',
					'min'         => '10',
					'max'         => '100',
					'step'        => '1',
					'group'       => 'Typography',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Toggle Title Color', 'elegant-elements' ),
					'param_name'  => 'title_color',
					'value'       => '#333333',
					'description' => esc_attr__( 'Controls the text color for toggle title.', 'elegant-elements' ),
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_content_toggle', 999 );
