<?php

declare(strict_types=1);

namespace App\Http\Resources\SchoolYear;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolYearResource extends JsonResource
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
            'start_year' => $this->start_year,
            'end_year' => $this->end_year,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
