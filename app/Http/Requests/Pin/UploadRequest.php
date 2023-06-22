<?php

namespace App\Http\Requests\Pin;

use Auth;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="PinMediaRequest",
 *     type="object",
 *
 *     @OA\Property(property="files", type="array", @OA\Items(
 *               type="string",
 *               format="binary"
 *          )),
 *     )),
 * )
 */
class UploadRequest extends FormRequest
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
            'files' => ['required', 'array'],
            'files.*' => ['required', 'image', 'max:2048'],
        ];
    }
}
