<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (
            app()->environment(['production']) &&
            auth()->check() &&
            app()->bound('debugbar')
        ) {
            app('debugbar')->enable();
        }

        $response = $next($request);

        if (
            $response instanceof JsonResponse &&
            auth()->check() &&
            app()->bound('debugbar') &&
            app('debugbar')->isEnabled() &&
            is_object($response->getData())
        ) {
            $response->setData($response->getData(true) + [
                '_debugbar' => app('debugbar')->getData(),
            ]);
        }

        return $response;
    }
}
