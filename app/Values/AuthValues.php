<?php

declare(strict_types=1);

namespace App\Values;

class AuthValues
{
    public function __construct(
        private string|bool|null $accessToken,
        private string|null      $refreshToken,
    ) {
    }

    public function getAccessToken(): string|bool
    {
        return $this->accessToken;
    }

    public function setAccessToken(string|bool|null $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(string $refreshToken): void
    {
        $this->refreshToken = $refreshToken;
    }


}
