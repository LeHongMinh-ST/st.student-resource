<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\GeneralClass;

use App\Enums\ClassType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateGeneralClassRequest extends FormRequest
{
    /**
     * Determine if the class is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.class.update');
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
                'unique:classes,code,' . $this->generalClass?->id . ',id',
            ],
            'name' => 'nullable',
            'type' => [
                'nullable',
                Rule::in([ClassType::Major, ClassType::Basic]),
            ],
            'status' => 'nullable',
            'teacher_id' => 'nullable|integer',
            'sub_teacher_id' => 'nullable|integer',
        ];
    }
}
