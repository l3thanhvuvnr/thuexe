<?php
if ( fusion_is_element_enabled( 'iee_advanced_video' ) && ! class_exists( 'IEE_Advanced_Video' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 2.3
	 */
	class IEE_Advanced_Video extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 2.3
		 * @var array
		 */
		protected $args;

		/**
		 * Constructor.
		 *
		 * @since 2.3
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-advanced-video', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_elegant-advanced-video-image', array( $this, 'attr_image' ) );
			add_filter( 'fusion_attr_elegant-advanced-video-icon', array( $this, 'attr_icon' ) );

			add_shortcode( 'iee_advanced_video', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 2.3
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'video_provider'           => 'youtube',
					'video_id'                 => 'il2ZAZX9KpQ',
					'video_file'               => '',
					'image'                    => '',
					'image_retina'             => '',
					'width'                    => 320,
					'alignment'                => 'left',
					'image_overlay'            => 'rgba(0,0,0,0.3)',
					'icon_type'                => 'icon',
					'video_play_icon'          => 'fa-play fas',
					'icon_color'               => '#ffffff',
					'icon_font_size'           => 32,
					'image_icon'               => '',
					'youtube_subscribe'        => 'no',
					'youtube_channel'          => 'GoogleDevelopers',
					'subscribe_text'           => esc_attr__( 'Subscribe to our YouTube channel.', 'elegant-elements' ),
					'subscribe_bar_background' => '#666666',
					'subscribe_bar_text_color' => '#ffffff',
					'hide_on_mobile'           => fusion_builder_default_visibility( 'string' ),
					'class'                    => '',
					'id'                       => '',
				),
				$args
			);

			$defaults = apply_filters( 'fusion_builder_default_args', $defaults, 'iee_advanced_video', $args );

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/advanced-video/elegant-advanced-video.php' ) ) {
				include locate_template( 'templates/advanced-video/elegant-advanced-video.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/advanced-video/elegant-advanced-video.php';
			}

			return $html;
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
				'class' => 'elegant-advanced-video',
			);

			$attr['class'] .= ' fusion-align' . $this->args['alignment'];

			$attr = fusion_builder_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$attr['style'] = 'max-width:' . FusionBuilder::validate_shortcode_attr_value( $this->args['width'], 'px' ) . ';';

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
		public function attr_image() {
			$attr = array(
				'class' => 'elegant-advanced-video-preview',
			);

			$attr['src'] = $this->args['image'];

			if ( isset( $this->args['image_retina'] ) && '' !== $this->args['image_retina'] ) {
				$attr['srcset']  = $this->args['image'] . ' 1x, ';
				$attr['srcset'] .= $this->args['image_retina'] . ' 2x ';
			}

			$attr['style'] = 'max-width:' . FusionBuilder::validate_shortcode_attr_value( $this->args['width'], 'px' ) . ';';

			$video_id               = ( 'hosted' === $this->args['video_provider'] ) ? $this->args['video_file'] : $this->args['video_id'];
			$attr['data-embed-url'] = elegant_get_embed_url_by_provider( $this->args['video_provider'], $video_id, true );

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 2.3
		 * @return array
		 */
		public function attr_icon() {
			$attr = array(
				'class' => 'elegant-advanced-video-play-icon',
				'style' => '',
			);

			$icon_class     = ( function_exists( 'fusion_font_awesome_name_handler' ) ) ? fusion_font_awesome_name_handler( $this->args['video_play_icon'] ) : FusionBuilder::font_awesome_name_handler( $this->args['video_play_icon'] );
			$attr['class'] .= ' ' . $icon_class;

			$attr['style'] .= 'color: ' . $this->args['icon_color'] . ';';
			$attr['style'] .= 'font-size: ' . $this->args['icon_font_size'] . 'px;';

			return $attr;
		}

		/**
		 * Sets the necessary scripts.
		 *
		 * @access public
		 * @since 2.3
		 * @return void
		 */
		public function add_scripts() {
			global $elegant_js_folder_url, $elegant_js_folder_path;

			Fusion_Dynamic_JS::enqueue_script(
				'infi-advanced-video',
				$elegant_js_folder_url . '/infi-elegant-advanced-video.min.js',
				$elegant_js_folder_path . '/infi-elegant-advanced-video.min.js',
				array( 'jquery' ),
				'1',
				true
			);
		}
	}

	new IEE_Advanced_Video();
} // End if().

/**
 * Map shortcode for advanced_video.
 *
 * @since 2.3
 * @return void
 */
function map_elegant_elements_advanced_video() {
	global $fusion_settings;

	fusion_builder_map(
		array(
			'name'       => esc_attr__( 'Elegant Advanced Video', 'elegant-elements' ),
			'shortcode'  => 'iee_advanced_video',
			'icon'       => 'fa-play-circle fas advanced-video-icon',
			'preview'    => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/previews/infi-advanced-video-preview.php',
			'preview_id' => 'elegant-elements-module-infi-advanced-video-preview-template',
			'front-end'  => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-advanced-video-preview.php',
			'params'     => array(
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Video Provider', 'elegant-elements' ),
					'param_name'  => 'video_provider',
					'value'       => array(
						'youtube' => __( 'YouTube', 'elegant-elements' ),
						'vimeo'   => __( 'Vimeo', 'elegant-elements' ),
						'wistia'  => __( 'Wistia', 'elegant-elements' ),
						'hosted'  => __( 'Self Hosted', 'elegant-elements' ),
					),
					'default'     => 'youtube',
					'description' => esc_attr__( 'Select the video provide you want to use the video from. You can choose from different providers like YouTube, Vimeo and Wistia.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Video ID', 'elegant-elements' ),
					'param_name'  => 'video_id',
					'value'       => 'il2ZAZX9KpQ',
					'placeholder' => true,
					'description' => esc_attr__( 'Enter the video id from your provider. You can get the video ID from the url.', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'video_provider',
							'value'    => 'hosted',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'uploadfile',
					'heading'     => esc_attr__( 'Video MP4 Upload', 'elegant-elements' ),
					'description' => esc_attr__( 'Add your MP4 video file. This format must be included to render your video with cross-browser compatibility.', 'elegant-elements' ),
					'param_name'  => 'video_file',
					'value'       => '',
					'dependency'  => array(
						array(
							'element'  => 'video_provider',
							'value'    => 'hosted',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'upload',
					'heading'     => esc_attr__( 'Preview Image', 'elegant-elements' ),
					'description' => esc_attr__( 'Upload or select the image to use for video preview.', 'elegant-elements' ),
					'param_name'  => 'image',
				),
				array(
					'type'        => 'upload',
					'heading'     => esc_attr__( 'Retina Preview Image', 'elegant-elements' ),
					'description' => esc_attr__( 'Upload or select the image to be used on retina devices as video preview.', 'elegant-elements' ),
					'param_name'  => 'image_retina',
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Image Max Width', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the maximum css width for the image. Height will change in the proportion automatically. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'width',
					'value'       => '320',
					'min'         => '50',
					'max'         => '2000',
					'step'        => '1',
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Image Alignment', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the image alignment.', 'elegant-elements' ),
					'param_name'  => 'alignment',
					'value'       => array(
						'none'   => 'Text Flow',
						'left'   => 'Left',
						'center' => 'Center',
						'right'  => 'Right',
					),
					'default'     => 'left',
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Overlay Background Color', 'elegant-elements' ),
					'param_name'  => 'image_overlay',
					'value'       => 'rgba(0,0,0,0.3)',
					'placeholder' => true,
					'description' => esc_attr__( 'Choose the overlay background color of the video placeholder image.', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Icon Type', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose if you want to use image or FontAwesome icon for play button.', 'elegant-elements' ),
					'param_name'  => 'icon_type',
					'value'       => array(
						'icon'  => 'Font Icon',
						'image' => 'Image Icon',
					),
					'default'     => 'icon',
					'group'       => 'Play Button',
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_attr__( 'Choose Play Icon', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the icon for the video play icon on the image.', 'elegant-elements' ),
					'param_name'  => 'video_play_icon',
					'value'       => 'fa-play fas',
					'group'       => 'Play Button',
					'dependency'  => array(
						array(
							'element'  => 'icon_type',
							'value'    => 'image',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Icon Color', 'elegant-elements' ),
					'param_name'  => 'icon_color',
					'value'       => '#ffffff',
					'placeholder' => true,
					'description' => esc_attr__( 'Choose the icon color of the video play icon.', 'elegant-elements' ),
					'group'       => 'Play Button',
					'dependency'  => array(
						array(
							'element'  => 'icon_type',
							'value'    => 'image',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Icon Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for icon. ( In Pixel. )', 'elegant-elements' ),
					'param_name'  => 'icon_font_size',
					'value'       => '32',
					'min'         => '12',
					'max'         => '100',
					'step'        => '1',
					'group'       => 'Play Button',
					'dependency'  => array(
						array(
							'element'  => 'icon_type',
							'value'    => 'image',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'upload',
					'heading'     => esc_attr__( 'Icon Image', 'elegant-elements' ),
					'description' => esc_attr__( 'Upload or select the image to use for video play button on the preview image.', 'elegant-elements' ),
					'param_name'  => 'image_icon',
					'dependency'  => array(
						array(
							'element'  => 'icon_type',
							'value'    => 'icon',
							'operator' => '!=',
						),
					),
					'group'       => 'Play Button',
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Display YouTube Subscription Button?', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose if you want display YouTube subscription bar with Subscribe button to increase your YouTube subscribers. Use this option only if you\'re using YouTube video here.', 'elegant-elements' ),
					'param_name'  => 'youtube_subscribe',
					'value'       => array(
						'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
						'no'  => esc_attr__( 'No', 'elegant-elements' ),
					),
					'default'     => 'no',
					'group'       => 'YouTube Subscribe Bar',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'YouTube Channel Name or ID', 'elegant-elements' ),
					'param_name'  => 'youtube_channel',
					'value'       => 'GoogleDevelopers',
					'placeholder' => true,
					'description' => esc_attr__( 'Enter your chanel name or ID here. You can find your channel ID here - ', 'elegant-elements' ) . ' <a href="https://www.youtube.com/account_advanced" target="_blank">https://www.youtube.com/account_advanced</a>',
					'group'       => 'YouTube Subscribe Bar',
					'dependency'  => array(
						array(
							'element'  => 'youtube_subscribe',
							'value'    => 'yes',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Subscribe Text', 'elegant-elements' ),
					'param_name'  => 'subscribe_text',
					'value'       => esc_attr__( 'Subscribe to our YouTube channel.', 'elegant-elements' ),
					'placeholder' => true,
					'description' => esc_attr__( 'Controls the promo text to ask users to Subscribe.', 'elegant-elements' ),
					'group'       => 'YouTube Subscribe Bar',
					'dependency'  => array(
						array(
							'element'  => 'youtube_subscribe',
							'value'    => 'yes',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Subscribe Bar Background Color', 'elegant-elements' ),
					'param_name'  => 'subscribe_bar_background',
					'value'       => '#666666',
					'description' => esc_attr__( 'Choose the subscribe bar background color.', 'elegant-elements' ),
					'group'       => 'YouTube Subscribe Bar',
					'dependency'  => array(
						array(
							'element'  => 'youtube_subscribe',
							'value'    => 'yes',
							'operator' => '==',
						),
					),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Subscribe Promo Text Color', 'elegant-elements' ),
					'param_name'  => 'subscribe_bar_text_color',
					'value'       => '#ffffff',
					'description' => esc_attr__( 'Choose the subscribe bar promo text color.', 'elegant-elements' ),
					'group'       => 'YouTube Subscribe Bar',
					'dependency'  => array(
						array(
							'element'  => 'youtube_subscribe',
							'value'    => 'yes',
							'operator' => '==',
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_advanced_video', 99 );
