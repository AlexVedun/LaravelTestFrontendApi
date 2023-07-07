<?php

namespace App\Repositories;

use App\Interfaces\WeatherRecordRepositoryInterface;
use App\Models\WeatherRecord;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class WeatherRecordRepository implements WeatherRecordRepositoryInterface
{

    public function getWeatherRecords(): Collection
    {
        return WeatherRecord::all();
    }

    public function getWeatherRecordsByUserId(int $userId): Collection
    {
        return WeatherRecord::whereUserId($userId)->get();
    }

    public function createWeatherRecord(array $weatherData): ?WeatherRecord
    {
        try {
            return WeatherRecord::create($weatherData);
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            Log::debug($exception->getTraceAsString());
        }

        return null;
    }

    public function updateWeatherRecord(int $weatherRecordId, array $weatherData): ?WeatherRecord
    {
        try {
            $weatherRecord = WeatherRecord::whereId($weatherRecordId)->first();
            $weatherRecord->update($weatherData);
            $weatherRecord->refresh();
            return $weatherRecord;
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            Log::debug($exception->getTraceAsString());
        }

        return null;
    }

    public function deleteWeatherRecord(int $weatherRecordId): bool
    {
        try {
            $weatherRecord = WeatherRecord::whereId($weatherRecordId)->first();
            $weatherRecord->delete();
            return true;
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            Log::debug($exception->getTraceAsString());
        }

        return false;
    }
}
