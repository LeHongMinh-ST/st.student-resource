<?php

declare(strict_types=1);

namespace App\Http\Resources\Student;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentFamilyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'relationship' => $this->relationship,
            'full_name' => $this->full_name,
            'job' => $this->job,
            'phone' => $this->phone,
        ];
    }
}
