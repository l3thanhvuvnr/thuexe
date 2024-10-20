<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 2.0
 */

?>
<script type="text/html" id="tmpl-iee_star_rating-shortcode">
<div {{{ _.fusionGetAttributes( attr ) }}}>
	<#
	var $empty_icon_start = false;
	for ( var $i = 0; $i < args.rating_scale; $i++ ) {
		var $args = {},
		$id = ( Math.random() * 3 ) + cid + $i,
		$icon = '',
		$color_offset = 0,
		$unfilled_color,
		$fill_icon_def,
		$icon_fill;

		if ( ( $i + 1 ) === parseInt( args.rating_scale ) ) {
			$args['last_icon'] = true;
		}

		if ( 1 > args.rating_value - $i ) {
			if ( 0 < args.rating_value - $i ) {
				$color_offset = ( args.rating_value - $i ) * 100;
			}
		}

		$unfilled_color = ( 'solid' !== args.unfilled_style ) ? 'transparent' : args.icon_unfill_color;
		$fill_icon_def  = '<defs><linearGradient id="fill-icon-' + $id + '"><stop offset="' + $color_offset + '%" stop-color="' + args.icon_fill_color + '"></stop><stop offset="' + $color_offset + '%" stop-color="' + $unfilled_color + '"></stop></linearGradient></defs>';

		$icon_fill = args.icon_fill_color;

		if ( $empty_icon_start ) {
			$icon_fill = $unfilled_color;
		}

		if ( 1 > args.rating_value - $i && ! $empty_icon_start ) {
			$icon_fill = 'url(#fill-icon-' + $id + ')';
		}

		if ( '1' === args.icon ) {
			$icon       = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="' + $icon_fill + '" d="M12 18.26l-7.053 3.948 1.575-7.928L.587 8.792l8.027-.952L12 .5l3.386 7.34 8.027.952-5.935 5.488 1.575 7.928z"/>' + $fill_icon_def + '</svg>';
			$empty_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="' + args.icon_unfill_color + '" d="M12 18.26l-7.053 3.948 1.575-7.928L.587 8.792l8.027-.952L12 .5l3.386 7.34 8.027.952-5.935 5.488 1.575 7.928L12 18.26zm0-2.292l4.247 2.377-.949-4.773 3.573-3.305-4.833-.573L12 5.275l-2.038 4.42-4.833.572 3.573 3.305-.949 4.773L12 15.968z"/></svg>';
		} else {
			$icon = '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="3 3 18 18" width="24px"><g><path fill="' + $icon_fill + '" d="M12,17.27l4.15,2.51c0.76,0.46,1.69-0.22,1.49-1.08l-1.1-4.72l3.67-3.18c0.67-0.58,0.31-1.68-0.57-1.75l-4.83-0.41 l-1.89-4.46c-0.34-0.81-1.5-0.81-1.84,0L9.19,8.63L4.36,9.04c-0.88,0.07-1.24,1.17-0.57,1.75l3.67,3.18l-1.1,4.72 c-0.2,0.86,0.73,1.54,1.49,1.08L12,17.27z"/></g>' + $fill_icon_def + '</svg>';
			$empty_icon = '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="3 3 18 18" width="24px" fill="' + args.icon_unfill_color + '"><path d="M19.65 9.04l-4.84-.42-1.89-4.45c-.34-.81-1.5-.81-1.84 0L9.19 8.63l-4.83.41c-.88.07-1.24 1.17-.57 1.75l3.67 3.18-1.1 4.72c-.2.86.73 1.54 1.49 1.08l4.15-2.5 4.15 2.51c.76.46 1.69-.22 1.49-1.08l-1.1-4.73 3.67-3.18c.67-.58.32-1.68-.56-1.75zM12 15.4l-3.76 2.27 1-4.28-3.32-2.88 4.38-.38L12 6.1l1.71 4.04 4.38.38-3.32 2.88 1 4.28L12 15.4z"/></svg>';
		}

		#>
		<i {{{ _.fusionGetAttributes( starAttr ) }}}>
			<#
			if ( ! $empty_icon_start ) {
				#>
				{{{ $icon }}}
				<#
			} else if ( 'solid' === args.unfilled_style ) {
				#>
				{{{ $icon }}}
				<#
			}

			if ( 1 > args.rating_value - $i ) {
				$empty_icon_start = true;
				if ( 'solid' !== args.unfilled_style ) {
					#>
					{{{ $empty_icon }}}
					<#
				}
			}
			#>
		</i>
	<#
	}
	#>
	<span itemprop="ratingValue" class="screen-reader-text">{{{ args.rating_value }}} / {{{ args.rating_scale }}}</span>
</div>
</script>
