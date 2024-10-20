/* global acf */

( function() {

	'use strict';

	jQuery( document ).ready( function() {
		// Convert HTML editor to CodeMirror.
		var editorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {},
			cssEditorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {},
			jsEditorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {},
			htmlEditor = '',
			cssEditor = '',
			jsEditor = '';

		// Insert the template tag to the cursor position.
		function insertTagAtCursor( editor, text ) {
			var doc = editor.getDoc(),
				cursor = doc.getCursor();

			doc.replaceRange( text, cursor );
		}

		// Enable editor content tag.
		function editorContentTag() {
			var attributeContainer = jQuery( '.acf-field[data-name="element_html"] .elegant-element-creator-template-tags' ),
				templateTagItem = '<ul class="elegant-element-creator-content-tag"><li class="elegant-element-creator-template-tag" data-tag="wp_editor_content">';
				templateTagItem += '<a href="javascript:void(0);" style="color: #795548;" class="elegant-element-creator-template-tag-link" data-template-tag="wp_editor_content">{{wp_editor_content}}</a>';
				templateTagItem += '</li></ul>';

			if ( jQuery( '.acf-field[data-name="wp_editor_field"] input[type="checkbox"]' ).is( ':checked' ) ) {
				attributeContainer.prepend( templateTagItem );
			} else {
				jQuery( 'body' ).find( '.elegant-element-creator-content-tag' ).remove();
			}
		}

		// Handle the code editor.
		function elegantInitializeEditor( el ) {
			var tab = jQuery( el ).find( 'a' ).text();


			if ( 'HTML' === tab ) {
				if ( ! htmlEditor ) {
					htmlEditor = wp.codeEditor.initialize( jQuery( '#element-html-editor textarea' ), editorSettings );
				}
			}

			if ( 'CSS' === tab ) {
				if ( ! cssEditor ) {
					cssEditor = wp.codeEditor.initialize( jQuery( '#element-css-editor textarea' ), cssEditorSettings );
				}
			}

			if ( 'JavaScript' === tab ) {
				if ( ! jsEditor ) {
					jsEditor = wp.codeEditor.initialize( jQuery( '#element-js-editor textarea' ), jsEditorSettings );
				}
			}
		}

		// Handle Template Tag building.
		function elegantBuildTemplateTags() {
			var attributes = acf.getField( 'field_5ea49cc833267' ),
				attributeContainer = jQuery( '.template-tags-container' );

			// Reset the template tags list.
			attributeContainer.html( '' );

			// Loop through all the available attributes to build template tags.
			jQuery( attributes.$el ).find( 'div[data-name="param_name"]' ).each( function() {
				var templateTag = jQuery( this ).find( 'input' ).val();
				if ( '' !== templateTag ) {
					attributeContainer.each( function() {
						var templateTagItem = '<li class="elegant-element-creator-template-tag" data-tag="' + templateTag + '">';
							templateTagItem += '<a href="javascript:void(0);" class="elegant-element-creator-template-tag-link" data-template-tag="' + templateTag + '">{{' + templateTag + '}}</a>';
							templateTagItem += '</li>';

						jQuery( this ).append( templateTagItem );
					} );
				}
			} );
		}

		editorSettings.codemirror = _.extend(
			{},
			editorSettings.codemirror,
			{
				indentUnit: 4,
				tabSize: 2
			}
		);

		cssEditorSettings.codemirror = _.extend(
			{},
			cssEditorSettings.codemirror,
			{
				indentUnit: 4,
				tabSize: 2,
				mode: 'css',
				lint: false
			}
		);

		jsEditorSettings.codemirror = _.extend(
			{},
			jsEditorSettings.codemirror,
			{
				indentUnit: 4,
				tabSize: 1,
				mode: 'javascript'
			}
		);

		// Insert the selected tag to the cursor location.
		jQuery( 'body' ).on( 'click', '.elegant-element-creator-template-tag-link', function() {
			var templateTag = jQuery( this ).data( 'template-tag' ),
				activeTab = jQuery( '.acf-tab-group li.active a' ).text();

			templateTag = '{{' + templateTag + '}}';

			if ( 'HTML' === activeTab ) {
				insertTagAtCursor( htmlEditor.codemirror, templateTag );
			}

			if ( 'CSS' === activeTab ) {
				insertTagAtCursor( cssEditor.codemirror, templateTag );
			}

			if ( 'JavaScript' === activeTab ) {
				insertTagAtCursor( jsEditor.codemirror, templateTag );
			}
		} );

		// Initialize the active tab editor.
		elegantInitializeEditor( jQuery( 'body' ).find( '.acf-tab-group li.active' ) );

		// Initialize the editor after tab switch.
		jQuery( '.acf-tab-group li' ).click( function() {
			elegantInitializeEditor( this );
		} );

		// Update template tags on attribute param name change.
		jQuery( 'body' ).find( '.acf-field[data-name="param_name"] input' ).on( 'change blur', function() {
			elegantBuildTemplateTags();
		} );

		// Update template tags on new attribute.
		acf.addAction( 'append', function( $el ) {
			$el.find( '.acf-field[data-name="param_name"] input' ).on( 'change blur', function() {
				elegantBuildTemplateTags();
			} );
		} );

		// Update template tags on attribute removal.
		acf.addAction( 'remove', function( $el ) {
			setTimeout( function() {
				$el.find( '.acf-field[data-name="param_name"] input' ).trigger( 'change' );
				elegantBuildTemplateTags();
			}, 500 );
		} );

		// Update param_name field value on attribute heading change.
		jQuery( 'body' ).on( 'change blur', '.acf-field[data-name="heading"] input', function() {
			var heading = jQuery( this ).val(),
				paramName = jQuery( this ).closest( '.acf-fields' ).find( '.acf-field[data-name="param_name"] input' ),
				paramNameFromHeading = '';

			if ( '' === paramName.val() ) {
				paramNameFromHeading = heading.replace( /[^A-Z0-9]+/ig, '_' ).toLowerCase();
				paramName.val( paramNameFromHeading ).trigger( 'change' );
			}
		} );

		// Add content tag if WordPress editor field is enabled.
		editorContentTag();
		jQuery( 'body' ).on( 'change', '.acf-field[data-name="wp_editor_field"] input[type="checkbox"]', function() {
			editorContentTag();
		} );
	} );
}( jQuery ) );
