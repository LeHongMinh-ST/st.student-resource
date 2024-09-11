<?php

declare(strict_types=1);

namespace App\Factories\User;

use App\DTO\User\UpdateUserDTO;
use App\Enums\UserRole;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Supports\Constants;
use App\Supports\ImageHelper;
use Illuminate\Http\UploadedFile;

class UpdateUserDTOFactory
{
    public static function make(UpdateUserRequest $request, User $user): UpdateUserDTO
    {
        // Create a new CreateUserDTO object
        $command = new UpdateUserDTO();

        // Create an avatar for the user
        if ($request->hasFile('thumbnail') && $request->file('thumbnail') instanceof UploadedFile) {
            $path = ImageHelper::uploadImage(
                $request->file('thumbnail'),
                Constants::PATH_THUMBNAIL_ADMIN,
            );
            $command->setThumbnail($path);
        }

        // Set properties of the UserCreateCommand object based on the provided faculty and email (or generate a default email)
        $command->setId($user->id);
        $command->setFirstName($request->get('first_name', $user->first_name));
        $command->setLastName($request->get('last_name', $user->last_name));
        $command->setEmail($request->get('email', $user->email));
        $command->setRole($request->get('role') ? UserRole::tryFrom($request->get('role')) : $user->role);
        $command->setPhone($request->get('phone', $user->phone));
        $command->setCode($request->get('code', $user->code));

        return $command;
    }
}
