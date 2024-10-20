<?php
// Get saved settings.
$settings = get_option( 'elegant_elements_settings', array() );

if ( ! class_exists( 'IEE_Gradient_Backgrounds' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class IEE_Gradient_Backgrounds extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $args;

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.1.0
		 * @var array
		 */
		protected $column_args;

		/**
		 * Container counter.
		 *
		 * @access private
		 * @since 1.0
		 * @var int
		 */
		private $container_counter = 1;

		/**
		 * Column counter.
		 *
		 * @access private
		 * @since 1.1.0
		 * @var int
		 */
		private $column_counter = 1;

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			// Pre-process the shortcodes.
			add_action( 'init', array( $this, 'update_shortcode_function' ) );

			// Generate gradient style from shortcode attributes.
			add_filter( 'elegant_gradient_backgrounds', array( $this, 'attr' ) );

			// Generate gradient style from shortcode attributes.
			add_filter( 'elegant_gradient_column_backgrounds', array( $this, 'column_attr' ), 10, 2 );
		}

		/**
		 * Update shortcode for container and columns and register new one for elegant elements.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function update_shortcode_function() {
			add_filter( 'pre_do_shortcode_tag', array( $this, 'change_container_shortcode_output' ), 99, 4 );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 1.0
		 * @param bool   $false Null.
		 * @param string $tag   Shortcode tag.
		 * @param array  $attr  Shortcode attributes.
		 * @param array  $m     Regular expression match array.
		 * @return string       Shortcode output.
		 */
		public function change_container_shortcode_output( $false, $tag, $attr, $m ) {
			global $shortcode_tags;

			$settings = get_option( 'elegant_elements_settings', array() );

			if ( 'fusion_builder_container' !== $tag && 'fusion_builder_column' !== $tag ) {
				return $false;
			}

			$content           = isset( $m[5] ) ? $m[5] : null;
			$this->column_args = $attr;

			$this->column_counter = wp_rand();

			if ( 'fusion_builder_column' === $tag ) {

				if ( empty( $settings ) || ( isset( $settings['remove_lottie_backgrounds'] ) && 1 !== absint( $settings['remove_lottie_backgrounds'] ) ) ) {
					// Add lottie animation to columb background.
					if ( isset( $attr['lottie_json_url'] ) && '' !== $attr['lottie_json_url'] ) {

						// Enqueue Lottie Player js.
						wp_enqueue_script( 'infi-lottie-player' );

						$attr_mode = '';
						if ( 'bounce' === $attr['animation_mode'] ) {
							$attr_mode = 'mode="bounce"';
						}

						$content = '<div class="lottie-background"><div class="lottie-background-wrapper"><lottie-player src="' . $attr['lottie_json_url'] . '" ' . $attr_mode . ' background="transparent" speed="1" loop autoplay></lottie-player></div></div>' . $content;
					}
				}

				if ( empty( $settings ) || ( isset( $settings['remove_gradient_backgrounds'] ) && 1 !== absint( $settings['remove_gradient_backgrounds'] ) ) ) {
					if ( isset( $attr['gradient_top_color'] ) && '' !== trim( $attr['gradient_top_color'] ) ) {
						$this->column_args    = $attr;
						$gradient_backgrounds = apply_filters( 'elegant_gradient_column_backgrounds', $this->column_args, $this->column_counter );
						$gradient_style       = '<style class="elegant-gradient-column" type="text/css">';
						$gradient_style      .= $gradient_backgrounds;
						$gradient_style      .= '</style>';
						$content              = $content . $gradient_style;

						if ( isset( $attr['class'] ) && '' !== $attr['class'] ) {
							$attr['class'] .= ' gradient-column-' . $this->column_counter;
						} else {
							$attr          = (array) $attr;
							$attr['class'] = 'gradient-column-' . $this->column_counter;
						}
					}
				}
			} elseif ( 'fusion_builder_container' === $tag ) {

				if ( empty( $settings ) || ( isset( $settings['remove_gradient_backgrounds'] ) && 1 !== absint( $settings['remove_gradient_backgrounds'] ) ) ) {
					if ( isset( $attr['gradient_top_color'] ) && '' !== trim( $attr['gradient_top_color'] ) ) {
						$this->args           = $attr;
						$gradient_backgrounds = apply_filters( 'elegant_gradient_backgrounds', $this->args );
						$gradient_style       = '<div class="elegant-gradient-row"></div>';
						$gradient_style      .= '<style type="text/css">';
						$gradient_style      .= $gradient_backgrounds;
						$gradient_style      .= '</style>';
						$content              = $content . $gradient_style;
					}

					if ( isset( $attr['class'] ) ) {
						$attr['class'] .= ' gradient-container-' . $this->container_counter;
					} else {
						$attr          = (array) $attr;
						$attr['class'] = 'gradient-container-' . $this->container_counter;
					}
				}

				$this->container_counter++;
			}

			if ( isset( $attr['enable_background_slider'] ) && 'yes' === $attr['enable_background_slider'] ) {
				if ( empty( $settings ) || ( isset( $settings['remove_background_sliders'] ) && 1 !== absint( $settings['remove_background_sliders'] ) ) ) {
					$m[5]    = $content;
					$content = apply_filters( 'elegant_background_slider', $false, $tag, $attr, $m );
				}
			}

			return call_user_func( $shortcode_tags[ $tag ], $attr, $content, $tag );
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function attr() {
			$class = '.fusion-fullwidth.fullwidth-box.gradient-container-' . $this->container_counter . ' .elegant-gradient-row:before';

			if ( ! isset( $this->args['gradient_top_color'] ) ) {
				return;
			}

			$gradient_top_color    = $this->args['gradient_top_color'];
			$gradient_bottom_color = $this->args['gradient_bottom_color'];
			$force_gradient        = ( isset( $this->args['gradient_force'] ) && 'yes' === $this->args['gradient_force'] ) ? '!important' : '';

			$direction          = ( isset( $this->args['ee_gradient_type'] ) ) ? $this->args['ee_gradient_type'] : $this->args['gradient_type'];
			$gradient_direction = ( 'vertical' == $direction ) ? 'top' : $this->args['gradient_direction'];

			if ( 'top' == $gradient_direction ) {
				// Safari 4-5, Chrome 1-9 support.
				$gradient = 'background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(' . $gradient_top_color . '), to(' . $gradient_bottom_color . '))' . $force_gradient . ';';
			} else {
				// Safari 4-5, Chrome 1-9 support.
				$gradient = 'background: -webkit-gradient(linear, left top, right top, from(' . $gradient_top_color . '), to(' . $gradient_bottom_color . '))' . $force_gradient . ';';
			}

			// Safari 5.1, Chrome 10+ support.
			$gradient .= 'background: -webkit-linear-gradient(' . $gradient_direction . ', ' . $gradient_top_color . ', ' . $gradient_bottom_color . ')' . $force_gradient . ';';

			// Firefox 3.6+ support.
			$gradient .= 'background: -moz-linear-gradient(' . $gradient_direction . ', ' . $gradient_top_color . ', ' . $gradient_bottom_color . ')' . $force_gradient . ';';

			// IE 10+ support.
			$gradient .= 'background: -ms-linear-gradient(' . $gradient_direction . ', ' . $gradient_top_color . ', ' . $gradient_bottom_color . ')' . $force_gradient . ';';

			// Opera 11.10+ support.
			$gradient .= 'background: -o-linear-gradient(' . $gradient_direction . ', ' . $gradient_top_color . ', ' . $gradient_bottom_color . ')' . $force_gradient . ';';

			$gradient_style  = '';
			$gradient_style .= $class . ' {';
			$gradient_style .= 'content: ""; position: absolute; width: 100%; height: 100%; top: 0; left: 0;';
			$gradient_style .= $gradient;
			$gradient_style .= '}';

			return $gradient_style;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.1.0
		 * @param array  $args    Column attributes.
		 * @param string $counter Column index counter.
		 * @return array
		 */
		public function column_attr( $args, $counter ) {
			if ( ! is_array( $args ) ) {
				return $args;
			}

			$class  = '.fusion-builder-row .fusion-layout-column.gradient-column-' . $counter;
			$class .= ' .fusion-column-wrapper:before';

			if ( ! isset( $args['gradient_top_color'] ) && '' !== $args['gradient_top_color'] ) {
				return;
			}

			$gradient_top_color    = $args['gradient_top_color'];
			$gradient_bottom_color = $args['gradient_bottom_color'];
			$force_gradient        = ( isset( $args['gradient_force'] ) && 'yes' === $args['gradient_force'] ) ? '!important' : '';

			$direction          = ( isset( $args['ee_gradient_type'] ) ) ? $args['ee_gradient_type'] : $args['gradient_type'];
			$gradient_direction = ( 'vertical' === $direction ) ? 'top' : ( isset( $args['gradient_direction'] ) ? $args['gradient_direction'] : '0deg' );

			if ( 'top' == $gradient_direction ) {
				// Safari 4-5, Chrome 1-9 support.
				$gradient = 'background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(' . $gradient_top_color . '), to(' . $gradient_bottom_color . '))' . $force_gradient . ';';
			} else {
				// Safari 4-5, Chrome 1-9 support.
				$gradient = 'background: -webkit-gradient(linear, left top, right top, from(' . $gradient_top_color . '), to(' . $gradient_bottom_color . '))' . $force_gradient . ';';
			}

			// Safari 5.1, Chrome 10+ support.
			$gradient .= 'background: -webkit-linear-gradient(' . $gradient_direction . ', ' . $gradient_top_color . ', ' . $gradient_bottom_color . ')' . $force_gradient . ';';

			// Firefox 3.6+ support.
			$gradient .= 'background: -moz-linear-gradient(' . $gradient_direction . ', ' . $gradient_top_color . ', ' . $gradient_bottom_color . ')' . $force_gradient . ';';

			// IE 10+ support.
			$gradient .= 'background: -ms-linear-gradient(' . $gradient_direction . ', ' . $gradient_top_color . ', ' . $gradient_bottom_color . ')' . $force_gradient . ';';

			// Opera 11.10+ support.
			$gradient .= 'background: -o-linear-gradient(' . $gradient_direction . ', ' . $gradient_top_color . ', ' . $gradient_bottom_color . ')' . $force_gradient . ';';

			$gradient_style  = '';
			$gradient_style .= $class . ' {';
			$gradient_style .= 'content: ""; position: absolute; width: 100%; height: 100%; top: 0; left: 0;z-index:-1;';
			$gradient_style .= $gradient;
			$gradient_style .= '}';

			return $gradient_style;
		}
	}

	new IEE_Gradient_Backgrounds();
} // End if().
