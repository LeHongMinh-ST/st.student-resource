<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\GeneralClass;

use App\Enums\AuthApiSection;
use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ShowGeneralClassRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        $auth = auth(AuthApiSection::Admin->value)->user();
        $class = $this->route('generalClass');
        if (UserRole::Teacher === $auth->role) {
            return Gate::allows('admin.class.index') && ($class->teacher_id === $auth->id || $class->sub_teacher_id === $auth->id);
        }

        return Gate::allows('admin.class.index');
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
}
