<?php

declare(strict_types=1);

namespace App\Http\Resources\Student;

use App\Http\Resources\Faculty\FacultyForLoadResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'email' => $this->email,
            'code' => $this->code,
            'school_year' => $this->school_year,
            'admission_year' => $this->admission_year,
            'faculty' => new FacultyForLoadResource($this->faculty),
            'status' => $this->status,
            'role' => $this->role,
            'info' => new StudentInfoResource($this->whenLoaded('info')),
            'families' => StudentFamilyResource::collection($this->whenLoaded('families')),
        ];
    }
}
