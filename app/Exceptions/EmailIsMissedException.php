<?php

declare(strict_types=1);

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class EmailIsMissedException extends CoreException
{
    protected $code = SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR;

    protected $message = 'One of the Emails is missed, check your configs..';
}
