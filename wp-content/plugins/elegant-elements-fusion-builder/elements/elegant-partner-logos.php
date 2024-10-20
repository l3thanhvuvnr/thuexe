<?php
if ( fusion_is_element_enabled( 'iee_partner_logos' ) && ! class_exists( 'IEE_Partner_Logo' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class IEE_Partner_Logo extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $args;

		/**
		 * Partner Logo counter.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $partner_logos_counter = 1;

		/**
		 * Partner Logo.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $partner_logos = array();

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-partner-logos', array( $this, 'attr' ) );

			add_shortcode( 'iee_partner_logos', array( $this, 'render_parent' ) );
			add_shortcode( 'iee_partner_logo', array( $this, 'render_child' ) );
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

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'border'         => '',
					'border_color'   => '',
					'border_style'   => '',
					'padding'        => '',
					'margin'         => '',
					'width'          => '',
					'height'         => '',
					'logo_alignment' => '',
					'hide_on_mobile' => '',
					'class'          => '',
					'id'             => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/partner-logos/elegant-partner-logos-parent.php' ) ) {
				include locate_template( 'templates/partner-logos/elegant-partner-logos-parent.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/partner-logos/elegant-partner-logos-parent.php';
			}

			$this->partner_logos_counter++;

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
					'image_url'    => '',
					'title'        => '',
					'click_action' => '',
					'modal_anchor' => '',
					'url'          => '',
					'target'       => '',
					'class'        => '',
					'id'           => '',
				),
				$args
			);

			$args = $defaults;

			$child_html = '';

			if ( '' !== locate_template( 'templates/partner-logos/elegant-partner-logos-child.php' ) ) {
				include locate_template( 'templates/partner-logos/elegant-partner-logos-child.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/partner-logos/elegant-partner-logos-child.php';
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
				'class' => 'elegant-partner-logos',
			);

			if ( isset( $this->args['logo_alignment'] ) && '' !== $this->args['logo_alignment'] ) {
				$attr['class'] .= ' elegant-partner-logo-align-' . $this->args['logo_alignment'];
			}

			return $attr;
		}
	}

	new IEE_Partner_Logo();
} // End if().


/**
 * Map shortcode for partner_logos.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_partner_logos() {
	global $fusion_settings;

	$parent_args = array(
		'name'          => esc_attr__( 'Elegant Partner Logo', 'elegant-elements' ),
		'shortcode'     => 'iee_partner_logos',
		'icon'          => 'fusiona-images',
		'multi'         => 'multi_element_parent',
		'element_child' => 'iee_partner_logo',
		'preview'       => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/previews/infi-partner-logos-preview.php',
		'preview_id'    => 'elegant-elements-module-infi-partner-logos-preview-template',
		'child_ui'      => true,
		'params'        => array(
			array(
				'type'        => 'tinymce',
				'heading'     => esc_attr__( 'Content', 'elegant-elements' ),
				'description' => esc_attr__( 'Partner logo items.', 'elegant-elements' ),
				'param_name'  => 'element_content',
				'value'       => '[iee_partner_logo title="' . esc_attr__( 'Your Content Goes Here', 'elegant-elements' ) . '" /]',
			),
			array(
				'type'             => 'multiple_upload',
				'heading'          => esc_attr__( 'Bulk Image Upload', 'elegant-elements' ),
				'description'      => __( 'This option allows you to select multiple images at once and they will populate into individual items. It saves time instead of adding one image at a time.', 'elegant-elements' ),
				'param_name'       => 'multiple_upload',
				'element_target'   => 'iee_partner_logo',
				'param_target'     => 'image_url',
				'remove_from_atts' => true,
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Border', 'elegant-elements' ),
				'param_name'  => 'border',
				'default'     => '',
				'value'       => '0',
				'min'         => '0',
				'max'         => '10',
				'step'        => '1',
				'description' => esc_attr__( 'Select the border size.', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpickeralpha',
				'heading'     => esc_attr__( 'Border Color', 'elegant-elements' ),
				'param_name'  => 'border_color',
				'value'       => '',
				'dependency'  => array(
					array(
						'element'  => 'border',
						'value'    => '0',
						'operator' => '!=',
					),
				),
				'description' => esc_attr__( 'Select border color for the partner logo.', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Border Style', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the border style you want to use.', 'elegant-elements' ),
				'param_name'  => 'border_style',
				'default'     => 'solid',
				'value'       => array(
					'solid'  => __( 'Solid', 'elegant-elements' ),
					'double' => __( 'Double', 'elegant-elements' ),
					'dashed' => __( 'Dashed', 'elegant-elements' ),
					'dotted' => __( 'Dotted', 'elegant-elements' ),
				),
				'dependency'  => array(
					array(
						'element'  => 'border',
						'value'    => '0',
						'operator' => '!=',
					),
				),
			),
			array(
				'type'        => 'dimension',
				'heading'     => esc_attr__( 'Padding', 'elegant-elements' ),
				'param_name'  => 'padding',
				'value'       => '',
				'description' => esc_attr__( 'Enter padding to add space around logo image. In pixels (px) eg. 10px.', 'elegant-elements' ),
			),
			array(
				'type'        => 'dimension',
				'heading'     => esc_attr__( 'Margin', 'elegant-elements' ),
				'param_name'  => 'margin',
				'value'       => '',
				'description' => esc_attr__( 'Enter margin to add space around logo image wrapper. In pixesl (px) eg. 10px.', 'elegant-elements' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Image Max Width', 'elegant-elements' ),
				'param_name'  => 'width',
				'value'       => '',
				'description' => esc_attr__( 'Enter width to be set as max width for image wrapper. In Pixels (px). eg. 120px.', 'elegant-elements' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Image Max Height', 'elegant-elements' ),
				'param_name'  => 'height',
				'value'       => '',
				'description' => esc_attr__( 'Enter height to be set as max height for image wrapper. Leave empty to set image height auto in width proportion. In Pixels (px). eg. 120px.', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Align logos', 'elegant-elements' ),
				'param_name'  => 'logo_alignment',
				'default'     => 'left',
				'value'       => array(
					'left'   => esc_attr__( 'Left', 'elegant-elements' ),
					'center' => esc_attr__( 'Center', 'elegant-elements' ),
					'right'  => esc_attr__( 'Right', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Set logos alignment. Useful if there\'s empty space after logos due to logo width being less than its container width.', 'elegant-elements' ),
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
		'name'              => esc_attr__( 'Partner Logo', 'elegant-elements' ),
		'shortcode'         => 'iee_partner_logo',
		'hide_from_builder' => true,
		'allow_generator'   => true,
		'tag_name'          => 'li',
		'selectors'         => array(
			'class' => 'elegant-partner-logo',
		),
		'params'            => array(
			array(
				'type'        => 'upload',
				'heading'     => esc_attr__( 'Image', 'elegant-elements' ),
				'description' => esc_attr__( 'Upload an image to display in the frame.', 'elegant-elements' ),
				'param_name'  => 'image_url',
				'value'       => '',
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Logo Title', 'elegant-elements' ),
				'description' => esc_attr__( 'Enter title to be used for this partner logo.', 'elegant-elements' ),
				'param_name'  => 'title',
				'placeholder' => true,
				'value'       => esc_attr__( 'Your Content Goes Here', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'On Click Action', 'elegant-elements' ),
				'description' => esc_attr__( 'Choose what you want to do when user click on partner logo.', 'elegant-elements' ),
				'param_name'  => 'click_action',
				'default'     => 'none',
				'value'       => array(
					'modal' => __( 'Open Modal', 'elegant-elements' ),
					'url'   => __( 'Open URL', 'elegant-elements' ),
					'none'  => __( 'Do Nothing', 'elegant-elements' ),
				),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Modal Window Anchor', 'elegant-elements' ),
				'description' => esc_attr__( 'Add the class name of the modal window you want to open on partner logo click.', 'elegant-elements' ),
				'param_name'  => 'modal_anchor',
				'value'       => '',
				'dependency'  => array(
					array(
						'element'  => 'click_action',
						'value'    => 'modal',
						'operator' => '==',
					),
				),
			),
			array(
				'type'        => 'link_selector',
				'heading'     => esc_attr__( 'URL to Open', 'elegant-elements' ),
				'description' => esc_attr__( 'Enter the url you want to open on partner logo click.', 'elegant-elements' ),
				'param_name'  => 'url',
				'value'       => '',
				'dependency'  => array(
					array(
						'element'  => 'click_action',
						'value'    => 'url',
						'operator' => '==',
					),
				),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Link Target', 'elegant-elements' ),
				'description' => esc_attr__( 'Select if you want to open the link in current browser tab or in new tab.', 'elegant-elements' ),
				'param_name'  => 'target',
				'default'     => '_blank',
				'value'       => array(
					'_blank' => __( 'New Tab', 'elegant-elements' ),
					'_self'  => __( 'Current Tab', 'elegant-elements' ),
				),
				'dependency'  => array(
					array(
						'element'  => 'click_action',
						'value'    => 'url',
						'operator' => '==',
					),
					array(
						'element'  => 'url',
						'value'    => '',
						'operator' => '!=',
					),
				),
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
				'IEE_Partner_Logo',
				$parent_args,
				'parent'
			)
		);

		fusion_builder_map(
			fusion_builder_frontend_data(
				'IEE_Partner_Logo',
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_partner_logos', 99 );
