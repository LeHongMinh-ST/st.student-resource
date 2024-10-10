<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\StudentWarning;

use App\Http\Requests\ListRequest;
use App\Models\Warning;

class ListStudentWarningRequest extends ListRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'semester_id' => 'required|integer|exists:semesters,id',
            ...parent::rules(),
        ];
    }

    protected function getOrderByRuleModel(): string
    {
        return Warning::class;
    }
}
