<?php

declare(strict_types=1);

namespace App\Http\Resources\SurveyPeriod;

use App\Http\Resources\Graduation\GraduationCeremonyResource;
use App\Http\Resources\Student\StudentSurveyPeriodResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SurveyPeriodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? 0,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'start_date' => $this->start_time,
            'end_date' => $this?->end_time,
            'year' => $this->year,
            'type' => $this->type,
            'faculty_id' => $this->faculty_id,
            'total_student_responses' => $this->total_student_responses,
            'total_student' => $this->total_student,
            'created_user' => new UserResource($this->whenLoaded('createdBy')),
            'updated_user' => new UserResource($this->whenLoaded('updatedBy')),
            'students' => StudentSurveyPeriodResource::collection($this->whenLoaded('students')) ?? [],
            'graduation_ceremonies' => GraduationCeremonyResource::collection($this->whenLoaded('graduationCeremonies')) ?? [],
            'graduation_ceremony_ids' => $this->whenLoaded('graduationCeremonies', fn () => $this->graduationCeremonies->map(fn ($graduationCeremony) => (string) $graduationCeremony->id)),
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
