<?php

declare(strict_types=1);

namespace App\Http\Requests\Student\Student;

use Illuminate\Foundation\Http\FormRequest;

class StudentSearchRequest extends FormRequest
{
    /**
     * Determine if the class is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string',
            'code' => 'nullable|string',
            'email' => 'nullable|string',
            'phone_number' => 'nullable|string',
        ];
    }
}
