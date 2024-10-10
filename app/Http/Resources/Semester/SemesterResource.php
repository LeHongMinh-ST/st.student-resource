<?php

declare(strict_types=1);

namespace App\Http\Resources\Semester;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SemesterResource extends JsonResource
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
            'start_year' => $this->start_year,
            'end_year' => $this->end_year,
            'semester' => $this->semester,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}