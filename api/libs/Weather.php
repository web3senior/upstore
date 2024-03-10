<?php

/**
 * Current weather and forecasts in your city
 * Documentation: https://openweathermap.org/weather-conditions
 * @author Amir
 */
class Weather
{

    private $url = 'http://api.openweathermap.org/data/2.5/';
    private $method = 'weather';
    private $city_id = 143083;
    private $app_id = 'e259e40ebf372f40ccced0fe3753899c';

    function __construct() {
        
    }

    /**
     * weather
     * http://api.openweathermap.org/data/2.5/weather?id=143083&appid=e259e40ebf372f40ccced0fe3753899c 
     * @return json
     */
    public function weather() {
        $data = array(
            'id' => $this->city_id,
            'appid' => $this->app_id
        );
        $url = $this->url . $this->method . '?' . http_build_query($data);
        $client = curl_init($url);
        curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($client);
        $result = json_decode($response);
        if (!empty($result)) {
            $weather_cod = $result->cod;
            $cod_desc = "نامشخص";
            switch ($weather_cod) {
                case 200: //Thunderstorm 	thunderstorm with light rain 	11d
                    $cod_desc = "رعدوبرق با باران کم";
                    break;
                case 201://Thunderstorm 	thunderstorm with rain 	11d
                    $cod_desc = "رعدوبرق با باران";
                    break;
                case 202://Thunderstorm 	thunderstorm with heavy rain 	11d
                    $cod_desc = "رعدوبرق با باران شدید";
                    break;
                case 210://Thunderstorm 	light thunderstorm 	11d
                    $cod_desc = "رعدوبرق کم";
                    break;
                case 211://Thunderstorm 	thunderstorm 	11d
                    $cod_desc = "رعدوبرق";
                    break;
                case 212://Thunderstorm 	heavy thunderstorm 	11d
                    $cod_desc = "رعدوبرق شدید";
                    break;
                case 221://Thunderstorm 	ragged thunderstorm 	11d
                    $cod_desc = "رعدوبرق اندک";
                    break;
                case 230://Thunderstorm 	thunderstorm with light drizzle 	11d
                    $cod_desc = "رعدوبرق بانم نم باران";
                    break;
                case 231://Thunderstorm 	thunderstorm with drizzle 	11d
                    $cod_desc = "رعدوبرق با باران نم";
                    break;
                case 232://Thunderstorm 	thunderstorm with heavy drizzle 	11d 
                    $cod_desc = "رعدوبرق با باران سنگین";
                    break;
                default:
                    break;
            }
			$cod_desc='-';
            $weather_icon = $result->weather[0]->icon;
            $icon_name = 'lni lni-drop';
            switch ($weather_icon) {
                case '01d': //clear sky
                    $icon_name = array(0 => "lni", 1 => "fa-sun");
                    break;
                case '02d': //few clouds 
                    $icon_name = array(0 => "lni", 1 => "fa-clouds-sun");
                    break;
                case '03d': //scattered clouds 
                    $icon_name = array(0 => "lni", 1 => "fa-cloud");
                    break;
                case '04d': //broken clouds 
                    $icon_name = array(0 => "lni", 1 => "fa-clouds");
                    break;
                case '09d': //shower rain 
                    $icon_name = array(0 => "lni", 1 => "lni-cloudy-sun");
                    break;
                case '10d': //rain 
                    $icon_name = array(0 => "lni", 1 => "fa-cloud-sun-rain");
                    break;
                case '11d': //thunderstorm
                    $icon_name = array(0 => "lni", 1 => "fa-poo-storm");
                    break;
                case '13d': //snow
                    $icon_name = array(0 => "lni", 1 => "fa-cloud-snow");
                    break;
                case '50d': //mist
                    $icon_name = array(0 => "lni", 1 => "fa-wind");
                    break;
                case '01n': //clear sky
                    $icon_name = array(0 => "lni", 1 => "lni-night");
                    break;
                case '02n': //few clouds 
                    $icon_name = array(0 => "lni", 1 => "fa-cloud-moon");
                    break;
                case '03n': //scattered clouds 
                    $icon_name = array(0 => "lni", 1 => "fa-cloud");
                    break;
                case '04n': //broken clouds 
                    $icon_name = array(0 => "lni", 1 => "fa-clouds");
                    break;
                case '09n': //shower rain 
                    $icon_name = array(0 => "lni", 1 => "fa-cloud-rain");
                    break;
                case '10n': //rain 
                    $icon_name = array(0 => "lni", 1 => "fa-cloud-moon-rain");
                    break;
                case '11n': //thunderstorm
                    $icon_name = array(0 => "lni", 1 => "fa-poo-storm");
                    break;
                case '13n': //snow
                    $icon_name = array(0 => "lni", 1 => "fa-cloud-snow");
                    break;
                case '50n': //mist
                    $icon_name = array(0 => "lni", 1 => "fa-wind");
                    break;
                default:
                    break;
            }
            return array(
                'main' => $result->weather[0]->main,
                'icon' => $icon_name,
                'name' => $result->name,
                'humidity' => $result->main->humidity,
                'wind_speed' => $result->wind->speed,
                'clouds_all' => $result->clouds->all,
                'cod_desc' => $cod_desc
            );
        } else {
            return '<b>error</br>';
        }
    }

}
