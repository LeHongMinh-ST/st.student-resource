<?php

declare(strict_types=1);

namespace App\Services\StudentInfoRequest;

use App\Models\StudentInfoUpdate;

class StudentInfoUpdateService
{
    public function create(array $data): StudentInfoUpdate
    {
        return StudentInfoUpdate::create($data);
    }
}
