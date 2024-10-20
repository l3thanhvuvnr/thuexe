<?php
if ( fusion_is_element_enabled( 'iee_modal_dialog' ) && ! class_exists( 'IEE_Modal_Dialog' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class IEE_Modal_Dialog extends Fusion_Element {

		/**
		 * The modals counter.
		 *
		 * @access private
		 * @since 1.0
		 * @var int
		 */
		private $modal_counter = 1;

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

			add_filter( 'fusion_attr_infi-modal-shortcode', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_infi-modal-shortcode-dialog', array( $this, 'dialog_attr' ) );
			add_filter( 'fusion_attr_infi-modal-shortcode-content', array( $this, 'content_attr' ) );
			add_filter( 'fusion_attr_infi-modal-shortcode-heading', array( $this, 'heading_attr' ) );
			add_filter( 'fusion_attr_infi-modal-shortcode-header', array( $this, 'header_attr' ) );
			add_filter( 'fusion_attr_infi-modal-shortcode-body', array( $this, 'body_attr' ) );
			add_filter( 'fusion_attr_infi-modal-shortcode-footer', array( $this, 'footer_attr' ) );
			add_filter( 'fusion_attr_infi-modal-shortcode-button', array( $this, 'button_attr' ) );
			add_filter( 'fusion_attr_infi-modal-shortcode-button-footer', array( $this, 'button_footer_attr' ) );
			add_filter( 'fusion_attr_infi-modal-trigger', array( $this, 'modal_trigger' ) );

			add_shortcode( 'iee_modal_dialog', array( $this, 'render' ) );
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
				$args['typography_title']         = $default_typography['title'];
				$args['typography_content']       = $default_typography['description'];
				$args['typography_footer_button'] = $default_typography['description'];
			}

			$args['footer_button_size']    = ( isset( $args['footer_button_size'] ) ) ? $args['footer_button_size'] : '';
			$args['footer_button_stretch'] = ( isset( $args['footer_button_stretch'] ) ) ? $args['footer_button_stretch'] : '';

			$this->args = $args;

			$html = '';

			if ( '' !== locate_template( 'templates/modal-dialog/elegant-modal-dialog.php' ) ) {
				include locate_template( 'templates/modal-dialog/elegant-modal-dialog.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/modal-dialog/elegant-modal-dialog.php';
			}

			$this->modal_counter++;

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
				'class'                => 'elegant-modal fusion-modal modal fade modal-' . $this->modal_counter,
				'tabindex'             => '-1',
				'role'                 => 'dialog',
				'aria-labelledby'      => 'modal-heading-' . $this->modal_counter,
				'aria-hidden'          => 'true',
				'data-animation-start' => ( isset( $this->args['entry_animation'] ) && '' !== $this->args['entry_animation'] ) ? 'infi-' . $this->args['entry_animation'] : 'infi-fadeIn',
				'data-animation-exit'  => ( isset( $this->args['exit_animation'] ) && '' !== $this->args['exit_animation'] ) ? 'infi-' . $this->args['exit_animation'] : 'infi-fadeOut',
			);

			if ( $this->args['name'] ) {
				$attr['class'] .= ' ' . $this->args['name'];
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
		 * Builds the dialog attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function dialog_attr() {

			$attr = array(
				'class' => 'elegant-modal-dialog modal-dialog',
			);

			$modal_size        = ( 'small' == $this->args['size'] ) ? ' modal-sm' : ' modal-lg';
			$attr['class']    .= $modal_size;
			$attr['data-size'] = $modal_size;

			if ( isset( $this->args['modal_width'] ) && '' !== $this->args['modal_width'] && 'custom' === $this->args['size'] ) {
				$attr['style']  = 'width:' . FusionBuilder::validate_shortcode_attr_value( $this->args['modal_width'], 'px' ) . ';';
				$attr['style'] .= 'max-width:' . FusionBuilder::validate_shortcode_attr_value( $this->args['modal_width'], 'px' ) . ';';
			}

			return $attr;

		}

		/**
		 * Builds the content attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function content_attr() {

			$attr = array(
				'class' => 'elegant-modal-content modal-content fusion-modal-content',
			);

			if ( isset( $this->args['body_background'] ) && '' !== $this->args['body_background'] ) {
				$attr['style'] = 'background-color:' . $this->args['body_background'];
			}

			return $attr;

		}

		/**
		 * Builds the button attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function button_attr() {
			$attr = array(
				'class'        => 'close',
				'type'         => 'button',
				'data-dismiss' => 'modal',
				'aria-hidden'  => 'true',
				'style'        => '',
			);

			if ( isset( $this->args['title_color'] ) && '' !== $this->args['title_color'] ) {
				$attr['style'] .= 'color:' . $this->args['title_color'] . ';';
			}

			return $attr;
		}

		/**
		 * Builds the heading attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function heading_attr() {
			$attr = array(
				'class'        => 'elegant-modal-title modal-title',
				'id'           => 'modal-heading-' . $this->modal_counter,
				'data-dismiss' => 'modal',
				'aria-hidden'  => 'true',
				'style'        => '',
			);

			if ( isset( $this->args['title_color'] ) && '' !== $this->args['title_color'] ) {
				$attr['style'] .= 'color:' . $this->args['title_color'] . ';';
			}

			if ( isset( $this->args['header_title_alignment'] ) ) {
				$attr['class'] .= ' elegant-align-' . $this->args['header_title_alignment'];
			}

			if ( isset( $this->args['typography_title'] ) && '' !== $this->args['typography_title'] ) {
				$typography       = $this->args['typography_title'];
				$title_typography = elegant_get_typography_css( $typography );

				$attr['style'] .= $title_typography;
			}

			if ( isset( $this->args['title_font_size'] ) && '' !== $this->args['title_font_size'] ) {
				$attr['style'] .= 'font-size:' . FusionBuilder::validate_shortcode_attr_value( $this->args['title_font_size'], 'px' ) . ';';
			}

			return $attr;
		}

		/**
		 * Builds the heading attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function header_attr() {
			$attr = array(
				'class'       => 'elegant-modal-header modal-header',
				'aria-hidden' => 'true',
				'style'       => '',
			);

			if ( isset( $this->args['header_background'] ) && '' !== $this->args['header_background'] ) {
				$attr['style'] .= 'background-color:' . $this->args['header_background'];
			}

			return $attr;
		}

		/**
		 * Builds the heading attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function footer_attr() {
			$attr = array(
				'class'       => 'elegant-modal-footer modal-footer',
				'aria-hidden' => 'true',
				'style'       => '',
			);

			if ( isset( $this->args['footer_background'] ) && '' !== $this->args['footer_background'] ) {
				$attr['style'] .= 'background-color:' . $this->args['footer_background'];
			}

			return $attr;
		}

		/**
		 * Builds the heading attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function body_attr() {
			$attr = array(
				'class'       => 'elegant-modal-body modal-body',
				'aria-hidden' => 'true',
				'style'       => '',
			);

			if ( isset( $this->args['typography_content'] ) && '' !== $this->args['typography_content'] ) {
				$typography         = $this->args['typography_content'];
				$content_typography = elegant_get_typography_css( $typography );

				$attr['style'] .= $content_typography;
			}

			if ( isset( $this->args['content_font_size'] ) && '' !== $this->args['content_font_size'] ) {
				$attr['style'] .= 'font-size:' . FusionBuilder::validate_shortcode_attr_value( $this->args['content_font_size'], 'px' ) . ';';
			}

			return $attr;
		}

		/**
		 * Builds the button attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function button_footer_attr() {
			$attr = array(
				'class'        => 'elegant-modal-dialog-button-wrapper',
				'data-dismiss' => 'modal',
				'style'        => '',
			);

			if ( isset( $this->args['typography_footer_button'] ) && '' !== $this->args['typography_footer_button'] ) {
				$typography        = $this->args['typography_footer_button'];
				$button_typography = elegant_get_typography_css( $typography );

				$attr['style'] .= $button_typography;
			}

			return $attr;
		}

		/**
		 * Builds the button attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function modal_trigger() {
			$attr = array(
				'class'       => 'elegant-modal-trigger',
				'data-toggle' => 'modal',
				'data-target' => '.elegant-modal.' . $this->args['name'],
			);

			return $attr;
		}

		/**
		 * Sets the necessary scripts.
		 *
		 * @access public
		 * @since 1.1
		 * @return void
		 */
		public function add_scripts() {
			global $elegant_js_folder_url, $elegant_js_folder_path, $elegant_css_folder_url, $elegant_css_folder_path;

			Fusion_Dynamic_JS::enqueue_script(
				'fusion-modal',
				FusionBuilder::$js_folder_url . '/general/fusion-modal.js',
				FusionBuilder::$js_folder_path . '/general/fusion-modal.js',
				array( 'bootstrap-modal' ),
				'1',
				true
			);

			Fusion_Dynamic_JS::enqueue_script(
				'infi-elegant-modal-dialog',
				$elegant_js_folder_url . '/infi-elegant-modal-dialog.min.js',
				$elegant_js_folder_path . '/infi-elegant-modal-dialog.min.js',
				array( 'jquery' ),
				'1',
				true
			);
		}
	}

	new IEE_Modal_Dialog();
} // End if().


/**
 * Map shortcode for modal_dialog.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_modal_dialog() {
	global $fusion_settings;

	// Get default typography for title and description.
	$default_typography = elegant_get_default_typography();

	fusion_builder_map(
		array(
			'name'                      => esc_attr__( 'Elegant Modal Dialog', 'elegant-elements' ),
			'shortcode'                 => 'iee_modal_dialog',
			'icon'                      => 'fusiona-external-link',
			'preview'                   => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/previews/infi-modal-dialog-preview.php',
			'preview_id'                => 'elegant-elements-module-infi-modal-dialog-preview-template',
			'admin_enqueue_js'          => ELEGANT_ELEMENTS_PLUGIN_URL . 'elements/custom/js/elegant-modal-dialog-filter.js',
			'on_save'                   => 'elegantModalDialogShortcodeFilter',
			'custom_settings_view_name' => 'ModuleSettingElegantView',
			'inline_editor'             => true,
			'params'                    => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Name Of Modal', 'elegant-elements' ),
					'description' => esc_attr__( 'Needs to be a unique identifier (lowercase), used for button or modal_text_link element to open the modal. ex: mymodal.', 'elegant-elements' ),
					'param_name'  => 'name',
					'value'       => '',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Modal Heading', 'elegant-elements' ),
					'description' => esc_attr__( 'Heading text for the modal.', 'elegant-elements' ),
					'param_name'  => 'title',
					'value'       => esc_attr__( 'Your Content Goes Here', 'elegant-elements' ),
					'placeholder' => true,
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Size Of Modal', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the modal window size.', 'elegant-elements' ),
					'param_name'  => 'size',
					'value'       => array(
						'small'  => esc_attr__( 'Small', 'elegant-elements' ),
						'large'  => esc_attr__( 'Large', 'elegant-elements' ),
						'custom' => esc_attr__( 'Custom Width', 'elegant-elements' ),
					),
					'default'     => 'small',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Modal Custom Width', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css width to create empty space between two elements. In Pixel (px). eg. 400px.', 'elegant-elements' ),
					'param_name'  => 'modal_width',
					'value'       => '400px',
					'dependency'  => array(
						array(
							'element'  => 'size',
							'value'    => 'custom',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'tinymce',
					'heading'     => esc_attr__( 'Contents of Modal', 'elegant-elements' ),
					'description' => esc_attr__( 'Add your content to be displayed in modal.', 'elegant-elements' ),
					'param_name'  => 'element_content',
					'value'       => esc_attr__( 'Your Content Goes Here', 'elegant-elements' ),
					'placeholder' => true,
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Header Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the modal header background color. ', 'elegant-elements' ),
					'param_name'  => 'header_background',
					'value'       => '',
					'default'     => '',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Header Title Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the modal header title text background color. ', 'elegant-elements' ),
					'param_name'  => 'title_color',
					'value'       => '',
					'default'     => '',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Title Alignmenr', 'elegant-elements' ),
					'description' => esc_attr__( 'Control the modal title alignment.', 'elegant-elements' ),
					'param_name'  => 'header_title_alignment',
					'default'     => 'left',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'center' => esc_attr__( 'Center', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Body Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the modal body background color. ', 'elegant-elements' ),
					'param_name'  => 'body_background',
					'value'       => '',
					'default'     => '',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Footer Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the modal footer background color. ', 'elegant-elements' ),
					'param_name'  => 'footer_background',
					'value'       => '',
					'default'     => '',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Border Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the modal border color. ', 'elegant-elements' ),
					'param_name'  => 'border_color',
					'value'       => '',
					'default'     => '',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Show Footer', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose to show the modal footer with close button.', 'elegant-elements' ),
					'param_name'  => 'show_footer',
					'value'       => array(
						'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
						'no'  => esc_attr__( 'No', 'elegant-elements' ),
					),
					'default'     => 'yes',
					'group'       => esc_attr__( 'Footer', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Button Text', 'elegant-elements' ),
					'param_name'  => 'button_title',
					'value'       => esc_attr__( 'Close', 'elegant-elements' ),
					'placeholder' => true,
					'description' => esc_attr__( 'Set a title attribute for the button link.', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'show_footer',
							'value'    => 'no',
							'operator' => '!=',
						),
					),
					'group'       => esc_attr__( 'Footer', 'elegant-elements' ),
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Button Style', 'elegant-elements' ),
					'description' => esc_attr__( "Select the button's color. Select default or color name for theme options, or select custom to use advanced color options below.", 'elegant-elements' ),
					'param_name'  => 'button_color',
					'value'       => array(
						'default'   => esc_attr__( 'Default', 'elegant-elements' ),
						'custom'    => esc_attr__( 'Custom', 'elegant-elements' ),
						'green'     => esc_attr__( 'Green', 'elegant-elements' ),
						'darkgreen' => esc_attr__( 'Dark Green', 'elegant-elements' ),
						'orange'    => esc_attr__( 'Orange', 'elegant-elements' ),
						'blue'      => esc_attr__( 'Blue', 'elegant-elements' ),
						'red'       => esc_attr__( 'Red', 'elegant-elements' ),
						'pink'      => esc_attr__( 'Pink', 'elegant-elements' ),
						'darkgray'  => esc_attr__( 'Dark Gray', 'elegant-elements' ),
						'lightgray' => esc_attr__( 'Light Gray', 'elegant-elements' ),
					),
					'default'     => 'default',
					'dependency'  => array(
						array(
							'element'  => 'show_footer',
							'value'    => 'no',
							'operator' => '!=',
						),
					),
					'group'       => esc_attr__( 'Footer', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Button Gradient Top Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the top color of the button background.', 'elegant-elements' ),
					'param_name'  => 'button_gradient_top_color',
					'value'       => '',
					'group'       => esc_attr__( 'Footer', 'elegant-elements' ),
					'default'     => $fusion_settings->get( 'button_gradient_top_color' ),
					'dependency'  => array(
						array(
							'element'  => 'button_color',
							'value'    => 'custom',
							'operator' => '==',
						),
						array(
							'element'  => 'show_footer',
							'value'    => 'no',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Button Gradient Bottom Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the bottom color of the button background.', 'elegant-elements' ),
					'param_name'  => 'button_gradient_bottom_color',
					'value'       => '',
					'group'       => esc_attr__( 'Footer', 'elegant-elements' ),
					'default'     => $fusion_settings->get( 'button_gradient_bottom_color' ),
					'dependency'  => array(
						array(
							'element'  => 'button_color',
							'value'    => 'custom',
							'operator' => '==',
						),
						array(
							'element'  => 'show_footer',
							'value'    => 'no',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Button Gradient Top Hover Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the top hover color of the button background.', 'elegant-elements' ),
					'param_name'  => 'button_gradient_top_color_hover',
					'value'       => '',
					'group'       => esc_attr__( 'Footer', 'elegant-elements' ),
					'default'     => $fusion_settings->get( 'button_gradient_top_color_hover' ),
					'dependency'  => array(
						array(
							'element'  => 'button_color',
							'value'    => 'custom',
							'operator' => '==',
						),
						array(
							'element'  => 'show_footer',
							'value'    => 'no',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Button Gradient Bottom Hover Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the bottom hover color of the button background.', 'elegant-elements' ),
					'param_name'  => 'button_gradient_bottom_color_hover',
					'value'       => '',
					'group'       => esc_attr__( 'Footer', 'elegant-elements' ),
					'default'     => $fusion_settings->get( 'button_gradient_bottom_color_hover' ),
					'dependency'  => array(
						array(
							'element'  => 'button_color',
							'value'    => 'custom',
							'operator' => '==',
						),
						array(
							'element'  => 'show_footer',
							'value'    => 'no',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Button Accent Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the color of the button border, divider, text and icon.', 'elegant-elements' ),
					'param_name'  => 'accent_color',
					'value'       => '',
					'group'       => esc_attr__( 'Footer', 'elegant-elements' ),
					'default'     => $fusion_settings->get( 'button_accent_color' ),
					'dependency'  => array(
						array(
							'element'  => 'button_color',
							'value'    => 'custom',
							'operator' => '==',
						),
						array(
							'element'  => 'show_footer',
							'value'    => 'no',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Button Accent Hover Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the hover color of the button border, divider, text and icon.', 'elegant-elements' ),
					'param_name'  => 'accent_hover_color',
					'value'       => '',
					'group'       => esc_attr__( 'Footer', 'elegant-elements' ),
					'default'     => $fusion_settings->get( 'button_accent_hover_color' ),
					'dependency'  => array(
						array(
							'element'  => 'button_color',
							'value'    => 'custom',
							'operator' => '==',
						),
						array(
							'element'  => 'show_footer',
							'value'    => 'no',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Button Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the button size.', 'elegant-elements' ),
					'param_name'  => 'footer_button_size',
					'default'     => '',
					'group'       => esc_attr__( 'Footer', 'elegant-elements' ),
					'value'       => array(
						''       => esc_attr__( 'Default', 'elegant-elements' ),
						'small'  => esc_attr__( 'Small', 'elegant-elements' ),
						'medium' => esc_attr__( 'Medium', 'elegant-elements' ),
						'large'  => esc_attr__( 'Large', 'elegant-elements' ),
						'xlarge' => esc_attr__( 'XLarge', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Button Span', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls if the button spans the full width of its container.', 'elegant-elements' ),
					'param_name'  => 'footer_button_stretch',
					'default'     => 'default',
					'group'       => esc_attr__( 'Footer', 'elegant-elements' ),
					'value'       => array(
						'default' => esc_attr__( 'Default', 'elegant-elements' ),
						'yes'     => esc_attr__( 'Yes', 'elegant-elements' ),
						'no'      => esc_attr__( 'No', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'elegant_select_optgroup',
					'heading'     => esc_attr__( 'Entry Animation', 'elegant-elements' ),
					'param_name'  => 'entry_animation',
					'value'       => elegant_get_entry_animations(),
					'description' => esc_attr__( 'Select the animation that is applied when modal is opened.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Animations', 'elegant-elements' ),
				),
				array(
					'type'        => 'elegant_select_optgroup',
					'heading'     => esc_attr__( 'Exit Animation', 'elegant-elements' ),
					'param_name'  => 'exit_animation',
					'value'       => elegant_get_exit_animations(),
					'description' => esc_attr__( 'Select the animation that is applied when modal is closing.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Animations', 'elegant-elements' ),
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
					'heading'     => esc_attr__( 'Heading Title Typography', 'elegant-elements' ),
					'param_name'  => 'typography_title',
					'value'       => '',
					'default'     => $default_typography['title'],
					'description' => esc_attr__( 'Select the typography for the heading title.', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'element_typography',
							'value'    => 'default',
							'operator' => '!=',
						),
					),
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Title Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for modal title. In Pixel (px).', 'elegant-elements' ),
					'param_name'  => 'title_font_size',
					'value'       => '24',
					'min'         => '12',
					'max'         => '100',
					'step'        => '1',
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
				),
				array(
					'type'        => 'elegant_typography',
					'heading'     => esc_attr__( 'Content Typography', 'elegant-elements' ),
					'param_name'  => 'typography_content',
					'value'       => '',
					'default'     => $default_typography['description'],
					'description' => esc_attr__( 'Select the typography for the modal content.', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'element_typography',
							'value'    => 'default',
							'operator' => '!=',
						),
					),
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Content Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for modal content paragraphs. In Pixel (px).', 'elegant-elements' ),
					'param_name'  => 'content_font_size',
					'value'       => '18',
					'min'         => '12',
					'max'         => '100',
					'step'        => '1',
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
				),
				array(
					'type'        => 'elegant_typography',
					'heading'     => esc_attr__( 'Footer Button Typography', 'elegant-elements' ),
					'param_name'  => 'typography_footer_button',
					'value'       => '',
					'default'     => $default_typography['description'],
					'description' => esc_attr__( 'Select the typography for the modal footer button.', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'element_typography',
							'value'    => 'default',
							'operator' => '!=',
						),
					),
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Open modal with', 'elegant-elements' ),
					'param_name'  => 'modal_trigger',
					'default'     => 'none',
					'value'       => array(
						'none'   => esc_attr__( 'None', 'elegant-elements' ),
						'icon'   => esc_attr__( 'FontAwesome Icon', 'elegant-elements' ),
						'button' => esc_attr__( 'Button Element', 'elegant-elements' ),
						'image'  => esc_attr__( 'Image', 'elegant-elements' ),
						'text'   => esc_attr__( 'Custom Text', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Select how you want the modal to open. Select none to use the modal name to open from your custom trigger.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Trigger', 'elegant-elements' ),
				),
				array(
					'type'        => 'raw_textarea',
					'heading'     => esc_attr__( 'FontAwesome Icon Shortcode', 'elegant-elements' ),
					'param_name'  => 'icon_shortcode',
					'value'       => '',
					'description' => __( 'Click the link to generate icon shortcode to open modal. Please remove the previous shortcode before generating new one.<p><a href="#" class="elegant-elements-add-shortcode" data-type="fusion_fontawesome" data-editor-id="icon_shortcode">Generate Icon Shortcode</a></p>', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'modal_trigger',
							'value'    => 'icon',
							'operator' => '==',
						),
					),
					'group'       => esc_attr__( 'Trigger', 'elegant-elements' ),
				),
				array(
					'type'        => 'raw_textarea',
					'heading'     => esc_attr__( 'Button Shortcode', 'elegant-elements' ),
					'param_name'  => 'button_shortcode',
					'value'       => '',
					'description' => __( 'Click the link to generate button shortcode to open modal. Please remove the previous shortcode before generating new one.<p><a href="#" class="elegant-elements-add-shortcode" data-type="fusion_button" data-editor-id="button_shortcode">Generate Button Shortcode</a></p>', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'modal_trigger',
							'value'    => 'button',
							'operator' => '==',
						),
					),
					'group'       => esc_attr__( 'Trigger', 'elegant-elements' ),
				),
				array(
					'type'        => 'upload',
					'heading'     => esc_attr__( 'Image', 'elegant-elements' ),
					'param_name'  => 'image_url',
					'value'       => '',
					'description' => esc_attr__( 'Select or upload image to open modal.', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'modal_trigger',
							'value'    => 'image',
							'operator' => '==',
						),
					),
					'group'       => esc_attr__( 'Trigger', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Custom Text', 'elegant-elements' ),
					'param_name'  => 'custom_text',
					'value'       => '',
					'description' => esc_attr__( 'Enter text to open modal.', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'modal_trigger',
							'value'    => 'text',
							'operator' => '==',
						),
					),
					'group'       => esc_attr__( 'Trigger', 'elegant-elements' ),
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_modal_dialog', 99 );
