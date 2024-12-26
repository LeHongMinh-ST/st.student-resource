<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\StudentRequestUpdate;

use App\Enums\StudentInfoUpdateStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ChangeStatusRequestUpdateStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.student-request.update-status');
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
