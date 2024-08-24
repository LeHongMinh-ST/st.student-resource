<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\SortOrder;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class ListRequest extends FormRequest
{
    /**
     * Get the model class for orderBy validation.
     *
     * @return string
     */
    abstract protected function getOrderByRuleModel(): string;
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order' => ['nullable', Rule::enum(SortOrder::class)],
            'orderBy' => ['nullable', Rule::in(app($this->getOrderByRuleModel())->getFillable())],
            'limit' => ['nullable', 'integer'],
            'page' => ['nullable', 'integer'],
            'search' => ['nullable', 'string'],
        ];
    }
}
