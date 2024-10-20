<?php
if ( fusion_is_element_enabled( 'iee_instagram_gallery' ) && ! class_exists( 'IEE_Instagram_Gallery' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 2.0
	 */
	class IEE_Instagram_Gallery extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 2.0
		 * @var array
		 */
		protected $args;

		/**
		 * Constructor.
		 *
		 * @since 2.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-instagram-gallery', array( $this, 'attr' ) );
			add_shortcode( 'iee_instagram_gallery', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 2.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			if ( ! isset( $args['gallery_layout'] ) ) {
				$args['gallery_layout'] = 'grid';
			}

			if ( ! isset( $args['masonry_columns'] ) ) {
				$args['masonry_columns'] = 'small';
			}

			$api_data = get_option( 'elegant_elements_instagram_api_data', array() );
			if ( isset( $api_data['access_token'] ) ) {
				$args['show_likes']    = 'no';
				$args['show_comments'] = 'no';
			}

			$this->args = $args;

			$html = '';

			if ( ! is_admin() ) {
				if ( '' !== locate_template( 'templates/instagram-gallery/elegant-instagram-gallery.php' ) ) {
					include locate_template( 'templates/instagram-gallery/elegant-instagram-gallery.php', false );
				} else {
					include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/instagram-gallery/elegant-instagram-gallery.php';
				}
			}

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 2.0
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-instagram-gallery',
			);

			$attr = fusion_builder_visibility_atts( $this->args['hide_on_mobile'], $attr );

			if ( 'none' !== $this->args['hover_type'] ) {
				$attr['class'] .= ' fusion-image-hovers';
			}

			if ( 'grid' !== $this->args['gallery_layout'] ) {
				$attr['class'] .= ' elegant-instagram-gallery-masonry';
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
		 * Sets the necessary scripts.
		 *
		 * @access public
		 * @since 2.5
		 * @return void
		 */
		public function add_scripts() {
			Fusion_Dynamic_JS::enqueue_script( 'fusion-lightbox' );
		}
	}

	new IEE_Instagram_Gallery();
} // End if().

/**
 * Map shortcode for instagram_gallery.
 *
 * @since 2.0
 * @return void
 */
function map_elegant_elements_instagram_gallery() {
	global $fusion_settings;

	$api_data    = get_option( 'elegant_elements_instagram_api_data', array() );
	$hide_option = isset( $api_data['access_token'] ) ? 'hidden' : '!hidden';

	fusion_builder_map(
		array(
			'name'      => esc_attr__( 'Elegant Instagram Gallery', 'elegant-elements' ),
			'shortcode' => 'iee_instagram_gallery',
			'icon'      => 'fa-instagram fab instagram-gallery-icon',
			'front-end' => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-instagram-gallery-preview.php',
			'params'    => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( '@username or #tag', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter your instagram username like @username or #tag to display photos from.', 'elegant-elements' ),
					'param_name'  => 'username',
					'value'       => '@unsplash',
					$hide_option  => true,
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Number of Photos', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter the number of photos to be displayed.', 'elegant-elements' ),
					'param_name'  => 'photos_count',
					'value'       => '10',
					'min'         => '1',
					'max'         => '100',
					'step'        => '1',
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Gallery Layout', 'elegant-elements' ),
					'description' => __( 'Choose if you want to display gallery in grid or as masonry layout.', 'elegant-elements' ),
					'param_name'  => 'gallery_layout',
					'default'     => 'grid',
					'value'       => array(
						'grid'    => esc_attr__( 'Grid', 'elegant-elements' ),
						'masonry' => esc_attr__( 'Masonry', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Photo Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the Photo size you want to use. Larger the size, higher the loading time.', 'elegant-elements' ),
					'param_name'  => 'photo_size',
					'default'     => 'small',
					'value'       => array(
						'thumbnail' => esc_attr__( 'Thumbnail ( 5 Columns )', 'elegant-elements' ),
						'small'     => esc_attr__( 'Small ( 4 Columns )', 'elegant-elements' ),
						'large'     => esc_attr__( 'Large ( 3 Columns )', 'elegant-elements' ),
						'original'  => esc_attr__( 'Original ( 2 Columns )', 'elegant-elements' ),
					),
					'dependency'  => array(
						array(
							'element'  => 'gallery_layout',
							'value'    => 'masonry',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Masonry Columns', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the number of columns for masonry layout.', 'elegant-elements' ),
					'param_name'  => 'masonry_columns',
					'default'     => 'small',
					'value'       => array(
						'thumbnail' => esc_attr__( '5 Columns', 'elegant-elements' ),
						'small'     => esc_attr__( '4 Columns', 'elegant-elements' ),
						'large'     => esc_attr__( '3 Columns', 'elegant-elements' ),
						'original'  => esc_attr__( '2 Columns', 'elegant-elements' ),
					),
					'dependency'  => array(
						array(
							'element'  => 'gallery_layout',
							'value'    => 'grid',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Link Target', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose how you want the images to open when clicked.', 'elegant-elements' ),
					'param_name'  => 'link_target',
					'default'     => 'lightbox',
					'value'       => array(
						'_self'    => esc_attr__( 'Current window ( _self )', 'elegant-elements' ),
						'_blank'   => esc_attr__( 'New window ( _blank )', 'elegant-elements' ),
						'lightbox' => esc_attr__( 'Lightbox', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Show Likes Count', 'elegant-elements' ),
					'description' => __( 'Choose if you want to display number of image likes. <br/><strong>Note:</strong> For some reasons, if you\'ve connected the Instagram API and the fallback method isn\'t working, the likes count display is not possible.', 'elegant-elements' ),
					'param_name'  => 'show_likes',
					'default'     => 'yes',
					$hide_option  => true,
					'value'       => array(
						'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
						'no'  => esc_attr__( 'No', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Show Comments Count', 'elegant-elements' ),
					'description' => __( 'Choose if you want to display number of image comments. <br/><strong>Note:</strong> For some reasons, if you\'ve connected the Instagram API and the fallback method isn\'t working, the comments count display is not possible.', 'elegant-elements' ),
					'param_name'  => 'show_comments',
					'default'     => 'yes',
					$hide_option  => true,
					'value'       => array(
						'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
						'no'  => esc_attr__( 'No', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Hover Image Zoom', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the hover effect type.', 'elegant-elements' ),
					'param_name'  => 'hover_type',
					'value'       => array(
						'none'    => esc_attr__( 'None', 'elegant-elements' ),
						'zoomin'  => esc_attr__( 'Zoom In', 'elegant-elements' ),
						'zoomout' => esc_attr__( 'Zoom Out', 'elegant-elements' ),
					),
					'default'     => 'none',
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_instagram_gallery', 99 );
