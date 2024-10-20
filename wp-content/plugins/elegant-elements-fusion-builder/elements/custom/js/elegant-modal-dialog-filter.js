( function( $ ) {

	$( document ).ready(
		function() {

			// Modal dialog shortcode filter. Build modal dialog shortcode.
			FusionPageBuilderApp.elegantModalDialogShortcodeFilter = function( attributes, view ) {

				var shortcode = '',
					button_shortcode,
					icon_shortcode;

				button_shortcode = attributes.params.button_shortcode;
				icon_shortcode   = attributes.params.icon_shortcode;

				attributes.params.button_shortcode = button_shortcode;
				attributes.params.icon_shortcode   = icon_shortcode;

				return attributes;

			};

		}
	);

}( jQuery ) );
