<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\User;

use App\Rules\PhoneNumberRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.user.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'nullable',
                'email',
                'unique:users,email,' . $this->user?->id . ',id',
            ],
            'phone' => [
                'nullable',
                new PhoneNumberRule(),
            ],
            'code' => [
                'string',
                'min:3',
                'nullable',
                'unique:users,code,' . $this->user?->id . ',id',
            ],
            'first_name' => [
                'nullable',
                'string',
                'min:1',
            ],
            'last_name' => [
                'nullable',
                'string',
                'min:1',
            ],
        ];
    }
}
