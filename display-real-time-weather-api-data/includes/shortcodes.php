<?php
  function displayWeather() {

    $apiKey = get_option('weather_api_key');

    $location = get_option( 'weather_api_location' );
    $days = get_option( 'weather_api_days' );
    $temp_type = get_option( 'weather_api_temp_type' );
    $googleApiUrl = "https://api.weatherapi.com/v1/forecast.json?key=" . $apiKey . "&q=".$location."&days=".$days."&aqi=yes&alerts=yes";

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.weatherapi.com/v1/forecast.json?key=1231e2e03e0f48cfb6375659232106&q=Rishikesh%2C%20Uttarakhand%2C%20India&days=5&aqi=yes&alerts=yes',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $data = json_decode($response,true);
    
    
    $lastUpdateTime = $data['current']['last_updated'];
    $cureent_temp = ($temp_type == 'temp_c') ? $data['current']['temp_c'].'°C' : $data['current']['temp_f'].'°F';

    $weatherHtml = "";
    $weatherHtml .= '<div class="weather-report-container">
                        <h2> Weather Status</h2>
                        <div class="weather">
                            <div class="current-weather-icon">
                            <img src="https:'.$data['current']['condition']['icon'].'" class="weather-icon" /> 
                            <span class="curent-temperature">'.$cureent_temp.'</span>
                            </div>
                            <span>'.$data['current']['condition']['text'].'</span>
                           
                        </div>
                        <div class="location"> Location: '.$data['location']['name'].','.$data['location']['region'].','.$data['location']['country'].'.</div>
                        <div class="time">
                            <div>Last Update Time: '.$lastUpdateTime.' </div>
                            <div>Humidity: '.$data['current']['humidity'].' %</div>
                            <div>Wind: '.$data['current']    ['wind_kph'].' km/h</div>
                        </div>
                    </div>';
                if($days > 1 ):
    $weatherHtml .= '<div class="forecast-weather-container">
                        <h2>Forecast Days Status</h2>';
                        foreach ($data['forecast']['forecastday'] as $key => $fdayValue):

                            $forecast_max_temp = ($temp_type == 'temp_c') ? $fdayValue['day']['maxtemp_c'].'°C' : $fdayValue['day']['maxtemp_f'].'°F';
                            $forecast_min_temp = ($temp_type == 'temp_c') ? $fdayValue['day']['mintemp_c'].'°C' : $fdayValue['day']['mintemp_f'].'°F';

    $weatherHtml .=     '<div class="forecast-weather">
                            <div class="forecast-day-grid">
                                <div class="forecast-date"> DATE : '.$fdayValue['date'].'</div>
                                <div class="forecast-day-text">'.$fdayValue['day']['condition']['text'].'</div>
                                <div class="forecast-day-icon"><img src="https:'.$fdayValue['day']['condition']['icon'].'" class="forecast-weather-icon" /> </div>
                                <div class="forecast-day"> 
                                <div class="max-temp">Max: '.$forecast_max_temp.'</div>
                                <div class="min-temp">Min: '.$forecast_min_temp.'</div> 
                                </div>
                            </div>';
                        endforeach;
                           
    $weatherHtml .=     '</div>
                    </div>';
                endif;
                    

    return $weatherHtml;
  }
  add_shortcode('weather_display', 'displayWeather');

?>
