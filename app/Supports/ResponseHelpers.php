<?php

declare(strict_types=1);

namespace App\Supports;

use App\Exceptions\CoreException;
use Illuminate\Http\JsonResponse;

class ResponseHelpers
{
    /**
     * Builds a JSON response for a given CoreException.
     * Provides detailed error information in non-production environments.
     *
     * @param  CoreException  $e  The exception to build the response for.
     * @return JsonResponse The JSON response containing the exception details.
     */
    public static function buildExceptionJson(CoreException $e): JsonResponse
    {
        // Check if the application is not in production
        if (! app()->isProduction()) {
            // Detailed response for non-production environments
            $response = [
                'message' => $e->getMessage(),
                'errors' => $e->getErrors(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace(),
            ];
        } else {
            // Simplified response for production environments
            $response = [
                'message' => $e->getMessage(),
                'errors' => $e->getErrors(),
            ];
        }

        // Return a JSON response with the appropriate status code
        return response()->json($response, (int) $e->getCode());
    }
}
