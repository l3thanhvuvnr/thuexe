<?php
if ( fusion_is_element_enabled( 'iee_typewriter_text' ) && ! class_exists( 'IEE_Typewriter_Text' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class IEE_Typewriter_Text extends Fusion_Element {

		/**
		 * Typewriter Text counter.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $typewriter_text_counter = 1;

		/**
		 * Typewriter Text.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $typewriter_text = array();

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_shortcode( 'iee_typewriter_text', array( $this, 'render_parent' ) );
			add_shortcode( 'iee_typewriter_text_child', array( $this, 'render_child' ) );
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
				$args['typography_child']  = $default_typography['description'];
			}

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'element_content'    => '',
					'prefix'             => '',
					'suffix'             => '',
					'loop'               => '',
					'alignment'          => '',
					'element_typography' => '',
					'typography_parent'  => '',
					'typography_child'   => '',
					'font_size'          => '',
					'title_color'        => '',
					'delete_delay'       => '1000',
					'hide_on_mobile'     => '',
					'class'              => '',
					'id'                 => '',
				),
				$args
			);

			$args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/typewriter-text/elegant-typewriter-text-parent.php' ) ) {
				include locate_template( 'templates/typewriter-text/elegant-typewriter-text-parent.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/typewriter-text/elegant-typewriter-text-parent.php';
			}

			$this->typewriter_text_counter++;

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

			if ( '' !== locate_template( 'templates/typewriter-text/elegant-typewriter-text-child.php' ) ) {
				include locate_template( 'templates/typewriter-text/elegant-typewriter-text-child.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/typewriter-text/elegant-typewriter-text-child.php';
			}

			return $child_html;
		}

		/**
		 * Returns equivalent global information for FB param.
		 *
		 * @since 1.0
		 * @return array|bool Element option data.
		 */
		public function iee_typewriter_text_map_descriptions() {
			$shortcode_option_map = array();

			$shortcode_option_map['background_color']['iee_typewriter_text'] = array(
				'theme-option' => 'iee_typewriter_text_background_color',
				'reset'        => true,
			);

			return $shortcode_option_map;
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
				'infi-elegant-typewriter-text',
				$elegant_js_folder_url . '/infi-elegant-typewriter-text.min.js',
				$elegant_js_folder_path . '/infi-elegant-typewriter-text.min.js',
				array( 'jquery' ),
				'1',
				true
			);
		}
	}

	new IEE_Typewriter_Text();
} // End if().


/**
 * Map shortcode for typewriter_text.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_typewriter_text() {
	global $fusion_settings;

	// Get default typography for title and description.
	$default_typography = elegant_get_default_typography();

	$parent_args = array(
		'name'                      => esc_attr__( 'Elegant Typewriter Text', 'elegant-elements' ),
		'shortcode'                 => 'iee_typewriter_text',
		'icon'                      => 'icon-typewriter',
		'multi'                     => 'multi_element_parent',
		'element_child'             => 'iee_typewriter_text_child',
		'preview'                   => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/previews/infi-typewriter-text-preview.php',
		'preview_id'                => 'elegant-elements-module-infi-typewriter-text-preview-template',
		'custom_settings_view_name' => 'ModuleSettingElegantView',
		'params'                    => array(
			array(
				'type'        => 'tinymce',
				'heading'     => esc_attr__( 'Content', 'elegant-elements' ),
				'description' => esc_attr__( 'Typewriter text items.', 'elegant-elements' ),
				'param_name'  => 'element_content',
				'value'       => '[iee_typewriter_text_child title="' . esc_attr__( 'Your Content Goes Here', 'elegant-elements' ) . '" /]',
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Prefix Text', 'elegant-elements' ),
				'description' => esc_attr__( 'Enter text to be displayed before the typewriter text.', 'elegant-elements' ),
				'param_name'  => 'prefix',
				'placeholder' => true,
				'value'       => esc_attr__( 'Prefix Text', 'elegant-elements' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Suffix Text', 'elegant-elements' ),
				'description' => esc_attr__( 'Enter text to be displayed after the typewriter text.', 'elegant-elements' ),
				'param_name'  => 'suffix',
				'placeholder' => true,
				'value'       => esc_attr__( 'Suffix Text', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Infinite Loop', 'elegant-elements' ),
				'description' => esc_attr__( 'Choose if you want to loop the text when it reaches to the end.', 'elegant-elements' ),
				'param_name'  => 'loop',
				'default'     => 'no',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Text Delete Delay ( In ms )', 'elegant-elements' ),
				'description' => esc_attr__( 'Controls the text delete delay. The cursor will wait till this delay reach to start deleting the text. ( In Milliseconds. )', 'elegant-elements' ),
				'param_name'  => 'delete_delay',
				'value'       => '1000',
				'min'         => '500',
				'max'         => '5000',
				'step'        => '100',
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Alignment', 'elegant-elements' ),
				'description' => esc_attr__( 'Set the text alignment. This will align the entier text with prefix and suffix to the set alignment.', 'elegant-elements' ),
				'param_name'  => 'alignment',
				'default'     => 'left',
				'value'       => array(
					'left'   => esc_attr__( 'Left', 'elegant-elements' ),
					'center' => esc_attr__( 'Center', 'elegant-elements' ),
					'right'  => esc_attr__( 'Right', 'elegant-elements' ),
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
			),
			array(
				'type'        => 'elegant_typography',
				'heading'     => esc_attr__( 'Prefix and Suffix Text Typography', 'elegant-elements' ),
				'description' => esc_attr__( 'Select typography for the text before and after the typewriter text.', 'elegant-elements' ),
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
				'heading'     => esc_attr__( 'Typewriter Text Typography', 'elegant-elements' ),
				'description' => esc_attr__( 'Select typography for the typewriter text.', 'elegant-elements' ),
				'param_name'  => 'typography_child',
				'value'       => '',
				'default'     => $default_typography['description'],
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
				'value'       => '28',
				'min'         => '12',
				'max'         => '100',
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
		'name'              => esc_attr__( 'Typewriter Text - Child', 'elegant-elements' ),
		'shortcode'         => 'iee_typewriter_text_child',
		'hide_from_builder' => true,
		'allow_generator'   => true,
		'tag_name'          => 'p',
		'selectors'         => array(
			'class' => 'elegant-typewriter-text-child',
		),
		'params'            => array(
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Text', 'elegant-elements' ),
				'description' => esc_attr__( 'Enter text to be displayed as typewriter text.', 'elegant-elements' ),
				'param_name'  => 'title',
				'placeholder' => true,
				'value'       => esc_attr__( 'Your Content Goes Here', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Text Color', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the color for this typewriter text.', 'elegant-elements' ),
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
				'IEE_Typewriter_Text',
				$parent_args,
				'parent'
			)
		);

		fusion_builder_map(
			fusion_builder_frontend_data(
				'IEE_Typewriter_Text',
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_typewriter_text', 99 );
