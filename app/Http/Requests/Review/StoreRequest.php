<?php

namespace App\Http\Requests\Review;

use Auth;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ReviewStoreRequest",
 *     type="object",
 *
 *     @OA\Property(property="rating", type="int", example=5),
 *     @OA\Property(property="message", type="string", nullable=true, example="Some message."),
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
            'rating' => ['required', 'numeric', 'min:1', 'max:5'],
            'message' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
