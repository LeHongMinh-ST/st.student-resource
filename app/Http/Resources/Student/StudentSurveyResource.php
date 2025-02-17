<?php

declare(strict_types=1);

namespace App\Http\Resources\Student;

use App\Http\Resources\EmploymentSurveyResponse\EmploymentSurveyResponseResource;
use App\Http\Resources\GeneralClass\GeneralClassForStudentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentSurveyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'last_name' => $this->last_name,
            'first_name' => $this->first_name,
            'full_name' => $this->last_name . ' ' . $this->first_name,
            'email' => $this->email,
            'code' => $this->code,
            'training_industry_id' => $this->training_industry_id,
            'current_employment_response' => new EmploymentSurveyResponseResource($this->whenLoaded('activeResponseSurvey')),
            'current_survey_period' => $this->whenLoaded('currentSurvey', fn () => [
                'number_mail_send' => (int) ($this->currentSurvey?->number_mail_send),
                'updated_at' => $this->currentSurvey?->send_mail_updated_at,
            ]),
            'status' => $this->status,
            'info' => new StudentInfoResource($this->whenLoaded('info')),
            'currentClass' => new GeneralClassForStudentResource($this->whenLoaded('currentClass')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
