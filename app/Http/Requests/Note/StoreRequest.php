<?php

namespace App\Http\Requests\Note;

use Auth;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="NoteStoreRequest",
 *     type="object",
 *     required={"animal_id","category_id","title"},
 *
 *     @OA\Property(property="animal_id", type="string", example="995037a6-60b3-4055-aa14-3513aa9824ca"),
 *     @OA\Property(property="category_ids", type="array", @OA\Items(type="string"), nullable=true),
 *     @OA\Property(property="title", type="string", example="Some title"),
 *     @OA\Property(property="description", type="string", nullable=true),
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
            'title' => ['required', 'string', 'max:191'],
            'animal_id' => ['required', 'string', 'exists:animals,uuid'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['nullable', 'string', 'exists:categories,uuid'],
            'description' => ['nullable', 'string'],
        ];
    }
}
