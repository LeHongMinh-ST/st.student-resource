<?php

declare(strict_types=1);

namespace App\Http\Requests\Student\EmploymentSurveyResponse;

use App\Models\SurveyPeriodStudent;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class VerifyInfoStudentResponseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
                function ($attribute, $value, $fail): void {
                    $isCheck = SurveyPeriodStudent::where('survey_period_id', $this->id)
                        ->whereHas('student', function ($query) use ($value): void {
                            $query->where('code', $value);
                        })
                        ->exists();
                    if (! $isCheck) {
                        $fail('Mã sinh viên không tồn tại trong danh sách sinh viên tham gia khảo sát');
                    }
                },
            ],
            'dob' => [
                'nullable',
                'date_format:d/m/Y',
            ],
            'training_industry_id' => [
                'nullable',
                'exists:training_industries,id',
            ],
            'email' => [
                'nullable',
                'email',
            ],
            'identification_card_number' => [
                'nullable',
                'string',
            ],
        ];
    }
}
