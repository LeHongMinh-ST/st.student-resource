<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Student;

use App\Enums\StudentInfoUpdateStatus;
use App\Enums\StudentRole;
use App\Supports\AuthHelper;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeStatusRequestUpdateStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return AuthHelper::isRoleStudent(StudentRole::President, StudentRole::VicePresident);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(StudentInfoUpdateStatus::class)],
            'reject_note' => ['required', 'string'],
        ];
    }
}
