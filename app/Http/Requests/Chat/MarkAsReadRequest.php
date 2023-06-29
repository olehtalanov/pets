<?php

namespace App\Http\Requests\Chat;

use Auth;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ChatMessageReadRequest",
 *     type="object",
 *     required={"ids"},
 *
 *     @OA\Property(property="ids", type="array", @OA\Items(type="string", example="995037a6-60b3-4055-aa14-3513aa9824ca")),
 *     )),
 * )
 */
class MarkAsReadRequest extends FormRequest
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
            'ids' => ['required', 'array'],
            'ids.*' => ['string'],
        ];
    }
}
