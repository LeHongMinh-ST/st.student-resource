<?php

declare(strict_types=1);

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class AccessDeniedException extends CoreException
{
    protected $code = Response::HTTP_FORBIDDEN;
    protected $message = 'This action is unauthorized.';
}
