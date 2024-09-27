<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Post;

use App\Enums\PostStatus;
use App\Http\Requests\ListRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ListPublishPostRequest extends ListRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.post.list-publish');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'q' => 'nullable|string',
            'status' => ['nullable', Rule::enum(PostStatus::class)],
            ...parent::rules(),
        ];
    }

    protected function getOrderByRuleModel(): string
    {
        return Post::class;
    }
}
