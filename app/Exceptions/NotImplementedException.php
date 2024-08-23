<?php

declare(strict_types=1);

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class NotImplementedException extends CoreException
{
    protected $code = Response::HTTP_NOT_IMPLEMENTED;

    protected $message = 'This method is not yet implemented.';
}
