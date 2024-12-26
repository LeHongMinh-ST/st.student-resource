<?php

declare(strict_types=1);

namespace App\Http\Resources\Student;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExternalStudentInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'gender' => $this->gender,
            'dob' => $this->dob,
            'countryside' => $this->countryside,
            'phone' => $this->phone,
            'citizen_identification' => $this->citizen_identification,
        ];
    }
}
