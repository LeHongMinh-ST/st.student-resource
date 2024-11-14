<?php

declare(strict_types=1);

namespace App\Http\Resources\City;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
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
            'priority' => $this->priority ?? 0,
            'created_at' => $this->created_at ?? Carbon::now(),
            'updated_at' => $this->updated_at ?? Carbon::now(),
        ];
    }
}
