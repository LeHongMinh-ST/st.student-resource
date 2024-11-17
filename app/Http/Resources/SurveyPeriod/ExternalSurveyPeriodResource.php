<?php

declare(strict_types=1);

namespace App\Http\Resources\SurveyPeriod;

use App\Http\Resources\Graduation\GraduationCeremonyResource;
use App\Http\Resources\Student\StudentSurveyPeriodResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExternalSurveyPeriodResource extends JsonResource
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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
