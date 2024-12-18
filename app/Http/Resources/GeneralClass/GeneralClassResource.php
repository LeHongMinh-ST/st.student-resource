<?php

declare(strict_types=1);

namespace App\Http\Resources\GeneralClass;

use App\Http\Resources\Faculty\FacultyForLoadResource;
use App\Http\Resources\Student\StudentResource;
use App\Http\Resources\User\UserForLoadResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GeneralClassResource extends JsonResource
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
            'code' => $this->code,
            'name' => $this->name,
            'type' => $this->type,
            'status' => $this->status,
            'teacher_id' => $this->teacher_id,
            'sub_teacher_id' => $this->sub_teacher_id,
            'faculty_id' => $this->faculty_id,
            'major_id' => $this->major_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'teacher' => new UserForLoadResource($this->whenLoaded('teacher')),
            'sub_teacher' => new UserForLoadResource($this->whenLoaded('subTeacher')),
            'faculty' => new FacultyForLoadResource($this->whenLoaded('faculty')),
            'officer' => [
                'student_president' => new StudentResource($this->whenLoaded('studentPresident')),
                'student_secretary' => new StudentResource($this->whenLoaded('studentSecretary')),
            ]
        ];
    }
}
