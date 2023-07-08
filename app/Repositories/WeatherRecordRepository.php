<?php

namespace App\Repositories;

use App\Interfaces\WeatherRecordRepositoryInterface;
use App\Models\WeatherRecord;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WeatherRecordRepository implements WeatherRecordRepositoryInterface
{

    public function getWeatherRecords(): Collection
    {
        return WeatherRecord::all();
    }

    public function getWeatherRecordsByUserId(int $userId): Collection
    {
        $cacheKey = "weather-records-all-${userId}";

        return Cache::tags(['weather-records-all'])->rememberForever($cacheKey, function() use ($userId) {
            return WeatherRecord::whereUserId($userId)->get();
        });
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

    public function getWeatherRecordsByDate(int $userId, Carbon $startDate, Carbon $endDate = null): Collection
    {
        $paramsHash = md5(serialize([$userId, $startDate, $endDate]));
        $cacheKey = "weather-records-by-date-${paramsHash}";

        return Cache::tags(['weather-records-by-date'])->rememberForever($cacheKey, function () use ($startDate, $endDate, $userId) {
            return WeatherRecord::whereUserId($userId)
                ->where('created_at', '>=', $startDate)
                ->when($endDate, function (Builder $query) use ($endDate) {
                    return $query->where('created_at', '<=', $endDate);
                })
                ->get();
        });
    }

    public function getWeatherRecord(int $weatherRecordId): ?WeatherRecord
    {
        $cacheKey = "weather-record-${weatherRecordId}";

        return Cache::tags('weather-record')->rememberForever($cacheKey, function() use ($weatherRecordId) {
            return WeatherRecord::find($weatherRecordId);
        });
    }
}
