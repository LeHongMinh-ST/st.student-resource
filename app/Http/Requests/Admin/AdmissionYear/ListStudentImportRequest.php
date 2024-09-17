<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\AdmissionYear;

use App\Http\Requests\ListRequest;
use App\Models\ExcelImportFile;
use Illuminate\Support\Facades\Gate;

class ListStudentImportRequest extends ListRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.admission.getListImportFile');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

        ];
    }

    protected function getOrderByRuleModel(): string
    {
        return ExcelImportFile::class;
    }
}
