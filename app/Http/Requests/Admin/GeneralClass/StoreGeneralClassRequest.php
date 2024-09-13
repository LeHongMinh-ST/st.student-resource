<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\GeneralClass;

use App\Enums\ClassType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreGeneralClassRequest extends FormRequest
{
    /**
     * Determine if the class is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.class.create');
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
            'type' => [
                'required',
                Rule::in([ClassType::Major, ClassType::Basic]),
            ],
            'code' => [
                'required',
                Rule::unique('classes', 'code'),
            ],
            'status' => 'nullable',
            'major_id' => [
                'nullable',
                Rule::requiredIf($this->type === ClassType::Major->value),
                'integer',
            ],
        ];
    }
}
