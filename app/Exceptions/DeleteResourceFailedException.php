<?php

declare(strict_types=1);

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class DeleteResourceFailedException extends CoreException
{
    protected $code = Response::HTTP_EXPECTATION_FAILED;

    protected $message = 'Failed to delete Resource.';
}
