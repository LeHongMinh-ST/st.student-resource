<?php

declare(strict_types=1);

namespace App\Http\Resources\EmploymentSurveyResponse;

use App\Http\Resources\City\CityResource;
use App\Http\Resources\Student\StudentSurveyPeriodResource;
use App\Http\Resources\TrainingIndustry\TrainingIndustryResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmploymentSurveyResponseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (! $this->resource) {
            return [];
        }
        return [
            'id' => $this->id ?? 0,
            'survey_period' => new StudentSurveyPeriodResource($this->whenLoaded('surveyPeriod')),
            'student' => new UserResource($this->whenLoaded('student')),
            'city_work' => new CityResource($this->whenLoaded('cityWork')),
            'training_industry' => new TrainingIndustryResource($this->whenLoaded('trainingIndustry')),
            'email' => $this->email,
            'full_name' => $this->full_name,
            'survey_period_id' => $this->survey_period_id,
            'student_id' => $this->student_id,
            'dob' => $this->dob,
            'gender' => $this->gender,
            'gender_txt' => $this->gender?->getName(),
            'code_student' => $this->code_student,
            'phone_number' => $this->phone_number,
            'identification_card_number' => $this->identification_card_number,
            'identification_card_number_update' => $this->identification_card_number_update,
            'identification_issuance_place' => $this->identification_issuance_place,
            'identification_issuance_date' => $this->identification_issuance_date,
            'training_industry_id' => $this->training_industry_id,
            'course' => $this->course,
            'employment_status' => $this->employment_status,
            'recruit_partner_name' => $this->recruit_partner_name,
            'recruit_partner_address' => $this->recruit_partner_address,
            'recruit_partner_date' => $this->recruit_partner_date,
            'recruit_partner_position' => $this->recruit_partner_position,
            'work_area' => $this->work_area,
            'city_work_id' => $this->city_work_id,
            'employed_since' => $this->employed_since,
            'trained_field' => $this->trained_field,
            'professional_qualification_field' => $this->professional_qualification_field,
            'level_knowledge_acquired' => $this->level_knowledge_acquired,
            'starting_salary' => $this->starting_salary,
            'average_income' => $this->average_income,
            'job_search_method' => $this->job_search_method,
            'recruitment_type' => $this->recruitment_type,
            'soft_skills_required' => $this->soft_skills_required,
            'must_attended_courses' => $this->must_attended_courses,
            'solutions_get_job' => $this->solutions_get_job,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
