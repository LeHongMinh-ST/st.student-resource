<?php

declare(strict_types=1);

namespace App\Http\Resources\Student;

use App\Http\Resources\GeneralClass\GeneralClassForStudentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentSurveyPeriodResource extends JsonResource
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
            'status' => $this->status,
            'info' => new StudentInfoResource($this->whenLoaded('info')),
            'current_class' => new GeneralClassForStudentResource($this->whenLoaded('currentClass')),
            'survey_period' => $this->whenPivotLoaded('survey_period_student', fn () => [
                'number_mail_send' => $this->pivot->number_mail_send,
            ]),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
