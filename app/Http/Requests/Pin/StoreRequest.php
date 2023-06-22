<?php

namespace App\Http\Requests\Pin;

use Auth;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="PinStoreRequest",
 *     type="object",
 *
 *     @OA\Property(property="name", type="string", example="Some title"),
 *     @OA\Property(property="type_id", type="string", example="995037a6-60b3-4055-aa14-3513aa9824ca"),
 *     @OA\Property(property="latitude", type="float"),
 *     @OA\Property(property="longitude", type="float"),
 *     @OA\Property(property="address", type="string", nullable=true),
 *     @OA\Property(property="contact", type="string", nullable=true),
 *     )),
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
            'name' => ['required', 'string', 'max:191'],
            'type_id' => ['required', 'string', 'exists:pin_types,uuid'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'address' => ['nullable', 'string'],
            'contact' => ['nullable', 'string'],
        ];
    }
}
