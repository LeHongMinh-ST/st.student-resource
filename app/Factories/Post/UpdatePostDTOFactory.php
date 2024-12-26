<?php

declare(strict_types=1);

namespace App\Factories\Post;

use App\DTO\Post\UpdatePostDTO;
use App\Http\Requests\Admin\Post\UpdatePostRequest;
use App\Models\Post;

class UpdatePostDTOFactory
{
    public static function make(UpdatePostRequest $request, Post $post): UpdatePostDTO
    {
        $command = new UpdatePostDTO();
        $command->setId($post->id);
        $command->setTitle($request->get('title'));
        $command->setContent($request->get('content'));
        $command->setStatus($request->get('status'));
        return $command;
    }
}
