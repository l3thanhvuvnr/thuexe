<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 2.0
 */

?>
<script type="text/html" id="tmpl-iee_instagram_gallery-shortcode">
<?php
$insta_array = elegant_scrape_instagram( '', '_self', 9, 'large', false, false, false );
if ( is_array( $insta_array ) ) {
	?>
	<#
	var instagramFeed = <?php echo wp_json_encode( $insta_array ); ?>;
	#>
	<?php
}
?>
<div {{{ _.fusionGetAttributes( attr ) }}}>
	<#
	var user_id  = username.replace( '@', '' ).replace( '#', '' ).replace( '.', '' ),
		imagesData = '';
	#>
	<ul class="instagram-pics-with-js-{{{ cid }}} elegant-instagram-pics elegant-instagram-size-{{{ size }}}">
		Loading instagram gallery...
		<#
		if ( instagramFeed ) {
			images = instagramFeed.slice( 0, parseInt( limit ) );

			jQuery.each( images, function( index, item ) {
				var $instagram = [],
					comments = ( 'undefined' !== typeof item.node ) ? item.node.edge_media_to_comment.count : false,
					likes = ( 'undefined' !== typeof item.node ) ? item.node.edge_liked_by.count : false,
					likes_comments = '';

				$instagram = item;

				if ( 'no' !== show_likes && likes ) {
					likes_comments += '<span class="elegant-instagram-likes fa fa-heart"> ' + likes + '</span>';
				}

				if ( 'no' !== show_comments && comments ) {
					likes_comments += '<span class="elegant-instagram-comments fa fa-comment"> ' + comments + '</span>';
				}

				if ( 'lightbox' !== target ) {
					imagesData += '<li class="elegant-instagram-pic">';
					imagesData += '<div class="elegant-instagram-pic-wrapper">';
					imagesData += '<a class="elegant-instagram-pic-link hover-type-' + hover_type + '" href="' + $instagram.link + '" target="' + target + '">';
					imagesData += '<img src="' + $instagram[ size ] + '" title="Instagram image"/>';
					imagesData += '</a>';

					if ( '' !== likes_comments ) {
						imagesData += '<div class="elegant-instagram-pic-likes">';
						imagesData += likes_comments;
						imagesData += '</div>';
					}

					imagesData += '</div>';
					imagesData += '</li>';
				} else {
					var data_rel = 'iLightbox[gallery_image_' + user_id + ']';

					imagesData += '<li class="elegant-instagram-pic">';
					imagesData += '<div class="elegant-instagram-pic-wrapper">';
					imagesData += '<a href="' + $instagram.original + '" data-rel="' + data_rel + '" class="fusion-lightbox elegant-instagram-pic-link hover-type-' + hover_type + '">';
					imagesData += '<img src="' + $instagram[ size ] + '">';
					imagesData += '</a>';

					if ( '' !== likes_comments ) {
						imagesData += '<div class="elegant-instagram-pic-likes">';
						imagesData += likes_comments;
						imagesData += '</div>';
					}

					imagesData += '</div>';
					imagesData += '</li>';
				}
			} );

			setTimeout( function() {
				var thisModel = FusionPageBuilderElements.find( function( model ) {
					return model.get( 'cid' ) == cid;
				} );

				jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( '.instagram-pics-with-js-' + cid ).html( imagesData );
			}, 100 );
		}
		#>
	</ul>
</div>
</script>
