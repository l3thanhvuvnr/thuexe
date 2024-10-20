<?php
/**
 * Elegant Elements Importer
 * Version 1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Don't duplicate me!
if ( ! class_exists( 'Elegant_Elements_Demo_Data_Importer' ) ) {
	global $demo_import_running;

	// Set the import running to false.
	$demo_import_running = false;

	require_once dirname( __FILE__ ) . '/elegant-importer.php';

	class Elegant_Elements_Demo_Data_Importer extends Elegant_Elements_Importer {

		/**
		 * The one, true instance of the class.
		 *
		 * @static
		 * @access public
		 * @var null|object
		 */
		public static $instance = null;

		/**
		 * Download content attachments.
		 *
		 * @access public
		 * @var bool
		 */
		public $download_content_attachments = true;

		/**
		 * Contents file name.
		 *
		 * @access public
		 * @var string
		 */
		public $contents_file_name = 'content.xml';

		/**
		 * Theme options file name.
		 *
		 * @access public
		 * @var string
		 */
		public $theme_options_file_name = 'theme_options.json';

		/**
		 * Theme options name.
		 *
		 * @access public
		 * @var string
		 */
		public $theme_option_name = 'fusion_options';

		/**
		 * Widgets file name.
		 *
		 * @access public
		 * @var string
		 */
		public $widgets_file_name = 'widgets.json';

		/**
		 * Rev. Slider file name.
		 *
		 * @access public
		 * @var string
		 */
		public $revsliders_file_name = 'revolution_slider.zip';

		/**
		 * Fusion Slider file name.
		 *
		 * @access public
		 * @var string
		 */
		public $fusion_slider_json = 'fusion_slider.json';

		/**
		 * LayerSlider file name.
		 *
		 * @access public
		 * @var string
		 */
		public $layersliders_file_name = 'layer_sliders.zip';


		/**
		 * Base demo files folder to download from.
		 *
		 * @access public
		 * @var string
		 */
		public $demo_zips_url_base = 'https://library.fusionelegantelements.com/';

		/**
		 * Widget import results.
		 *
		 * @access public
		 * @var array
		 */
		public $widget_import_results;

		/**
		 * Uploads directory base.
		 *
		 * @access public
		 * @var string
		 */
		public $wp_uploads_base;

		/**
		 * The class constructor
		 *
		 * @access private
		 */
		public function __construct() {
			self::$instance = $this;

			$wp_uploads_dir        = wp_get_upload_dir();
			$this->wp_uploads_base = $wp_uploads_dir['basedir'];

			add_filter( 'add_post_metadata', array( $this, 'check_previous_meta' ), 10, 5 );

			add_action( 'elegant_elements_after_content_import', array( $this, 'setup_default_pages' ) );

			add_action( 'wp_ajax_elegant_elements_demo_import', array( $this, 'demo_import_ajax' ) );
			add_action( 'wp_ajax_elegant_elements_demo_menu', array( $this, 'setup_menus_locations' ) );
		}

		/**
		 * Add Panel Page
		 */
		public function demo_import_ajax() {
			global $demo_import_running;

			if ( ! isset( $_REQUEST['module'] ) || empty( $_REQUEST['module'] ) ) { // @codingStandardsIgnoreLine
				echo 'Please choose the template elements to be imported';
				die( '' );
			}

			// Set demo import running to true.
			$demo_import_running = true;

			set_time_limit( 0 );
			@ini_set( 'memory_limit', '512M' ); // @codingStandardsIgnoreLine

			$template = trim( $_REQUEST['template'] ); // @codingStandardsIgnoreLine
			$module   = $_REQUEST['module']; // @codingStandardsIgnoreLine

			// First, download the ZIP (if it doesn't exist) and unzip the folder to the /demo-files directory.
			$this->download_and_unzip( $template );

			// Then do the import (previous code from here).
			$this->demo_files_path = $this->wp_uploads_base . '/demo-files/' . $template . '/';
			$this->init();

			switch ( $module ) {
				case 'revsliders':
					$res = $this->import_revsliders( $this->revsliders_file );
					$res = $this->import_revsliders( $this->revsliders_file2 );
					break;

				case 'fusion_slider':
					$res = $this->import_fusion_sliders( $this->fusion_slider_file );
					break;

				case 'options':
					$res = $this->import_theme_options( $this->theme_options_file, $this->theme_option_name );
					break;

				case 'widgets':
					$res = $this->import_widgets( $this->widgets_file );
					break;

				case '__contents':
					$res = $this->import_contents( $this->contents_file );
					break;

				case 'contents':
					$res = $this->import_contents_alternate( $this->contents_file, 20 );
					break;
			}

			if ( 'contents' === $module ) {
				if ( false === $res ) {
					$res = 'Contents Imported';
					if ( get_option( '_cri_processed_posts' ) ) {
						$res = count( get_option( '_cri_processed_posts' ) ) . ' media files imported, importing rest of the Contents';
					}

					self::ajax_response(
						array(
							'status' => 'ok',
							'html'   => $res,
							'next'   => 'contents',
						)
					);
				} else {
					self::ajax_ok( 'Contents Imported' );
				}
			} else {
				self::ajax_ok( $res );
			}
		}

		/**
		 * Load contents of a URL
		 * Dan Rowden Feb 2017
		 *
		 * @param string $url Demo url.
		 */
		private function url_get_contents( $url ) {
			if ( ! function_exists( 'curl_init' ) ) {
				die( 'CURL is not installed!' );
			}

			// @codingStandardsIgnoreStart
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, 'http://rockythemes.com/dev/demos.php' );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

			$output = curl_exec( $ch );

			curl_close( $ch );
			// @codingStandardsIgnoreEnd

			return $output;
		}

		/**
		 * Function to download a demo ZIP and unzip to local folder
		 * Dan Rowden Feb 2017.
		 *
		 * @param string $template Template to download.
		 */
		private function download_and_unzip( $template ) {
			$file_name    = $template . '.zip';
			$external_zip = $this->demo_zips_url_base . '/demo/' . str_replace( ' ', '%20', $template );
			$local_zip    = $this->wp_uploads_base . '/demo-files/' . $file_name;
			$extract_to   = $this->wp_uploads_base . '/demo-files/';

			if ( ! file_exists( $extract_to ) ) {
				wp_mkdir_p( $extract_to );
			}

			// Only download if folder doesn't exist
			// Use content.xml as a check.
			if ( ! file_exists( $extract_to . $template . '/content.xml' ) ) {

				// @codingStandardsIgnoreStart
				// Here is the file we are downloading, replace spaces with %20.
				global $wp_filesystem;

				if ( empty( $wp_filesystem ) ) {
					require_once wp_normalize_path( ABSPATH . '/wp-admin/includes/file.php' );
					WP_Filesystem();
				}

				// Get file content from the server.
				$data = $wp_filesystem->get_contents( $external_zip );

				if ( ! $wp_filesystem->put_contents( $local_zip, $data, FS_CHMOD_FILE ) ) {

					// If the attempt to write to the file failed, then fallback to fwrite.
					@unlink( $local_zip );
					$fp = @fopen( $local_zip, 'w' );

					$written = @fwrite( $fp, $data );
					@fclose( $fp );
					if ( false === $written ) {
						return false;
					}
				}

				// @codingStandardsIgnoreEnd

				if ( filesize( $local_zip ) > 0 ) {

					// Unzip.
					$zip = new ZipArchive();
					if ( $zip->open( $local_zip ) != 'true' ) {
						die( 'Unable to open the Zip File' );
					}

					// Extract Zip File.
					$zip->extractTo( $extract_to );
					$zip->close();
				} else {
					die( "Couldn't download the ZIP file." );
				}
			}
		}

		/**
		 * General notes about importer.
		 *
		 * @access public
		 */
		public function general_notes() {
			?>
			<div class="updated settings-error">
				<h3><?php esc_attr_e( 'Please read the notice below before proceeding further:', 'Creativo' ); ?></h3>
				<ul style="padding-left: 20px;list-style-position: inside;list-style-type: square;}">
					<li><?php esc_attr_e( '<strong>Important:</strong> If you import the same file twice, menu items will be duplicated.', 'Creativo' ); ?></li>
					<li><?php esc_attr_e( '<strong>Important:</strong> To import Revolution Slider, Layer Slider & WooCommerce contents, all of those three plugins must be active.', 'Creativo' ); ?></li>
					<li><?php esc_attr_e( 'No existing posts, pages, categories, images, custom post types or any other data will be deleted or modified .', 'Creativo' ); ?></li>
					<li><?php esc_attr_e( 'No WordPress settings will be modified .', 'Creativo' ); ?></li>
					<li><?php esc_attr_e( 'Posts, pages, some images, some widgets and menus will get imported .', 'Creativo' ); ?></li>
					<li><?php esc_attr_e( 'Some Images & Videos will be downloaded from our server, these images are copyrighted and are for demo use only .', 'Creativo' ); ?></li>
					<li><?php esc_attr_e( 'Please click import only once and wait, it can take a couple of minutes', 'Creativo' ); ?></li>
				</ul>
			</div>
			<?php
		}

		/**
		 * Avoids adding duplicate meta causing arrays in arrays from WP_importer
		 *
		 * @param string $continue   Continue.
		 * @param string $post_id    Post ID.
		 * @param string $meta_key   Meta Key.
		 * @param string $meta_value Meta Value.
		 * @param string $unique     Unique.
		 *
		 * @since 7.0
		 *
		 * @return bool
		 */
		public function check_previous_meta( $continue, $post_id, $meta_key, $meta_value, $unique ) {
			global $demo_import_running;

			if ( $demo_import_running ) {
				$old_value = get_metadata( 'post', $post_id, $meta_key );

				if ( count( $old_value ) === 1 ) {
					if ( $old_value[0] === $meta_value ) {
						return false;
					} elseif ( $old_value[0] !== $meta_value ) {
						update_post_meta( $post_id, $meta_key, $meta_value );
						return false;
					}
				}
			}
		}

		/**
		 * Setup default pages.
		 *
		 * @access public
		 */
		public function setup_default_pages() {
			$homepage = get_page_by_title( 'Home' );
			if ( empty( $homepage->ID ) ) {
				$homepage = get_page_by_title( 'HOME' );
				if ( empty( $homepage->ID ) ) {
					$homepage = get_page_by_title( 'Homepage' );
				}
			}

			if ( ! empty( $homepage->ID ) ) {
				update_option( 'page_on_front', $homepage->ID );
				update_option( 'show_on_front', 'page' );
			}

			$shop_page = get_page_by_title( 'Shop' );
			if ( ! empty( $shop_page->ID ) ) {
				update_option( 'woocommerce_shop_page_id', $shop_page->ID );
			}

			$hello = get_page_by_title( 'Hello world!', 'OBJECT', 'post' );
			if ( $hello ) {
				wp_delete_post( $hello->ID );
			}
		}

		/**
		 * Setup menu locations.
		 *
		 * @access public
		 */
		public function setup_menus_locations() {
			$locations = get_theme_mod( 'nav_menu_locations' );
			$menus     = wp_get_nav_menus();

			if ( $menus ) {
				foreach ( $menus as $menu ) {
					if ( 'Home menu' === $menu->name || 'Home Menu' === $menu->name || 'Home' === $menu->name || 'Main Navigation' === $menu->name || 'Main Menu' === $menu->name ) {
						$locations['main_navigation'] = $menu->term_id;
					}

					if ( 'Top Menu' === $menu->name ) {
						$locations['top_navigation'] = $menu->term_id;
					}
				}
			}
			set_theme_mod( 'nav_menu_locations', $locations );

			return $locations;
		}

		/**
		 * Ajax Error.
		 *
		 * @access public
		 * @param string $html HTML.
		 */
		public static function ajax_error( $html ) {
			self::ajax_response(
				array(
					'status' => 'error',
					'html'   => $html,
				)
			);
		}

		/**
		 * Ajax OK.
		 *
		 * @access public
		 * @param string $html HTML.
		 */
		public static function ajax_ok( $html ) {
			self::ajax_response(
				array(
					'status' => 'ok',
					'html'   => $html,
				)
			);
		}

		/**
		 * Ajax Response
		 *
		 * @access public
		 * @param array $a Ajax response.
		 */
		public static function ajax_response( $a ) {
			@error_reporting( 0 ); // @codingStandardsIgnoreLine

			header( 'Content-type: application/json' );
			echo json_encode( $a ); // @codingStandardsIgnoreLine

			die( '' );
		}

		/**
		 * Print Ajax Response.
		 *
		 * @access public
		 * @param array $a Array of ajax response.
		 */
		public static function p( $a ) {
			echo '<pre>';
			print_r( $a ); // @codingStandardsIgnoreLine
			echo '</pre>';
		}
	}

	new Elegant_Elements_Demo_Data_Importer();
}
