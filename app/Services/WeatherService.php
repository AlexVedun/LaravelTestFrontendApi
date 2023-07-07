<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class WeatherService
{
    private function requestWeather(string $lat, string $lon)
    {
        $response = Http::get(config('services.weather.api_url'), [
            'lat' => $lat,
            'lon' => $lon,
            'units' => 'metric',
            'appid' => config('services.weather.api_key'),
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return [];
    }

    public function getWeatherForUser(User $user): array
    {
        return $this->requestWeather($user->latitude, $user->longitude);
    }
}
