<?php
$carousel_settings = array(
	'dots'           => false,
	'arrows'         => false,
	'infinite'       => true,
	'speed'          => 300,
	'slidesToShow'   => 1,
	'slidesToScroll' => 1,
	'fade'           => true,
	'autoplay'       => true,
	'accessibility'  => false,
);

$insta_array = elegant_scrape_instagram( $this->args['username'], '_self', 9, 'large', false, false, false );
$api_data    = get_option( 'elegant_elements_instagram_api_data', array() );
$settings    = get_option( 'elegant_elements_settings', array() );

if ( ! isset( $api_data['access_token'] ) ) {
	$insta_array = elegant_scrape_instagram( $this->args['username'], '_self', 15, 'large', false, false, false );
	if ( is_wp_error( $insta_array ) ) {

		$rand_id = wp_rand();

		$html  = '<div ' . FusionBuilder::attributes( 'elegant-instagram-profile-card' ) . '>';
		$html .= '<script type="text/javascript" class="elegant-slick-settings">var elegantSettings_insta' . $rand_id . ' = ' . wp_json_encode( $carousel_settings ) . ';</script>';
		$html .= '<ul class="instagram-images-' . $this->args['username'] . ' elegant-carousel elegant-slick" data-carousel-id="insta' . $rand_id . '">';
		$html .= '</ul>';

		// User profile pic.
		$html .= '<div class="elegant-instagram-profile-card-pic instagram-user-pic-' . $this->args['username'] . '">';
		$html .= '</div>';

		// Profile username and follow button.
		$html .= '<div class="elegant-instagram-profile-card-info">';
		$html .= '<div class="elegant-instagram-profile-card-handle">';
		$html .= '<h3 class="elegant-instagram-profile-handle">@' . $this->args['username'] . '</h3>';
		$html .= '<span class="elegant-instagram-profile-follow-count instagram-user-followers-' . $this->args['username'] . '"></span>';
		$html .= '</div>';
		$html .= '<a ' . FusionBuilder::attributes( 'elegant-instagram-profile-card-follow-button' ) . ' href="//instagram.com/' . $this->args['username'] . '" target="_blank">' . __( 'Follow', 'elegant-elements' ) . '</a>';
		$html .= '</div>';

		$html .= '</div>';

		ob_start();
		?>
		<script type="text/javascript">
		var url = "https://www.instagram.com/<?php echo esc_attr( $this->args['username'] ); ?>";

		function numberFormatter( num ) {
			var formattedNumber = num;

			if ( Math.abs( num ) > 999999 ) {
				formattedNumber = ( Math.abs( num ) / 1000000 ).toFixed( 1 ) + 'M';
			} else if ( Math.abs( num ) > 999 ) {
				formattedNumber = ( Math.abs( num ) / 1000 ).toFixed( 1 ) + 'K';
			}

			return formattedNumber;
		}

		jQuery.ajax( {
			type: 'GET',
			url: url,
			error: function () {
				//..
			},
			success: function (data) {
				var scriptTag = 'script',
					html = '',
					images = '',
					imagesData = '',
					prifileImage = '',
					followersCount = '',
					followers = '';

				data = JSON.parse( data.split( 'window._sharedData = ' )[1].split( ';</' + scriptTag + '>' )[0] ).entry_data.ProfilePage[0].graphql;

				if ( data.user ) {
					images = data.user.edge_owner_to_timeline_media.edges;
					images = images.slice( 0, parseInt( <?php echo esc_attr( $this->args['number_of_images'] ); ?> ) );

					jQuery.each( images, function( index, item ) {
						var $instagram = [];

						$instagram = {
							link: '//instagram.com/p/' + item.node.shortcode,
							time: item.node.taken_at_timestamp,
							thumbnail: item.node.thumbnail_resources[0]['src'],
							small: item.node.thumbnail_resources[2]['src'],
							large: item.node.thumbnail_resources[4]['src'],
							original: item.node.display_url,
						};

						imagesData = '<li class="elegant-instagram-profile-pic">';
						imagesData += '<img src="' + $instagram['large'] + '" title="Instagram image"/>';
						imagesData += '</li>';

						jQuery( '.instagram-images-<?php echo esc_attr( $this->args['username'] ); ?>').elegant_slick( 'elegant_slickAdd', imagesData );
					} );

					profileImage = '<img src="' + data.user.profile_pic_url + '" srcset="' + data.user.profile_pic_url + ' 1x, ' + data.user.profile_pic_url_hd + ' 2x" />';
					jQuery( '.instagram-user-pic-<?php echo esc_attr( $this->args['username'] ); ?>' ).html( profileImage );

					followersCount = data.user.edge_followed_by.count;
					followers = numberFormatter( followersCount ) + ' <?php echo __( 'Followers', 'elegant-elements' ); ?>';
					jQuery( '.instagram-user-followers-<?php echo esc_attr( $this->args['username'] ); ?>' ).html( followers );

					jQuery( document ).trigger( 'elegantImagesLoaded', [ '.instagram-images-<?php echo esc_attr( $this->args['username'] ); ?>' ] );
				}
			}
		} );
		</script>
		<?php
		$html .= ob_get_clean();

	}
} else {
	$user_data   = array();
	$media_array = array();

	$username = $api_data['username'];

	if ( ! empty( $insta_user ) ) {
		unset( $insta_array['user'] );

		// slice list down to required limit.
		$media_array = array_slice( $insta_array, 0, $this->args['number_of_images'] );

		$user_data = array(
			'biography'      => $insta_user['biography'],
			'profile_pic'    => $insta_user['profile_pic_url'],
			'profile_pic_hd' => $insta_user['profile_pic_url_hd'],
		);
	}

	$rand_id = wp_rand();

	$html  = '<div ' . FusionBuilder::attributes( 'elegant-instagram-profile-card' ) . '>';
	$html .= '<script type="text/javascript" class="elegant-slick-settings">
		var elegantSettings_insta' . $rand_id . ' = ' . wp_json_encode( $carousel_settings ) . ';
		jQuery( window ).on( "load", function() {
			var instaEmbed = jQuery( "body" ).find( ".elegant-instagram-embed" );
			if ( instaEmbed.length ) {
				var height = instaEmbed.parents( ".elegant-instagram-profile-card-slides" ).height(),
					width = instaEmbed.parents( ".elegant-instagram-profile-card-slides" ).width();
				instaEmbed.css( { "height": height, "width": width } );
			}
		} );
	</script>';
	$html .= '<ul class="elegant-instagram-profile-card-slides elegant-carousel elegant-slick" data-carousel-id="insta' . $rand_id . '">';

	// slice list down to required limit.
	$media_array = array_slice( $insta_array, 0, $this->args['number_of_images'] );
	foreach ( $media_array as $item ) {
		$type = $item['type'];
		$url  = $item['thumbnail'];

		if ( 'VIDEO' !== $type ) {
			$html .= '<li><img src="' . $url . '" alt="' . basename( substr( $url, 0, strrpos( $url, '?' ) ) ) . '" disable-lazyload /></li>';
		} else {
			$html .= '<li><iframe allowfullscreen="true" class="elegant-instagram-embed" src="' . $item['large'] . '"></iframe></li>';
		}
	}

	$html .= '</ul>';

	$instagram_profile_image = '';
	if ( isset( $settings['instagram_profile_image'] ) ) {
		$instagram_profile_image = $settings['instagram_profile_image'];
	} elseif ( isset( $user_data['profile_pic'] ) ) {
		$instagram_profile_image = $user_data['profile_pic'];
	}

	// User profile pic.
	$html .= '<div class="elegant-instagram-profile-card-pic">';
	$html .= '<img src="' . $instagram_profile_image . '" alt="' . basename( $instagram_profile_image ) . '" />';
	$html .= '</div>';

	// Profile username and follow button.
	$html .= '<div class="elegant-instagram-profile-card-info">';
	$html .= '<div class="elegant-instagram-profile-card-handle">';
	$html .= '<h3 class="elegant-instagram-profile-handle">@' . $username . '</h3>';

	$html .= '</div>';
	$html .= '<a ' . FusionBuilder::attributes( 'elegant-instagram-profile-card-follow-button' ) . ' href="//instagram.com/' . $username . '" target="_blank">' . __( 'Follow', 'elegant-elements' ) . '</a>';
	$html .= '</div>';

	$html .= '</div>';
}
