<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\SurveyPeriod;

use App\Enums\Status;
use App\Enums\SurveyPeriodType;
use App\Http\Requests\ListRequest;
use App\Models\SurveyPeriod;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ListSurveyPeriodRequest extends ListRequest
{
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
            'q' => 'nullable',
            'faculty_id' => 'nullable|integer',
            'type' => [
                'nullable',
                Rule::in(SurveyPeriodType::cases()),
            ],
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => [
                'nullable',
                Rule::in(Status::cases()),
            ],
            ...parent::rules(),
        ];
    }

    protected function getOrderByRuleModel(): string
    {
        return SurveyPeriod::class;
    }
}
