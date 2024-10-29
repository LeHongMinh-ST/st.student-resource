<?php

declare(strict_types=1);

namespace App\Http\Resources\ExcelImportFile;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExcelImportFileResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? 0,
            'name' => $this->name ?? '',
            'type' => $this->type ?? '',
            'total_record' => $this->total_record ?? 0,
            'process_record' => $this->process_record ?? 0,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'file_errors_count' => $this->excel_import_file_errors_count ?? 0,
        ];
    }
}
