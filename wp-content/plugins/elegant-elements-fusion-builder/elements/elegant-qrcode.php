<?php
if ( fusion_is_element_enabled( 'iee_qrcode' ) && ! class_exists( 'IEE_QRCode' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 3.3.5
	 */
	class IEE_QRCode extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 3.3.5
		 * @var array
		 */
		protected $args;

		/**
		 * Constructor.
		 *
		 * @since 3.3.5
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-qrcode', array( $this, 'attr' ) );
			add_shortcode( 'iee_qrcode', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 3.3.5
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			// Enqueue Script.
			wp_enqueue_script( 'infi-qrcode' );

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'type'           => 'link',
					'qr_full_name'   => '',
					'qr_url'         => '',
					'qr_phone'       => '',
					'qr_email'       => '',
					'qr_sms_number'  => '',
					'qr_sms_text'    => '',
					'qr_text'        => '',
					'qr_skype_id'    => '',
					'width'          => 320,
					'height'         => 320,
					'qr_color'       => '#000000',
					'hide_on_mobile' => fusion_builder_default_visibility( 'string' ),
					'class'          => '',
					'id'             => '',
				),
				$args
			);

			$defaults = apply_filters( 'fusion_builder_default_args', $defaults, 'iee_qrcode', $args );

			$this->args = $defaults;

			$this->args['random_id'] = wp_rand();

			$html = '';

			if ( '' !== locate_template( 'templates/qrcode/elegant-qrcode.php' ) ) {
				include locate_template( 'templates/qrcode/elegant-qrcode.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/qrcode/elegant-qrcode.php';
			}

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 3.3.5
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-qrcode',
				'style' => '',
			);

			$attr = fusion_builder_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$height         = FusionBuilder::validate_shortcode_attr_value( $this->args['height'], 'px' );
			$attr['style'] .= 'height:' . $height . ';';

			$width          = FusionBuilder::validate_shortcode_attr_value( $this->args['width'], 'px' );
			$attr['style'] .= 'width:' . $width . ';';

			if ( isset( $this->args['class'] ) ) {
				$attr['class'] .= ' ' . $this->args['class'];
			}

			if ( isset( $this->args['id'] ) ) {
				$attr['id'] = $this->args['id'];
			}

			return $attr;
		}
	}

	new IEE_QRCode();
} // End if().

/**
 * Map shortcode for qrcode.
 *
 * @since 3.3.5
 * @return void
 */
function map_elegant_elements_qrcode() {
	global $fusion_settings;

	fusion_builder_map(
		array(
			'name'      => esc_attr__( 'Elegant QRCode', 'elegant-elements' ),
			'shortcode' => 'iee_qrcode',
			'icon'      => 'fa-qrcode fas qrcode-icon',
			'front-end' => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-qrcode-preview.php',
			'params'    => array(
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Content Type', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the content type you want to embed into the QRCode.', 'elegant-elements' ),
					'param_name'  => 'type',
					'default'     => 'link',
					'value'       => array(
						'link'     => __( 'Link', 'elegant-elements' ),
						'vcard'    => __( 'Contact Info', 'elegant-elements' ),
						'phone'    => __( 'Phone Number', 'elegant-elements' ),
						'email'    => __( 'Email', 'elegant-elements' ),
						'sms'      => __( 'SMS', 'elegant-elements' ),
						'text'     => __( 'Text', 'elegant-elements' ),
						'skype'    => __( 'Open Skype Call', 'elegant-elements' ),
						'whatsapp' => __( 'Open WhatsApp', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Full Name', 'elegant-elements' ),
					'param_name'  => 'qr_full_name',
					'value'       => esc_attr__( 'John Doe', 'elegant-elements' ),
					'placeholder' => true,
					'description' => esc_attr__( 'Enter the full name for the contact info QRCode.', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'type',
							'value'    => 'vcard',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'link_selector',
					'heading'     => esc_attr__( 'URL', 'elegant-elements' ),
					'param_name'  => 'qr_url',
					'value'       => 'https://google.com',
					'placeholder' => true,
					'description' => esc_attr__( 'Enter the link for the QRCode. Enter url with http(s).', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'type',
							'value'    => 'link',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Phone Number', 'elegant-elements' ),
					'param_name'  => 'qr_phone',
					'value'       => '(091)012-345-678',
					'placeholder' => true,
					'description' => esc_attr__( 'Enter the phone number for the QRCode. Enter with country code.', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'type',
							'value'    => 'text',
							'operator' => '!=',
						),
						array(
							'element'  => 'type',
							'value'    => 'email',
							'operator' => '!=',
						),
						array(
							'element'  => 'type',
							'value'    => 'skype',
							'operator' => '!=',
						),
						array(
							'element'  => 'type',
							'value'    => 'sms',
							'operator' => '!=',
						),
						array(
							'element'  => 'type',
							'value'    => 'link',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Email', 'elegant-elements' ),
					'param_name'  => 'qr_email',
					'value'       => 'email@domain.com',
					'placeholder' => true,
					'description' => esc_attr__( 'Enter the email for the QRCode.', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'type',
							'value'    => 'text',
							'operator' => '!=',
						),
						array(
							'element'  => 'type',
							'value'    => 'phone',
							'operator' => '!=',
						),
						array(
							'element'  => 'type',
							'value'    => 'skype',
							'operator' => '!=',
						),
						array(
							'element'  => 'type',
							'value'    => 'sms',
							'operator' => '!=',
						),
						array(
							'element'  => 'type',
							'value'    => 'link',
							'operator' => '!=',
						),
						array(
							'element'  => 'type',
							'value'    => 'whatsapp',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'SMS Number', 'elegant-elements' ),
					'param_name'  => 'qr_sms_number',
					'value'       => '(091)012-345-678',
					'placeholder' => true,
					'description' => esc_attr__( 'Enter the mobile number to be set for sending SMS.', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'type',
							'value'    => 'sms',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'SMS Text', 'elegant-elements' ),
					'param_name'  => 'qr_sms_text',
					'value'       => esc_attr__( 'Default SMS text goes here', 'elegant-elements' ),
					'placeholder' => true,
					'description' => esc_attr__( 'Enter the mobile number to be set for sending SMS.', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'type',
							'value'    => 'text',
							'operator' => '!=',
						),
						array(
							'element'  => 'type',
							'value'    => 'email',
							'operator' => '!=',
						),
						array(
							'element'  => 'type',
							'value'    => 'phone',
							'operator' => '!=',
						),
						array(
							'element'  => 'type',
							'value'    => 'skype',
							'operator' => '!=',
						),
						array(
							'element'  => 'type',
							'value'    => 'vcard',
							'operator' => '!=',
						),
						array(
							'element'  => 'type',
							'value'    => 'link',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Text', 'elegant-elements' ),
					'param_name'  => 'qr_text',
					'value'       => esc_attr__( 'Your content goes here.', 'elegant-elements' ),
					'placeholder' => true,
					'description' => esc_attr__( 'Enter the text for the QRCode. Do not include HTML or text formatting of any kind.', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'type',
							'value'    => 'text',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Skype ID', 'elegant-elements' ),
					'param_name'  => 'qr_skype_id',
					'value'       => 'john.doe',
					'placeholder' => true,
					'description' => esc_attr__( 'Enter the skype ID you want to receive calls on.', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'type',
							'value'    => 'skype',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css height for the QRCode. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'height',
					'value'       => '320',
					'min'         => '100',
					'max'         => '1000',
					'step'        => '1',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Width', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css width for the QRCode. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'width',
					'value'       => '320',
					'min'         => '100',
					'max'         => '1000',
					'step'        => '1',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'QRCode Color', 'elegant-elements' ),
					'param_name'  => 'qr_color',
					'value'       => '#000000',
					'description' => esc_attr__( 'Controls the color of the QRCode.', 'elegant-elements' ),
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
		)
	);
}

add_action( 'fusion_builder_before_init', 'map_elegant_elements_qrcode', 99 );
