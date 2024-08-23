<?php

declare(strict_types=1);

namespace App\Http\Resources\User;

use App\Http\Resources\Faculty\FacultyForLoadResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserForLoadResource extends JsonResource
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
            'user_name' => $this->user_name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'code' => $this->code,
            'thumbnail' => $this->thumbnail_path,
            'department_id' => $this->department_id,
            'is_super_admin' => $this->is_super_admin,
            'permissions' => $this->permissions->pluck('code')->toArray(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'faculty' => new FacultyForLoadResource($this->whenLoaded('faculty')),
        ];
    }
}
