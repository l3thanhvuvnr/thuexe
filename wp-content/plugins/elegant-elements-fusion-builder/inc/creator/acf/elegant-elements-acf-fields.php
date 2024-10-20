<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// check if class already exists.
if ( ! class_exists( 'Elegant_Elements_ACF_Fields' ) ) :

	class Elegant_Elements_ACF_Fields {

		/**
		 * Settings.
		 *
		 * @since 3.0
		 * @access public
		 * @var array
		 */
		public $settings;

		/**
		 * The constructor
		 *
		 * This function will setup the class functionality
		 *
		 * @type function
		 * @since 3.0
		 * @return void
		 */
		public function __construct() {

			// Settings - these will be passed into the field class.
			$this->settings = array(
				'version' => '1.0.0',
				'url'     => plugin_dir_url( __FILE__ ),
				'path'    => plugin_dir_path( __FILE__ ),
			);

			// Include field.
			add_action( 'acf/include_field_types', array( $this, 'include_field' ) );
		}


		/**
		 * Include field.
		 *
		 * This function will include the field type class
		 *
		 * @type function
		 * @since 3.0
		 *
		 * @param int $version Major ACF version. Defaults to false.
		 * @return  void
		 */
		public function include_field( $version = false ) {

			// Support empty $version.
			if ( ! $version ) {
				$version = 5;
			}

			// Include.
			include_once 'fields/class-elegant-element-creator-template-tags.php';
		}

	}


	// Initialize.
	new Elegant_Elements_ACF_Fields();


	// Class_exists check.
endif;
