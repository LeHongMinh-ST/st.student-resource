<?php

declare(strict_types=1);

namespace App\Factories\User;

use App\DTO\User\CreateUserDTO;
use App\Enums\UserRole;
use App\Http\Requests\User\StoreUserRequest;
use App\Supports\AvatarHelper;
use App\Supports\Constants;
use App\Supports\ImageHelper;
use App\Supports\PasswordHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class CreateUserDTOFactory
{
    public static function make(StoreUserRequest $request): CreateUserDTO
    {
        // Create a new CreateUserDTO object
        $command = new CreateUserDTO();

        // Generate a password for the user, using a default password for production environments or a randomly generated one otherwise
        $password = PasswordHelper::makePassword();

        // Create an avatar for the user
        if ($request->hasFile('thumbnail') && $request->file('thumbnail') instanceof UploadedFile) {
            $path = ImageHelper::uploadImage(
                $request->file('thumbnail'),
                Constants::PATH_THUMBNAIL_ADMIN,
            );
        } else {
            $thumbnailFileName = uniqid() . $request->get('user_name') . Str::random(3);
            $path = AvatarHelper::createAvatar(
                $request->get('user_name'),
                Constants::PATH_THUMBNAIL_ADMIN,
                $thumbnailFileName
            );
        }

        // Set properties of the UserCreateCommand object based on the provided faculty and email (or generate a default email)
        $command->setUserName($request->get('user_name'));
        $command->setFirstName($request->get('first_name'));
        $command->setLastName($request->get('last_name'));
        $command->setPassword($password);
        $command->setEmail($request->get('email'));
        $command->setRole(UserRole::tryFrom($request->get('role')));
        $command->setFacultyId(auth()->user()->faculty_id);
        $command->setThumbnail($path);
        $command->setPhone($request->get('phone'));
        $command->setCode($request->get('code'));

        return $command;
    }
}
