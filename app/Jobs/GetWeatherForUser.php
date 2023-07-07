<?php

namespace App\Jobs;

use App\Interfaces\WeatherRecordRepositoryInterface;
use App\Models\User;
use App\Services\WeatherService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GetWeatherForUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private User $user;
    private WeatherService $weatherService;
    private WeatherRecordRepositoryInterface $weatherRecordRepository;

    /**
     * Create a new job instance.
     */
    public function __construct(
        User $user,
        WeatherService $weatherService,
        WeatherRecordRepositoryInterface $weatherRecordRepository
    ){
        $this->user = $user;
        $this->weatherService = $weatherService;
        $this->weatherRecordRepository = $weatherRecordRepository;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $weatherData = $this->weatherService->getWeatherForUser($this->user);

        if (!empty($weatherData)) {
            $weatherRecordData = [
                'user_id' => $this->user->id,
                'temp' => data_get($weatherData, 'main.temp', ''),
                'temp_min' => data_get($weatherData, 'main.temp_min', ''),
                'temp_max' => data_get($weatherData, 'main.temp_max', ''),
                'feels_like' => data_get($weatherData, 'main.feels_like', ''),
                'pressure' => data_get($weatherData, 'main.pressure', ''),
                'humidity' => data_get($weatherData, 'main.humidity', ''),
            ];

            $this->weatherRecordRepository->createWeatherRecord($weatherRecordData);
        } else {
            Log::warning('For user with id=' . $this->user->id . ' weather service has returned empty results.');
        }
    }
}
