<?php
//https://gist.github.com/bjornbjorn/7428321
?>
<input type="text" id="search_location" name="search_location" class="grill-field-gmap_search" placeholder="Search Location" />
<a href="#" id="grill_gmaps_search" class="button button-primary grill-field-gmap_search-button"><?php _e('Search', 'grill-guides'); ?></a>
<div style="width:100%;height:250px" id="grill-field-gmail_map" class="grill-field-gmail_map"></div>

<script>
$(document).ready(function(e) {
// init map
function initMap(lat, long) {

	//Default to London
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
		$('#<?php echo $this->args['options']['latitude']; ?>').val(lat);
		$('#<?php echo $this->args['options']['longitude']; ?>').val(long);
	});		
}
/**
 * Geocode when user location input changes
 */
$('body').on('click', '.grill-field-gmap_search-button', function(e) {
	e.preventDefault();
	var address = $('.grill-field-gmap_search').val();
	var geocoder = new google.maps.Geocoder();
	if (geocoder) {
		geocoder.geocode({ 'address': address }, function (results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				console.log(results[0].geometry.location);
				var lat = results[0].geometry.location.lat();
				var long = results[0].geometry.location.lng();
				console.log("lat="+lat);
				
				initMap(lat, long);
				$('#<?php echo $this->args['options']['latitude']; ?>').val(lat);
				$('#<?php echo $this->args['options']['longitude']; ?>').val(long);
			}
			else {
				alert("<?php _e('Could not find this address, please write one nearby and drag the pin on the map as close as possible to the correct position.', 'grill-guides'); ?>");
				$('.register-form__latitude-holder').focus().select();
			}
		});
	}
});
var lat  = $('#<?php echo $this->args['options']['latitude']; ?>').val();
var long = $('#<?php echo $this->args['options']['longitude']; ?>').val();
initMap(lat, long);
});			
</script>