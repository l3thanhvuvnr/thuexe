<?php
if ( fusion_is_element_enabled( 'iee_instagram_profile_card' ) && ! class_exists( 'IEE_Instagram_Profile_Card' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 2.5
	 */
	class IEE_Instagram_Profile_Card extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 2.5
		 * @var array
		 */
		protected $args;

		/**
		 * Constructor.
		 *
		 * @since 2.5
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-instagram-profile-card', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_elegant-instagram-profile-card-follow-button', array( $this, 'button_attr' ) );

			add_shortcode( 'iee_instagram_profile_card', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 2.5
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			wp_enqueue_style( 'infi-elegant-carousel' );

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'username'                      => 'unsplash',
					'number_of_images'              => 4,
					'button_text_color'             => '#333333',
					'button_background_color'       => '#ffffff',
					'button_text_color_hover'       => '#ffffff',
					'button_background_color_hover' => '#333333',
					'hide_on_mobile'                => fusion_builder_default_visibility( 'string' ),
					'class'                         => '',
					'id'                            => '',
				),
				$args
			);

			$defaults = apply_filters( 'fusion_builder_default_args', $defaults, 'iee_instagram_profile_card', $args );

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/instagram-profile-card/elegant-instagram-profile-card.php' ) ) {
				include locate_template( 'templates/instagram-profile-card/elegant-instagram-profile-card.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/instagram-profile-card/elegant-instagram-profile-card.php';
			}

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 2.5
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-instagram-profile-card',
				'style' => '',
			);

			$attr = fusion_builder_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$attr['class'] .= ' fusion-clearfix';

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
		 * @since 2.5
		 * @return array
		 */
		public function button_attr() {
			$attr = array(
				'class' => 'button follow-button',
				'style' => '',
			);

			$attr['style'] .= '--color:' . $this->args['button_text_color'] . ';';
			$attr['style'] .= '--color-hover:' . $this->args['button_text_color_hover'] . ';';
			$attr['style'] .= '--background-color:' . $this->args['button_background_color'] . ';';
			$attr['style'] .= '--background-color-hover:' . $this->args['button_background_color_hover'] . ';';

			return $attr;
		}

		/**
		 * Sets the necessary scripts.
		 *
		 * @access public
		 * @since 2.5
		 * @return void
		 */
		public function add_scripts() {
			global $elegant_js_folder_url, $elegant_js_folder_path;

			Fusion_Dynamic_JS::enqueue_script(
				'infi-elegant-carousel',
				$elegant_js_folder_url . '/infi-elegant-carousel.min.js',
				$elegant_js_folder_path . '/infi-elegant-carousel.min.js',
				array( 'jquery' ),
				'1',
				true
			);
		}
	}

	new IEE_Instagram_Profile_Card();
} // End if().

/**
 * Map shortcode for instagram_profile_card.
 *
 * @since 2.5
 * @return void
 */
function map_elegant_elements_instagram_profile_card() {
	global $fusion_settings;

	$api_data    = get_option( 'elegant_elements_instagram_api_data', array() );
	$name_hidden = isset( $api_data['access_token'] ) ? 'hidden' : '!hidden';
	$username    = ( isset( $api_data['username'] ) ) ? $api_data['username'] : '';

	fusion_builder_map(
		array(
			'name'      => esc_attr__( 'Elegant Instagram Profile Card', 'elegant-elements' ),
			'shortcode' => 'iee_instagram_profile_card',
			'icon'      => 'fa-instagram fab instagram-profile-card-icon',
			'front-end' => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-instagram-profile-card-preview.php',
			'params'    => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Instagram Username', 'elegant-elements' ),
					'param_name'  => 'username',
					'value'       => $username,
					'placeholder' => true,
					$name_hidden  => true,
					'description' => esc_attr__( 'Enter the instagram username without "@" to show the teaser box for the user.', 'elegant-elements' ),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Number of Images', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the number of images being displayed in the profile header slider.', 'elegant-elements' ),
					'param_name'  => 'number_of_images',
					'value'       => '4',
					'min'         => '1',
					'max'         => '15',
					'step'        => '1',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Follow Button Text Color', 'elegant-elements' ),
					'param_name'  => 'button_text_color',
					'value'       => '#333333',
					'description' => esc_attr__( 'Controls the follow button text color.', 'elegant-elements' ),
					'group'       => __( 'Follow Button', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Follow Button Background Color', 'elegant-elements' ),
					'param_name'  => 'button_background_color',
					'value'       => '#ffffff',
					'description' => esc_attr__( 'Controls the follow button background color.', 'elegant-elements' ),
					'group'       => __( 'Follow Button', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Follow Button Text Color on Hover', 'elegant-elements' ),
					'param_name'  => 'button_text_color_hover',
					'value'       => '#ffffff',
					'description' => esc_attr__( 'Controls the follow button text color on hover.', 'elegant-elements' ),
					'group'       => __( 'Follow Button', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Follow Button Background Color on Hover', 'elegant-elements' ),
					'param_name'  => 'button_background_color_hover',
					'value'       => '#333333',
					'description' => esc_attr__( 'Controls the follow button text color.', 'elegant-elements' ),
					'group'       => __( 'Follow Button', 'elegant-elements' ),
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_instagram_profile_card', 99 );
