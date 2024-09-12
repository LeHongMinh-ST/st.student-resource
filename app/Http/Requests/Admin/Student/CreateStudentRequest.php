<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Student;

use App\Enums\Gender;
use App\Enums\StudentRole;
use App\Rules\YearRange;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CreateStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.student.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'last_name' => ['required', 'string', 'max:255', 'min:2'],
            'first_name' => ['required', 'max:255', 'min:1'],
            'code' => ['required', 'unique:students,code', 'string'],
            'role' => ['required', Rule::enum(StudentRole::class)],
            'school_year' => ['required', new YearRange()],
            'gender' => ['required', Rule::enum(Gender::class)],
            'thumbnail' => ['nullable', 'file', 'mimes:jpeg,jpg,png', 'max:1024'],
        ];
    }
}
