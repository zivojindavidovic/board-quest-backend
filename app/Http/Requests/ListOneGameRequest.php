<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ListOneGameRequest extends FormRequest
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
            'uuid' => ['required', 'uuid', 'exists:games,id'],
        ];
    }

    /**
     * Merge route parameter
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge(['uuid' => $this->route('uuid')]);
    }

    /**
     * Returns custom error message
     *
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'uuid.exists' => "Game with this UUID: {$this->route('uuid')} does not exist.",
        ];
    }

    /**
     * Returns custom http status code
     *
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator): void
    {
        $errors = $validator->errors();

        if ($errors->has('uuid') && $errors->first('uuid') === "Game with this UUID: {$this->route('uuid')} does not exist.") {
            throw new HttpResponseException(response()->json([
                'success'    => false,
                'message'    => 'Validation failed.',
                'error_code' => 'VALIDATION_ERROR',
                'errors'     => $errors,
            ], 404));
        }

        parent::failedValidation($validator);
    }
}
