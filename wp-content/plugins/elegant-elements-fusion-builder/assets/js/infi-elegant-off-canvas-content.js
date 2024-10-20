( function() {

	'use strict';

	jQuery( document ).ready( function() {
		var canvasTrigger = jQuery( '.elegant-off-canvas-trigger' ),
			animationStyle = '',
			position = '',
			canvasID = '',
			canvasContentWidth = '',
			canvasContentHeight = '';

		canvasTrigger.on( 'click', function( e ) {
			e.preventDefault();

			canvasID            = jQuery( this ).find( '[data-target]' ).data( 'target' ).replace( '.', '' );
			canvasContentWidth  = jQuery( '#' + canvasID ).outerWidth();
			canvasContentHeight = jQuery( '#' + canvasID ).outerHeight();
			animationStyle      = jQuery( '[data-target="' + canvasID + '"]' ).parents( '.elegant-off-canvas-content' ).data( 'animation' );
			position            = jQuery( '[data-target="' + canvasID + '"]' ).parents( '.elegant-off-canvas-content' ).data( 'position' );

			if ( 'slide' !== animationStyle ) {
				if ( 'left' === position ) {
					jQuery( '#wrapper' ).css( 'transform', 'translate3d(' + canvasContentWidth + 'px, 0, 0)' );
				}
				if ( 'right' === position ) {
					jQuery( '#wrapper' ).css( 'transform', 'translate3d(-' + canvasContentWidth + 'px, 0, 0)' );
				}
				if ( 'top' === position ) {
					jQuery( '#wrapper' ).css( 'transform', 'translate3d(0,' + canvasContentHeight + 'px, 0)' );
				}
				if ( 'bottom' === position ) {
					jQuery( '#wrapper' ).css( 'transform', 'translate3d(0,-' + canvasContentHeight + 'px, 0)' );
				}
			}

			jQuery( '#' + canvasID ).toggleClass( 'off-canvas-display' );
			jQuery( 'html' ).toggleClass( 'off-canvas-displaying' );
		} );

		jQuery( '.elegant-off-canvas-overlay, .elegant-off-content-close' ).on( 'click', function( e ) {
			e.preventDefault();
			jQuery( '.elegant-off-canvas-content-wrapper' ).removeClass( 'off-canvas-display' );
			jQuery( 'html' ).removeClass( 'off-canvas-displaying' );
			jQuery( '#wrapper' ).removeAttr( 'style' );
		} );
	} );
}( jQuery ) );
