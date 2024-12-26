<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Department;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreDepartmentRequest extends FormRequest
{
    /**
     * Determine if the class is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.department.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'code' => [
                'required',
                Rule::unique('departments', 'code'),
            ],
            'status' => [
                'nullable',
                Rule::in(Status::cases()),
            ],
        ];
    }
}
