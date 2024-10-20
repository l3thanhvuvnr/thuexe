<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Elements_Creator_Mapping {

	/**
	 * The one, true instance of this object.
	 *
	 * @since 3.0
	 * @access public
	 * @var object
	 */
	public $element_creator_posts;

	/**
	 * Carousel counter.
	 *
	 * @since 3.0
	 * @access private
	 * @var object
	 */
	private $counter = 1;

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 3.0
	 * @return void
	 */
	public function __construct() {
		$transient_posts = get_transient( 'elegant_element_creator_posts' );

		if ( false === $transient_posts ) {
			$this->element_creator_posts = get_posts(
				array(
					'posts_per_page' => -1,
					'post_type'		 => 'element_creator',
				)
			);

			set_transient( 'elegant_element_creator_posts', $this->element_creator_posts, HOUR_IN_SECONDS * 24 );
		} else {
			$this->element_creator_posts = $transient_posts;
		}

		if ( $this->element_creator_posts ) {
			foreach ( $this->element_creator_posts as $key => $element ) {
				$shortcode_name = 'eec_' . str_replace( '-', '_', $element->post_name );

				// Add shortcode.
				add_shortcode( $shortcode_name, array( $this, 'render_shortcode' ) );
			}
		}

		// Add element creator elements to the Fusion Builder.
		add_action( 'fusion_builder_before_init', array( $this, 'init_creator_elements' ) );

		// Enqueue scripts in admin.
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10 );

		// Register scripts in the elements.
		add_action( 'wp_print_scripts', array( $this, 'register_element_scripts' ), 11 );

		// Add created elements to the list of enabled elements.
		add_filter( 'fusion_builder_enabled_elements', array( $this, 'enable_creator_elements' ) );
	}

	/**
	 * Initialize Creator elements once FB elements are loaded.
	 *
	 * @since 3.0
	 * @param array $fusion_builder_enabled_elements Fusion Builder enabled elements array.
	 * @return array Fusion Builder enabled elements array with creator elements added.
	 */
	public function enable_creator_elements( $fusion_builder_enabled_elements ) {
		if ( '' === $fusion_builder_enabled_elements ) {
			return $fusion_builder_enabled_elements;
		}

		if ( $this->element_creator_posts ) {
			foreach ( $this->element_creator_posts as $key => $element ) {
				$shortcode_name                    = 'eec_' . str_replace( '-', '_', $element->post_name );
				$fusion_builder_enabled_elements[] = $shortcode_name;
			}
		}

		return $fusion_builder_enabled_elements;
	}

	/**
	 * Add templates required for elegant elements on front-end.
	 *
	 * @since 3.0
	 * @access public
	 * @return void
	 */
	public function frontend_load_templates() {
		if ( $this->element_creator_posts ) {
			foreach ( $this->element_creator_posts as $key => $element ) {
				elegant_element_creator_generate_template( $element );
			}
		}
	}

	/**
	 * Register scripts in the elements.
	 *
	 * @access public
	 * @since 3.0
	 * @return void
	 */
	public function register_element_scripts() {
		if ( ! is_admin() && $this->element_creator_posts ) {
			foreach ( $this->element_creator_posts as $key => $element ) {
				$css_files = get_field( 'css_files', $element->ID );
				$js_files  = get_field( 'javascript_files', $element->ID );

				if ( $css_files ) {
					foreach ( $css_files as $css_file ) {
						$file_name = str_replace( ' ', '-', $css_file['file_name'] );
						$file_url  = $css_file['upload_file'];

						wp_register_style( $file_name, $file_url, '', ELEGANT_ELEMENTS_VERSION );
					}
				}

				if ( $js_files ) {
					foreach ( $js_files as $js_file ) {
						$file_name = str_replace( ' ', '-', $js_file['file_name'] );
						$file_url  = $js_file['upload_file'];

						wp_register_script( $file_name, $file_url, array( 'jquery' ), ELEGANT_ELEMENTS_VERSION, false );
					}
				}
			}
		}
	}

	/**
	 * Map elements created with element creator.
	 *
	 * @access public
	 * @since 3.0
	 * @return void
	 */
	public function init_creator_elements() {
		$elements = $this->element_creator_posts;

		if ( function_exists( 'fusion_is_builder_frame' ) && fusion_is_builder_frame() ) {
			// Add front-end templates.
			$this->frontend_load_templates();
		}

		if ( $elements ) {
			foreach ( $elements as $key => $element ) {
				$attributes   = get_field( 'attributes', $element->ID );
				$editor_field = get_field( 'wp_editor_field', $element->ID );
				$element_icon = get_field( 'element_icon_class', $element->ID );

				$params     = array();
				$attributes = $attributes['attribute'];

				if ( isset( $editor_field['wp_editor_field'] ) && $editor_field['wp_editor_field'] ) {
					$param_settings = array(
						'type'         => 'tinymce',
						'heading'      => $editor_field['heading'],
						'param_name'   => 'element_content',
						'value'        => '',
						'description'  => $editor_field['description'],
						'dynamic_data' => true,
					);

					if ( '' !== $editor_field['settings_group'] ) {
						$param_settings['group'] = $editor_field['settings_group'];
					}

					$params[] = $param_settings;
				}

				if ( $attributes ) {
					foreach ( $attributes as $attribute ) {
						$type       = $attribute['element_settings_type'];
						$options    = $attribute['options'];
						$param_name = str_replace( array( ' ', '-' ), '_', strtolower( $attribute['param_name'] ) );

						$param_options = array();
						if ( $options ) {
							foreach ( $options as $key => $option ) {
								$param_options[ $option['option_value'] ] = $option['option_name'];
							}
						}

						// Check field type and create array accordingly.
						switch ( $type ) {
							case 'textfield':
							case 'textarea':
							case 'colorpicker':
							case 'colorpickeralpha':
							case 'upload':
							case 'uploadfile':
							case 'iconpicker':
							case 'link_selector':
							case 'date_time_picker':
								$param_settings = array(
									'type'         => $type,
									'heading'      => $attribute['heading'],
									'param_name'   => $param_name,
									'dynamic_data' => true,
									'value'        => $attribute['default_value'],
									'placeholder'  => true,
									'description'  => $attribute['description'],
								);

								if ( '' !== $attribute['settings_group'] ) {
									$param_settings['group'] = $attribute['settings_group'];
								}

								$params[] = $param_settings;
								break;
							case 'checkbox_button_set':
							case 'radio_button_set':
							case 'select':
							case 'multiple_select':
								$param_settings = array(
									'type'         => $type,
									'heading'      => $attribute['heading'],
									'param_name'   => $param_name,
									'dynamic_data' => true,
									'value'        => $param_options,
									'default'      => $attribute['default_value'],
									'description'  => $attribute['description'],
								);

								if ( '' !== $attribute['settings_group'] ) {
									$param_settings['group'] = $attribute['settings_group'];
								}

								$params[] = $param_settings;
								break;
							case 'range':
								$param_settings = array(
									'type'         => $type,
									'heading'      => $attribute['heading'],
									'param_name'   => $param_name,
									'dynamic_data' => true,
									'value'        => $attribute['default_value'],
									'min'          => $attribute['range_values']['min'],
									'max'          => $attribute['range_values']['max'],
									'step'         => 1,
									'description'  => $attribute['description'],
								);

								if ( '' !== $attribute['settings_group'] ) {
									$param_settings['group'] = $attribute['settings_group'];
								}

								$params[] = $param_settings;
								break;
							case 'padding':
							case 'margin':
								$param_settings = array(
									'type'         => 'dimension',
									'heading'      => $attribute['heading'],
									'param_name'   => $param_name,
									'dynamic_data' => true,
									'default'      => $attribute['default_value'],
									'value'        => $attribute['default_value'],
									'description'  => $attribute['description'] . ' ' . __( 'Enter value with valid css unit. ex. (px,em,%)', 'elegant-elements' ),
								);

								if ( '' !== $attribute['settings_group'] ) {
									$param_settings['group'] = $attribute['settings_group'];
								}

								$params[] = $param_settings;
								break;
						}
					}

					$shortcode_name = str_replace( '-', '_', $element->post_name );

					// Map with Fusion Builder.
					fusion_builder_map(
						array(
							'name'                     => $element->post_title,
							'shortcode'                => 'eec_' . $shortcode_name,
							'icon'                     => ( $element_icon ) ? $element_icon : 'fa-cog fas',
							'allow_generator'          => true,
							'inline_editor'            => true,
							'inline_editor_shortcodes' => true,
							'params'                   => $params,
						)
					);
				}
			}
		}
	}

	/**
	 * Enqueue required js on backend.
	 *
	 * @since 3.0
	 * @access public
	 * @param string $hook Current screen ID.
	 * @return void
	 */
	public function admin_enqueue_scripts( $hook ) {
		global $post;

		if ( 'post-new.php' === $hook || 'post.php' === $hook ) {
			if ( 'element_creator' === $post->post_type ) {
				wp_enqueue_code_editor( array( 'type' => 'text/html' ) );
				wp_enqueue_script( 'elegant-element-creator-js', ELEGANT_ELEMENTS_PLUGIN_URL . 'inc/creator/js/element-creator.js', array( 'jquery', 'code-editor' ), ELEGANT_ELEMENTS_VERSION, true );
			}
		}
	}

	/**
	 * Render the shortcode.
	 *
	 * @access public
	 * @since 3.0
	 * @param  array  $atts          Shortcode paramters.
	 * @param  string $content       Content between shortcode.
	 * @param  string $shortcode_tag Current shortcode tag.
	 * @return string                HTML output.
	 */
	public function render_shortcode( $atts, $content = '', $shortcode_tag = '' ) {
		$element_html = '';
		$element_css  = '';
		$html         = '';
		$js_file_name = '';
		$element_id   = wp_rand() + $this->counter++;
		$params       = array();

		if ( $this->element_creator_posts ) {
			foreach ( $this->element_creator_posts as $key => $element ) {
				$shortcode_name = 'eec_' . str_replace( '-', '_', $element->post_name );
				$css_files      = get_field( 'css_files', $element->ID );
				$js_files       = get_field( 'javascript_files', $element->ID );
				$attributes     = get_field( 'attributes', $element->ID );
				$attributes     = $attributes['attribute'];

				if ( $attributes ) {
					foreach ( $attributes as $attribute ) {
						$param_name            = str_replace( array( ' ', '-' ), '_', strtolower( $attribute['param_name'] ) );
						$params[ $param_name ] = $attribute['default_value'];
					}
				}

				if ( $css_files ) {
					foreach ( $css_files as $css_file ) {
						$file_name = str_replace( ' ', '-', $css_file['file_name'] );

						wp_enqueue_style( $file_name );
					}
				}

				if ( $js_files ) {
					foreach ( $js_files as $js_file ) {
						$js_file_name = str_replace( ' ', '-', $js_file['file_name'] );

						wp_enqueue_script( $js_file_name );
					}
				}

				if ( $shortcode_name === $shortcode_tag ) {
					// Get element HTML.
					$element_html = get_field( 'element_html', $element->ID );
					$element_html = $element_html['editor'];

					// Get element CSS.
					$element_css = get_field( 'element_css', $element->ID );
					$element_css = $element_css['editor'];

					// Get element JS.
					$element_js = get_field( 'element_js', $element->ID );
					$element_js = $element_js['editor'];
					continue;
				}
			}

			$defaults = FusionBuilder::set_shortcode_defaults( $params, $atts );

			foreach ( $defaults as $key => $default_value ) {
				$value = isset( $defaults[ $key ] ) ? $defaults[ $key ] : $default_value;

				// If dimension param is available, replace with the combined values.
				if ( isset( $defaults[ $key . '_top' ] ) ) {
					$dimension_values           = array();
					$dimension_values['top']    = ( isset( $defaults[ $key . '_top' ] ) && '' !== $defaults[ $key . '_top' ] ) ? FusionBuilder::validate_shortcode_attr_value( $defaults[ $key . '_top' ], 'px' ) : '0px';
					$dimension_values['right']  = ( isset( $defaults[ $key . '_right' ] ) && '' !== $defaults[ $key . '_right' ] ) ? FusionBuilder::validate_shortcode_attr_value( $defaults[ $key . '_right' ], 'px' ) : '0px';
					$dimension_values['bottom'] = ( isset( $defaults[ $key . '_bottom' ] ) && '' !== $defaults[ $key . '_bottom' ] ) ? FusionBuilder::validate_shortcode_attr_value( $defaults[ $key . '_bottom' ], 'px' ) : '0px';
					$dimension_values['left']   = ( isset( $defaults[ $key . '_left' ] ) && '' !== $defaults[ $key . '_left' ] ) ? FusionBuilder::validate_shortcode_attr_value( $defaults[ $key . '_left' ], 'px' ) : '0px';

					$value = implode( ' ', $dimension_values );

				}

				// Replace attributes with their values in element HTML.
				$element_html = str_replace( '{{' . $key . '}}', $value, $element_html );

				// Replace attributes with their values in the CSS.
				if ( '' !== $element_css ) {
					$element_css = str_replace( '{{' . $key . '}}', $value, $element_css );
				}

				// Replace attributes with their values in the JS.
				if ( '' !== $element_js ) {
					$element_js = str_replace( '{{' . $key . '}}', $value, $element_js );
				}
			}

			$html = '';

			// Add inline js to the dependent script if available, else add as individual script tag.
			if ( '' !== $element_js ) {
				$element_js = str_replace( '{{element_id}}', $element_id, $element_js );
				if ( '' !== $js_file_name ) {
					wp_add_inline_script( $js_file_name, $element_js );
				} else {
					$html .= '<script type="text/javascript">' . $element_js . '</script>';
				}
			}

			// If template has editor content, replace with the $content.
			$element_html = str_replace( '{{wp_editor_content}}', $content, $element_html );

			// Replace the element id with random string.
			$element_html = str_replace( '{{element_id}}', $element_id, $element_html );
			$element_css  = str_replace( '{{element_id}}', $element_id, $element_css );

			$html .= '<style type="text/css">' . $element_css . '</style>';
			$html .= $element_html;
		}

		return do_shortcode( $html );
	}
}

new Elements_Creator_Mapping();
