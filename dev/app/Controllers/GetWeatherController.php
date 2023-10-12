<?php

namespace App\Controllers;

use OpenSwoole\Http\Request;

class GetWeatherController {
    public function execute(Request $request): mixed {
        $city = $request->get['city'];

        if (!$city) {
            $parts = explode('/', $request->server['request_uri']);
            $city = $parts[array_key_last($parts)];
        }

        $weatherData = json_decode(file_get_contents("https://api.openweathermap.org/data/2.5/weather?q=$city&appid=ba006f6d23994bf75f8af2cc7602ce24"));

        return $weatherData;
    }
}