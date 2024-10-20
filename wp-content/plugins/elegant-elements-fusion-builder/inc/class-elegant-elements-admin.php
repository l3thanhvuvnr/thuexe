<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Elegant_Elements_Admin {
	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.1.0
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'check_if_avada_registered' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 1 );
		add_action( 'admin_head', array( $this, 'admin_head' ) );

		// Save Settings.
		add_action( 'admin_post_save_elegant_elements_settings', array( $this, 'settings_save' ) );

		// Whitelist settings page.
		add_filter( 'whitelist_options', array( $this, 'whitelist_options' ) );

		add_filter( 'avada_options_sections', array( $this, 'elegant_elements_options_section' ) );
	}

	/**
	 * Check if Avada is registered and user has purchased the plugin from the same account.
	 *
	 * @access public
	 * @since 3.3.2.1
	 * @return void
	 */
	public function check_if_avada_registered() {
		if ( defined( 'AVADA_VERSION' ) && version_compare( AVADA_VERSION, '7.3', '>=' ) ) {
			return;
		}

		$avada_registration            = get_option( 'fusion_registration_data' );
		$elegant_elements_registration = get_option( 'elegant_element_registration' );

		// Retrieve purchase data.
		$purchase_data = get_option( 'elegant_elements_purchase_data', array() );

		$token = '';
		if ( ( ! isset( $elegant_elements_registration['elegantelementsforfusionbuilder'] ) && ! isset( $purchase_data['purchase_verified'] ) ) && isset( $avada_registration['avada'] ) && isset( $avada_registration['avada']['token'] ) ) {
			$registration_data = $avada_registration['avada'];
			$token             = $registration_data['token'];

			if ( '' === $token ) {
				return;
			}

			$args     = array(
				'headers' => array(
					'Authorization' => 'Bearer ' . $token,
					'User-Agent'    => 'WordPress - Elegant Elements',
				),
				'timeout' => 20,
			);
			$api_url  = 'https://api.envato.com/v3/market/buyer/list-purchases?filter_by=wordpress-plugins&include_all_item_details=false';
			$response = wp_remote_get( esc_url_raw( $api_url ), $args );
			$response = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( isset( $response['results'] ) ) {
				foreach ( $response['results'] as $plugin ) {
					if ( 'Elegant Elements for Fusion Builder and Avada' === $plugin['item']['name'] ) {
						$registration_data['is_valid'] = true;
						$elegant_elements_registration = array(
							'elegantelementsforfusionbuilder' => $registration_data,
						);

						update_option( 'elegant_element_registration', $elegant_elements_registration );

						// Update the 'elegant_elements_registered' option.
						update_option(
							'elegant_elements_registered',
							array(
								'elegantelementsforfusionbuilder' => true,
							)
						);
						break;
					}
				}
			}
		}
	}

	/**
	 * Admin Head.
	 *
	 * @access public
	 * @since 1.1.0
	 * @return void
	 */
	public function admin_head() {
		$menu_image = ELEGANT_ELEMENTS_PLUGIN_URL . 'assets/admin/img/icon-white.png';
		$admin_icon = ELEGANT_ELEMENTS_PLUGIN_URL . 'assets/admin/img/icon.png';
		echo '<style type="text/css">.dashicons-elegant-elements:before {
			content: "";
			background: url( ' . esc_attr( $menu_image ) . ' ) no-repeat center center;
			background-size: contain;
		}
		.elegant-elements-logo {
			background-image: url( ' . esc_attr( $admin_icon ) . ' ) !important;
			background-color: #fff;
		}
		.elegant-elements-version {
			background: #000000;
			box-shadow: 0 1px 3px rgba(0, 0, 0, .2);
			color: #ffffff;
			display: block;
			margin-top: 5px;
			padding: 10px 0;
			text-align: center;
		}
		#elegant_default_title_typography .select_wrapper.typography-family-backup,
		#elegant_default_title_typography .select_wrapper.typography-script.tooltip,
		#elegant_default_description_typography .select_wrapper.typography-family-backup,
		#elegant_default_description_typography .select_wrapper.typography-script.tooltip {
			display: none !important;
		}
		</style>';
	}

	/**
	 * Admin Menu.
	 *
	 * @access public
	 * @since 1.1.0
	 * @return void
	 */
	public function admin_menu() {
		global $submenu;

		$welcome  = add_menu_page( esc_attr__( 'Elegant Elements', 'elegant-elements' ), esc_attr__( 'Elegant Elements', 'elegant-elements' ), 'manage_options', 'elegant-elements-options', array( $this, 'welcome' ), 'dashicons-elegant-elements', '4.222222' );
		$demos    = add_submenu_page( 'elegant-elements-options', esc_attr__( 'Demos', 'elegant-elements' ), esc_attr__( 'Demos', 'elegant-elements' ), 'manage_options', 'elegant-elements-demos', array( $this, 'demos_tab' ) );
		$patcher  = add_submenu_page( 'elegant-elements-options', esc_attr__( 'Patcher', 'elegant-elements' ), esc_attr__( 'Patcher', 'elegant-elements' ), 'manage_options', 'elegant-elements-patcher', array( $this, 'patcher_tab' ) );
		$support  = add_submenu_page( 'elegant-elements-options', esc_attr__( 'Support', 'elegant-elements' ), esc_attr__( 'Support', 'elegant-elements' ), 'manage_options', 'elegant-elements-support', array( $this, 'support_tab' ) );
		$settings = add_submenu_page( 'elegant-elements-options', esc_attr__( 'Settings', 'elegant-elements' ), esc_attr__( 'Settings', 'elegant-elements' ), 'manage_options', 'elegant-elements-settings', array( $this, 'settings_tab' ) );

		if ( current_user_can( 'edit_theme_options' ) ) {
			$submenu['elegant-elements-options'][0][0] = esc_attr__( 'Welcome', 'elegant-elements' ); // phpcs:ignore
		}

		add_action( 'admin_print_scripts-' . $welcome, array( $this, 'admin_scripts' ) );
		add_action( 'admin_print_scripts-' . $demos, array( $this, 'admin_scripts' ) );
		add_action( 'admin_print_scripts-' . $demos, array( $this, 'demo_import_scripts' ) );
		add_action( 'admin_print_scripts-' . $support, array( $this, 'admin_scripts' ) );
		add_action( 'admin_print_scripts-' . $patcher, array( $this, 'admin_scripts' ) );
		add_action( 'admin_print_scripts-' . $settings, array( $this, 'admin_scripts' ) );
	}

	/**
	 * Handles the saving of settings in admin area.
	 *
	 * @access public
	 * @since 1.1.0
	 * @return void
	 */
	public function settings_save() {
		check_admin_referer( 'elegant_elements_save_settings', 'elegant_elements_save_settings' );

		// @codingStandardsIgnoreLine WordPress.VIP.ValidatedSanitizedInput.InputNotSanitized
		$settings = ( ! empty( $_POST ) ) ? $_POST : array();

		// Update settings.
		update_option( 'elegant_elements_settings', $settings );

		// Redirect back to the settings page.
		wp_safe_redirect( admin_url( 'admin.php?page=elegant-elements-settings' ) );
		exit;
	}

	/**
	 * Whitelist options.
	 *
	 * @access public
	 * @since 3.3.2.1
	 * @param array $options The whitelisted options.
	 * @return array
	 */
	public function whitelist_options( $options ) {

		$added = array();

		// Whitelist settings page.
		$added['elegant_element_registration'] = array(
			'elegant-elements-options',
			'elegant_element_registration',
		);

		$options = add_option_whitelist( $added, $options );

		return $options;
	}

	/**
	 * Admin scripts.
	 *
	 * @access public
	 * @since 1.1.0
	 * @return void
	 */
	public function admin_scripts() {
		wp_enqueue_media();
		wp_enqueue_style( 'elegant_admin_css', ELEGANT_ELEMENTS_PLUGIN_URL . 'assets/admin/css/min/elegant-elements-admin.min.css', '', ELEGANT_ELEMENTS_VERSION );
		wp_enqueue_script( 'elegant-admin-js', ELEGANT_ELEMENTS_PLUGIN_URL . 'assets/admin/js/min/elegant-elements-admin.min.js', array( 'jquery' ), ELEGANT_ELEMENTS_VERSION, true );
	}

	/**
	 * Admin scripts for demo import.
	 *
	 * @access public
	 * @since 2.0
	 * @return void
	 */
	public function demo_import_scripts() {

		$translation_array = array(
			'preconfirmation' => __( 'Are you sure to import dummy content? We highly encourage you to do this action in a WordPress fresh installation!', 'elegant-elements' ),
			'importing'       => __( 'Please be patient while we are importing the demo. This process may take a couple of minutes.', 'elegant-elements' ),
			'importedAlert'   => __( 'Please note that some of the images & videos that you will see in page sections, sliders might be hotlinked to our server (not imported), as you are allowed to use these images on your development phase (for better visual guide) of your site and MUST be replaced with your own images/videos before its ready for production.', 'elegant-elements' ),
			'importFailded'   => __( 'We could not complete the import. Please try again.', 'elegant-elements' ),

		);

		wp_localize_script( 'elegant-admin-js', 'demoImporterText', $translation_array );
	}

	/**
	 * Loads the welcome page template.
	 *
	 * @access public
	 * @since 1.1.0
	 * @return void
	 */
	public function welcome() {
		require_once wp_normalize_path( dirname( __FILE__ ) . '/admin-screens/welcome.php' );
	}

	/**
	 * Loads the demos page template.
	 *
	 * @access public
	 * @since 2.0
	 * @return void
	 */
	public function demos_tab() {
		require_once wp_normalize_path( dirname( __FILE__ ) . '/admin-screens/demos.php' );
	}

	/**
	 * Loads the support page template.
	 *
	 * @access public
	 * @since 1.1.0
	 * @return void
	 */
	public function support_tab() {
		require_once wp_normalize_path( dirname( __FILE__ ) . '/admin-screens/support.php' );
	}

	/**
	 * Loads the patcher page template.
	 *
	 * @access public
	 * @since 3.4.0
	 * @return void
	 */
	public function patcher_tab() {
		require_once wp_normalize_path( dirname( __FILE__ ) . '/admin-screens/patches.php' );
	}

	/**
	 * Loads the settings page template.
	 *
	 * @access public
	 * @since 1.1.0
	 * @return void
	 */
	public function settings_tab() {
		require_once wp_normalize_path( dirname( __FILE__ ) . '/admin-screens/settings.php' );
	}

	/**
	 * Set the admin page tabs.
	 *
	 * @static
	 * @access protected
	 * @since 1.1.0
	 * @param string $title The title.
	 * @param string $page  The page slug.
	 */
	public static function admin_tab( $title, $page ) {

		if ( isset( $_GET['page'] ) ) {
			$active_page = $_GET['page'];
		}

		if ( $active_page == $page ) {
			$link       = 'javascript:void(0);';
			$active_tab = ' nav-tab-active';
		} else {
			$link       = 'admin.php?page=' . $page;
			$active_tab = '';
		}

		echo '<a href="' . $link . '" class="nav-tab' . $active_tab . '">' . $title . '</a>'; // phpcs:ignore.

	}

	/**
	 * Adds the footer.
	 *
	 * @static
	 * @access public
	 * @since 1.1.0
	 * @return void
	 */
	public static function footer() {
		?>
		<div class="elegant-elements-thanks">
			<p class="description"><?php esc_html_e( 'Thank you for choosing Elegant Elements. We are honored and are fully dedicated to making your experience perfect.', 'elegant-elements' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Adds the header.
	 *
	 * @static
	 * @access public
	 * @since 1.1.0
	 * @return void
	 */
	public static function header() {
		?>
		<h1><?php esc_html_e( 'Welcome to Elegant Elements!', 'elegant-elements' ); ?></h1>
		<div class="updated registration-notice-1" style="display: none;">
			<p><strong><?php esc_attr_e( 'Thanks for registering your purchase. You will now receive the automatic updates.', 'elegant-elements' ); ?></strong></p>
		</div>
		<div class="updated error registration-notice-2" style="display: none;">
			<p><strong><?php esc_attr_e( 'Please provide all the three details for registering your copy of Elegant Elements.', 'elegant-elements' ); ?>.</strong></p>
		</div>
		<div class="updated error registration-notice-3" style="display: none;">
			<p><strong><?php esc_attr_e( 'Something went wrong. Please verify your details and try again.', 'elegant-elements' ); ?></strong></p>
		</div>
			<div class="about-text">
					<?php esc_attr_e( 'Elegant Elements is now installed and ready to use! Get ready to build something beautiful. Please register your purchase on welcome tab to receive automatic updates and support. We hope you enjoy it!', 'elegant-elements' ); ?>
			</div>
		<div class="elegant-elements-logo wp-badge">
			<span class="elegant-elements-version">
				<?php printf( esc_attr__( 'Version %s', 'elegant-elements' ), esc_attr( ELEGANT_ELEMENTS_VERSION ) ); ?>
			</span>
		</div>
		<h2 class="nav-tab-wrapper">
			<?php
				self::admin_tab( esc_attr__( 'Welcome', 'elegant-elements' ), 'elegant-elements-options' );
				self::admin_tab( esc_attr__( 'Demos', 'elegant-elements' ), 'elegant-elements-demos' );
				self::admin_tab( esc_attr__( 'Patcher', 'elegant-elements' ), 'elegant-elements-patcher' );
				self::admin_tab( esc_attr__( 'Support', 'elegant-elements' ), 'elegant-elements-support' );
				self::admin_tab( esc_attr__( 'Settings', 'elegant-elements' ), 'elegant-elements-settings' );
				self::admin_tab( esc_attr__( 'Element Creator', 'elegant-elements' ), 'elegant-elements-creator' );
			?>
		</h2>
		<?php
	}

	/**
	 * Add theme options for elegant elements.
	 *
	 * @access public
	 * @since 1.2.0
	 * @param array $sections Theme option sections.
	 * @return array $sections
	 */
	public function elegant_elements_options_section( $sections ) {

		if ( ! is_admin() ) {
			return $sections;
		}

		$fields = array();

		// Add element options.
		foreach ( glob( ELEGANT_ELEMENTS_PLUGIN_DIR . '/inc/options/*.php', GLOB_NOSORT ) as $filename ) {
			include $filename;
		}

		$sections['elegant_elements'] = array(
			'label'    => esc_html__( 'Elegant Elements', 'elegant-elements' ),
			'id'       => 'elegant_elements_section',
			'priority' => 3,
			'icon'     => 'dashicons-before dashicons-elegant-elements',
			'fields'   => array_merge(
				array(
					'elegant_elements_important_note_info' => array(
						'label'       => '',
						'description' => '<div class="fusion-redux-important-notice">' . __( '<strong>IMPORTANT NOTE:</strong> This panel holds element options for Elegan Elements for Fusion Builder Add-on by InfiWebs. These are not made by ThemeFusion. If you require support for these elements, please contact InfiWebs Support.', 'fusion-builder' ) . '</div>',
						'id'          => 'elegant_elements_important_note_info',
						'type'        => 'custom',
					),
					'elegant_default_title_typography'     => array(
						'label'       => esc_html__( 'Default Typography for Title', 'elegant-elements' ),
						'description' => esc_html__( 'Set default typography for the title option in elements.', 'elegant-elements' ),
						'id'          => 'elegant_default_title_typography',
						'type'        => 'typography',
						'choices'     => array(
							'font-family'    => true,
							'font-size'      => false,
							'font-weight'    => true,
							'line-height'    => false,
							'letter-spacing' => false,
							'color'          => false,
						),
						'default'     => array(
							'font-family' => 'Open Sans',
							'font-weight' => '400',
						),
					),
					'elegant_default_description_typography' => array(
						'label'       => esc_html__( 'Default Typography for Description or Sub-title', 'elegant-elements' ),
						'description' => esc_html__( 'Set default typography for the description or sub-title option in elements.', 'elegant-elements' ),
						'id'          => 'elegant_default_description_typography',
						'type'        => 'typography',
						'choices'     => array(
							'font-family'    => true,
							'font-size'      => false,
							'font-weight'    => true,
							'line-height'    => false,
							'letter-spacing' => false,
							'color'          => false,
						),
						'default'     => array(
							'font-family' => 'Open Sans',
							'font-weight' => '300',
						),
					),

					'element_options_info'                 => array(
						'label'       => esc_html__( 'Element Options ', 'elegant-elements' ),
						'description' => '',
						'id'          => 'element_options_info',
						'default'     => esc_html__( 'Set global options for elegant elements.', 'elegant-elements' ),
						'type'        => 'info',
					),
				),
				$fields
			),
		);

		return $sections;
	}
}

new Elegant_Elements_Admin();
