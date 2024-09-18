<?php

declare(strict_types=1);

namespace App\Http\Resources\GeneralClass;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GeneralClassForStudentResource extends JsonResource
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
        ];
    }
}
