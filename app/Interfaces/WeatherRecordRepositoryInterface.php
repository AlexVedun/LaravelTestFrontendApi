<?php

namespace App\Interfaces;

use App\Models\WeatherRecord;
use Illuminate\Database\Eloquent\Collection;

interface WeatherRecordRepositoryInterface
{
    public function getWeatherRecords(): Collection;
    public function getWeatherRecordsByUserId(int $userId): Collection;
    public function createWeatherRecord(array $weatherData): ?WeatherRecord;
    public function updateWeatherRecord(int $weatherRecordId, array $weatherData): ?WeatherRecord;
    public function deleteWeatherRecord(int $weatherRecordId): bool;
}
