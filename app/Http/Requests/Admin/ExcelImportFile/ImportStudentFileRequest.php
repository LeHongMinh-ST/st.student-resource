<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\ExcelImportFile;

use App\Enums\ExcelImportType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ImportStudentFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.file.download_file_import_error');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls',
            ],
            'type' => [
                'required',
                'string',
                Rule::in(ExcelImportType::cases()),
            ],
            'entity_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
