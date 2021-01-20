<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Return weather
     *
     * @param  string  $city
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function weather($city)
    {

      $url = "http://api.openweathermap.org/data/2.5/weather";
      $icon_url = "http://openweathermap.org/img/wn/";
      $icon_ext = "@2x.png";

      $city = $city === "_default" ? "Bydgoszcz" : $city;

      $params = array(
        'q' => $city,
        'appid' => '4638f6a7eb112a87437ed087e94e1f40',
        'lang' => 'pl',
        'units' => 'metric'
      );

      $url .= '?' . http_build_query($params);

      $json = file_get_contents($url);

      $weather_obj = json_decode($json, true);

      // wind, clouds, rain, snow

      $atmo = [];

      if (array_key_exists('wind', $weather_obj)) {
          $atmo[] = [
            'name' => 'wiatr',
            'value' => $weather_obj['wind']['speed'],
            'unit' => 'm/s'
          ];
      }

      if (array_key_exists('clouds', $weather_obj)) {
          $atmo[] = [
            'name' => 'chmury',
            'value' => $weather_obj['clouds']['all'],
            'unit' => '%'
          ];
      }

      if (array_key_exists('rain', $weather_obj)) {
          $atmo[] = [
            'name' => 'deszcz',
            'value' => $weather_obj['rain']['1h'],
            'unit' => 'mm'
          ];
      }

      if (array_key_exists('snow', $weather_obj)) {
          $atmo[] = [
            'name' => 'śnieg',
            'value' => $weather_obj['snow']['1h'],
            'unit' => 'mm'
          ];
      }

      // temp, pressure, humidity

      $cond = [];

      $cond['temp'] = [
        'name' => 'temperatura',
        'value' => $weather_obj['main']['temp'],
        'unit' => '&deg;C'
      ];

      $cond['pressure'] = [
        'name' => 'ciśnienie',
        'value' => $weather_obj['main']['pressure'],
        'unit' => 'hPa'
      ];

      $cond['humidity'] = [
        'name' => 'wilgotność',
        'value' => $weather_obj['main']['humidity'],
        'unit' => '%'
      ];

      $weather_selected_obj = [
          'city' => $weather_obj['name'],
          'description' => $weather_obj['weather'][0]['description'],
          'icon' => $icon_url . $weather_obj['weather'][0]['icon'] . $icon_ext,
          'cond' => $cond,
          'atmo' => $atmo
      ];

      return response()->json($weather_selected_obj);
    }

    /**
     * Return list of cities
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cities()
    {
      $cities = [
        'Bydgoszcz',
        'Toruń',
        'Katowice',
        'Warszawa',
        'Lublin',
        'Londyn',
        'Moskwa',
        'Toronto',
        'Madryt',
        'Bruksela'
      ];

      return response()->json($cities);
    }
}
