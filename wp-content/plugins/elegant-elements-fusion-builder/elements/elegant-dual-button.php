<?php
if ( fusion_is_element_enabled( 'iee_dual_button' ) && ! class_exists( 'IEE_Dual_Button' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class IEE_Dual_Button extends Fusion_Element {

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

			add_filter( 'fusion_attr_elegant-dual-button', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_elegant-dual-button-separator', array( $this, 'separator_attr' ) );
			add_shortcode( 'iee_dual_button', array( $this, 'render' ) );
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
				$args['typography_separator'] = $default_typography['title'];
			}

			$this->args = $args;

			$html = '';

			if ( '' !== locate_template( 'templates/dual-button/elegant-dual-button.php' ) ) {
				include locate_template( 'templates/dual-button/elegant-dual-button.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/dual-button/elegant-dual-button.php';
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
				'class' => 'elegant-dual-button',
			);

			if ( isset( $this->args['alignment'] ) && '' !== $this->args['alignment'] ) {
				$attr['class'] .= ' elegant-align-' . $this->args['alignment'];
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
		 * Builds the attributes array for separator.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function separator_attr() {
			$attr = array(
				'class' => 'elegant-dual-button-separator',
				'style' => '',
			);

			$attr['class'] .= ' elegant-separator-type-' . $this->args['separator_type'];

			if ( isset( $this->args['sep_background_color'] ) && '' !== $this->args['sep_background_color'] ) {
				$attr['style'] .= 'background-color:' . $this->args['sep_background_color'] . ';';
			}

			if ( isset( $this->args['sep_color'] ) && '' !== $this->args['sep_color'] ) {
				$attr['style'] .= 'color:' . $this->args['sep_color'] . ';';
			}

			if ( isset( $this->args['typography_separator'] ) && '' !== $this->args['typography_separator'] ) {
				$typography           = $this->args['typography_separator'];
				$separator_typography = elegant_get_typography_css( $typography );

				$attr['style'] .= $separator_typography;
			}

			return $attr;
		}
	}

	new IEE_Dual_Button();
} // End if().


/**
 * Map shortcode for dual_button.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_dual_button() {
	global $fusion_settings;

	// Get default typography for title and description.
	$default_typography = elegant_get_default_typography();

	fusion_builder_map(
		array(
			'name'                      => esc_attr__( 'Elegant Dual Button', 'elegant-elements' ),
			'shortcode'                 => 'iee_dual_button',
			'icon'                      => 'icon-dual-button',
			'preview'                   => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/previews/infi-dual-button-preview.php',
			'preview_id'                => 'elegant-elements-module-infi-dual-button-preview-template',
			'admin_enqueue_js'          => ELEGANT_ELEMENTS_PLUGIN_URL . 'elements/custom/js/elegant-dual-button-filter.js',
			'on_change'                 => 'elegantDualButtonShortcodeFilter',
			'custom_settings_view_name' => 'ModuleSettingElegantView',
			'front-end'                 => ELEGANT_ELEMENTS_PLUGIN_DIR . '/elements/front-end/templates/infi-dual-button-preview.php',
			'params'                    => array(
				array(
					'type'        => 'raw_textarea',
					'heading'     => esc_attr__( 'Button 1 Shortcode', 'elegant-elements' ),
					'param_name'  => 'button_1',
					'value'       => '',
					'description' => __( 'Click the link to generate button 1 shortcode. Please remove the previous shortcode before generating new one.<br/><p><a href="#" class="elegant-elements-add-shortcode" data-type="fusion_button" data-base="dual_button" data-editor-id="button_1">Generate Button 1 Shortcode</a></p>', 'elegant-elements' ),
				),
				array(
					'type'        => 'raw_textarea',
					'heading'     => esc_attr__( 'Button 2 Shortcode', 'elegant-elements' ),
					'param_name'  => 'button_2',
					'value'       => '',
					'description' => __( 'Click the link to generate button 2 shortcode. Please remove the previous shortcode before generating new one.<br/><p><a href="#" class="elegant-elements-add-shortcode" data-type="fusion_button" data-base="dual_button" data-editor-id="button_2">Generate Button 2 Shortcode</a></p>', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Separator Type', 'elegant-elements' ),
					'param_name'  => 'separator_type',
					'default'     => 'string',
					'value'       => array(
						'string' => esc_attr__( 'String', 'elegant-elements' ),
						'icon'   => esc_attr__( 'Icon', 'elegant-elements' ),
						'none'   => esc_attr__( 'None', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Select if you want to display string or icon in separator or remove the separator.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Separator', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Separator Word', 'elegant-elements' ),
					'param_name'  => 'sep_text',
					'value'       => esc_attr__( 'OR', 'elegant-elements' ),
					'placeholder' => true,
					'description' => esc_attr__( 'Controls the string displayed in separator.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Separator', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'separator_type',
							'value'    => 'string',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_attr__( 'Separator Icon', 'elegant-elements' ),
					'param_name'  => 'sep_icon',
					'value'       => '',
					'description' => esc_attr__( 'Select the icon to be used as separator.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Separator', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'separator_type',
							'value'    => 'icon',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Separator Icon / Text Background Color', 'elegant-elements' ),
					'param_name'  => 'sep_background_color',
					'value'       => '#ffffff',
					'description' => esc_attr__( 'Select the color to be applied to separator background.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Separator', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'separator_type',
							'value'    => 'none',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Separator Icon / Text Color', 'elegant-elements' ),
					'param_name'  => 'sep_color',
					'value'       => '#8bc34a',
					'description' => esc_attr__( 'Select the text color to be applied to separator text or icon.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Separator', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'separator_type',
							'value'    => 'none',
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
					'group'       => esc_attr__( 'Separator', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'separator_type',
							'value'    => 'string',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'elegant_typography',
					'heading'     => esc_attr__( 'Separator Text Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select typography for the separator text.', 'elegant-elements' ),
					'param_name'  => 'typography_separator',
					'value'       => '',
					'default'     => $default_typography['title'],
					'group'       => esc_attr__( 'Separator', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'element_typography',
							'value'    => 'default',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Alignment', 'elegant-elements' ),
					'param_name'  => 'alignment',
					'default'     => 'left',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'center' => esc_attr__( 'Center', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Select the button alignment.', 'elegant-elements' ),
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_dual_button', 99 );
