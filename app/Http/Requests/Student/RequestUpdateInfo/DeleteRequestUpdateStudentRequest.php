<?php

declare(strict_types=1);

namespace App\Http\Requests\Student\RequestUpdateInfo;

use App\Supports\AuthHelper;
use Illuminate\Foundation\Http\FormRequest;

class DeleteRequestUpdateStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $studentInfoUpdate = $this->route('studentInfoUpdate');

        return AuthHelper::isStudentOwner($studentInfoUpdate->id);
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
