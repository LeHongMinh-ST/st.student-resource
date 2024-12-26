<?php

declare(strict_types=1);

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class NotFoundException extends CoreException
{
    protected $code = Response::HTTP_NOT_FOUND;

    protected $message = 'The requested resource could not be found.';
}
