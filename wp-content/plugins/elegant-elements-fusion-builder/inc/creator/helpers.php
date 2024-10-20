<?php
/**
 * Elegant element creator helper functions.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Create js view required for element created with element creator on front-end builder.
 *
 * @since 3.0
 * @access public
 * @param object $element Element post object.
 * @return void
 */
function elegant_element_creator_generate_view( $element ) {
	$shortcode_name = 'eec_' . str_replace( '-', '_', $element->post_name );
	$attributes     = get_field( 'attributes', $element->ID );
	$attributes     = $attributes['attribute'];
	?>
	<script type="text/javascript">
		var FusionPageBuilder = FusionPageBuilder || {};

		( function() {

			jQuery( document ).ready( function() {

				// Element View.
				FusionPageBuilder.<?php echo $shortcode_name; ?> = FusionPageBuilder.ElementView.extend( {

					/**
					 * Modify template attributes.
					 *
					 * @since 2.0
					 * @param {Object} atts - The attributes.
					 * @return {Object} atts - The attributes.
					 */
					filterTemplateAtts: function( atts ) {
						var attributes = {},
							params = {};

						<?php
						if ( $attributes ) {
							foreach ( $attributes as $attribute ) {
								$param_name = str_replace( array( ' ', '-' ), '_', strtolower( $attribute['param_name'] ) );
								?>
								params['<?php echo esc_attr( $param_name ); ?>'] = '<?php echo esc_attr( $attribute['default_value'] ); ?>';
								<?php
							}
						}
						?>

						// Validate values.
						this.validateValues( atts.params, params );

						// Assign all params.
						attributes = atts.params;

						// Unique ID for this particular element instance, can be useful.
						attributes.cid        = this.model.get( 'cid' );
						attributes.element_id = this.model.get( 'cid' );

						// Any extras that need passed on.
						attributes.content           = atts.params.element_content;
						attributes.wp_editor_content = attributes.content;

						return attributes;
					},

					/**
					 * Modifies values.
					 *
					 * @since 3.0
					 * @param {Object} values - The values.
					 * @return {void}
					 */
					validateValues: function( values, params ) {
						var dimensionValues = '';

						_.each( params, function( value, name ) {
							if ( ( 'undefined' !== typeof values[ name + '_top' ] ) ) {
								dimensionValues  = ( ( 'undefined' !== typeof values[ name + '_top' ] ) ? _.fusionGetValueWithUnit( values[ name + '_top' ] ) + '' : '0px' );
								dimensionValues += ' ' + ( ( 'undefined' !== typeof values[ name + '_right' ] ) ? _.fusionGetValueWithUnit( values[ name + '_right' ] ) + '' : '0px' );
								dimensionValues += ' ' + ( ( 'undefined' !== typeof values[ name + '_bottom' ] ) ? _.fusionGetValueWithUnit( values[ name + '_bottom' ] ) + '' : '0px' );
								dimensionValues += ' ' + ( ( 'undefined' !== typeof values[ name + '_left' ] ) ? _.fusionGetValueWithUnit( values[ name + '_left' ] ) + '' : '0px' );

								values[ name ] = dimensionValues;
							} else {
								values[ name ] = values[ name ];
							}
						} );
					}
				} );
			} );
		}( jQuery ) );
	</script>
	<?php
}

/**
 * Create backbone template required for element created with element creator on front-end builder.
 *
 * @since 3.0
 * @access public
 * @param object $element Element post object.
 * @return void
 */
function elegant_element_creator_generate_template( $element ) {
	// Get shortcode name.
	$shortcode_name = 'eec_' . str_replace( '-', '_', $element->post_name );

	// Get element HTML and replace the variable placeholders.
	$element_html = get_field( 'element_html', $element->ID );
	$element_html = $element_html['editor'];
	$element_html = str_replace( '{{', '{{{', $element_html );
	$element_html = str_replace( '}}', '}}}', $element_html );

	// Get element CSS and replacce the variable placeholders.
	$element_css = get_field( 'element_css', $element->ID );
	$element_css = $element_css['editor'];
	$element_css = str_replace( '{{', '{{{', $element_css );
	$element_css = str_replace( '}}', '}}}', $element_css );
	?>
	<script type="text/html" id="tmpl-<?php echo $shortcode_name; ?>-shortcode">
		<?php echo $element_html; ?>
		<style type="text/css">
			<?php echo $element_css; ?>
		</style>
	</script>
	<?php
}

/**
 * Load ACF fields from JSON.
 *
 * @access public
 * @since 3.0
 * @param object $object     Post object.
 * @param string $field_name Post meta field name.
 * @param string $request    REST API request.
 * @return array
 */
function elegant_get_images_urls( $object, $field_name, $request ) {
	$medium     = wp_get_attachment_image_src( get_post_thumbnail_id( $object['id'] ), 'medium' );
	$medium_url = $medium['0'];

	$large     = wp_get_attachment_image_src( get_post_thumbnail_id( $object['id'] ), 'large' );
	$large_url = $large['0'];

	return array(
		'medium' => $medium_url,
		'large'  => $large_url,
	);
}

add_action( 'wp_ajax_element_creator_import_element', 'element_creator_import_element' );
/**
 * Load ACF fields from JSON.
 *
 * @access public
 * @since 3.0
 * @return void
 */
function element_creator_import_element() {
	$data = $_POST; // @codingStandardsIgnoreLine

	$element_name = $data['element_name'];
	$acf_data     = json_decode( base64_decode( $data['acf'] ), true ); // @codingStandardsIgnoreLine

	// Create post data to insert.
	$new_element_data = array(
		'post_title'   => $element_name,
		'post_content' => '',
		'post_status'  => 'publish',
		'post_author'  => 1,
		'post_type'    => 'element_creator',
	);

	// Insert the post into the database.
	$element_id = wp_insert_post( $new_element_data );

	if ( ! is_wp_error( $element_id ) ) {
		foreach ( $acf_data as $field_name => $field_value ) {
			update_field( $field_name, $field_value, $element_id );
		}

		$response = array(
			'element_edit_url' => get_edit_post_link( $element_id ),
			'message'          => __( 'Element imported successfully!', 'elegant-elements' ),
		);
	} else {
		$response = array(
			'element_edit_url' => '#',
			'message'          => __( 'Element import failed! Please try again.', 'elegant-elements' ),
		);
	}

	// Reset creator transients.
	delete_transient( 'elegant_element_creator_posts' );

	die( wp_json_encode( $response ) );
}
