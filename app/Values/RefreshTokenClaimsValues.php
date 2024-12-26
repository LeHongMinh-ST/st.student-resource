<?php

declare(strict_types=1);

namespace App\Values;

use App\Enums\AuthApiSection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

class RefreshTokenClaimsValues
{
    // Stores the user ID
    private int $userId;

    // Stores the expiration time of the refresh token
    private int $exp;

    // Stores the section of the API
    private string $section;

    // Constructor to initialize the object with the given API section
    public function __construct(AuthApiSection $apiSection)
    {
        $this->section = $apiSection->value; // Set the section value
        $this->userId = auth($this->section)->id() ?? 0; // Get the user ID from the authentication system
        $this->exp = Carbon::now()->timestamp + Config::get('jwt.refresh_ttl'); // Set the expiration time
    }

    // Static method to create an instance from an array of data
    public static function formArray(AuthApiSection $section, array $data): RefreshTokenClaimsValues
    {
        $instance = new self($section); // Create a new instance
        $instance->setUserId(@$data['user_id'] ?? 0); // Set the user ID from the array
        $instance->setExp(@$data['exp'] ?? 0); // Set the expiration time from the array

        return $instance; // Return the created instance
    }

    // Getter for the user ID
    public function getUserId(): int
    {
        return $this->userId;
    }

    // Setter for the user ID, accepting both int and string and converting to int
    public function setUserId(int|string $userId): void
    {
        $this->userId = (int) $userId;
    }

    // Getter for the expiration time
    public function getExp(): int
    {
        return $this->exp;
    }

    // Setter for the expiration time
    public function setExp(int $exp): void
    {
        $this->exp = $exp;
    }

    // Setter for the section value
    public function setSection(AuthApiSection $apiSection): void
    {
        $this->section = $apiSection->value;
    }

    // Getter for the section value
    public function getSection(): string
    {
        return $this->section;
    }

    // Convert the object properties to an array
    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'exp' => $this->exp,
            'section' => $this->section,
        ];
    }

    // Check if the token has expired
    public function isTimeOut(): bool
    {
        return $this->exp < Carbon::now()->timestamp;
    }

    // Check if the given section matches the object's section
    public function isSection(AuthApiSection $apiSection): bool
    {
        return $apiSection->value === $this->section;
    }
}
