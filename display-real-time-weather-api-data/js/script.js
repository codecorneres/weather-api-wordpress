var input = document.getElementById('data_setting_location');

// Create a new Awesomplete instance
var awesomplete = new Awesomplete(input, {
  minChars: 2,
  maxItems: 5,
  autoFirst: true
});

// Add an event listener to fetch location suggestions on input
input.addEventListener('input', function() {
  var term = input.value;
  //console.log(term);

  var accessToken ='sk.eyJ1Ijoic3VyYWprMTIzIiwiYSI6ImNsamlhNDlsZDAwd3IzZWxhdG40bGg2amcifQ.-cb9oSC210B5q-DfQFAxkQ';
  var geocodingUrl ='https://api.mapbox.com/geocoding/v5/mapbox.places/';
  var requestUrl = geocodingUrl + encodeURIComponent(term) + '.json?access_token=' + accessToken;

  // Make an AJAX request to fetch the location suggestions
  var xhr = new XMLHttpRequest();
  xhr.open('GET', requestUrl, true);
  xhr.onload = function() {
    if (xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);
      var locations = response.features.map(function(feature) {
        return feature.place_name;
      });
      awesomplete.list = locations;
    } else {
      console.error('Request failed. Status: ' + xhr.status);
    }
  };
  xhr.send();
});

// Initialize the Mapbox GL Geocoder control
var geocoder = new MapboxGeocoder({
  accessToken: mapboxgl.accessToken,
  mapboxgl: mapboxgl
});

// // Attach the geocoder control to the input field
document.getElementById('data_setting_location').appendChild(geocoder.onAdd(map));
