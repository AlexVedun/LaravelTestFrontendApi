<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'temp',
        'temp_min',
        'temp_max',
        'feels_like',
        'pressure',
        'humidity',
        'user_id',
    ];

    // Relations

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
