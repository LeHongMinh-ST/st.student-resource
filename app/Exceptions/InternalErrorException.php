<?php

declare(strict_types=1);

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class InternalErrorException extends CoreException
{
    protected $code = SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR;

    protected $message = 'Something went wrong!';
}
