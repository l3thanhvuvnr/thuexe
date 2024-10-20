<?php
/**
 * Plugin Name: Elegant Elements for Fusion Builder
 * Plugin URI: https://fusionelegantelements.com/
 * Description: Elegant Elements add-on for Fusion Builder
 * Version: 3.6.7
 * Requires at least: 5.0
 * Requires PHP:      7.2
 * Author: InfiWebs
 * Author URI: https://www.infiwebs.com
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Plugin version.
if ( ! defined( 'ELEGANT_ELEMENTS_VERSION' ) ) {
	define( 'ELEGANT_ELEMENTS_VERSION', '3.6.7' );
}
// Plugin Root File.
if ( ! defined( 'ELEGANT_ELEMENTS_PLUGIN_FILE' ) ) {
	define( 'ELEGANT_ELEMENTS_PLUGIN_FILE', __FILE__ );
}
// Plugin Folder Path.
if ( ! defined( 'ELEGANT_ELEMENTS_PLUGIN_DIR' ) ) {
	define( 'ELEGANT_ELEMENTS_PLUGIN_DIR', wp_normalize_path( plugin_dir_path( ELEGANT_ELEMENTS_PLUGIN_FILE ) ) );
}
// Plugin Folder URL.
if ( ! defined( 'ELEGANT_ELEMENTS_PLUGIN_URL' ) ) {
	define( 'ELEGANT_ELEMENTS_PLUGIN_URL', plugin_dir_url( ELEGANT_ELEMENTS_PLUGIN_FILE ) );
}

global $elegant_js_folder_url, $elegant_js_folder_path, $elegant_css_folder_url, $elegant_css_folder_path;

// JS folder URL.
$elegant_js_folder_url = ELEGANT_ELEMENTS_PLUGIN_URL . 'assets/js/min';

// JS folder path.
$elegant_js_folder_path = ELEGANT_ELEMENTS_PLUGIN_DIR . 'assets/js/min';

// CSS folder URL.
$elegant_css_folder_url = ELEGANT_ELEMENTS_PLUGIN_URL . 'assets/css/min';

// CSS folder path.
$elegant_css_folder_path = ELEGANT_ELEMENTS_PLUGIN_DIR . 'assets/css/min';

if ( ! class_exists( 'Elegant_Elements_Fusion_Builder' ) ) {

	/**
	 * Main Elegant_Elements_Fusion_Builder Class.
	 *
	 * @since 1.0
	 */
	class Elegant_Elements_Fusion_Builder {

		/**
		 * The one, true instance of this object.
		 *
		 * @since 1.0
		 * @static
		 * @access private
		 * @var object
		 */
		private static $instance;

		/**
		 * Pre-built Templates.
		 *
		 * @static
		 * @access public
		 * @since 1.0.1
		 * @var array
		 */
		public static $templates = array();

		/**
		 * Elegant_Product_Registration
		 *
		 * @since 1.0
		 * @static
		 * @access public
		 * @var object Elegant_Product_Registration.
		 */
		public $registration;

		/**
		 * Code Patcher.
		 *
		 * @access public
		 * @since 3.4.0
		 * @var object
		 */
		public $patcher;

		/**
		 * Creates or returns an instance of this class.
		 *
		 * @since 1.0
		 * @static
		 * @access public
		 */
		public static function get_instance() {

			// If an instance hasn't been created and set to $instance create an instance and set it to $instance.
			if ( null === self::$instance ) {
				self::$instance = new Elegant_Elements_Fusion_Builder();
			}
			return self::$instance;
		}

		/**
		 * Initializes the plugin by setting localization, hooks, filters,
		 * and administrative functions.
		 *
		 * @since 1.0
		 * @access private
		 */
		private function __construct() {

			// Init patcher.
			$this->patcher = new Elegant_Elements_Patcher();

			// Add admin notice if Fusion Builder is deactivated or not installed.
			add_action( 'admin_notices', array( $this, 'fusion_builder_required_admin_notice' ) );

			// Load plugin textdomain.
			add_action( 'plugins_loaded', array( $this, 'textdomain' ) );

			// Enqueue styles on frontend.
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_styles' ), 9 );

			// Enqueue scripts on frontend.
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );

			// Enqueue scripts on backend.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

			// Add new setting fields to Fusion Builder.
			add_filter( 'fusion_builder_fields', array( $this, 'add_new_fields' ), 11 );

			// Add meta box for typography storage.
			add_action( 'add_meta_boxes', array( $this, 'elegant_typography_metabox' ) );

			// Save metabox value for typography.
			add_action( 'save_post', array( $this, 'elegant_typography_metabox_save' ) );
			add_action( 'pre_post_update', array( $this, 'elegant_typography_postmeta_save' ) );

			// Enqueue scripts on backend.
			add_action( 'admin_enqueue_scripts', array( $this, 'add_builder_scripts' ) );

			// Enqueue custom backbone templates for elegant elements.
			add_action( 'fusion_builder_after', array( $this, 'add_builder_templates' ) );

			// Add pre-built templates.
			add_filter( 'elegant_elements_templates', array( $this, 'elegant_elements_templates' ) );

			// Overwrite page template for template preview.
			add_filter( 'template_include', 'elegant_elements_preview_template' );

			if ( function_exists( 'fusion_is_builder_frame' ) && fusion_is_builder_frame() ) {
				// Add front-end templates.
				add_action( 'fusion_builder_before_init', array( $this, 'frontend_load_templates' ) );
				add_action( 'wp_enqueue_scripts', array( $this, 'live_scripts' ), 1001 );
				add_action( 'wp_print_scripts', array( $this, 'element_creator_scripts' ), 1001 );
			}

			if ( ( is_admin() && class_exists( 'Elegant_Product_Registration' ) ) || ( function_exists( 'fusion_is_builder_frame' ) && fusion_is_builder_frame() ) ) {
				$this->registration = new Elegant_Product_Registration(
					array(
						'type' => 'plugin',
						'name' => 'Elegant Elements for Fusion Builder',
					)
				);
			}
		}

		/**
		 * Enqueue required js on backend.
		 *
		 * @since 1.1.0
		 * @access public
		 * @return void
		 */
		public function add_builder_scripts() {
			global $pagenow, $typenow;
			if ( ( 'post.php' === $pagenow || 'post-new.php' === $pagenow ) && post_type_supports( $typenow, 'editor' ) ) {
				wp_enqueue_script( 'fusion-builder-elegant-templates-masonry', plugins_url( 'inc/app/js/masonry.min.js', __FILE__ ), '', '1.0', true );
				wp_enqueue_script( 'fusion-builder-elegant-templates', plugins_url( 'inc/app/js/fusion-builder-elegant-templates.js', __FILE__ ), '', '1.0', true );
			}
		}

		/**
		 * Add builder templates required.
		 *
		 * @since 1.1.0
		 * @access public
		 * @return void
		 */
		public function add_builder_templates() {

			include ELEGANT_ELEMENTS_PLUGIN_DIR . 'inc/app/elegant-templates.php';

		}

		/**
		 * Pre-built templates.
		 *
		 * @since 1.1.0
		 * @param array $template Pre-built template.
		 * @return array $templates
		 */
		public function elegant_elements_templates( $template ) {
			global $wp_filesystem;

			if ( empty( $wp_filesystem ) ) {
				require_once wp_normalize_path( ABSPATH . '/wp-admin/includes/file.php' );
				WP_Filesystem();
			}

			$templates_json = ELEGANT_ELEMENTS_PLUGIN_DIR . 'inc/templates/templates.json';
			$template_json  = $wp_filesystem->get_contents( $templates_json );

			$template_array = json_decode( $template_json, true );

			self::$templates = $template_array;

			return self::$templates;
		}

		/**
		 * Displays admin notice if Fusion Builder is not active.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return void
		 */
		public function fusion_builder_required_admin_notice() {
			if ( ! class_exists( 'FusionBuilder' ) ) {
				echo '<div class="notice notice-warning is-dismissible">
	             <p>' . esc_attr__( 'Elegant Elements is installed and activated correctly. However, it will not take any effect until you install and activate Fusion Builder.', 'elegant-elements' ) . '</p>
	         </div>';
			}
		}

		/**
		 * Loads the plugin language files.
		 *
		 * @access public
		 * @since 1.0
		 * @return void
		 */
		public function textdomain() {

			// Set text domain.
			$domain = 'elegant-elements';

			// Load the plugin textdomain.
			load_plugin_textdomain( $domain, false, dirname( plugin_basename( ELEGANT_ELEMENTS_PLUGIN_FILE ) ) . '/languages/' );
		}

		/**
		 * Enqueue elegant elements styles on frontend.
		 *
		 * @since 1.0
		 * @access public
		 * @return void
		 */
		public function frontend_styles() {
			global $post, $fusion_settings;

			$custom_fonts = array();

			if ( $fusion_settings ) {
				$custom_fonts = $fusion_settings->get( 'custom_fonts' );
				$custom_fonts = isset( $custom_fonts['name'] ) ? $custom_fonts['name'] : array();
			}

			// Register styles.
			wp_register_style( 'infi-elegant-elements', ELEGANT_ELEMENTS_PLUGIN_URL . 'assets/css/min/elegant-elements.min.css', '', ELEGANT_ELEMENTS_VERSION );
			wp_register_style( 'infi-elegant-animations', ELEGANT_ELEMENTS_PLUGIN_URL . 'assets/css/min/infi-css-animations.min.css', '', ELEGANT_ELEMENTS_VERSION );
			wp_register_style( 'infi-elegant-combined-css', ELEGANT_ELEMENTS_PLUGIN_URL . 'assets/css/min/elegant-elements-combined.min.css', '', ELEGANT_ELEMENTS_VERSION );

			if ( is_404() || is_search() ) {
				// Enqueue combined css.
				wp_enqueue_style( 'infi-elegant-combined-css' );
			} elseif ( $post ) {

				// Enqueue styles on frontend.
				wp_enqueue_style( 'infi-elegant-elements' );
				wp_enqueue_style( 'infi-elegant-animations' );

				// Register styles for each element.
				$elements = array(
					'cards',
					'carousel',
					'testimonials',
					'rotating_text',
					'typewriter_text',
					'partner_logo',
					'promo_box',
					'fancy_banner',
					'special_heading',
					'dual_button',
					'modal_dialog',
					'fancy_button',
					'notification_box',
					'profile_panel',
					'gradient_heading',
					'image_filters',
					'expanding_sections',
					'image_compare',
					'list_box',
					'instagram_gallery',
					'skew_heading',
					'image_hotspot',
					'image_mask_heading',
					'icon_block',
					'dual_style_heading',
					'image_separator',
					'content_toggle',
					'faq_rich_snippets',
					'video_list',
					'advanced_video',
					'business_hours',
					'cube_box',
					'whatsapp_chat_button',
					'ribbon',
					'distortion_hover_image',
					'instagram_teaser_box',
					'instagram_profile_card',
					'off_canvas_content',
					'image',
					'image_swap',
					'slicebox_image_slider',
					'particles_banner',
					'accordion_slider',
					'text_path',
					'star_rating',
					'lottie_content_box',
					'animated_blob_shape_image',
					'image_overlay_button',
					'hero_section',
				);

				// Get saved settings.
				$defaults = ( function_exists( 'wc' ) ) ? array( 'enqueue_combined_scripts' => 1 ) : array();
				$settings = get_option( 'elegant_elements_settings', $defaults );

				$is_fb_live = ( isset( $_GET['builder'] ) ) ? true : false; // @codingStandardsIgnoreLine

				if ( $is_fb_live ) {
					// Enqueue combined css.
					wp_enqueue_style( 'infi-elegant-combined-css' );
				}

				if ( empty( $settings ) || ( isset( $settings['enqueue_combined_scripts'] ) && 1 !== absint( $settings['enqueue_combined_scripts'] ) ) ) {

					// Early exit if there is no post content.
					if ( ! isset( $post->post_content ) ) {
						return;
					}

					foreach ( $elements  as $element ) {
						$element_handle = str_replace( '_', '-', $element );

						wp_register_style( 'infi-elegant-' . $element_handle, ELEGANT_ELEMENTS_PLUGIN_URL . 'assets/css/min/infi-elegant-' . $element_handle . '.min.css', '', ELEGANT_ELEMENTS_VERSION );

						if ( has_shortcode( $post->post_content, 'iee_' . $element ) ) {
							wp_enqueue_style( 'infi-elegant-' . $element_handle );
						}
					}
				} else {
					// Enqueue combined css.
					wp_enqueue_style( 'infi-elegant-combined-css' );
				}

				$enqueue_google_fonts = apply_filters( 'elegant_elements_enqueue_google_fonts', true );

				if ( ! $enqueue_google_fonts ) {
					return;
				}

				// Enqueue Google Fonts used in this post.
				$values     = get_post_custom( $post->ID );
				$typography = isset( $values['elegant_typography'] ) ? esc_attr( $values['elegant_typography'][0] ) : '';

				$typography_split = explode( '|', $typography );
				$fonts_array      = array();

				foreach ( $typography_split as $key => $fonts ) {
					if ( '' !== $fonts ) {
						$fonts_split = explode( ':', $fonts );
						if ( ! empty( $fonts_array ) && ! isset( $fonts_array[ $fonts_split[0] ] ) ) {
							$fonts_array[ $fonts_split[0] ] = array();
						}

						if ( isset( $fonts_split[1] ) && is_array( $fonts_split ) ) {
							$fonts_array[ $fonts_split[0] ][] = $fonts_split['1'];
						} else {
							$fonts_array[ $fonts_split[0] ] = '';
						}
					}
				}

				$font_with_variants = '';
				if ( ! empty( $fonts_array ) ) {
					foreach ( $fonts_array as $family => $variants ) {
						$separator           = ( '' !== $font_with_variants ) ? '|' : '';
						$font_with_variants .= $separator . $family . ( ! empty( $variants ) ? ':' . implode( ',', $variants ) : '' );
					}
				}

				// Get default typography for title and description.
				$default_typography = elegant_get_default_typography();

				// Get all system fonts.
				$system_fonts = elegant_get_system_fonts();

				// Title typography defaults.
				$title_typography_family = explode( ':', $default_typography['title'] )[0];
				$title_typography        = ( isset( $default_typography['title'] ) && ! isset( $system_fonts[ $title_typography_family ] ) ) ? $default_typography['title'] : '';

				// Description typography defaults.
				$description_typography_family = explode( ':', $default_typography['description'] )[0];
				$description_typography        = ( isset( $default_typography['description'] ) && ! isset( $system_fonts[ $description_typography_family ] ) ) ? $default_typography['description'] : '';

				if ( ! empty( $custom_fonts ) && in_array( explode( ':', $typography )[0], $custom_fonts ) ) {
					$font_with_variants = '';
				}

				if ( ! empty( $custom_fonts ) && in_array( explode( ':', $title_typography )[0], $custom_fonts ) ) {
					$title_typography = '';
				}

				if ( ! empty( $custom_fonts ) && in_array( explode( ':', $description_typography )[0], $custom_fonts ) ) {
					$description_typography = '';
				}

				$separator           = ( '' !== $font_with_variants ) ? '|' : '';
				$font_with_variants .= ( '' !== $title_typography ) ? $separator . $title_typography : '';
				$font_with_variants .= ( '' !== $description_typography ) ? $separator . $description_typography : '';

				if ( '' !== $font_with_variants ) {
					if ( 'local' === $fusion_settings->get( 'gfonts_load_method' ) && class_exists( 'Fusion_GFonts_Downloader' ) ) {
						$typography_split = explode( '|', $font_with_variants );
						$css              = '';
						foreach ( $typography_split as $key => $fonts ) {
							$font_split         = explode( ':', $fonts );
							$font_transient_id  = str_replace( array( ' ', '-', ':' ), '_', $fonts );
							$font_transient_css = get_transient( 'font_css_' . $font_transient_id );

							if ( false === $font_transient_css ) {
								$family  = $font_split[0];
								$variant = $font_split[1];
								$font    = new Fusion_GFonts_Downloader( $family );

								$font_transient_css = $font->get_fontface_css( array( $variant ) );
								set_transient( 'font_css_' . $font_transient_id, $font_transient_css, 60 * 60 * 24 );
							}

							$css .= $font_transient_css;

						}
						?>
						<style type="text/css" id="elegant-google-fonts">
						<?php echo $css; // @codingStandardsIgnoreLine ?>
						</style>
						<?php
					} else {
						wp_enqueue_style( 'elegant-google-fonts', 'https://fonts.googleapis.com/css?display=swap&family=' . $font_with_variants, '', ELEGANT_ELEMENTS_VERSION );
					}
				}
			}
		}

		/**
		 * Enqueue elegant elements scripts on frontend.
		 *
		 * @since 1.0
		 * @access public
		 * @return void
		 */
		public function frontend_scripts() {
			global $elegant_js_folder_url;

			// Register scripts for frontend.
			wp_register_script( 'infi-lottie-player', str_replace( '/min', '', $elegant_js_folder_url ) . '/lottie-player.min.js', array(), ELEGANT_ELEMENTS_VERSION, true );
			wp_register_script( 'infi-distortion-imagesloaded', str_replace( '/min', '/distortion', $elegant_js_folder_url ) . '/imagesloaded.pkgd.min.js', array(), ELEGANT_ELEMENTS_VERSION, true );
			wp_register_script( 'infi-distortion-three', str_replace( '/min', '/distortion', $elegant_js_folder_url ) . '/three.min.js', array(), ELEGANT_ELEMENTS_VERSION, true );
			wp_register_script( 'infi-distortion-tweenmax', str_replace( '/min', '/distortion', $elegant_js_folder_url ) . '/TweenMax.min.js', array(), ELEGANT_ELEMENTS_VERSION, true );
			wp_register_script( 'infi-distortion-hover', $elegant_js_folder_url . '/infi-elegant-hover-effect.min.js', array( 'infi-distortion-imagesloaded', 'infi-distortion-three', 'infi-distortion-tweenmax' ), ELEGANT_ELEMENTS_VERSION, true );
			wp_register_script( 'infi-off-canvas-content', $elegant_js_folder_url . '/infi-elegant-off-canvas-content.min.js', array( 'jquery' ), ELEGANT_ELEMENTS_VERSION, true );
			wp_register_script( 'infi-elegant-background-slider', $elegant_js_folder_url . '/infi-elegant-background-slider.min.js', array( 'jquery' ), ELEGANT_ELEMENTS_VERSION, true );
			wp_register_script( 'infi-elegant-slicebox-image-slider', $elegant_js_folder_url . '/infi-elegant-slicebox-image-slider.min.js', array( 'jquery', 'infi-distortion-imagesloaded' ), ELEGANT_ELEMENTS_VERSION, true );
			wp_register_script( 'infi-particles-banner', $elegant_js_folder_url . '/infi-elegant-particles.min.js', array( 'jquery' ), ELEGANT_ELEMENTS_VERSION, true );
			wp_register_script( 'infi-qrcode', $elegant_js_folder_url . '/infi-elegant-qrcode.min.js', array( 'jquery' ), ELEGANT_ELEMENTS_VERSION, true );
		}

		/**
		 * Enqueue required js on backend.
		 *
		 * @since 1.0
		 * @access public
		 * @return void
		 */
		public function admin_scripts() {
			// Enqueue scripts and styles on backend.
			global $pagenow, $typenow;
			if ( ( 'post.php' === $pagenow || 'post-new.php' === $pagenow ) && post_type_supports( $typenow, 'editor' ) ) {
				wp_enqueue_style( 'infi-icomoon', ELEGANT_ELEMENTS_PLUGIN_URL . 'assets/icomoon/icomoon.css', '', ELEGANT_ELEMENTS_VERSION );
				wp_enqueue_style( 'infi-admin', ELEGANT_ELEMENTS_PLUGIN_URL . 'assets/css/min/elegant-elements-admin.min.css', '', ELEGANT_ELEMENTS_VERSION );

				if ( class_exists( 'FusionBuilder' ) ) {
					wp_enqueue_script( 'elegant-elements-custom-settings-js', ELEGANT_ELEMENTS_PLUGIN_URL . 'elements/custom/js/elegant-settings.js', array( 'jquery' ), ELEGANT_ELEMENTS_VERSION, true );
					wp_enqueue_script( 'elegant-elements-admin-js', ELEGANT_ELEMENTS_PLUGIN_URL . 'assets/js/min/elegant-elements-admin.min.js', array( 'jquery' ), ELEGANT_ELEMENTS_VERSION, true );
					wp_enqueue_script( 'elegant-elements-common-js', ELEGANT_ELEMENTS_PLUGIN_URL . 'inc/app/js/common.js', array( 'jquery' ), ELEGANT_ELEMENTS_VERSION, true );
				}

				// Localize Scripts.
				$webfonts = elegant_elements_get_google_fonts();
				wp_localize_script( 'elegant-elements-admin-js', 'elegantGoogleFonts', $webfonts );
				wp_localize_script( 'elegant-elements-admin-js', 'elegantText', self::elegant_elements_text_strings() );
				wp_localize_script(
					'elegant-elements-admin-js',
					'elegantElementsConfig',
					array(
						'ajaxurl'                    => admin_url( 'admin-ajax.php' ),
						'elegant_templates_security' => wp_create_nonce( 'elegant_templates_security' ),
						'elegant_load_nonce'         => wp_create_nonce( 'elegant_load_nonce' ),
					)
				);
			}
		}

		/**
		 * Elegant Elements text strings.
		 *
		 * @since 1.0
		 * @access public
		 * @return array Text strings to be localized.
		 */
		public static function elegant_elements_text_strings() {

			$text_strings = array(
				'select_icon'       => esc_attr__( 'Select Icon', 'elegant-elements' ),
				'insert_icon'       => esc_attr__( 'Insert Icon', 'elegant-elements' ),
				'add_field'         => esc_attr__( 'Add Field', 'elegant-elements' ),
				'templates'         => esc_attr__( 'Templates', 'elegant-elements' ),
				'elegant_templates' => esc_attr__( 'Elegant Templates', 'elegant-elements' ),
			);

			return $text_strings;
		}

		/**
		 * Add templates required for elegant elements on front-end.
		 *
		 * @since 2.0
		 * @access public
		 * @return void
		 */
		public function frontend_load_templates() {
			foreach ( glob( ELEGANT_ELEMENTS_PLUGIN_DIR . '/elements/front-end/templates/*.php', GLOB_NOSORT ) as $filename ) {
				include $filename;
			}

			require_once ELEGANT_ELEMENTS_PLUGIN_DIR . 'inc/class-elegant-envato-api.php';
			require_once ELEGANT_ELEMENTS_PLUGIN_DIR . 'inc/class-elegant-product-registration.php';
			$is_registered = $this->registration->is_registered();

			include ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/app/elegant-templates.php';

			wp_enqueue_style( 'infi-icomoon', ELEGANT_ELEMENTS_PLUGIN_URL . 'assets/icomoon/icomoon.css', '', ELEGANT_ELEMENTS_VERSION );
			wp_enqueue_style( 'infi-admin', ELEGANT_ELEMENTS_PLUGIN_URL . 'assets/css/min/elegant-elements-admin.min.css', '', ELEGANT_ELEMENTS_VERSION );

			wp_enqueue_script( 'fusion-builder-elegant-templates-masonry', plugins_url( 'inc/app/js/masonry.min.js', __FILE__ ), '', '1.0', true );
			wp_enqueue_script( 'elegant-elements-toolbar-view-js', ELEGANT_ELEMENTS_PLUGIN_URL . 'elements/front-end/app/view-toolbar.js', array( 'jquery' ), ELEGANT_ELEMENTS_VERSION, true );
			wp_enqueue_script( 'elegant-elements-templates-js', ELEGANT_ELEMENTS_PLUGIN_URL . 'elements/front-end/js/fusion-builder-elegant-templates.js', array( 'jquery' ), ELEGANT_ELEMENTS_VERSION, true );
			wp_enqueue_script( 'elegant-elements-custom-settings-js', ELEGANT_ELEMENTS_PLUGIN_URL . 'elements/front-end/js/elegant-settings.js', array( 'jquery' ), ELEGANT_ELEMENTS_VERSION, true );
			wp_enqueue_script( 'elegant-elements-custom-js', ELEGANT_ELEMENTS_PLUGIN_URL . 'elements/front-end/js/custom.js', array( 'jquery' ), ELEGANT_ELEMENTS_VERSION, true );
			wp_enqueue_script( 'elegant-elements-common-js', ELEGANT_ELEMENTS_PLUGIN_URL . 'inc/app/js/common.js', array( 'jquery' ), ELEGANT_ELEMENTS_VERSION, true );
			wp_enqueue_script( 'elegant-elements-carousel-js', ELEGANT_ELEMENTS_PLUGIN_URL . 'assets/js/min/infi-elegant-carousel.min.js', array( 'jquery' ), ELEGANT_ELEMENTS_VERSION, true );
			wp_enqueue_script( 'elegant-elements-rotating-text-js', ELEGANT_ELEMENTS_PLUGIN_URL . 'assets/js/min/infi-elegant-rotating-text.min.js', array( 'jquery' ), ELEGANT_ELEMENTS_VERSION, true );

			// Localize Scripts.
			$webfonts = elegant_elements_get_google_fonts();
			wp_localize_script( 'elegant-elements-toolbar-view-js', 'elegantGoogleFonts', $webfonts );
			wp_localize_script( 'elegant-elements-toolbar-view-js', 'elegantText', self::elegant_elements_text_strings() );
			wp_localize_script(
				'elegant-elements-toolbar-view-js',
				'elegantElementsConfig',
				array(
					'ajaxurl'                    => admin_url( 'admin-ajax.php' ),
					'elegant_templates_security' => wp_create_nonce( 'elegant_templates_security' ),
					'elegant_load_nonce'         => wp_create_nonce( 'elegant_load_nonce' ),
				)
			);
		}

		/**
		 * Add js view templates required for elegant elements on front-end.
		 *
		 * @since 2.0
		 * @access public
		 * @return void
		 */
		public function live_scripts() {
			// Enqueue Lottie Player js.
			wp_enqueue_script( 'infi-lottie-player' );

			foreach ( glob( ELEGANT_ELEMENTS_PLUGIN_DIR . '/elements/front-end/views/*.js', GLOB_NOSORT ) as $filepath ) {
				$filename = basename( $filepath, '.js' );
				wp_enqueue_script( 'elegant_elements_view_' . $filename, ELEGANT_ELEMENTS_PLUGIN_URL . 'elements/front-end/views/' . $filename . '.js', array(), ELEGANT_ELEMENTS_VERSION, true );
			}
		}

		/**
		 * Add js view templates required for elegant elements created with element creator on front-end.
		 *
		 * @since 3.0
		 * @access public
		 * @return void
		 */
		public function element_creator_scripts() {
			// Get all elements created.
			$element_creator_posts = get_posts(
				array(
					'posts_per_page' => -1,
					'post_type'		 => 'element_creator',
				)
			);

			// Elementor creator scripts.
			if ( $element_creator_posts && function_exists( 'get_field' ) ) {
				foreach ( $element_creator_posts as $key => $element ) {
					elegant_element_creator_generate_view( $element );
				}
			}
		}

		/**
		 * Add new setting fields to Fusion Builder.
		 *
		 * @since 1.0
		 * @access public
		 * @param array $fields The array of fields added with filter.
		 * @return array
		 */
		public function add_new_fields( $fields ) {
			$fields[] = array( 'elegant_typography', ELEGANT_ELEMENTS_PLUGIN_DIR . 'inc/fields/typography.php' );
			$fields[] = array( 'elegant_textarea', ELEGANT_ELEMENTS_PLUGIN_DIR . 'inc/fields/elegant-textarea.php' );
			$fields[] = array( 'elegant_select_optgroup', ELEGANT_ELEMENTS_PLUGIN_DIR . 'inc/fields/select-optgroup.php' );
			$fields[] = array( 'elegant_blob_shape_generator', ELEGANT_ELEMENTS_PLUGIN_DIR . 'inc/fields/blob-generator.php' );
			$fields[] = array( 'elegant_upload_images', ELEGANT_ELEMENTS_PLUGIN_DIR . 'inc/fields/upload-images.php' );

			return $fields;
		}

		/**
		 * Meta box for typography.
		 *
		 * @since 1.0
		 * @access public
		 * @return void
		 */
		public function elegant_typography_metabox() {
			$post_types = get_post_types();
			add_meta_box( 'elegant-typography-metabox', 'Elegant Typography', array( $this, 'elegant_typography_metabox_render' ), $post_types, 'normal', 'high' );
		}

		/**
		 * Render meta box for typography.
		 *
		 * @since 1.0
		 * @access public
		 * @return void
		 */
		public function elegant_typography_metabox_render() {
			global $post;

			$values     = get_post_custom( $post->ID );
			$typography = isset( $values['elegant_typography'] ) ? esc_attr( $values['elegant_typography'][0] ) : '';

			// We'll use this nonce field later on when saving.
			wp_nonce_field( 'typography_meta_box_nonce', 'typography_meta_box_nonce' );
			?>
			<p>
			<input type="text" name="elegant_typography" id="elegant_typography" value="<?php echo esc_attr( $typography ); ?>" />
			</p>
			<style type="text/css">
			label[for="elegant-typography-metabox-hide"],
			#elegant-typography-metabox {
				display: none !important;
			}
			</style>
			<?php
		}

		/**
		 * Save typography value.
		 *
		 * @since 1.0
		 * @access public
		 * @return void
		 */
		public function elegant_typography_metabox_save() {
			// Bail if we're doing an auto save.
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			// if our nonce isn't there, or we can't verify it, bail.
			if ( ! isset( $_POST['typography_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['typography_meta_box_nonce'], 'typography_meta_box_nonce' ) ) { // @codingStandardsIgnoreLine
				return;
			}

			// Bail, if post has no content.
			if ( ! isset( $_POST['post_content'] ) ) {
				return;
			}

			$post_content = stripcslashes( $_POST['post_content'] ); // @codingStandardsIgnoreLine
			$typography   = elegant_elements_parse_typography( $post_content );

			update_post_meta( $_POST['ID'], 'elegant_typography', $typography ); // @codingStandardsIgnoreLine
		}

		/**
		 * Save typography value.
		 *
		 * @since 2.0
		 * @access public
		 * @return void
		 */
		public function elegant_typography_postmeta_save() {
			// Bail if we're doing an auto save.
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			// Reset creator transients.
			delete_transient( 'elegant_element_creator_posts' );

			if ( isset( $_POST['action'] ) && 'fusion_app_save_post_content' === $_POST['action'] ) { // @codingStandardsIgnoreLine
				$post_content = stripcslashes( $_POST['post_content'] ); // @codingStandardsIgnoreLine
				$typography   = elegant_elements_parse_typography( $post_content );

				update_post_meta( $_POST['post_id'], 'elegant_typography', $typography ); // @codingStandardsIgnoreLine
			}
		}
	} // End Elegant_Elements_Fusion_Builder class.
} // End if statement.

/**
 * Instantiates the Elegant_Elements_Fusion_Builder class.
 * Make sure the class is properly set-up.
 * The Elegant_Elements_Fusion_Builder class is a singleton
 * so we can directly access the one true Elegant_Elements_Fusion_Builder object using this function.
 *
 * @return object Elegant_Elements_Fusion_Builder
 */
function infi_elegant_elements() {
	return Elegant_Elements_Fusion_Builder::get_instance();
}

/**
 * Instantiate Elegant_Elements_Fusion_Builder class.
 *
 * @since 1.0
 * @return void
 */
function infi_elegant_elements_activate() {
	if ( class_exists( 'FusionBuilder' ) ) {
		infi_elegant_elements();
	}

	if ( ! class_exists( 'Fusion_Cache' ) && defined( 'FUSION_BUILDER_PLUGIN_DIR' ) ) {
		if ( is_file( FUSION_BUILDER_PLUGIN_DIR . 'inc/lib/inc/class-fusion-cache.php' ) ) {
			include_once FUSION_BUILDER_PLUGIN_DIR . 'inc/lib/inc/class-fusion-cache.php';
		}
	}

	$version = get_option( 'elegant_elements_version', false );

	if ( ! $version && class_exists( 'Fusion_Cache' ) ) {
		// Clear cache if no version number found.
		$fusion_cache = new Fusion_Cache();
		$fusion_cache->reset_all_caches();
	} elseif ( version_compare( ELEGANT_ELEMENTS_VERSION, $version, '>' ) && class_exists( 'Fusion_Cache' ) ) {

		// Clear cache as the version from database is different than current version.
		$fusion_cache = new Fusion_Cache();
		$fusion_cache->reset_all_caches();
	}

	// Update current version number to database.
	update_option( 'elegant_elements_version', ELEGANT_ELEMENTS_VERSION );
}
add_action( 'after_setup_theme', 'infi_elegant_elements_activate', 11 );

/**
 * Initialize Elegant elements once FB elements are loaded.
 *
 * @since 1.0
 * @return void
 */
function init_elegant_elements() {
	foreach ( glob( ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/*.php', GLOB_NOSORT ) as $filename ) {
		require_once $filename;
	}

	if ( class_exists( 'ACF' ) ) {
		require_once ELEGANT_ELEMENTS_PLUGIN_DIR . 'inc/creator/acf/options/elegant-elements-creator.php';
		require_once ELEGANT_ELEMENTS_PLUGIN_DIR . 'inc/creator/class-elements-creator-mapping.php';
	}
}
add_action( 'fusion_builder_shortcodes_init', 'init_elegant_elements' );

require_once ELEGANT_ELEMENTS_PLUGIN_DIR . 'inc/helpers.php';
require_once ELEGANT_ELEMENTS_PLUGIN_DIR . 'inc/class-elegant-elements-updater.php';
require_once ELEGANT_ELEMENTS_PLUGIN_DIR . 'inc/class-elegant-envato-api.php';
require_once ELEGANT_ELEMENTS_PLUGIN_DIR . 'inc/class-elegant-product-registration.php';
require_once ELEGANT_ELEMENTS_PLUGIN_DIR . 'inc/class-elegant-elements-admin.php';
require_once ELEGANT_ELEMENTS_PLUGIN_DIR . 'inc/demo-importer/init.php';
require_once ELEGANT_ELEMENTS_PLUGIN_DIR . 'inc/creator/helpers.php';
require_once ELEGANT_ELEMENTS_PLUGIN_DIR . 'inc/creator/acf/elegant-elements-acf-fields.php';
require_once ELEGANT_ELEMENTS_PLUGIN_DIR . 'inc/creator/class-elegant-elements-creator.php';
require_once ELEGANT_ELEMENTS_PLUGIN_DIR . 'inc/class-elegant-elements-patcher.php';

/**
 * Auto activate elements on plugin activation.
 *
 * @since 1.0
 * @return void
 */
function auto_activate_elegant_elements() {

	// Auto activate element.
	if ( function_exists( 'fusion_builder_auto_activate_element' ) ) {
		$elements = array(
			'iee_testimonials',
			'iee_rotating_text',
			'iee_typewriter_text',
			'iee_empty_space',
			'iee_partner_logos',
			'iee_promo_box',
			'iee_fancy_banner',
			'iee_contact_form7',
			'iee_special_heading',
			'iee_dual_button',
			'iee_modal_dialog',
			'iee_fancy_button',
			'iee_carousel',
			'iee_notification_box',
			'iee_cards',
			'iee_profile_panel',
			'iee_gradient_heading',
			'iee_image_filters',
			'iee_expanding_sections',
			'iee_image_compare',
			'iee_list_box',
			'iee_instagram_gallery',
			'iee_skew_heading',
			'iee_image_hotspot',
			'iee_image_mask_heading',
			'iee_icon_block',
			'iee_dual_style_heading',
			'iee_image_separator',
			'iee_content_toggle',
			'iee_faq_rich_snippets',
			'iee_video_list',
			'iee_retina_image',
			'iee_advanced_video',
			'iee_business_hours',
			'iee_document_viewer',
			'iee_cube_box',
			'iee_big_caps',
			'iee_whatsapp_chat_button',
			'iee_ribbon',
			'iee_animated_dividers',
			'iee_lottie_animated_image',
			'iee_distortion_hover_image',
			'iee_blob_shape_image',
			'iee_instagram_teaser_box',
			'iee_instagram_profile_card',
			'iee_off_canvas_content',
			'iee_horizontal_scrolling_images',
			'iee_image',
			'iee_image_swap',
			'iee_slicebox_image_slider',
			'iee_particles_banner',
			'iee_accordion_slider',
			'iee_qrcode',
			'iee_text_path',
			'iee_star_rating',
			'iee_lottie_content_box',
			'iee_animated_blob_shape_image',
			'iee_image_overlay_button',
			'iee_hero_section',
		);

		foreach ( $elements as $element ) {
			fusion_builder_auto_activate_element( $element );
		}
	}

	$version = get_option( 'elegant_elements_version', false );

	if ( ! $version && class_exists( 'Fusion_Cache' ) ) {

		// Clear cache if no version number found.
		$fusion_cache = new Fusion_Cache();
		$fusion_cache->reset_all_caches();

	} elseif ( version_compare( ELEGANT_ELEMENTS_VERSION, $version, '>' ) && class_exists( 'Fusion_Cache' ) ) {

		// Clear cache as the version from database is different than current version.
		$fusion_cache = new Fusion_Cache();
		$fusion_cache->reset_all_caches();
	}

	// Update current version number to database.
	update_option( 'elegant_elements_version', ELEGANT_ELEMENTS_VERSION );

	// Reset creator transients.
	delete_transient( 'elegant_element_creator_posts' );
}

register_activation_hook( ELEGANT_ELEMENTS_PLUGIN_FILE, 'auto_activate_elegant_elements' );
