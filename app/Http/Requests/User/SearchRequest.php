<?php

namespace App\Http\Requests\User;

use Auth;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'latitude' => ['nullable', 'numeric', 'required_with:longitude,radius'],
            'longitude' => ['nullable', 'numeric', 'required_with:latitude,radius'],
            'radius' => ['nullable', 'numeric', 'required_with:latitude,longitude'],
            'animal_type_ids' => ['sometimes', 'array'],
            'animal_type_ids.*' => ['string', 'exists:animal_types,uuid'],
            'breed_ids' => ['sometimes', 'array'],
            'breed_ids.*' => ['string', 'exists:breeds,uuid'],
        ];
    }
}
