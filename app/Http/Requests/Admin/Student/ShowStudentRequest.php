<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Student;

use App\Supports\StudentHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ShowStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $gate = Gate::allows('admin.student.show');

        $student = $this->route('student');

        return $gate && StudentHelper::checkUserStudent($student->id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

        ];
    }
}
