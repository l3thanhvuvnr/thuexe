<?php
if ( fusion_is_element_enabled( 'iee_document_viewer' ) && ! class_exists( 'IEE_Document_Viewer' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 2.3
	 */
	class IEE_Document_Viewer extends Fusion_Element {

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

			add_filter( 'fusion_attr_elegant-document-viewer', array( $this, 'attr' ) );

			add_shortcode( 'iee_document_viewer', array( $this, 'render' ) );
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
					'file_type'       => 'pdf',
					'pdf_file_api'    => 'browser',
					'file_url'        => '',
					'document_height' => '400',
					'hide_on_mobile'  => '',
					'class'           => '',
					'id'              => '',
				),
				$args
			);

			$defaults = apply_filters( 'fusion_builder_default_args', $defaults, 'iee_document_viewer', $args );

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/document-viewer/elegant-document-viewer.php' ) ) {
				include locate_template( 'templates/document-viewer/elegant-document-viewer.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/document-viewer/elegant-document-viewer.php';
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
				'class' => 'elegant-document-viewer',
			);

			$attr['class'] .= ' document-type-' . $this->args['file_type'];

			$attr = fusion_builder_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$height        = FusionBuilder::validate_shortcode_attr_value( $this->args['document_height'], 'px' );
			$attr['style'] = 'height:' . $height . ';';

			if ( isset( $this->args['class'] ) ) {
				$attr['class'] .= ' ' . $this->args['class'];
			}

			if ( isset( $this->args['id'] ) ) {
				$attr['id'] = $this->args['id'];
			}

			return $attr;
		}
	}

	new IEE_Document_Viewer();
} // End if().

/**
 * Map shortcode for document_viewer.
 *
 * @since 2.3
 * @return void
 */
function map_elegant_elements_document_viewer() {
	global $fusion_settings;

	fusion_builder_map(
		array(
			'name'      => esc_attr__( 'Elegant Document Viewer', 'elegant-elements' ),
			'shortcode' => 'iee_document_viewer',
			'icon'      => 'fa-file-alt fas document-viewer-icon',
			'front-end' => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-document-viewer-preview.php',
			'params'    => array(
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'File Type', 'elegant-elements' ),
					'description' => esc_attr__( 'Select which file you want to add for document viewer.', 'elegant-elements' ),
					'param_name'  => 'file_type',
					'default'     => 'pdf',
					'value'       => array(
						'pdf'  => __( 'PDF', 'elegant-elements' ),
						'docx' => __( 'Word Document ( DOC and .DOCX )', 'elegant-elements' ),
						'xlsx' => __( 'Excel Spreadsheet ( .XLS and .XLSX )', 'elegant-elements' ),
						'ppt'  => __( 'PowerPoint Presentation ( .PPT and .PPTX )', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'PDF Viewer API Source', 'elegant-elements' ),
					'description' => esc_attr__( 'Select how you want to display the PDF document.', 'elegant-elements' ),
					'param_name'  => 'pdf_file_api',
					'default'     => 'browser',
					'value'       => array(
						'browser' => __( 'Browser Default', 'elegant-elements' ),
						'google'  => __( 'Google Docs Viewer API', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'uploadfile',
					'heading'     => esc_attr__( 'File URL', 'elegant-elements' ),
					'param_name'  => 'file_url',
					'value'       => '',
					'description' => esc_attr__( 'Upload your file and provide url to the downloadable file here. You can also use external downloadable file url.', 'elegant-elements' ),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Document Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css height to view the document. If the document overflows the height, the scroll will apprear inside the document. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'document_height',
					'value'       => '400',
					'min'         => '100',
					'max'         => '5000',
					'step'        => '1',
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_document_viewer', 99 );
