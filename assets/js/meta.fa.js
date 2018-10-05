jQuery( document ).ready( function() {
	
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
	
});