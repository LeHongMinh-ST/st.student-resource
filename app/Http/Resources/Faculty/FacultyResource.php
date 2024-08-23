<?php

declare(strict_types=1);

namespace App\Http\Resources\Faculty;

use App\Http\Resources\User\UserResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FacultyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? 0,
            'name' => $this->name ?? '',
            'code' => $this->code ?? '',
            'created_at' => $this->created_at ?? Carbon::now(),
            'updated_at' => $this->updated_at ?? Carbon::now(),
            'users' => UserResource::collection($this->whenLoaded('users')),
            'admins' => UserResource::collection($this->whenLoaded('admins')),
        ];
    }
}
