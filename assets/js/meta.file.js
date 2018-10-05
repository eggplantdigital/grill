jQuery( document ).ready( function() {
	
	jQuery( document ).on( 'click', '.grill-file-upload', function(e) {
		
		e.preventDefault();

		var link = jQuery( this );
		var container = jQuery( this ).parent();
		
		var frameArgs = {
			multiple: false,
			title: 'Select File',
		};

		/*
		library = container.attr( 'data-type' ).split( ',' );
		if ( library.length > 0 ) {
			frameArgs.library = {type: library};
		}
		*/

		var Grill_Frame = wp.media( frameArgs );
			
		Grill_Frame.on( 'select', function() {

			var selection = Grill_Frame.state().get( 'selection' ),
				model = selection.first(),
				fileHolder = container.find( '.grill-file-holder' );

			jQuery( container ).find( '.grill-file-upload-input' ).val( model.id );

			link.hide(); // Hide 'add media' button

			Grill_Frame.close();
			
			fileHolder.parent().addClass('grill-file-preview');
			fileHolder.html( '' );
			fileHolder.show();
			fileHolder.siblings( '.grill-remove-file' ).show();

			var fieldType = 'TODO'/* container.closest( '.field-item' ).attr( 'data-class' ) */;

			jQuery( '<img />', { src: model.attributes.icon } ).prependTo( fileHolder );
			fileHolder.append( jQuery( '<div class="filename" />' ).html( '<strong>' + model.attributes.filename + '</strong>' ) );

		});

		Grill_Frame.open();
		
	});	

	jQuery( document ).on( 'click', '.grill-remove-file', function(e) {

		e.preventDefault();

		var container = jQuery( this ).parent().parent();

		container.find( '.grill-file-holder' ).html( '' ).hide();
		container.find( '.grill-file-upload-input' ).val( '' );
		container.find( '.grill-file-upload' ).show().css( 'display', 'inline-block' );
		container.find( '.grill-remove-file' ).hide();
		container.find( '.grill-file-preview' ).removeClass('grill-file-preview');	
	} );
});