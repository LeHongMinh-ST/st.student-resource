<?php

declare(strict_types=1);

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class LockedException extends CoreException
{
    protected $code = Response::HTTP_LOCKED;
    protected $message = 'The resource being accessed is locked.';
}
