<?php

declare(strict_types=1);

namespace App\Http\Resources\Student;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExternalStudentResource extends JsonResource
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
            'full_name' => $this->last_name . ' ' . $this->first_name,
            'code' => $this->code,
            'training_industry_id' => $this->training_industry_id,
            'info' => new ExternalStudentInfoResource($this->whenLoaded('info')),
            'graduate' => $this->whenLoaded('graduationCeremonies', function () {
                $graduate = $this->graduationCeremonies->first();
                if (! $graduate) {
                    return null;
                }

                return [
                    'email' => $graduate->pivot->email,
                ];
            }),
        ];
    }
}
