<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\GeneralClass;

use App\Http\Requests\ListRequest;
use App\Models\GeneralClass;
use Illuminate\Support\Facades\Gate;

class ListGeneralClassRequest extends ListRequest
{
    public function authorize(): bool
    {
        return Gate::allows('admin.class.index');
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
            'sub_teacher_id' => 'nullable',
            'type' => 'nullable',
            ...parent::rules(),
        ];
    }

    protected function getOrderByRuleModel(): string
    {
        return GeneralClass::class;
    }
}
