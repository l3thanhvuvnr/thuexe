<?php
if ( fusion_is_element_enabled( 'iee_rotating_text' ) && ! class_exists( 'IEE_Rotating_Text' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class IEE_Rotating_Text extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $args;

		/**
		 * Rotating Text counter.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $rotating_text_counter = 1;

		/**
		 * Rotating Text.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $rotating_text = array();

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-rotating-text-container', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_elegant-rotating-text', array( $this, 'text_attr' ) );
			add_filter( 'fusion_attr_elegant-rotating-text-wrap', array( $this, 'text_wrap_attr' ) );

			add_shortcode( 'iee_rotating_text', array( $this, 'render_parent' ) );
			add_shortcode( 'iee_rotating_text_child', array( $this, 'render_child' ) );
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

			// Get default typography for title and description.
			$default_typography = elegant_get_default_typography();

			if ( isset( $args['element_typography'] ) && 'default' === $args['element_typography'] ) {
				$args['typography_parent'] = $default_typography['title'];
				$args['typography_child']  = $default_typography['title'];
			}

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'prefix'             => '',
					'element_typography' => '',
					'typography_parent'  => '',
					'typography_child'   => '',
					'font_size'          => '',
					'delay'              => '',
					'title_color'        => '',
					'center_align'       => '',
					'hide_on_mobile'     => '',
					'class'              => '',
					'id'                 => '',
				),
				$args
			);

			$args = $defaults;

			$this->args = $args;

			$html = '';

			if ( '' !== locate_template( 'templates/rotating-text/elegant-rotating-text-parent.php' ) ) {
				include locate_template( 'templates/rotating-text/elegant-rotating-text-parent.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/rotating-text/elegant-rotating-text-parent.php';
			}

			$this->rotating_text_counter++;

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
					'title'       => '',
					'title_color' => '',
					'class'       => '',
					'id'          => '',
				),
				$args
			);

			$args = $defaults;

			$child_html = '';

			if ( '' !== locate_template( 'templates/rotating-text/elegant-rotating-text-child.php' ) ) {
				include locate_template( 'templates/rotating-text/elegant-rotating-text-child.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/rotating-text/elegant-rotating-text-child.php';
			}

			return $child_html;
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
				'class' => 'elegant-rotating-text-container',
			);

			$attr['class'] .= ' elegant-rotating-text-container-' . $this->rotating_text_counter;

			if ( isset( $this->args['center_align'] ) && 'no' !== $this->args['center_align'] ) {
				$attr['class'] .= ' elegant-align-center';
			}

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
		 * @since 1.0
		 * @return array
		 */
		public function text_attr() {
			$attr = array(
				'class' => 'elegant-rotating-text',
				'style' => '',
			);

			$delay = ( isset( $this->args['delay'] ) ) ? $this->args['delay'] : 2;

			if ( isset( $this->args['typography_parent'] ) && '' !== $this->args['typography_parent'] ) {
				$typography         = $this->args['typography_parent'];
				$parent_font_family = elegant_get_typography_css( $typography );
				$attr['style']     .= $parent_font_family;
			}

			$attr['style']     .= 'font-size:' . $this->args['font_size'] . 'px;';
			$attr['style']     .= 'color:' . $this->args['title_color'] . ';';
			$attr['data-delay'] = $delay * 1000;

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @param array $rotating_text Rotating text child attr.
		 * @return array
		 */
		public function text_wrap_attr( $rotating_text ) {
			$attr = array(
				'class' => 'elegant-rotating-text-wrap',
				'style' => '',
			);

			if ( isset( $this->args['typography_child'] ) && '' !== $this->args['typography_child'] ) {
				$typography        = $this->args['typography_child'];
				$child_font_family = elegant_get_typography_css( $typography );
				$attr['style']    .= $child_font_family;
			}

			if ( isset( $rotating_text['title_color'] ) && '' !== $rotating_text['title_color'] ) {
				$attr['style'] .= ' color:' . $rotating_text['title_color'] . ';';
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
				'infi-elegant-rotating-text',
				$elegant_js_folder_url . '/infi-elegant-rotating-text.min.js',
				$elegant_js_folder_path . '/infi-elegant-rotating-text.min.js',
				array( 'jquery' ),
				'1',
				true
			);
		}
	}

	new IEE_Rotating_Text();
} // End if().


/**
 * Map shortcode for rotating_text.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_rotating_text() {
	global $fusion_settings;

	// Get default typography for title and description.
	$default_typography = elegant_get_default_typography();

	$parent_args = array(
		'name'                      => esc_attr__( 'Elegant Rotating Text', 'elegant-elements' ),
		'shortcode'                 => 'iee_rotating_text',
		'icon'                      => 'fusiona-air',
		'multi'                     => 'multi_element_parent',
		'element_child'             => 'iee_rotating_text_child',
		'preview'                   => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/previews/infi-rotating-text-preview.php',
		'preview_id'                => 'elegant-elements-module-infi-rotating-text-preview-template',
		'custom_settings_view_name' => 'ModuleSettingElegantView',
		'params'                    => array(
			array(
				'type'        => 'tinymce',
				'heading'     => esc_attr__( 'Content', 'elegant-elements' ),
				'description' => esc_attr__( 'Rotating text items.', 'elegant-elements' ),
				'param_name'  => 'element_content',
				'value'       => '[iee_rotating_text_child title="' . esc_attr__( 'Your Content Goes Here', 'elegant-elements' ) . '" /]',
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Prefix Text', 'elegant-elements' ),
				'description' => esc_attr__( 'Enter text to be displayed before the rotating text.', 'elegant-elements' ),
				'param_name'  => 'prefix',
				'placeholder' => true,
				'value'       => esc_attr__( 'Your Content Goes Here', 'elegant-elements' ),
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
			),
			array(
				'type'        => 'elegant_typography',
				'heading'     => esc_attr__( 'Prefix Text Typography', 'elegant-elements' ),
				'description' => esc_attr__( 'Select typography for the text before the rotating text.', 'elegant-elements' ),
				'param_name'  => 'typography_parent',
				'value'       => '',
				'default'     => $default_typography['title'],
				'dependency'  => array(
					array(
						'element'  => 'element_typography',
						'value'    => 'default',
						'operator' => '!=',
					),
				),
			),
			array(
				'type'        => 'elegant_typography',
				'heading'     => esc_attr__( 'Rotating Text Typography', 'elegant-elements' ),
				'description' => esc_attr__( 'Select typography for the rotating text.', 'elegant-elements' ),
				'param_name'  => 'typography_child',
				'value'       => '',
				'default'     => $default_typography['title'],
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
				'heading'     => esc_attr__( 'Font Size', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the font size for text. ( In Pixel. )', 'elegant-elements' ),
				'param_name'  => 'font_size',
				'value'       => '18',
				'min'         => '12',
				'max'         => '100',
				'step'        => '1',
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Rotating Delay', 'elegant-elements' ),
				'description' => esc_attr__( 'Set the delay for changing the text rotation. ( In Seconds. )', 'elegant-elements' ),
				'param_name'  => 'delay',
				'value'       => '2',
				'min'         => '1',
				'max'         => '15',
				'step'        => '1',
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Text Color', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the color for prefix text.', 'elegant-elements' ),
				'param_name'  => 'title_color',
				'value'       => '',
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Center Aligned Text', 'elegant-elements' ),
				'description' => esc_attr__( 'Select if you want to set the text in center.', 'elegant-elements' ),
				'param_name'  => 'center_align',
				'default'     => 'no',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
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
		'name'              => esc_attr__( 'Rotating Text - Child', 'elegant-elements' ),
		'shortcode'         => 'iee_rotating_text_child',
		'hide_from_builder' => true,
		'allow_generator'   => true,
		'tag_name'          => 'p',
		'selectors'         => array(
			'class' => 'elegant-rotating-text-child',
		),
		'params'            => array(
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Text', 'elegant-elements' ),
				'description' => esc_attr__( 'Enter text to be displayed as rotating text.', 'elegant-elements' ),
				'param_name'  => 'title',
				'placeholder' => true,
				'value'       => esc_attr__( 'Your Content Goes Here', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Text Color', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the color for this rotating text.', 'elegant-elements' ),
				'param_name'  => 'title_color',
				'value'       => '',
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
				'IEE_Rotating_Text',
				$parent_args,
				'parent'
			)
		);

		fusion_builder_map(
			fusion_builder_frontend_data(
				'IEE_Rotating_Text',
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_rotating_text', 99 );
