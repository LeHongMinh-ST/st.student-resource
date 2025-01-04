<?php

declare(strict_types=1);

namespace App\Http\Requests\Student\RequestUpdateInfo;

use App\Enums\FamilyRelationship;
use App\Enums\SocialPolicyObject;
use App\Enums\TrainingType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequestUpdateStudentRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'person_email' => ['nullable', 'email', 'max:255'],
            'permanent_residence' => ['nullable', 'string', 'max:255'],
            'pob' => ['nullable', 'string', 'max:255'],
            'countryside' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'training_type' => ['nullable', Rule::enum(TrainingType::class)],
            'phone' => ['nullable', 'string', 'max:20'],
            'nationality' => ['nullable', 'string', 'max:255'],
            'citizen_identification' => ['nullable', 'string', 'max:255'],
            'ethnic' => ['nullable', 'string', 'max:255'],
            'religion' => ['nullable', 'string', 'max:255'],
            'social_policy_object' => ['nullable', 'string', Rule::enum(SocialPolicyObject::class)],
            'families' => ['array', 'nullable'],
            'families.*.relationship' => ['nullable', Rule::enum(FamilyRelationship::class)],
            'families.*.full_name' => ['nullable', 'string'],
            'families.*.job' => ['nullable', 'string'],
            'families.*.phone' => ['nullable', 'string'],
        ];
    }
}
