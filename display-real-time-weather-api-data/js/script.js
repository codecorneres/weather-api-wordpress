mapboxgl.accessToken = 'pk.eyJ1Ijoic3VyYWprMTIzIiwiYSI6ImNsamNjajMxdzBuc2szdWtpcjBvNHp4Y3gifQ.hJXhyLQ8KdIHKZ8TWz6cHQ';

  //console.log(mapboxgl.accessToken);
  const map = new mapboxgl.Map({
      container: 'map', // container ID
      style: 'mapbox://styles/mapbox/streets-v12', // style URL
      center: [-74.5, 40], // starting position [lng, lat]
      zoom: 9 // starting zoom
      });

      const locationInput = document.getElementById('data_setting_location');

      //console.log(locationInput);

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