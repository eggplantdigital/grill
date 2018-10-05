window.wp = window.wp || {};

(function($){

	var views = {},
	    instances = {},
	    media = wp.media;
	
	// Create the `wp.mce` object if necessary.
	wp.mce = wp.mce || {};
	
	// Grab the params for the shortcode
	var shortcode = window.tinyMCEPreInit.mceInit.content.grill_sc_params;
	
	post_types = _.extend( {}, {
		
		// Whether or not to display a loader.
		loader: false,
		
		// Set the media template to use
		// Loaded from Grill_Post_Type_Shortcodes
		template: media.template( 'editor-apps' ),
		
		// Open the editor
		edit: function() {
			options = {
				frame: 'post',
				state: 'iframe:'+shortcode.id
			};
			editor = $( this ).data('editor');
			wp.media.editor.open( editor, options );
		},
		
		// Assign the template to this shortcode
		getContent: function() {
	        var options = {
                title : shortcode.title,
                menu_icon : shortcode.menu_item
            };
			return this.template( options );
		}	
	} );	

	wp.mce.views.register( shortcode.id, _.extend( {}, post_types ) );

}(jQuery));