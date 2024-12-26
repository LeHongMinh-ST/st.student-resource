<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Student;

use App\Enums\Gender;
use App\Enums\SocialPolicyObject;
use App\Enums\TrainingType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.student.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $studentId = $this->route('student'); // Assuming your route parameter is named 'student'

        return [
            'id' => ['required', 'integer', 'exists:students,id'], // Validate student ID
            'last_name' => ['nullable', 'string', 'max:255', 'min:2'],
            'first_name' => ['nullable', 'max:255', 'min:1'],
            'info' => ['nullable', 'array'],
            'info.note' => ['nullable', 'string', 'max:1000'],
            'info.person_email' => ['nullable', 'email', 'max:255'],
            'info.gender' => ['nullable', Rule::enum(Gender::class)],
            'info.permanent_residence' => ['nullable', 'string', 'max:255'],
            'info.dob' => ['nullable', 'date'],
            'info.pob' => ['nullable', 'string', 'max:255'],
            'info.countryside' => ['nullable', 'string', 'max:255'],
            'info.address' => ['nullable', 'string', 'max:255'],
            'info.training_type' => ['nullable', Rule::enum(TrainingType::class)],
            'info.phone' => ['nullable', 'string', 'max:20'],
            'info.nationality' => ['nullable', 'string', 'max:255'],
            'info.citizen_identification' => ['nullable', 'string', 'max:255'],
            'info.ethnic' => ['nullable', 'string', 'max:255'],
            'info.religion' => ['nullable', 'string', 'max:255'],
            'info.thumbnail' => ['nullable', 'string', 'url'],
            'info.social_policy_object' => ['nullable', 'string', Rule::enum(SocialPolicyObject::class)],
            'families' => ['nullable', 'array'],
            'families.*.relationship' => ['nullable', 'string'],
            'families.*.full_name' => ['nullable', 'string', 'max:255'],
            'families.*.job' => ['nullable', 'string', 'max:255'],
            'families.*.phone' => ['nullable', 'string', 'max:20'],
        ];
    }
}
