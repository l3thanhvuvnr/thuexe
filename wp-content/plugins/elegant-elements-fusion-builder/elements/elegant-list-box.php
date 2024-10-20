<?php
if ( fusion_is_element_enabled( 'iee_list_box' ) && ! class_exists( 'IEE_List_Box' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.2.0
	 */
	class IEE_List_Box extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.2.0
		 * @var array
		 */
		protected $args;

		/**
		 * List box counter.
		 *
		 * @since 1.2.0
		 * @access private
		 * @var object
		 */
		private $list_box_counter = 1;

		/**
		 * The CSS class of circle elements.
		 *
		 * @access private
		 * @since 1.2.0
		 * @var string
		 */
		private $circle_class = 'circle-no';

		/**
		 * Parent SC arguments.
		 *
		 * @access protected
		 * @since 1.2.0
		 * @var array
		 */
		protected $parent_args;

		/**
		 * Child SC arguments.
		 *
		 * @access protected
		 * @since 1.2.0
		 * @var array
		 */
		protected $child_args;

		/**
		 * Constructor.
		 *
		 * @since 1.2.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_list-box-shortcode', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_list-box-shortcode-title', array( $this, 'title_attr' ) );
			add_filter( 'fusion_attr_list-box-shortcode-title-span', array( $this, 'title_span_attr' ) );
			add_filter( 'fusion_attr_list-box-shortcode-items', array( $this, 'items_attr' ) );
			add_filter( 'fusion_attr_list-box-shortcode-li-item', array( $this, 'li_attr' ) );
			add_filter( 'fusion_attr_list-box-shortcode-span', array( $this, 'span_attr' ) );
			add_filter( 'fusion_attr_list-box-shortcode-icon', array( $this, 'icon_attr' ) );
			add_filter( 'fusion_attr_list-box-shortcode-item-content', array( $this, 'item_content_attr' ) );

			add_shortcode( 'iee_list_box', array( $this, 'render_parent' ) );
			add_shortcode( 'iee_list_box_item', array( $this, 'render_child' ) );
		}

		/**
		 * Set defaults values.
		 *
		 * @static
		 * @access public
		 * @since 2.0
		 * @return string          HTML output.
		 */
		public static function get_element_defaults() {

			return array(
				'hide_on_mobile'  => fusion_builder_default_visibility( 'string' ),
				'class'           => '',
				'id'              => '',
				'circle'          => 'yes',
				'circlecolor'     => '#a0ce4e',
				'icon'            => 'fa-check',
				'iconcolor'       => '#ffffff',
				'size'            => '13px',
				'title_font_size' => 13,
				'title_align'     => 'center',
				'title_color'     => '#333333',
				'border_color'    => '#dddddd',
				'border_style'    => 'solid',
				'border_size'     => '1',
				'border_radius'   => 'square',
				'item_align'      => 'center',
			);
		}

		/**
		 * Render the parent shortcode.
		 *
		 * @access public
		 * @since 1.2.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render_parent( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			$html = '';

			if ( '' !== locate_template( 'templates/list-box/elegant-list-box.php' ) ) {
				include locate_template( 'templates/list-box/elegant-list-box.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/list-box/elegant-list-box.php';
			}

			$this->list_box_counter++;

			return $html;
		}

		/**
		 * Render the child shortcode.
		 *
		 * @access public
		 * @since 1.2.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render_child( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			$html = '';

			if ( '' !== locate_template( 'templates/list-box/elegant-list-box-item.php' ) ) {
				include locate_template( 'templates/list-box/elegant-list-box-item.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/list-box/elegant-list-box-item.php';
			}

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.2.0
		 * @return array
		 */
		public function attr() {

			$attr = array();

			$attr['class']  = 'elegant-list-box fusion-checklist elegant-list-box-' . $this->list_box_counter;
			$attr['class'] .= ' elegant-align-' . $this->parent_args['item_align'];

			$attr          = fusion_builder_visibility_atts( $this->parent_args['hide_on_mobile'], $attr );
			$font_size     = str_replace( 'px', '', $this->parent_args['size'] );
			$line_height   = $font_size * 1.7;
			$attr['style'] = 'font-size:' . $this->parent_args['size'] . ';line-height:' . $line_height . 'px;';

			if ( $this->parent_args['class'] ) {
				$attr['class'] .= ' ' . $this->parent_args['class'];
			}

			if ( $this->parent_args['id'] ) {
				$attr['id'] = $this->parent_args['id'];
			}

			return $attr;

		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.2.0
		 * @return array
		 */
		public function title_attr() {
			$attr = array();

			$attr['class']  = 'elegant-list-box-title';
			$attr['class'] .= ' elegant-align-' . $this->parent_args['title_align'];

			$attr['style'] = 'color:' . $this->parent_args['title_color'] . ';';

			$font_size      = FusionBuilder::validate_shortcode_attr_value( $this->parent_args['title_font_size'], 'px' );
			$line_height    = $this->parent_args['title_font_size'] * 1.2;
			$margin_bottom  = $this->parent_args['title_font_size'] * 1.7;
			$attr['style'] .= 'font-size:' . $font_size . ';line-height:' . $line_height . 'px;margin-bottom:-' . $margin_bottom . 'px;';

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.2.0
		 * @return array
		 */
		public function items_attr() {
			$attr = array();

			$attr['class'] = 'elegant-list-box-items';

			$attr['style']  = 'border-color:' . $this->parent_args['border_color'] . ';';
			$attr['style'] .= 'border-style:' . $this->parent_args['border_style'] . ';';
			$attr['style'] .= 'border-width:' . FusionBuilder::validate_shortcode_attr_value( $this->parent_args['border_size'], 'px' ) . ';';

			$font_size      = str_replace( 'px', '', $this->parent_args['size'] );
			$line_height    = $font_size * 2;
			$attr['style'] .= 'padding-top:' . $line_height . 'px;';

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.2.0
		 * @return array
		 */
		public function title_span_attr() {
			$attr = array(
				'class' => 'elegant-list-box-border-' . $this->parent_args['border_radius'],
			);

			$attr['style']  = 'border-color:' . $this->parent_args['border_color'] . ';';
			$attr['style'] .= 'border-style:' . $this->parent_args['border_style'] . ';';
			$attr['style'] .= 'border-width:' . FusionBuilder::validate_shortcode_attr_value( $this->parent_args['border_size'], 'px' ) . ';';

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.2.0
		 * @return array
		 */
		public function li_attr() {

			$attr = array();

			$attr['class'] = 'elegant-list-item fusion-li-item';

			return $attr;

		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.2.0
		 * @return array
		 */
		public function item_content_attr() {
			return array(
				'class' => 'elegant-list-item-content fusion-li-item-content',
				'style' => 'margin-' . $this->parent_args['content_margin_position'] . ':' . $this->parent_args['content_margin'] . 'px;',
			);
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.2.0
		 * @return array
		 */
		public function span_attr() {

			$attr = array(
				'style' => '',
			);

			if ( 'yes' === $this->child_args['circle'] || 'yes' === $this->parent_args['circle'] && ( 'no' !== $this->child_args['circle'] ) ) {
				$this->circle_class = 'circle-yes';

				if ( ! $this->child_args['circlecolor'] ) {
					$circlecolor = $this->parent_args['circlecolor'];
				} else {
					$circlecolor = $this->child_args['circlecolor'];
				}
				$attr['style'] = 'background-color:' . $circlecolor . ';';

				$attr['style'] .= 'font-size:' . $this->parent_args['circle_yes_font_size'] . 'px;';
			}

			$attr['class'] = 'icon-wrapper ' . $this->circle_class;

			$attr['style'] .= 'height:' . $this->parent_args['line_height'] . 'px;';
			$attr['style'] .= 'width:' . $this->parent_args['line_height'] . 'px;';
			$attr['style'] .= 'margin-' . $this->parent_args['icon_margin_position'] . ':' . $this->parent_args['icon_margin'] . 'px;';

			return $attr;

		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.2.0
		 * @return array
		 */
		public function icon_attr() {

			if ( ! $this->child_args['icon'] ) {
				$icon = $this->parent_args['icon'];
			} else {
				$icon = $this->child_args['icon'];
			}

			$icon_class = ( function_exists( 'fusion_font_awesome_name_handler' ) ) ? fusion_font_awesome_name_handler( $icon ) : FusionBuilder::font_awesome_name_handler( $icon );

			if ( ! $this->child_args['iconcolor'] ) {
				$iconcolor = $this->parent_args['iconcolor'];
			} else {
				$iconcolor = $this->child_args['iconcolor'];
			}

			return array(
				'class' => 'elegant-list-item-icon fusion-li-icon ' . $icon_class,
				'style' => 'color:' . $iconcolor . ';',
			);
		}
	}

	new IEE_List_Box();
} // End if().

/**
 * Map shortcode for list_box.
 *
 * @since 1.2.0
 * @return void
 */
function map_elegant_elements_list_box() {
	global $fusion_settings;

	$parent_args = array(
		'name'          => esc_attr__( 'Elegant List Box', 'elegant-elements' ),
		'shortcode'     => 'iee_list_box',
		'icon'          => 'fusiona-list-ul',
		'multi'         => 'multi_element_parent',
		'element_child' => 'iee_list_box_item',
		'preview'       => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/previews/infi-list-box-preview.php',
		'preview_id'    => 'elegant-elements-module-infi-list-box-preview-template',
		'child_ui'      => true,
		'params'        => array(
			array(
				'type'        => 'tinymce',
				'heading'     => esc_attr__( 'Content', 'elegant-elements' ),
				'description' => esc_attr__( 'Enter some content for this contentbox.', 'elegant-elements' ),
				'param_name'  => 'element_content',
				'value'       => '[iee_list_box_item icon=""]' . esc_attr__( 'Your Content Goes Here', 'elegant-elements' ) . '[/iee_list_box_item]',
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'List Box Title', 'elegant-elements' ),
				'description' => esc_attr__( 'Enter title for this list box.', 'elegant-elements' ),
				'param_name'  => 'title',
				'value'       => esc_attr__( 'List Box Title', 'elegant-elements' ),
				'placeholder' => true,
				'group'       => esc_attr__( 'General', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Title Alignment', 'elegant-elements' ),
				'description' => esc_attr__( 'Select list title alighment.', 'elegant-elements' ),
				'param_name'  => 'title_align',
				'default'     => 'center',
				'value'       => array(
					'left'   => esc_attr__( 'Left', 'elegant-elements' ),
					'center' => esc_attr__( 'Center', 'elegant-elements' ),
					'right'  => esc_attr__( 'Right', 'elegant-elements' ),
				),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Title Color', 'elegant-elements' ),
				'description' => esc_attr__( 'Controls the list box title text color.', 'elegant-elements' ),
				'param_name'  => 'title_color',
				'value'       => '',
				'default'     => '#333333',
				'group'       => esc_attr__( 'Design', 'elegant-elements' ),
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Border Size', 'elegant-elements' ),
				'description' => esc_attr__( 'Controls the border size of the column. In pixels.', 'elegant-elements' ),
				'param_name'  => 'border_size',
				'value'       => '1',
				'min'         => '1',
				'max'         => '50',
				'step'        => '1',
				'group'       => esc_attr__( 'Design', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Border Color', 'elegant-elements' ),
				'description' => esc_attr__( 'Controls the border color.', 'elegant-elements' ),
				'param_name'  => 'border_color',
				'value'       => '#dddddd',
				'group'       => esc_attr__( 'Design', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Border Style', 'elegant-elements' ),
				'description' => esc_attr__( 'Controls the border style.', 'elegant-elements' ),
				'param_name'  => 'border_style',
				'default'     => 'solid',
				'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				'value'       => array(
					'solid'  => esc_attr__( 'Solid', 'elegant-elements' ),
					'dashed' => esc_attr__( 'Dashed', 'elegant-elements' ),
					'dotted' => esc_attr__( 'Dotted', 'elegant-elements' ),
				),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Title Border Type', 'elegant-elements' ),
				'description' => esc_attr__( 'Select how do you want the title border display.', 'elegant-elements' ),
				'param_name'  => 'border_radius',
				'default'     => 'square',
				'value'       => array(
					'square' => esc_attr__( 'Square', 'elegant-elements' ),
					'round'  => esc_attr__( 'Round', 'elegant-elements' ),
					'pill'   => esc_attr__( 'Pill', 'elegant-elements' ),
				),
				'group'       => esc_attr__( 'Design', 'elegant-elements' ),
			),
			array(
				'type'        => 'iconpicker',
				'heading'     => esc_attr__( 'Select Icon', 'elegant-elements' ),
				'param_name'  => 'icon',
				'value'       => '',
				'description' => esc_attr__( 'Global setting for all list items, this can be overridden individually. Click an icon to select, click again to deselect.', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Checklist Icon Color', 'elegant-elements' ),
				'description' => esc_attr__( 'Global setting for all list items.  Controls the color of the checklist icon.', 'elegant-elements' ),
				'param_name'  => 'iconcolor',
				'value'       => '',
				'default'     => '#ffffff',
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Checklist Circle', 'elegant-elements' ),
				'description' => esc_attr__( 'Global setting for all list items. Turn on if you want to display a circle background for checklists.', 'elegant-elements' ),
				'param_name'  => 'circle',
				'default'     => 'yes',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
			),
			array(
				'type'        => 'colorpickeralpha',
				'heading'     => esc_attr__( 'Checklist Circle Color', 'elegant-elements' ),
				'description' => esc_attr__( 'Global setting for all list items.  Controls the color of the checklist circle background.', 'elegant-elements' ),
				'param_name'  => 'circlecolor',
				'value'       => '',
				'default'     => '#a0ce4e',
				'dependency'  => array(
					array(
						'element'  => 'circle',
						'value'    => 'no',
						'operator' => '!=',
					),
				),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Item Font Size', 'elegant-elements' ),
				'description' => esc_attr__( "Select the list item's font size. In pixels (px), ex: 13px.", 'elegant-elements' ),
				'param_name'  => 'size',
				'value'       => '13px',
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Title Font Size', 'elegant-elements' ),
				'description' => esc_attr__( 'Controls the list item title font size. In pixels.', 'elegant-elements' ),
				'param_name'  => 'title_font_size',
				'value'       => '13',
				'min'         => '1',
				'max'         => '100',
				'step'        => '1',
				'group'       => esc_attr__( 'Design', 'elegant-elements' ),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'List Item Alignment', 'elegant-elements' ),
				'description' => esc_attr__( 'Select list item alighment.', 'elegant-elements' ),
				'param_name'  => 'item_align',
				'default'     => 'center',
				'value'       => array(
					'left'   => esc_attr__( 'Left', 'elegant-elements' ),
					'center' => esc_attr__( 'Center', 'elegant-elements' ),
					'right'  => esc_attr__( 'Right', 'elegant-elements' ),
				),
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
				'description' => esc_attr__( 'Add a class to the wrapping HTML element.', 'elegant-elements' ),
				'param_name'  => 'class',
				'value'       => '',
				'group'       => esc_attr__( 'General', 'elegant-elements' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'CSS ID', 'elegant-elements' ),
				'description' => esc_attr__( 'Add an ID to the wrapping HTML element.', 'elegant-elements' ),
				'param_name'  => 'id',
				'value'       => '',
				'group'       => esc_attr__( 'General', 'elegant-elements' ),
			),
		),
	);

	$child_args = array(
		'name'              => esc_attr__( 'List Item', 'elegant-elements' ),
		'description'       => esc_attr__( 'Enter some content for this textblock', 'elegant-elements' ),
		'shortcode'         => 'iee_list_box_item',
		'hide_from_builder' => true,
		'allow_generator'   => true,
		'inline_editor'     => true,
		'tag_name'          => 'li',
		'selectors'         => array(
			'class' => 'elegant-list-item fusion-li-item',
		),
		'params'            => array(
			array(
				'type'        => 'iconpicker',
				'heading'     => esc_attr__( 'Select Icon', 'elegant-elements' ),
				'param_name'  => 'icon',
				'value'       => '',
				'description' => esc_attr__( 'This setting will override the global setting. ', 'elegant-elements' ),
			),
			array(
				'type'        => 'tinymce',
				'heading'     => esc_attr__( 'List Item Content', 'elegant-elements' ),
				'description' => esc_attr__( 'Add list item content.', 'elegant-elements' ),
				'param_name'  => 'element_content',
				'value'       => esc_attr__( 'Your Content Goes Here', 'elegant-elements' ),
				'placeholder' => true,
			),
		),
	);

	if ( function_exists( 'fusion_builder_frontend_data' ) ) {
		fusion_builder_map(
			fusion_builder_frontend_data(
				'IEE_List_Box',
				$parent_args,
				'parent'
			)
		);

		fusion_builder_map(
			fusion_builder_frontend_data(
				'IEE_List_Box',
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_list_box', 99 );
