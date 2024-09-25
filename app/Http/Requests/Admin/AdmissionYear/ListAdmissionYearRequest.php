<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\AdmissionYear;

use App\Http\Requests\ListRequest;
use App\Models\AdmissionYear;
use Illuminate\Support\Facades\Gate;

class ListAdmissionYearRequest extends ListRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.admission.index');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'admission_year' => 'nullable',
            'school_year' => 'nullable',
            ...parent::rules(),
        ];
    }

    protected function getOrderByRuleModel(): string
    {
        return AdmissionYear::class;
    }
}
