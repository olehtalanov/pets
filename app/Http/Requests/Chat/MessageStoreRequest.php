<?php

namespace App\Http\Requests\Chat;

use Auth;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ChatMessageStoreRequest",
 *     type="object",
 *     required={"recipient_id", "message"},
 *
 *     @OA\Property(property="recipient_id", type="string", example="995037a6-60b3-4055-aa14-3513aa9824ca"),
 *     @OA\Property(property="message", type="string"),
 *     )),
 * )
 */
class MessageStoreRequest extends FormRequest
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
            'recipient_id' => ['required', 'string', 'exists:users,uuid'],
            'message' => ['required', 'string'],
        ];
    }
}
