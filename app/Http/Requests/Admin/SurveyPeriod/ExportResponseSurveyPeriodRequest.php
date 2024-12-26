<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\SurveyPeriod;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ExportResponseSurveyPeriodRequest extends FormRequest
{
    /**
     * Determine if the class is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.survey-period.index');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'student_ids' => [
                'array',
                Rule::requiredIf(null === $this->is_all_student),
            ],
            'is_all_student' => [
                Rule::requiredIf(null === $this->student_ids),
                'boolean',
            ],
        ];
    }
}
