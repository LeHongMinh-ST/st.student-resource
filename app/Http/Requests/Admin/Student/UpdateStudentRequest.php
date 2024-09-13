<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Student;

use App\Enums\Gender;
use App\Enums\SocialPolicyObject;
use App\Enums\StudentRole;
use App\Enums\TrainingType;
use App\Rules\YearRange;
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
            'last_name' => ['nullable', 'string', 'max:255', 'min:2'],
            'first_name' => ['nullable', 'max:255', 'min:1'],
            'code' => ['nullable', Rule::unique('students', 'code')->ignore($studentId), 'string'],
            'role' => ['nullable', Rule::enum(StudentRole::class)],
            'school_year' => ['nullable', new YearRange()],
            'gender' => ['nullable', Rule::enum(Gender::class)],
            'thumbnail' => ['nullable', 'file', 'mimes:jpeg,jpg,png', 'max:1024'],
            'person_email' => ['nullable', 'email', 'max:255'],
            'permanent_residence' => ['nullable', 'string', 'max:255'],
            'dob' => ['nullable', 'date', 'date_format:d-m-Y'],
            'pob' => ['nullable', 'string', 'max:255'],
            'countryside' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'training_type' => ['nullable', Rule::enum(TrainingType::class)],
            'phone' => ['nullable', 'string', 'max:20'],
            'nationality' => ['nullable', 'string', 'max:255'],
            'citizen_identification' => ['nullable', 'string', 'max:255'],
            'ethnic' => ['nullable', 'string', 'max:255'],
            'religion' => ['nullable', 'string', 'max:255'],
            'social_policy_object' => ['nullable', 'string', Rule::enum(SocialPolicyObject::class)],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
