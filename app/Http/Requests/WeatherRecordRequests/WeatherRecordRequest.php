<?php

namespace App\Http\Requests\WeatherRecordRequests;

use Illuminate\Foundation\Http\FormRequest;

class WeatherRecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'weather_request_id' => ['required', 'int', 'exists:weather_records,id'],
        ];
    }
}
