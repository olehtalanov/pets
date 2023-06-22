<?php

namespace App\Http\Requests\Pin;

use Auth;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="PinSearchRequest",
 *     type="object",
 *
 *     @OA\Property(property="latitude", type="float", nullable=true),
 *     @OA\Property(property="longitude", type="float", nullable=true),
 *     @OA\Property(property="radius", type="int", nullable=true),
 *     @OA\Property(property="type_ids", type="array", @OA\Items(type="string"), nullable=true),
 *     )),
 * )
 */
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
            'type_ids' => ['sometimes', 'array'],
        ];
    }
}
