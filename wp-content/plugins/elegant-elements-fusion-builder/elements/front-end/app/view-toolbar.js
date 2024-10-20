/* global FusionEvents, elegantText */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {
		var elegantElementsToolbar = {};

		// Builder Toolbar
		FusionPageBuilder.ElegantElementsToolbar = window.wp.Backbone.View.extend( {

			/**
			 * Initialize the events.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			initialize: function() {
				this.listenTo( FusionEvents, 'all', this.render );
			},

			/**
			 * Renders the view.
			 *
			 * @since 2.0
			 * @return {Object} this
			 */
			render: function() {
				var templateIconSVG = '<svg xmlns="http://www.w3.org/2000/svg" height="512pt" viewBox="0 0 512 512" width="512pt" style="width: 20px;height: 20px;"><path d="m457.003906 0h-402.007812c-30.324219 0-54.996094 24.671875-54.996094 54.996094v402.007812c0 30.324219 24.671875 54.996094 54.996094 54.996094h402.007812c30.324219 0 54.996094-24.671875 54.996094-54.996094v-402.007812c0-30.324219-24.671875-54.996094-54.996094-54.996094zm-402.007812 30h402.007812c13.78125 0 24.996094 11.214844 24.996094 24.996094v66.003906h-452v-66.003906c0-13.78125 11.214844-24.996094 24.996094-24.996094zm402.007812 452h-402.007812c-13.78125 0-24.996094-11.214844-24.996094-24.996094v-306.003906h452v306.003906c0 13.78125-11.214844 24.996094-24.996094 24.996094zm0 0"></path><path d="m451 76c0 8.285156-6.714844 15-15 15s-15-6.714844-15-15 6.714844-15 15-15 15 6.714844 15 15zm0 0"></path><path d="m391 76c0 8.285156-6.714844 15-15 15s-15-6.714844-15-15 6.714844-15 15-15 15 6.714844 15 15zm0 0"></path><path d="m331 76c0 8.285156-6.714844 15-15 15s-15-6.714844-15-15 6.714844-15 15-15 15 6.714844 15 15zm0 0"></path><path d="m436 181h-360c-8.285156 0-15 6.714844-15 15v90c0 8.285156 6.714844 15 15 15h360c8.285156 0 15-6.714844 15-15v-90c0-8.285156-6.714844-15-15-15zm-15 90h-330v-60h330zm0 0"></path><path d="m226 331h-150c-8.285156 0-15 6.714844-15 15v90c0 8.285156 6.714844 15 15 15h150c8.285156 0 15-6.714844 15-15v-90c0-8.285156-6.714844-15-15-15zm-15 90h-120v-60h120zm0 0"></path><path d="m436 331h-150c-8.285156 0-15 6.714844-15 15v90c0 8.285156 6.714844 15 15 15h150c8.285156 0 15-6.714844 15-15v-90c0-8.285156-6.714844-15-15-15zm-15 90h-120v-60h120zm0 0"></path></svg>',
					templatesLi = '<li onClick="elegantToolbar( event );"><a href="#" class="fusion-builder-elegant-templates-dialog has-tooltip" aria-label="Elegant ' + elegantText.templates + '">' + templateIconSVG + '</a></li>';

				if ( ! jQuery( '.fusion-builder-elegant-templates-dialog' ).length ) {
					jQuery( templatesLi ).insertAfter( '.admin-tools.fb .open-library' );
				}

				setTimeout( function() {
					if ( ! jQuery( '.fusion-builder-elegant-templates-dialog' ).length ) {
						jQuery( templatesLi ).insertAfter( '.admin-tools.fb .open-library' );
					}
				}, 2020 );

				return this;
			}
		} );

		elegantElementsToolbar = new FusionPageBuilder.ElegantElementsToolbar();
		elegantElementsToolbar.render();

	} );
}( jQuery ) );
