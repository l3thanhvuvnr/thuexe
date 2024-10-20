<?php
if ( fusion_is_element_enabled( 'iee_off_canvas_content' ) && ! class_exists( 'IEE_Off_Canvas_Content' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 3.2.0
	 */
	class IEE_Off_Canvas_Content extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 3.2.0
		 * @var array
		 */
		protected $args;

		/**
		 * Unique data identifier.
		 *
		 * @access protected
		 * @since 3.2.0
		 * @var array
		 */
		protected $uid;

		/**
		 * Unique data identifier.
		 *
		 * @access protected
		 * @since 3.2.0
		 * @var array
		 */
		protected $canvas_contents = array();

		/**
		 * Constructor.
		 *
		 * @since 3.2.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-off-canvas-content', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_elegant-off-canvas-content-wrapper', array( $this, 'attr_content_wrapper' ) );
			add_filter( 'fusion_attr_elegant-off-canvas-content-body', array( $this, 'attr_content_body' ) );
			add_filter( 'fusion_attr_elegant-off-canvas-trigger-icon', array( $this, 'attr_content_trigger_icon' ) );

			add_shortcode( 'iee_off_canvas_content', array( $this, 'render' ) );

			add_action( 'wp_footer', array( $this, 'render_off_canvas_content' ) );

			// Mechanism for frontend rendering.
			add_shortcode( 'iee_display_sidebar', array( $this, 'render_sidebar_content' ) );
		}

		/**
		 * Append the canvas content to the footer.
		 *
		 * @since 3.2.0
		 * @access public
		 */
		public function render_off_canvas_content() {
			if ( ! empty( $this->canvas_contents ) ) {
				foreach ( $this->canvas_contents as $canvas_content ) {
					echo do_shortcode( $canvas_content );
				}
				echo '<div class="elegant-off-canvas-overlay"></div>';
			}
		}

		/**
		 * Display the sidebar content.
		 *
		 * @access public
		 * @since 3.2.0
		 * @param array $args Shortcode attributes.
		 * @return string
		 */
		public function render_sidebar_content( $args ) {
			$sidebar = $args['sidebar'];

			ob_start();

			if ( function_exists( 'dynamic_sidebar' ) ) {
				dynamic_sidebar( $sidebar );
			}

			return ob_get_clean();
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 3.2.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			// Enqueue script.
			wp_enqueue_script( 'infi-off-canvas-content' );

			$this->uid = wp_rand();

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'canvas_id'               => 'off-canvas-id-' . $this->uid,
					'position'                => 'left',
					'content_source'          => 'custom',
					'content'                 => '',
					'content_template'        => '',
					'sidebar'                 => '',
					'width'                   => 400,
					'height'                  => 400,
					'content_animation'       => 'slide',
					'canvas_background_color' => 'rgba(0,0,0,0.8)',
					'canvas_text_color'       => '#ffffff',
					'content_padding'         => '',
					'trigger_source'          => 'text',
					'trigger_text'            => '',
					'trigger_image'           => '',
					'trigger_icon'            => '',
					'trigger_icon_color'      => '',
					'trigger_icon_size'       => 48,
					'trigger_alignment'       => 'left',
					'hide_on_mobile'          => fusion_builder_default_visibility( 'string' ),
					'class'                   => '',
					'id'                      => '',
				),
				$args
			);

			$defaults = apply_filters( 'fusion_builder_default_args', $defaults, 'iee_off_canvas_content', $args );

			$this->args = $defaults;

			$content = apply_filters( 'fusion_shortcode_content', $content, 'iee_off_canvas_content', $args );

			$html = '';

			if ( '' !== locate_template( 'templates/off-canvas-content/elegant-off-canvas-content.php' ) ) {
				include locate_template( 'templates/off-canvas-content/elegant-off-canvas-content.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/off-canvas-content/elegant-off-canvas-content.php';
			}

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 3.2.0
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-off-canvas-content',
				'style' => '',
			);

			$attr['class'] .= ' off-canvas-position-' . $this->args['position'];
			$attr['class'] .= ' elegant-align-' . $this->args['trigger_alignment'];
			$attr['class'] .= ' off-canvas-animation-' . $this->args['content_animation'];

			$attr['data-position']  = $this->args['position'];
			$attr['data-animation'] = $this->args['content_animation'];
			$attr['data-canvas-id'] = $this->args['canvas_id'];

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
		 * @since 3.2.0
		 * @return array
		 */
		public function attr_content_wrapper() {
			$attr = array(
				'class' => 'elegant-off-canvas-content-wrapper',
				'style' => '',
			);

			$attr['class'] .= ' off-canvas-content-' . $this->args['position'];
			$attr['class'] .= ' off-canvas-content-animation-' . $this->args['content_animation'];
			$attr['id']     = $this->args['canvas_id'];

			// For initial load, hide the element from screen.
			$attr['style'] .= 'visibility:hidden;';

			if ( 'top' == $this->args['position'] || 'bottom' == $this->args['position'] ) {
				$height         = FusionBuilder::validate_shortcode_attr_value( $this->args['height'], 'px' );
				$attr['style'] .= 'height:' . $height . ';';
			} else {
				$width          = FusionBuilder::validate_shortcode_attr_value( $this->args['width'], 'px' );
				$attr['style'] .= 'width:' . $width . ';';
			}

			if ( '' !== $this->args['canvas_background_color'] ) {
				$attr['style'] .= 'background:' . $this->args['canvas_background_color'] . ';';
			}

			if ( '' !== $this->args['canvas_text_color'] ) {
				$attr['style'] .= 'color:' . $this->args['canvas_text_color'] . ';';
				$attr['style'] .= 'fill:' . $this->args['canvas_text_color'] . ';';
			}

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 3.2.0
		 * @return array
		 */
		public function attr_content_body() {
			global $fusion_library;

			$attr = array(
				'class' => 'elegant-off-canvas-content-body',
				'style' => '',
			);

			if ( 'saved_template' === $this->args['content_source'] || 'custom' === $this->args['content_source'] ) {
				$attr['class'] .= ' post-content';
			} elseif ( 'sidebar' === $this->args['content_source'] ) {
				$attr['class'] .= ' fusion-footer';
			}

			// Set form styles.
			if ( $this->args['content_padding'] ) {
				$attr['style'] .= $fusion_library->sanitize->get_value_with_unit( $this->args['content_padding'] );
			}

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 3.2.0
		 * @return array
		 */
		public function attr_content_trigger_icon() {
			$attr = array(
				'class' => 'elegant-off-canvas-content-trigger-icon',
				'style' => '',
			);

			$icon_class     = ( function_exists( 'fusion_font_awesome_name_handler' ) ) ? fusion_font_awesome_name_handler( $this->args['trigger_icon'] ) : FusionBuilder::font_awesome_name_handler( $this->args['trigger_icon'] );
			$attr['class'] .= ' ' . $icon_class;

			if ( '' !== $this->args['trigger_icon_size'] ) {
				$trigger_icon_size = FusionBuilder::validate_shortcode_attr_value( $this->args['trigger_icon_size'], 'px' );
				$attr['style']     = 'font-size:' . $trigger_icon_size . ';';
			}

			if ( '' !== $this->args['trigger_icon_color'] ) {
				$attr['style'] .= 'color:' . $this->args['trigger_icon_color'] . ';';
			}

			return $attr;
		}

		/**
		 * Render the library element content.
		 *
		 * @access public
		 * @since 3.2.0
		 * @param array $args Shortcode paramters.
		 * @return string     HTML output.
		 */
		public function render_library_element( $args ) {
			$post_id               = $args['id'];
			$template_content_post = get_post( $post_id );
			$template_content      = $template_content_post->post_content;

			return $template_content;
		}
	}

	new IEE_Off_Canvas_Content();
} // End if().

/**
 * Map shortcode for off_canvas_content.
 *
 * @since 3.2.0
 * @return void
 */
function map_elegant_elements_off_canvas_content() {
	global $fusion_settings;

	$sidebars  = elegant_get_sidebars();
	$canvas_id = 'off-canvas-id-' . wp_rand();

	fusion_builder_map(
		array(
			'name'            => esc_attr__( 'Elegant Off-Canvas Content', 'elegant-elements' ),
			'shortcode'       => 'iee_off_canvas_content',
			'icon'            => 'fa-columns fas off-canvas-icon',
			'allow_generator' => true,
			'front-end'       => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-off-canvas-content-preview.php',
			'params'          => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Off-Canvas ID', 'elegant-elements' ),
					'param_name'  => 'canvas_id',
					'value'       => $canvas_id,
					'description' => esc_attr__( 'If you want to trigger the canvas display from custom links or menu, use class "elegant-off-canvas-trigger" for the link and this id as data-target attribute in child element for your link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_image_set',
					'heading'     => esc_attr__( 'Canvas Position', 'elegant-elements' ),
					'description' => esc_attr__( 'Select how you want the canvas content to appear.', 'elegant-elements' ),
					'param_name'  => 'position',
					'default'     => 'left',
					'value'       => array(
						'left'   => ELEGANT_ELEMENTS_PLUGIN_URL . '/assets/admin/img/from-left.jpg',
						'right'  => ELEGANT_ELEMENTS_PLUGIN_URL . '/assets/admin/img/from-right.jpg',
						'top'    => ELEGANT_ELEMENTS_PLUGIN_URL . '/assets/admin/img/from-top.jpg',
						'bottom' => ELEGANT_ELEMENTS_PLUGIN_URL . '/assets/admin/img/from-bottom.jpg',
					),
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Content Source', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the content source for this off-canvas element.', 'elegant-elements' ),
					'param_name'  => 'content_source',
					'default'     => 'custom',
					'value'       => array(
						'custom'         => __( 'Custom Content', 'elegant-elements' ),
						'saved_template' => __( 'Saved Content', 'elegant-elements' ),
						'sidebar'        => __( 'Sidebar', 'elegant-elements' ),
					),
				),
				array(
					'type'         => 'tinymce',
					'heading'      => esc_attr__( 'Off Canvas Content', 'elegant-elements' ),
					'param_name'   => 'element_content',
					'value'        => '',
					'dynamic_data' => true,
					'description'  => esc_attr__( 'Enter the content to be used for this off-canvas element.', 'elegant-elements' ),
					'dependency'   => array(
						array(
							'element'  => 'content_source',
							'value'    => 'custom',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'elegant_select_optgroup',
					'heading'     => esc_attr__( 'Select Saved Content', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the content template from saved elements library to display in the off-canvas content.', 'elegant-elements' ),
					'param_name'  => 'content_template',
					'value'       => elegant_get_library_collection(),
					'dependency'  => array(
						array(
							'element'  => 'content_source',
							'value'    => 'saved_template',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Sidebar', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the sidebar widget area you want to use for canvas content.', 'elegant-elements' ),
					'param_name'  => 'sidebar',
					'value'       => $sidebars,
					'dependency'  => array(
						array(
							'element'  => 'content_source',
							'value'    => 'sidebar',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Width', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css width for the canvas content. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'width',
					'value'       => '400',
					'min'         => '100',
					'max'         => '1500',
					'step'        => '1',
					'dependency'  => array(
						array(
							'element'  => 'position',
							'value'    => 'top',
							'operator' => '!=',
						),
						array(
							'element'  => 'position',
							'value'    => 'bottom',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css height for the canvas content. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'height',
					'value'       => '300',
					'min'         => '100',
					'max'         => '1500',
					'step'        => '1',
					'dependency'  => array(
						array(
							'element'  => 'position',
							'value'    => 'left',
							'operator' => '!=',
						),
						array(
							'element'  => 'position',
							'value'    => 'right',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Animation Style', 'elegant-elements' ),
					'description' => esc_attr__( 'Select how you want to display the off-canvas content when opening.', 'elegant-elements' ),
					'param_name'  => 'content_animation',
					'default'     => 'slide',
					'value'       => array(
						'slide'        => __( 'Slide', 'elegant-elements' ),
						'push_content' => __( 'Push', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Canvas Content Background Color', 'elegant-elements' ),
					'param_name'  => 'canvas_background_color',
					'value'       => 'rgba(0,0,0,0.8)',
					'description' => esc_attr__( 'Controls the background color for the canvas content sidebar.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Canvas Content Text Color', 'elegant-elements' ),
					'param_name'  => 'canvas_text_color',
					'value'       => '#ffffff',
					'description' => esc_attr__( 'Controls the text and links color in the canvas content.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'dimension',
					'heading'     => esc_attr__( 'Content Padding', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls space around the content. In pixels (px), ex: 10px.', 'elegant-elements' ),
					'param_name'  => 'content_padding',
					'value'       => array(
						'content_padding_top'    => '',
						'content_padding_right'  => '',
						'content_padding_bottom' => '',
						'content_padding_left'   => '',
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Trigger Source', 'elegant-elements' ),
					'description' => esc_attr__( 'Select how you want to trigger the off-canvas content display.', 'elegant-elements' ),
					'param_name'  => 'trigger_source',
					'default'     => 'text',
					'value'       => array(
						'text'  => __( 'Text', 'elegant-elements' ),
						'image' => __( 'Image', 'elegant-elements' ),
						'icon'  => __( 'Icon', 'elegant-elements' ),
					),
					'group'       => esc_attr__( 'Trigger', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Enter Trigger Text', 'elegant-elements' ),
					'param_name'  => 'trigger_text',
					'value'       => esc_attr__( 'Display Off-canvas Content', 'elegant-elements' ),
					'description' => esc_attr__( 'Triggers the off-canvas content display after click on this text.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Trigger', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'trigger_source',
							'value'    => 'text',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'upload',
					'heading'     => esc_attr__( 'Trigger Image', 'elegant-elements' ),
					'param_name'  => 'trigger_image',
					'value'       => '',
					'description' => esc_attr__( 'Triggers the off-canvas content display after click on this image.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Trigger', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'trigger_source',
							'value'    => 'image',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_attr__( 'Trigger Icon', 'elegant-elements' ),
					'param_name'  => 'trigger_icon',
					'value'       => '',
					'description' => esc_attr__( 'Triggers the off-canvas content display after click on this icon.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Trigger', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'trigger_source',
							'value'    => 'icon',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Trigger Icon Color', 'elegant-elements' ),
					'param_name'  => 'trigger_icon_color',
					'value'       => '',
					'description' => esc_attr__( 'Controls the trigger icon color.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Trigger', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'trigger_source',
							'value'    => 'icon',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Trigger Icon Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the size for the canvas display trigger icon. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'trigger_icon_size',
					'value'       => '48',
					'min'         => '10',
					'max'         => '200',
					'step'        => '1',
					'group'       => esc_attr__( 'Trigger', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'trigger_source',
							'value'    => 'icon',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Trigger Content Alignment', 'elegant-elements' ),
					'param_name'  => 'trigger_alignment',
					'default'     => 'left',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'center' => esc_attr__( 'Center', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Align the trigger content to left, right or center.', 'elegant-elements' ),
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

add_action( 'wp_loaded', 'map_elegant_elements_off_canvas_content', 99 );
