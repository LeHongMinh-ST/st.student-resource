<?php

declare(strict_types=1);

namespace App\Http\Resources\Student;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'note' => $this->note,
            'person_email' => $this->person_email,
            'gender' => $this->gender,
            'permanent_residence' => $this->permanent_residence,
            'dob' => $this->dob,
            'pob' => $this->pob,
            'countryside' => $this->countryside,
            'address' => $this->address,
            'training_type' => $this->training_type,
            'phone' => $this->phone,
            'nationality' => $this->nationality,
            'citizen_identification' => $this->citizen_identification,
            'ethnic' => $this->ethnic,
            'religion' => $this->religion,
            'thumbnail' => $this->thumbnail,
            'social_policy_object' => $this->social_policy_object,
        ];
    }
}
