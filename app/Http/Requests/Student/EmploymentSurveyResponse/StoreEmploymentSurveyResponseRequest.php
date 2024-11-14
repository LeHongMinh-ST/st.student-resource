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
use App\Enums\Status;
use App\Models\Student;
use App\Models\SurveyPeriod;
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
                function ($attribute, $value, $fail): void {
                    $surveyPeriod = SurveyPeriod::where('id', $value)->first();
                    if (! $surveyPeriod) {
                        $fail('Không tìm thấy kỳ đợt khảo sát');
                    } else {
                        if (now()->gt($surveyPeriod->end_time)) {
                            $fail('Đợt khảo sát đã kết thúc');
                        }
                        if (now()->lt($surveyPeriod->start_time)) {
                            $fail('Đợt khảo sát chưa bắt đầu');
                        }
                        if (Status::Disable === $surveyPeriod->status) {
                            $fail('Đợt khảo sát đã đóng');
                        }
                    }
                },
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
                function ($attribute, $value, $fail): void {
                    $student = Student::with(['surveyPeriods'])->where('code', $value)->first();
                    if (! $student) {
                        $fail('Không tìm thấy sinh viên');
                    }


                    if ($student?->surveyPeriods->where('id', $this->input('survey_period_id'))->where('status', Status::Enable)->isEmpty()) {
                        $fail('Sinh viên không thuộc đợt khảo sát');
                    }
                },
                'required',
                'string',
                'max:255',
            ],
            'identification_card_number' => [
                'required',
                'string',
                'max:30',
            ],
            'identification_card_number_update' => [
                'string',
                'nullable',
                'max:30',
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
            'course' => [
                'required',
            ],
            'employment_status' => [
                'required',
                Rule::in(EmploymentStatus::cases()),
            ],
            'recruit_partner_name' => [
                Rule::requiredIf((int) $this->employment_status === EmploymentStatus::Employed->value),
                'string',
                'max:255',
            ],
            'city_work_id' => [
                Rule::requiredIf((int) $this->employment_status === EmploymentStatus::Employed->value),
                Rule::exists('cities', 'id'),
            ],
            'recruit_partner_address' => [
                Rule::requiredIf((int) $this->employment_status === EmploymentStatus::Employed->value),
                'string',
                'max:255',
            ],
            'recruit_partner_date' => [
                Rule::requiredIf((int) $this->employment_status === EmploymentStatus::Employed->value),
                'date_format:Y-m-d',
            ],
            'recruit_partner_position' => [
                Rule::requiredIf((int) $this->employment_status === EmploymentStatus::Employed->value),
                'string',
                'max:255',
            ],
            'work_area' => [
                Rule::requiredIf((int) $this->employment_status === EmploymentStatus::Employed->value),
                Rule::in(WorkArea::cases()),
            ],
            'employed_since' => [
                Rule::in(EmployedSince::cases()),
                Rule::requiredIf((int) $this->employment_status === EmploymentStatus::Employed->value),
            ],
            'trained_field' => [
                Rule::in(TrainedField::cases()),
                Rule::requiredIf((int) $this->employment_status === EmploymentStatus::Employed->value),
            ],
            'professional_qualification_field' => [
                Rule::in(ProfessionalQualificationField::cases()),
                Rule::requiredIf((int) $this->employment_status === EmploymentStatus::Employed->value),
            ],
            'level_knowledge_acquired' => [
                Rule::in(LevelKnowledgeAcquired::cases()),
                Rule::requiredIf((int) $this->employment_status === EmploymentStatus::Employed->value),
            ],
            'starting_salary' => [
                'numeric',
                Rule::requiredIf((int) $this->employment_status === EmploymentStatus::Employed->value),
            ],
            'average_income' => [
                Rule::in(AverageIncome::cases()),
                Rule::requiredIf((int) $this->employment_status === EmploymentStatus::Employed->value),
            ],
            'job_search_method' => [
                Rule::requiredIf((int) $this->employment_status === EmploymentStatus::Employed->value),
            ],
            'job_search_method.value' => [
                Rule::requiredIf($this->job_search_method),
                'array',
            ],
            'job_search_method.value.*' => [
                Rule::in(JobSearchMethod::cases()),
            ],
            'job_search_method.content_other' => [
                'string',
                'max:255',
            ],
            'recruitment_type' => [
                Rule::requiredIf((int) $this->employment_status === EmploymentStatus::Employed->value),
            ],
            'recruitment_type.value' => [
                Rule::requiredIf($this->recruitment_type),
                'array',
            ],
            'recruitment_type.value.*' => [
                Rule::in(RecruitmentType::cases()),
            ],
            'recruitment_type.content_other' => [
                'string',
                'max:255',
            ],
            'soft_skills_required' => [
                Rule::requiredIf((int) $this->employment_status === EmploymentStatus::Employed->value),
            ],
            'soft_skills_required.value' => [
                Rule::requiredIf($this->soft_skills_required),
                'array',
            ],
            'soft_skills_required.value.*' => [
                Rule::in(SoftSkillsRequired::cases()),
            ],
            'soft_skills_required.content_other' => [
                'string',
                'max:255',
            ],
            'must_attended_courses' => [
                Rule::requiredIf((int) $this->employment_status === EmploymentStatus::Employed->value),
            ],
            'must_attended_courses.value' => [
                Rule::requiredIf($this->must_attended_courses),
                'array',
            ],
            'must_attended_courses.value.*' => [
                'nullable',
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
                'array',
            ],
            'solutions_get_job.value.*' => [
                Rule::in(SolutionsGetJob::cases()),
            ],
            'solutions_get_job.content_other' => [
                'string',
                'max:255',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'code_student' => 'Mã sinh viên',
            'phone_number' => 'Số điện thoại',
            'dob' => 'Ngày sinh',
            'identification_card_number' => 'Số CCCD',
            'identification_card_number_update' => 'Số CCCD mới',
            'identification_issuance_place' => 'Nơi cấp CCCD',
            'identification_issuance_date' => 'Ngày cấp CCCD',
            'training_industry_id' => 'Ngành đào tạo',
            'admission_year_id' => 'Năm nhập học',
            'employment_status' => 'Tình trạng việc làm',
            'recruit_partner_name' => 'Tên đơn vị tuyển dụng',
            'recruit_partner_address' => 'Địa chỉ đơn vị tuyển dụng',
            'recruit_partner_date' => 'Ngày tuyển dụng',
            'recruit_partner_position' => 'Chức vụ',
            'work_area' => 'Khu vực làm việc',
            'employed_since' => 'Có việc làm từ khi nào',
            'trained_field' => 'Mức độ phù hợp với ngành đào tạo',
            'professional_qualification_field' => 'Mức độ phù hợp với chuyên môn đào tạo',
            'level_knowledge_acquired' => 'Mức độ học được kiến thức, kỹ năng cần thiết cho công việc',
            'starting_salary' => 'Mức lương khởi điểm khi mới nhận việc',
            'average_income' => 'Mức thu nhập bình quân/tháng',
            'job_search_method' => 'Tìm việc làm theo hình thức nào',
            'recruitment_type' => 'Hình thức tuyển dụng',
            'soft_skills_required' => 'Kỹ năng mềm cần có',
            'must_attended_courses' => 'Các khóa học phải tham ra để nâng cao kỹ năng',
            'solutions_get_job' => 'Các giải pháp để có việc làm',
        ];
    }
}
