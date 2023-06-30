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

  var accessToken ='pk.eyJ1Ijoic3VyYWprMTIzIiwiYSI6ImNsamk4eWF1czBxMDkzZ29lbml5bmh0NjcifQ.x25Lqctp4l23_IhTOO8deQ';
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

mapboxgl.accessToken = 'pk.eyJ1Ijoic3VyYWprMTIzIiwiYSI6ImNsamk4eWF1czBxMDkzZ29lbml5bmh0NjcifQ.x25Lqctp4l23_IhTOO8deQ';
// Initialize the Mapbox GL Geocoder control
// var geocoder = new MapboxGeocoder({
//   accessToken: mapboxgl.accessToken,
//   mapboxgl: mapboxgl
// });

 var map = new mapboxgl.Map({
                      container: 'map', // container ID
                      style: 'mapbox://styles/mapbox/streets-v11', // style URL
                      center: [-74.5, 40], // starting position [lng, lat]
                      zoom: 9 // starting zoom
                    });
  var geocoder = new MapboxGeocoder({
                      accessToken: mapboxgl.accessToken,
                      mapboxgl: mapboxgl,
                      marker: false // Disable marker on the map
                  });


    const locationInput = document.getElementById('data_setting_location');

    // // Attach the geocoder control to the input field
    locationInput.appendChild(geocoder.onAdd(map));

     locationInput.addEventListener('input', updateMap);

      function updateMap() {
          const location = locationInput.value;

          // Perform geocoding request to convert location to coordinates
          fetch('https://api.mapbox.com/geocoding/v5/mapbox.places/'+location+'.json?access_token='+mapboxgl.accessToken)
            .then(response => response.json())
            .then(data => {
              const coordinates = data.features[0].center;

              // Update the map center and fly to the new location
              map.flyTo({
                center: coordinates,
                zoom: 9
              });
          })
          .catch(error => {
              console.error('Error:', error);
          });
      }

