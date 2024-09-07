<?php

declare(strict_types=1);

namespace App\Http\Requests\GeneralClass;

use App\Enums\ClassType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGeneralClassRequest extends FormRequest
{
    /**
     * Determine if the class is authorized to make this request.
     */
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
            'major_id' => [
                'nullable',
                'integer',
                Rule::requiredIf($this->type === ClassType::Major->value ||
                    ClassType::Major === $this->generalClass?->type),
            ],
        ];
    }
}
