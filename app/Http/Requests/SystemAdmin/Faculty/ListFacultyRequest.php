<?php

declare(strict_types=1);

namespace App\Http\Requests\SystemAdmin\Faculty;

use App\Http\Requests\ListRequest;
use App\Models\Faculty;

class ListFacultyRequest extends ListRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'name' => 'nullable|string',
        ];
    }

    protected function getOrderByRuleModel(): string
    {
        return Faculty::class;
    }
}
