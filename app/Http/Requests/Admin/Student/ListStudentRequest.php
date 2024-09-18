<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Student;

use App\Http\Requests\ListRequest;
use App\Models\Student;
use Illuminate\Support\Facades\Gate;

class ListStudentRequest extends ListRequest
{
    public function authorize(): bool
    {
        return Gate::allows('admin.student.index');
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
            'teacher_id' => 'nullable',
            'admission_year_id' => 'nullable',
            ...parent::rules(),
        ];
    }

    protected function getOrderByRuleModel(): string
    {
        return Student::class;
    }
}
