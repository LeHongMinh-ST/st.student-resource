<?php

declare(strict_types=1);

namespace App\Factories\Post;

use App\DTO\Post\CreatePostDTO;
use App\Enums\AuthApiSection;
use App\Http\Requests\Admin\Post\StorePostRequest;

class CreatePostDTOFactory
{
    public static function make(StorePostRequest $request): CreatePostDTO
    {
        $command = new CreatePostDTO();
        $command->setTitle($request->get('title'));
        $command->setContent($request->get('content'));
        $command->setStatus($request->get('status'));
        $command->setUserId(auth(AuthApiSection::Admin->value)->id());
        return $command;
    }
}
