<?php

namespace App\Http\Requests\Event;

use App\Enums\EventRepeatSchemeEnum;
use Auth;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="EventStoreRequest",
 *     type="object",
 *     required={"animal_id","title","repeat_scheme"},
 *
 *     @OA\Property(property="animal_id", type="string", example="995037a6-60b3-4055-aa14-3513aa9824ca"),
 *     @OA\Property(property="category_ids", type="array", @OA\Items(type="string")),
 *     @OA\Property(property="title", type="string", example="Some title"),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="starts_at", type="string", nullable=true, example=null),
 *     @OA\Property(property="ends_at", type="string", nullable=true, example=null),
 *     @OA\Property(property="repeat", type="string", example="never"),
 *     @OA\Property(property="whole_day", type="boolean", example=false),
 *     @OA\Property(property="only_this", type="boolean", example=true),
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
            'animal_id' => ['required', 'string', 'exists:animals,uuid'],
            'title' => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date'],
            'repeat_scheme' => ['required', 'string', new Enum(EventRepeatSchemeEnum::class)],
            'whole_day' => ['nullable', 'boolean'],
            'only_this' => ['sometimes', 'boolean'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['nullable', 'string', 'exists:categories,uuid'],
        ];
    }
}
