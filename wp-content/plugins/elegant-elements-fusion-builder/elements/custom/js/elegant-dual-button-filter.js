( function( $ ) {

	$( document ).ready(
		function() {

			// Dual button shortcode filter. Build dual button shortcode.
			FusionPageBuilderApp.elegantDualButtonShortcodeFilter = function( attributes, view ) {

				var shortcode = '',
					button_1,
					button_2;

				button_1 = FusionPageBuilderApp.base64Encode( attributes.params.button_1 );
				button_2 = FusionPageBuilderApp.base64Encode( attributes.params.button_2 );

				attributes.params.button_1 = button_1;
				attributes.params.button_2 = button_2;

				return attributes;

			};

		}
	);

}( jQuery ) );
