<?php
if ( fusion_is_element_enabled( 'iee_contact_form7' ) && ! class_exists( 'IEE_Contact_Form7' ) && defined( 'WPCF7_VERSION' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class IEE_Contact_Form7 extends Fusion_Element {

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

			add_filter( 'fusion_attr_elegant-contact-form7', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_elegant-contact-form7-form-wrapper', array( $this, 'form_attr' ) );

			add_shortcode( 'iee_contact_form7', array( $this, 'render' ) );
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
				$args['typography_heading'] = $default_typography['title'];
				$args['typography_caption'] = $default_typography['description'];
			}

			$this->args = $args;

			$html = '';

			if ( '' !== locate_template( 'templates/contact-form7/elegant-contact-form7.php' ) ) {
				include locate_template( 'templates/contact-form7/elegant-contact-form7.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/contact-form7/elegant-contact-form7.php';
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
				'class' => 'elegant-contact-form7',
			);

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
		 * Builds the attributes array for form wrapper.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function form_attr() {
			global $fusion_library;

			$attr = array(
				'class' => 'elegant-contact-form7-form-wrapper',
			);

			$styles = '';

			// Set form styles.
			if ( $this->args['form_padding'] ) {
				$styles .= 'padding:' . $fusion_library->sanitize->get_value_with_unit( $this->args['form_padding'] ) . ';';
			}

			if ( $this->args['form_background_color'] ) {
				$styles .= 'background-color:' . $this->args['form_background_color'] . ';';
			}

			if ( $this->args['form_background_image'] ) {
				$styles .= 'background-image: url(' . $this->args['form_background_image'] . ');';
				$styles .= 'background-position: ' . $this->args['form_background_position'] . ';';
				$styles .= 'background-repeat: ' . $this->args['form_background_repeat'] . ';';
				$styles .= 'background-blend-mode: overlay;';
			}

			if ( $this->args['form_border_size'] ) {
				$styles .= 'border-width:' . FusionBuilder::validate_shortcode_attr_value( $this->args['form_border_size'], 'px' ) . ';';
				$styles .= 'border-color:' . $this->args['form_border_color'] . ';';
				$styles .= 'border-style:' . $this->args['form_border_style'] . ';';

				if ( isset( $this->args['heading_text'] ) && '' !== trim( $this->args['heading_text'] ) || isset( $this->args['caption_text'] ) && '' !== trim( $this->args['caption_text'] ) ) {
					$styles .= 'border-top: none;';
				}
			}

			if ( isset( $this->args['form_border_radius'] ) && '' !== $this->args['form_border_radius'] ) {
				$border_radius = FusionBuilder::validate_shortcode_attr_value( $this->args['form_border_radius'], 'px' );

				if ( isset( $this->args['heading_text'] ) && '' !== trim( $this->args['heading_text'] ) || isset( $this->args['caption_text'] ) && '' !== trim( $this->args['caption_text'] ) ) {
					$styles .= 'border-radius:0 0 ' . $border_radius . ' ' . $border_radius . ';';
				} else {
					$styles .= 'border-radius:' . $border_radius . ';';
				}
			}

			if ( '' !== $styles ) {
				$attr['style'] = $styles;
			}

			return $attr;
		}
	}

	new IEE_Contact_Form7();
} // End if().


/**
 * Map shortcode for contact_form7.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_contact_form7() {
	global $fusion_settings;

	// Get default typography for title and description.
	$default_typography = elegant_get_default_typography();

	fusion_builder_map(
		array(
			'name'                      => esc_attr__( 'Elegant Contact Form 7', 'elegant-elements' ),
			'shortcode'                 => 'iee_contact_form7',
			'icon'                      => 'icon-contact-form7',
			'front-end'                 => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-contact-form7-preview.php',
			'inline_editor'             => true,
			'custom_settings_view_name' => 'ModuleSettingElegantView',
			'params'                    => array(
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Select Form', 'elegant-elements' ),
					'description' => esc_attr__( 'Select which form you want to use.', 'elegant-elements' ),
					'param_name'  => 'form',
					'value'       => elegant_get_contact_form_list(),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Heading Text', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter heading text for this form.', 'elegant-elements' ),
					'param_name'  => 'heading_text',
					'value'       => esc_attr__( 'Subscribe to our newsletter', 'elegant-elements' ),
					'placeholder' => true,
					'group'       => esc_attr__( 'Form Heading', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Heading Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the text color for the form heading.', 'elegant-elements' ),
					'param_name'  => 'heading_color',
					'value'       => '#03a9f4',
					'dependency'  => array(
						array(
							'element'  => 'heading_text',
							'value'    => '',
							'operator' => '!=',
						),
					),
					'group'       => esc_attr__( 'Form Heading', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Heading Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the background color for the form heading area.', 'elegant-elements' ),
					'param_name'  => 'heading_background_color',
					'value'       => '#fcfcfc',
					'dependency'  => array(
						array(
							'element'  => 'heading_text',
							'value'    => '',
							'operator' => '!=',
						),
					),
					'group'       => esc_attr__( 'Form Heading', 'elegant-elements' ),
				),
				array(
					'type'        => 'upload',
					'heading'     => esc_attr__( 'Heading Background Image', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the image to be used as background image for the form heading area.', 'elegant-elements' ),
					'param_name'  => 'heading_background_image',
					'dependency'  => array(
						array(
							'element'  => 'heading_text',
							'value'    => '',
							'operator' => '!=',
						),
					),
					'group'       => esc_attr__( 'Form Heading', 'elegant-elements' ),
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Heading Background Image Position', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the postion of the background image for form heading.', 'elegant-elements' ),
					'param_name'  => 'heading_background_position',
					'default'     => 'left top',
					'dependency'  => array(
						array(
							'element'  => 'heading_background_image',
							'value'    => '',
							'operator' => '!=',
						),
						array(
							'element'  => 'heading_text',
							'value'    => '',
							'operator' => '!=',
						),
					),
					'value'       => array(
						'left top'      => esc_attr__( 'Left Top', 'elegant-elements' ),
						'left center'   => esc_attr__( 'Left Center', 'elegant-elements' ),
						'left bottom'   => esc_attr__( 'Left Bottom', 'elegant-elements' ),
						'right top'     => esc_attr__( 'Right Top', 'elegant-elements' ),
						'right center'  => esc_attr__( 'Right Center', 'elegant-elements' ),
						'right bottom'  => esc_attr__( 'Right Bottom', 'elegant-elements' ),
						'center top'    => esc_attr__( 'Center Top', 'elegant-elements' ),
						'center center' => esc_attr__( 'Center Center', 'elegant-elements' ),
						'center bottom' => esc_attr__( 'Center Bottom', 'elegant-elements' ),
					),
					'group'       => esc_attr__( 'Form Heading', 'elegant-elements' ),
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Heading Background Repeat', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose how the background image repeats for heading.', 'elegant-elements' ),
					'param_name'  => 'heading_background_repeat',
					'default'     => 'no-repeat',
					'dependency'  => array(
						array(
							'element'  => 'heading_background_image',
							'value'    => '',
							'operator' => '!=',
						),
						array(
							'element'  => 'heading_text',
							'value'    => '',
							'operator' => '!=',
						),
					),
					'value'       => array(
						'no-repeat' => esc_attr__( 'No Repeat', 'elegant-elements' ),
						'repeat'    => esc_attr__( 'Repeat Vertically and Horizontally', 'elegant-elements' ),
						'repeat-x'  => esc_attr__( 'Repeat Horizontally', 'elegant-elements' ),
						'repeat-y'  => esc_attr__( 'Repeat Vertically', 'elegant-elements' ),
					),
					'group'       => esc_attr__( 'Form Heading', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Heading Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the heading size for the form heading.', 'elegant-elements' ),
					'param_name'  => 'heading_size',
					'default'     => 'h2',
					'value'       => array(
						'h1' => esc_attr__( 'H1', 'elegant-elements' ),
						'h2' => esc_attr__( 'H2', 'elegant-elements' ),
						'h3' => esc_attr__( 'H3', 'elegant-elements' ),
						'h4' => esc_attr__( 'H4', 'elegant-elements' ),
						'h5' => esc_attr__( 'H5', 'elegant-elements' ),
						'h6' => esc_attr__( 'H6', 'elegant-elements' ),
					),
					'dependency'  => array(
						array(
							'element'  => 'heading_text',
							'value'    => '',
							'operator' => '!=',
						),
					),
					'group'       => esc_attr__( 'Form Heading', 'elegant-elements' ),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Heading Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the heading font size. ( In pixel. )', 'elegant-elements' ),
					'param_name'  => 'heading_font_size',
					'default'     => '',
					'value'       => '28',
					'min'         => '12',
					'max'         => '100',
					'step'        => '1',
					'dependency'  => array(
						array(
							'element'  => 'heading_text',
							'value'    => '',
							'operator' => '!=',
						),
					),
					'group'       => esc_attr__( 'Form Heading', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Heading Alignment', 'elegant-elements' ),
					'description' => esc_attr__( 'Select alignment for heading text.', 'elegant-elements' ),
					'param_name'  => 'heading_align',
					'default'     => 'left',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'center' => esc_attr__( 'Center', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'dependency'  => array(
						array(
							'element'  => 'heading_text',
							'value'    => '',
							'operator' => '!=',
						),
					),
					'group'       => esc_attr__( 'Form Heading', 'elegant-elements' ),
				),
				array(
					'type'        => 'dimension',
					'heading'     => esc_attr__( 'Heading Area Padding', 'elegant-elements' ),
					'description' => esc_attr__( 'In pixels (px), ex: 10px.', 'elegant-elements' ),
					'param_name'  => 'heading_padding',
					'value'       => '15px',
					'dependency'  => array(
						array(
							'element'  => 'heading_text',
							'value'    => '',
							'operator' => '!=',
						),
					),
					'group'       => esc_attr__( 'Form Heading', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Caption Text', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter caption text for this form.', 'elegant-elements' ),
					'param_name'  => 'caption_text',
					'value'       => esc_attr__( 'Get latest blog posts to your inbox', 'elegant-elements' ),
					'placeholder' => true,
					'group'       => esc_attr__( 'Form Heading', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Caption Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the text color for the form heading caption.', 'elegant-elements' ),
					'param_name'  => 'caption_color',
					'value'       => '#6d6d6d',
					'dependency'  => array(
						array(
							'element'  => 'caption_text',
							'value'    => '',
							'operator' => '!=',
						),
					),
					'group'       => esc_attr__( 'Form Heading', 'elegant-elements' ),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Caption Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the caption font size. ( In pixel. )', 'elegant-elements' ),
					'param_name'  => 'caption_font_size',
					'default'     => '',
					'value'       => '18',
					'min'         => '10',
					'max'         => '100',
					'step'        => '1',
					'dependency'  => array(
						array(
							'element'  => 'caption_text',
							'value'    => '',
							'operator' => '!=',
						),
					),
					'group'       => esc_attr__( 'Form Heading', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Form Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the background color for the form area.', 'elegant-elements' ),
					'param_name'  => 'form_background_color',
					'value'       => '#f9f9f9',
					'group'       => esc_attr__( 'Form Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'upload',
					'heading'     => esc_attr__( 'Form Background Image', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the image to be used as background image for the form area.', 'elegant-elements' ),
					'param_name'  => 'form_background_image',
					'group'       => esc_attr__( 'Form Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Form Background Image Position', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the postion of the background image for form area.', 'elegant-elements' ),
					'param_name'  => 'form_background_position',
					'default'     => 'left top',
					'dependency'  => array(
						array(
							'element'  => 'form_background_image',
							'value'    => '',
							'operator' => '!=',
						),
					),
					'value'       => array(
						'left top'      => esc_attr__( 'Left Top', 'elegant-elements' ),
						'left center'   => esc_attr__( 'Left Center', 'elegant-elements' ),
						'left bottom'   => esc_attr__( 'Left Bottom', 'elegant-elements' ),
						'right top'     => esc_attr__( 'Right Top', 'elegant-elements' ),
						'right center'  => esc_attr__( 'Right Center', 'elegant-elements' ),
						'right bottom'  => esc_attr__( 'Right Bottom', 'elegant-elements' ),
						'center top'    => esc_attr__( 'Center Top', 'elegant-elements' ),
						'center center' => esc_attr__( 'Center Center', 'elegant-elements' ),
						'center bottom' => esc_attr__( 'Center Bottom', 'elegant-elements' ),
					),
					'group'       => esc_attr__( 'Form Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Form Background Repeat', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose how the background image repeats for form area.', 'elegant-elements' ),
					'param_name'  => 'form_background_repeat',
					'default'     => 'no-repeat',
					'dependency'  => array(
						array(
							'element'  => 'form_background_image',
							'value'    => '',
							'operator' => '!=',
						),
					),
					'value'       => array(
						'no-repeat' => esc_attr__( 'No Repeat', 'elegant-elements' ),
						'repeat'    => esc_attr__( 'Repeat Vertically and Horizontally', 'elegant-elements' ),
						'repeat-x'  => esc_attr__( 'Repeat Horizontally', 'elegant-elements' ),
						'repeat-y'  => esc_attr__( 'Repeat Vertically', 'elegant-elements' ),
					),
					'group'       => esc_attr__( 'Form Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'dimension',
					'heading'     => esc_attr__( 'Form Area Padding', 'elegant-elements' ),
					'description' => esc_attr__( 'In pixels (px), ex: 10px.', 'elegant-elements' ),
					'param_name'  => 'form_padding',
					'value'       => '15px',
					'group'       => esc_attr__( 'Form Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Form Border Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border size of the form. In pixels.', 'elegant-elements' ),
					'param_name'  => 'form_border_size',
					'min'         => '0',
					'max'         => '50',
					'step'        => '1',
					'value'       => '0',
					'group'       => esc_attr__( 'Form Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Form Border Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border color of the form.', 'elegant-elements' ),
					'param_name'  => 'form_border_color',
					'value'       => '#dddddd',
					'dependency'  => array(
						array(
							'element'  => 'form_border_size',
							'value'    => '0',
							'operator' => '!=',
						),
					),
					'group'       => esc_attr__( 'Form Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Form Border Style', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border style.', 'elegant-elements' ),
					'param_name'  => 'form_border_style',
					'value'       => array(
						'solid'  => esc_attr__( 'Solid', 'elegant-elements' ),
						'dashed' => esc_attr__( 'Dashed', 'elegant-elements' ),
						'dotted' => esc_attr__( 'Dotted', 'elegant-elements' ),
					),
					'default'     => 'solid',
					'dependency'  => array(
						array(
							'element'  => 'form_border_size',
							'value'    => '0',
							'operator' => '!=',
						),
					),
					'group'       => esc_attr__( 'Form Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Form Border Radius', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border radius for the form container. In pixels (px).', 'elegant-elements' ),
					'param_name'  => 'form_border_radius',
					'min'         => '0',
					'max'         => '100',
					'step'        => '1',
					'value'       => '0',
					'group'       => esc_attr__( 'Form Design', 'elegant-elements' ),
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
					'heading'     => esc_attr__( 'Heading Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select typography for the form heading text.', 'elegant-elements' ),
					'param_name'  => 'typography_heading',
					'value'       => '',
					'default'     => $default_typography['title'],
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
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
					'heading'     => esc_attr__( 'Heading Caption Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select typography for the form heading caption text.', 'elegant-elements' ),
					'param_name'  => 'typography_caption',
					'value'       => '',
					'default'     => $default_typography['description'],
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'element_typography',
							'value'    => 'default',
							'operator' => '!=',
						),
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
		)
	);
}

if ( defined( 'WPCF7_VERSION' ) ) {
	add_action( 'fusion_builder_before_init', 'map_elegant_elements_contact_form7', 99 );
}
