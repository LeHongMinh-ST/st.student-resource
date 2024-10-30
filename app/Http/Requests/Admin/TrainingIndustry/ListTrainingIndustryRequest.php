<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\TrainingIndustry;

use App\Enums\Status;
use App\Http\Requests\ListRequest;
use App\Models\Department;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ListTrainingIndustryRequest extends ListRequest
{
    public function authorize(): bool
    {
        return Gate::allows('admin.training-industry.index');
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
            'status' => [
                'nullable',
                Rule::in(Status::cases())
            ],
            ...parent::rules(),
        ];
    }

    protected function getOrderByRuleModel(): string
    {
        return Department::class;
    }
}
