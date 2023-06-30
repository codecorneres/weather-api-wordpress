<?php
  function displayWeather() {

    $apiKey = get_option('weather_api_key');

    $location = get_option( 'weather_api_location' );
    $days = get_option( 'weather_api_days' );
    $temp_type = get_option( 'weather_api_temp_type' );
    $googleApiUrl = "https://api.weatherapi.com/v1/forecast.json?key=" . $apiKey . "&q=".urlencode($location)."&days=".$days."&aqi=yes&alerts=yes";

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $googleApiUrl,
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
//echo $response;


    $data = json_decode($response,true);
    
    
    $originalLastUpdateTime = $data['current']['last_updated'];
    $lastUpdateTime = date("d F Y", strtotime($originalLastUpdateTime));
    $cureent_temp = ($temp_type == 'temp_c') ? $data['current']['temp_c'].'°C' : $data['current']['temp_f'].'°F';

    $weatherHtml = "";
    $weatherHtml .= '<div class="weather-report-container">
                        <h2> Weather Status</h2>
                        <div class="weather-details">
                            <div class="weather">
                                <div class="current-weather-icon">
                                <img src="https:'.$data['current']['condition']['icon'].'" class="weather-icon" /> 
                                <span class="curent-temperature">'.$cureent_temp.'</span>
                                </div>
                                <span>'.$data['current']['condition']['text'].'</span>
                               
                            </div>
                            <div class="location"> <span>Location:</span>'.$data['location']['name'].','.$data['location']['region'].','.$data['location']['country'].'.</div>
                            <div class="time">
                                <div><span>Last Update Time:</span>'.$lastUpdateTime.' </div>
                                <div><span>Humidity:</span>'.$data['current']['humidity'].' %</div>
                                <div><span>Wind:</span>'.$data['current']    ['wind_kph'].' km/h</div>
                            </div>
                        </div>
                    </div>';
                if($days > 1 ):
    $weatherHtml .= '<div class="forecast-weather-container">
                        <h2>Forecast Days Status</h2>
                        <div class="weather-forcast-list">';
                        foreach ($data['forecast']['forecastday'] as $key => $fdayValue):

                            $forecast_max_temp = ($temp_type == 'temp_c') ? $fdayValue['day']['maxtemp_c'].'°C' : $fdayValue['day']['maxtemp_f'].'°F';
                            $forecast_min_temp = ($temp_type == 'temp_c') ? $fdayValue['day']['mintemp_c'].'°C' : $fdayValue['day']['mintemp_f'].'°F';

                            $originalDate = $fdayValue['date'];
                            $newDate = date("d F Y", strtotime($originalDate));

    $weatherHtml .=     '<div class="forecast-weather">
                            <div class="forecast-day-grid">
                                <div class="forecast-date">'.$newDate.'</div>
                                <div class="forecast-day-text">'.$fdayValue['day']['condition']['text'].'</div>
                                <div class="forecast-day-icon"><img src="https:'.$fdayValue['day']['condition']['icon'].'" class="forecast-weather-icon" /> </div>
                                <div class="forecast-day"> 
                                    <div class="max-temp"><span>Max:</span>'.$forecast_max_temp.'</div>
                                    <div class="min-temp"><span>Min:</span>'.$forecast_min_temp.'</div> 
                                </div>
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
