<?php

declare(strict_types=1);

namespace App\Http\Resources\EmploymentSurveyResponse;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExternalEmploymentSurveyResponseResource extends JsonResource
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
            'full_name' => $this->full_name,
            'survey_period_id' => $this->survey_period_id,
            'code_student' => $this->code_student,
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
