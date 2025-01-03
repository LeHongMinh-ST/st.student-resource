<?php

declare(strict_types=1);

namespace App\Http\Resources\StudentWarning;

use App\Http\Resources\Semester\SemesterResource;
use App\Http\Resources\Student\StudentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentWarningResource extends JsonResource
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
            'semester' => new SemesterResource($this->semester),
            'semester_id' => $this->semester_id,
            'school_year' => $this->school_year,
            'students' => StudentResource::collection($this->whenLoaded('students')),
            'student_count' => $this->students_count ?? 0,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
