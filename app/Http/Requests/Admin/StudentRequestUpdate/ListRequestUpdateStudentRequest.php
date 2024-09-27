<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\StudentRequestUpdate;

use App\Http\Requests\ListRequest;
use App\Models\StudentInfoUpdate;
use Illuminate\Support\Facades\Gate;

class ListRequestUpdateStudentRequest extends ListRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.student-request.index');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

        ];
    }

    protected function getOrderByRuleModel(): string
    {
        return StudentInfoUpdate::class;
    }
}
