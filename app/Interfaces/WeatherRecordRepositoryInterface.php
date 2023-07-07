<?php

namespace App\Interfaces;

use App\Models\WeatherRecord;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

interface WeatherRecordRepositoryInterface
{
    public function getWeatherRecords(): Collection;
    public function getWeatherRecordsByUserId(int $userId): Collection;
    public function createWeatherRecord(array $weatherData): ?WeatherRecord;
    public function updateWeatherRecord(int $weatherRecordId, array $weatherData): ?WeatherRecord;
    public function deleteWeatherRecord(int $weatherRecordId): bool;
    public function getWeatherRecordsByDate(Carbon $startDate, Carbon $endDate = null): Collection;
    public function getWeatherRecord(int $weatherRecordId): ?WeatherRecord;
}
