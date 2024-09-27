<?php

declare(strict_types=1);

namespace App\Factories\Post;

use App\DTO\Post\ListPostDTO;
use App\Enums\PostStatus;
use App\Http\Requests\Admin\Post\ListPostRequest;
use App\Http\Requests\Admin\Post\ListPublishPostRequest;
use App\Supports\MakeDataHelper;

class ListPostDTOFactory
{
    public static function make(ListPostRequest|ListPublishPostRequest $request): ListPostDTO
    {
        $command  = new ListPostDTO();

        $command =  MakeDataHelper::makeListData($request, $command);

        if ($request->has('q')) {
            $command->setQ($request->get('q'));
        }

        if ($request->has('status')) {
            $command->setStatus(PostStatus::from($request->get('status')));
            if ($request instanceof ListPublishPostRequest) {
                $command->setStatus(PostStatus::Publish);
            }
        }

        return $command;
    }
}
