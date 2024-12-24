<?php

declare(strict_types=1);

namespace App\Http\Resources\StudentQuit;

use App\Http\Resources\Student\StudentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentQuitResource extends JsonResource
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
            'name' => $this->name,
            'year' => $this->year,
            'certification' => $this->certification,
            'certification_date' => $this->certification_date,
            'students' => StudentResource::collection($this->whenLoaded('students')) ?? [],
            'student_count' => $this->students_count ?? 0,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
