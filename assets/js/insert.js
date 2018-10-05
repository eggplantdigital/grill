jQuery(document).ready(function ($) {	
	/**
	 * Bind click event for .insert-post-type using event delegation
	 *
	 * @global wp.media.editor
	 */
	$('.insert-post-type').live("click", function(e) {
		e.preventDefault();
		
		options = {
			frame:    'post',
			state:    $(this).data('tab-id')
		};
		editor = $( e.currentTarget ).data('editor');
		
		wp.media.editor.open( editor, options );
	});
});