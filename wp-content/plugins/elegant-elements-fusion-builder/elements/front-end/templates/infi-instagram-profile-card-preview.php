<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 2.5
 */

?>
<script type="text/html" id="tmpl-iee_instagram_profile_card-shortcode">
<?php
$settings = get_option( 'elegant_elements_settings', array() );
$api_data = get_option( 'elegant_elements_instagram_api_data', array() );
$username = ( isset( $api_data['username'] ) ) ? $api_data['username'] : '';
?>
<?php
$insta_array = elegant_scrape_instagram( $username, '_self', 9, 'large', false, false, false );
if ( is_array( $insta_array ) ) {
	?>
	<#
	var instagramFeed = <?php echo wp_json_encode( $insta_array ); ?>;
	#>
	<?php
}
?>
<#
username = '<?php echo $username; ?>';
profile_pic_url = '<?php echo $settings['instagram_profile_image']; ?>';
#>
<div {{{ _.fusionGetAttributes( attr ) }}}>
	<?php
	$carousel_settings = array(
		'dots'           => false,
		'arrows'         => false,
		'infinite'       => true,
		'speed'          => 500,
		'slidesToShow'   => 1,
		'slidesToScroll' => 1,
		'fade'           => true,
		'autoplay'       => true,
	);
	?>
	<div class="elegant-slick-settings" style="display:none;"><?php echo wp_json_encode( $carousel_settings ); ?></div>
	<ul class="instagram-images-{{{ username }}}">
		<#
		if ( instagramFeed ) {
			images = instagramFeed.slice( 0, parseInt( 1 ) );

			jQuery.each( images, function( index, item ) {
				var $instagram = item;
				#>
				<li class="elegant-instagram-teaser-pic">
				<img src="{{{ $instagram['small'] }}}" title="Instagram image"/>
				</li>
				<#
			} );
		}
		#>
	</ul>
	<div class="elegant-instagram-profile-card-pic instagram-user-pic-{{{ username }}}">
		<img src="{{{ profile_pic_url }}}" />
	</div>

	<div class="elegant-instagram-profile-card-info">
		<div class="elegant-instagram-profile-card-handle">
			<h3 class="elegant-instagram-profile-handle">@{{{ username }}}</h3>
			<span class="elegant-instagram-profile-follow-count instagram-user-followers-{{{ username }}}"></span>
		</div>
		<a {{{ _.fusionGetAttributes( buttonAttr ) }}} href="//instagram.com/{{{ username }}}" target="_blank"><?php echo __( 'Follow', 'elegant-elements' ); ?></a>
	</div>
</div>
</script>
