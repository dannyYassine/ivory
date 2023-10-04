<?php

namespace Dev\Controllers;

use OpenSwoole\Http\Request;

class GetWeatherController {
    public function execute(Request $request): mixed {
        $city = $request->get['city'];

        $weatherData = json_decode(file_get_contents("https://api.openweathermap.org/data/2.5/weather?q=$city&appid=ba006f6d23994bf75f8af2cc7602ce24"));

        return $weatherData;
    }
}