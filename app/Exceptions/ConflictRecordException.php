<?php

declare(strict_types=1);

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class ConflictRecordException extends CoreException
{
    protected $code = Response::HTTP_CONFLICT;

    protected $message = 'Record conflict';
}
