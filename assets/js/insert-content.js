jQuery( document ).ready( function( $ ) {
	
	$('.view-select').live("click", function(e) {
		e.preventDefault();
		
		if ($(this).hasClass('details selected')) 
		{	
			$(this).removeClass('details selected');
			$('#view_id').val('');
			$('.media-uploader-status').hide();
			$('.media-button-select').prop('disabled', 'disabled');
		} else 
		{	
			$(this).parent().find('li.view-select.details.selected').removeClass('details selected');
			$(this).addClass('details selected');
			$('#view_id').val($(this).data('id'));
			
			$('.media-uploader-status').show();
			$('.media-button-select').prop('disabled', '');
			
			$('.attachment-display-settings').addClass('hidden');
			$('*[data-views*="'+$(this).data('id')+'"]').removeClass('hidden');
		}
	});
		
	/**
	 * Ajax to send the shortcode to the editor 
	 *
	 * @since 1.5.0
	 */
	$('#grill_form_create_shortcode').submit(function(){

		// All the hidden form elements should be disabled so they are not submitted
		var $disabled_list = $(this).find('.hidden :input').attr('disabled', 'disabled');
		
		// Serialize the remaining data
		var data = $(this).serialize();
		
		// Undisable the hidden form elements, so they might be used again if needed
		$disabled_list.prop('disabled','');

		// Ajax call to process the form data	
		$.ajax({
	        url:        ajaxurl,
	        type:       'POST',
	        dataType:	'json',
	        data:       { 
	        	"action": "grill_create_shortcode", 
	        	/* "nonce": nonce, */ 
	        	"data": data 
	        },
	        beforeSend: function(){
	            //console.log('sending...');
	        },
	        success: function(obj) {
				
				if ( obj.response=='success' )
				{
					var win = window.dialogArguments || opener || parent || top;
					win.send_to_editor(obj.shortcode);
				}
			},
			error: function() {
				//console.log('An error has occured');
			},
			complete: function() {
				//console.log('Complete!');
			}
		});
		return false;
	});
	
});