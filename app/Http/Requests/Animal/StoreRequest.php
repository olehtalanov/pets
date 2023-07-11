<?php

namespace App\Http\Requests\Animal;

use App\Enums\SexEnum;
use App\Enums\WeightUnitEnum;
use Auth;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="AnimalStoreRequest",
 *     type="object",
 *     required={"name","sex","birth_date","breed_name","weight","weight_unit"},
 *
 *     @OA\Property(property="name", type="string", example="Fluffy"),
 *     @OA\Property(property="sex", type="string", enum={"male","female"}, example="male"),
 *     @OA\Property(property="birth_date", type="string", example="2021-06-22"),
 *     @OA\Property(property="animal_type", type="string", nullable=true, example="995037a6-5811-4ace-b1f7-4667517dd6e0"),
 *     @OA\Property(property="breed", type="string", nullable=true, example=null),
 *     @OA\Property(property="custom_type_name", type="string", nullable=true, example=null),
 *     @OA\Property(property="custom_breed_name", type="string", nullable=true),
 *     @OA\Property(property="breed_name", type="string"),
 *     @OA\Property(property="metis", type="boolean", example=false),
 *     @OA\Property(property="sterilised", type="boolean", example=false),
 *     @OA\Property(property="weight", type="number", format="double", example="2.5"),
 *     @OA\Property(property="weight_unit", type="string", example="kg"),
 * )
 */
class StoreRequest extends FormRequest
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
            'animal_type_id' => ['required_without:custom_type_name', 'nullable', 'string', 'exists:animal_types,uuid'],
            'breed_id' => ['required_without:custom_breed_name', 'nullable', 'string', 'exists:breeds,uuid'],
            'breed_name' => ['required', 'string', 'max:100'],
            'custom_type_name' => ['required_without:animal_type_id', 'nullable', 'string', 'max:100'],
            'custom_breed_name' => ['required_without:breed_id', 'nullable', 'string', 'max:100'],
            'metis' => ['required', 'boolean'],
            'sterilised' => ['required', 'boolean'],
            'weight' => ['required', 'numeric'],
            'weight_unit' => ['required', 'string', new Enum(WeightUnitEnum::class)],
        ];
    }
}
