var GrillMeta = {

	init : function() {

/*
		jQuery( '.field.repeatable' ).each( function() {
			GrillMeta.isMaxFields( jQuery( this ) );
		} );
*/

		// Unbind & Re-bind all GrillMeta events to prevent duplicates.
		jQuery( document ).unbind( 'click.GrillMeta' );
		jQuery( document ).on( 'click.GrillMeta', '.grill-delete-field', GrillMeta.deleteField );
		jQuery( document ).on( 'click.GrillMeta', '.repeat-field', GrillMeta.repeatField );

		// Load tabs		
		jQuery(".grill-fields-section").hide();
		jQuery("ul.grill-tabs-nav").each(function() {
			$(this).find('li:first').addClass("active");
			$(this).nextAll('.grill-fields-section:first').show();
		});

		jQuery( document ).on( 'click.GrillMeta', '.grill-tabs-nav li', GrillMeta.tabs );
		
		// When toggling the display of the meta box container - reinitialize
		jQuery( document ).on( 'click.GrillMeta', '.postbox h3, .postbox .handlediv', GrillMeta.init );	

		// Load the color picker on the grill input box selector
	    $('.grill-select-color').wpColorPicker();
		
		GrillMeta.doneInit();

/*
jQuery( '.field.grill-sortable' ).each( function() {
	GrillMeta.sortableInit( jQuery( this ) );
} );
*/

	},

 	tabs : function( e ) {
	 	
	 	e.preventDefault();
	 	
	 	// Add an active class to the selected tab and remove others.
		var cTab = $(this).closest('li');
		cTab.siblings('li').removeClass("active");
		cTab.addClass("active");
		cTab.closest('ul.grill-tabs-nav').nextAll('.grill-fields-section').hide();
				
		// Find the data link value to identify the active tab + content.
		var activeTab = $(this).find('a').data("link");
		
		//Fade in the active ID content
		$('#'+activeTab).fadeIn();
		
		if ( $('#'+activeTab).find('.grill-gmap').length ) {
			var center = map.getCenter()
			google.maps.event.trigger(map, "resize")
			map.setCenter(center);
		}
    },
    
	repeatField : function( e ) {
		
		var templateField, newT, field, index, attr;

		field = $( this ).closest( '.grill-field' );
	
		e.preventDefault();
		jQuery( this ).blur();

		if ( GrillMeta.isMaxFields( field, 1 ) ) {
			return;
		}

		templateField = field.children( '.grill-repeatable-group' );

		newT = templateField.clone();
		newT.removeClass( 'hidden' );

		newT.insertBefore( templateField );

		newT.prop('class', 'grill-group');
		
		$("input,select,textarea", newT).each(function(){
			if ( $(this).attr('name') != undefined ) {
				$(this).attr('name', $(this).attr('name').replace('[x]', '['+newT.data('index')+']'));
			}
		});
		
		var cloneCount = parseInt( $(templateField).data('index') )+1;
		$(templateField).attr('data-index', cloneCount);

		$( newT ).find('input,textarea,select').filter(':visible:first').focus();

/*
	if ( field.hasClass( 'grill-sortable' ) ) {
	GrillMeta.sortableInit( field );
*/
	},

	deleteField : function( e ) {

		var fieldItem, field;

		e.preventDefault();
		jQuery( this ).blur();

		fieldItem = jQuery( this ).closest( '.grill-group' );

		if ( false !== fieldItem.data( 'confirm-delete' ) && ! confirm( GrillMetaData.strings.confirmDeleteField ) ) {
			return;
		}

		fieldItem = jQuery( this ).closest( '.grill-group' );

//		GrillMeta.isMaxFields( field, -1 );

		fieldItem.remove();
	},

	/**
	 * Prevent having more than the maximum number of repeatable fields.
	 * When called, if there is the maximum, disable .repeat-field button.
	 * Note: Information Passed using data-max attribute on the .field element.
	 *
	 * @param jQuery .field
	 * @param int modifier - adjust count by this ammount. 1 If adding a field, 0 if checking, -1 if removing a field... etc
	 * @return null
	 */
	isMaxFields: function( field, modifier ) {

		var count, addBtn, min, max, count;

		modifier = (modifier) ? parseInt( modifier, 10 ) : 0;

		addBtn = field.children( '.repeat-field' );
		count  = field.children( '.field-item' ).not( '.hidden' ).length + modifier; // Count after anticipated action (modifier)
		max    = field.attr( 'data-rep-max' );

		// Show all the remove field buttons.
		field.find( '> .field-item > .cmb-delete-field, > .field-item > .group > .cmb-delete-field' ).show();

		if ( typeof( max ) === 'undefined' ) {
			return false;
		}

		// Disable the add new field button?
		if ( count >= parseInt( max, 10 ) ) {
			addBtn.attr( 'disabled', 'disabled' );
		} else {
			addBtn.removeAttr( 'disabled' );
		}

		if ( count > parseInt( max, 10 ) ) {
			return true;
		}

	},

	/**
	 * Fire init callbacks.
	 * Called when GrillMeta has been set up.
	 */
	doneInit: function() {

		var _this = this,
			callbacks = GrillMeta._initCallbacks;

		if ( callbacks ) {
			for ( var a = 0; a < callbacks.length; a++) {
				callbacks[a]();
			}
		}

	},

	/**
	 * Simple debouncing function.
	 *
	 * @param func func function to run after wait period.
	 * @param wait int Wait interval.
	 * @param immediate bool Whether to run immediately or not.
	 * @returns {Function}
	 */
	debounce: function debounce( func, wait, immediate ) {
		var timeout;
		return function() {
			var context = this,
				args = arguments;
			var later = function() {
				timeout = null;
				if ( ! immediate ) {
					func.apply( context, args );
				}
			};
			var callNow = immediate && ! timeout;
			clearTimeout( timeout );
			timeout = setTimeout( later, wait );
			if ( callNow ) {
				func.apply( context, args );
			}
		};
	}

};

jQuery(document).ready(function ($) {	

	GrillMeta.init();

});