<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\User;

use App\Enums\UserRole;
use App\Rules\PhoneNumberRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.user.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|unique:users|email',
            'first_name' => 'required',
            'last_name' => 'required',
            'user_name' => 'required|unique:users',
            'role' => [
                'required',
                Rule::in(UserRole::cases()),
            ],
            'phone' => [
                new PhoneNumberRule(),
            ],
            'code' => [
                'nullable',
                Rule::unique('users', 'code')->where('code', $this->code),
            ],
        ];
    }
}
