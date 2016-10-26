// This example adds a search box to a map, using the Google Place Autocomplete
// feature. People can enter geographical searches. The search box will return a
// pick list containing a mix of places and predicted search terms.

// This example requires the Places library. Include the libraries=places
// parameter when you first load the API. For example:
// <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
var markers = [];

function initAutocomplete() {
  jQuery("input[type='submit']").on('click', function(e){
    // e.preventDefault();
  });
  var amenities = JSON.parse(AMENITIES['data']);
  var primary_location;
  
  if (jQuery("#am_address").val()) {
    // Get place info 
    var latitude = parseFloat(jQuery("#am_lat").val());
    var longitude = parseFloat(jQuery("#am_lon").val());
    var address = jQuery("#am_address").val();

    var map = new google.maps.Map(document.getElementById('am_admin_map'), {
      center: {lat: latitude, lng: longitude},
      zoom: 13,
      mapTypeId: 'roadmap'
    });

    // Create a marker for each place.
    markers.push(new google.maps.Marker({
      map: map,
      title: address,
      position: {lat: latitude, lng: longitude},
    }));

  }
  else {
    var latitude = parseFloat( ( amenities[1] == null ? "43.6532" : amenities[1] ) );
    var longitude = parseFloat( ( amenities[2] == null ? "-79.3832" : amenities[2] ) );
    
    var map = new google.maps.Map(document.getElementById('am_admin_map'), {
      center: {lat: latitude, lng: longitude},
      zoom: 13,
      mapTypeId: 'roadmap'
    });
  }
  

  // Create the search box and link it to the UI element.
  var input = document.getElementById('pac-input');
  var searchBox = new google.maps.places.SearchBox(input);
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  // Bias the SearchBox results towards current map's viewport.
  map.addListener('bounds_changed', function() {
    searchBox.setBounds(map.getBounds());
  });

  // var markers = [];
  // Listen for the event fired when the user selects a prediction and retrieve
  // more details for that place.
  searchBox.addListener('places_changed', function() {
    var places = searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }

    // Clear out the old markers.
    markers.forEach(function(marker) {
      marker.setMap(null);
    });
    markers = [];

    // For each place, get the icon, name and location.
    var bounds = new google.maps.LatLngBounds();
    places.forEach(function(place) {
      console.log(place);
      if (!place.geometry) {
        console.log("Returned place contains no geometry");
        return;
      }
      var icon = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25)
      };

      // Create a marker for each place.
      markers.push(new google.maps.Marker({
        map: map,
        icon: icon,
        title: place.name,
        position: place.geometry.location
      }));

      if (place.geometry.viewport) {
        // Only geocodes have viewport.
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }

      // update values
      var address = place.name;
      console.log(place.geometry.location);
      var location = place.geometry.location;
      var latitude = location.lat;
      var longitude = location.lng;
      var place_id = place.place_id;
      
      jQuery('#am_lat').val(latitude);
      jQuery("#am_lon").val(longitude);
      jQuery("#am_place_id").val(place_id);
      jQuery("#am_address").val(address);
    });
    map.fitBounds(bounds);
  });
}

