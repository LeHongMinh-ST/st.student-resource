<?php

declare(strict_types=1);

namespace App\Http\Resources\Department;

use App\Http\Resources\Faculty\FacultyForLoadResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
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
            'status' => $this->status,
            'faculty_id' => $this->faculty_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'faculty' => new FacultyForLoadResource($this->whenLoaded('faculty')),
        ];
    }
}
