<?php
// Get saved settings.
$settings = get_option( 'elegant_elements_settings', array() );

if ( ! class_exists( 'IEE_Background_Slider' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 3.2.0
	 */
	class IEE_Background_Slider extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 3.2.0
		 * @var array
		 */
		protected $args;

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 3.2.0
		 * @var array
		 */
		protected $column_args;

		/**
		 * Container counter.
		 *
		 * @access private
		 * @since 3.2.0
		 * @var int
		 */
		private $container_counter = 1;

		/**
		 * Column counter.
		 *
		 * @access private
		 * @since 3.2.0
		 * @var int
		 */
		private $column_counter = 1;

		/**
		 * Constructor.
		 *
		 * @since 3.2.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			// Pre-process the shortcodes.
			add_action( 'init', array( $this, 'update_shortcode_function' ) );

			// Generate background slider from shortcode attributes.
			add_filter( 'elegant_slider_background', array( $this, 'attr' ) );

			// Generate background slider from shortcode attributes.
			add_filter( 'elegant_slider_background_column', array( $this, 'column_attr' ) );
		}

		/**
		 * Enqueue script required for slider.
		 *
		 * @since 3.2.0
		 * @access public
		 * @return void
		 */
		public function add_slider_js() {
			wp_enqueue_script( 'infi-elegant-background-slider' );
		}

		/**
		 * Update shortcode for container and columns and register new one for elegant elements.
		 *
		 * @since 3.2.0
		 * @access public
		 * @return void
		 */
		public function update_shortcode_function() {
			$settings = get_option( 'elegant_elements_settings', array() );

			if ( isset( $settings['remove_gradient_backgrounds'] ) && 1 === absint( $settings['remove_gradient_backgrounds'] ) ) {
				add_filter( 'pre_do_shortcode_tag', array( $this, 'change_container_shortcode_output' ), 11, 4 );
			} else {
				add_filter( 'elegant_background_slider', array( $this, 'change_container_shortcode_output' ), 10, 4 );
			}
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 3.2.0
		 * @param bool   $false Null.
		 * @param string $tag   Shortcode tag.
		 * @param string $attr  Shortcode attributes.
		 * @param array  $m     Regular expression match array.
		 * @return string       Shortcode output.
		 */
		public function change_container_shortcode_output( $false, $tag, $attr, $m ) {
			global $shortcode_tags;

			$settings = get_option( 'elegant_elements_settings', array() );

			if ( ( 'fusion_builder_container' !== $tag && 'fusion_builder_column' !== $tag ) || ! isset( $attr['enable_background_slider'] ) ) {
				return $false;
			}

			$content     = isset( $m[5] ) ? $m[5] : null;
			$slider_html = '';

			if ( 'fusion_builder_column' === $tag ) {

				if ( isset( $attr['enable_background_slider'] ) && 'yes' === trim( $attr['enable_background_slider'] ) ) {
					$this->add_slider_js();

					$this->column_args   = $attr;
					$transition          = ( isset( $this->args['elegant_transition_effect'] ) && '' !== $this->column_args['elegant_transition_effect'] ) ? $this->column_args['elegant_transition_effect'] : 'fade';
					$transition_delay    = ( isset( $this->column_args['elegant_transition_delay'] ) && '' !== $this->column_args['elegant_transition_delay'] ) ? $this->column_args['elegant_transition_delay'] : 3;
					$transition_duration = ( isset( $this->column_args['elegant_transition_duration'] ) && '' !== $this->column_args['elegant_transition_duration'] ) ? $this->column_args['elegant_transition_duration'] : 750;
					$image_scale         = $this->column_args['elegant_background_scale'];
					$slider_images       = apply_filters( 'elegant_slider_background_column', $this->column_args );
					$slider_html         = '<script class="elegant-column-background-slider" data-no-defer="1" data-image-scale="' . $image_scale . '" data-transition="' . $transition . '" data-transition-delay="' . $transition_delay * 1000 . '" data-transition-duration="' . $transition_duration . '">' . wp_json_encode( $slider_images ) . '</script>';
					$content             = $content . $slider_html;
				}

				$this->column_counter++;
			} elseif ( 'fusion_builder_container' === $tag ) {

				if ( isset( $attr['enable_background_slider'] ) && 'yes' === trim( $attr['enable_background_slider'] ) ) {
					$this->add_slider_js();

					$this->args          = $attr;
					$transition          = ( isset( $this->args['elegant_transition_effect'] ) && '' !== $this->args['elegant_transition_effect'] ) ? $this->args['elegant_transition_effect'] : 'fade';
					$transition_delay    = ( isset( $this->args['elegant_transition_delay'] ) && '' !== $this->args['elegant_transition_delay'] ) ? $this->args['elegant_transition_delay'] : 3;
					$transition_duration = ( isset( $this->args['elegant_transition_duration'] ) && '' !== $this->args['elegant_transition_duration'] ) ? $this->args['elegant_transition_duration'] : 750;
					$image_scale         = isset( $this->args['elegant_background_scale'] ) ? $this->args['elegant_background_scale'] : 'cover';
					$slider_images       = apply_filters( 'elegant_slider_background', $this->args );
					$slider_html         = '<script class="elegant-row-background-slider" data-no-defer="1" data-image-scale="' . $image_scale . '" data-transition="' . $transition . '" data-transition-delay="' . $transition_delay * 1000 . '" data-transition-duration="' . $transition_duration . '">' . wp_json_encode( $slider_images ) . '</script>';
					$content             = $content . $slider_html;
				}

				$this->container_counter++;
			}

			return $content;

		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 3.2.0
		 * @return array
		 */
		public function attr() {

			if ( ! isset( $this->args['enable_background_slider'] ) ) {
				return;
			}

			$images = $this->args['image_ids'];
			$images = explode( ',', $images );

			if ( empty( $images ) ) {
				return;
			}

			$slider_images = array();

			foreach ( $images as $image_id ) {
				$image = wp_get_attachment_image_src( $image_id, 'full' );

				if ( isset( $image[0] ) ) {
					$image_url       = $image[0];
					$image_url       = esc_url( $image_url );
					$slider_images[] = $image_url;
				}
			}

			return $slider_images;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 3.2.0
		 * @return array
		 */
		public function column_attr() {
			if ( ! isset( $this->column_args['enable_background_slider'] ) ) {
				return;
			}

			$images = $this->column_args['image_ids'];
			$images = explode( ',', $images );

			if ( empty( $images ) ) {
				return;
			}

			$slider_images = array();

			foreach ( $images as $image_id ) {
				$image           = wp_get_attachment_image_src( $image_id, 'full' );
				$image_url       = $image[0];
				$image_url       = esc_url( $image_url );
				$slider_images[] = $image_url;
			}

			return $slider_images;
		}
	}

	if ( empty( $settings ) || ( isset( $settings['remove_background_sliders'] ) && 1 !== absint( $settings['remove_background_sliders'] ) ) ) {
		new IEE_Background_Slider();
	}
} // End if().
