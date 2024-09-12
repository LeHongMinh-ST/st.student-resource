<?php

declare(strict_types=1);

namespace App\Http\Requests\SystemAdmin\Faculty;

use Illuminate\Foundation\Http\FormRequest;

class StoreFacultyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'code' => ['required', 'unique:faculties,code', 'string'],
            'email' => ['nullable', 'unique:users,email', 'string'],
        ];
    }
}
