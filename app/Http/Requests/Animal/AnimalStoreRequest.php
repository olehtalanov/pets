<?php

namespace App\Http\Requests\Animal;

use App\Enums\Animal\SexEnum;
use App\Enums\Animal\WeightUnitEnum;
use Auth;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class AnimalStoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],
            'sex' => ['required', 'string', new Enum(SexEnum::class)],
            'birth_date' => ['required', 'date', 'before:tomorrow'],
            'animal_type_id' => ['nullable', 'string', 'exists:animal_types,uuid'],
            'custom_animal_type' => ['required_without:animal_type_id', 'string', 'max:100'],
            'breed_id' => ['nullable', 'string', 'exists:breeds,uuid'],
            'custom_breed_name' => ['required_without:breed_id', 'string', 'max:100'],
            'breed_name' => ['required', 'string', 'max:100'],
            'metis' => ['required', 'boolean'],
            'weight' => ['required', 'numeric'],
            'weight_unit' => ['required', 'string', new Enum(WeightUnitEnum::class)],
        ];
    }
}
