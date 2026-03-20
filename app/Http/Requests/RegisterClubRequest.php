<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterClubRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'description' => ['nullable', 'string', 'min:2', 'max:10000'],
            'address' => ['required', 'string', 'min:2', 'max:255'],
            'city' => ['required', 'string', 'min:2', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'phone' => ['nullable', 'string', 'min:8', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', /*'unique:users'*/],
            'website' => ['nullable', 'string', 'url', 'max:255'],
            'image_url' => ['nullable', 'string', 'url', 'max:255'],
            'working_days' => ['nullable', 'array', 'min:1', 'max:7'],
            'working_days.*' => ['integer', 'between:0,6', 'distinct'],
            'working_hours' => ['required_with:working_days', 'array'],
            'working_hours.*' => ['required_with:working_days', 'array', 'min:1'],
            'working_hours.*.*.open_time' => ['required_with:working_days', 'date_format:H:i'],
            'working_hours.*.*.close_time' => ['required_with:working_days', 'date_format:H:i'],
            'available_games' => ['required', 'array', 'min:1'],
            'available_games.*' => ['required_with:available_games', 'uuid', 'exists:games,id'],
        ];
    }
}
