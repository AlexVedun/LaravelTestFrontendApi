<?php

namespace App\Observers;

use App\Models\WeatherRecord;
use Illuminate\Support\Facades\Cache;

class WeatherRecordObserver
{
    /**
     * Handle the WeatherRecord "created" event.
     */
    public function created(WeatherRecord $weatherRecord): void
    {
        Cache::tags(['weather-records-all'])->flush();
        Cache::tags(['weather-records-by-date'])->flush();
    }

    /**
     * Handle the WeatherRecord "updated" event.
     */
    public function updated(WeatherRecord $weatherRecord): void
    {
        Cache::tags(['weather-records-by-date'])->flush();
        Cache::forget('weather-record-' . $weatherRecord->id);
    }

    /**
     * Handle the WeatherRecord "deleted" event.
     */
    public function deleted(WeatherRecord $weatherRecord): void
    {
        Cache::tags(['weather-records-all'])->flush();
        Cache::tags(['weather-records-by-date'])->flush();
        Cache::forget('weather-record-' . $weatherRecord->id);
    }

    /**
     * Handle the WeatherRecord "restored" event.
     */
    public function restored(WeatherRecord $weatherRecord): void
    {
        //
    }

    /**
     * Handle the WeatherRecord "force deleted" event.
     */
    public function forceDeleted(WeatherRecord $weatherRecord): void
    {
        //
    }
}
