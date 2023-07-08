<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WeatherRecord;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class WeatherRecordFactory extends Factory
{
    protected $model = WeatherRecord::class;

    public function definition()
    {
        return [
            'temp' => $this->faker->randomFloat(1, 17, 28),
            'temp_min' => $this->faker->randomFloat(1, 15, 17),
            'temp_max' => $this->faker->randomFloat(1, 28, 30),
            'feels_like' => $this->faker->randomFloat(1, 15, 30),
            'pressure' => $this->faker->randomFloat(null, 1015, 1030),
            'humidity' => $this->faker->randomFloat(null, 30, 70),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
        ];
    }
}
