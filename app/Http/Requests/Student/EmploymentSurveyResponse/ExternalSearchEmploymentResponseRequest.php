<?php

declare(strict_types=1);

namespace App\Http\Requests\Student\EmploymentSurveyResponse;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExternalSearchEmploymentResponseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'student_code' => [
                'nullable',
            ],
            'survey_period_id' => [
                'required',
                'integer',
                Rule::exists('survey_periods', 'id')->where('status', Status::Enable),
            ],
        ];
    }
}
