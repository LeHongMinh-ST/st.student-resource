<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\ExcelImportFile;

use App\Enums\ExcelImportType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class DownloadExcelImportTemplateFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.file.download_file_import_template');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'type' => [
                'required',
                Rule::in(ExcelImportType::cases()),
            ],
        ];
    }
}
