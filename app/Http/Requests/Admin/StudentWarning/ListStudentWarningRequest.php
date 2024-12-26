<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\StudentWarning;

use App\Http\Requests\ListRequest;
use App\Models\Warning;
use Illuminate\Support\Facades\Gate;

class ListStudentWarningRequest extends ListRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.warning.index');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'semester_id' => 'nullable|integer',
            'school_id' => 'nullable|integer',
            ...parent::rules(),
        ];
    }

    protected function getOrderByRuleModel(): string
    {
        return Warning::class;
    }
}
