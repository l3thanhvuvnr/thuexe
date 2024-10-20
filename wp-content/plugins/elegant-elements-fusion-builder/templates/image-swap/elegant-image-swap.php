<?php
$html = '<div ' . FusionBuilder::attributes( 'elegant-image-swap' ) . '>';

$modal_data = ( isset( $this->args['modal_anchor'] ) && '' !== $this->args['modal_anchor'] ) ? ' data-toggle="modal" data-target=".fusion-modal.' . $this->args['modal_anchor'] . '"' : '';
$image      = '<img' . $modal_data . ' ' . FusionBuilder::attributes( 'elegant-image-swap-main' ) . '/>';
$image     .= '<img' . $modal_data . ' ' . FusionBuilder::attributes( 'elegant-image-swap-hover' ) . '/>';

$html .= '<div class="elegant-image-swap-wrapper">';
if ( 'url' === $this->args['click_action'] ) {
	$url   = ( strpos( $this->args['url'], '://' ) === false ) ? 'http://' . $this->args['url'] : $this->args['url'];
	$html .= '<a href="' . $url . '" target="' . $this->args['target'] . '">';
	$html .= $image;
	$html .= '</a>';
} elseif ( 'lightbox' === $this->args['click_action'] ) {
	$lightbox_image_url = ( isset( $this->args['lightbox_image'] ) && '' !== $this->args['lightbox_image'] ) ? $this->args['lightbox_image'] : $this->args['image_url'];
	$lightbox_image_url = str_replace( array( '×', '&#215;' ), 'x', $lightbox_image_url );

	$image_caption       = '';
	$image_title         = '';
	$lightbox_image_meta = ( '' !== $this->args['lightbox_image_meta'] ) ? $this->args['lightbox_image_meta'] : '';

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
			$image_title     = ( $image_meta_data['image_meta'] ) ? $image_meta_data['image_meta']['title'] : '';

			if ( '' === $image_title && $lightbox_image_id ) {
				$image_title = get_the_title( $lightbox_image_id );
			}
		}
	}

	$lightbox_image = $lightbox_image_url;
	$data_rel       = 'iLightbox[elegant-image-swap-' . $this->image_counter . ']';

	$html .= '<a href="' . $lightbox_image . '" class="fusion-lightbox" data-rel="' . $data_rel . '" data-caption="' . $image_caption . '" data-title="' . $image_title . '">';
	$html .= $image;
	$html .= '</a>';
} else {
	$html .= $image;
}
$html .= '</div>';
$html .= '</div>';
