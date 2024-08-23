<?php

declare(strict_types=1);

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class InvalidTokenException extends CoreException
{
    protected $code = Response::HTTP_UNAUTHORIZED;

    protected $message = 'Invalid token.';
}
