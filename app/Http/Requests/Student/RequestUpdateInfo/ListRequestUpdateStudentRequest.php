<?php

declare(strict_types=1);

namespace App\Http\Requests\Student\RequestUpdateInfo;

use App\Enums\StudentRole;
use App\Http\Requests\ListRequest;
use App\Models\StudentInfoUpdate;
use App\Supports\AuthHelper;

class ListRequestUpdateStudentRequest extends ListRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return AuthHelper::isRoleStudent(StudentRole::President, StudentRole::VicePresident);
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
