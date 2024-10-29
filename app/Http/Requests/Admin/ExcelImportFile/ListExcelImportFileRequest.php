<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\ExcelImportFile;

use App\Enums\ExcelImportType;
use App\Http\Requests\ListRequest;
use App\Models\ExcelImportFile;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ListExcelImportFileRequest extends ListRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.file.index');
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
                'nullable',
                Rule::in(ExcelImportType::cases()),
                Rule::requiredIf(null !== $this->entity_id),
            ],
            'entity_id' => 'nullable|integer',
            'faculty_id' => 'nullable|integer',
            ...parent::rules(),
        ];
    }

    protected function getOrderByRuleModel(): string
    {
        return ExcelImportFile::class;
    }
}
