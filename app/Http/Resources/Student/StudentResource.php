<?php

declare(strict_types=1);

namespace App\Http\Resources\Student;

use App\Http\Resources\AdmissionYear\AdmissionYearResource;
use App\Http\Resources\Faculty\FacultyForLoadResource;
use App\Http\Resources\GeneralClass\GeneralClassForStudentResource;
use App\Models\GraduationCeremonyStudent;
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
            'admission_year' => new AdmissionYearResource($this->whenLoaded('admissionYear')),
            'faculty' => new FacultyForLoadResource($this->faculty),
            'status' => $this->status,
            'info' => new StudentInfoResource($this->whenLoaded('info')),
            'families' => StudentFamilyResource::collection($this->whenLoaded('families')),
            'currentClass' => new GeneralClassForStudentResource($this->whenLoaded('currentClass')),
            'graduate' => $this->whenPivotLoaded(new GraduationCeremonyStudent(), function () {
                return  [
                    'gpa' => $this->pivot->gpa,
                    'email' =>  $this->pivot->email,
                    'rank' => $this->pivot->rank
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
