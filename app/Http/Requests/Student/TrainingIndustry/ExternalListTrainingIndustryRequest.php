<?php

declare(strict_types=1);

namespace App\Http\Requests\Student\TrainingIndustry;

use App\Enums\Status;
use App\Http\Requests\ListRequest;
use App\Models\TrainingIndustry;
use Illuminate\Validation\Rule;

class ExternalListTrainingIndustryRequest extends ListRequest
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
            'q' => 'nullable',
            'faculty_id' => 'nullable|exists:faculties,id',
            'status' => [
                'nullable',
                Rule::in(Status::cases()),
            ],
            ...parent::rules(),
        ];
    }

    protected function getOrderByRuleModel(): string
    {
        return TrainingIndustry::class;
    }
}
