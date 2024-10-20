<?php
$html = '<div ' . FusionBuilder::attributes( 'elegant-accordion-slider' ) . '>';

$html .= '<ul class="elegant-accordion-slider-items">';

$slider_gallery_id = wp_rand();

if ( 'images' === $this->args['source'] ) {
	$images = explode( ',', $this->args['images'] );

	if ( $images ) {
		foreach ( $images as $image_id ) {
			$image     = wp_get_attachment_image_src( $image_id, 'full' );
			$image_url = $image[0];
			$image_url = esc_url( $image_url );

			$image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
			$image_alt = ( '' !== $image_alt ) ? $image_alt : basename( $image_url );

			$html .= '<li ' . FusionBuilder::attributes( 'elegant-accordion-slider-item' ) . '>';
			$html .= '<div ' . FusionBuilder::attributes( 'elegant-accordion-slider-item-image' ) . '>';
			$html .= '<span><img src="' . $image_url . '" alt="' . $image_alt . '" /></span>';

			$anchor_style = ( 'no' === $this->args['display_image_title'] && 'no' === $this->args['display_image_description'] ) ? 'background: transparent;' : '';

			$html .= '<a href="' . $image_url . '" class="fusion-lightbox" data-rel="iLightbox[' . $slider_gallery_id . ']" style="' . $anchor_style . '">';

			$image_post = get_post( $image_id );

			if ( 'no' !== $this->args['display_image_title'] ) {
				$title = $image_post->post_title;
				$html .= '<h3 ' . FusionBuilder::attributes( 'elegant-accordion-slider-item-title' ) . '>' . $title . '</h3>';
			}

			if ( 'no' !== $this->args['display_image_description'] ) {
				$description = $image_post->post_content;
				$html       .= '<p ' . FusionBuilder::attributes( 'elegant-accordion-slider-item-description' ) . '>' . $description . '</p>';
			}

			$html .= '</a>';
			$html .= '</div>';
			$html .= '</li>';
		}
	}
}

$html .= '</ul>';
$html .= '</div>';
