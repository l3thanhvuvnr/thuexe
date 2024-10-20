<?php
$username      = ( '' !== $this->args['username'] ) ? $this->args['username'] : '';
$limit         = ( '' !== $this->args['photos_count'] ) ? $this->args['photos_count'] : 9;
$size          = ( '' !== $this->args['photo_size'] ) ? $this->args['photo_size'] : 'large';
$target        = ( '' !== $this->args['link_target'] ) ? $this->args['link_target'] : '_self';
$show_likes    = ( 'no' !== $this->args['show_likes'] ) ? true : false;
$show_comments = ( 'no' !== $this->args['show_comments'] ) ? true : false;
$hover_type    = ( '' !== $this->args['hover_type'] ) ? $this->args['hover_type'] : 'none';

$html = '<div ' . FusionBuilder::attributes( 'elegant-instagram-gallery' ) . '>';

if ( 'grid' !== $this->args['gallery_layout'] ) {
	// Enqueue Isotope for Masonry.
	$js_folder_url = FUSION_LIBRARY_URL . '/assets' . ( ( true === FUSION_LIBRARY_DEV_MODE ) ? '' : '/min' ) . '/js';
	wp_enqueue_script( 'isotope', $js_folder_url . '/library/isotope.js', array(), FUSION_BUILDER_VERSION, true );
	wp_enqueue_script( 'packery', $js_folder_url . '/library/packery.js', array(), FUSION_BUILDER_VERSION, true );
	wp_enqueue_script( 'images-loaded', $js_folder_url . '/library/imagesLoaded.js', array(), FUSION_BUILDER_VERSION, true );
	wp_add_inline_script(
		'isotope',
		"// Set the layout after all the images are loaded.
		jQuery( '.elegant-instagram-gallery-masonry .elegant-instagram-pics' ).isotope();
		jQuery( window ).load( function() {
			jQuery( '.elegant-instagram-gallery-masonry .elegant-instagram-pics' ).each( function() {
				jQuery( this ).isotope( 'layout' );
			} );
		} );

		jQuery( document ).on( 'instagramGalleryLoaded', function( event, items ) {
			jQuery( '.elegant-instagram-gallery-masonry .elegant-instagram-pics' ).each( function() {
				var galleryItems = items.items;
				jQuery( this ).isotope( 'appended', jQuery( galleryItems ) );
				jQuery( this ).isotope( 'layout' );
				jQuery( this ).css( 'opacity', 1 );
			} );
		} );"
	);
} else {
	// Enqueue Isotope for Masonry.
	$js_folder_url = FUSION_LIBRARY_URL . '/assets' . ( ( true === FUSION_LIBRARY_DEV_MODE ) ? '' : '/min' ) . '/js';
	wp_enqueue_script( 'isotope', $js_folder_url . '/library/isotope.js', array(), FUSION_BUILDER_VERSION, true );
	wp_enqueue_script( 'packery', $js_folder_url . '/library/packery.js', array(), FUSION_BUILDER_VERSION, true );
	wp_enqueue_script( 'images-loaded', $js_folder_url . '/library/imagesLoaded.js', array(), FUSION_BUILDER_VERSION, true );
	wp_add_inline_script(
		'isotope',
		"// Set the layout after all the images are loaded.
		jQuery( '.elegant-instagram-gallery .elegant-instagram-pics' ).isotope( { layoutMode: 'fitRows' } );
		jQuery( window ).load( function() {
			jQuery( '.elegant-instagram-gallery .elegant-instagram-pics' ).each( function() {
				jQuery( this ).isotope( 'layout' );
			} );
		} );"
	);
}

if ( '' !== $username ) {

	$column = $size;
	if ( 'grid' !== $this->args['gallery_layout'] ) {
		$column = ( '' !== $this->args['masonry_columns'] ) ? $this->args['masonry_columns'] : $size;
		$size   = 'original';
	}

	$media_array = elegant_scrape_instagram( $username, $target, $limit, $column, $this->args['show_likes'], $this->args['show_comments'], $this->args['gallery_layout'] );

	if ( is_wp_error( $media_array ) ) {

		$html .= $media_array->get_error_message();

	} else {
		unset( $media_array['user'] );

		// filters for custom classes.
		$ulclass  = apply_filters( 'elegant_instagram_list_class', 'elegant-instagram-pics elegant-instagram-size-' . $column );
		$liclass  = apply_filters( 'elegant_instagram_item_class', 'elegant-instagram-pic' );
		$aclass   = apply_filters( 'elegant_instagram_a_class', 'elegant-instagram-pic-link' );
		$imgclass = apply_filters( 'elegant_instagram_img_class', '' );

		if ( 'none' !== $hover_type ) {
			$aclass .= ' hover-type-' . $hover_type;
		}

		$images = '';
		$i      = 0;

		foreach ( $media_array as $item ) {
			$comments       = $item['comments'];
			$likes          = $item['likes'];
			$type           = $item['type'];
			$likes_comments = '';

			// If video type, skip it.
			if ( 'VIDEO' === $type ) {
				continue;
			}

			// Limit the media display.
			if ( $limit <= $i ) {
				continue;
			}

			if ( $show_likes && $likes ) {
				$likes_comments .= '<span class="elegant-instagram-likes fa fa-heart"> ' . $likes . '</span>';
			}

			if ( $show_comments && $comments ) {
				$likes_comments .= '<span class="elegant-instagram-comments fa fa-comment"> ' . $comments . '</span>';
			}

			if ( 'lightbox' !== $target ) {

				$images .= '<li class="' . esc_attr( $liclass ) . '">';
				$images .= '<div class="elegant-instagram-pic-wrapper">';
				$images .= '<a href="' . esc_url( $item['link'] ) . '" target="' . esc_attr( $target ) . '"  class="' . esc_attr( $aclass ) . '">';
				$images .= '<img src="' . esc_url( $item[ $size ] ) . '" alt="' . esc_html( $item['description'] ) . '" title="' . esc_attr( $item['description'] ) . '"  class="' . esc_attr( $imgclass ) . ' disable-lazyload"/>';
				$images .= '</a>';

				if ( '' !== $likes_comments ) {
					$images .= '<div class="elegant-instagram-pic-likes">';
					$images .= $likes_comments;
					$images .= '</div>';
				}

				$images .= '</div>';
				$images .= '</li>';
			} else {
				$item_link = $item['original'] . '&type=.jpg';
				$user_id   = str_replace( array( '@', '#', '.' ), '', $username );
				$data_rel  = 'iLightbox[gallery_image_' . $user_id . ']';

				$images .= '<li class="' . esc_attr( $liclass ) . '">';
				$images .= '<div class="elegant-instagram-pic-wrapper">';
				$images .= '<a href="' . esc_url( $item_link ) . '" data-rel="' . $data_rel . '" class="fusion-lightbox ' . esc_attr( $aclass ) . '">';
				$images .= '<img src="' . esc_url( $item[ $size ] ) . '"  class="' . esc_attr( $imgclass ) . ' disable-lazyload">';
				$images .= '</a>';

				if ( '' !== $likes_comments ) {
					$images .= '<div class="elegant-instagram-pic-likes">';
					$images .= $likes_comments;
					$images .= '</div>';
				}

				$images .= '</div>';
				$images .= '</li>';
			}

			$i++;
		}

		$html .= '<ul class="' . esc_attr( $ulclass ) . '">';
		$html .= $images;
		$html .= '</ul>';
	}
}

$html .= '</div>';
