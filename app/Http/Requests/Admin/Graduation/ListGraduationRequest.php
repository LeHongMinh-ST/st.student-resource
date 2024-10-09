<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Graduation;

use App\Http\Requests\ListRequest;
use App\Models\GraduationCeremony;
use Illuminate\Support\Facades\Gate;

class ListGraduationRequest extends ListRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.graduation.index');
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
        ];
    }

    protected function getOrderByRuleModel(): string
    {
        return GraduationCeremony::class;
    }
}
