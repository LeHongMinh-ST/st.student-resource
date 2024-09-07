<?php

declare(strict_types=1);

namespace App\Http\Requests\GeneralClass;

use App\Http\Requests\ListRequest;
use App\Models\GeneralClass;

class ListGeneralClassRequest extends ListRequest
{
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
            'q' => 'nullable',
            'status' => 'nullable',
            'teacher_id' => 'nullable',
            ...parent::rules(),
        ];
    }

    protected function getOrderByRuleModel(): string
    {
        return GeneralClass::class;
    }
}
