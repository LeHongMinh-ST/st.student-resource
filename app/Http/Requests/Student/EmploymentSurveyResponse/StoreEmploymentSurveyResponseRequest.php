<?php

declare(strict_types=1);

namespace App\Http\Requests\Student\EmploymentSurveyResponse;

use App\Enums\EmploymentSurvey\AverageIncome;
use App\Enums\EmploymentSurvey\EmployedSince;
use App\Enums\EmploymentSurvey\EmploymentStatus;
use App\Enums\EmploymentSurvey\JobSearchMethod;
use App\Enums\EmploymentSurvey\LevelKnowledgeAcquired;
use App\Enums\EmploymentSurvey\MustAttendedCourses;
use App\Enums\EmploymentSurvey\ProfessionalQualificationField;
use App\Enums\EmploymentSurvey\RecruitmentType;
use App\Enums\EmploymentSurvey\SoftSkillsRequired;
use App\Enums\EmploymentSurvey\SolutionsGetJob;
use App\Enums\EmploymentSurvey\TrainedField;
use App\Enums\EmploymentSurvey\WorkArea;
use App\Enums\Gender;
use App\Rules\PhoneNumberRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmploymentSurveyResponseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'survey_period_id' => [
                'required',
                'integer',
                'exists:survey_periods,id',
            ],
            'student_id' => [
                'nullable',
                'integer',
                'exists:students,id',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'full_name' => [
                'required',
                'string',
                'max:255',
            ],
            'dob' => [
                'required',
                'date_format:Y-m-d',
            ],
            'gender' => [
                'required',
                Rule::in(Gender::cases()),
            ],
            'code_student' => [
                Rule::exists('students', 'code'),
                'required',
                'string',
                'max:255',
            ],
            'identification_card_number' => [
                'required',
                'string',
                'max:255',
            ],
            'identification_issuance_place' => [
                'required',
                'string',
                'max:255',
            ],
            'identification_issuance_date' => [
                'required',
                'date_format:Y-m-d',
            ],
            'training_industry_id' => [
                'required',
                'integer',
                'exists:training_industries,id',
            ],
            'phone_number' => [
                'required',
                'string',
                new PhoneNumberRule(),
            ],
            'admission_year_id' => [
                'required',
                'integer',
                'exists:admission_years,id',
            ],
            'employment_status' => [
                'required',
                Rule::in(EmploymentStatus::cases()),
            ],
            'recruit_partner_name' => [
                Rule::requiredIf($this->employment_status === EmploymentStatus::Employed->value),
                'string',
                'max:255',
            ],
            'recruit_partner_address' => [
                Rule::requiredIf($this->employment_status === EmploymentStatus::Employed->value),
                'string',
                'max:255',
            ],
            'recruit_partner_date' => [
                Rule::requiredIf($this->employment_status === EmploymentStatus::Employed->value),
                'date_format:Y-m-d',
            ],
            'recruit_partner_position' => [
                Rule::requiredIf($this->employment_status === EmploymentStatus::Employed->value),
                'string',
                'max:255',
            ],
            'work_area' => [
                Rule::requiredIf($this->employment_status === EmploymentStatus::Employed->value),
                Rule::in(WorkArea::cases()),
            ],
            'employed_since' => [
                Rule::in(EmployedSince::cases()),
                Rule::requiredIf($this->employment_status === EmploymentStatus::Employed->value),
            ],
            'trained_field' => [
                Rule::in(TrainedField::cases()),
                Rule::requiredIf($this->employment_status === EmploymentStatus::Employed->value),
            ],
            'professional_qualification_field' => [
                Rule::in(ProfessionalQualificationField::cases()),
                Rule::requiredIf($this->employment_status === EmploymentStatus::Employed->value),
            ],
            'level_knowledge_acquired' => [
                Rule::in(LevelKnowledgeAcquired::cases()),
                Rule::requiredIf($this->employment_status === EmploymentStatus::Employed->value),
            ],
            'starting_salary' => [
                'numeric',
                Rule::requiredIf($this->employment_status === EmploymentStatus::Employed->value),
            ],
            'average_income' => [
                Rule::in(AverageIncome::cases()),
                Rule::requiredIf($this->employment_status === EmploymentStatus::Employed->value),
            ],
            'job_search_method' => [
                Rule::requiredIf($this->employment_status === EmploymentStatus::Employed->value),
            ],
            'job_search_method.value' => [
                Rule::requiredIf($this->job_search_method),
                Rule::in(JobSearchMethod::cases()),
            ],
            'job_search_method.content_other' => [
                'string',
                'max:255',
            ],
            'recruitment_type' => [
                Rule::requiredIf($this->employment_status === EmploymentStatus::Employed->value),
            ],
            'recruitment_type.value' => [
                Rule::requiredIf($this->recruitment_type),
                Rule::in(RecruitmentType::cases()),
            ],
            'recruitment_type.content_other' => [
                'string',
                'max:255',
            ],
            'soft_skills_required' => [
                Rule::requiredIf($this->employment_status === EmploymentStatus::Employed->value),
            ],
            'soft_skills_required.value' => [
                Rule::requiredIf($this->soft_skills_required),
                Rule::in(SoftSkillsRequired::cases()),
            ],
            'soft_skills_required.content_other' => [
                'string',
                'max:255',
            ],
            'must_attended_courses' => [
                Rule::requiredIf($this->employment_status === EmploymentStatus::Employed->value),
            ],
            'must_attended_courses.value' => [
                Rule::requiredIf($this->must_attended_courses),
                Rule::in(MustAttendedCourses::cases()),
            ],
            'must_attended_courses.content_other' => [
                'string',
                'max:255',
            ],
            'solutions_get_job' => [
                'required',
            ],
            'solutions_get_job.value' => [
                'required',
                Rule::in(SolutionsGetJob::cases()),
            ],
            'solutions_get_job.content_other' => [
                'string',
                'max:255',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'code_student' => 'Mã sinh viên',
            'phone_number' => 'Số điện thoại',
            'dob' => 'Ngày sinh',
            'identification_issuance_date' => 'Ngày cấp CCCD',
        ];
    }
}
