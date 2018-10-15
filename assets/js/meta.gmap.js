$(document).ready(function(e) {
	
	/* init map */
	function initMap(lat, long) {
	
		/* Default to London */
		if (lat=='')
			lat='51.5073509';
		if (long=='')
			long = '-0.12775829999998223';
	
		var center = new google.maps.LatLng(parseFloat(lat), long);
		
		var mapOptions = {center: center, zoom: 16, scrollwheel: false};
		map    = new google.maps.Map(document.getElementById("grill-field-gmail_map"), mapOptions);
		marker = new google.maps.Marker({ position: new google.maps.LatLng(lat, long), draggable:true, map: map });
		google.maps.event.addListener(marker, 'dragend', function (event) {
			var lat = this.getPosition().lat();
			var long = this.getPosition().lng();
			initMap(lat, long);
			$('#'+grill_gmap.latitude).val(lat);
			$('#'+grill_gmap.longitude).val(long);
		});		
	}
	
	/* Geocode when user location input changes */
	$('body').on('click', '.grill-field-gmap_search-button', function(e) {
		e.preventDefault();
		var address = $('.grill-field-gmap_search').val();
		var geocoder = new google.maps.Geocoder();
		if (geocoder) {
			geocoder.geocode({ 'address': address }, function (results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					//console.log(results[0].geometry.location);
					var lat = results[0].geometry.location.lat();
					var long = results[0].geometry.location.lng();
					//console.log("lat="+lat);
					initMap(lat, long);
					$('#'+grill_gmap.latitude).val(lat);
					$('#'+grill_gmap.longitude).val(long);
				}
				else {
					alert( grill_gmap.map_error );
					$('.register-form__latitude-holder').focus().select();
				}
			});
		}
	});	
	var lat  = $('#'+grill_gmap.latitude).val();
	var long = $('#'+grill_gmap.longitude).val();
	initMap(lat, long);
});			
