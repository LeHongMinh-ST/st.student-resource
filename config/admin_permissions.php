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
        ],
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
        'show' => [
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
        'import' => [
            UserRole::Admin,
            UserRole::Office,
        ],
        'download-error-import' => [
            UserRole::Admin,
            UserRole::Office,
        ],
    ],
    'student-request' => [
        'index' => [
            UserRole::Admin,
            UserRole::Office,
            UserRole::Teacher,
        ],
        'update-status' => [
            UserRole::Admin,
            UserRole::Office,
            UserRole::Teacher,
        ],
    ],
    'post' => [
        'index' => [
            UserRole::Admin,
            UserRole::Office,
        ],
        'create' => [
            UserRole::Admin,
            UserRole::Office,
        ],
        'update' => [
            UserRole::Admin,
            UserRole::Office,
        ],
        'delete' => [
            UserRole::Admin,
            UserRole::Office,
        ],
        'show' => [
            UserRole::Admin,
            UserRole::Office,
            UserRole::Teacher,
        ],
        'list-publish' => [
            UserRole::Admin,
            UserRole::Office,
            UserRole::Teacher,
        ],
    ],
    'department' => [
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
    'graduation' => [
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
        'import' => [
            UserRole::Admin,
            UserRole::Office,
        ],
    ],
    'warning' => [
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
        'import' => [
            UserRole::Admin,
            UserRole::Office,
        ],
    ],
    'quit' => [
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
        'import' => [
            UserRole::Admin,
            UserRole::Office,
        ],
    ],
    'file' => [
        'index' => [
            UserRole::Admin,
        ],
        'download_file_import_template' => [
            UserRole::Admin,
        ],
        'download_file_import_error' => [
            UserRole::Admin,
        ],
    ],
    'training-industry' => [
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
    'survey-period' => [
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
    'employment-survey-response' => [
        'index' => [
            UserRole::Admin,
            UserRole::Office,
            UserRole::Teacher,
        ],
        'show' => [
            UserRole::Admin,
            UserRole::Office,
            UserRole::Teacher,
        ],
    ],
];
