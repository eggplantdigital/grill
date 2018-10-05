/*
 * Loads the custom metabox ColorPicker
 */
jQuery(document).ready(function ($) {	
 
    // Instantiates the variable that holds the media library frame.
    var meta_image_frame;
 
    // Runs when the choose image button is clicked.
    $('.grill-choose-image').click(function(e) {
			
        // Prevents the default action from occuring.
        e.preventDefault();

        // If the frame already exists, re-open it.
        if ( meta_image_frame ) {
            meta_image_frame.open();
            return;
        }
 
        // Sets up the media library frame
        meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
            title: meta_image.title,
            button: { text:  meta_image.button },
            library: { type: 'image' }
        });
        
        // Get the parent node.
        var parent  = $(this).parent();
 
        // Runs when an image is selected.
        meta_image_frame.on('select', function(){
 
            // Grabs the attachment selection and creates a JSON representation of the model.
            var media_attachment = meta_image_frame.state().get('selection').first().toJSON();
 
            // Sends the attachment ID to our custom image input field.
            $(parent).find( '.grill-image-upload-field' ).val( media_attachment.id );
            
            // Check if the placeholder is showing.
            if ( $(parent).find('.grill-image-display .placeholder').length > 0 ) {
	            
	            // Swap the placeholder for an image.
				$(parent).find('.grill-image-display .placeholder').removeClass('placeholder').addClass('grill-background-image-holder');

		        // Add the dashicon instead of the image.
		        $(parent).find('.grill-background-image-holder').html('<img src="'+media_attachment.url+'" id="preview-background-img" class="grill-background-image" />');
				
				// Show the close button.
				$(parent).find('.grill-image-remove').show();
				
            } else {
	            // Sends the attachment url to our custom image preview.
	            $(parent).find('.grill-background-image-holder img').attr('src', media_attachment.url);
			}
        });
 
        // Opens the media library frame.
        meta_image_frame.open();
    });
    
    // Runs when the remove image button is clicked.
    $('.grill-image-remove').click(function(e) {

        // Prevents the default action from occuring.
        e.preventDefault();
        
        // Get the parent node.
        var parent  = $(this).parent();
        
        // Change the holder class to 'placeholder'.
        $(parent).find('.grill-background-image-holder').addClass('placeholder').removeClass('grill-background-image-holder');
        
        // Add the dashicon instead of the image.
        $(parent).find('.placeholder').html('<span class="dashicons dashicons-format-image"></span>');
        
        // Clear the image input field.
        $(parent).parent().find('.grill-image-upload-field').val('');
        
        // Hide the close button.
        $(this).hide();
	});
	
	// Load the color picker on the grill input box selector
    $('.grill-select-color').wpColorPicker();

	/**
	 * Bind click event for .insert-post-type using event delegation
	 *
	 * @global wp.media.editor
	 */
	$('.insert-post-type').on("click", function(e) {
		e.preventDefault();
		
		options = {
			frame:    'post',
			state:    $(this).data('editor')
		};
		editor = $( e.currentTarget ).data('editor');
		
		wp.media.editor.open( editor, options );
	});
	
	/**
	 * Metabox Tabs
	 * Make the first tab selected when the page has loaded
	 */
	$(".grill-fields-section").hide();
		
	// Cycle through tab sets and select the first tab.
	$("ul.grill-tabs-nav").each(function() {
		$(this).find('li:first').addClass("active");
		$(this).nextAll('.grill-fields-section:first').show();
	});
 	
 	// Click the tab item.
    $( '.grill-tabs-nav li' ).click( function(e) {
		// Add an active class to the selected tab and remove others.
		var cTab = $(this).closest('li');
		cTab.siblings('li').removeClass("active");
		cTab.addClass("active");
		cTab.closest('ul.grill-tabs-nav').nextAll('.grill-fields-section').hide();
				
		// Find the data link value to identify the active tab + content.
		var activeTab = $(this).find('a').data("link");
		
		//Fade in the active ID content
		$('#'+activeTab).fadeIn();
		return false;
    });	
    
    $( '.grill-fa-modal .grill-fa a').on("click", function(e){
	    
		// Save current selected preview class
		var current_ico = $('.grill-fa-preview i.fa').attr('class');
		
		// Deselect and select new icon
		$('.grill-fa-modal .grill-fa.selected').removeClass('selected');
		$(this).parent().find('input').attr('checked', true);
		$(this).parent().addClass('selected'); 
		
		// Update Preview
		$('.grill-fa-preview i.fa').removeClass( current_ico );
		if ( $(this).data('id') != '' ) {
			$('.grill-fa-preview i').addClass('fa fa-'+ $(this).data('id')+' fa-2x');
		}
    });
    
    $( '#grill-change-category' ).change(function() {
		update_category( this.value );
	});
	
	function update_category( selected ) {
		$('.grill-fa-modal div.grill-fa[data-category*="' + selected + '"]').show();
		$('.grill-fa-modal div.grill-fa:not([data-category*="' + selected + '"])').hide();	
	}
	
    $( '.grill-fa-holder a.fa-clear').on("click", function(e){
		
		var current_ico = $('.grill-fa-preview i.fa').attr('class');
		
		$('.grill-fa-preview i.fa').removeClass( current_ico );
    
		$('.grill-fa-modal .grill-fa.selected').removeClass('selected');
		$(this).parent().find('input').attr('checked', false);
    });
    
    var regex = /^(.+?)(\d+)$/i;

    $( '.new_clone' ).on("click", function(e) {
	    
	    var cloneCount=$(this).data('index');
	    
	    e.preventDefault();
	    
	    var id = $(this).data('target');

//		var value = $(this).attr('value');
		
		$tmc = $( '#'+id )
			.clone()
 			.attr( 'id', id + cloneCount++)
			.insertAfter('[id^='+id+']:last')
			.each(function() {
	            var id = this.id || "";
	            var match = id.match(regex) || [];
	            if (match.length == 3) {
	                this.id = match[1] + (cloneCount);
	            }
	        });
			
		// Swap inputs
		$("input,select,textarea", $tmc).each(function(){
			var $name = $(this).attr('name');
			$(this).attr( 'name', $tmc.attr('id')+'[]['+$name+']');
		});
		
		$(this).data('index', cloneCount);

/*
		// Swap inputs
		$("input", $tmc).each(function(){
			var $name = $(this).attr('name');
			$(this).attr( 'name', $name+'['+value+']');
		});
*/
		
		//var text = $("label", $tmc).replace( "%s", "abrucadabra" );
		//$("label", $tmc).text(text);
		
/*
		console.log(  $clone );
		$( $($clone).prop('id') + ' label' ).each(function() {
// 		    var text = $(this).text();
		    text = text.replace("%s", "abrucadabra");
		    console.log(text);
// 		    $(this).text(text);
		});
*/

/*
// get the last DIV which ID starts with our main id.
var $div = $('div[id^="'+id+'"]:last');

console.log( $div.prop('id') );

// Read the Number from that DIV's ID (i.e: 3 from our main id)
// And increment that number by 1
if ( $('#'+$(this).attr('id')+'1').length ) {
	var num = parseInt( $div.prop("id").match(/\d+/g), 10 )+1;
} else {
	var num = 1;
}

// Clone it and assign the new ID (i.e: from num 4 to ID "4")
var $clone = $('#_clonable'+id).clone().prop('id', id+num );

//.attr('id', 'id'+ cloneCount++)

// Finally insert $clone wherever you want
$clone.insertAfter('[id^=id]:last').show();
*/

/*
		clone = $( id ).clone().insertAfter(this).show();
		
		$(clone).attr('id', 'change');
*/






/*
		$(clone+'label,'+clone+'p').each(function() {
		    var text = $(this).text();
		    text = text.replace("%s", "abrucadabra");
		    console.log(text);
		    $(this).text(text);
		});
*/
    });
    
    
    
});