<?php

declare(strict_types=1);

namespace App\Factories\User;

use App\DTO\User\CreateUserDTO;
use App\Enums\UserRole;
use App\Models\Faculty;
use App\Supports\AvatarHelper;
use App\Supports\Constants;
use App\Supports\PasswordHelper;
use Illuminate\Support\Str;

class CreateUserFacultyDTOFactory
{
    public static function make(Faculty $faculty, ?string $email): CreateUserDTO
    {
        // Create a new UserCreateDTO object
        $dto = new CreateUserDTO();

        // Generate a password for the user, using a default password for production environments or a randomly generated one otherwise
        $password = PasswordHelper::makePassword();

        // Convert the faculty code to lowercase
        $facultyCode = mb_strtolower($faculty->code);

        // Create an avatar for the user
        $thumbnailFileName = uniqid() . "admin{$facultyCode}" . Str::random(3);
        $path = AvatarHelper::createAvatar(
            "Super Admin {$faculty->code}",
            Constants::PATH_THUMBNAIL_ADMIN,
            $thumbnailFileName
        );

        // Set properties of the UserCreateDTO object based on the provided faculty and email (or generate a default email)
        $dto->setUserName("admin{$facultyCode}");
        $dto->setFirstName("Admin {$faculty->code}");
        $dto->setLastName('Supper');
        $dto->setPassword($password);
        $dto->setEmail($email ?? "superadmin.{$facultyCode}@vnua.edu.vn");
        $dto->setRole(UserRole::Admin);
        $dto->setFacultyId($faculty->id);
        $dto->setThumbnail($path);

        return $dto;
    }
}
