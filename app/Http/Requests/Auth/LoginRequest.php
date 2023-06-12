<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'exists:users,email'],
            'code' => ['required', 'numeric', 'digits:6', 'exists:personal_access_codes,code'],
            'device_name' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $user = User::whereEmail($this->request->get('email'))->first();

        if (! $user || $user->accessCodes()->active()->where('code', $this->request->get('code'))->doesntExist()) {
            throw ValidationException::withMessages([
                'code' => __('auth.expired'),
            ]);
        }
    }
}
