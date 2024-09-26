<?php

declare(strict_types=1);

use App\Enums\UserRole;

return [
    'admission' => [
        'index' => [
            UserRole::Admin,
            UserRole::Office,
            UserRole::Teacher,
        ],
        'getListImportFile' => [
            UserRole::Admin,
            UserRole::Office,
        ]
    ],
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
    'student' => [
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
    'student-request' => [
        'update-status' => [
            UserRole::Admin,
            UserRole::Office,
            UserRole::Teacher,
        ]
    ]
];
