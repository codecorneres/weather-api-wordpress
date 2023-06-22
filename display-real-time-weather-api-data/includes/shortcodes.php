<?php
  function displayWeather() {
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
    $apiKey = esc_attr(get_option('drt_weather_api_data_options'));
    return $apiKey;
  }
  add_shortcode('weathertoday', 'displayWeather');