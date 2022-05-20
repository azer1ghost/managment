<?php

namespace App\Services;

class OpenWeatherApi
{
    private string $key;
    private string $url;
    private string $lat;
    private string $lon;

    public function __construct()
    {
        $this->key = config('services.open_weather.key', 'key');
        $this->url = config('services.open_weather.url', 'key');
    }

    public function location(float $lat, float $lon)
    {
        $this->lat = $lat;
        $this->lon = $lon;

        return $this;
    }

    public function send()
    {
        $response_en = \Http::get($this->url, [
            'lat' => $this->lat,
            'lon' => $this->lon,
            'appid' => $this->key,
            'units' => 'metric',
            'lang' => 'en'
        ]);

        $response_az = \Http::get($this->url, [
            'lat' => $this->lat,
            'lon' => $this->lon,
            'appid' => $this->key,
            'units' => 'metric',
            'lang' => 'az'
        ]);
        $response_az = $response_az->json();
        $response_en = $response_en->json();

        return [
            'description' => [
                'az' => $response_az['weather'][0]['description'],
                'en' => $response_en['weather'][0]['description']
            ],
            'icon' => "https://openweathermap.org/img/w/{$response_en['weather'][0]['icon']}.png",
            'temp' => ceil($response_en['main']['temp'])
        ];
    }
}