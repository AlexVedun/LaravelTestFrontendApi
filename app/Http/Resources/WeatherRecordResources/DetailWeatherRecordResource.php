<?php

namespace App\Http\Resources\WeatherRecordResources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailWeatherRecordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'temp' => $this->temp,
            'temp_min' => $this->temp_min,
            'temp_max' => $this->temp_max,
            'feels_like' => $this->feels_like,
            'pressure' => $this->pressure,
            'humidity' => $this->humidity,
            'created_at' => $this->created_at,
        ];
    }
}
