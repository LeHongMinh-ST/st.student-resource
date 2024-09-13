<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\User;

use App\Http\Requests\ListRequest;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class ListUserRequest extends ListRequest
{
    public function authorize(): bool
    {
        return Gate::allows('admin.user.index');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'q' => 'nullable|string',
            'department_id' => 'nullable',
            'status' => 'nullable',
            ...parent::rules(),
        ];
    }

    protected function getOrderByRuleModel(): string
    {
        return User::class;
    }
}
