<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EmploymentSurvey\AverageIncome;
use App\Enums\EmploymentSurvey\EmployedSince;
use App\Enums\EmploymentSurvey\LevelKnowledgeAcquired;
use App\Enums\EmploymentSurvey\ProfessionalQualificationField;
use App\Enums\EmploymentSurvey\TrainedField;
use App\Enums\EmploymentSurvey\WorkArea;
use App\Enums\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmploymentSurveyResponse extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'survey_period_id',
        'student_id',
        'email',
        'full_name',
        'dob',
        'gender',
        'code_student',
        'identification_card_number',
        'identification_card_number_update',
        'identification_issuance_place',
        'identification_issuance_date',
        'training_industry_id',
        'course',
        'phone_number',
        'employment_status', // Tình trạng việc làm
        'recruit_partner_name', // Tên đơn vị tuyển dụng
        'recruit_partner_address', // Địa chỉ đơn vị tuyển dụng
        'recruit_partner_date', // Ngày tuyển dụng
        'recruit_partner_position', // Chức vụ
        'work_area', // Khu vực làm việc
        'employed_since', // Có việc làm từ khi nào
        'trained_field', // Mức độ phù hợp với ngành đào tạo
        'professional_qualification_field', // Mức độ phù hợp với chuyên môn đào tạo
        'level_knowledge_acquired', // Mức độ học được kiến thức, kỹ năng cần thiết cho công việc
        'starting_salary', // Mức lương khởi điểm khi mới nhận việc
        'average_income', // Mức thu nhập bình quân/tháng
        'job_search_method', // Tìm việc làm theo hình thức nào
        'recruitment_type', // Hình thức tuyển dụng
        'soft_skills_required', // Kỹ năng mềm cần có
        'must_attended_courses', // Các khóa học phải tham ra để nâng cao kỹ năng
        'solutions_get_job', // Các giải pháp để có việc làm
    ];

    protected $casts = [
        'employed_since' => EmployedSince::class,
        'work_area' => WorkArea::class,
        'trained_field' => TrainedField::class,
        'professional_qualification_field' => ProfessionalQualificationField::class,
        'level_knowledge_acquired' => LevelKnowledgeAcquired::class,
        'average_income' => AverageIncome::class,
        'gender' => Gender::class,
    ];

    public function getJobSearchMethodAttribute($value)
    {
        return null === $value ? null : json_decode($value, true);
    }

    public function getRecruitmentTypeAttribute($value)
    {
        return null === $value ? null : json_decode($value, true);
    }

    public function getSoftSkillsRequiredAttribute($value)
    {
        return null === $value ? null : json_decode($value, true);
    }

    public function getMustAttendedCoursesAttribute($value)
    {
        return null === $value ? null : json_decode($value, true);
    }

    public function getSolutionsGetJobAttribute($value)
    {
        return null === $value ? null : json_decode($value, true);
    }

    //----------------------- SCOPES ----------------------------------//

    // ------------------------ RELATIONS -------------------------//

    // ------------------------ CASTS -------------------------//

    // ---------------------- ACCESSORS AND MUTATORS --------------------//
    public function student(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function surveyPeriod(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SurveyPeriod::class);
    }
}
