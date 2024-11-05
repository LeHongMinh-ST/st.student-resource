<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\SurveyPeriod;

use App\Enums\Status;
use App\Enums\SurveyPeriodType;
use App\Models\SurveyPeriodGraduation;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreSurveyPeriodRequest extends FormRequest
{
    /**
     * Determine if the class is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.survey-period.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => 'required',
            'year' => [
                'required',
                'integer',
            ],
            'start_date' => [
                'required',
                'date_format:Y-m-d H:i',
                'before:end_date',
                'after:now',
            ],
            'end_date' => [
                'required',
                'date_format:Y-m-d H:i',
                'after:start_date',
            ],
            'graduation_ceremony_ids' => [
                'required',
                'array',
            ],
            'graduation_ceremony_ids.*' => [
                'required',
                'integer',
                function (string $attribute, mixed $value, Closure $fail): void {
                    $isExist = SurveyPeriodGraduation::query()
                        ->where('graduation_ceremony_id', $value)
                        ->whereHas('surveyPeriod', function ($query): void {
                            $query->where('status', Status::Enable->value);
                        })->exists();

                    if ($isExist) {
                        $fail('Đợt tốt nghiệp ' . $attribute . ' đã có đợt khảo sát.');
                    }
                },
            ],
            'type' => [
                'nullable',
                Rule::in(SurveyPeriodType::cases()),
            ],
            'status' => [
                'nullable',
                Rule::in(Status::cases()),
            ],
        ];
    }
}
