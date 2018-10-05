(function($) {
	$(document).ready(function() {
		/*
		Try targeting a specific div
		http://stackoverflow.com/questions/24968100/magnific-popup-ajax-parse-div
		*/
		jQuery('.popup').magnificPopup({
			type: 'ajax',
			//gallery: { enabled: true, arrowMarkup: '<a title="%title%" class="mfp-arrow mfp-arrow-%dir%"></a>',  },
	        ajax: {
                cursor: 'mfp-ajax-cur',
                tError: '<a href="%url%">The content</a> could not be loaded.'
            },
            callbacks: {
                parseAjax: function(mfpResponse) {

                    mfpResponse.data = $(mfpResponse.data).find('.hentry');
                    console.log('Ajax content loaded:', mfpResponse.data);
                }
            }
		});
		
		$('.grill_faq_wrapper h3').each(function() {
			var tis = $(this), state = false, answer = tis.parent().next('div').hide().css('height','auto').slideUp();
			tis.click(function(e) {
				e.preventDefault();
				state = !state;
				answer.slideToggle(state);
				tis.toggleClass('active',state);
			});
		});		
	});
})(jQuery);