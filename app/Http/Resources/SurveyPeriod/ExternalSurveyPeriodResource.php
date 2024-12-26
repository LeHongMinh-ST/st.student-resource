<?php

declare(strict_types=1);

namespace App\Http\Resources\SurveyPeriod;

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
            'start_date' => $this->start_time,
            'end_date' => $this?->end_time,
            'year' => $this->year,
            'faculty_id' => $this->faculty_id,
        ];
    }
}
