<?php
if ( fusion_is_element_enabled( 'iee_business_hours' ) && ! class_exists( 'IEE_Business_Hours' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 2.3
	 */
	class IEE_Business_Hours extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 2.3
		 * @var array
		 */
		protected $args;

		/**
		 * An array of the child shortcode arguments.
		 *
		 * @access protected
		 * @since 2.3
		 * @var array
		 */
		protected $child_args;

		/**
		 * Business hours counter.
		 *
		 * @since 2.3
		 * @access private
		 * @var object
		 */
		private $business_hours_counter = 1;

		/**
		 * Business hours child counter.
		 *
		 * @since 2.3
		 * @access private
		 * @var object
		 */
		private $business_hours_child_counter = 1;

		/**
		 * Business hours child count.
		 *
		 * @since 2.3
		 * @access private
		 * @var object
		 */
		private $business_hours_child_count = 1;

		/**
		 * Constructor.
		 *
		 * @since 2.3
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			// Parent filter.
			add_filter( 'fusion_attr_elegant-business-hours', array( $this, 'attr' ) );

			// Child item filters.
			add_filter( 'fusion_attr_elegant-business-hours-item', array( $this, 'child_attr' ) );
			add_filter( 'fusion_attr_elegant-business-hours-item-day', array( $this, 'child_attr_day' ) );
			add_filter( 'fusion_attr_elegant-business-hours-item-hours', array( $this, 'child_attr_hours' ) );
			add_filter( 'fusion_attr_elegant-business-hours-separator', array( $this, 'separator_attr' ) );

			add_shortcode( 'iee_business_hours', array( $this, 'render_parent' ) );
			add_shortcode( 'iee_business_hours_item', array( $this, 'render_child' ) );
		}

		/**
		 * Render the parent shortcode.
		 *
		 * @access public
		 * @since 2.3
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render_parent( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			$this->args = $args;

			$this->business_hours_child_count = count( explode( '[iee_business_hours_item', $content ) ) - 1;

			$html = '';

			if ( '' !== locate_template( 'templates/business-hours/elegant-business-hours-parent.php' ) ) {
				include locate_template( 'templates/business-hours/elegant-business-hours-parent.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/business-hours/elegant-business-hours-parent.php';
			}

			$this->business_hours_counter++;

			return $html;
		}

		/**
		 * Render the child shortcode.
		 *
		 * @access public
		 * @since 2.3
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render_child( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			$this->child_args = $args;

			$child_html = '';

			if ( '' !== locate_template( 'templates/business-hours/elegant-business-hours-child.php' ) ) {
				include locate_template( 'templates/business-hours/elegant-business-hours-child.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/business-hours/elegant-business-hours-child.php';
			}

			$this->business_hours_child_counter++;

			return $child_html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 2.3
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-business-hours',
				'style' => '',
			);

			$attr = fusion_builder_visibility_atts( $this->args['hide_on_mobile'], $attr );

			if ( '' !== $this->args['text_color'] ) {
				$attr['style'] .= 'color: ' . $this->args['text_color'] . ';';
			}

			$attr['style'] .= 'font-size:' . FusionBuilder::validate_shortcode_attr_value( $this->args['font_size'], 'px' ) . ';';

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
		 * @since 2.3
		 * @return array
		 */
		public function child_attr() {
			$attr = array(
				'class' => 'elegant-business-hours-item business-hours-item-' . $this->business_hours_child_counter,
				'style' => '',
			);

			if ( isset( $this->child_args['text_color'] ) && '' !== $this->child_args['text_color'] ) {
				$attr['style'] .= 'color: ' . $this->child_args['text_color'] . ';';
			}

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 2.3
		 * @return array
		 */
		public function child_attr_day() {
			$attr = array(
				'class' => 'elegant-business-hours-item-day',
				'style' => '',
			);

			$attr['class'] .= ' elegant-align-' . $this->args['day_alignment'];

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 2.3
		 * @return array
		 */
		public function child_attr_hours() {
			$attr = array(
				'class' => 'elegant-business-hours-item-hours',
				'style' => '',
			);

			$attr['class'] .= ' elegant-align-' . $this->args['hours_alignment'];

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 2.3
		 * @return array
		 */
		public function separator_attr() {
			$attr = array(
				'class' => 'elegant-business-hours-sep elegant-content-sep',
				'style' => '',
			);

			$styles = explode( ' ', $this->args['separator_type'] );

			foreach ( $styles as $style ) {
				$attr['class'] .= ' sep-' . $style;
			}

			if ( '' !== $this->args['sep_color'] ) {
				$attr['style'] .= 'border-color: ' . $this->args['sep_color'] . ';';
			}

			return $attr;
		}
	}

	new IEE_Business_Hours();
} // End if().


/**
 * Map shortcode for business_hours.
 *
 * @since 2.3
 * @return void
 */
function map_elegant_elements_business_hours() {
	global $fusion_settings;

	$parent_args = array(
		'name'          => esc_attr__( 'Elegant Business Hours', 'elegant-elements' ),
		'shortcode'     => 'iee_business_hours',
		'icon'          => 'fa-business-time fas business-hours-icon',
		'multi'         => 'multi_element_parent',
		'element_child' => 'iee_business_hours_item',
		'preview'       => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/previews/infi-business-hours-preview.php',
		'preview_id'    => 'elegant-elements-module-infi-business-hours-preview-template',
		'params'        => array(
			array(
				'type'        => 'tinymce',
				'heading'     => esc_attr__( 'Content', 'elegant-elements' ),
				'description' => esc_attr__( 'Business hours items.', 'elegant-elements' ),
				'param_name'  => 'element_content',
				'value'       => '[iee_business_hours_item title="' . esc_attr__( 'Monday - Tuesday', 'elegant-elements' ) . '" hours_text="9:00 - 17:00" /]',
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Business Day Alignment', 'elegant-elements' ),
				'description' => esc_attr__( 'Set the text alignment for the business days. This will align the only business days text.', 'elegant-elements' ),
				'param_name'  => 'day_alignment',
				'default'     => 'left',
				'value'       => array(
					'left'   => esc_attr__( 'Left', 'elegant-elements' ),
					'center' => esc_attr__( 'Center', 'elegant-elements' ),
					'right'  => esc_attr__( 'Right', 'elegant-elements' ),
				),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Business Hours Alignment', 'elegant-elements' ),
				'description' => esc_attr__( 'Set the text alignment for the business hours. This will align the only business hours text.', 'elegant-elements' ),
				'param_name'  => 'hours_alignment',
				'default'     => 'right',
				'value'       => array(
					'left'   => esc_attr__( 'Left', 'elegant-elements' ),
					'center' => esc_attr__( 'Center', 'elegant-elements' ),
					'right'  => esc_attr__( 'Right', 'elegant-elements' ),
				),
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Font Size', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the font size for the day and hours text. ( In Pixel. )', 'elegant-elements' ),
				'param_name'  => 'font_size',
				'value'       => '18',
				'min'         => '12',
				'max'         => '100',
				'step'        => '1',
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Text Color', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the text color for the day and hours text. You can control the color for individual business hours item from the child settings.', 'elegant-elements' ),
				'param_name'  => 'text_color',
				'value'       => '',
				'group'       => esc_attr__( 'Design', 'elegant-elements' ),
			),
			array(
				'type'        => 'select',
				'heading'     => esc_attr__( 'Separator', 'fusion-builder' ),
				'description' => esc_attr__( 'Choose the kind of the line separator you want to use.', 'elegant-elements' ),
				'param_name'  => 'separator_type',
				'value'       => array(
					'default'       => esc_attr__( 'Default', 'elegant-elements' ),
					'single solid'  => esc_attr__( 'Single Solid', 'elegant-elements' ),
					'single dashed' => esc_attr__( 'Single Dashed', 'elegant-elements' ),
					'single dotted' => esc_attr__( 'Single Dotted', 'elegant-elements' ),
					'double solid'  => esc_attr__( 'Double Solid', 'elegant-elements' ),
					'double dashed' => esc_attr__( 'Double Dashed', 'elegant-elements' ),
					'double dotted' => esc_attr__( 'Double Dotted', 'elegant-elements' ),
					'none'          => esc_attr__( 'None', 'elegant-elements' ),
				),
				'default'     => 'default',
				'group'       => esc_attr__( 'Design', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Separator Color', 'elegant-elements' ),
				'description' => esc_attr__( 'Controls the separator color.', 'elegant-elements' ),
				'param_name'  => 'sep_color',
				'value'       => '',
				'dependency'  => array(
					array(
						'element'  => 'style_type',
						'value'    => 'none',
						'operator' => '!=',
					),
				),
				'group'       => esc_attr__( 'Design', 'elegant-elements' ),
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
		'name'              => esc_attr__( 'Business Hours Item', 'elegant-elements' ),
		'shortcode'         => 'iee_business_hours_item',
		'hide_from_builder' => true,
		'allow_generator'   => true,
		'inline_editor'     => true,
		'tag_name'          => 'div',
		'selectors'         => array(
			'class' => 'elegant-business-hours-child',
		),
		'params'            => array(
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Day Text', 'elegant-elements' ),
				'description' => esc_attr__( 'Enter text to be displayed as business day.', 'elegant-elements' ),
				'param_name'  => 'title',
				'placeholder' => true,
				'value'       => esc_attr__( 'Monday - Tuesday', 'elegant-elements' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Hours Text', 'elegant-elements' ),
				'description' => esc_attr__( 'Enter text to be displayed as business hours.', 'elegant-elements' ),
				'param_name'  => 'hours_text',
				'placeholder' => true,
				'value'       => esc_attr__( '9:00 - 17:00', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Text Color', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the color for this business hours item text. Leave empty to inherit from parent settings.', 'elegant-elements' ),
				'param_name'  => 'text_color',
				'value'       => '',
			),
		),
	);

	if ( function_exists( 'fusion_builder_frontend_data' ) ) {
		fusion_builder_map(
			fusion_builder_frontend_data(
				'IEE_Business_Hours',
				$parent_args,
				'parent'
			)
		);

		fusion_builder_map(
			fusion_builder_frontend_data(
				'IEE_Business_Hours',
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_business_hours', 99 );
