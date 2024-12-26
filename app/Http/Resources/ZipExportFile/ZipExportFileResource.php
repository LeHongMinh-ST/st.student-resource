<?php

declare(strict_types=1);

namespace App\Http\Resources\ZipExportFile;

use App\Http\Resources\SurveyPeriod\SurveyPeriodResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ZipExportFileResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? 0,
            'name' => $this->name ?? '',
            'file_total' => $this->file_total ?? 0,
            'process_total' => $this->process_total ?? 0,
            'survey_period_id' => $this->survey_period_id ?? 0,
            'survey_period' => new SurveyPeriodResource($this->whenLoaded('surveyPeriod')),
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
