<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Graduation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ImportStudentGraduateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.graduation.import');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls',
            ],
            'graduation_ceremony_id' => [
                'required',
                'exists:graduation_ceremonies,id',
                'integer',
            ],
        ];
    }
}
