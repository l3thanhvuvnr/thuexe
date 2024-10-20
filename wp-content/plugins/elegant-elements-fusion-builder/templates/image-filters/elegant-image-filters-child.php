<?php
// Get image ID.
$image_id = $args['image_id'];

$orientation = isset( $args['orientation'] ) ? $args['orientation'] : 'auto';

if ( 'auto' === $orientation ) {
	// Get image meta data to check orientation.
	$image_metadata = wp_get_attachment_metadata( $image_id );

	// Check for image orientation.
	$image_orientation = 'elegant-image-portrait';
	$image_sizes       = ( isset( $image_metadata['sizes'] ) && ! empty( $image_metadata['sizes'] ) ) ? $image_metadata['sizes'] : array();
	$image_width       = ( isset( $image_metadata['width'] ) ) ? $image_metadata['width'] : '';
	$image_height      = ( isset( $image_metadata['height'] ) ) ? $image_metadata['height'] : '';

	if ( ! empty( $image_sizes ) ) {
		foreach ( $image_sizes as $size => $image_data ) {
			if ( basename( $args['image_url'] ) == $image_data['file'] ) {
				$image_width  = $image_data['width'];
				$image_height = $image_data['height'];
			}
		}
	}

	$ratio = '0.8';

	$lower_limit = ( $ratio / 2 ) + ( $ratio / 4 );
	$upper_limit = ( $ratio * 2 ) - ( $ratio / 2 );

	// Landscape image.
	if ( $image_width ) {
		if ( $lower_limit > $image_height / $image_width ) {
			$image_orientation = 'elegant-image-landscape';
		} elseif ( $upper_limit < $image_height / $image_width ) {
			$image_orientation = 'elegant-image-portrait';
		}
	}
} else {
	$image_orientation = 'elegant-image-' . $orientation;
}

$this->image_filters[ $this->image_filters_counter ][] = array(
	'image_url'          => $args['image_url'],
	'lightbox_image_url' => isset( $content ) ? $content : '',
	'title'              => $args['title'],
	'navigation'         => $args['navigation'],
	'click_action'       => $args['click_action'],
	'modal_anchor'       => $args['modal_anchor'],
	'url'                => $args['url'],
	'target'             => $args['target'],
	'class'              => $args['class'],
	'id'                 => $args['id'],
	'orientation'        => $image_orientation,
	'args'               => $args,
);

// Create navigation array.
$navigation_title = $args['navigation'];
$navigation_title = explode( ',', $navigation_title );

foreach ( $navigation_title as $title ) {
	if ( '' !== trim( $title ) ) {
		$navigation_id = str_replace( array( ' ', '_', '-' ), '', strtolower( $title ) );
		$this->image_filter_navigation[ $this->image_filters_counter ][ $navigation_id ] = $title;
	}
}
