<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\SurveyPeriod;

use App\Enums\Status;
use App\Models\SurveyPeriodGraduation;
use Carbon\Carbon;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateSurveyPeriodRequest extends FormRequest
{
    /**
     * Determine if the class is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.survey-period.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $endDateCurrent = $this->surveyPeriod->end_time;

        return [
            'status' => [
                Rule::in(Status::cases()),
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (Status::Disable === $this->surveyPeriod->status) {
                        $fail('Không thể vô hiệu hóa đợt khảo sát tốt nghiệp.');
                    }
                },
            ],
            'start_date' => [
                'date_format:Y-m-d H:i',
                'before:end_date',
                'after:now',
                Rule::when(null === $this->end_date, [
                    'before:' . Carbon::parse($endDateCurrent)->format('Y-m-d H:i'),
                ]),
            ],
            'end_date' => [
                'date_format:Y-m-d H:i',
                'after:start_date',
            ],
            'graduation_ceremony_ids' => [
                'nullable',
                'array',
            ],
            'graduation_ceremony_ids.*' => [
                'required',
                'integer',
                function (string $attribute, mixed $value, Closure $fail): void {
                    $isExist = SurveyPeriodGraduation::query()
                        ->where('graduation_ceremony_id', $value)
                        ->where('survey_period_id', '<>', $this->surveyPeriod->id)
                        ->whereHas('surveyPeriod', function ($query): void {
                            $query->where('status', Status::Enable->value);
                        })
                        ->exists();

                    if ($isExist) {
                        $fail('Đợt tốt nghiệp ' . $attribute . ' đã có đợt khảo sát.');
                    }
                },
            ],
        ];
    }
}
