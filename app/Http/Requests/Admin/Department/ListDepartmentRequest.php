<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Department;

use App\Http\Requests\ListRequest;
use App\Models\Department;
use Illuminate\Support\Facades\Gate;

class ListDepartmentRequest extends ListRequest
{
    public function authorize(): bool
    {
        return Gate::allows('admin.department.index');
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
            'status' => 'nullable',
            'name' => 'nullable',
            'code' => 'nullable',
            ...parent::rules(),
        ];
    }

    protected function getOrderByRuleModel(): string
    {
        return Department::class;
    }
}
