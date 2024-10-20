<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Elegant_Elements_Creator {
	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 3.0
	 * @return void
	 */
	public function __construct() {
		// Add creator menu under Elegant Elements.
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 1 );

		// Register post types.
		add_action( 'init', array( $this, 'register_post_types' ) );

		// Disable Avada page options for elements post type.
		add_filter( 'avada_hide_page_options', array( $this, 'disable_fusion_page_options' ) );

		// Load ACF files from the JSON file.
		add_filter( 'acf/settings/load_json', array( $this, 'add_acf_json_load_point' ) );

		// Set field types dynamically.
		add_filter( 'acf/load_field/name=element_settings_type', array( $this, 'acf_load_field_types' ) );

		// Add css to hide field labels.
		add_action( 'admin_head', array( $this, 'hide_field_labels' ) );

		// Register REST API for featured image urls.
		add_action( 'rest_api_init', array( $this, 'rest_register_images_field' ) );
	}

	/**
	 * Load ACF fields from JSON.
	 *
	 * @access public
	 * @since 3.0
	 * @return void
	 */
	public function rest_register_images_field() {
		register_rest_field(
			'element_creator',
			'featured_media',
			array(
				'get_callback'    => 'elegant_get_images_urls',
				'update_callback' => null,
				'schema'          => null,
			)
		);
	}

	/**
	 * Load ACF fields from JSON.
	 *
	 * @access public
	 * @since 3.0
	 * @return array
	 */
	public function add_acf_json_load_point() {

		// Append path.
		$paths[] = ELEGANT_ELEMENTS_PLUGIN_DIR . '/inc/creator/acf/json';

		// Return updated paths.
		return $paths;
	}

	/**
	 * Load field types dynamically.
	 *
	 * @access public
	 * @since 3.0
	 * @param array $field ACF field options.
	 * @return array
	 */
	public function acf_load_field_types( $field ) {

		$field['choices'] = array(
			'textfield'           => esc_attr__( 'Text Field', 'elegant-elements' ),
			'textarea'            => esc_attr__( 'Textarea', 'elegant-elements' ),
			'range'               => esc_attr__( 'Range', 'elegant-elements' ),
			'colorpicker'         => esc_attr__( 'Color Picker', 'elegant-elements' ),
			'colorpickeralpha'    => esc_attr__( 'Color Picker - RGBA', 'elegant-elements' ),
			'select'              => esc_attr__( 'Select Field', 'elegant-elements' ),
			'checkbox_button_set' => esc_attr__( 'Checkbox Button Set', 'elegant-elements' ),
			'radio_button_set'    => esc_attr__( 'Radio Button Set', 'elegant-elements' ),
			'upload'              => esc_attr__( 'Image Upload', 'elegant-elements' ),
			'uploadfile'          => esc_attr__( 'File Upload', 'elegant-elements' ),
			'iconpicker'          => esc_attr__( 'Icon Picker', 'elegant-elements' ),
			'multiple_select'     => esc_attr__( 'Multiple Select', 'elegant-elements' ),
			'link_selector'       => esc_attr__( 'Link Selector', 'elegant-elements' ),
			'date_time_picker'    => esc_attr__( 'Date Time Picker', 'elegant-elements' ),
			// Special fields. Need to add conditional logic for them in the mapping.
			'padding'             => esc_attr__( 'Padding', 'elegant-elements' ),
			'margin'              => esc_attr__( 'Margin', 'elegant-elements' ),
		);

		return $field;
	}

	/**
	 * Register post types for templates.
	 *
	 * @access public
	 * @since 3.0
	 * @return void
	 */
	public function register_post_types() {
		// Register post type - templates.
		$labels = array(
			'name'                  => _x( 'Element Creator', 'Post Type General Name', 'wcppb' ),
			'singular_name'         => _x( 'Element Creator', 'Post Type Singular Name', 'wcppb' ),
			'menu_name'             => __( 'Element Creator', 'wcppb' ),
			'name_admin_bar'        => __( 'Element Creator', 'wcppb' ),
			'archives'              => __( 'Element Archives', 'wcppb' ),
			'attributes'            => __( 'Element Attributes', 'wcppb' ),
			'parent_item_colon'     => __( 'Parent Element:', 'wcppb' ),
			'all_items'             => __( 'All Elements', 'wcppb' ),
			'add_new_item'          => __( 'Add New Element', 'wcppb' ),
			'add_new'               => __( 'Add New', 'wcppb' ),
			'new_item'              => __( 'New Element', 'wcppb' ),
			'edit_item'             => __( 'Edit Element', 'wcppb' ),
			'update_item'           => __( 'Update Element', 'wcppb' ),
			'view_item'             => __( 'View Element', 'wcppb' ),
			'view_items'            => __( 'View Elements', 'wcppb' ),
			'search_items'          => __( 'Search Element', 'wcppb' ),
			'not_found'             => __( 'Not found', 'wcppb' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'wcppb' ),
			'featured_image'        => __( 'Featured Image', 'wcppb' ),
			'set_featured_image'    => __( 'Set featured image', 'wcppb' ),
			'remove_featured_image' => __( 'Remove featured image', 'wcppb' ),
			'use_featured_image'    => __( 'Use as featured image', 'wcppb' ),
			'insert_into_item'      => __( 'Insert into template', 'wcppb' ),
			'uploaded_to_this_item' => __( 'Uploaded to this template', 'wcppb' ),
			'items_list'            => __( 'Elements list', 'wcppb' ),
			'items_list_navigation' => __( 'Elements list navigation', 'wcppb' ),
			'filter_items_list'     => __( 'Filter templates list', 'wcppb' ),
		);
		$args   = array(
			'label'               => __( 'Element Creator', 'wcppb' ),
			'description'         => __( 'Create Custom Fusion Builder Elements', 'wcppb' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'thumbnail', 'custom-fields' ),
			'hierarchical'        => true,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'menu_position'       => 5,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => false,
			'capability_type'     => 'page',
			'show_in_rest'        => true,
		);

		register_post_type( 'element_creator', $args );
	}

	/**
	 * Disable fusion page options for element creator post type.
	 *
	 * @since 3.0
	 * @access public
	 * @param array $post_types Default enabled post types.
	 * @return array Post types.
	 */
	public function disable_fusion_page_options( $post_types ) {
		global $typenow, $post;

		$post_types[] = 'element_creator';

		return $post_types;
	}

	/**
	 * Admin Menu.
	 *
	 * @access public
	 * @since 3.0
	 * @return void
	 */
	public function admin_menu() {
		global $submenu;

		$creator = add_submenu_page( 'elegant-elements-options', esc_attr__( 'Element Creator', 'elegant-elements' ), esc_attr__( 'Element Creator', 'elegant-elements' ), 'manage_options', 'elegant-elements-creator', array( $this, 'element_creator_tab' ) );

		add_action( 'admin_print_scripts-' . $creator, array( $this, 'admin_scripts' ) );
	}

	/**
	 * Admin scripts.
	 *
	 * @access public
	 * @since 3.0
	 * @return void
	 */
	public function admin_scripts() {
		if ( class_exists( 'Fusion_Font_Awesome' ) ) {
			wp_enqueue_style( 'fontawesome', Fusion_Font_Awesome::get_backend_css_url(), array(), ELEGANT_ELEMENTS_VERSION );
		}

		wp_enqueue_style( 'elegant_admin_css', ELEGANT_ELEMENTS_PLUGIN_URL . 'assets/admin/css/min/elegant-elements-admin.min.css', '', ELEGANT_ELEMENTS_VERSION );
		wp_enqueue_script( 'elegant-admin-js', ELEGANT_ELEMENTS_PLUGIN_URL . 'assets/admin/js/min/elegant-elements-admin.min.js', array( 'jquery' ), ELEGANT_ELEMENTS_VERSION, true );
	}

	/**
	 * Loads the creator admin page template.
	 *
	 * @access public
	 * @since 3.0
	 * @return void
	 */
	public function element_creator_tab() {
		require_once wp_normalize_path( dirname( __FILE__ ) . '/admin-screens/elements-creator.php' );
	}

	/**
	 * Adds the footer.
	 *
	 * @static
	 * @access public
	 * @since 3.0
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
	 * @since 3.0
	 * @return void
	 */
	public static function header() {
		?>
		<h1><?php esc_html_e( 'Welcome to Elegant Element Creator!', 'elegant-elements' ); ?></h1>
		<div class="about-text">
			<?php esc_attr_e( 'Elegant Element Creator is here to help you build any Fusion Builder element that you want to create for your custom design requirement. All you need is basic knowledge of HTML and CSS!', 'elegant-elements' ); ?>
		</div>
		<div class="elegant-elements-logo wp-badge">
			<span class="elegant-elements-version">
				<?php printf( esc_attr__( 'Version %s', 'elegant-elements' ), esc_attr( ELEGANT_ELEMENTS_VERSION ) ); ?>
			</span>
		</div>
		<h2 class="nav-tab-wrapper">
			<?php
				Elegant_Elements_Admin::admin_tab( esc_attr__( 'Welcome', 'elegant-elements' ), 'elegant-elements-options' );
				Elegant_Elements_Admin::admin_tab( esc_attr__( 'Demos', 'elegant-elements' ), 'elegant-elements-demos' );
				Elegant_Elements_Admin::admin_tab( esc_attr__( 'Support', 'elegant-elements' ), 'elegant-elements-support' );
				Elegant_Elements_Admin::admin_tab( esc_attr__( 'Settings', 'elegant-elements' ), 'elegant-elements-settings' );
				Elegant_Elements_Admin::admin_tab( esc_attr__( 'Element Creator', 'elegant-elements' ), 'elegant-elements-creator' );
			?>
		</h2>
		<?php
	}

	/**
	 * Adds the styling.
	 *
	 * @static
	 * @access public
	 * @since 3.0
	 * @return void
	 */
	public function hide_field_labels() {
		?>
		<style type="text/css">
		.hide-field-label > .acf-label {
			display: none;
		}
		</style>
		<?php
	}

	/**
	 * Adds the styling.
	 *
	 * @static
	 * @access public
	 * @since 3.0
	 * @return void
	 */
	public static function styling() {
		?>
		<style type="text/css">
		.elegant-elements-important-notice.elegant-elements-element-creator-form {
			display: flex;
			align-items: center;
		}

		.elegant-elements-important-notice.elegant-elements-element-creator-form .intro-text {
			flex: 1;
			padding-right: 30px;
		}
		.elegant-elements-important-notice.elegant-elements-element-creator-form .intro-text p {
			font-family: "Noto Sans", Roboto, "Open Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", "Oxygen-Sans", "Ubuntu", "Cantarell", "Helvetica Neue", sans-serif;
			font-style: normal;
			font-weight: normal;
			font-size: 16px;
			line-height: 32px;
			color: #4a5259;
			margin: 15px 0 0 0;
		}

		.elegant-elements-important-notice.elegant-elements-element-creator-form #element-creator-form {
			flex: 1;
			align-items: center;
			justify-content: center;
			display: flex;
			flex-direction: column;
		}
		#element-creator-form input[type="text"] {
			width: 100%;
			padding-left: 13px;
			height: 40px;
			max-width: 100%;
			background: #fff;
			border: 1px solid #e0e3e7;
			box-sizing: border-box;
			border-radius: 4px;
			margin: 6px 0 20px 0;
			color: #444;
		}
		#element-creator-form input[type="submit"],
		.elegant-large-button {
			width: 100%;
			background: #198fd9;
			border-radius: 3px;
			font-family: "Noto Sans", Roboto, "Open Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", "Oxygen-Sans", "Ubuntu", "Cantarell", "Helvetica Neue", sans-serif;
			font-style: normal;
			font-weight: bold;
			color: #fff;
			padding: 10px !important;
			border: none;
			-webkit-box-shadow: none;
			box-shadow: none;
			text-align: center;
		}
		.wp-core-ui .button.button-primary.element-creator-import-button {
			background: #3F51B5;
			border: none;
		}
		ul.element-creator-elements-list {
			display: flex;
			flex-wrap: wrap;
		}
		ul.element-creator-elements-list li {
			width: 32%;
			padding: 10px;
			box-sizing: border-box;
			border: 1px solid #fbfbfb;
			background: #fff;
			margin-bottom: 2%;
			margin-right: 2%;
		}
		ul.element-creator-elements-list li:nth-child(3n) {
			margin-right: 0;
		}
		.elegant-element-creator-item > i {
			width: 32px;
			padding: 10px;
			margin: -10px;
			margin-right: 0;
		}
		.elegant-element-creator-item {
			width: 80%;
			font-size: 18px;
			line-height: 1.5em;
			display: flex;
			align-items: center;
		}
		.elegant-element-creator-item-actions {
			width: 20%;
			display: flex;
		}
		.elegant-element-creator-item-wrapper {
			display: flex;
		}
		.elegant-element-creator-item-actions a {
			text-decoration: none;
			width: 50%;
			display: inline-flex;
			align-items: center;
			justify-content: center;
		}
		.elegant-element-creator-library {
			position: fixed;
			width: 100%;
			height: 100%;
			top: 0;
			left: 0;
			margin: 0 auto;
			align-items: center;
			justify-content: center;
			flex-direction: column;
			background: rgba(63, 81, 181, 0.8);
			z-index: 9999999;
			display: none;
		}
		.elegant-element-creator-library.import-active {
			display: flex;
		}
		.elegant-element-creator-library.import-active .elegant-element-creator-library-popup.theme-browser {
			width: 75%;
			height: 85%;
			background: #fff;
			margin: 0 auto;
			margin-top: 32px;
			overflow: hidden;
			border-radius: 5px;
			animation: EECreatorModalShow .1s ease-out;
			box-shadow: 0 0px 4px 0px rgb(255, 255, 255);
		}
		.element-creator-library-heading {
			padding: 10px 20px;
			border-bottom: 1px solid #ddd;
			margin-bottom: 10px;
			position: relative;
		}
		a.element-creator-library-close {
			width: 58px;
			height: 51px;
			text-decoration: none;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			background: #ffffff;
			color: #333;
			position: absolute;
			top: 0;
			right: 0;
			border-left: 1px solid #ddd;
			box-shadow: none;
			outline: none;
		}
		a.element-creator-library-close > span {
			font-size: 32px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
		}
		.element-creator-library-heading h3 {
			margin: 0;
		}
		.element-creator-library-content.themes {
			height: 85%;
			overflow: auto;
			padding: 15px 20px;
			display: flex;
			justify-content: start;
			flex-wrap: wrap;
		}
		.element-creator-library-item.theme {
			max-width: 23%;
			margin-right: 2.65% !important;
			margin-bottom: 2.65%;
			max-height: 250px;
		}
		.element-creator-library-item.theme:nth-child(4n) {
			margin-right: 0 !important;
		}
		.elegant-elements-registration-info {
			width: 75%;
			font-size: 24px;
			line-height: 1.5em;
			text-align: center;
			margin: 0 auto;
			display: block;
		}
		@keyframes EECreatorModalShow {
			0% {
				opacity: 0;
				transform: scale(1.2);
			}
			to {
				opacity: 1;
				transform: scale(1);
			}
		}
		</style>
		<?php
	}
}

new Elegant_Elements_Creator();
