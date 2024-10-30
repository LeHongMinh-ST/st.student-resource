<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\TrainingIndustry;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateTrainingIndustryRequest extends FormRequest
{
    /**
     * Determine if the class is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.training-industry.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'code' => [
                'string',
                'nullable',
                'unique:training_industries,code,' . $this->trainingIndustry?->id . ',id',
            ],
            'name' => 'nullable',
        ];
    }
}
