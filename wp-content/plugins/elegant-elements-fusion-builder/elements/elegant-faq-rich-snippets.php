<?php
if ( fusion_is_element_enabled( 'iee_faq_rich_snippets' ) && ! class_exists( 'IEE_FAQ_Rich_Snippets' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 2.1.0
	 */
	class IEE_FAQ_Rich_Snippets extends Fusion_Element {

		/**
		 * Parent SC arguments.
		 *
		 * @access protected
		 * @since 2.1.0
		 * @var array
		 */
		protected $args;

		/**
		 * Parse FAQ rich snippets.
		 *
		 * @access protected
		 * @since 2.1.0
		 * @var array
		 */
		protected $seo_faq_data;

		/**
		 * Child SC arguments.
		 *
		 * @access protected
		 * @since 2.1.0
		 * @var array
		 */
		protected $child_args;

		/**
		 * List box counter.
		 *
		 * @since 2.1.0
		 * @access private
		 * @var object
		 */
		private $faqs_counter = 1;

		/**
		 * Constructor.
		 *
		 * @since 2.1.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-faq-rich-snippets', array( $this, 'attr' ) );

			add_shortcode( 'iee_faq_rich_snippets', array( $this, 'render_parent' ) );
			add_shortcode( 'iee_faq_rich_snippet_item', array( $this, 'render_child' ) );
		}

		/**
		 * Render the parent shortcode.
		 *
		 * @access public
		 * @since 2.1.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render_parent( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			$this->args = $args;

			$this->seo_faq_data = array(
				'@context'   => 'https://schema.org',
				'@type'      => 'FAQPage',
				'mainEntity' => array(),
			);

			$html = '';

			if ( '' !== locate_template( 'templates/faq-rich-snippets/elegant-faq-rich-snippets.php' ) ) {
				include locate_template( 'templates/faq-rich-snippets/elegant-faq-rich-snippets.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/faq-rich-snippets/elegant-faq-rich-snippets.php';
			}

			$this->faqs_counter++;

			return $html;
		}

		/**
		 * Render the child shortcode.
		 *
		 * @access public
		 * @since 2.1.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render_child( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			$this->child_args = $args;

			$child_html = '';

			if ( '' !== locate_template( 'templates/faq-rich-snippets/elegant-faq-rich-snippet-item.php' ) ) {
				include locate_template( 'templates/faq-rich-snippets/elegant-faq-rich-snippet-item.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/faq-rich-snippets/elegant-faq-rich-snippet-item.php';
			}

			return $child_html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 2.1.0
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-faq-rich-snippets',
			);

			$attr['class'] .= ' output-type-' . $this->args['output_type'];

			$attr = fusion_builder_visibility_atts( $this->args['hide_on_mobile'], $attr );

			if ( isset( $this->args['class'] ) ) {
				$attr['class'] .= ' ' . $this->args['class'];
			}

			if ( isset( $this->args['id'] ) ) {
				$attr['id'] = $this->args['id'];
			}

			return $attr;
		}
	}

	new IEE_FAQ_Rich_Snippets();
} // End if().

/**
 * Map shortcode for faq_rich_snippets.
 *
 * @since 2.1.0
 * @return void
 */
function map_elegant_elements_faq_rich_snippets() {
	global $fusion_settings;

	$parent_args = array(
		'name'          => esc_attr__( 'Elegant FAQ Rich Snippets', 'elegant-elements' ),
		'shortcode'     => 'iee_faq_rich_snippets',
		'icon'          => 'fas fa-question-circle faq-rich-snippets-icon',
		'multi'         => 'multi_element_parent',
		'element_child' => 'iee_faq_rich_snippet_item',
		'preview'       => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/previews/infi-faq-rich-snippets-preview.php',
		'preview_id'    => 'elegant-elements-module-infi-faq-rich-snippets-preview-template',
		'front-end'     => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-faq-rich-snippets-preview.php',
		'child_ui'      => true,
		'params'        => array(
			array(
				'type'        => 'tinymce',
				'heading'     => esc_attr__( 'Content', 'elegant-elements' ),
				'description' => esc_attr__( 'Enter some content for this contentbox.', 'elegant-elements' ),
				'param_name'  => 'element_content',
				'value'       => '[iee_faq_rich_snippet_item question="What is Fusion Builder"]' . esc_attr__( 'Fusion Builder is a Page Builder plugin by ThemeFusion.', 'elegant-elements' ) . '[/iee_faq_rich_snippet_item][iee_faq_rich_snippet_item question="What is Elegant Elements for Fusion Builder"]' . esc_attr__( 'Elegant Elements is an add-on for the Fusion Builder plugin.', 'elegant-elements' ) . '[/iee_faq_rich_snippet_item]',
			),
			array(
				'type'        => 'select',
				'heading'     => esc_attr__( 'Output Type', 'elegant-elements' ),
				'description' => esc_attr__( 'Select how you want the FAQs to be displayed on this page.', 'elegant-elements' ),
				'param_name'  => 'output_type',
				'default'     => 'descriptive',
				'value'       => array(
					'descriptive' => __( 'Descriptive ( Boxed )', 'elegant-elements' ),
					'accordions'  => __( 'Accordion (Toggle )', 'elegant-elements' ),
				),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'FAQs Section Title', 'elegant-elements' ),
				'param_name'  => 'title',
				'value'       => esc_attr__( 'Frequently Asked Questions', 'elegant-elements' ),
				'placeholder' => true,
				'description' => esc_attr__( 'Enter section title to be displayed above the FAQs. Keep empty to remove.', 'elegant-elements' ),
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
		'name'              => esc_attr__( 'FAQ Item', 'elegant-elements' ),
		'description'       => esc_attr__( 'Add single question and its answer', 'elegant-elements' ),
		'shortcode'         => 'iee_faq_rich_snippet_item',
		'hide_from_builder' => true,
		'allow_generator'   => true,
		'inline_editor'     => true,
		'params'            => array(
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Question', 'elegant-elements' ),
				'param_name'  => 'question',
				'value'       => esc_attr__( 'Your Question Goes Here', 'elegant-elements' ),
				'description' => esc_attr__( 'Enter the question for this FAQ item.', 'elegant-elements' ),
			),
			array(
				'type'        => 'tinymce',
				'heading'     => esc_attr__( 'Answer Text', 'elegant-elements' ),
				'description' => esc_attr__( 'Add the FAQ answer. Only text with links is acceptable. Avoid adding images and any other HTML.', 'elegant-elements' ),
				'param_name'  => 'element_content',
				'value'       => esc_attr__( 'Your Answer Goes Here.', 'elegant-elements' ),
				'placeholder' => true,
			),
		),
	);

	if ( function_exists( 'fusion_builder_frontend_data' ) ) {
		fusion_builder_map(
			fusion_builder_frontend_data(
				'IEE_FAQ_Rich_Snippets',
				$parent_args,
				'parent'
			)
		);

		fusion_builder_map(
			fusion_builder_frontend_data(
				'IEE_FAQ_Rich_Snippets',
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_faq_rich_snippets', 99 );
