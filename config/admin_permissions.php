<?php

declare(strict_types=1);

use App\Enums\UserRole;

return [
    'user' => [
        'index' => [
            UserRole::Admin,
            UserRole::Office,
            UserRole::Teacher,
        ],
        'create' => [
            UserRole::Admin,
        ],
        'update' => [
            UserRole::Admin,
        ],
        'destroy' => [
            UserRole::Admin,
        ],
    ],
    'class' => [
        'index' => [
            UserRole::Admin,
            UserRole::Office,
            UserRole::Teacher,
        ],
        'create' => [
            UserRole::Admin,
        ],
        'update' => [
            UserRole::Admin,
        ],
        'destroy' => [
            UserRole::Admin,
        ],
    ],
];
