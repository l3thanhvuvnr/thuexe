<?php
global $fusion_library;

do_shortcode( $content );

$html .= '<div ' . FusionBuilder::attributes( 'elegant-image-filters-wrapper' ) . '>';

$html .= '<ul ' . FusionBuilder::attributes( 'elegant-image-filters-navigation' ) . '>';

$filter_separator = '';
if ( isset( $this->args['navigation_layout'] ) && 'horizontal' == $this->args['navigation_layout'] ) {
	$filter_separator = ( isset( $this->args['filter_separator'] ) && '' !== $this->args['filter_separator'] ) ? '<span class="image-filter-navigation-separator">' . $this->args['filter_separator'] . '</span>' : '';
}

// Add "All" filter.
if ( isset( $this->args['use_all_filter'] ) && 'yes' === $this->args['use_all_filter'] ) {
	$all_filter_text = ( isset( $this->args['all_filter_text'] ) && '' !== $this->args['all_filter_text'] ) ? $this->args['all_filter_text'] : __( 'All', 'elegant-elements' );
	$html           .= '<li ' . FusionBuilder::attributes( 'elegant-image-filters-navigation-item' ) . '>';
	$html           .= '<a href="#" data-filter="*">' . $all_filter_text . '</a>';
	$html           .= '</li>';
	$html           .= $filter_separator;
}

$i = 1;

if ( $this->image_filter_navigation[ $this->image_filters_counter ] ) {
	$c   = count( $this->image_filter_navigation[ $this->image_filters_counter ] );
	$nav = $this->image_filter_navigation[ $this->image_filters_counter ];

	if ( isset( $this->args['navigation_alpha'] ) && 'yes' === $this->args['navigation_alpha'] ) {
		ksort( $nav );
	}

	foreach ( $nav as $id => $title ) {
		$id = str_replace( ' ', '-', $id ); // Replaces all spaces with hyphens.
		$id = preg_replace( '/[^A-Za-z0-9\-]/', '', $id ); // Removes special chars.

		$html .= '<li ' . FusionBuilder::attributes( 'elegant-image-filters-navigation-item' ) . '>';
		$html .= '<a href="#" data-filter=".' . esc_attr( $id ) . '">' . $title . '</a>';
		$html .= '</li>';

		if ( $i < $c ) {
			$html .= $filter_separator;
		}

		$i++;
	}
}

$html .= '</ul>';

$html .= '<div ' . FusionBuilder::attributes( 'elegant-image-filters-content' ) . '>';

foreach ( $this->image_filters[ $this->image_filters_counter ] as $key => $filter_image ) {

	$image_alt = get_post_meta( $filter_image['args']['image_id'], '_wp_attachment_image_alt', true );
	$image_alt = ( '' !== $image_alt ) ? $image_alt : basename( $filter_image['image_url'] );

	$args           = array();
	$args['id']     = $filter_image['id'];
	$args['class']  = 'elegant-image-filter-item';
	$args['class'] .= ' ' . $filter_image['orientation'];
	$args['class'] .= ( '' !== $filter_image['class'] ) ? ' ' . $filter_image['class'] : '';

	$args['style'] = 'padding:' . FusionBuilder::validate_shortcode_attr_value( $this->args['grid_item_padding'], 'px' ) . ';';

	// Get navigation title.
	$navigation_title = $filter_image['navigation'];
	$navigation_title = explode( ',', $navigation_title );

	foreach ( $navigation_title as $title ) {
		$navigation_id  = str_replace( array( ' ', '_', '-' ), '', strtolower( $title ) );
		$navigation_id  = str_replace( ' ', '-', $navigation_id ); // Replaces all spaces with hyphens.
		$navigation_id  = preg_replace( '/[^A-Za-z0-9\-]/', '', $navigation_id ); // Removes special chars.
		$args['class'] .= ' ' . $navigation_id;
	}

	$html .= '<div ' . FusionBuilder::attributes( 'elegant-image-filter', $args ) . '>';

	if ( ( isset( $this->args['image_title_position'] ) && 'before_image' == $this->args['image_title_position'] ) && isset( $filter_image['title'] ) && '' !== $filter_image['title'] ) {
		$html .= '<div ' . FusionBuilder::attributes( 'elegant-image-filter-title', $filter_image['args'] ) . '>';
		$html .= $filter_image['title'];
		$html .= '</div>';
	}

	$modal_data = ( isset( $filter_image['modal_anchor'] ) && '' !== $filter_image['modal_anchor'] ) ? 'data-toggle="modal" data-target=".fusion-modal.' . $filter_image['modal_anchor'] . '"' : '';

	if ( '' !== $modal_data ) {
		$image = '<a href="javascript:void(0);" ' . $modal_data . '><img src="' . $filter_image['image_url'] . '" alt="' . $image_alt . '"/></a>';
	} else {
		$image = '<img src="' . $filter_image['image_url'] . '" alt="' . $image_alt . '"/>';
	}

	if ( 'url' === $filter_image['click_action'] ) {
		$url   = $filter_image['url'];
		$html .= '<a href="' . $url . '" target="' . $filter_image['target'] . '">';
		$html .= $image;
	} elseif ( 'lightbox' === $filter_image['click_action'] ) {
		$lightbox_image_url = ( isset( $filter_image['lightbox_image_url'] ) && '' !== $filter_image['lightbox_image_url'] ) ? $filter_image['lightbox_image_url'] : $filter_image['image_url'];
		$lightbox_image_url = str_replace( array( '×', '&#215;' ), 'x', $lightbox_image_url );

		$image_caption       = '';
		$image_title         = '';
		$lightbox_image_meta = ( '' !== $filter_image['args']['lightbox_image_meta'] ) ? $filter_image['args']['lightbox_image_meta'] : $this->args['lightbox_image_meta'];

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
		$data_rel       = 'iLightbox[gallery_image_' . $this->image_filters_counter . ']';

		$html .= '<a href="' . $lightbox_image . '" class="fusion-lightbox" data-rel="' . $data_rel . '" data-caption="' . $image_caption . '" data-title="' . $image_title . '">';
		$html .= $image;

		if ( ( isset( $this->args['image_title_position'] ) && ( 'on_image_hover' == $this->args['image_title_position'] || 'after_image' == $this->args['image_title_position'] ) ) && isset( $filter_image['title'] ) && '' !== $filter_image['title'] ) {
			$html .= '<div ' . FusionBuilder::attributes( 'elegant-image-filter-title', $filter_image['args'] ) . '>';

			if ( 'on_image_hover' == $this->args['image_title_position'] ) {
				$html .= '<div ' . FusionBuilder::attributes( 'elegant-image-filter-title-overlay', $filter_image['args'] ) . '>' . $filter_image['title'] . '</div>';
			} else {
				$html .= $filter_image['title'];
			}
			$html .= '</div>';
		}

		$html .= '</a>';
	} else {
		$html .= $image;
	}

	if ( 'lightbox' !== $filter_image['click_action'] ) {
		if ( ( isset( $this->args['image_title_position'] ) && ( 'on_image_hover' == $this->args['image_title_position'] || 'after_image' == $this->args['image_title_position'] ) ) && isset( $filter_image['title'] ) && '' !== $filter_image['title'] ) {
			$html .= '<div ' . FusionBuilder::attributes( 'elegant-image-filter-title', $filter_image['args'] ) . '>';

			if ( 'on_image_hover' == $this->args['image_title_position'] ) {
				$html .= '<div ' . FusionBuilder::attributes( 'elegant-image-filter-title-overlay', $filter_image['args'] ) . '>' . $filter_image['title'] . '</div>';
			} else {
				$html .= $filter_image['title'];
			}
			$html .= '</div>';
		}
	}

	if ( 'url' === $filter_image['click_action'] ) {
		$html .= '</a>';
	}

	$html .= '</div>';
}

$html .= '</div>';
$html .= '</div>';
