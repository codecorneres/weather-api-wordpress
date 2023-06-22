<?php
  function displayWeather() {

    $apiKey = get_option('weather_api_key');
    $location = get_option( 'weather_api_location' );
    $days = get_option( 'weather_api_days' );
    $googleApiUrl = "https://api.weatherapi.com/v1/forecast.json?key=" . $apiKey . "&q=".$location."&days=".$days."&aqi=yes&alerts=yes";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);

    curl_close($ch);
    $data = json_decode($response);
    $currentTime = time();

    $weatherHtml = "";
    $weatherHtml .= '<div class="report-container">
        <h2> Weather Status</h2>
        <div class="weather-forecast">
            <img
                src="https://openweathermap.org/img/w/ echo $data->weather[0]->icon; .png"
                class="weather-icon" />  echo $data->main->temp_max; °C<span
                class="min-temperature"> echo $data->main->temp_min; °C</span>
        </div>
        <div class="time">
            <div>Humidity:= echo $data->main->humidity; %</div>
            <div>Wind:= echo $data->wind->speed; km/h</div>
        </div>
    </div>';
    $key = get_option('weather_api_key');
    return $response;
  }
  add_shortcode('weathertoday', 'displayWeather');