<?php
$image_max_width = FusionBuilder::validate_shortcode_attr_value( $this->child_args['image_max_width'], 'px' );

$child_html = '<div ' . FusionBuilder::attributes( 'elegant-horizontal-scrolling-image-item' ) . '>';

$image_alt  = get_post_meta( $this->child_args['image_id'], '_wp_attachment_image_alt', true );
$image_alt  = ( '' !== $image_alt ) ? $image_alt : basename( $this->child_args['image_url'] );
$modal_data = ( isset( $this->child_args['modal_anchor'] ) && '' !== $this->child_args['modal_anchor'] ) ? 'data-toggle="modal" data-target=".fusion-modal.' . $this->child_args['modal_anchor'] . '"' : '';
$image      = '<img ' . $modal_data . 'src="' . $this->child_args['image_url'] . '" alt="' . $image_alt . '"/>';

if ( 'url' === $this->child_args['click_action'] ) {
	$url         = ( strpos( $this->child_args['url'], '://' ) === false ) ? 'http://' . $this->child_args['url'] : $this->child_args['url'];
	$child_html .= '<a style="display: inline-block;max-width:' . $image_max_width . ';" href="' . $url . '" target="' . $this->child_args['target'] . '">';
	$child_html .= $image;
	$child_html .= '</a>';
} elseif ( 'lightbox' === $this->child_args['click_action'] ) {
	$lightbox_image_url = ( isset( $this->child_args['lightbox_image'] ) && '' !== $this->child_args['lightbox_image'] ) ? $this->child_args['lightbox_image'] : $this->child_args['image_url'];
	$lightbox_image_url = str_replace( array( '×', '&#215;' ), 'x', $lightbox_image_url );

	$image_caption       = '';
	$image_title         = '';
	$lightbox_image_meta = ( '' !== $this->child_args['lightbox_image_meta'] ) ? $this->child_args['lightbox_image_meta'] : '';

	if ( '' !== $lightbox_image_meta ) {
		$lightbox_image_id = attachment_url_to_postid( $lightbox_image_url );

		if ( ! $lightbox_image_id ) {
			$lightbox_image_id = $fusion_library->images->get_attachment_id_from_url( $lightbox_image_url );
		}

		if ( false !== strpos( $lightbox_image_meta, 'caption' ) ) {
			$image_caption = wp_get_attachment_caption( $lightbox_image_id );
		}

		if ( false !== strpos( $lightbox_image_meta, 'title' ) ) {
			$image_meta_data = wp_get_attachment_metadata( $lightbox_image_id );
			$image_title     = ( isset( $image_meta_data['image_meta'] ) ) ? $image_meta_data['image_meta']['title'] : '';

			if ( '' === $image_title && $lightbox_image_id ) {
				$image_title = get_the_title( $lightbox_image_id );
			}
		}
	}

	$lightbox_image = $lightbox_image_url;
	$data_rel       = 'iLightbox';

	$child_html .= '<a style="display: inline-block;max-width:' . $image_max_width . ';" href="' . $lightbox_image . '" class="fusion-lightbox" data-rel="' . $data_rel . '" data-caption="' . $image_caption . '" data-title="' . $image_title . '">';
	$child_html .= $image;
	$child_html .= '</a>';
} else {
	$child_html .= '<span style="display: inline-block;max-width:' . $image_max_width . ';">' . $image . '</span>';
}

$child_html .= '<style type="text/css">' . $this->add_child_style() . '</style>';
$child_html .= '</div>';
