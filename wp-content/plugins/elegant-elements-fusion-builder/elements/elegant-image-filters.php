<?php
if ( fusion_is_element_enabled( 'iee_image_filters' ) && ! class_exists( 'IEE_Image_Filters' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.1.0
	 */
	class IEE_Image_Filters extends Fusion_Element {

		/**
		 * An array of the parent shortcode arguments.
		 *
		 * @access protected
		 * @since 1.1.0
		 * @var array
		 */
		protected $args;

		/**
		 * An array of the child shortcode arguments.
		 *
		 * @access protected
		 * @since 1.1.0
		 * @var array
		 */
		protected $child_args;

		/**
		 * Image Filters counter.
		 *
		 * @since 1.1.0
		 * @access private
		 * @var object
		 */
		private $image_filters_counter = 1;

		/**
		 * Image Filters child counter.
		 *
		 * @since 1.1.0
		 * @access private
		 * @var object
		 */
		private $image_filters_child_counter = 0;

		/**
		 * Image Filters.
		 *
		 * @since 1.1.0
		 * @access private
		 * @var object
		 */
		private $image_filters = array();

		/**
		 * Image Filter Navigation.
		 *
		 * @since 1.1.0
		 * @access private
		 * @var object
		 */
		private $image_filter_navigation = array();

		/**
		 * Constructor.
		 *
		 * @since 1.1.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-image-filters-wrapper', array( $this, 'wrapper_attr' ) );
			add_filter( 'fusion_attr_elegant-image-filters-navigation', array( $this, 'navigation_attr' ) );
			add_filter( 'fusion_attr_elegant-image-filters-navigation-item', array( $this, 'navigation_item_attr' ) );
			add_filter( 'fusion_attr_elegant-image-filters-content', array( $this, 'content_attr' ) );
			add_filter( 'fusion_attr_elegant-image-filter-title', array( $this, 'filter_title_attr' ) );
			add_filter( 'fusion_attr_elegant-image-filter-title-overlay', array( $this, 'title_overlay_attr' ) );

			add_shortcode( 'iee_image_filters', array( $this, 'render_parent' ) );
			add_shortcode( 'iee_filter_image', array( $this, 'render_child' ) );

			// Ajax mechanism for query related part.
			add_action( 'wp_ajax_get_filter_lightbox_image', array( $this, 'ajax_query' ) );
		}

		/**
		 * Render the parent shortcode.
		 *
		 * @access public
		 * @since 1.1.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render_parent( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			$this->image_filters_child_counter = 1;

			// Get default typography for title and description.
			$default_typography = elegant_get_default_typography();

			if ( isset( $args['element_typography'] ) && 'default' === $args['element_typography'] ) {
				$args['typography_navigation_title'] = $default_typography['title'];
				$args['typography_image_title']      = $default_typography['description'];
			}

			$args['lightbox_image_meta'] = ( isset( $args['lightbox_image_meta'] ) ) ? $args['lightbox_image_meta'] : 'caption';

			$this->args = $args;

			$html  = '';
			$html .= $this->add_styles();

			if ( '' !== locate_template( 'templates/image-filters/elegant-image-filters-parent.php' ) ) {
				include locate_template( 'templates/image-filters/elegant-image-filters-parent.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/image-filters/elegant-image-filters-parent.php';
			}

			$this->image_filters_counter++;

			return $html;
		}

		/**
		 * Render the child shortcode.
		 *
		 * @access public
		 * @since 1.1.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render_child( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			$args['lightbox_image_meta'] = ( isset( $args['lightbox_image_meta'] ) ) ? $args['lightbox_image_meta'] : $this->args['lightbox_image_meta'];

			$this->child_args = $args;
			$child_html       = '';

			if ( '' !== locate_template( 'templates/image-filters/elegant-image-filters-child.php' ) ) {
				include locate_template( 'templates/image-filters/elegant-image-filters-child.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/image-filters/elegant-image-filters-child.php';
			}

			$this->image_filters_child_counter++;
			return $child_html;
		}

		/**
		 * Gets the query data.
		 *
		 * @static
		 * @access public
		 * @since 2.0
		 * @param array $defaults An array of defaults.
		 * @return void
		 */
		public function ajax_query( $defaults ) {
			check_ajax_referer( 'fusion_load_nonce', 'fusion_load_nonce' );
			$this->query( $defaults );
		}

		/**
		 * Gets the query data.
		 *
		 * @static
		 * @access public
		 * @since 2.0
		 * @param array $defaults The default args.
		 * @return void
		 */
		public function query( $defaults ) {
			$live_request = false;

			// From Ajax Request.
			if ( isset( $_POST['model'] ) && isset( $_POST['model']['params'] ) ) { // @codingStandardsIgnoreLine
				$defaults     = $_POST['model']['params']; // @codingStandardsIgnoreLine
				$return_data  = array();
				$live_request = true;
				fusion_set_live_data();
			}

			$lightbox_image_url = ( isset( $defaults['lightbox_image_url'] ) && '' !== $defaults['lightbox_image_url'] ) ? $defaults['lightbox_image_url'] : $defaults['image_url'];
			$lightbox_image_url = str_replace( array( '×', '&#215;' ), 'x', $lightbox_image_url );

			$image_caption = '';
			$image_title   = '';

			$lightbox_image_id = attachment_url_to_postid( $lightbox_image_url );

			if ( ! $lightbox_image_id ) {
				$lightbox_image_id = fusion_library()->images->get_attachment_id_from_url( $lightbox_image_url );
			}

			$image_caption = wp_get_attachment_caption( $lightbox_image_id );

			$image_meta_data = wp_get_attachment_metadata( $lightbox_image_id );
			$image_title     = $image_meta_data['image_meta']['title'];

			if ( '' === $image_title && $lightbox_image_id ) {
				$image_title = get_the_title( $lightbox_image_id );
			}

			$return_data['image'] = array(
				'id'      => $lightbox_image_id,
				'title'   => $image_title,
				'caption' => $image_caption,
			);

			echo wp_json_encode( $return_data );
			wp_die();
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return array
		 */
		public function wrapper_attr() {
			$attr = array(
				'class' => 'elegant-image-filters-wrapper',
			);

			$attr['class'] .= ' elegant-image-filters-' . $this->image_filters_counter;

			$attr = fusion_builder_visibility_atts( $this->args['hide_on_mobile'], $attr );

			if ( isset( $this->args['navigation_layout'] ) && 'horizontal' === $this->args['navigation_layout'] ) {
				$attr['class'] .= ' image-filter-navigation-layout-horizontal image-filter-navigation-align-' . $this->args['navigation_alignment'];
			} else {
				$attr['class'] .= ' image-filter-navigation-layout-vertical image-filter-navigation-position-' . $this->args['navigation_position'];
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
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return array
		 */
		public function navigation_attr() {
			$attr = array(
				'class'      => 'elegant-image-filters-navigation',
				'role'       => 'menu',
				'aria-label' => 'filters',
				'style'      => '',
			);

			if ( isset( $this->args['active_navigation_border_type'] ) && '' !== $this->args['active_navigation_border_type'] ) {
				$attr['class'] .= ' image-filters-active-navigation-' . $this->args['active_navigation_border_type'];
			}

			if ( isset( $this->args['typography_navigation_title'] ) && '' !== $this->args['typography_navigation_title'] ) {
				$typography                  = $this->args['typography_navigation_title'];
				$typography_navigation_title = elegant_get_typography_css( $typography );

				$attr['style'] .= $typography_navigation_title;
			}

			if ( isset( $this->args['navigation_title_font_size'] ) && '' !== $this->args['navigation_title_font_size'] ) {
				$attr['style'] .= 'font-size:' . FusionBuilder::validate_shortcode_attr_value( $this->args['navigation_title_font_size'], 'px' ) . ';';
			}

			if ( isset( $this->args['navigation_title_color'] ) && '' !== $this->args['navigation_title_color'] ) {
				$attr['style'] .= 'color:' . $this->args['navigation_title_color'] . ';';
			}

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return array
		 */
		public function navigation_item_attr() {
			$attr = array(
				'class' => 'elegant-image-filters-navigation-item',
				'role'  => 'menuitem',
			);

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return array
		 */
		public function content_attr() {
			$attr = array(
				'class' => 'elegant-image-filters-content',
				'style' => 'opacity:0;',
			);

			$columns = ( isset( $this->args['columns'] ) ) ? $this->args['columns'] : '3';

			$attr['class'] .= ' elegant-image-filter-grid-' . $columns;

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.1.0
		 * @param array $args Current child attributes.
		 * @return array
		 */
		public function filter_title_attr( $args ) {
			$attr = array(
				'class' => 'elegant-image-filter-title',
				'style' => '',
			);

			if ( isset( $this->args['typography_image_title'] ) && '' !== $this->args['typography_image_title'] ) {
				$typography             = $this->args['typography_image_title'];
				$typography_image_title = elegant_get_typography_css( $typography );

				$attr['style'] .= $typography_image_title;
			}

			if ( isset( $this->args['image_title_font_size'] ) && '' !== $this->args['image_title_font_size'] ) {
				$attr['style'] .= 'font-size:' . FusionBuilder::validate_shortcode_attr_value( $this->args['image_title_font_size'], 'px' ) . ';';
			}

			if ( isset( $args['image_title_color'] ) && '' !== $args['image_title_color'] ) {
				$attr['style'] .= 'color:' . $args['image_title_color'] . ';';
			} elseif ( isset( $this->args['image_title_color'] ) && '' !== $this->args['image_title_color'] ) {
				$attr['style'] .= 'color:' . $this->args['image_title_color'] . ';';
			}

			if ( isset( $this->args['image_title_position'] ) && 'on_image_hover' !== $this->args['image_title_position'] ) {
				if ( isset( $this->args['image_title_layout'] ) && 'unboxed' !== $this->args['image_title_layout'] ) {
					$boxed_background_color = ( isset( $args['boxed_background_color'] ) && '' !== $args['boxed_background_color'] ) ? $args['boxed_background_color'] : $this->args['boxed_background_color'];
					$attr['class']         .= ' image-filter-title-layout-boxed';
					$attr['style']         .= 'background-color:' . $boxed_background_color . ';';
				}
			} else {
				$attr['class'] .= ' image-filter-title-layout-overlay';
			}

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.1.0
		 * @param array $args Current child attributes.
		 * @return array
		 */
		public function title_overlay_attr( $args ) {
			$attr = array(
				'class' => 'image-filter-title-overlay',
				'style' => '',
			);

			if ( isset( $this->args['grid_item_padding'] ) && '' !== $this->args['grid_item_padding'] ) {
				$attr['style'] .= 'top:' . FusionBuilder::validate_shortcode_attr_value( $this->args['grid_item_padding'], 'px' ) . ';';
				$attr['style'] .= 'left:' . FusionBuilder::validate_shortcode_attr_value( $this->args['grid_item_padding'], 'px' ) . ';';
				$attr['style'] .= 'width: calc( 100% - ' . FusionBuilder::validate_shortcode_attr_value( ( $this->args['grid_item_padding'] * 2 ), 'px' ) . ');';
				$attr['style'] .= 'height: calc( 100% - ' . FusionBuilder::validate_shortcode_attr_value( ( $this->args['grid_item_padding'] * 2 ), 'px' ) . ');';
			}

			$overlay_background_color = ( isset( $args['overlay_background_color'] ) && '' !== $args['overlay_background_color'] ) ? $args['overlay_background_color'] : $this->args['overlay_background_color'];
			$attr['style']           .= 'background-color:' . $overlay_background_color . ';';

			return $attr;
		}

		/**
		 * Sets the necessary scripts.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return void
		 */
		public function add_scripts() {
			global $elegant_js_folder_url, $elegant_js_folder_path, $elegant_css_folder_url, $elegant_css_folder_path;

			Fusion_Dynamic_JS::enqueue_script( 'images-loaded' );
			Fusion_Dynamic_JS::enqueue_script( 'packery' );
			Fusion_Dynamic_JS::enqueue_script(
				'infi-elegant-image-filters',
				$elegant_js_folder_url . '/infi-elegant-image-filters.min.js',
				$elegant_js_folder_path . '/infi-elegant-image-filters.min.js',
				array( 'jquery', 'images-loaded', 'packery' ),
				'1',
				true
			);
		}

		/**
		 * Add active navigation styling.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return string $styles Generated CSS.
		 */
		public function add_styles() {
			$style  = '<style type="text/css">';
			$style .= '.elegant-image-filters-wrapper.elegant-image-filters-' . $this->image_filters_counter . ' .elegant-image-filters-navigation-item.filter-active {';

			if ( isset( $this->args['navigation_active_color'] ) ) {
				$style .= 'color:' . $this->args['navigation_active_color'] . ';';
			}

			if ( isset( $this->args['navigation_active_background_color'] ) ) {
				$style .= 'background-color:' . $this->args['navigation_active_background_color'] . ';';
			}

			$style .= '}';
			$style .= '</style>';

			return $style;
		}

		/**
		 * Sets the dynamic css for media queries.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return string $css Generated global CSS.
		 */
		public function add_styling() {
			global $wp_version, $content_media_query, $six_fourty_media_query, $three_twenty_six_fourty_media_query, $ipad_portrait_media_query, $content_min_media_query, $small_media_query, $medium_media_query, $large_media_query, $six_columns_media_query, $five_columns_media_query, $four_columns_media_query, $three_columns_media_query, $two_columns_media_query, $one_column_media_query, $fusion_library, $fusion_settings, $dynamic_css_helpers;

			// Six Column Breakpoint.
			$elements = array(
				'.elegant-image-filters-wrapper .elegant-image-filters-content.elegant-image-filter-grid-6 .elegant-image-filter-item',
			);
			$css[ $six_columns_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['width'] = '20% !important';
			$css[ $six_columns_media_query ]['.elegant-image-filters-wrapper .elegant-image-filters-content.elegant-image-filter-grid-6 .elegant-image-landscape']['width'] = '40% !important';

			$elements = array(
				'.elegant-image-filters-wrapper .elegant-image-filters-content.elegant-image-filter-grid-5 .elegant-image-filter-item',
			);
			$css[ $six_columns_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['width'] = '25% !important';
			$css[ $six_columns_media_query ]['.elegant-image-filters-wrapper .elegant-image-filters-content.elegant-image-filter-grid-5 .elegant-image-landscape']['width'] = '50% !important';

			// Five Column Breakpoint.
			$elements = array(
				'.elegant-image-filters-wrapper .elegant-image-filters-content.elegant-image-filter-grid-6 .elegant-image-filter-item',
			);
			$css[ $five_columns_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['width'] = '20% !important';
			$css[ $five_columns_media_query ]['.elegant-image-filters-wrapper .elegant-image-filters-content.elegant-image-filter-grid-6 .elegant-image-landscape']['width'] = '40% !important';

			$elements = array(
				'.elegant-image-filters-wrapper .elegant-image-filters-content.elegant-image-filter-grid-5 .elegant-image-filter-item',
			);
			$css[ $five_columns_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['width'] = '33.3333333333% !important';
			$css[ $five_columns_media_query ]['.elegant-image-filters-wrapper .elegant-image-filters-content.elegant-image-filter-grid-5 .elegant-image-landscape']['width'] = '66% !important';

			$elements = array(
				'.elegant-image-filters-wrapper .elegant-image-filters-content.elegant-image-filter-grid-4 .elegant-image-filter-item',
			);
			$css[ $five_columns_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['width'] = '33.3333333333% !important';
			$css[ $five_columns_media_query ]['.elegant-image-filters-wrapper .elegant-image-filters-content.elegant-image-filter-grid-4 .elegant-image-landscape']['width'] = '66% !important';

			// Four Column Breakpoint.
			$elements = array(
				'.elegant-image-filters-wrapper .elegant-image-filters-content.elegant-image-filter-grid-6 .elegant-image-filter-item',
			);
			$css[ $four_columns_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['width'] = '25% !important';
			$css[ $four_columns_media_query ]['.elegant-image-filters-wrapper .elegant-image-filters-content.elegant-image-filter-grid-6 .elegant-image-landscape']['width'] = '50% !important';

			$elements = array(
				'.elegant-image-filters-wrapper .elegant-image-filters-content.elegant-image-filter-grid-5 .elegant-image-filter-item',
				'.elegant-image-filters-wrapper .elegant-image-filters-content.elegant-image-filter-grid-4 .elegant-image-filter-item',
				'.elegant-image-filters-wrapper .elegant-image-filters-content.elegant-image-filter-grid-3 .elegant-image-filter-item',
			);
			$css[ $four_columns_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['width'] = '50% !important';

			$elements = $dynamic_css_helpers->map_selector( $elements, '.elegant-image-landscape' );
			$css[ $four_columns_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['width'] = '100% !important';

			// Three Column Breakpoint.
			$elements = array(
				'.elegant-image-filters-wrapper .elegant-image-filters-content.elegant-image-filter-grid-6 .elegant-image-filter-item',
			);
			$css[ $three_columns_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['width'] = '33.33% !important';
			$css[ $three_columns_media_query ]['.elegant-image-filters-wrapper .elegant-image-filters-content.elegant-image-filter-grid-6 .elegant-image-landscape']['width'] = '66% !important';

			$elements = array(
				'.elegant-image-filters-wrapper .elegant-image-filters-content.elegant-image-filter-grid-5 .elegant-image-filter-item',
				'.elegant-image-filters-wrapper .elegant-image-filters-content.elegant-image-filter-grid-4 .elegant-image-filter-item',
				'.elegant-image-filters-wrapper .elegant-image-filters-content.elegant-image-filter-grid-3 .elegant-image-filter-item',
			);
			$css[ $three_columns_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['width'] = '50% !important';

			$elements = $dynamic_css_helpers->map_selector( $elements, '.elegant-image-landscape' );
			$css[ $three_columns_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['width'] = '100% !important';

			// Two Column Breakpoint.
			$elements = array(
				'.elegant-image-filters-wrapper .elegant-image-filters-content .elegant-image-filter-item',
			);
			$css[ $two_columns_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['width'] = '100% !important';

			$elements = array(
				'.elegant-image-filters-wrapper .elegant-image-filters-content.elegant-image-filter-grid-6 .elegant-image-filter-item',
			);
			$css[ $two_columns_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['width'] = '50% !important';
			$css[ $two_columns_media_query ]['.elegant-image-filters-wrapper .elegant-image-filters-content.elegant-image-filter-grid-6 .elegant-image-landscape']['width'] = '100% !important';

			// One Column Breakpoint.
			$elements = array(
				'.elegant-image-filters-wrapper .elegant-image-filters-content.elegant-image-filter-grid-6 .elegant-image-filter-item',
			);
			$css[ $one_column_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['width'] = '100% !important';

			// Portrait Column Breakpoint for iPad.
			$elements = array(
				'.elegant-image-filters-wrapper .elegant-image-filters-content.elegant-image-filter-grid-6 .elegant-image-filter-item',
			);
			$css[ $ipad_portrait_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['width'] = '33.3333333333% !important';
			$css[ $ipad_portrait_media_query ]['.elegant-image-filters-wrapper .elegant-image-filters-content.elegant-image-filter-grid-6 .elegant-image-landscape']['width'] = '66% !important';

			$elements = array(
				'.elegant-image-filters-wrapper .elegant-image-filters-content.elegant-image-filter-grid-5 .elegant-image-filter-item',
				'.elegant-image-filters-wrapper .elegant-image-filters-content.elegant-image-filter-grid-4 .elegant-image-filter-item',
				'.elegant-image-filters-wrapper .elegant-image-filters-content.elegant-image-filter-grid-3 .elegant-image-filter-item',
			);
			$css[ $ipad_portrait_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['width'] = '50% !important';

			$elements = $dynamic_css_helpers->map_selector( $elements, '.elegant-image-landscape' );
			$css[ $ipad_portrait_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['width'] = '100% !important';

			return $css;
		}
	}

	new IEE_Image_Filters();
} // End if().


/**
 * Map shortcode for image_filters.
 *
 * @since 1.1.0
 * @return void
 */
function map_elegant_elements_image_filters() {
	global $fusion_settings;

	// Get default typography for title and description.
	$default_typography = elegant_get_default_typography();

	$parent_args = array(
		'name'          => esc_attr__( 'Elegant Image Filters', 'elegant-elements' ),
		'shortcode'     => 'iee_image_filters',
		'icon'          => 'icon-filter',
		'multi'         => 'multi_element_parent',
		'element_child' => 'iee_filter_image',
		'preview'       => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/previews/infi-image-filters-preview.php',
		'preview_id'    => 'elegant-elements-module-infi-image-filters-preview-template',
		'child_ui'      => true,
		'params'        => array(
			array(
				'type'        => 'tinymce',
				'heading'     => esc_attr__( 'Content', 'elegant-elements' ),
				'description' => esc_attr__( 'Image Filter items.', 'elegant-elements' ),
				'param_name'  => 'element_content',
				'value'       => '[iee_filter_image title="' . esc_attr__( 'Your Content Goes Here', 'elegant-elements' ) . '" /]',
			),
			array(
				'type'             => 'multiple_upload',
				'heading'          => esc_attr__( 'Bulk Image Upload', 'elegant-elements' ),
				'description'      => __( 'This option allows you to select multiple images at once and they will populate into individual items. It saves time instead of adding one image at a time.', 'elegant-elements' ),
				'param_name'       => 'multiple_upload',
				'element_target'   => 'iee_filter_image',
				'param_target'     => 'image_url',
				'child_params'     => array(
					'image_url' => 'url',
					'image_id'  => 'id',
				),
				'remove_from_atts' => true,
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Image Grid Coumns', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the number of columns you want the images to be display.', 'elegant-elements' ),
				'param_name'  => 'columns',
				'value'       => '3',
				'min'         => '2',
				'max'         => '6',
				'step'        => '1',
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Image Padding', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the space you want in between images in the grid. In Pixels (px).', 'elegant-elements' ),
				'param_name'  => 'grid_item_padding',
				'value'       => '10',
				'min'         => '0',
				'max'         => '100',
				'step'        => '1',
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
				'heading'     => esc_attr__( 'Navigation Title Font', 'elegant-elements' ),
				'description' => esc_attr__( 'Select font for the navigation title.', 'elegant-elements' ),
				'param_name'  => 'typography_navigation_title',
				'value'       => '',
				'default'     => $default_typography['title'],
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
				'heading'     => esc_attr__( 'Navigation Title Font Size', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the font size for navigation title text. In Pixels (px).', 'elegant-elements' ),
				'param_name'  => 'navigation_title_font_size',
				'value'       => '18',
				'min'         => '12',
				'max'         => '100',
				'step'        => '1',
				'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Navigation Title Color', 'elegant-elements' ),
				'param_name'  => 'navigation_title_color',
				'value'       => '',
				'description' => esc_attr__( 'Controls the navigation title text color for inactive state. To change active nagivation item styling, visit navigation tab.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
			),
			array(
				'type'        => 'elegant_typography',
				'heading'     => esc_attr__( 'Image Title Font', 'elegant-elements' ),
				'description' => esc_attr__( 'Select font for the image title.', 'elegant-elements' ),
				'param_name'  => 'typography_image_title',
				'value'       => '',
				'default'     => $default_typography['description'],
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
				'heading'     => esc_attr__( 'Image Title Font Size', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the font size for image title text. In Pixels (px).', 'elegant-elements' ),
				'param_name'  => 'image_title_font_size',
				'value'       => '18',
				'min'         => '12',
				'max'         => '100',
				'step'        => '1',
				'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Image Title Color', 'elegant-elements' ),
				'param_name'  => 'image_title_color',
				'value'       => '',
				'description' => esc_attr__( 'Controls the image title text color.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Image Title Position', 'elegant-elements' ),
				'param_name'  => 'image_title_position',
				'default'     => 'after_image',
				'value'       => array(
					'after_image'    => esc_attr__( 'After Image', 'elegant-elements' ),
					'before_image'   => esc_attr__( 'Before Image', 'elegant-elements' ),
					'on_image_hover' => esc_attr__( 'On Image Hover', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Controls where the image title will be displayed.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Image Options', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Image Title Layout', 'elegant-elements' ),
				'param_name'  => 'image_title_layout',
				'default'     => 'boxed',
				'value'       => array(
					'boxed'   => esc_attr__( 'Boxed', 'elegant-elements' ),
					'unboxed' => esc_attr__( 'Unboxed', 'elegant-elements' ),
				),
				'dependency'  => array(
					array(
						'element'  => 'image_title_position',
						'value'    => 'on_image_hover',
						'operator' => '!=',
					),
				),
				'description' => esc_attr__( 'Choose if you want to display image in boxed mode with background color.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Image Options', 'elegant-elements' ),
			),
			array(
				'type'        => 'checkbox_button_set',
				'heading'     => esc_attr__( 'Image Meta in Lightbox', 'elegant-elements' ),
				'description' => esc_attr__( 'Choose what you want to display from the lightbox image meta in lightbox if you display lightbox on image click. Uncheck all to disable.', 'elegant-elements' ),
				'param_name'  => 'lightbox_image_meta',
				'default'     => 'caption',
				'value'       => array(
					'caption' => __( 'Caption', 'elegant-elements' ),
					'title'   => __( 'Title', 'elegant-elements' ),
				),
				'group'       => esc_attr__( 'Image Options', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Boxed Background Color', 'elegant-elements' ),
				'param_name'  => 'boxed_background_color',
				'value'       => '#fbfbfb',
				'dependency'  => array(
					array(
						'element'  => 'image_title_position',
						'value'    => 'on_image_hover',
						'operator' => '!=',
					),
					array(
						'element'  => 'image_title_layout',
						'value'    => 'boxed',
						'operator' => '==',
					),
				),
				'description' => esc_attr__( 'Controls the box background color for image title.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Image Options', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpickeralpha',
				'heading'     => esc_attr__( 'Overlay Background Color', 'elegant-elements' ),
				'param_name'  => 'overlay_background_color',
				'value'       => 'rgba(0,0,0,0.6)',
				'dependency'  => array(
					array(
						'element'  => 'image_title_position',
						'value'    => 'on_image_hover',
						'operator' => '==',
					),
				),
				'description' => esc_attr__( 'Controls the overlay background color for image title.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Image Options', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Filter Navigation Layout', 'elegant-elements' ),
				'param_name'  => 'navigation_layout',
				'default'     => 'horizontal',
				'value'       => array(
					'horizontal' => esc_attr__( 'Horizontal', 'elegant-elements' ),
					'vertical'   => esc_attr__( 'Vertical', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Controls the filter navigatio layout.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Navigation', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Filter Navigation Alignment', 'elegant-elements' ),
				'param_name'  => 'navigation_alignment',
				'default'     => 'left',
				'value'       => array(
					'left'   => esc_attr__( 'Left', 'elegant-elements' ),
					'center' => esc_attr__( 'Center', 'elegant-elements' ),
					'right'  => esc_attr__( 'Right', 'elegant-elements' ),
				),
				'dependency'  => array(
					array(
						'element'  => 'navigation_layout',
						'value'    => 'horizontal',
						'operator' => '==',
					),
				),
				'description' => esc_attr__( 'Set filter navigation alignment.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Navigation', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Filter Navigation Position', 'elegant-elements' ),
				'param_name'  => 'navigation_position',
				'default'     => 'left',
				'value'       => array(
					'left'  => esc_attr__( 'Left', 'elegant-elements' ),
					'right' => esc_attr__( 'Right', 'elegant-elements' ),
				),
				'dependency'  => array(
					array(
						'element'  => 'navigation_layout',
						'value'    => 'vertical',
						'operator' => '==',
					),
				),
				'description' => esc_attr__( 'Set filter navigation position for the vertical layout.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Navigation', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Active Navigation Border Type', 'elegant-elements' ),
				'param_name'  => 'active_navigation_border_type',
				'default'     => 'round',
				'value'       => array(
					'round'  => esc_attr__( 'Round', 'elegant-elements' ),
					'square' => esc_attr__( 'Square', 'elegant-elements' ),
					'bottom' => esc_attr__( 'Bottom Only', 'elegant-elements' ),
					'top'    => esc_attr__( 'Top Only', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Controls the border type for active navigation item.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Navigation', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => sprintf( esc_attr__( 'Use %s Filter', 'elegant-elements' ), '"All"' ),
				'param_name'  => 'use_all_filter',
				'default'     => 'yes',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Choose if you want to enable the "All" filter to display all your images.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Navigation', 'elegant-elements' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => sprintf( esc_attr__( '%s Filter Text', 'elegant-elements' ), '"All"' ),
				'param_name'  => 'all_filter_text',
				'value'       => esc_attr__( 'All', 'elegant-elements' ),
				'placeholder' => true,
				'dependency'  => array(
					array(
						'element'  => 'use_all_filter',
						'value'    => 'yes',
						'operator' => '==',
					),
				),
				'description' => esc_attr__( 'Choose if you want to enable the "All" filter to display all your images.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Navigation', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Order Navigation Alphabetically', 'elegant-elements' ),
				'description' => esc_attr__( 'Choose if you want to order the filter navigation alphabetically.', 'elegant-elements' ),
				'param_name'  => 'navigation_alpha',
				'default'     => 'no',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'group'       => esc_attr__( 'Navigation', 'elegant-elements' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Filter Navigation Separator', 'elegant-elements' ),
				'param_name'  => 'filter_separator',
				'value'       => '',
				'placeholder' => true,
				'dependency'  => array(
					array(
						'element'  => 'navigation_layout',
						'value'    => 'horizontal',
						'operator' => '==',
					),
				),
				'description' => esc_attr__( 'Controls the separator between navigation items.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Navigation', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Active Navigation Text Color', 'elegant-elements' ),
				'param_name'  => 'navigation_active_color',
				'value'       => '',
				'description' => esc_attr__( 'Controls the active navigation text color.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Navigation', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Active Navigation Background Color', 'elegant-elements' ),
				'param_name'  => 'navigation_active_background_color',
				'value'       => '',
				'description' => esc_attr__( 'Controls the active navigation background color.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Navigation', 'elegant-elements' ),
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
		'name'              => esc_attr__( 'Filter Image', 'elegant-elements' ),
		'shortcode'         => 'iee_filter_image',
		'hide_from_builder' => true,
		'allow_generator'   => true,
		'on_change'         => 'elegantImageFilterShortcodeFilter',
		'tag_name'          => 'div',
		'selectors'         => array(
			'class' => 'elegant-image-filter-item',
		),
		'params'            => array(
			array(
				'type'        => 'upload',
				'heading'     => esc_attr__( 'Image', 'elegant-elements' ),
				'description' => esc_attr__( 'Upload an image to be used in the filter.', 'elegant-elements' ),
				'param_name'  => 'image_url',
				'value'       => '',
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Image ID', 'elegant-elements' ),
				'description' => esc_attr__( 'Image ID from Media Library.', 'elegant-elements' ),
				'param_name'  => 'image_id',
				'value'       => '',
				'hidden'      => true,
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Image Orientation', 'elegant-elements' ),
				'description' => esc_attr__( 'Choose the image orientation you want to set.', 'elegant-elements' ),
				'param_name'  => 'orientation',
				'default'     => 'auto',
				'value'       => array(
					'auto'      => esc_attr__( 'Auto', 'elegant-elements' ),
					'portrait'  => esc_attr__( 'Portrait', 'elegant-elements' ),
					'landscape' => esc_attr__( 'Landscape', 'elegant-elements' ),
				),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Image Title', 'elegant-elements' ),
				'description' => esc_attr__( 'Enter title to be used for this filter image.', 'elegant-elements' ),
				'param_name'  => 'title',
				'placeholder' => true,
				'value'       => esc_attr__( 'Your Content Goes Here', 'elegant-elements' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Navitation Category Title', 'elegant-elements' ),
				'description' => esc_attr__( 'Enter title to be used as filter navigation this image. You can use multiple titles separated with comma.', 'elegant-elements' ),
				'param_name'  => 'navigation',
				'placeholder' => true,
				'value'       => esc_attr__( 'Category 1, Category 2', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'On Click Action', 'elegant-elements' ),
				'description' => esc_attr__( 'Choose what you want to do when user click on filter image.', 'elegant-elements' ),
				'param_name'  => 'click_action',
				'default'     => 'none',
				'value'       => array(
					'modal'    => __( 'Open Modal', 'elegant-elements' ),
					'url'      => __( 'Open URL', 'elegant-elements' ),
					'lightbox' => __( 'Open Lightbox', 'elegant-elements' ),
					'none'     => __( 'Do Nothing', 'elegant-elements' ),
				),
			),
			array(
				'type'        => 'upload',
				'heading'     => esc_attr__( 'Lightbox Image', 'elegant-elements' ),
				'description' => esc_attr__( 'Upload an image to be opened in the lightbox. Default image will be used instead.', 'elegant-elements' ),
				'param_name'  => 'element_content',
				'value'       => '',
				'dependency'  => array(
					array(
						'element'  => 'click_action',
						'value'    => 'lightbox',
						'operator' => '==',
					),
				),
				'callback'    => array(
					'function' => 'fusion_ajax',
					'action'   => 'get_filter_lightbox_image',
					'ajax'     => true,
				),
			),
			array(
				'type'        => 'checkbox_button_set',
				'heading'     => esc_attr__( 'Image Meta in Lightbox', 'elegant-elements' ),
				'description' => esc_attr__( 'Choose what you want to display from the lightbox image meta in lightbox. Keep empty to inherit from parent.', 'elegant-elements' ),
				'param_name'  => 'lightbox_image_meta',
				'default'     => '',
				'value'       => array(
					'caption' => __( 'Caption', 'elegant-elements' ),
					'title'   => __( 'Title', 'elegant-elements' ),
				),
				'dependency'  => array(
					array(
						'element'  => 'click_action',
						'value'    => 'lightbox',
						'operator' => '==',
					),
				),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Modal Window Anchor', 'elegant-elements' ),
				'description' => esc_attr__( 'Add the class name of the modal window you want to open on filter image click.', 'elegant-elements' ),
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
				'description' => esc_attr__( 'Enter the url you want to open on filter image click.', 'elegant-elements' ),
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
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Image Title Color', 'elegant-elements' ),
				'param_name'  => 'image_title_color',
				'value'       => '',
				'description' => esc_attr__( 'Change to override the image title text color set in parent. Keep empty to use parent default.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Title Styling', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Boxed Background Color', 'elegant-elements' ),
				'param_name'  => 'boxed_background_color',
				'value'       => '',
				'description' => esc_attr__( 'Change to override the box background color set in parent for this image title. Keep empty to use parent default.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Title Styling', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpickeralpha',
				'heading'     => esc_attr__( 'Overlay Background Color', 'elegant-elements' ),
				'param_name'  => 'overlay_background_color',
				'value'       => '',
				'description' => esc_attr__( 'Change to overrides the overlay background color set in parent for this image title. Keep empty to use parent default.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Title Styling', 'elegant-elements' ),
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
		'callback'          => array(
			'function' => 'fusion_ajax',
			'action'   => 'get_filter_lightbox_image',
			'ajax'     => true,
		),
	);

	if ( function_exists( 'fusion_builder_frontend_data' ) ) {
		fusion_builder_map(
			fusion_builder_frontend_data(
				'IEE_Image_Filters',
				$parent_args,
				'parent'
			)
		);

		fusion_builder_map(
			fusion_builder_frontend_data(
				'IEE_Image_Filters',
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_image_filters', 99 );
